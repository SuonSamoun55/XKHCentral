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

        $cart = Cart::with('items')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty.',
            ], 422);
        }

        DB::beginTransaction();

        try {
            $subtotal = $cart->items->sum('line_total');
            $discountAmount = 0;
            $totalAmount = $subtotal - $discountAmount;

            $order = Order::create([
                'order_no'         => 'ORD-' . now()->format('YmdHis') . '-' . $user->id,
                'user_id'          => $user->id,
                'currency_code'    => strtoupper($validated['currency_code']),
                'currency_factor'  => $validated['currency_factor'] ?? 1,
                'subtotal'         => $subtotal,
                'discount_amount'  => $discountAmount,
                'total_amount'     => $totalAmount,
                'status'           => 'pending',
                'sync_status'      => 'pending',
                'checked_out_at'   => now(),
            ]);

            $itemNames = [];

            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'item_id'    => $cartItem->item_id,
                    'item_no'    => $cartItem->item_no,
                    'item_name'  => $cartItem->item_name,
                    'qty'        => $cartItem->qty,
                    'unit_price' => $cartItem->unit_price,
                    'line_total' => $cartItem->line_total,
                ]);

                $itemNames[] = $cartItem->item_name;
            }

            Notification::create([
                'user_id' => $user->id,
                'order_id' => $order->id,
                'item_id' => null,
                'type' => 'order',
                'title' => 'New Order',
                'message' => 'User ' . $user->name . ' placed order ' . $order->order_no . ' for item(s): ' . implode(', ', $itemNames),
                'is_read' => false,
            ]);

            $cart->items()->delete();

            $cart->update([
                'status' => 'checked_out',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Checkout completed successfully.',
                'order'   => $order->load('items'),
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

    public function history()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $orders = Order::with('items')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'orders'  => $orders,
        ]);
    }
}
