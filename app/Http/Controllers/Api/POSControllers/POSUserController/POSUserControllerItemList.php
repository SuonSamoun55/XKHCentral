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

    $items = Item::where('blocked', false)
        ->where('is_visible', true)
        ->get();

    $favoriteIds = Favorite::where('user_id', $user->id)
                    ->pluck('item_id')
                    ->toArray();

    return view('POSViews.POSUserViews.POSitemlistUserView', compact('items','favoriteIds'));
}
public function show($id)
{
    $customer = Customer::findOrFail($id);
    $user = $customer->user; // or however your relationship is defined

    // Return a partial view
    return view('ManagementSystemViews.AdminViews.Layouts.UserinfoView.UserShow', 
        compact('customer', 'user')
    )->render(); 
}
}
    
