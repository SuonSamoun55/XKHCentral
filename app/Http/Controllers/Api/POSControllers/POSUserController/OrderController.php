<?php

namespace App\Http\Controllers\Api\POSControllers\POSUserController;

use App\Http\Controllers\Controller;
use App\Models\POSModel\Cart;
use App\Models\POSModel\Order;
use App\Models\POSModel\OrderItem;
use App\Models\POSModel\OrderHistory;
use App\Models\MagamentSystemModel\Notification;
use App\Models\MagamentSystemModel\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $companyId = Company::value('id');

        Log::info('Checkout started', [
            'user_id' => $user->id,
            'company_id' => $companyId,
            'request' => $request->all(),
            'all_user_carts' => Cart::where('user_id', $user->id)->with('items')->get()->toArray(),
        ]);

        if (!$companyId) {
            Log::warning('Checkout stopped: no company found', [
                'user_id' => $user->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'No company found.',
            ], 422);
        }

        $cart = Cart::with('items.item')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        // Ensure items are loaded fresh
        if ($cart) {
            $cart->load('items.item');
        }

        Log::info('Cart found', [
            'cart_id' => $cart ? $cart->id : null,
            'cart_status' => $cart ? $cart->status : null,
            'items_count' => $cart ? $cart->items->count() : 0,
            'items_data' => $cart ? $cart->items->toArray() : null,
        ]);

        if (!$cart) {
            Log::warning('Checkout stopped: cart not found', [
                'user_id' => $user->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Cart not found.',
            ], 422);
        }

        if ($cart->items->isEmpty()) {
            Log::warning('Checkout stopped: cart empty', [
                'user_id' => $user->id,
                'cart_id' => $cart->id,
                'all_carts' => Cart::where('user_id', $user->id)->with('items')->get()->toArray(),
                'cart_items_raw' => \DB::table('cart_items')->where('cart_id', $cart->id)->get(),
            ]);

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
                $item = $cartItem->item;

                if (!$item) {
                    throw new \Exception("Cart item ID {$cartItem->id} has no related item.");
                }

                $qty = (float) ($cartItem->qty ?? $cartItem->quantity ?? 0);
                $unitPrice = (float) ($cartItem->unit_price ?? $cartItem->price ?? 0);

                Log::info('Cart item debug', [
                    'cart_item_id' => $cartItem->id,
                    'item_id' => $cartItem->item_id,
                    'qty' => $qty,
                    'unit_price' => $unitPrice,
                    'item_company_id' => $item->company_id ?? null,
                    'company_id' => $companyId,
                ]);

                if ((int) $item->company_id !== (int) $companyId) {
                    throw new \Exception("Item {$item->display_name} does not belong to current company.");
                }

                if ($qty <= 0) {
                    throw new \Exception("Invalid quantity for cart item ID {$cartItem->id}.");
                }

                if ($unitPrice < 0) {
                    throw new \Exception("Invalid unit price for cart item ID {$cartItem->id}.");
                }

                $subtotal += ($unitPrice * $qty);
            }

            $totalAmount = $subtotal - $discountAmount;
            $firstCartItem = $cart->items->first();
            $locationCode = optional($firstCartItem->item)->default_location_code;
            $orderNo = 'ORD-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));

            $order = Order::create([
                'company_id'      => $companyId,
                'order_no'        => $orderNo,
                'user_id'         => $user->id,
                'customer_no'     => $user->bc_customer_no ?? null,
                'currency_code'   => $request->currency_code ?? 'USD',
                'currency_factor' => $request->currency_factor ?? 1,
                'subtotal'        => $subtotal,
                'discount_amount' => $discountAmount,
                'total_amount'    => $totalAmount,
                'location_code'   => $locationCode,
                'status'          => 'pending',
                'sync_status'     => 'pending',
                'checked_out_at'  => now(),
            ]);

            Log::info('Order created', [
                'order_id' => $order->id,
                'order_no' => $order->order_no,
            ]);

            // Save order history
            $itemsSummary = [];
            foreach ($cart->items as $cartItem) {
                $itemsSummary[] = [
                    'item_no' => $cartItem->item_no,
                    'item_name' => $cartItem->item_name,
                    'qty' => $cartItem->qty,
                    'unit_price' => $cartItem->unit_price,
                    'line_total' => $cartItem->line_total,
                ];
            }

            OrderHistory::create([
                'user_id' => $user->id,
                'order_no' => $order->order_no,
                'customer_no' => $user->bc_customer_no ?? null,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'items_summary' => json_encode($itemsSummary),
            ]);

            foreach ($cart->items as $cartItem) {
                $item = $cartItem->item;
                $qty = (float) ($cartItem->qty ?? $cartItem->quantity ?? 0);
                $unitPrice = (float) ($cartItem->unit_price ?? $cartItem->price ?? 0);
                $lineTotal = $unitPrice * $qty;

                OrderItem::create([
                    'order_id'      => $order->id,
                    'company_id'    => $companyId,
                    'item_id'       => $cartItem->item_id,
                    'item_no'       => $item->number,
                    'item_name'     => $item->display_name,
                    'qty'           => $qty,
                    'unit_price'    => $unitPrice,
                    'line_total'    => $lineTotal,
                    'location_code' => $item->default_location_code,
                ]);
            }

            try {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Order Created',
                    'message' => 'Your order #' . $order->order_no . ' has been created successfully.',
                    'type' => 'user',
                    'is_read' => false,
                ]);
            } catch (\Throwable $notifyError) {
                Log::warning('Notification create failed', [
                    'error' => $notifyError->getMessage(),
                ]);
            }

            $cart->items()->delete();
            $cart->status = 'completed';
            $cart->save();

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Checkout successful.',
                'order_id' => $order->id,
                'order_no' => $order->order_no,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Checkout failed', [
                'user_id' => $user->id,
                'company_id' => $companyId,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Checkout failed.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    
}

