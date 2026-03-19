<?php

namespace App\Http\Controllers\Api\POSControllers\POSUserController;

use App\Http\Controllers\Controller;
use App\Models\POSModel\Cart;
use App\Models\POSModel\Order;
use App\Models\POSModel\OrderItem;
use App\Models\MagamentSystemModel\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'currency_code'   => ['required', 'string', 'max:10'],
            'currency_factor' => ['nullable', 'numeric'],
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $companyId = session('selected_company_id');

        if (!$companyId) {
            return response()->json([
                'success' => false,
                'message' => 'No company selected.',
            ], 422);
        }

        $cart = Cart::with('items.item')
            ->where('user_id', $user->id)
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty.',
            ], 422);
        }

        DB::beginTransaction();

        try {
            $subtotal = 0;
            $discountAmount = 0;

            foreach ($cart->items as $cartItem) {
                $subtotal += ($cartItem->price * $cartItem->quantity);
            }

            $totalAmount = $subtotal - $discountAmount;

            $orderNo = 'ORD-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));

            $firstCartItem = $cart->items->first();
            $locationCode = $firstCartItem->item->default_location_code ?? null;

            $order = Order::create([
                'company_id'      => $companyId,
                'order_no'        => $orderNo,
                'user_id'         => $user->id,
                'customer_no'     => $user->bc_customer_no ?? null,
                'currency_code'   => $validated['currency_code'],
                'currency_factor' => $validated['currency_factor'] ?? 1,
                'subtotal'        => $subtotal,
                'discount_amount' => $discountAmount,
                'total_amount'    => $totalAmount,
                'location_code'   => $locationCode,
                'status'          => 'pending',
                'sync_status'     => 'pending',
                'checked_out_at'  => now(),
            ]);

            foreach ($cart->items as $cartItem) {
                $item = $cartItem->item;

                if (!$item) {
                    throw new \Exception('One of the cart items no longer exists.');
                }

                if ((int) $item->company_id !== (int) $companyId) {
                    throw new \Exception("Item {$item->display_name} does not belong to the selected company.");
                }

                $lineTotal = $cartItem->price * $cartItem->quantity;

                OrderItem::create([
                    'order_id'      => $order->id,
                    'company_id'    => $companyId,
                    'item_id'       => $cartItem->item_id,
                    'item_no'       => $item->number,
                    'item_name'     => $item->display_name,
                    'qty'           => $cartItem->quantity,
                    'unit_price'    => $cartItem->price,
                    'line_total'    => $lineTotal,
                    'location_code' => $item->default_location_code,
                ]);
            }

            Notification::create([
                'user_id' => $user->id,
                'title' => 'Order Created',
                'message' => 'Your order #' . $order->order_no . ' has been created successfully.',
                'type' => 'user',
                'is_read' => false,
            ]);

            $cart->items()->delete();

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Checkout successful.',
                'order_id' => $order->id,
                'order_no' => $order->order_no,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Checkout failed.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
