<?php

namespace App\Http\Controllers\Api\POSControllers\POSUserController;

use App\Http\Controllers\Controller;
use App\Models\POSModel\Favorite;
use App\Models\POSModel\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $favorites = Favorite::with('item')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'favorites' => $favorites
        ]);
    }


    public function addFavorite(Request $request)
    {
        $validated = $request->validate([
            'item_id' => ['required', 'exists:items,id']
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $favorite = Favorite::firstOrCreate([
            'user_id' => $user->id,
            'item_id' => $validated['item_id']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item added to favorite.',
            'favorite' => $favorite
        ]);
    }


    public function removeFavorite($id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $favorite = Favorite::where('user_id', $user->id)
            ->where('item_id', $id)
            ->first();

        if (!$favorite) {
            return response()->json([
                'success' => false,
                'message' => 'Favorite not found.'
            ], 404);
        }

        $favorite->delete();

        return response()->json([
            'success' => true,
            'message' => 'Favorite removed successfully.'
        ]);
    }

}
