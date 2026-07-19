<?php

namespace App\Http\Controllers\Api\POS\User\Favorites;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\POS\Favorite;
use App\Models\POS\Item;
use App\Models\POS\ItemVariant;
use App\Models\POS\Cart;
use Carbon\Carbon;

class FavoriteController extends Controller
{
    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'item_id' => ['required', 'exists:items,id'],
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $favorite = Favorite::where('user_id', $user->id)
            ->where('item_id', $validated['item_id'])
            ->first();

        if ($favorite) {
            $favorite->delete();

            return response()->json([
                'success' => true,
                'favorited' => false,
                'item_id' => (int) $validated['item_id'],
                'message' => 'Removed from favorites.',
            ]);
        }

        Favorite::firstOrCreate([
            'user_id' => $user->id,
            'item_id' => $validated['item_id']
        ]);

        return response()->json([
            'success' => true,
            'favorited' => true,
            'item_id' => (int) $validated['item_id'],
            'message' => 'Added to favorites.',
        ]);
    }

    public function getFavorites()
    {
        $user = Auth::user();

        $favorites = Item::whereIn('id', function ($query) use ($user) {
            $query->select('item_id')
                  ->from('favorites')
                  ->where('user_id', $user->id);
        })
        ->where('is_visible', true)
        ->get();

        // ── Batch-load variants for every favorite item in one query,
        // same pattern as ItemListController::getItems(), so the Blade
        // view's $item->variants works and the variant popup can fire.
        // We do NOT filter out blocked variants here on purpose — the
        // popup wants to show blocked variants too (disabled/struck-through).
        $itemIds = $favorites->pluck('id');

        $variantsByItem = ItemVariant::whereIn('item_id', $itemIds)
            ->get()
            ->groupBy('item_id');

        $favorites->transform(function (Item $item) use ($variantsByItem) {
            $item->setRelation(
                'variants',
                $variantsByItem->get($item->id, collect())
            );

            return $this->decorateItem($item);
        });

        $cartCount = 0;
        if ($user) {
            $activeCart = Cart::where('user_id', $user->id)
                ->where('status', 'active')
                ->first();

            if ($activeCart) {
                $cartCount = (int) $activeCart->items()->sum('qty');
            }
        }

        return view('POSViews.POSUserViews.Favorites.index', compact('favorites', 'cartCount'));
    }

    /**
     * Apply discount/price + resolved image_url to a single item.
     * Mirrors ItemListController::decorateItem() so favorites and the
     * product list stay visually/behaviorally consistent (same image
     * resolution, same discount math, same final_price attribute the
     * Blade view and variant popup both depend on).
     */
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

    /**
     * Make sure image_url is always a full, browser-loadable URL,
     * whether it's stored as a full URL, a public disk path, or empty.
     * Same logic as ItemListController::resolveImageUrl().
     */
    private function resolveImageUrl(?string $rawPath): string
    {
        if (!$rawPath) {
            return asset('images/no-image.png');
        }

        if (str_starts_with($rawPath, 'http://') || str_starts_with($rawPath, 'https://')) {
            return $rawPath;
        }

        if (str_starts_with($rawPath, '/')) {
            return asset(ltrim($rawPath, '/'));
        }

        if (Storage::disk('public')->exists($rawPath)) {
            return asset('storage/' . $rawPath);
        }

        return asset($rawPath);
    }
}
