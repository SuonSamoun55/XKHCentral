<?php

namespace App\Http\Controllers\Api\POSControllers\POSUserController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
class PolicyController extends Controller
{
    public function index()
    {
        return view('POSViews.POSUserViews.mobile.PrivacyPolicy');
    }
}