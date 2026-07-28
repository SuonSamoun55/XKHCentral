<?php

namespace App\Http\Controllers\Api\POS\User\Daskboard;

use App\Http\Controllers\Controller;
use App\Models\ManagementSystem\Notification;
use App\Models\POS\Order;
use App\Models\POS\OrderItem;
use App\Models\POS\Item; // adjust namespace if your Item model lives elsewhere
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
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

        $totalOrders = (clone $orderQuery)->count();
        $pendingOrders = (clone $orderQuery)->where('status', 'pending')->count();
        $totalOrderAmount = (float) ((clone $orderQuery)->sum('total_amount') ?? 0);

        $totalProducts = Item::query()
            ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->where('company_id', $selectedCompanyId);
            })
            ->count();

        $now = Carbon::now();
        $thisMonthOrders = (clone $orderQuery)
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();
        $thisMonthAmount = (float) ((clone $orderQuery)
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->sum('total_amount') ?? 0);

        $confirmedAmount = (float) ((clone $orderQuery)
            ->where('status', 'confirmed')
            ->sum('total_amount') ?? 0);

        $pendingAmount = (float) ((clone $orderQuery)
            ->where('status', 'pending')
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount') ?? 0);

        $availableYears = (clone $orderQuery)
            ->selectRaw('DISTINCT YEAR(created_at) as yr')
            ->orderByDesc('yr')
            ->pluck('yr')
            ->map(fn ($yr) => (int) $yr)
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

        $monthOptions = collect(range(1, 12))->map(fn ($m) => [
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

        // Eager-load sender so decorateNotificationIcon() can pick a
        // profile image for admin-type notifications, same as the full
        // notification list page does.
        $recentNotifications = Notification::query()
            ->with('sender')
            ->where('user_id', $user->id)
            ->latest()
            ->take(4)
            ->get()
            ->map(fn ($notification) => $this->decorateNotificationIcon($notification));

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
        $purchaseQtyTotal = (int) ($purchaseTotals->qty_total ?? 0);
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
            'totalOrderAmount',
            'thisMonthOrders',
            'thisMonthAmount',
            'confirmedAmount',
            'pendingAmount',
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

    public function mobileDashboard()
    {
        $user = Auth::user();

        $totalOrder = Order::where('user_id', $user->id)
            ->sum('total_amount');

        $totalReturn = Order::where('user_id', $user->id)
            ->where('status', 'returned')
            ->sum('total_amount');

        $start = Carbon::now()->subDays(6)->startOfDay();

        $dailySales = Order::where('user_id', $user->id)
            ->whereBetween('created_at', [$start, Carbon::now()->endOfWeek()])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        return view('views.POSViews.POSUserViews.Daskboard.DashboardUser', compact(
            'totalOrder',
            'totalReturn',
            'dailySales'
        ));
    }

    /**
     * Same "is this an Admin Message" rule used on the full notification
     * list page: type is authoritative when it's explicitly
     * admin_message/global_message; otherwise fall back to keyword
     * sniffing on title/message, with order wording always winning.
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
     * Attaches display_icon (admin / global / cancelled / confirmed /
     * default) plus a resolved sender image, so the dashboard's
     * Notification card renders the same icon set as the full
     * notification list page.
     */
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