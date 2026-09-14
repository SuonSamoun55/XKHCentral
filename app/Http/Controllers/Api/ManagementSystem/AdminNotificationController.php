<?php

namespace App\Http\Controllers\Api\ManagementSystem;

use App\Http\Controllers\Controller;
use App\Models\ManagementSystem\Notification;
use App\Models\ManagementSystem\User;
use App\Models\POS\Item;
use App\Models\POS\Order;
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
        $perPage = (int) $request->get('per_page', 10);
        $allowedPerPage = [10, 20, 50];

        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }

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

        /** @var \Illuminate\Pagination\LengthAwarePaginator $notifications */
        $notifications = $notificationQuery
            ->latest('updated_at')
            ->paginate($perPage)
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
                'tab',
                'perPage'
            )
        );
    }

    public function show(Request $request, $id)
    {
        $selectedCompanyId = session('selected_company_id');

        $notification = $this->baseAdminNotificationQuery($selectedCompanyId)
            ->with(['order'])
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

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'id' => $notification->id,
                'redirect_url' => route('admin.notifications.show', $notification->id),
            ]);
        }

        // Items are paginated for the desktop table, but the order summary
        // (VAT, item count) needs totals across ALL items regardless of
        // which page is showing — summed at the DB level rather than from
        // whatever page happened to be loaded into memory. The phone
        // receipt layout shows every item on one scroll (no pagination),
        // so it gets its own unpaginated copy of the same query.
        $orderItems = null;
        $allOrderItems = null;
        $orderItemsTotal = 0;
        $orderVat = 0.0;

        if ($notification->type === 'order' && $notification->order) {
            $orderItemsQuery = $notification->order->items();
            $orderItemsTotal = (clone $orderItemsQuery)->count();
            $orderVat = (float) (clone $orderItemsQuery)->sum('tax_amount');
            $allOrderItems = (clone $orderItemsQuery)
                ->with(['item', 'itemVariant'])
                ->orderBy('id')
                ->get();
            $orderItems = $orderItemsQuery
                ->with(['item', 'itemVariant'])
                ->orderBy('id')
                ->paginate(4, ['*'], 'items_page')
                ->withQueryString();
        }

        // Out-of-stock alerts only store item_id (no order_id), and the
        // "current stock" / "reserved" figures need to reflect the item's
        // live state rather than whatever it was when the alert fired —
        // so these are recomputed here the same way
        // OrderController::notifyLowStockIfNeeded() derives them, instead
        // of trusting anything cached on the notification row.
        $stockItem = null;
        $stockCurrent = 0;
        $stockReserved = 0;
        $stockLevel = null;
        $stockOrder = null;

        if ($notification->type === 'out_of_stock' && $notification->item_id) {
            $stockItem = Item::find($notification->item_id);

            if ($stockItem) {
                $stockCurrent = (float) $stockItem->inventory;

                $stockReserved = (int) \App\Models\POS\OrderItem::query()
                    ->from('order_items as oi')
                    ->join('orders as o', 'o.id', '=', 'oi.order_id')
                    ->where('oi.item_id', $stockItem->id)
                    ->where('o.status', 'pending')
                    ->sum('oi.qty');

                $stockOrder = Order::query()
                    ->where('status', 'pending')
                    ->whereHas('items', function ($q) use ($stockItem) {
                        $q->where('item_id', $stockItem->id);
                    })
                    ->latest('id')
                    ->first();

                $stockLevel = $stockCurrent <= 0
                    ? ['label' => 'Out of stock', 'class' => 'critical']
                    : (($stockReserved >= 0.8 * $stockCurrent)
                        ? ['label' => 'Low stock', 'class' => 'warning']
                        : ['label' => 'Notice', 'class' => 'notice']);
            }
        }

        return view(
            'ManagementSystemViews.AdminViews.Layouts.Notifications.NotificationsViews',
            compact(
                'notification',
                'orderItems',
                'allOrderItems',
                'orderItemsTotal',
                'orderVat',
                'stockItem',
                'stockCurrent',
                'stockReserved',
                'stockLevel',
                'stockOrder'
            )
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
            return [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email ?? 'No Email',
                'phone' => $customer->phone ?? '',
                'customer_no' => $customer->bc_customer_no ?? 'No Customer No',
                'avatar' => $this->getCustomerImageDisplay($customer),
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
            ->where('id', '>', $lastId)
            ->latest('updated_at')
            ->limit(20)
            ->get();
        $mapped = $notifications->map(function ($notification) {
            $user = $notification->user;
            $sender = $notification->sender;
            $isUserContact = ($notification->type === 'user_contact');
            $contactUser = $isUserContact ? ($sender ?: $user) : ($user ?: $sender);
            $avatar = $this->getCustomerImageDisplay($contactUser, $sender);

            $displayName = optional($contactUser)->name
                ?? ($notification->sender_name ?: optional($sender)->name)
                ?? 'System';

            $chatUrl = optional($contactUser)->id
                ? route('admin.chat.index', ['user_id' => $contactUser->id])
                : null;

            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => trim(preg_replace(
                    '/\s+/',
                    ' ',
                    html_entity_decode(strip_tags($notification->message ?? ''), ENT_QUOTES, 'UTF-8')
                )),
                'type' => $notification->type,
                'is_read' => (bool) $notification->is_read,
                'time' => optional($notification->updated_at)->format('H:i'),
                'created_at' => optional($notification->updated_at)->toDateTimeString(),
                'unread_count' => max(0, (int) ($notification->unread_count ?? 0)),
                'show_url' => ($isUserContact && $chatUrl) ? $chatUrl : route('admin.notifications.show', $notification->id),
                'chat_url' => $chatUrl,
                'user_name' => $displayName,
                'contact_user_id' => optional($contactUser)->id,
                'avatar' => $avatar,
            ];
        })->values();

        $baseCountQuery = $this->baseAdminNotificationQuery($selectedCompanyId);

        return response()->json([
            'success' => true,
            'data' => $mapped,
            'last_id' => $notifications->max('id') ?? $lastId,
            'last_seen_at' => optional($notifications->max('updated_at'))->toDateTimeString(),
            'counts' => [
                'order_notification' => $this->unreadBadgeCount(
                    $this->applyOrderNotificationFilter(clone $baseCountQuery)
                ),
                'user_contact' => $this->unreadBadgeCount(
                    $this->applyUserContactFilter(clone $baseCountQuery)
                ),
                'out_of_stock' => $this->unreadBadgeCount(
                    $this->applyOutOfStockFilter(clone $baseCountQuery)
                ),
                'global_message' => $this->unreadBadgeCount(
                    $this->applyGlobalMessageFilter(clone $baseCountQuery)
                ),
            ],
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

        $cleanMessage = $this->sanitizeNotificationMessage($request->message);

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

            $this->createBulkUserNotifications($customers, $request, $cleanMessage, $sender, $groupKey, 'global_message');
            $this->createBulkSummaryNotification($customers, $request, $cleanMessage, $sender, $groupKey, 'global_message');

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notification sent to all customers successfully.',
                ]);
            }

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

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notification sent successfully.',
                ]);
            }

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

        $this->createBulkUserNotifications($customers, $request, $cleanMessage, $sender, $groupKey);
        $this->createBulkSummaryNotification($customers, $request, $cleanMessage, $sender, $groupKey);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification sent to selected customers successfully.',
            ]);
        }

        return back()->with('success', 'Notification sent to selected customers successfully.');
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = $this->baseAdminNotificationQuery(session('selected_company_id'))->findOrFail($id);

        if (!$notification->is_read) {
            $notification->update([
                'is_read' => true,
                'unread_count' => 0,
            ]);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read.',
                'id' => $notification->id,
            ]);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead(Request $request)
    {
        $selectedCompanyId = session('selected_company_id');

        $updated = $this->baseAdminNotificationQuery($selectedCompanyId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'unread_count' => 0,
            ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read.',
                'updated' => $updated,
            ]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    public function markSelectedAsRead(Request $request)
    {
        $selectedCompanyId = session('selected_company_id');
        $ids = $request->input('notification_ids', []);

        if (!is_array($ids) || count($ids) === 0) {
            return back()->with('error', 'No notifications selected.');
        }

        $updated = $this->baseAdminNotificationQuery($selectedCompanyId)
            ->whereIn('id', $ids)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'unread_count' => 0,
            ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Selected notifications marked as read.',
                'updated' => $updated,
            ]);
        }

        return back()->with('success', 'Selected notifications marked as read.');
    }

    public function deleteSelected(Request $request)
    {
        $selectedCompanyId = session('selected_company_id');
        $ids = $request->input('notification_ids', []);

        if (!is_array($ids) || count($ids) === 0) {
            return back()->with('error', 'No notifications selected.');
        }

        $deleted = $this->baseAdminNotificationQuery($selectedCompanyId)
            ->whereIn('id', $ids)
            ->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Selected notifications deleted.',
                'deleted' => $deleted,
                'ids' => array_values(array_map('intval', $ids)),
            ]);
        }

        return back()->with('success', 'Selected notifications deleted.');
    }

    public function destroy(Request $request, $id)
    {
        $notification = $this->baseAdminNotificationQuery(session('selected_company_id'))->findOrFail($id);
        $notification->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully.',
                'id' => (int) $id,
            ]);
        }

        return back()->with('success', 'Notification deleted successfully.');
    }

    /**
     * @param mixed $fallbackUser Checked for a profile image/URL when $user has neither
     *                            (used for notification rows where the primary contact
     *                            has no image but the sender does).
     */
    protected function getCustomerImageDisplay($user, $fallbackUser = null)
    {
        if ($user && !empty($user->profile_image)) {
            return asset('storage/' . ltrim($user->profile_image, '/'));
        }

        if ($user && !empty($user->profile_image_url)) {
            return $user->profile_image_url;
        }

        if ($user && !empty($user->bc_customer_no)) {
            return route('users.bc-image', ['bcId' => $user->bc_customer_no]);
        }

        if ($fallbackUser) {
            if (!empty($fallbackUser->profile_image)) {
                return asset('storage/' . ltrim($fallbackUser->profile_image, '/'));
            }

            if (!empty($fallbackUser->profile_image_url)) {
                return $fallbackUser->profile_image_url;
            }
        }

        return asset('images/default-avatar.png');
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
            })
            // 'user_contact' is the one type where `user_id` names a specific
            // admin (the one a customer picked to message via ChatController::
            // userSend()), not a customer — so unlike every other type, it must
            // not be visible to every admin in the company, only the one it
            // was actually sent to.
            ->where(function ($q) {
                $q->where('type', '!=', 'user_contact')
                    ->orWhere('user_id', Auth::id());
            });
    }

    // Each tab is scoped strictly by the notification's own `type` column.
    // Previously these also fuzzy-matched title/message text (e.g. "%order%",
    // "%out of stock%"), which meant a cancelled-order notification whose
    // message happened to say "...already out of stock..." leaked into the
    // Out of Stock tab even though its real type is 'order'. Strict type
    // matching keeps each notification in exactly one tab, and since the
    // unread badge counts reuse these same filters, it fixes the counts too.
    protected function applyOrderNotificationFilter(Builder $query): Builder
    {
        return $query->where('type', 'order');
    }

    protected function applyUserContactFilter(Builder $query): Builder
    {
        return $query->where('type', 'user_contact');
    }

    protected function applyOutOfStockFilter(Builder $query): Builder
    {
        return $query->where('type', 'out_of_stock');
    }

    protected function applyGlobalMessageFilter(Builder $query): Builder
    {
        // Strictly 'global_message' — the type only set when the "Send
        // Message" form is submitted with send_type = 'all' (broadcast to
        // every customer). 'admin_message' is excluded on purpose: it's
        // reused both by 1-on-1 admin chat replies (ChatController::adminSend)
        // and by "Send Message" sends to a specific/selected customer, so
        // including it here was leaking live chat conversations into this tab.
        return $query->where('type', 'global_message');
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
            'sender_profile_image' => $this->getSenderProfileImage($sender),
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

    /**
     * Same rows createSingleUserNotification() would create one-by-one, but
     * as a single bulk INSERT — sending to "all customers" was issuing one
     * query per recipient (a few hundred/thousand for a large company).
     */
    protected function createBulkUserNotifications(
        $customers,
        Request $request,
        string $cleanMessage,
        ?User $sender,
        ?string $groupKey,
        ?string $forcedType = null
    ): void {
        if ($customers->isEmpty()) {
            return;
        }

        $senderProfileImage = $this->getSenderProfileImage($sender);
        $type = $forcedType ?: $request->type;
        $now = now();

        $rows = $customers->map(fn ($customer) => [
            'user_id' => $customer->id,
            'sender_id' => $sender?->id,
            'sender_name' => $sender?->name,
            'sender_profile_image' => $senderProfileImage,
            'order_id' => null,
            'item_id' => null,
            'type' => $type,
            'title' => $request->title,
            'message' => $cleanMessage,
            'group_key' => $groupKey,
            'is_group_summary' => false,
            'unread_count' => 1,
            'is_read' => false,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all();

        Notification::insert($rows);
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
            'sender_profile_image' => $this->getSenderProfileImage($sender),
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

    protected function sanitizeNotificationMessage(?string $message): string
    {
        $allowedHtml = '<a><b><strong><i><em><u><ul><ol><li><br><p><div>';
        $cleanMessage = trim(strip_tags((string) $message, $allowedHtml));

        if ($cleanMessage === '') {
            return '';
        }

        return preg_replace_callback(
            '/<a\b[^>]*href=(["\'])(.*?)\1[^>]*>/i',
            function ($matches) {
                $href = trim(html_entity_decode($matches[2], ENT_QUOTES, 'UTF-8'));

                if ($href === '' || preg_match('/^\s*javascript:/i', $href)) {
                    $href = '#';
                } elseif (!preg_match('/^(https?:\/\/|mailto:)/i', $href)) {
                    $href = 'https://' . ltrim($href, '/');
                }

                return '<a href="' . e($href) . '" target="_blank" rel="noopener noreferrer">';
            },
            $cleanMessage
        );
    }

    protected function getSenderProfileImage(?User $sender): ?string
    {
        if (!$sender) {
            return null;
        }

        if (!empty($sender->profile_image)) {
            return asset('storage/' . ltrim($sender->profile_image, '/'));
        }

        if (!empty($sender->profile_image_url)) {
            return $sender->profile_image_url;
        }

        return null;
    }
}
