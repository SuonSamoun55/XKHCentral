<?php

namespace App\Http\Controllers\Api\POSControllers\POSUserController;

use App\Http\Controllers\Controller;
use App\Models\POSModel\Item;

class POSUserControllerItemList extends Controller
{
    public function getItems()
    {
        $items = Item::where('blocked', false)->get();

        return view('POSViews.POSUserViews.POSitemlistUserView', compact('items'));
    }
}
    