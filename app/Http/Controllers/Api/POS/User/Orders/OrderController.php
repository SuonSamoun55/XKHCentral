<?php

namespace App\Http\Controllers\Api\POS\User\Orders;

use App\Http\Controllers\Controller;
use App\Models\POS\{Cart, Order, OrderItem, OrderHistory, Item};
use App\Models\ManagementSystem\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log};
use Illuminate\Support\Str;
use Carbon\Carbon;

class OrderController extends Controller
{
    private function user()
    {
        return Auth::user() ?? abort(response()->json([
            'success' => false,
            'message' => 'Unauthenticated'
        ], 401));
    }

    private function orders()
    {
        return Order::where('user_id', auth()->id());
    }

    public function history(Request $r)
    {
        return response()->json([
            'success' => true,
            'data' => $this->orders()
                ->with('items')
                ->when($r->status && $r->status != 'all', fn($q) =>
                    $q->where('status', strtolower(str_replace(' ', '-', $r->status)))
                )
                ->latest()
                ->paginate($r->limit ?? 10),
        ]);
    }

    public function checkout(Request $r)
    {
        $user = $this->user();

        $cart = Cart::with('items.item', 'items.itemVariant')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return $this->fail('Cart is empty');
        }

        // The customer's own company, not an arbitrary/admin-session one —
        // checkout doesn't run inside the admin panel's tenant context.
        $companyId = $user->company_id;
        if (!$companyId) return $this->fail('Your account is not linked to a company.');

        DB::beginTransaction();

