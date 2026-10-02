<?php

namespace App\Http\Controllers\Api\POS\User\Products;

use App\Http\Controllers\Concerns\ResolvesImageUrl;
use App\Http\Controllers\Controller;
use App\Models\POS\Cart;
use App\Models\POS\Favorite;
use App\Models\POS\Item;
use App\Models\POS\ItemVariant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ItemListController extends Controller
{
    use ResolvesImageUrl;

    public function getItems()
    {
        $user = Auth::user();
        $companyId = $this->companyId();

        $items = $this->visibleItems($companyId)
            ->orderBy('display_name')
            ->get();

        $items = $this->prepareForListing($items);
        $favoriteIds = $this->favoriteIds($user->id);
        $cartCount = $this->cartCount($user->id, $companyId);

        return view('POSViews.POSUserViews.Products.index', compact('items', 'favoriteIds', 'cartCount'));
    }

    public function showProduct($id)
    {
        $user = Auth::user();
        $companyId = $this->companyId();

        $item = $this->visibleItems($companyId)
            ->where('id', $id)
            ->firstOrFail();

        $item = $this->decorateItem($item);

        if (!$item->isPurchasable()) {
            return redirect()->route('user.posinterface')
                ->with('error', 'This product is currently out of stock and unavailable.');
        }

        $discountPercent = $item->effective_discount_percent;
        $finalPrice = $item->final_price;
        $unitPrice = (float) ($item->unit_price ?? 0);
        $cartCount = $this->cartCount($user->id, $companyId);
        $favoriteIds = $this->favoriteIds($user->id);

        $variants = ItemVariant::where('item_id', $item->id)
            ->where('blocked', false)
            ->get();

        // Up to 10 other products from the same category. A product without
        // a category shows no related products.
        $relatedItems = collect();

        if ($item->item_category_code) {
            $relatedItems = $this->visibleItems($companyId)
                ->where('id', '!=', $item->id)
                ->where('item_category_code', $item->item_category_code)
                ->orderBy('display_name')
                ->limit(10)
                ->get();

            $relatedItems = $this->prepareForListing($relatedItems);
        }

        return view('POSViews.POSUserViews.Products.show', compact(
            'item',
            'discountPercent',
            'finalPrice',
            'unitPrice',
            'cartCount',
            'variants',
            'favoriteIds',
            'relatedItems'
        ));
    }

    private function companyId(): ?int
    {
        return session('selected_company_id') ?? Auth::user()->company_id;
    }

    /**
     * Products a customer may see: not blocked, approved by the admin
     * (a null is_visible means the item was just synced and not reviewed
     * yet) and not in a hidden category.
     */
    private function visibleItems(?int $companyId): Builder
    {
        return Item::query()
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->where(fn ($q) => $q->where('blocked', false)->orWhereNull('blocked'))
            ->where('is_visible', true)
            ->where(fn ($q) => $q->where('category_visible', true)->orWhereNull('category_visible'));
    }

    /**
     * Attach variants (so the "+" button can open the variant popup), add
     * the display prices, and drop products that can't be bought.
     */
    private function prepareForListing(Collection $items): Collection
    {
        $variantsByItem = ItemVariant::whereIn('item_id', $items->pluck('id'))
            ->get()
            ->groupBy('item_id');

        return $items
            ->map(function (Item $item) use ($variantsByItem) {
                $item->setRelation('variants', $variantsByItem->get($item->id, collect()));

                return $this->decorateItem($item);
            })
            ->filter(fn (Item $item) => $item->isPurchasable())
            ->values();
    }

    private function favoriteIds(int $userId): array
    {
        return Favorite::where('user_id', $userId)->pluck('item_id')->all();
    }

    private function cartCount(int $userId, ?int $companyId): int
    {
        $cart = Cart::where('user_id', $userId)
            ->where('company_id', $companyId)
            ->where('status', 'active')
            ->first();

        return $cart ? (int) $cart->items()->sum('qty') : 0;
    }

    private function decorateItem(Item $item): Item
    {
        $discountPercent = $this->resolveDiscountPercent($item);
        $unitPrice = (float) ($item->unit_price ?? 0);
        $finalPrice = round(max(0, $unitPrice * (1 - ($discountPercent / 100))), 2);

        $item->setAttribute('effective_discount_percent', round($discountPercent, 2));
        $item->setAttribute('final_price', $finalPrice);
        $item->setAttribute('image_url', $this->resolveImageUrl($item->custom_image_url ?: $item->image_url));

        return $item;
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
