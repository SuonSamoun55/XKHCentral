<?php

namespace App\Http\Controllers\Api\POS\User\Notifications;

use App\Http\Controllers\Controller;
use App\Models\ManagementSystem\Notification;
use App\Models\ManagementSystem\OrderAction;
use App\Models\ManagementSystem\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
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

        $tabData = $this->classifyNotificationTabs($notifications, $adminMessages);

        return view('POSViews.POSUserViews.Notifications.index', array_merge(
            compact(
                'notifications',
                'adminMessages',
                'inboxCount',
                'globalMessageCount',
                'unreadCount',
                'tab',
                'contactList'
            ),
            $tabData
        ));
    }

    public function show(Request $request, int $id)
    {
        $user = $request->user();

        if (!$user instanceof User) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        $notification = Notification::with(['sender', 'relatedOrderItems.item', 'relatedOrderItems.itemVariant'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        if (!$notification->is_read) {
            $notification->is_read = true;
            $notification->save();
        }

        $notification->sender_profile_image_display = $this->senderImage($notification);
        $notification->sender_name = $this->senderName($notification);
        $orderAction = $this->resolveOrderAction($notification);

        $detail = $this->buildNotificationDetail($notification, $orderAction);

        return view(
            'POSViews.POSUserViews.Notifications.show',
            array_merge(compact('notification', 'orderAction'), $detail)
        );
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

    /**
     * Single source of truth for "is this notification an Admin Message":
     * type is authoritative when it's explicitly admin_message/global_message;
     * otherwise fall back to keyword sniffing on title/message. Any order
     * wording (cancel/confirm/approve/order/received) keeps it in the Order
     * tab even if the text happens to also mention "admin".
     */
    private function isAdminNotification(Notification $notification): bool
    {
        if ($notification->type === 'admin_message') {
            return true;
        }

        if ($notification->type === 'global_message') {
            return false;
        }

        $title = strtolower($notification->title ?? '');
        $message = strtolower($notification->message ?? '');

        $orderKeywords = ['order', 'cancel', 'confirm', 'approve', 'received'];

        foreach ($orderKeywords as $keyword) {
            if (str_contains($title, $keyword) || str_contains($message, $keyword)) {
                return false;
            }
        }

        if (str_contains($title, 'chat message') || str_contains($message, 'chat message')) {
            return true;
        }

        if (str_contains($title, 'admin') || str_contains($message, 'admin')) {
            return true;
        }

        return false;
    }

    /**
     * Attaches the display fields the Order Notification list needs
     * (icon, subject line, attachment flag) so the view never has to run
     * str_contains() itself.
     */
    private function decorateOrderNotification(Notification $notification): Notification
    {
        $titleLower = strtolower($notification->title ?? '');
        $messageLower = strtolower($notification->message ?? '');

        $notification->is_admin_notification = $this->isAdminNotification($notification);
        $notification->has_attachment = str_contains($messageLower, 'attachment');

        if ($notification->is_admin_notification) {
            $notification->display_icon = 'admin';
            $notification->display_subject = 'Admin Message';
        } elseif ($notification->type === 'global_message') {
            $notification->display_icon = 'global';
            $notification->display_subject = 'New Deal';
        } elseif (str_contains($titleLower, 'cancel')) {
            $notification->display_icon = 'cancelled';
            $notification->display_subject = 'Order Cancelled';
        } elseif (str_contains($titleLower, 'confirm') || str_contains($titleLower, 'approve')) {
            $notification->display_icon = 'confirmed';
            $notification->display_subject = 'Order Confirmed';
        } else {
            $notification->display_icon = 'default';
            $notification->display_subject = 'Order Received';
        }

        return $notification;
    }

    /**
     * Attaches the display fields the Admin Message list needs.
     */
    private function decorateAdminNotification(Notification $notification): Notification
    {
        if ($notification->type === 'global_message') {
            $notification->display_status = 'Global Message';
        } else {
            $notification->display_status = 'Admin Message';
        }

        return $notification;
    }

    /**
     * Splits the paginated "order tab" query into the notifications that
     * really belong in the Order tab vs the ones that should display as
     * Admin Messages instead (reclassified by isAdminNotification), then
     * merges those reclassified rows into the admin list and recomputes
     * unread counts for both tabs.
     */
    private function classifyNotificationTabs(LengthAwarePaginator $notifications, LengthAwarePaginator $adminMessages): array
    {
        $orderNotifications = collect();
        $reclassifiedAsAdmin = collect();

        foreach ($notifications->getCollection() as $notification) {
            $this->decorateOrderNotification($notification);

            $isAdmin = $notification->is_admin_notification;
            $isGlobal = $notification->type === 'global_message';

            if ($isAdmin || $isGlobal) {
                if ($isAdmin && !$isGlobal) {
                    $reclassifiedAsAdmin->push($notification);
                }
                continue;
            }

            $orderNotifications->push($notification);
        }

        foreach ($adminMessages->getCollection() as $notification) {
            $this->decorateAdminNotification($notification);
        }

        foreach ($reclassifiedAsAdmin as $notification) {
            $this->decorateAdminNotification($notification);
        }

        $adminMessagesDisplay = $adminMessages->getCollection()->concat($reclassifiedAsAdmin);

        return [
            'orderNotifications' => $orderNotifications,
            'adminMessagesDisplay' => $adminMessagesDisplay,
            'orderUnreadCount' => $orderNotifications->where('is_read', false)->count(),
            'adminUnreadCount' => $adminMessagesDisplay->where('is_read', false)->count(),
        ];
    }

    /**
     * Resolves a stored image path to a working, absolute URL. image_url /
     * custom_image_url may be saved either as a full URL or a relative
     * storage path, so both shapes need to work here — same helper used
     * across the cart/checkout/order/dashboard/product-detail views.
     */
    private function resolveImagePath(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return str_starts_with($path, 'http') ? $path : asset($path);
    }

    /**
     * Priority: the order line's variant image, then the item's
     * admin-set custom_image_url override, then the item's synced
     * image_url. Returns null if none are set — the front end already
     * has its own placeholder image to fall back to.
     */
    private function resolveOrderItemImage($orderItem): ?string
    {
        return $this->resolveImagePath($orderItem->itemVariant?->image_url ?? null)
            ?? $this->resolveImagePath($orderItem->item?->custom_image_url ?? null)
            ?? $this->resolveImagePath($orderItem->item?->image_url ?? null);
    }

    /**
     * For order confirm/cancel notifications, find the matching row in
     * order_actions and eager-load the admin via the actionBy() relation.
     * This replaces the old guesswork of trying several relation names on
     * Notification/Order.
     */
    private function resolveOrderAction(Notification $notification): ?OrderAction
    {
        $isOrderNotification = $notification->type !== 'admin_message' && $notification->type !== 'global_message';

        if (!$isOrderNotification || !$notification->order_id) {
            return null;
        }

        $titleLower = strtolower($notification->title ?? '');
        $messageLower = strtolower($notification->message ?? '');

        $isCancelled = str_contains($titleLower, 'cancel') || str_contains($messageLower, 'cancel');
        $isApproved = str_contains($titleLower, 'confirm')
            || str_contains($titleLower, 'approve')
            || str_contains($messageLower, 'confirm')
            || str_contains($messageLower, 'approve');

        if (!$isCancelled && !$isApproved) {
            return null;
        }

        $query = OrderAction::with('actionBy')
            ->where('order_id', $notification->order_id);

        // order_actions.action_type is 'cancelled' / 'confirmed' per the
        // seeded data — adjust the values here if your table uses different
        // wording (e.g. 'approved').
        $query->where('action_type', $isCancelled ? 'cancelled' : 'confirmed');

        return $query->latest('id')->first();
    }

    /**
     * Builds every value the notification-detail view needs: status badge,
     * sender display, and the order summary/pricing block. Used to be a
     * @php block living in the Blade file — moved here so the view only
     * ever displays variables.
     */
    private function buildNotificationDetail(Notification $notification, ?OrderAction $orderAction): array
    {
        $isOrderNotification = $notification->type !== 'admin_message' && $notification->type !== 'global_message';

        if ($isOrderNotification) {
            $orderItems = $notification->relatedOrderItems;
        } else {
            $orderItems = collect();
        }

        $titleLower = strtolower($notification->title ?? '');
        $messageLower = strtolower($notification->message ?? '');

        $isCancelled = str_contains($titleLower, 'cancel') || str_contains($messageLower, 'cancel');
        $isApproved = str_contains($titleLower, 'confirm')
            || str_contains($titleLower, 'approve')
            || str_contains($messageLower, 'confirm')
            || str_contains($messageLower, 'approve');

        $actionAdmin = $orderAction?->actionBy;

        if ($actionAdmin) {
            $senderSource = $actionAdmin;
        } elseif ($notification->sender) {
            $senderSource = $notification->sender;
        } else {
            $senderSource = null;
        }

        $senderImage = $this->resolveDetailSenderImage($senderSource);

        if ($actionAdmin && $actionAdmin->name) {
            $senderDisplayName = $actionAdmin->name;
        } else {
            $senderDisplayName = $notification->sender_name;
        }

        if ($actionAdmin) {
            $senderLabel = $isCancelled ? 'Cancelled by' : 'Approved by';
        } else {
            $senderLabel = null;
        }

        if ($actionAdmin && $actionAdmin->email) {
            $senderContact = $actionAdmin->email;
        } elseif ($notification->sender && $notification->sender->email) {
            $senderContact = $notification->sender->email;
        } else {
            $senderContact = 'Sent to you';
        }

        $isAdminMessage = $notification->type === 'admin_message' || $notification->type === 'global_message';

        if ($isAdminMessage) {
            $statusKey = 'message';
            $statusLabel = 'Message';
        } elseif ($isCancelled) {
            $statusKey = 'cancelled';
            $statusLabel = 'Cancelled';
        } elseif ($isApproved) {
            $statusKey = 'approved';
            $statusLabel = 'Approved';
        } else {
            $statusKey = null;
            $statusLabel = null;
        }

        // Walk each order item once: attach its display image and per-item
        // pricing breakdown, and roll totals up as we go.
        $itemsForView = collect();
        $subtotal = 0.0;
        $tax = 0.0;
        $grossSubtotal = 0.0;

        foreach ($orderItems as $orderItem) {
            $discountPercent = (float) ($orderItem->discount_percent ?? 0);
            $vatPercent = (float) ($orderItem->tax_percent ?? 0);
            $vatAmount = (float) ($orderItem->tax_amount ?? 0);
            $qty = (int) ($orderItem->qty ?? 0);
            $unitPrice = (float) ($orderItem->unit_price ?? 0);
            $gross = $unitPrice * $qty;
            $discountAmount = $gross * $discountPercent / 100;

            if ($qty > 0) {
                $netUnitPrice = ($gross - $discountAmount) / $qty;
            } else {
                $netUnitPrice = $unitPrice;
            }

            $orderItem->display_image = $this->resolveOrderItemImage($orderItem);
            $orderItem->display_discount_percent = $discountPercent;
            $orderItem->display_discount_amount = $discountAmount;
            $orderItem->display_vat_percent = $vatPercent;
            $orderItem->display_vat_amount = $vatAmount;
            $orderItem->display_qty = $qty;
            $orderItem->display_net_unit_price = $netUnitPrice;

            $itemsForView->push($orderItem);

            $subtotal += (float) ($orderItem->line_total ?? 0);
            $tax += $vatAmount;
            $grossSubtotal += $gross;
        }

        // Notification model has no `order` relation, so delivery fee and
        // exchange rate fall back to their defaults below (0 and 4100).
        $deliveryFee = 0.0;
        $discountTotal = max(0, $grossSubtotal - $subtotal);
        $total = $subtotal + $tax + $deliveryFee;

        if ($subtotal > 0) {
            $vatRatePercent = ($tax / $subtotal) * 100;
        } else {
            $vatRatePercent = 0;
        }

        $exchangeRate = 4100.0;
        $totalRiel = $total * $exchangeRate;

        return [
            'isOrderNotification' => $isOrderNotification,
            'orderItems' => $itemsForView,
            'isCancelledNotif' => $isCancelled,
            'isApprovedNotif' => $isApproved,
            'ndActionAdmin' => $actionAdmin,
            'ndSenderImage' => $senderImage,
            'ndSenderDisplayName' => $senderDisplayName,
            'ndSenderLabel' => $senderLabel,
            'ndSenderContact' => $senderContact,
            'ndStatusKey' => $statusKey,
            'ndStatusLabel' => $statusLabel,
            'ndSubtotal' => $subtotal,
            'ndTax' => $tax,
            'ndDeliveryFee' => $deliveryFee,
            'ndGrossSubtotal' => $grossSubtotal,
            'ndDiscountTotal' => $discountTotal,
            'ndTotal' => $total,
            'ndVatRatePercent' => $vatRatePercent,
            'ndExchangeRate' => $exchangeRate,
            'ndTotalRiel' => $totalRiel,
        ];
    }

    private function resolveDetailSenderImage($senderSource): ?string
    {
        if (!$senderSource) {
            return null;
        }

        $candidates = [
            $senderSource->profile_image_display ?? null,
            $senderSource->profile_image_url ?? null,
            $senderSource->profile_image ?? null,
            $senderSource->avatar ?? null,
            $senderSource->profile_photo_url ?? null,
            $senderSource->profile_photo ?? null,
            $senderSource->photo ?? null,
            $senderSource->image ?? null,
            $senderSource->image_url ?? null,
        ];

        foreach ($candidates as $candidate) {
            if ($candidate) {
                return $this->resolveImagePath($candidate);
            }
        }

        return null;
    }

    /**
     * Get related order items for a notification
     */
    public function getNotificationItems(int $notificationId): JsonResponse
    {
        $notification = Notification::with('relatedOrderItems.item', 'relatedOrderItems.itemVariant')
            ->find($notificationId);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found',
            ], 404);
        }

        $items = $notification->relatedOrderItems->map(function ($orderItem) {
            return [
                'id' => $orderItem->id,
                'item_no' => $orderItem->item_no,
                'item_name' => $orderItem->item_name,
                'variant_description' => $orderItem->variant_description,
                'variant_code' => $orderItem->itemVariant?->code,
                'quantity' => $orderItem->qty,
                'unit_price' => $orderItem->unit_price,
                'line_total' => $orderItem->line_total,
                'discount_percent' => $orderItem->discount_percent,
                'image' => $this->resolveOrderItemImage($orderItem),
            ];
        });

        return response()->json([
            'success' => true,
            'items' => $items,
            'order_no' => $notification->order_id,
        ]);
    }

    /**
     * Delete one or more of the current user's notifications.
     * Expects JSON body: { notification_ids: [1, 2, 3] }
     */
    public function deleteSelected(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user instanceof User) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $ids = $request->input('notification_ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No notification ids provided.',
            ], 422);
        }

        Notification::where('user_id', $user->id)
            ->whereIn('id', $ids)
            ->delete();

        return response()->json([
            'success' => true,
        ]);
    }
    public function unreadNotifications(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user instanceof User) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $unread = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->latest()
            ->limit(10)
            ->get(['id', 'title', 'message', 'type', 'created_at']);

        return response()->json([
            'success' => true,
            'count' => $unread->count(),
            'notifications' => $unread,
        ]);
    }
}