<?php

namespace App\Http\Controllers\Api\ManagementSystemController;

use App\Http\Controllers\Controller;
use App\Models\MagamentSystemModel\Notification;
use App\Models\MagamentSystemModel\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminNotificationController extends Controller
{
    public function index(Request $request)
    {
        $selectedCompanyId = session('selected_company_id');
        $tab = $request->get('tab', 'order_notification');
        if (in_array($tab, ['all', 'new'], true)) {
            $tab = 'order_notification';
        }

        $notificationQuery = $this->baseAdminNotificationQuery($selectedCompanyId);

        if ($request->filled('search')) {
            $search = trim($request->search);

            $notificationQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('message', 'like', '%' . $search . '%')
                    ->orWhere('type', 'like', '%' . $search . '%')
                    ->orWhere('sender_name', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%')
                            ->orWhere('phone', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('date')) {
            $notificationQuery->whereDate('created_at', $request->date);
        }

        if ($tab === 'order_notification') {
            $this->applyOrderNotificationFilter($notificationQuery);
        } elseif ($tab === 'user_contact') {
            $this->applyUserContactFilter($notificationQuery);
        } elseif ($tab === 'out_of_stock') {
            $this->applyOutOfStockFilter($notificationQuery);
        } elseif ($tab === 'global_message') {
            $this->applyGlobalMessageFilter($notificationQuery);
        }

        $notifications = $notificationQuery
            ->latest('updated_at')
            ->paginate(10)
            ->appends($request->query());

        $notifications->getCollection()->transform(function ($notification) {
            if ($notification->user) {
                $notification->user->profile_image_display = $this->getCustomerImageDisplay($notification->user);
            }

            return $notification;
        });

        $baseCountQuery = $this->baseAdminNotificationQuery($selectedCompanyId);

        $orderCount = $this->unreadBadgeCount(
            $this->applyOrderNotificationFilter(clone $baseCountQuery)
        );

        $userContactCount = $this->unreadBadgeCount(
            $this->applyUserContactFilter(clone $baseCountQuery)
        );

        $outOfStockCount = $this->unreadBadgeCount(
            $this->applyOutOfStockFilter(clone $baseCountQuery)
        );

        $globalMessageCount = $this->unreadBadgeCount(
            $this->applyGlobalMessageFilter(clone $baseCountQuery)
        );

        $customers = User::query()
            ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->where('company_id', $selectedCompanyId);
            })
            ->where('status', true)
            ->where('role', '!=', 'admin')
            ->orderBy('name')
            ->get();

        return view(
            'ManagementSystemViews.AdminViews.Layouts.Notifications.AdminNotificationViews',
            compact(
                'notifications',
                'customers',
                'orderCount',
                'userContactCount',
                'outOfStockCount',
                'globalMessageCount',
                'tab'
            )
        );
    }

    public function show($id)
    {
        $selectedCompanyId = session('selected_company_id');

        $notification = $this->baseAdminNotificationQuery($selectedCompanyId)
            ->findOrFail($id);

        if ($notification->user) {
            $notification->user->profile_image_display = $this->getCustomerImageDisplay($notification->user);
        }

        if (!$notification->is_read) {
            $notification->update([
                'is_read' => true,
                'unread_count' => 0,
            ]);
        }

        return view(
            'ManagementSystemViews.AdminViews.Layouts.Notifications.NotificationsViews',
            compact('notification')
        );
    }

    public function searchCustomers(Request $request)
    {
        $selectedCompanyId = session('selected_company_id');
        $keyword = trim((string) $request->get('q', ''));

        $customers = User::query()
            ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->where('company_id', $selectedCompanyId);
            })
            ->where('status', true)
            ->where('role', '!=', 'admin')
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%')
                        ->orWhere('email', 'like', '%' . $keyword . '%')
                        ->orWhere('phone', 'like', '%' . $keyword . '%')
                        ->orWhere('bc_customer_no', 'like', '%' . $keyword . '%');
                });
            })
            ->orderBy('name')
            ->limit(15)
            ->get([
                'id',
                'name',
                'email',
                'phone',
                'bc_customer_no',
                'profile_image',
                'profile_image_url',
            ]);

        $mapped = $customers->map(function ($customer) {
            $avatar = asset('images/default-avatar.png');

            if (!empty($customer->profile_image)) {
                $avatar = asset('storage/' . ltrim($customer->profile_image, '/'));
            } elseif (!empty($customer->profile_image_url)) {
                $avatar = $customer->profile_image_url;
            } elseif (!empty($customer->bc_customer_no)) {
                $avatar = route('users.bc.image', $customer->bc_customer_no);
            }

            return [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email ?? 'No Email',
                'phone' => $customer->phone ?? '',
                'customer_no' => $customer->bc_customer_no ?? 'No Customer No',
                'avatar' => $avatar,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => $mapped,
        ]);
    }

    public function latestNotifications(Request $request)
    {
        $selectedCompanyId = session('selected_company_id');
        $lastId = (int) $request->get('last_id', 0);
        $lastSeenAt = $request->get('last_seen_at');

        $notifications = $this->baseAdminNotificationQuery($selectedCompanyId)
            ->where(function ($query) use ($lastId, $lastSeenAt) {
                $query->where('id', '>', $lastId);

                if (!empty($lastSeenAt)) {
                    $query->orWhere('updated_at', '>', $lastSeenAt);
                }
            })
            ->latest('updated_at')
            ->limit(20)
            ->get();

        $mapped = $notifications->map(function ($notification) {
            $user = $notification->user;
            $sender = $notification->sender;
            $isUserContact = ($notification->type === 'user_contact');
            $contactUser = $isUserContact ? ($sender ?: $user) : ($user ?: $sender);
            $avatar = asset('images/default-avatar.png');

            if ($contactUser && !empty($contactUser->profile_image)) {
                $avatar = asset('storage/' . ltrim($contactUser->profile_image, '/'));
            } elseif ($contactUser && !empty($contactUser->profile_image_url)) {
                $avatar = $contactUser->profile_image_url;
            } elseif ($contactUser && !empty($contactUser->bc_customer_no)) {
                $avatar = route('users.bc.image', $contactUser->bc_customer_no);
            } elseif ($sender && !empty($sender->profile_image)) {
                $avatar = asset('storage/' . ltrim($sender->profile_image, '/'));
            } elseif ($sender && !empty($sender->profile_image_url)) {
                $avatar = $sender->profile_image_url;
            }

            $displayName = optional($contactUser)->name
                ?? ($notification->sender_name ?: optional($sender)->name)
                ?? 'System';

            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => strip_tags($notification->message ?? ''),
                'type' => $notification->type,
                'is_read' => (bool) $notification->is_read,
                'time' => optional($notification->updated_at)->format('H:i'),
                'created_at' => optional($notification->updated_at)->toDateTimeString(),
                'unread_count' => max(0, (int) ($notification->unread_count ?? 0)),
                'show_url' => route('admin.notifications.show', $notification->id),
                'user_name' => $displayName,
                'contact_user_id' => optional($contactUser)->id,
                'avatar' => $avatar,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => $mapped,
            'last_id' => $notifications->max('id') ?? $lastId,
            'last_seen_at' => optional($notifications->max('updated_at'))->toDateTimeString() ?? $lastSeenAt,
        ]);
    }

    public function store(Request $request)
    {
        $selectedCompanyId = session('selected_company_id');
        $sender = Auth::user();

        $request->validate([
            'send_type' => 'required|in:all,specific,multiple',
            'user_id' => 'nullable|exists:users,id',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'nullable|exists:users,id',
            'type' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $allowedHtml = '<b><strong><i><em><u><ul><ol><li><br><p><div>';
        $cleanMessage = trim(strip_tags($request->message, $allowedHtml));

        if ($cleanMessage === '') {
            return back()->withInput()->with('error', 'Message cannot be empty.');
        }

        $customerQuery = User::query()
            ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->where('company_id', $selectedCompanyId);
            })
            ->where('status', true)
            ->where('role', '!=', 'admin');

        if ($request->send_type === 'all') {
            $customers = $customerQuery->get();

            if ($customers->isEmpty()) {
                return back()->withInput()->with('error', 'No active customers found.');
            }

            $groupKey = 'broadcast-' . Str::uuid();

            foreach ($customers as $customer) {
                $this->createSingleUserNotification($customer, $request, $cleanMessage, $sender, $groupKey, 'global_message');
            }

            $this->createBulkSummaryNotification($customers, $request, $cleanMessage, $sender, $groupKey, 'global_message');

            return back()->with('success', 'Notification sent to all customers successfully.');
        }

        if ($request->send_type === 'specific') {
            if (empty($request->user_id)) {
                return back()->withInput()->with('error', 'Please select a customer.');
            }

            $customer = (clone $customerQuery)->where('id', $request->user_id)->first();

            if (!$customer) {
                return back()->withInput()->with('error', 'Selected customer not found.');
            }

            $this->createSingleUserNotification($customer, $request, $cleanMessage, $sender, null);

            return back()->with('success', 'Notification sent successfully.');
        }

        $selectedIds = collect($request->user_ids ?? [])
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($selectedIds->isEmpty()) {
            return back()->withInput()->with('error', 'Please select at least one customer.');
        }

        $customers = (clone $customerQuery)
            ->whereIn('id', $selectedIds->all())
            ->get();

        if ($customers->isEmpty()) {
            return back()->withInput()->with('error', 'Selected customers not found.');
        }

        $groupKey = 'broadcast-' . Str::uuid();

        foreach ($customers as $customer) {
            $this->createSingleUserNotification($customer, $request, $cleanMessage, $sender, $groupKey);
        }

        $this->createBulkSummaryNotification($customers, $request, $cleanMessage, $sender, $groupKey);

        return back()->with('success', 'Notification sent to selected customers successfully.');
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);

        if (!$notification->is_read) {
            $notification->update([
                'is_read' => true,
                'unread_count' => 0,
            ]);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        $selectedCompanyId = session('selected_company_id');

        $this->baseAdminNotificationQuery($selectedCompanyId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'unread_count' => 0,
            ]);

        return back()->with('success', 'All notifications marked as read.');
    }

    public function deleteSelected(Request $request)
    {
        $selectedCompanyId = session('selected_company_id');
        $ids = $request->input('notification_ids', []);

        if (!is_array($ids) || count($ids) === 0) {
            return back()->with('error', 'No notifications selected.');
        }

        $this->baseAdminNotificationQuery($selectedCompanyId)
            ->whereIn('id', $ids)
            ->delete();

        return back()->with('success', 'Selected notifications deleted.');
    }

    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return back()->with('success', 'Notification deleted successfully.');
    }

    protected function getCustomerImageDisplay($user)
    {
        if (!empty($user->profile_image)) {
            return asset('storage/' . $user->profile_image);
        }

        if (!empty($user->profile_image_url)) {
            return $user->profile_image_url;
        }

        if (!empty($user->bc_id)) {
            return route('users.bc-image', ['bcId' => $user->bc_id]);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'User') . '&background=17bfd0&color=fff&size=128';
    }

    protected function baseAdminNotificationQuery(?int $selectedCompanyId = null): Builder
    {
        return Notification::with(['user', 'sender'])
            ->where(function ($q) {
                $q->whereNull('group_key')
                    ->orWhere('is_group_summary', true);
            })
            ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->where(function ($q) use ($selectedCompanyId) {
                    $q->whereHas('user', function ($uq) use ($selectedCompanyId) {
                        $uq->where('company_id', $selectedCompanyId);
                    })->orWhere('is_group_summary', true);
                });
            });
    }

    protected function applyOrderNotificationFilter(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereIn('type', ['order', 'order_notification', 'user'])
                ->orWhere('title', 'like', '%order%')
                ->orWhere('message', 'like', '%order%');
        });
    }

    protected function applyUserContactFilter(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->where('type', 'user_contact')
                ->orWhere('title', 'like', '%contact%')
                ->orWhere('message', 'like', '%contact%');
        });
    }

    protected function applyOutOfStockFilter(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->where('type', 'out_of_stock')
                ->orWhere('title', 'like', '%out of stock%')
                ->orWhere('message', 'like', '%out of stock%');
        });
    }

    protected function applyGlobalMessageFilter(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->where('type', 'global_message')
                ->orWhere('title', 'like', '%global message%')
                ->orWhere('message', 'like', '%global message%');
        });
    }

    protected function unreadBadgeCount(Builder $query): int
    {
        return (int) $query
            ->selectRaw("
                COALESCE(SUM(
                    CASE
                        WHEN is_read = 0 THEN
                            CASE
                                WHEN unread_count IS NULL OR unread_count < 1 THEN 1
                                ELSE unread_count
                            END
                        ELSE 0
                    END
                ), 0) AS unread_total
            ")
            ->value('unread_total');
    }

    protected function createSingleUserNotification(
        User $customer,
        Request $request,
        string $cleanMessage,
        ?User $sender,
        ?string $groupKey,
        ?string $forcedType = null
    ): void {
        Notification::create([
            'user_id' => $customer->id,
            'sender_id' => $sender?->id,
            'sender_name' => $sender?->name,
            'sender_profile_image' => $sender?->profile_image_display,
            'order_id' => null,
            'item_id' => null,
            'type' => $forcedType ?: $request->type,
            'title' => $request->title,
            'message' => $cleanMessage,
            'group_key' => $groupKey,
            'is_group_summary' => false,
            'unread_count' => 1,
            'is_read' => false,
        ]);
    }

    protected function createBulkSummaryNotification(
        $customers,
        Request $request,
        string $cleanMessage,
        ?User $sender,
        string $groupKey,
        ?string $forcedType = null
    ): void {
        $recipientSummary = $this->formatRecipientsForSummary($customers);

        Notification::create([
            'user_id' => null,
            'sender_id' => $sender?->id,
            'sender_name' => $sender?->name,
            'sender_profile_image' => $sender?->profile_image_display,
            'order_id' => null,
            'item_id' => null,
            'type' => $forcedType ?: $request->type,
            'title' => $request->title . ' (Sent to ' . $customers->count() . ' users)',
            'message' => trim($cleanMessage . "\n\nRecipients: " . $recipientSummary),
            'group_key' => $groupKey,
            'is_group_summary' => true,
            'unread_count' => 1,
            'is_read' => false,
        ]);
    }

    protected function formatRecipientsForSummary($customers): string
    {
        $visibleRecipients = $customers
            ->take(30)
            ->map(function ($customer) {
                $name = trim((string) ($customer->name ?? 'Unknown'));
                $email = trim((string) ($customer->email ?? 'No email'));

                return $name . ' <' . $email . '>';
            })
            ->implode(', ');

        $remaining = max(0, $customers->count() - 30);

        if ($remaining > 0) {
            return $visibleRecipients . ', +' . $remaining . ' more';
        }

        return $visibleRecipients;
    }
}

