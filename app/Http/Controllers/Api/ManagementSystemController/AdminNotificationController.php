<?php

namespace App\Http\Controllers\Api\ManagementSystemController;

use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Auth;
use App\Models\MagamentSystemModel\Notification;
// use Illuminate\Support\Facades\Auth;

class AdminNotificationController extends Controller
{

public function index(){
    $notifications = Notification::latest()->get();

        return view(
            'ManagementSystemViews.AdminViews.Layouts.Notifications.AdminNotificationViews',
            compact('notifications')
        );

}



}
