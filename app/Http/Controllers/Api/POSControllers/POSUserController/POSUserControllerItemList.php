<?php

namespace App\Http\Controllers\Api\POSControllers\POSUserController;
use Illuminate\Support\Facades\Auth;
use App\Models\POSModel\Favorite;
use App\Http\Controllers\Controller;
use App\Models\POSModel\Item;

class POSUserControllerItemList extends Controller
{
   public function getItems()
{
    $user = Auth::user();

    $items = Item::where('blocked', false)->get();

    $favoriteIds = Favorite::where('user_id', $user->id)
                    ->pluck('item_id')
                    ->toArray();

    return view('POSViews.POSUserViews.POSitemlistUserView', compact('items','favoriteIds'));
}
}
    