        try {
            [$subtotal, $discount, $tax] = $this->calculateTotals($cart, $companyId);
            $totalAmount = ($subtotal - $discount) + $tax;

            $order = Order::create([
                'company_id' => $companyId,
                'order_no' => 'ORD-' . now()->format('YmdHis') . Str::upper(Str::random(4)),
                'user_id' => $user->id,
                'customer_no' => $user->bc_customer_no,
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'total_amount' => $totalAmount,
                'amount_paid' => $totalAmount,
                'status' => 'pending',
            ]);

            $this->createItems($cart, $order, $companyId);
            $this->createHistory($cart, $order);

            $itemIdsInOrder = $cart->items->pluck('item_id')->unique();

            $cart->items()->delete();
            $cart->update(['status' => 'completed']);

            DB::commit();

            foreach ($itemIdsInOrder as $itemId) {
                $this->notifyLowStockIfNeeded($itemId);
            }

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'order_no' => $order->order_no,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return $this->fail('Checkout failed');
        }
    }

    private function calculateTotals($cart, $companyId)
    {
        $subtotal = $discount = $tax = 0;

        foreach ($cart->items as $i) {
            $item = $i->item;

            if (!$item || $item->company_id != $companyId) {
                throw new \Exception("Invalid item");
            }

            $line = $this->calculateLinePricing($item, (int) ($i->qty ?? 0));

            $subtotal += $line['subtotal'];
            $discount += $line['discount_amount'];
            $tax += $line['tax_amount'];
        }

        return [$subtotal, $discount, $tax];
    }

    private function createItems($cart, $order, $companyId)
    {
        foreach ($cart->items as $i) {
            $item = $i->item;
            $line = $this->calculateLinePricing($item, (int) ($i->qty ?? 0));

            OrderItem::create([
                'order_id' => $order->id,
                'company_id' => $companyId,
                'item_id' => $i->item_id,
                'item_variant_id' => $i->item_variant_id,
                'item_no' => $i->item->number,
                'item_name' => $i->item->display_name,
                'variant_description' => $i->itemVariant->description ?? null,
                'qty' => $i->qty,
                'unit_price' => $i->unit_price,
                'discount_percent' => $line['discount_percent'],
                'discount_amount' => $line['discount_amount'],
                'tax_amount' => $line['tax_amount'],
                'line_total' => $line['line_total'],
            ]);
        }
    }

    private function createHistory($cart, $order)
    {
        OrderHistory::create([
            'user_id' => auth()->id(),
            'order_no' => $order->order_no,
            'total_amount' => $order->total_amount,
            'status' => 'pending',
            'items_summary' => json_encode($cart->items),
        ]);
    }

    private function fail($msg)
    {
        return response()->json([
            'success' => false,
            'message' => $msg
        ], 422);
    }

    /**
     * Same pending-vs-stock risk classification used on the admin Product
     * Detail page (StoreManagementController::productDetail): warn once
     * pending demand reaches 80% of stock, escalate once it would oversell
     * outright. Fires an admin notification the moment a fresh checkout
     * pushes an item into that zone, instead of waiting for an admin to
     * happen to open the product page.
     */
    private function notifyLowStockIfNeeded($itemId): void
    {
        $item = Item::find($itemId);
        if (!$item) {
            return;
        }

        $stock = (int) $item->inventory;

        $pendingQty = (int) OrderItem::query()
            ->from('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->where('oi.item_id', $itemId)
            ->where('o.status', 'pending')
            ->sum('oi.qty');

        if ($pendingQty <= 0) {
            return;
        }

        $level = null;
        if ($pendingQty >= $stock) {
            $level = 'critical';
        } elseif ($pendingQty >= 0.8 * $stock) {
            $level = 'warning';
        }

        if (!$level) {
            return;
        }

        // Don't spam a fresh notification on every checkout while an
        // existing alert for this item is still unread/unresolved.
        $alreadyAlerted = Notification::where('item_id', $item->id)
            ->where('type', 'out_of_stock')
            ->where('is_read', false)
            ->exists();

        if ($alreadyAlerted) {
            return;
        }

        $title = $level === 'critical'
            ? 'Pending demand will oversell: ' . $item->display_name
            : 'Nearly out of stock: ' . $item->display_name;

        $message = $level === 'critical'
            ? "{$pendingQty} units of \"{$item->display_name}\" are tied up in pending orders, but only {$stock} are in stock. Confirming all pending orders will oversell this item — review pending orders before approving."
            : "{$pendingQty} of {$stock} units in stock for \"{$item->display_name}\" are already claimed by pending orders — worth checking on before approving more.";

        Notification::create([
            'user_id' => null,
            'order_id' => null,
            'item_id' => $item->id,
            'type' => 'out_of_stock',
            'title' => $title,
            'message' => $message,
            'is_group_summary' => true,
            'unread_count' => 1,
            'is_read' => false,
        ]);
    }

    public function success(Request $r)
    {
        $order = Order::where('id', $r->order)
            ->where('user_id', auth()->id())
            ->first();

        if (!$order) return redirect('/pos-system/cart');

        return view('POSViews.POSUserViews.Cart.index', [
            'showOrderSuccess' => true,
            'orderId' => $order->id,
            'orderNumber' => $order->order_no,
            'amountPaid' => $order->amount_paid,
        ]);
    }

    public function detail($id)
    {
        $order = Order::with('items.item', 'items.itemVariant')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $cart = Cart::with('items.item')
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->first();

        return view('POSViews.POSUserViews.Cart.index', [
            'cart' => $cart,
            'orderDetail' => $order,
            'showOrderDetail' => true,
        ]);
    }

    /**
     * Same pricing logic used in CartController::calculateLinePricing()
     * so cart totals and order totals never diverge.
     */
    private function calculateLinePricing($item, int $qty): array
    {
        $unitPrice = (float) ($item->unit_price ?? 0);
        $subtotal = max(0, $unitPrice * $qty);

        $discountPercent = $this->resolveDiscountPercent($item);
        $discountAmount = $subtotal * ($discountPercent / 100);

        $taxableAmount = max(0, $subtotal - $discountAmount);
        $taxAmount = 0;

        if (!$item->price_includes_tax) {
            $vatPercent = max(0, (float) ($item->resolved_vat_percent ?? 0));
            $fixedTaxPerUnit = max(0, (float) ($item->tax_amount ?? 0));

            $percentTaxAmount = $taxableAmount * ($vatPercent / 100);
            $fixedTaxAmount = $fixedTaxPerUnit * $qty;
            $taxAmount = $percentTaxAmount + $fixedTaxAmount;
        }

        $lineTotal = $taxableAmount + $taxAmount;

        return [
            'subtotal' => round($subtotal, 2),
            'discount_percent' => round($discountPercent, 2),
            'discount_amount' => round($discountAmount, 2),
            'tax_amount' => round($taxAmount, 2),
            'line_total' => round($lineTotal, 2),
        ];
    }

    private function resolveDiscountPercent($item): float
    {
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
