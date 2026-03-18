<?php

namespace App\Http\Controllers\Api\ManagementSystemController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // check if login
        if (!$user) {
            return redirect()->route('login');
        }

        // allow only admin
        if ($user->role !== 'admin') {
            abort(403, 'Only admin can access this page.');
        }

        ini_set('memory_limit', '512M');

        return view('ManagementSystemViews.AdminViews.Layouts.DaskboardView.Dashboard');
    }
}
