<?php

namespace App\Http\Controllers\Api\POS\User\Cart;

use App\Http\Controllers\Controller;
use App\Models\POS\Cart;
use App\Models\POS\CartItem;
use App\Models\POS\Item;
use App\Models\POS\ItemVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->activeCart()?->load('items.item', 'items.itemVariant');

        return $this->cartPage($cart, false);
    }

    public function checkout()
    {
        $cart = $this->activeCart()?->load('items.item', 'items.itemVariant');

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('user.pos.cart');
        }

        return $this->cartPage($cart, true);
    }

    public function getCart()
    {
        $cart = Cart::with('items.item', 'items.itemVariant')->firstOrCreate([
            'user_id' => Auth::id(),
            'company_id' => $this->companyId(),
            'status' => 'active',
        ]);

        $totals = $this->calculateCartTotals($cart);

        return response()->json([
            'success' => true,
            'cart' => $cart,
            'subtotal' => $totals['subtotal'],
            'discount' => $totals['discount_amount'],
            'tax_amount' => $totals['tax_amount'],
            'total' => $totals['total'],
            'item_count' => $cart->items->sum('qty'),
        ]);
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'item_id' => ['required', 'integer', 'exists:items,id'],
            'variant_id' => ['nullable', 'integer'],
            'variant_ids' => ['nullable', 'array'],
            'variant_ids.*' => ['integer'],
            'qty' => ['nullable', 'numeric', 'min:0.01'],
        ]);

        $companyId = $this->companyId();
        $qty = round((float) ($validated['qty'] ?? 1), 2);

        // A product can have several option groups (Size, Beef Type, ...),
        // but a cart line holds one variant, so the first selection is used.
        $variantId = $validated['variant_id'] ?? ($validated['variant_ids'][0] ?? null);

        $item = Item::where('id', $validated['item_id'])
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->firstOrFail();

        if (!$item->is_visible) {
            return $this->fail('This product is inactive and cannot be added to cart.');
        }

        if (!$item->isPurchasable()) {
            return $this->fail('This product is out of stock and cannot be added to cart.');
        }

        $variant = null;
        if ($variantId) {
            $variant = ItemVariant::where('item_id', $item->id)->find($variantId);

            if (!$variant) {
                return $this->fail('The selected option is not available for this product.');
            }
        }

        $cart = Cart::firstOrCreate([
            'user_id' => Auth::id(),
            'company_id' => $companyId,
            'status' => 'active',
        ]);

        $cartItem = CartItem::firstOrNew([
            'cart_id' => $cart->id,
            'item_id' => $item->id,
            'item_variant_id' => $variant?->id,
        ]);

        // Every cart line of this item (any variant) shares the same stock
        $alreadyInCart = (float) CartItem::where('cart_id', $cart->id)
            ->where('item_id', $item->id)
            ->sum('qty');
        if ($message = $this->stockLimitMessage($item, $alreadyInCart + $qty, $alreadyInCart)) {
            return $this->fail($message);
        }

        $unitPrice = $item->unitPriceFor($variant);
        $cartItem->item_no = $item->number;
        $cartItem->item_name = $item->display_name;
        $cartItem->qty = round((float) $cartItem->qty + $qty, 2);
        $cartItem->unit_price = $unitPrice;
        $cartItem->line_total = $this->calculateLinePricing($item, (float) $cartItem->qty, $unitPrice)['line_total'];
        $cartItem->save();

        return response()->json([
            'success' => true,
            'cartCount' => (int) $cart->items()->sum('qty'),
        ]);
    }

    public function updateQty(Request $request, $id)
    {
        $validated = $request->validate([
            'qty' => ['required', 'numeric', 'min:0.01'],
        ]);

        $cartItem = $this->findCartItem($id);
        $newQty = round((float) $validated['qty'], 2);

        $otherLinesQty = (float) CartItem::where('cart_id', $cartItem->cart_id)
            ->where('item_id', $cartItem->item_id)
            ->where('id', '!=', $cartItem->id)
            ->sum('qty');
        if ($message = $this->stockLimitMessage($cartItem->item, $otherLinesQty + $newQty, $otherLinesQty)) {
            $max = $cartItem->item->maxOrderableQty();

            return response()->json([
                'success' => false,
                'message' => $message,
                // Most this line can hold, so the cart can snap back to it
                'max_qty' => max(0, round($max - $otherLinesQty, 2)),
                'qty' => (float) $cartItem->qty,
            ], 422);
        }

        $unitPrice = $cartItem->item->unitPriceFor($cartItem->itemVariant);
        $cartItem->qty = $newQty;
        $cartItem->unit_price = $unitPrice;
        $cartItem->line_total = $this->calculateLinePricing($cartItem->item, (float) $cartItem->qty, $unitPrice)['line_total'];
        $cartItem->save();

        return response()->json([
            'success' => true,
            'message' => 'Cart item updated successfully.',
            'cart_item' => $cartItem,
        ]);
    }

    public function removeItem($id)
    {
        $this->findCartItem($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart.',
        ]);
    }

    public function clearCart()
    {
        $this->activeCart()?->items()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully.',
        ]);
    }

    private function cartPage(?Cart $cart, bool $showCheckout)
    {
        $hasItems = $cart && $cart->items->isNotEmpty();
        $totals = $hasItems
            ? $this->calculateCartTotals($cart)
            : ['subtotal' => 0, 'discount_amount' => 0, 'tax_amount' => 0, 'total' => 0];

        return view('POSViews.POSUserViews.Cart.index', [
            'cart' => $cart,
            'subtotal' => $totals['subtotal'],
            'discount' => $totals['discount_amount'],
            'taxAmount' => $totals['tax_amount'],
            'total' => $totals['total'],
            'itemCount' => $hasItems ? $cart->items->sum('qty') : 0,
            'showCheckout' => $showCheckout,
        ]);
    }

    private function activeCart(): ?Cart
    {
        return Cart::where('user_id', Auth::id())
            ->where('company_id', $this->companyId())
            ->where('status', 'active')
            ->first();
    }

    /** A line of the current user's own active cart (404 otherwise). */
    private function findCartItem($id): CartItem
    {
        $cart = $this->activeCart() ?? abort(404);

        return CartItem::where('cart_id', $cart->id)->findOrFail($id);
    }

    /**
     * Null when $totalQty fits the item's stock (always, with Oversell on);
     * otherwise the message shown to the customer.
     */
    private function stockLimitMessage(Item $item, float $totalQty, float $alreadyInCart = 0): ?string
    {
        $max = $item->maxOrderableQty();
        if ($max === null || $totalQty <= $max + 0.0001) {
            return null;
        }

        $message = 'Only ' . $item->stockLabel($max) . ' of "' . $item->display_name . '" left in stock';
        $message .= $alreadyInCart > 0
            ? ' (you already have ' . $item->stockLabel($alreadyInCart) . ' in your cart).'
            : '.';

        return $message . ' Please check the stock on View detail and choose a smaller quantity.';
    }

    private function fail(string $message)
    {
        return response()->json(['success' => false, 'message' => $message], 422);
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

            $unitPrice = $item->unitPriceFor($cartItem->itemVariant);
            $line = $this->calculateLinePricing($item, (float) $cartItem->qty, $unitPrice);

            // Keep the row's saved price in step with the current (variant)
            // price, so the cart rows always add up to the totals shown.
            if ((float) $cartItem->unit_price !== $unitPrice || (float) $cartItem->line_total !== $line['line_total']) {
                $cartItem->unit_price = $unitPrice;
                $cartItem->line_total = $line['line_total'];
                $cartItem->save();
            }

            $subtotal += $line['subtotal'];
            $discountAmount += $line['discount_amount'];
            $taxAmount += $line['tax_amount'];
        }

        return [
            'subtotal' => round($subtotal, 2),
            'discount_amount' => round($discountAmount, 2),
            'tax_amount' => round($taxAmount, 2),
            'total' => round($subtotal - $discountAmount + $taxAmount, 2),
        ];
    }

    /** $unitPrice comes from Item::unitPriceFor(), so a variant's own price is used. */
    private function calculateLinePricing(Item $item, float $qty, float $unitPrice): array
    {
        $subtotal = max(0, $unitPrice * $qty);

        $discountPercent = $item->active_discount_percent;
        $discountAmount = $subtotal * ($discountPercent / 100);

        $taxableAmount = max(0, $subtotal - $discountAmount);
        $taxAmount = 0;

        if (!$item->price_includes_tax) {
            $vatPercent = max(0, (float) ($item->resolved_vat_percent ?? 0));
            $fixedTaxPerUnit = max(0, (float) ($item->tax_amount ?? 0));

            $taxAmount = $taxableAmount * ($vatPercent / 100) + $fixedTaxPerUnit * $qty;
        }

        return [
            'subtotal' => round($subtotal, 2),
            'discount_percent' => round($discountPercent, 2),
            'discount_amount' => round($discountAmount, 2),
            'tax_amount' => round($taxAmount, 2),
            'line_total' => round($taxableAmount + $taxAmount, 2),
        ];
    }

    /**
     * The company whose cart this request works on: the selected company
     * (so a cross-company admin gets that company's cart), otherwise the
     * user's own company.
     */
    private function companyId(): ?int
    {
        return session('selected_company_id') ?? Auth::user()->company_id;
    }
}
