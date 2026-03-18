<?php

namespace App\Http\Controllers\Api\ManagementSystemController;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardUserController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ini_set('memory_limit', '512M');

        return view('ManagementSystemViews.UserViews.DashboardUser');
    }
}
