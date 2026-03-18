<?php

namespace App\Http\Controllers\Api\POSControllers\POSUserController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\POSModel\Favorite;
use App\Models\POSModel\Item;

class FavoriteController extends Controller
{
    public function toggle(Request $request)
    {
        $user = Auth::user();

        $favorite = Favorite::where('user_id', $user->id)
            ->where('item_id', $request->item_id)
            ->first();

        if ($favorite) {
            $favorite->delete();

            return response()->json([
                'favorited' => false
            ]);
        }

        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $request->item_id
        ]);

        return response()->json([
            'favorited' => true
        ]);
    }

    public function getFavorites()
    {
        $user = Auth::user();

        $favorites = Item::whereIn('id', function ($query) use ($user) {
            $query->select('item_id')
                  ->from('favorites')
                  ->where('user_id', $user->id);
        })->get();

        return view('POSViews.POSUserViews.POSItemFavoriteView', compact('favorites'));
    }
}