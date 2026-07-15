<?php

namespace App\Http\Controllers\Api\POS\User\Notifications;

use App\Http\Controllers\Controller;
use App\Models\ManagementSystem\Notification;
use App\Models\ManagementSystem\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class NotificationController extends Controller
{
    private const PER_PAGE_DEFAULT = 10;

    private const ADMIN_TYPES = [
        'admin_message',
        'global_message',
    ];

    private const DEFAULT_IMAGE = 'images/pos/Rectangle 2.png';

    public function getNotifications(Request $request)
    {
        $user = $request->user();

        if (!$user instanceof User) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        $perPage = (int) $request->input('limit', self::PER_PAGE_DEFAULT);
        $tab = $request->input('tab', 'orderNotification');

        $notificationsQuery = $this->baseQuery($user->id);

        $this->applyTab($notificationsQuery, $tab);
        $this->applyFilters($notificationsQuery, $request);

        $notifications = $this->paginate($notificationsQuery, $request, $perPage, 'page');

        $adminQuery = $this->baseQuery($user->id)
            ->whereIn('type', self::ADMIN_TYPES);

        $adminMessages = $this->paginate($adminQuery, $request, $perPage, 'admin_page');
        $inboxCount = Notification::where('user_id', $user->id)
            ->where(function ($q) {
                $q->where('category', 'inbox')->orWhereNull('category');
            })
            ->count();

        $globalMessageCount = Notification::where('user_id', $user->id)
            ->where('type', 'global_message')
            ->count();

        $unreadCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
        $contactList = $this->contactList($user->id);

        return view('POSViews.POSUserViews.Notifications.index', compact(
            'notifications',
            'adminMessages',
            'inboxCount',
            'globalMessageCount',
            'unreadCount',
            'tab',
            'contactList'
        ));
    }
    private function baseQuery(int $userId): Builder
    {
        return Notification::query()
            ->with('sender')
            ->where('user_id', $userId)
            ->latest();
    }
    private function applyTab(Builder $query, string $tab): void
    {
        if ($tab === 'adminMessage') {
            $query->whereIn('type', self::ADMIN_TYPES);
            return;
        }

        $query->where(function ($q) {
            $q->where('category', 'inbox')
              ->orWhereNull('category');
        });
    }
    private function applyFilters(Builder $query, Request $request): void
    {
        if ($search = trim($request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->boolean('unread')) {
            $query->where('is_read', false);
        }
    }

    private function paginate(Builder $query, Request $request, int $perPage, string $pageName): LengthAwarePaginator
    {
        /** @var LengthAwarePaginator $paginator */
        $paginator = $query->paginate($perPage, ['*'], $pageName);

        $paginator->appends($request->except($pageName));

        $paginator->setCollection(
            $paginator->getCollection()->map(function ($item) {
                $item->sender_profile_image_display = $this->senderImage($item);
                $item->sender_name = $this->senderName($item);
                return $item;
            })
        );

        return $paginator;
    }
    private function contactList(int $userId): Collection
    {
        return Notification::with('sender')
            ->where('user_id', $userId)
            ->latest()
            ->get()
            ->groupBy(fn ($n) => $n->sender_id ?? 'system')
            ->map(function ($items) {
                $latest = $items->first();

                return (object)[
                    'id' => $latest->sender_id ?? 0,
                    'name' => $this->senderName($latest),
                    'chat_avatar' => $this->senderImage($latest),
                    'unread_count' => $items->where('is_read', false)->count(),
                ];
            })
            ->values();
    }

    private function senderImage($notification): string
    {
        if ($notification->relationLoaded('sender') && $notification->sender?->profile_image) {
            return asset('storage/' . $notification->sender->profile_image);
        }

        return asset(self::DEFAULT_IMAGE);
    }

    private function senderName($notification): string
    {
        return $notification->sender_name
            ?? $notification->sender?->name
            ?? 'Admin';
    }
}