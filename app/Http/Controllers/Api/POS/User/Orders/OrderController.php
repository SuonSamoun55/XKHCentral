<?php

namespace App\Http\Controllers\Api\POS\User\Orders;

use App\Http\Controllers\Controller;
use App\Models\POS\{Cart, Order, OrderItem, OrderHistory};
use App\Models\ManagementSystem\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log};
use Illuminate\Support\Str;
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

        $cart = Cart::with('items.item')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return $this->fail('Cart is empty');
        }

        $companyId = DB::table('companies')->value('id');
        if (!$companyId) return $this->fail('Company not found');

        DB::beginTransaction();

        try {
            [$subtotal, $discount, $tax] = $this->calculateTotals($cart, $companyId);

            $order = Order::create([
                'company_id' => $companyId,
                'order_no' => 'ORD-' . now()->format('YmdHis') . Str::upper(Str::random(4)),
                'user_id' => $user->id,
                'customer_no' => $user->bc_customer_no,
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'total_amount' => ($subtotal - $discount) + $tax,
                'amount_paid' => ($subtotal - $discount) + $tax,
                'status' => 'pending',
            ]);

            $this->createItems($cart, $order, $companyId);
            $this->createHistory($cart, $order);

            $cart->items()->delete();
            $cart->update(['status' => 'completed']);

            DB::commit();

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

            $qty = $i->qty ?? 0;
            $price = $i->unit_price ?? 0;

            $line = $price * $qty;

            $subtotal += $line;
            $discount += 0;
            $tax += 0;
        }

        return [$subtotal, $discount, $tax];
    }

    private function createItems($cart, $order, $companyId)
    {
        foreach ($cart->items as $i) {
            OrderItem::create([
                'order_id' => $order->id,
                'company_id' => $companyId,
                'item_id' => $i->item_id,
                'item_no' => $i->item->number,
                'item_name' => $i->item->display_name,
                'qty' => $i->qty,
                'unit_price' => $i->unit_price,
                'line_total' => $i->line_total,
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
        $order = Order::with('items.item')
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
}