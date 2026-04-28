<?php

namespace App\Http\Controllers\Api\POSControllers\POSUserController;

use App\Http\Controllers\Controller;
use App\Models\POSModel\Cart;
use App\Models\POSModel\Favorite;
use App\Models\POSModel\Item;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class POSUserControllerItemList extends Controller
{
    public function getItems()
    {
        $user = Auth::user();

        $items = Item::query()
            ->where(function ($q) {
                $q->where('blocked', false)->orWhereNull('blocked');
            })
            ->where(function ($q) {
                $q->where('is_visible', true)->orWhereNull('is_visible');
            })
            ->where(function ($q) {
                $q->where('category_visible', true)->orWhereNull('category_visible');
            })
            ->orderBy('display_name')
            ->get();

        $items->transform(function (Item $item) {
            $discountPercent = $this->resolveDiscountPercent($item);
            $unitPrice = (float) ($item->unit_price ?? 0);
            $finalPrice = round(max(0, $unitPrice * (1 - ($discountPercent / 100))), 2);

            $item->setAttribute('effective_discount_percent', round($discountPercent, 2));
            $item->setAttribute('final_price', $finalPrice);

            return $item;
        });

        $favoriteIds = Favorite::where('user_id', $user->id)
            ->pluck('item_id')
            ->toArray();

        $cartCount = 0;
        if ($user) {
            $activeCart = Cart::where('user_id', $user->id)
                ->where('status', 'active')
                ->first();

            if ($activeCart) {
                $cartCount = (int) $activeCart->items()->sum('qty');
            }
        }

        return view('POSViews.POSUserViews.POSitemlistUserView', compact('items', 'favoriteIds', 'cartCount'));
    }

    public function mobileCategories()
    {
        $categories = Item::query()
            ->where(function ($q) {
                $q->where('blocked', false)->orWhereNull('blocked');
            })
            ->where(function ($q) {
                $q->where('is_visible', true)->orWhereNull('is_visible');
            })
            ->where(function ($q) {
                $q->where('category_visible', true)->orWhereNull('category_visible');
            })
            ->whereNotNull('item_category_code')
            ->where('item_category_code', '<>', '')
            ->selectRaw('item_category_code as code, count(*) as count, max(image_url) as image_url')
            ->groupBy('item_category_code')
            ->orderBy('item_category_code')
            ->get()
            ->map(function ($category) {
                return [
                    'code' => $category->code,
                    'title' => ucwords(str_replace(['-', '_'], [' ', ' '], $category->code)),
                    'count' => (int) $category->count,
                    'image' => $category->image_url ?: asset('images/no-image.png'),
                ];
            });

        return view('POSViews.POSUserViews.POSitemCategoriesMobileView', compact('categories'));
    }

    public function mobileCategoryProducts($category)
    {
        $categoryCode = $category;
        $categoryTitle = ucwords(str_replace(['-', '_'], [' ', ' '], $categoryCode));

        $items = Item::query()
            ->where(function ($q) {
                $q->where('blocked', false)->orWhereNull('blocked');
            })
            ->where(function ($q) {
                $q->where('is_visible', true)->orWhereNull('is_visible');
            })
            ->where(function ($q) {
                $q->where('category_visible', true)->orWhereNull('category_visible');
            })
            ->where('item_category_code', $categoryCode)
            ->orderBy('display_name')
            ->get();

        $items->transform(function (Item $item) {
            $discountPercent = $this->resolveDiscountPercent($item);
            $unitPrice = (float) ($item->unit_price ?? 0);
            $finalPrice = round(max(0, $unitPrice * (1 - ($discountPercent / 100))), 2);

            $item->setAttribute('effective_discount_percent', round($discountPercent, 2));
            $item->setAttribute('final_price', $finalPrice);
            return $item;
        });

        return view('POSViews.POSUserViews.POSitemCategoryProductsMobileView', compact('items', 'categoryTitle', 'categoryCode'));
    }

    public function detail($id)
    {
        $user = Auth::user();

        $item = Item::query()
            ->where('id', $id)
            ->where(function ($q) {
                $q->where('blocked', false)->orWhereNull('blocked');
            })
            ->where(function ($q) {
                $q->where('is_visible', true)->orWhereNull('is_visible');
            })
            ->where(function ($q) {
                $q->where('category_visible', true)->orWhereNull('category_visible');
            })
            ->firstOrFail();

        $discountPercent = $this->resolveDiscountPercent($item);
        $unitPrice = (float) ($item->unit_price ?? 0);
        $finalPrice = round(max(0, $unitPrice * (1 - ($discountPercent / 100))), 2);
        $cartCount = 0;

        if ($user) {
            $activeCart = Cart::where('user_id', $user->id)
                ->where('status', 'active')
                ->first();

            if ($activeCart) {
                $cartCount = (int) $activeCart->items()->sum('qty');
            }
        }

        return view('POSViews.POSUserViews.POSItemDetailView', compact(
            'item',
            'discountPercent',
            'finalPrice',
            'unitPrice',
            'cartCount'
        ));
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
