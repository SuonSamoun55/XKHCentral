<?php

namespace App\Http\Controllers\Api\POSControllers\POSUserController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\POSModel\Favorite;
use App\Models\POSModel\Item;
use App\Models\POSModel\Cart;
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

        $favorites->transform(function (Item $item) {
            $discountPercent = $this->resolveDiscountPercent($item);
            $unitPrice = (float) ($item->unit_price ?? 0);
            $finalPrice = round(max(0, $unitPrice * (1 - ($discountPercent / 100))), 2);

            $item->setAttribute('effective_discount_percent', round($discountPercent, 2));
            $item->setAttribute('final_price', $finalPrice);

            return $item;
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

        return view('POSViews.POSUserViews.POSItemFavoriteView', compact('favorites', 'cartCount'));
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
