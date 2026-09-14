<?php

namespace App\Http\Controllers\Api\POS\Admin\Orders;

use App\Http\Controllers\Controller;
use App\Models\POS\Item;
use App\Models\POS\InventoryMovement;
use App\Models\POS\Order;
use App\Models\POS\NumberSeries;
use App\Models\ManagementSystem\OrderAction;
use App\Models\ManagementSystem\Notification;
use App\Http\Controllers\Api\POS\Reports\OrderReportController;
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
        /** @var \App\Models\ManagementSystem\User|null $admin */
        $admin = Auth::user();

        if (!$admin || (!$admin->isAdmin() && !$admin->hasPermission('orders'))) {
            abort(403, 'You do not have access to this page.');
        }

        $tab = $request->get('tab', 'new');
        $companyId = session('selected_company_id');

        $query = Order::with(['user', 'items', 'actions.actionBy'])
            ->when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('order_no', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('items', function ($iq) use ($search) {
                        $iq->where('item_name', 'like', "%{$search}%")
                            ->orWhere('item_no', 'like', "%{$search}%");
                    });
            });
        }
        if ($request->filled('date')) {
            $date = $request->date;
            $query->where(function ($q) use ($date) {
                $q->whereDate('checked_out_at', $date)
                    ->orWhereDate('created_at', $date);
            });
        }
        if ($request->filled('customer_id')) {
            $query->where('user_id', $request->get('customer_id'));
        }

        // Used by the admin profile's "Orders Approved" / "Customers Served"
        // lists — restrict to orders *this* admin personally approved,
        // rather than every approved order in the system.
        if ($request->filled('approved_by')) {
            $approvedBy = $request->get('approved_by');
            $query->whereHas('actions', function ($aq) use ($approvedBy) {
                $aq->where('action_by', $approvedBy)
                    ->whereIn('action_type', ['confirmed', 'approved']);
            });
        }

        if ($tab === 'approved') {
            $query->where('status', 'confirmed');
        } else {
            $query->where('status', 'pending');
        }

        $orders = $query->paginate(10);
        $orders->appends($request->query());

        $newOrdersCount = Order::when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->where('status', 'pending')->count();
        $approvedOrdersCount = Order::when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->where('status', 'confirmed')->count();

        // Lets the view show a "showing orders you approved for X" banner
        // instead of silently filtering with no explanation.
        $activityFilterCustomerName = null;
        if ($request->filled('customer_id')) {
            $activityFilterCustomerName = optional(
                \App\Models\ManagementSystem\User::find($request->get('customer_id'))
            )->name;
        }
        $activityFilterIsMine = $request->filled('approved_by')
            && (int) $request->get('approved_by') === (int) $admin->id;

        return view('POSViews.POSAdminViews.Orders.index', compact(
            'orders',
            'tab',
            'newOrdersCount',
            'approvedOrdersCount',
            'activityFilterCustomerName',
            'activityFilterIsMine'
        ));
    }

    public function show($id)
    {
        /** @var \App\Models\ManagementSystem\User|null $admin */
        $admin = Auth::user();

        if (!$admin || (!$admin->isAdmin() && !$admin->hasPermission('orders'))) {
            abort(403, 'You do not have access to this page.');
        }

        $order = Order::with(['user', 'items.item', 'items.itemVariant'])
            ->when(session('selected_company_id'), fn($q) => $q->where('company_id', session('selected_company_id')))
            ->findOrFail($id);

        return view('POSViews.POSAdminViews.Orders.show', compact('order'));
    }

    public function confirm($id)
    {
        /** @var \App\Models\ManagementSystem\User|null $admin */
        $admin = Auth::user();

        if (!$admin || (!$admin->isAdmin() && !$admin->hasPermission('orders'))) {
            return back()->with('error', 'Unauthorized.');
        }

        $order = Order::with(['user', 'items.item', 'items.itemVariant'])
            ->when(session('selected_company_id'), fn($q) => $q->where('company_id', session('selected_company_id')))
            ->find($id);

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
                    'sellToCustomerNo'   => $order->user->bc_customer_no,
                    'orderDate'          => now()->toDateString(),
                    'locationCode'       => $order->location_code ?? '',
                    'externalDocumentNo' => $order->order_no,
                ]);

            if (!$orderResponse->successful()) {
                throw new \Exception('Create BC sales order failed: ' . $orderResponse->body());
            }

            $salesOrderData = $orderResponse->json();
            $salesOrderId   = $salesOrderData['id'] ?? null;
            $salesOrderNo   = $salesOrderData['number'] ?? $salesOrderData['no'] ?? null;

            if (!$salesOrderId) {
                throw new \Exception('BC sales order ID not returned.');
            }
            foreach ($orderItems as $item) {
                $discountPercent = $this->resolveDiscountPercent($item->item);
                $variantCode = optional($item->itemVariant)->code ?? '';
                $linePayload = [
                    'lineType'         => 'Item',
                    'lineObjectNumber' => $item->item_no,
                    'quantity'         => (float) $item->qty,
                    'unitPrice'       => 0,
                    'locationCode'    => '',
                    'discountPercent' => round($discountPercent, 2),
                    'variantCode'     => $variantCode,
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
                ->map(fn($rows) => round((float) $rows->sum('qty'), 2))
                ->filter(fn($qty) => $qty > 0);

            $outOfStockItems = collect();

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

                    $availableQty = (float) ($product->inventory ?? 0);
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

                    $oldInventory = (float) ($product->inventory ?? 0);
                    $newInventory = $oldInventory - $requestedQty;

                    if ($newInventory <= 0) {
                        $outOfStockItems->push($product);
                    }

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
                'entry_no'    => $this->issueEntryNo($order->company_id),
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

            foreach ($outOfStockItems as $outOfStockItem) {
                $this->notifyOutOfStock($outOfStockItem);
            }

            // Pre-render the order's PDF report now, while an admin is
            // already waiting on this request's BC round-trips, instead of
            // making whoever opens the report next pay dompdf's render cost.
            app(OrderReportController::class)->warmCache($order);

            return back()->with('success', 'Order confirmed and stored in BC Sales Order successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Confirm failed: ' . $e->getMessage());
        }
    }

    /**
     * Best-effort entry number for the Approval Entries log — if no "ENTRY"
     * number series is set up yet for this company, entries are simply
     * logged without one rather than blocking the order confirm/cancel.
     */
    private function issueEntryNo(int $companyId): ?string
    {
        try {
            return DB::transaction(fn() => NumberSeries::issue($companyId, 'ENTRY'));
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function cancel(Request $request, $id)
    {
        /** @var \App\Models\ManagementSystem\User|null $admin */
        $admin = Auth::user();

        if (!$admin || (!$admin->isAdmin() && !$admin->hasPermission('orders'))) {
            return back()->with('error', 'Unauthorized.');
        }

        $request->validate([
            'note' => ['required', 'string', 'max:1000'],
        ], [
            'note.required' => 'Please input reason before cancelling the order.',
        ]);

        $order = Order::with(['user', 'items'])
            ->when(session('selected_company_id'), fn($q) => $q->where('company_id', session('selected_company_id')))
            ->find($id);

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
                'entry_no'    => $this->issueEntryNo($order->company_id),
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
        /** @var \App\Models\ManagementSystem\User|null $admin */
        $admin = Auth::user();

        if (!$admin || (!$admin->isAdmin() && !$admin->hasPermission('orders'))) {
            abort(403, 'You do not have access to this page.');
        }

        $actions = OrderAction::with(['order', 'user', 'actionBy'])
            ->when(session('selected_company_id'), function ($q) {
                $q->whereHas('order', fn($oq) => $oq->where('company_id', session('selected_company_id')));
            })
            ->latest()
            ->paginate(20);

        return view('POSViews.POSAdminViews.Orders.actions', compact('actions'));
    }
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
                // Custom [ServiceEnabled] bound action — all 6 params always required
                'endpoint' => $this->bcUrl("salesOrders({$salesOrderId})/Microsoft.NAV.addLine"),
                'payload'  => $this->serviceEnabledAddLinePayload($linePayload),
                'originalPayload' => $linePayload, // Keep original for logging
            ],
        ];

        // Add generic fallback attempts
        $attempts[] = [
            'endpoint' => $configuredEndpoint,
            'payload'  => $configuredPayload,
            'originalPayload' => $linePayload,
        ];
        $attempts[] = [
            'endpoint' => $this->bcUrl('salesOrderLines'),
            'payload'  => array_merge(['documentId' => $salesOrderId], $linePayload),
            'originalPayload' => $linePayload,
        ];
        $attempts[] = [
            'endpoint' => $this->bcUrl('salesOrderLines'),
            'payload'  => array_merge(['salesOrderId' => $salesOrderId], $linePayload),
            'originalPayload' => $linePayload,
        ];

        if ($salesOrderNo) {
            $attempts[] = [
                'endpoint' => $this->bcUrl('salesOrderLines'),
                'payload'  => array_merge(['documentNo' => $salesOrderNo], $linePayload),
                'originalPayload' => $linePayload,
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
                // Check if fields were dropped before logging success
                $this->logFieldDropIfNeeded($attempt['originalPayload'], $attempt['payload'], $attempt['endpoint']);
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
                    // Check if fields were dropped before logging success
                    $this->logFieldDropIfNeeded($attempt['originalPayload'], $payloadWithoutDiscount, $attempt['endpoint']);
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

    private function serviceEnabledAddLinePayload(array $linePayload): array
    {
        return [
            'itemNo'          => (string)  ($linePayload['lineObjectNumber'] ?? ''),
            'quantity'        => (float)   ($linePayload['quantity']         ?? 0),
            'unitPrice'       => (float)   ($linePayload['unitPrice']        ?? 0),   // always send
            'locationCode'    => (string)  ($linePayload['locationCode']     ?? ''),  // always send
            'discountPercent' => (float)   ($linePayload['discountPercent']  ?? 0),   // always send
            'variantCode'     => (string)  ($linePayload['variantCode']     ?? ''),  // always send
        ];
    }

    /**
     * Fires once an item's inventory is actually decremented to zero (or
     * below) by a confirmed order — the hard "truly out of stock" case,
     * distinct from the softer "pending demand is nearly there" warning
     * created at checkout time (OrderController::notifyLowStockIfNeeded).
     */
    private function notifyOutOfStock(Item $item): void
    {
        $alreadyAlerted = Notification::where('item_id', $item->id)
            ->where('type', 'out_of_stock')
            ->where('is_read', false)
            ->exists();

        if ($alreadyAlerted) {
            return;
        }

        Notification::create([
            'user_id' => null,
            'order_id' => null,
            'item_id' => $item->id,
            'type' => 'out_of_stock',
            'title' => 'Out of stock: ' . $item->display_name,
            'message' => "\"{$item->display_name}\" just sold out (0 units remaining) after an order was confirmed.",
            'is_group_summary' => true,
            'unread_count' => 1,
            'is_read' => false,
        ]);
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

    /**
     * Log when a line insert succeeds but critical fields were dropped from the payload.
     * This ensures we don't silently lose discountPercent or variantCode when falling back
     * to endpoints that don't support these required fields.
     */
    private function logFieldDropIfNeeded(array $originalPayload, array $actualPayload, string $endpoint): void
    {
        // Check if discount was present in original but missing in actual
        $discountDropped = (
            array_key_exists('discountPercent', $originalPayload)
            && $originalPayload['discountPercent'] > 0
            && (!array_key_exists('discountPercent', $actualPayload) || $actualPayload['discountPercent'] == 0)
        );

        // Check if variantCode was present in original but missing or empty in actual
        $variantDropped = (
            array_key_exists('variantCode', $originalPayload)
            && $originalPayload['variantCode'] !== ''
            && (!array_key_exists('variantCode', $actualPayload) || $actualPayload['variantCode'] == '')
        );

        if ($discountDropped || $variantDropped) {
            $logMessage = 'Line insert succeeded but fields were dropped: ';
            if ($discountDropped) $logMessage .= 'discountPercent ';
            if ($variantDropped) $logMessage .= 'variantCode ';
            $logMessage .= 'in endpoint: ' . $endpoint;
            logger()->warning($logMessage);
        }
    }
}
