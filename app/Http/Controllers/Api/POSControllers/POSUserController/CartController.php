<?php

namespace App\Http\Controllers\Api\POSControllers\POSUserController;

use App\Http\Controllers\Controller;
use App\Models\POSModel\Cart;
use App\Models\POSModel\CartItem;
use App\Models\POSModel\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
public function index()
{
    $user = Auth::user();

    $cart = Cart::with('items.item')
        ->where('user_id', $user->id)
        ->where('status', 'active')
        ->first();

    $subtotal = 0;
    $total = 0;
    $itemCount = 0;

    if ($cart && $cart->items->count()) {
        $subtotal = $cart->items->sum('line_total');
        $total = $subtotal;
        $itemCount = $cart->items->sum('qty');
    }

    return view('POSViews.POSUserViews.POSItemcartView', compact(
        'cart',
        'subtotal',
        'total',
        'itemCount'
    ));
}

    public function getCart()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $cart = Cart::with('items.item')->firstOrCreate(
            [
                'user_id' => $user->id,
                'status'  => 'active',
            ],
            [
                'user_id' => $user->id,
                'status'  => 'active',
            ]
        );

        $subtotal = $cart->items->sum('line_total');
        $itemCount = $cart->items->sum('qty');

        return response()->json([
            'success'    => true,
            'cart'       => $cart,
            'subtotal'   => $subtotal,
            'total'      => $subtotal,
            'item_count' => $itemCount,
        ]);
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'item_id' => ['required', 'exists:items,id'],
            'qty'     => ['nullable', 'integer', 'min:1'],
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $qty = (int) ($validated['qty'] ?? 1);

        $cart = Cart::firstOrCreate(
            [
                'user_id' => $user->id,
                'status'  => 'active',
            ],
            [
                'user_id' => $user->id,
                'status'  => 'active',
            ]
        );

        $item = Item::findOrFail($validated['item_id']);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('item_id', $item->id)
            ->first();

        if ($cartItem) {
            $cartItem->qty += $qty;
            $cartItem->line_total = $cartItem->qty * $cartItem->unit_price;
            $cartItem->save();
        } else {
            $cartItem = CartItem::create([
                'cart_id'    => $cart->id,
                'item_id'    => $item->id,
                'item_no'    => $item->number,
                'item_name'  => $item->display_name,
                'qty'        => $qty,
                'unit_price' => $item->unit_price,
                'line_total' => $qty * $item->unit_price,
            ]);
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Item added to cart successfully.',
            'cart_item' => $cartItem,
        ]);
    }

    public function updateQty(Request $request, $id)
    {
        $validated = $request->validate([
            'qty' => ['required', 'integer', 'min:1'],
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $cart = Cart::where('user_id', $user->id)
            ->where('status', 'active')
            ->firstOrFail();

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('id', $id)
            ->firstOrFail();

        $cartItem->qty = (int) $validated['qty'];
        $cartItem->line_total = $cartItem->qty * $cartItem->unit_price;
        $cartItem->save();

        return response()->json([
            'success'   => true,
            'message'   => 'Cart item updated successfully.',
            'cart_item' => $cartItem,
        ]);
    }

    public function removeItem($id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $cart = Cart::where('user_id', $user->id)
            ->where('status', 'active')
            ->firstOrFail();

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('id', $id)
            ->firstOrFail();

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart.',
        ]);
    }

    public function clearCart()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $cart = Cart::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if ($cart) {
            $cart->items()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully.',
        ]);
    }
}
