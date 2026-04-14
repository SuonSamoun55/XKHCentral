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
        $user = $customer->user; // Get the connected user through relationship

        // Set profile image display like WebUserController does
        $customer->profile_image_display = $this->getCustomerImageDisplay($customer, $user);

        // Return a partial view
        return view('ManagementSystemViews.AdminViews.Layouts.UserinfoView.UserShow',
            compact('customer', 'user')
        )->render();
    }

    protected function getCustomerImageDisplay($customer, $linkedUser = null)
    {
        if ($linkedUser && !empty($linkedUser->profile_image)) {
            return asset('storage/' . $linkedUser->profile_image);
        }

        if ($linkedUser && !empty($linkedUser->profile_image_url)) {
            return $linkedUser->profile_image_url;
        }

        if (!empty($customer->bc_id)) {
            return route('users.bc-image', ['bcId' => $customer->bc_id]);
        }

        if (!empty($customer->profile_image_url)) {
            return $customer->profile_image_url;
        }

        return $this->defaultImageUrl();
    }

    protected function defaultImageUrl()
    {
        return 'https://ui-avatars.com/api/?name=User&background=17bfd0&color=fff&size=128';
    }
}