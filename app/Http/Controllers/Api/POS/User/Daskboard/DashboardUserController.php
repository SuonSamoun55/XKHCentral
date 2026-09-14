<?php

namespace App\Http\Controllers\Api\POS\User\Daskboard;

use App\Http\Controllers\Controller;
use App\Models\ManagementSystem\Notification;
use App\Models\POS\Order;
use App\Models\POS\OrderItem;
use App\Models\POS\Item;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardUserController extends Controller
{
    private const ADMIN_TYPES = [
        'admin_message',
        'global_message',
    ];

    private const DEFAULT_SENDER_IMAGE = 'images/pos/Rectangle 2.png';

    public function index()
    {
        $user = Auth::user();
        $selectedCompanyId = session('selected_company_id');

        $orderQuery = Order::query()
            ->where('user_id', $user->id)
            ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->where('company_id', $selectedCompanyId);
            });
        // Matches ItemListController's customer-facing listing exactly: visible
        // items that are also actually buyable (in stock, or oversell allowed
        // for that product) — otherwise this count would include products the
        // user can't actually see/buy anywhere else in the app.
        $totalProducts = Item::query()
            ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->where('company_id', $selectedCompanyId);
            })
            ->where(function ($q) {
                $q->where('blocked', false)->orWhereNull('blocked');
            })
            ->where('is_visible', true)
            ->where(function ($q) {
                $q->where('category_visible', true)->orWhereNull('category_visible');
            })
            ->get()
            ->filter(fn (Item $item) => $item->isPurchasable())
            ->count();

        $now = Carbon::now();
        $statusBreakdown = (clone $orderQuery)
            ->select('status', DB::raw('COUNT(*) as cnt'), DB::raw('COALESCE(SUM(total_amount), 0) as amt'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $totalOrders = (int) $statusBreakdown->sum('cnt');

        $confirmedOrders = (int) ($statusBreakdown['confirmed']->cnt ?? 0);
        $confirmedAmount = (float) ($statusBreakdown['confirmed']->amt ?? 0);

        $pendingOrdersCount = (int) ($statusBreakdown['pending']->cnt ?? 0);
        $pendingOrders = $pendingOrdersCount;
        $pendingAmount = (float) ($statusBreakdown['pending']->amt ?? 0);

        $cancelledOrders = (int) ($statusBreakdown['cancelled']->cnt ?? 0);
        $cancelledAmount = (float) ($statusBreakdown['cancelled']->amt ?? 0);
        $pendingItems = (int) (OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.user_id', $user->id)
            ->where('orders.status', 'pending')
            ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->where('orders.company_id', $selectedCompanyId);
            })
            ->sum('order_items.qty') ?? 0);
        $confirmedPct = $totalOrders > 0 ? (int) round(($confirmedOrders / $totalOrders) * 100) : 0;
        $pendingPct = $totalOrders > 0 ? (int) round(($pendingOrdersCount / $totalOrders) * 100) : 0;
        $cancelledPct = $totalOrders > 0 ? (int) round(($cancelledOrders / $totalOrders) * 100) : 0;

        $availableYears = (clone $orderQuery)
            ->selectRaw('DISTINCT YEAR(created_at) as yr')
            ->orderByDesc('yr')
            ->pluck('yr')
            ->map(fn($yr) => (int) $yr)
            ->values();

        if ($availableYears->isEmpty()) {
            $availableYears = collect([$now->year]);
        }

        $requestedYear = (int) request()->get('year', $now->year);
        $selectedYear = $availableYears->contains($requestedYear)
            ? $requestedYear
            : $availableYears->first();

        $requestedMonth = request()->get('month', 'all');
        $selectedMonth = ($requestedMonth !== 'all' && (int) $requestedMonth >= 1 && (int) $requestedMonth <= 12)
            ? (int) $requestedMonth
            : null;

        $monthOptions = collect(range(1, 12))->map(fn($m) => [
            'value' => $m,
            'label' => Carbon::create($selectedYear, $m, 1)->format('F'),
        ]);

        $recentOrders = (clone $orderQuery)
            ->whereYear('created_at', $selectedYear)
            ->when($selectedMonth, function ($query) use ($selectedMonth) {
                $query->whereMonth('created_at', $selectedMonth);
            })
            ->with(['items.item', 'items.itemVariant'])
            ->latest()
            ->take(8)
            ->get();
        $recentNotifications = Notification::query()
            ->with('sender')
            ->where('user_id', $user->id)
            ->latest()
            ->take(4)
            ->get()
            ->map(fn($notification) => $this->decorateNotificationIcon($notification));

        $unreadNotificationCount = (int) Notification::query()
            ->where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
        $purchasedItemsBase = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.user_id', $user->id)
            ->where('orders.status', '!=', 'cancelled')
            ->whereYear('orders.created_at', $selectedYear)
            ->when($selectedMonth, function ($query) use ($selectedMonth) {
                $query->whereMonth('orders.created_at', $selectedMonth);
            })
            ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->where('orders.company_id', $selectedCompanyId);
            });

        $topPurchasedItems = (clone $purchasedItemsBase)
            ->leftJoin('items', 'items.id', '=', 'order_items.item_id')
            ->select(
                'order_items.item_id',
                'order_items.item_no',
                DB::raw('COALESCE(MAX(items.display_name), MAX(order_items.item_name), MAX(order_items.item_no)) as item_name'),
                DB::raw('MAX(items.custom_image_url) as custom_image_url'),
                DB::raw('MAX(items.image_url) as image_url'),
                DB::raw('SUM(order_items.qty) as total_qty'),
                DB::raw('SUM(order_items.line_total) as total_amount')
            )
            ->groupBy('order_items.item_id', 'order_items.item_no')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        $purchaseTotals = (clone $purchasedItemsBase)
            ->selectRaw('COALESCE(SUM(order_items.qty), 0) as qty_total, COALESCE(SUM(order_items.line_total), 0) as amount_total')
            ->first();
        $purchaseQtyTotal = (float) ($purchaseTotals->qty_total ?? 0);
        $purchaseAmountTotal = (float) ($purchaseTotals->amount_total ?? 0);

        $reportLabels = [];
        $reportDates = [];
        $reportValues = [];

        if ($selectedMonth) {
            $monthStart = Carbon::create($selectedYear, $selectedMonth, 1)->startOfDay();
            $monthEnd = (clone $monthStart)->endOfMonth();

            $dailyRevenue = Order::query()
                ->where('user_id', $user->id)
                ->where('status', 'confirmed')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                    $query->where('company_id', $selectedCompanyId);
                })
                ->selectRaw('DAY(created_at) as day, COALESCE(SUM(total_amount), 0) as amount')
                ->groupBy('day')
                ->pluck('amount', 'day');

            for ($day = 1; $day <= $monthEnd->day; $day++) {
                $date = (clone $monthStart)->day($day);
                $reportLabels[] = $date->format('d');
                $reportDates[] = $date->format('Y-m-d');
                $reportValues[] = (float) ($dailyRevenue[$day] ?? 0);
            }
        } else {
            $monthlyRevenue = Order::query()
                ->where('user_id', $user->id)
                ->where('status', 'confirmed')
                ->whereYear('created_at', $selectedYear)
                ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                    $query->where('company_id', $selectedCompanyId);
                })
                ->selectRaw('MONTH(created_at) as month, COALESCE(SUM(total_amount), 0) as amount')
                ->groupBy('month')
                ->pluck('amount', 'month');

            for ($month = 1; $month <= 12; $month++) {
                $monthDate = Carbon::create($selectedYear, $month, 1);
                $reportLabels[] = $monthDate->format('M');
                $reportDates[] = $monthDate->format('Y-m');
                $reportValues[] = (float) ($monthlyRevenue[$month] ?? 0);
            }
        }

        return view('POSViews.POSUserViews.Daskboard.DashboardUser', compact(
            'totalOrders',
            'pendingOrders',
            'totalProducts',
            'confirmedAmount',
            'confirmedOrders',
            'confirmedPct',
            'pendingAmount',
            'pendingItems',
            'pendingPct',
            'cancelledAmount',
            'cancelledOrders',
            'cancelledPct',
            'recentOrders',
            'recentNotifications',
            'unreadNotificationCount',
            'topPurchasedItems',
            'purchaseQtyTotal',
            'purchaseAmountTotal',
            'reportLabels',
            'reportDates',
            'reportValues',
            'availableYears',
            'selectedYear',
            'monthOptions',
            'selectedMonth'
        ));
    }

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

    private function decorateNotificationIcon(Notification $notification): Notification
    {
        $titleLower = strtolower($notification->title ?? '');
        $messageLower = strtolower($notification->message ?? '');

        $isAdmin = $this->isAdminNotification($notification);

        if ($isAdmin) {
            $notification->display_icon = 'admin';
        } elseif ($notification->type === 'global_message') {
            $notification->display_icon = 'global';
        } elseif (str_contains($titleLower, 'cancel')) {
            $notification->display_icon = 'cancelled';
        } elseif (str_contains($titleLower, 'confirm') || str_contains($titleLower, 'approve')) {
            $notification->display_icon = 'confirmed';
        } else {
            $notification->display_icon = 'default';
        }

        $notification->sender_profile_image = $this->resolveSenderImage($notification);

        return $notification;
    }

    private function resolveSenderImage(Notification $notification): ?string
    {
        if ($notification->relationLoaded('sender') && $notification->sender?->profile_image) {
            return asset('storage/' . $notification->sender->profile_image);
        }

        return null;
    }
}
