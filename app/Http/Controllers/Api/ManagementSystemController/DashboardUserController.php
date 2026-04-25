<?php

namespace App\Http\Controllers\Api\ManagementSystemController;

use App\Http\Controllers\Controller;
use App\Models\MagamentSystemModel\Notification;
use App\Models\POSModel\Order;
use App\Models\POSModel\OrderItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardUserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $selectedCompanyId = session('selected_company_id');
        $purchasePeriod = request()->get('purchase_period', 'month');
        if (!in_array($purchasePeriod, ['week', 'month', 'year'], true)) {
            $purchasePeriod = 'month';
        }

        $periodStart = match ($purchasePeriod) {
            'week' => Carbon::now()->startOfWeek(),
            'year' => Carbon::now()->startOfYear(),
            default => Carbon::now()->startOfMonth(),
        };

        $orderQuery = Order::query()
            ->where('user_id', $user->id)
            ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->where('company_id', $selectedCompanyId);
            });

        $totalOrders = (clone $orderQuery)->count();
        $pendingOrders = (clone $orderQuery)->where('status', 'pending')->count();
        $totalOrderAmount = (float) ((clone $orderQuery)->sum('total_amount') ?? 0);

        $now = Carbon::now();
        $thisMonthOrders = (clone $orderQuery)
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();
        $thisMonthAmount = (float) ((clone $orderQuery)
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->sum('total_amount') ?? 0);

        $recentOrders = (clone $orderQuery)
            ->with(['items.item'])
            ->latest()
            ->take(5)
            ->get();

        $recentNotifications = Notification::query()
            ->where('user_id', $user->id)
            ->latest()
            ->take(4)
            ->get();

        $unreadNotificationCount = (int) Notification::query()
            ->where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        $purchasedItemsBase = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.user_id', $user->id)
            ->where('orders.status', '!=', 'cancelled')
            ->where('orders.created_at', '>=', $periodStart)
            ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->where('orders.company_id', $selectedCompanyId);
            });

        $topPurchasedItems = (clone $purchasedItemsBase)
            ->leftJoin('items', 'items.id', '=', 'order_items.item_id')
            ->select(
                'order_items.item_id',
                'order_items.item_no',
                DB::raw('COALESCE(MAX(items.display_name), MAX(order_items.item_name), MAX(order_items.item_no)) as item_name'),
                DB::raw('MAX(items.image_url) as image_url'),
                DB::raw('SUM(order_items.qty) as total_qty'),
                DB::raw('SUM(order_items.line_total) as total_amount')
            )
            ->groupBy('order_items.item_id', 'order_items.item_no')
            ->orderByDesc('total_qty')
            ->get();

        $purchaseTotals = (clone $purchasedItemsBase)
            ->selectRaw('COALESCE(SUM(order_items.qty), 0) as qty_total, COALESCE(SUM(order_items.line_total), 0) as amount_total')
            ->first();
        $purchaseQtyTotal = (int) ($purchaseTotals->qty_total ?? 0);
        $purchaseAmountTotal = (float) ($purchaseTotals->amount_total ?? 0);

        $reportDays = 14;
        $reportStart = Carbon::now()->subDays($reportDays - 1)->startOfDay();
        $dailyRevenue = Order::query()
            ->where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->where('created_at', '>=', $reportStart)
            ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->where('company_id', $selectedCompanyId);
            })
            ->selectRaw('DATE(created_at) as day, COALESCE(SUM(total_amount), 0) as amount')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('amount', 'day');

        $reportLabels = [];
        $reportDates = [];
        $reportValues = [];
        for ($i = 0; $i < $reportDays; $i++) {
            $date = (clone $reportStart)->addDays($i);
            $key = $date->toDateString();
            $reportLabels[] = $date->format('M d');
            $reportDates[] = $date->format('Y-m-d');
            $reportValues[] = (float) ($dailyRevenue[$key] ?? 0);
        }

        return view('ManagementSystemViews.UserViews.DashboardUser', compact(
            'totalOrders',
            'pendingOrders',
            'totalOrderAmount',
            'thisMonthOrders',
            'thisMonthAmount',
            'recentOrders',
            'recentNotifications',
            'unreadNotificationCount',
            'purchasePeriod',
            'topPurchasedItems',
            'purchaseQtyTotal',
            'purchaseAmountTotal',
            'reportLabels',
            'reportDates',
            'reportValues'
        ));
    }
}
