<?php

namespace App\Http\Controllers\Api\POSControllers\POSUserController;

use App\Http\Controllers\Controller;
use App\Models\MagamentSystemModel\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

        $query = Notification::with('sender')
            ->where('user_id', $user->id)
            ->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('message', 'like', '%' . $search . '%')
                    ->orWhere('type', 'like', '%' . $search . '%');
            });
        }

        // Filter based on tab
        if ($tab === 'spam') {
            $query->where('category', 'spam');
        } elseif ($tab === 'archive') {
            $query->where('category', 'archive');
        } elseif ($tab === 'global_message') {
            $query->where('type', 'global_message');
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
            $notification->sender_name = $this->getSenderName($notification);
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

        $globalMessageCount = Notification::where('user_id', $user->id)
            ->where('type', 'global_message')
            ->count();

        $unreadCount = (int) Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->sum('unread_count');

        return view('POSViews.POSUserViews.POSItemNotiView', compact(
            'notifications',
            'inboxCount',
            'spamCount',
            'archiveCount',
            'globalMessageCount',
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

        $unreadToasts = Notification::with('sender')
            ->where('user_id', $user->id)
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();

        $unreadCount = (int) Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->selectRaw('COALESCE(SUM(CASE WHEN unread_count IS NULL OR unread_count < 1 THEN 1 ELSE unread_count END), 0) AS unread_total')
            ->value('unread_total');

        return response()->json([
            'unread_count' => $unreadCount,
            'unread' => $unreadToasts->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'title' => $notif->title,
                    'message' => $notif->message,
                    'created_at' => $notif->created_at->toDateTimeString(),
                    'sender_name' => $this->getSenderName($notif),
                    'avatar' => $this->getSenderImageDisplay($notif),
                    'unread_count' => max(1, (int) ($notif->unread_count ?? 1)),
                ];
            }),
        ]);
    }

    public function markAsRead(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        $notification = Notification::where('user_id', $user->id)->findOrFail($id);

        if (!$notification->is_read) {
            $notification->update([
                'is_read' => true,
                'unread_count' => 0,
            ]);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'id' => $notification->id,
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
                'unread_count' => 0,
            ]);

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
            ]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    public function show($id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        $notification = Notification::with('sender')
            ->where('user_id', $user->id)
            ->findOrFail($id);

        if (!$notification->is_read) {
            $notification->is_read = true;
            $notification->unread_count = 0;
            $notification->save();
        }

        $notification->sender_profile_image_display = $this->getSenderImageDisplay($notification);
        $notification->sender_name = $this->getSenderName($notification);

        return view('notifications.show', compact('notification'));
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

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
            ]);
        }

        return back()->with('success', 'Selected notifications deleted.');
    }

    protected function getSenderImageDisplay($notification)
    {
        if ($notification->relationLoaded('sender') && $notification->sender) {
            $sender = $notification->sender;

            if (!empty($sender->profile_image)) {
                return asset('storage/' . ltrim($sender->profile_image, '/'));
            }

            if (!empty($sender->profile_image_url)) {
                return $sender->profile_image_url;
            }

            $bcId = $sender->bc_id ?? null;
            if (!empty($bcId)) {
                return route('users.bc-image', ['bcId' => $bcId]);
            }
        }

        if (!empty($notification->sender_profile_image)) {
            $raw = trim((string) $notification->sender_profile_image);

            if (Str::startsWith($raw, ['http://', 'https://'])) {
                return $raw;
            }

            if (Str::startsWith($raw, ['/storage/', 'storage/'])) {
                return asset(ltrim($raw, '/'));
            }

            return asset('storage/' . ltrim($raw, '/'));
        }

        return asset('images/pos/Rectangle 2.png');
    }

    protected function getSenderName($notification)
    {
        if (!empty($notification->sender_name)) {
            return $notification->sender_name;
        }

        if ($notification->relationLoaded('sender') && $notification->sender) {
            return $notification->sender->name ?? 'Admin';
        }

        return 'Admin';
    }

    
 
 public function mobileInbox()
{
    $userId = auth()->id();

    // Get all notifications
    $notifications = Notification::where('user_id', $userId)
        ->orderByDesc('created_at')
        ->get();

    // Build contacts from notifications (grouped by sender)
    $contacts = $notifications
        ->groupBy('sender_id')
        ->map(function ($items) {
            $latest = $items->first();

            return (object) [
                'id' => $latest->sender_id,
                'name' => $latest->sender_name ?? 'System',
                'chat_avatar' => $latest->sender_profile_image_display
                    ?? $latest->sender_profile_image
                    ?? asset('images/pos/Rectangle 2.png'),
                'last_message' => $latest->message,
                'last_message_at' => $latest->created_at,
                'unread_count' => $items->where('is_read', false)
                    ->sum(fn ($n) => max(1, (int) ($n->unread_count ?? 1))),
            ];
        })
        ->values();

    return view(
        'POSViews.POSUserViews.mobile.POSInbox_mobile',
        compact('notifications', 'contacts')
    );
}

}
