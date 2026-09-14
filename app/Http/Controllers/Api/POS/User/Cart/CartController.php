<?php

namespace App\Http\Controllers\Api\POS\User\Cart;

use App\Http\Controllers\Controller;
use App\Models\POS\Cart;
use App\Models\POS\CartItem;
use App\Models\POS\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\POS\Order;
use App\Models\POS\OrderItem;
use \App\Models\POS\ItemVariant;
class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $cart = Cart::with('items.item', 'items.itemVariant')
            ->where('user_id', $user->id)
            ->where('company_id', $this->resolveCompanyId($user))
            ->where('status', 'active')
            ->first();
        $subtotal = 0;
        $discount = 0;
        $taxAmount = 0;
        $total = 0;
        $itemCount = 0;

        if ($cart && $cart->items->count()) {
            $totals = $this->calculateCartTotals($cart);
            $subtotal = $totals['subtotal'];
            $discount = $totals['discount_amount'];
            $taxAmount = $totals['tax_amount'];
            $total = $totals['total'];
            $itemCount = $cart->items->sum('qty');
        }

        return view('POSViews.POSUserViews.Cart.index', compact(
            'cart',
            'subtotal',
            'discount',
            'taxAmount',
            'total',
            'itemCount'
        ));
    }
    public function checkout()
    {
        $user = Auth::user();

        $cart = Cart::with('items.item', 'items.itemVariant')
            ->where('user_id', $user->id)
            ->where('company_id', $this->resolveCompanyId($user))
            ->where('status', 'active')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect('/pos-system/cart');
        }

        $totals = $this->calculateCartTotals($cart);

        return view('POSViews.POSUserViews.Cart.index', [
            'cart' => $cart,
            'subtotal' => $totals['subtotal'],
            'discount' => $totals['discount_amount'],
            'taxAmount' => $totals['tax_amount'],
            'total' => $totals['total'],
            'itemCount' => $cart->items->sum('qty'),
            'showCheckout' => true,
        ]);
    }
    public function itemVariant()
    {
        return $this->belongsTo(ItemVariant::class, 'item_variant_id');
    }
    public function success(Request $request)
    {
        $order = Order::where('id', $request->order)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('POSViews.POSUserViews.mobile.POSPlaceOrder_mobile', [
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

        $companyId = $this->resolveCompanyId($user);

        $cart = Cart::with('items.item', 'items.itemVariant')->firstOrCreate(
            [
                'user_id'    => $user->id,
                'company_id' => $companyId,
                'status'     => 'active',
            ]
        );

        $totals = $this->calculateCartTotals($cart);
        $itemCount = $cart->items->sum('qty');

        return response()->json([
            'success'    => true,
            'cart'       => $cart,
            'subtotal'   => $totals['subtotal'],
            'discount'   => $totals['discount_amount'],
            'tax_amount' => $totals['tax_amount'],
            'total'      => $totals['total'],
            'item_count' => $itemCount,
        ]);
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'item_id' => ['required', 'exists:items,id'],
            'qty'     => ['nullable', 'numeric', 'min:0.01'],
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $qty = round((float) ($validated['qty'] ?? 1), 2);
        $variantId = $request->input('variant_id')
            ?? $request->input('variantId')
            ?? $request->input('selected_variant_id')
            ?? $request->input('selectedVariantId');

        if (!$variantId) {
            $variantIds = $request->input('variant_ids') ?? $request->input('variantIds');
            if (is_array($variantIds) && count($variantIds) > 0) {
                $variantId = $variantIds[0];
            }
        }

        $companyId = $this->resolveCompanyId($user);

        $cart = Cart::firstOrCreate(
            [
                'user_id'    => $user->id,
                'company_id' => $companyId,
                'status'     => 'active',
            ]
        );

        $item = Item::where('id', $validated['item_id'])
            ->when($companyId, function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->firstOrFail();

        if (!$item->is_visible) {
            return response()->json([
                'success' => false,
                'message' => 'This product is inactive and cannot be added to cart.',
            ], 422);
        }
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('item_id', $item->id)
            ->where('item_variant_id', $variantId)
            ->first();
        if ($cartItem) {
            $cartItem->qty = round((float) $cartItem->qty + $qty, 2);
            $linePricing = $this->calculateLinePricing($item, (float) $cartItem->qty);
            $cartItem->line_total = $linePricing['line_total'];
            $cartItem->save();
        } else {
            $linePricing = $this->calculateLinePricing($item, $qty);
            $cartItem = CartItem::create([
                'cart_id'         => $cart->id,
                'item_id'         => $item->id,
                'item_variant_id' => $variantId,
                'item_no'         => $item->number,
                'item_name'       => $item->display_name,
                'qty'             => $qty,
                'unit_price'      => $item->unit_price,
                'line_total'      => $linePricing['line_total'],
            ]);
        }

        $itemCount = (int) $cart->items()->sum('qty');

        return response()->json([
            'success'    => true,
            'cartCount'  => $itemCount,
            'variant_id_received' => $variantId,
        ]);
    }

    public function updateQty(Request $request, $id)
    {
        $validated = $request->validate([
            'qty' => ['required', 'numeric', 'min:0.01'],
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $cart = Cart::where('user_id', $user->id)
            ->where('company_id', $this->resolveCompanyId($user))
            ->where('status', 'active')
            ->firstOrFail();

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('id', $id)
            ->firstOrFail();

        $cartItem->qty = round((float) $validated['qty'], 2);
        $linePricing = $this->calculateLinePricing($cartItem->item, (float) $cartItem->qty);
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
            ->where('company_id', $this->resolveCompanyId($user))
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
            ->where('company_id', $this->resolveCompanyId($user))
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
            $line = $this->calculateLinePricing($item, (float) $cartItem->qty);
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

    private function calculateLinePricing(Item $item, float $qty): array
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

    private function resolveDiscountPercent(Item $item): float
    {
        return $item->active_discount_percent;
    }

    /**
     * The company whose items/cart the current request should operate on.
     * Session's "currently selected" company wins so a cross-company admin
     * switching companies gets that company's own cart, not their pinned
     * user_id's company — falls back to the user's own company only when
     * nothing is selected.
     */
    private function resolveCompanyId($user): ?int
    {
        return session('selected_company_id') ?? $user->company_id;
    }
}
