<?php

namespace App\Http\Controllers\Api\POSControllers\POSAdminController;

use App\Http\Controllers\Controller;
use App\Models\POSModel\Item;
use App\Models\POSModel\InventoryMovement;
use App\Models\POSModel\Order;
use App\Models\MagamentSystemModel\OrderAction;
use App\Models\MagamentSystemModel\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $admin = Auth::user();

        if (!$admin || $admin->role !== 'admin') {
            abort(403, 'Only admin can access this page.');
        }

        $tab = $request->get('tab', 'new');

        $query = Order::with(['user', 'items'])->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('order_no', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('items', function ($iq) use ($search) {
                        $iq->where('item_name', 'like', "%{$search}%")
                           ->orWhere('item_no', 'like', "%{$search}%");
                    });
            });
        }

        if ($tab === 'approved') {
            $query->where('status', 'confirmed');
        } else {
            $query->where('status', 'pending');
        }

        $orders = $query->paginate(10);
        $orders->appends($request->query());

        return view('POSViews.POSAdminViews.OrderList', compact('orders', 'tab'));
    }

    public function show($id)
    {
        $admin = Auth::user();

        if (!$admin || $admin->role !== 'admin') {
            abort(403, 'Only admin can access this page.');
        }

        $order = Order::with(['user', 'items'])->findOrFail($id);

        return view('POSViews.POSAdminViews.OrderDetail', compact('order'));
    }

    public function confirm($id)
    {
        $admin = Auth::user();

        if (!$admin || $admin->role !== 'admin') {
            return back()->with('error', 'Unauthorized.');
        }

        $order = Order::with(['user', 'items.item'])->find($id);

        if (!$order) {
            return back()->with('error', 'Order not found.');
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be confirmed.');
        }

        if (!$order->user) {
            return back()->with('error', 'Customer not found.');
        }

        if (empty($order->user->bc_customer_no)) {
            return back()->with('error', 'Customer BC number not found.');
        }

        if (!$order->items()->exists()) {
            return back()->with('error', 'Order has no items.');
        }

        DB::beginTransaction();

        try {
            $token = $this->getToken();

            if (!$token) {
                throw new \Exception('Failed to get Business Central access token.');
            }

            $orderResponse = Http::withoutVerifying()
                ->withToken($token)
                ->acceptJson()
                ->post($this->bcEndpoint('sales_orders_endpoint', 'salesOrders'), [
                    'customerNumber' => $order->user->bc_customer_no,
                ]);

            if (!$orderResponse->successful()) {
                throw new \Exception('Create BC sales order failed: ' . $orderResponse->body());
            }

            $salesOrderData = $orderResponse->json();
            $salesOrderId   = $salesOrderData['id'] ?? null;
            $salesOrderNo   = $salesOrderData['number'] ?? null;

            if (!$salesOrderId) {
                throw new \Exception('BC sales order ID not returned.');
            }

            foreach ($order->items as $item) {
                $discountPercent = $this->resolveDiscountPercent($item->item);
                $linePayload = [
                    'lineType'         => 'Item',
                    'lineObjectNumber' => $item->item_no,
                    'quantity'         => (int) $item->qty,
                ];

                if ($discountPercent > 0) {
                    $linePayload['discountPercent'] = round($discountPercent, 2);
                }

                $lineResponse = Http::withoutVerifying()
                    ->withToken($token)
                    ->acceptJson()
                    ->post(
                        $this->bcEndpoint(
                            'sales_order_lines_endpoint',
                            'salesOrders({salesOrderId})/salesOrderLines',
                            ['salesOrderId' => $salesOrderId]
                        ),
                        $linePayload
                    );

                if (
                    !$lineResponse->successful()
                    && array_key_exists('discountPercent', $linePayload)
                    && $this->isUnknownFieldError($lineResponse->body(), 'discountPercent')
                ) {
                    unset($linePayload['discountPercent']);
                    $lineResponse = Http::withoutVerifying()
                        ->withToken($token)
                        ->acceptJson()
                        ->post(
                            $this->bcEndpoint(
                                'sales_order_lines_endpoint',
                                'salesOrders({salesOrderId})/salesOrderLines',
                                ['salesOrderId' => $salesOrderId]
                            ),
                            $linePayload
                        );
                }

                if (!$lineResponse->successful()) {
                    throw new \Exception(
                        'Create BC sales order line failed for item [' . $item->item_no . ']: ' . $lineResponse->body()
                    );
                }
            }

            foreach ($order->items as $orderItem) {
                if (empty($orderItem->item_id)) {
                    throw new \Exception("Order item {$orderItem->id} is missing item reference.");
                }
            }

            $requestedQtyByItemId = $order->items
                ->groupBy('item_id')
                ->map(fn ($rows) => (int) $rows->sum('qty'))
                ->filter(fn ($qty) => $qty > 0);

            if ($requestedQtyByItemId->isNotEmpty()) {
                $lockedItems = Item::query()
                    ->whereIn('id', $requestedQtyByItemId->keys()->all())
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                foreach ($requestedQtyByItemId as $itemId => $requestedQty) {
                    $product = $lockedItems->get($itemId);

                    if (!$product) {
                        throw new \Exception("Item not found for stock update. Item ID: {$itemId}");
                    }

                    $availableQty = (int) ($product->inventory ?? 0);
                    if ($availableQty < $requestedQty) {
                        throw new \Exception(
                            "Insufficient stock for item {$product->number}. Requested {$requestedQty}, available {$availableQty}."
                        );
                    }
                }

                foreach ($requestedQtyByItemId as $itemId => $requestedQty) {
                    $product = $lockedItems->get($itemId);
                    if (!$product) {
                        continue;
                    }

                    $oldInventory = (int) ($product->inventory ?? 0);
                    $newInventory = $oldInventory - $requestedQty;

                    $product->decrement('inventory', $requestedQty);

                    InventoryMovement::create([
                        'company_id' => $order->company_id,
                        'item_id' => $product->id,
                        'order_id' => $order->id,
                        'actor_user_id' => $admin->id,
                        'buyer_user_id' => $order->user_id,
                        'source' => 'sale',
                        'quantity_change' => -$requestedQty,
                        'old_inventory' => $oldInventory,
                        'new_inventory' => $newInventory,
                        'happened_at' => now(),
                        'reference_no' => $order->order_no,
                        'note' => 'Inventory deducted after order confirmation.',
                    ]);
                }
            }

            $order->update([
                'status'         => 'confirmed',
                'sync_status'    => 'synced',
                'bc_document_no' => $salesOrderNo ?: null,
            ]);

            OrderAction::create([
                'order_id'    => $order->id,
                'user_id'     => $order->user_id,
                'action_by'   => $admin->id,
                'action_type' => 'confirmed',
                'status'      => 'confirmed',
                'note'        => 'Order confirmed by admin and stored in Business Central Sales Order.',
            ]);

            Notification::create([
                'user_id'  => $order->user_id,
                'order_id' => $order->id,
                'item_id'  => null,
                'type'     => 'order',
                'title'    => 'Order Confirmed',
                'message'  => 'Your order ' . $order->order_no . ' has been confirmed and stored in Sales Order.',
                'is_read'  => false,
            ]);

            DB::commit();

            return back()->with('success', 'Order confirmed and stored in BC Sales Order successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Confirm failed: ' . $e->getMessage());
        }
    }

  public function cancel(Request $request, $id)
{
    $admin = Auth::user();

    if (!$admin || $admin->role !== 'admin') {
        return back()->with('error', 'Unauthorized.');
    }

    $request->validate([
        'note' => ['required', 'string', 'max:1000'],
    ], [
        'note.required' => 'Please input reason before cancelling the order.',
    ]);

    $order = Order::with(['user', 'items'])->find($id);

    if (!$order) {
        return back()->with('error', 'Order not found.');
    }

    if ($order->status !== 'pending') {
        return back()->with('error', 'Only pending orders can be cancelled.');
    }

    DB::beginTransaction();

    try {
        $reason = trim($request->note);

        $order->update([
            'status' => 'cancelled',
        ]);

        OrderAction::create([
            'order_id'    => $order->id,
            'user_id'     => $order->user_id,
            'action_by'   => $admin->id,
            'action_type' => 'cancelled',
            'status'      => 'cancelled',
            'note'        => $reason,
        ]);

        Notification::create([
            'user_id'  => $order->user_id,
            'order_id' => $order->id,
            'item_id'  => null,
            'type'     => 'order',
            'title'    => 'Order Cancelled',
            'message'  => 'Your order ' . $order->order_no . ' has been cancelled by admin. Reason: ' . $reason,
            'is_read'  => false,
        ]);

        DB::commit();

        return redirect()
            ->route('admin.orders.index', ['tab' => 'new'])
            ->with('success', 'Order cancelled successfully.');
    } catch (\Throwable $e) {
        DB::rollBack();

        return back()->with('error', 'Cancel failed: ' . $e->getMessage());
    }
}

    private function isUnknownFieldError(string $responseBody, string $field): bool
    {
        return str_contains($responseBody, "property '{$field}' does not exist")
            || str_contains($responseBody, "property `{$field}` does not exist")
            || str_contains($responseBody, '"' . $field . '"')
                && str_contains(strtolower($responseBody), 'does not exist');
    }

    private function resolveDiscountPercent($item): float
    {
        if (!$item) {
            return 0.0;
        }

        $discount = max(0, (float) ($item->discount_amount ?? 0));
        if ($discount <= 0) {
            return 0.0;
        }

        $today = Carbon::today();
        $start = $item->discount_start_date ? Carbon::parse($item->discount_start_date)->startOfDay() : null;
        $end = $item->discount_end_date ? Carbon::parse($item->discount_end_date)->endOfDay() : null;

        if ($start && $today->lt($start)) {
            return 0.0;
        }

        if ($end && $today->gt($end)) {
            return 0.0;
        }

        return min(100, $discount);
    }
}
