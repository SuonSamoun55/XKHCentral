<?php

namespace App\Http\Controllers\Api\POSControllers\POSUserController;

use App\Http\Controllers\Controller;
use App\Models\POSModel\Cart;
use App\Models\POSModel\CartItem;
use App\Models\POSModel\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\POSModel\Order;
use App\Models\POSModel\OrderItem;

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
    $discountAmount = 0;
    $taxAmount = 0;
    $total = 0;
    $itemCount = 0;

    if ($cart && $cart->items->count()) {
        $totals = $this->calculateCartTotals($cart);
        $subtotal = $totals['subtotal'];
        $discountAmount = $totals['discount_amount'];
        $taxAmount = $totals['tax_amount'];
        $total = $totals['total'];
        $itemCount = $cart->items->sum('qty');
    }

    return view('POSViews.POSUserViews.POSItemcartView', compact(
        'cart',
        'subtotal',
        'discountAmount',
        'taxAmount',
        'total',
        'itemCount'
    ));
}
public function checkout()
{
    $user = Auth::user();

    $cart = Cart::with('items.item')
        ->where('user_id', $user->id)
        ->where('status', 'active')
        ->first();

    if (!$cart || $cart->items->isEmpty()) {
        return redirect('/pos-system/cart');
    }

    $totals = $this->calculateCartTotals($cart);

    return view('POSViews.POSUserViews.mobile.POSPlaceOrder_mobile', [
        'cart' => $cart,
        'subtotal' => $totals['subtotal'],
        'discountAmount' => $totals['discount_amount'],
        'taxAmount' => $totals['tax_amount'],
        'total' => $totals['total'],
        'itemCount' => $cart->items->sum('qty'),
    ]);
}
    
public function success(Request $request)
{
    $order = Order::where('id', $request->order)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    return view('POSViews.POSUserViews.mobile.POSorder_success', [
        'orderNumber' => $order->order_no,
        'amountPaid'  => $order->amount_paid,
    ]);
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

        $totals = $this->calculateCartTotals($cart);
        $itemCount = $cart->items->sum('qty');

        return response()->json([
            'success'    => true,
            'cart'       => $cart,
            'subtotal'   => $totals['subtotal'],
            'discount_amount' => $totals['discount_amount'],
            'tax_amount' => $totals['tax_amount'],
            'total'      => $totals['total'],
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

        if (!$item->is_visible) {
            return response()->json([
                'success' => false,
                'message' => 'This product is inactive and cannot be added to cart.',
            ], 422);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('item_id', $item->id)
            ->first();

        if ($cartItem) {
            $cartItem->qty += $qty;
            $linePricing = $this->calculateLinePricing($item, (int) $cartItem->qty);
            $cartItem->line_total = $linePricing['line_total'];
            $cartItem->save();
        } else {
            $linePricing = $this->calculateLinePricing($item, $qty);
            $cartItem = CartItem::create([
                'cart_id'    => $cart->id,
                'item_id'    => $item->id,
                'item_no'    => $item->number,
                'item_name'  => $item->display_name,
                'qty'        => $qty,
                'unit_price' => $item->unit_price,
                'line_total' => $linePricing['line_total'],
            ]);
        }

    $itemCount = $cart->items()->sum('qty');

return response()->json([
    'success' => true,
    'cartCount' => $itemCount
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
        $linePricing = $this->calculateLinePricing($cartItem->item, (int) $cartItem->qty);
        $cartItem->line_total = $linePricing['line_total'];
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

    private function calculateCartTotals(Cart $cart): array
    {
        $subtotal = 0.0;
        $discountAmount = 0.0;
        $taxAmount = 0.0;

        foreach ($cart->items as $cartItem) {
            $item = $cartItem->item;

            if (!$item) {
                continue;
            }

            $line = $this->calculateLinePricing($item, (int) $cartItem->qty);
            $subtotal += $line['subtotal'];
            $discountAmount += $line['discount_amount'];
            $taxAmount += $line['tax_amount'];
        }

        $total = ($subtotal - $discountAmount) + $taxAmount;

        return [
            'subtotal' => round($subtotal, 2),
            'discount_amount' => round($discountAmount, 2),
            'tax_amount' => round($taxAmount, 2),
            'total' => round($total, 2),
        ];
    }

    private function calculateLinePricing(Item $item, int $qty): array
    {
        $unitPrice = (float) ($item->unit_price ?? 0);
        $subtotal = max(0, $unitPrice * $qty);

        $discountPercent = $this->resolveDiscountPercent($item);
        $discountAmount = $subtotal * ($discountPercent / 100);

        $taxableAmount = max(0, $subtotal - $discountAmount);
        $taxAmount = 0;

        if (!$item->price_includes_tax) {
            $vatPercent = max(0, (float) ($item->vat_percent ?? 0));
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

    private function resolveDiscountPercent(Item $item): float
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
