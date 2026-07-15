<?php

namespace App\Http\Controllers\Api\POS\Admin\Orders;

use App\Http\Controllers\Controller;
use App\Models\POS\Item;
use App\Models\POS\InventoryMovement;
use App\Models\POS\Order;
use App\Models\ManagementSystem\OrderAction;
use App\Models\ManagementSystem\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
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

        return view('POSViews.POSAdminViews.Orders.index', compact('orders', 'tab'));
    }

    public function show($id)
    {
        $admin = Auth::user();

        if (!$admin || $admin->role !== 'admin') {
            abort(403, 'Only admin can access this page.');
        }

        $order = Order::with(['user', 'items'])->findOrFail($id);

        return view('POSViews.POSAdminViews.Orders.show', compact('order'));
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

        $orderItems = $order->items()->get();

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
                    'sellToCustomerNo' => $order->user->bc_customer_no,
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

            foreach ($orderItems as $item) {
                $discountPercent = $this->resolveDiscountPercent($item->item);

                $linePayload = [
                    'lineType'         => 'Item',
                    'lineObjectNumber' => $item->item_no,
                    'quantity'         => (int) $item->qty,
                    'discountPercent'  => round($discountPercent, 2), // always send, 0 = no discount
                ];

                $lineResponse = $this->createBusinessCentralSalesOrderLine(
                    $token,
                    $salesOrderId,
                    $salesOrderNo,
                    $linePayload
                );

                if (!$lineResponse->successful()) {
                    throw new \Exception(
                        'Create BC sales order line failed for item [' . $item->item_no . ']: ' . $lineResponse->body()
                    );
                }
            }

            foreach ($orderItems as $orderItem) {
                if (empty($orderItem->item_id)) {
                    throw new \Exception("Order item {$orderItem->id} is missing item reference.");
                }
            }

            $requestedQtyByItemId = $orderItems
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
                        'company_id'      => $order->company_id,
                        'item_id'         => $product->id,
                        'order_id'        => $order->id,
                        'actor_user_id'   => $admin->id,
                        'buyer_user_id'   => $order->user_id,
                        'source'          => 'sale',
                        'quantity_change' => -$requestedQty,
                        'old_inventory'   => $oldInventory,
                        'new_inventory'   => $newInventory,
                        'happened_at'     => now(),
                        'reference_no'    => $order->order_no,
                        'note'            => 'Inventory deducted after order confirmation.',
                    ]);
                }
            }

            $orderUpdates = [
                'status'         => 'confirmed',
                'sync_status'    => 'synced',
                'bc_document_no' => $salesOrderNo ?: null,
            ];

            if (Schema::hasColumn('orders', 'bc_order_id')) {
                $orderUpdates['bc_order_id'] = $salesOrderId;
            }

            $order->update($orderUpdates);

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
                'status'      => 'cancelled',
                'sync_status' => 'cancelled',
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

    public function actionHistory()
    {
        $admin = Auth::user();

        if (!$admin || $admin->role !== 'admin') {
            abort(403, 'Only admin can access this page.');
        }

        $actions = OrderAction::with(['order', 'user', 'actionBy'])
            ->latest()
            ->paginate(20);

        return view('POSViews.POSAdminViews.Orders.actions', compact('actions'));
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private function createBusinessCentralSalesOrderLine(
        string $token,
        string $salesOrderId,
        ?string $salesOrderNo,
        array $linePayload
    ) {
        $configuredEndpoint = $this->bcEndpoint(
            'sales_order_lines_endpoint',
            'salesOrders({salesOrderId})/salesOrderLines',
            ['salesOrderId' => $salesOrderId]
        );

        $configuredPayload = $linePayload;
        if ($configuredEndpoint && !str_contains($configuredEndpoint, '/salesOrders(')) {
            $configuredPayload = array_merge(['documentId' => $salesOrderId], $linePayload);
        }

        $attempts = [
            [
                'endpoint' => $configuredEndpoint,
                'payload'  => $configuredPayload,
            ],
            [
                'endpoint' => $this->bcUrl('salesOrderLines'),
                'payload'  => array_merge(['documentId' => $salesOrderId], $linePayload),
            ],
            [
                'endpoint' => $this->bcUrl('salesOrderLines'),
                'payload'  => array_merge(['salesOrderId' => $salesOrderId], $linePayload),
            ],
            [
                // Custom [ServiceEnabled] bound action — all 5 params always required
                'endpoint' => $this->bcUrl("salesOrders({$salesOrderId})/Microsoft.NAV.addLine"),
                'payload'  => $this->serviceEnabledAddLinePayload($linePayload),
            ],
        ];

        if ($salesOrderNo) {
            $attempts[] = [
                'endpoint' => $this->bcUrl('salesOrderLines'),
                'payload'  => array_merge(['documentNo' => $salesOrderNo], $linePayload),
            ];
        }

        $lastResponse = null;

        foreach ($attempts as $attempt) {
            if (empty($attempt['endpoint'])) {
                continue;
            }

            $lastResponse = $this->postBusinessCentralSalesOrderLine(
                $token,
                $attempt['endpoint'],
                $attempt['payload']
            );

            if ($lastResponse->successful()) {
                return $lastResponse;
            }

            // Endpoint not found — try next attempt
            if ($this->isNotFoundResponse($lastResponse->body())) {
                continue;
            }

            // discountPercent not supported on this endpoint — retry without it
            if (
                array_key_exists('discountPercent', $attempt['payload'])
                && $this->isUnknownFieldError($lastResponse->body(), 'discountPercent')
            ) {
                $payloadWithoutDiscount = $attempt['payload'];
                unset($payloadWithoutDiscount['discountPercent']);

                $lastResponse = $this->postBusinessCentralSalesOrderLine(
                    $token,
                    $attempt['endpoint'],
                    $payloadWithoutDiscount
                );

                if ($lastResponse->successful()) {
                    return $lastResponse;
                }
            }

            // documentId / salesOrderId / documentNo not recognised — try next attempt
            if (!$this->isUnknownDocumentLinkFieldResponse($lastResponse->body())) {
                return $lastResponse;
            }
        }

        return $lastResponse;
    }

    private function postBusinessCentralSalesOrderLine(string $token, string $endpoint, array $payload)
    {
        return Http::withoutVerifying()
            ->withToken($token)
            ->acceptJson()
            ->post($endpoint, $payload);
    }

    /**
     * Build the payload for the custom [ServiceEnabled] AddLine bound action.
     *
     * ALL five parameters declared in the AL signature must always be present.
     * BC rejects the call with "Expected a parameter with name 'x'" if any is missing.
     *
     *   itemNo          Code[20]   — item number, required
     *   quantity        Decimal    — must be > 0
     *   unitPrice       Decimal    — 0 = let BC resolve from price list
     *   locationCode    Code[10]   — '' = use order-level location
     *   discountPercent Decimal    — 0 = no discount
     */
    private function serviceEnabledAddLinePayload(array $linePayload): array
    {
        return [
            'itemNo'          => (string)  ($linePayload['lineObjectNumber'] ?? ''),
            'quantity'        => (float)   ($linePayload['quantity']         ?? 0),
            'unitPrice'       => (float)   ($linePayload['unitPrice']        ?? 0),   // always send
            'locationCode'    => (string)  ($linePayload['locationCode']     ?? ''),  // always send
            'discountPercent' => (float)   ($linePayload['discountPercent']  ?? 0),   // always send
        ];
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
        $end   = $item->discount_end_date   ? Carbon::parse($item->discount_end_date)->endOfDay()   : null;

        if ($start && $today->lt($start)) {
            return 0.0;
        }

        if ($end && $today->gt($end)) {
            return 0.0;
        }

        return min(100, $discount);
    }

    private function isNotFoundResponse(string $responseBody): bool
    {
        return str_contains($responseBody, 'BadRequest_NotFound')
            || str_contains($responseBody, 'No HTTP resource was found');
    }

    private function isUnknownFieldError(string $responseBody, string $field): bool
    {
        return str_contains($responseBody, "property '{$field}' does not exist")
            || str_contains($responseBody, "property `{$field}` does not exist")
            || (str_contains($responseBody, '"' . $field . '"')
                && str_contains(strtolower($responseBody), 'does not exist'));
    }

    private function isUnknownDocumentLinkFieldResponse(string $responseBody): bool
    {
        foreach (['documentId', 'salesOrderId', 'documentNo'] as $field) {
            if ($this->isUnknownFieldError($responseBody, $field)) {
                return true;
            }
        }

        return false;
    }
}
