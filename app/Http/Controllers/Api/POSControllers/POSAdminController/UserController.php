<?php

namespace App\Http\Controllers\Api\POSControllers\POSAdminController;

use App\Http\Controllers\Controller;
use App\Models\POSModel\Item;
use App\Models\MagamentSystemModel\Company;
use App\Models\BcCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class UserController extends Controller
{
    public function index()
    {
        $company = Company::first();
        $response = Http::get('http://localhost:8000/api/items');
        $items = $response->json();
        return view('POSViews.POSAdminViews.POSAdminUserList', compact('items','company'));
    }
    public function show($id)
{
    $customer = BcCustomer::findOrFail($id);
    $user = $customer->user; // or however your relationship is defined

    // Return a partial view
    return view('ManagementSystemViews.AdminViews.Layouts.UserinfoView.UserShow', 
        compact('customer', 'user')
    )->render(); 
}
}