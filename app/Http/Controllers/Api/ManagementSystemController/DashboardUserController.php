<?php

namespace App\Http\Controllers\Api\ManagementSystemController;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardUserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('ManagementSystemViews.UserViews.DashboardUser');
    }
}
