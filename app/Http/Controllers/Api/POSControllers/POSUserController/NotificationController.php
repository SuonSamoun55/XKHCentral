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
        $perPage = $request->get('limit', 10);

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

        // Filter based on tab (use category column, not type)
        if ($tab === 'spam') {
            $query->where('category', 'spam');
        } elseif ($tab === 'archive') {
            $query->where('category', 'archive');
        } else { // inbox is default
            $query->where(function ($q) {
                $q->where('category', 'inbox')
                    ->orWhereNull('category');
            });
        }

        // Filter unread if requested
        if ($request->get('unread') === 'true') {
            $query->where('is_read', false);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $notifications = $query->paginate($perPage)->withQueryString();

        // Set profile image display for notifications (sender avatar)
        $notifications->getCollection()->transform(function ($notification) {
            // For POS user notifications, sender is typically admin/system
            $notification->sender_profile_image_display = $this->getSenderImageDisplay($notification);
            return $notification;
        });

        // Get counts for each tab
        $inboxCount = Notification::where('user_id', $user->id)
            ->where(function ($q) {
                $q->where('category', 'inbox')
                    ->orWhereNull('category');
            })
            ->count();

        $spamCount = Notification::where('user_id', $user->id)
            ->where('category', 'spam')
            ->count();

        $archiveCount = Notification::where('user_id', $user->id)
            ->where('category', 'archive')
            ->count();

        $unreadCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return view('POSViews.POSUserViews.POSItemNotiView', compact(
            'notifications',
            'inboxCount',
            'spamCount',
            'archiveCount',
            'unreadCount',
            'tab'
        ));
    }

    public function unreadNotifications(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $unreadToasts = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();

        $unreadCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'unread_count' => $unreadCount,
            'unread' => $unreadToasts->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'title' => $notif->title,
                    'message' => $notif->message,
                    'created_at' => $notif->created_at->toDateTimeString(),
                ];
            }),
        ]);
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
    public function show($id)
{
    $notification = Notification::findOrFail($id);

    // mark as read automatically
    if (!$notification->is_read) {
        $notification->is_read = 1;
        $notification->save();
    }

        // Set sender profile image display
        $notification->sender_profile_image_display = $this->getSenderImageDisplay($notification);


        $ids = $request->input('notification_ids', []);

        if (!is_array($ids) || count($ids) === 0) {
            return back()->with('error', 'No notifications selected.');
        }

        Notification::where('user_id', $user->id)
            ->whereIn('id', $ids)
            ->delete();

        return back()->with('success', 'Selected notifications deleted.');
    }

    protected function getSenderImageDisplay($notification)
    {
        // For POS user notifications, sender is typically admin/system
        // You can customize this based on notification type or add sender info to notification model
        if ($notification->type === 'admin_message') {
            // Return admin avatar or system icon
            return asset('images/pos/admin-avatar.png'); // You can create this image
        }

        // Default system/admin avatar
        return asset('images/pos/Rectangle 2.png'); // Use existing system avatar
    }
}
