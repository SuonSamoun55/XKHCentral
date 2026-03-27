<?php

namespace App\Http\Controllers\Api\ManagementSystemController;

use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Auth;

class AdminNotificationController extends Controller
{

public function index(){
    return view('ManagementSystemViews.AdminViews.Layouts.Notifications.AdminNotificationViews');
}
}
