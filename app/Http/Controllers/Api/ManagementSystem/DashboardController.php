<?php

namespace App\Http\Controllers\Api\ManagementSystem;

use App\Http\Controllers\Controller;
use App\Models\ManagementSystem\Notification;
use App\Models\POS\Item;
use App\Models\POS\Order;
use App\Models\POS\OrderItem;
use App\Models\ManagementSystem\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private const DEFAULT_AVATAR = 'images/pos/Rectangle 2.png';

    public function index()
    {
        $user = Auth::user();

        // check if login
        if (!$user) {
            return redirect()->route('login');
        }

        // allow only admin
        if ($user->role !== 'admin') {
            abort(403, 'Only admin can access this page.');
        }

        ini_set('memory_limit', '512M');

        $selectedCompanyId = session('selected_company_id');
        $now = Carbon::now();
        $selectedYear = (int) request()->get('year', $now->year);

        $orderQuery = Order::query()
            ->when($selectedCompanyId, fn ($q) => $q->where('company_id', $selectedCompanyId));

        // ================================================================
        // HERO ROW (counts shown on the 3 hero cards, if you wire text in)
        // ================================================================
        $totalOrders = (clone $orderQuery)->count();
        $pendingOrders = (clone $orderQuery)->where('status', 'pending')->count();
        $totalProducts = Item::query()
            ->when($selectedCompanyId, fn ($q) => $q->where('company_id', $selectedCompanyId))
            ->count();

        // ================================================================
        // REPORT — monthly revenue bar chart for the selected year
        // ================================================================
        $monthlyRevenue = (clone $orderQuery)
            ->where('status', 'confirmed')
            ->whereYear('created_at', $selectedYear)
            ->selectRaw('MONTH(created_at) as month, COALESCE(SUM(total_amount), 0) as amount')
            ->groupBy('month')
            ->pluck('amount', 'month');

        $chartData = [];
        for ($m = 1; $m <= 12; $m++) {
            $amountK = round(((float) ($monthlyRevenue[$m] ?? 0)) / 1000, 1);
            $chartData[] = [
                'label' => Carbon::create($selectedYear, $m, 1)->format('M'),
                'value' => $amountK,
                // grey out months in the future so the bar chart doesn't
                // imply data for months that haven't happened yet
                'muted' => Carbon::create($selectedYear, $m, 1)->startOfMonth()->isAfter($now),
            ];
        }

        $maxValue = max(1, (int) ceil(max(array_column($chartData, 'value'))));
        $yAxisMax = (int) (ceil($maxValue / 20) * 20);
        $yAxisMax = max($yAxisMax, 20);
        $step = (int) ($yAxisMax / 4);
        $yAxisSteps = range(0, $yAxisMax, $step);

        // ================================================================
        // NOTIFICATIONS — most recent 4 addressed to this admin
        // ================================================================
        $notifications = Notification::query()
            ->with('sender')
            ->where('user_id', $user->id)
            ->latest()
            ->take(4)
            ->get()
            ->map(function ($n) {
                return [
                    'name' => $n->sender->name ?? 'Unknown',
                    // TODO: swap 'company_name' for whatever column on your
                    // User model actually stores the customer's business name
                    'role' => $n->sender->company_name ?? ($n->title ?? ''),
                    'avatar' => ($n->sender && $n->sender->profile_image)
                        ? asset('storage/' . $n->sender->profile_image)
                        : asset(self::DEFAULT_AVATAR),
                ];
            })
            ->all();

        // ================================================================
        // TOP CUSTOMERS — ranked by all-time spend, with week/month/year
        // % change in spend vs the prior equivalent period
        // ================================================================
        $topCustomers = $this->topCustomers($selectedCompanyId, $now);

        // ================================================================
        // TOP ITEMS — ranked by qty sold for the selected year
        // (this is the array the "Top Items" panel loops over: $topItems)
        // ================================================================
        $topItems = $this->topItemsForYear($selectedCompanyId, $selectedYear);

        return view('ManagementSystemViews.AdminViews.Layouts.DashboardView.Dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'totalProducts',
            'chartData',
            'yAxisSteps',
            'yAxisMax',
            'notifications',
            'topCustomers',
            'topItems',
            'selectedYear'
        ));
    }

    /**
     * Top 10 customers by all-time confirmed spend, each annotated with
     * % change in spend vs the prior week / month / year.
     */
    private function topCustomers(?int $companyId, Carbon $now): array
    {
        $baseQuery = fn () => Order::query()
            ->where('status', 'confirmed')
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId));

        $topUsers = (clone $baseQuery())
            ->select('user_id', DB::raw('SUM(total_amount) as lifetime_total'))
            ->groupBy('user_id')
            ->orderByDesc('lifetime_total')
            ->take(10)
            ->get();

        $userIds = $topUsers->pluck('user_id');
        $users = User::query()->whereIn('id', $userIds)->get()->keyBy('id');

        $weekStart = $now->copy()->subDays(7);
        $prevWeekStart = $now->copy()->subDays(14);
        $monthStart = $now->copy()->subMonthNoOverflow();
        $prevMonthStart = $now->copy()->subMonthsNoOverflow(2);
        $yearStart = $now->copy()->subYear();
        $prevYearStart = $now->copy()->subYears(2);

        return $topUsers->map(function ($row) use ($users, $baseQuery, $now, $weekStart, $prevWeekStart, $monthStart, $prevMonthStart, $yearStart, $prevYearStart) {
            $customer = $users[$row->user_id] ?? null;

            $spend = fn (Carbon $from, Carbon $to) => (float) (clone $baseQuery())
                ->where('user_id', $row->user_id)
                ->whereBetween('created_at', [$from, $to])
                ->sum('total_amount');

            $weekNow = $spend($weekStart, $now);
            $weekPrev = $spend($prevWeekStart, $weekStart);
            $monthNow = $spend($monthStart, $now);
            $monthPrev = $spend($prevMonthStart, $monthStart);
            $yearNow = $spend($yearStart, $now);
            $yearPrev = $spend($prevYearStart, $yearStart);

            return [
                'name' => $customer->name ?? 'Unknown',
                // TODO: adjust to your actual customer/business-name column
                'sub' => $customer->company_name ?? '',
                'value' => '$' . number_format($row->lifetime_total),
                'avatar' => ($customer && $customer->profile_image)
                    ? asset('storage/' . $customer->profile_image)
                    : asset(self::DEFAULT_AVATAR),
                'week' => $this->percentChange($weekPrev, $weekNow),
                'month' => $this->percentChange($monthPrev, $monthNow),
                'year' => $this->percentChange($yearPrev, $yearNow),
            ];
        })->all();
    }

    /**
     * Top 20 items by qty sold for a given year — feeds the "Top Items" panel.
     */
    private function topItemsForYear(?int $companyId, int $year): array
    {
        $rows = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('items', 'items.id', '=', 'order_items.item_id')
            ->where('orders.status', 'confirmed')
            ->whereYear('orders.created_at', $year)
            ->when($companyId, fn ($q) => $q->where('orders.company_id', $companyId))
            ->select(
                'order_items.item_id',
                DB::raw('COALESCE(MAX(items.display_name), MAX(order_items.item_name), MAX(order_items.item_no)) as item_name'),
                DB::raw('MAX(items.custom_image_url) as custom_image_url'),
                DB::raw('MAX(items.image_url) as image_url'),
                DB::raw('SUM(order_items.qty) as total_qty'),
                DB::raw('SUM(order_items.line_total) as total_amount')
            )
            ->groupBy('order_items.item_id')
            ->orderByDesc('total_qty')
            ->take(20)
            ->get();

        return $rows->map(fn ($row) => [
            'name' => $row->item_name,
            'sub' => $row->total_qty . ' sold',
            'value' => '$' . number_format((float) $row->total_amount),
            'thumb' => $row->custom_image_url ?: $row->image_url,
            'change' => 0, // no prior-period comparison for a yearly total
        ])->all();
    }

    private function percentChange(float $prev, float $current): int
    {
        if ($prev <= 0) {
            return $current > 0 ? 100 : 0;
        }

        return (int) round((($current - $prev) / $prev) * 100);
    }
}