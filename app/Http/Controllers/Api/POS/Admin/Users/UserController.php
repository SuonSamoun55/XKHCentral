<?php

namespace App\Http\Controllers\Api\POS\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\POS\Item;
use App\Models\ManagementSystem\Company;
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
}