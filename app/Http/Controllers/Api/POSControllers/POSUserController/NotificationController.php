<?php

namespace App\Http\Controllers\Api\POSControllers\POSUserController;

use App\Http\Controllers\Controller;
use App\Models\MagamentSystemModel\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotifications(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        $tab = $request->get('tab', 'inbox');

        $query = Notification::where('user_id', $user->id)->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('message', 'like', '%' . $search . '%')
                    ->orWhere('type', 'like', '%' . $search . '%');
            });
        }

        if ($tab === 'unread') {
            $query->where('is_read', false);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $notifications = $query->paginate(10)->withQueryString();

        $allCount = Notification::where('user_id', $user->id)->count();

        $unreadCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return view('POSViews.POSUserViews.POSItemNotiView', compact(
            'notifications',
            'allCount',
            'unreadCount',
            'tab'
        ));
    }

    public function markAsRead($id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        $notification = Notification::where('user_id', $user->id)->findOrFail($id);

        if (!$notification->is_read) {
            $notification->update([
                'is_read' => true,
            ]);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
            ]);

        return back()->with('success', 'All notifications marked as read.');
    }

    public function deleteSelected(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        $ids = $request->input('notification_ids', []);

        if (!is_array($ids) || count($ids) === 0) {
            return back()->with('error', 'No notifications selected.');
        }

        Notification::where('user_id', $user->id)
            ->whereIn('id', $ids)
            ->delete();

        return back()->with('success', 'Selected notifications deleted.');
    }
}
