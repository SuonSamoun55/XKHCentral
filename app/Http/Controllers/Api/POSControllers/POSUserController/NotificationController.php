<?php

namespace App\Http\Controllers\Api\POSControllers\POSUserController;

use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
   public function getNotifications()
{
    $notifications = [];
    return view('POSViews.POSUserViews.POSItemNotiView', compact('notifications'));
}
}