<?php

namespace App\Http\Controllers\Api\ManagementSystem;

use App\Http\Controllers\Controller;
use App\Models\ManagementSystem\Notification;
use App\Models\ManagementSystem\User;
use App\Models\POS\Item;
use App\Models\POS\Order;
use App\Models\POS\OrderItem;
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

        $orderQuery = Order::query()
            ->when($selectedCompanyId, fn ($q) => $q->where('company_id', $selectedCompanyId));

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

        $reportPeriod = request()->get('report_period', 'year');
        if (!in_array($reportPeriod, ['today', 'week', 'month', 'year'], true)) {
            $reportPeriod = 'year';
        }

        // ================================================================
        // HERO ROW (counts shown on the 3 hero cards, if you wire text in)
        // ================================================================
        $totalProducts = Item::query()
            ->when($selectedCompanyId, fn ($q) => $q->where('company_id', $selectedCompanyId))
            ->count();
        // Registered customers (signed up / logged in at least once), and
        // how many of them are online right now — reuses User::scopeOnline's
        // existing 5-minute last_seen_at window (kept fresh by the
        // 'last.seen' middleware and the /heartbeat endpoint) so this stays
        // consistent with how "online" is defined everywhere else.
        $totalCustomers = User::where('role', 'customer')->count();
        $onlineCustomers = User::where('role', 'customer')->online()->count();

        // ================================================================
        // REPORT — revenue bar chart, bucketed by hour/day/month depending
        // on the selected period
        // ================================================================
        [$chartData, $yAxisSteps, $yAxisMax] = $this->buildReportChart($orderQuery, $reportPeriod, $now, $selectedYear);

        // ================================================================
        // NOTIFICATIONS — most recent 4 addressed to this admin, plus the
        // actual unread count (previously the panel's "X unread messages"
        // caption was faked as min(2, count of the 4 latest) regardless of
        // their real is_read status).
        // ================================================================
        $unreadNotificationCount = Notification::query()
            ->where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        $notifications = Notification::query()
            ->with('sender')
            ->where('user_id', $user->id)
            ->latest()
            ->take(4)
            ->get()
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'name' => $n->sender->name ?? 'Unknown',
                    // TODO: swap 'company_name' for whatever column on your
                    // User model actually stores the customer's business name
                    'role' => $n->sender->company_name ?? ($n->title ?? ''),
                    // profile_image_display is the User model's own accessor —
                    // real profile photo if one exists and the file is
                    // actually still on disk, else profile_image_url, else a
                    // default avatar. Previously this checked profile_image
                    // directly without verifying the file exists, which could
                    // point at a stale/missing path instead of falling back.
                    'avatar' => $n->sender ? $n->sender->profile_image_display : asset(self::DEFAULT_AVATAR),
                ];
            })
            ->all();

        // ================================================================
        // TOP SELLING PRODUCTS — ranked by qty sold in the selected period,
        // each with a % change in qty vs the prior equivalent period
        // ================================================================
        $topProductsPeriod = request()->get('products_period', 'today');
        if (!in_array($topProductsPeriod, ['today', 'week', 'month', 'year'], true)) {
            $topProductsPeriod = 'today';
        }
        $topProductsLimit = $this->normalizeTopProductsLimit(request()->get('products_limit', '20'));
        $topProducts = $this->topSellingProducts($selectedCompanyId, $now, $topProductsPeriod, $this->topProductsLimitToInt($topProductsLimit));

        // ================================================================
        // OVERVIEW STATS — Total Income / Total Confirmed / Pending Product,
        // all filterable by the same Today / This Week / This Month /
        // This Year period, each with a % change vs the prior equivalent
        // period.
        // ================================================================
        $statsPeriod = request()->get('stats_period', 'month');
        if (!in_array($statsPeriod, ['today', 'week', 'month', 'year'], true)) {
            $statsPeriod = 'month';
        }
        [
            'totalIncome' => $totalIncome,
            'totalIncomeChangePct' => $totalIncomeChangePct,
            'totalConfirmedOrders' => $totalConfirmedOrders,
            'totalConfirmedChangePct' => $totalConfirmedChangePct,
            'pendingProductCount' => $pendingProductCount,
            'pendingProductChangePct' => $pendingProductChangePct,
        ] = $this->buildOverviewStats($orderQuery, $selectedCompanyId, $now, $statsPeriod);

        return view('ManagementSystemViews.AdminViews.Layouts.DashboardView.Dashboard', compact(
            'totalCustomers',
            'onlineCustomers',
            'totalProducts',
            'chartData',
            'yAxisSteps',
            'yAxisMax',
            'notifications',
            'unreadNotificationCount',
            'topProducts',
            'topProductsPeriod',
            'topProductsLimit',
            'statsPeriod',
            'totalIncome',
            'totalIncomeChangePct',
            'totalConfirmedOrders',
            'totalConfirmedChangePct',
            'pendingProductCount',
            'pendingProductChangePct',
            'selectedYear',
            'availableYears',
            'reportPeriod'
        ));
    }

    /**
     * AJAX endpoint behind the Report panel's year select — returns just the
     * bar-chart data for the requested year so the dropdown can redraw the
     * chart in place instead of reloading the whole dashboard.
     */
    public function reportChart()
    {
        $this->authorizeAjaxAdmin();

        $selectedCompanyId = session('selected_company_id');
        $now = Carbon::now();

        $orderQuery = Order::query()
            ->when($selectedCompanyId, fn ($q) => $q->where('company_id', $selectedCompanyId));

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

        $reportPeriod = request()->get('report_period', 'year');
        if (!in_array($reportPeriod, ['today', 'week', 'month', 'year'], true)) {
            $reportPeriod = 'year';
        }

        [$chartData, $yAxisSteps, $yAxisMax] = $this->buildReportChart($orderQuery, $reportPeriod, $now, $selectedYear);

        return response()->json(compact('chartData', 'yAxisSteps', 'yAxisMax', 'selectedYear'));
    }

    /**
     * AJAX endpoint behind the Top Selling Products period select — returns
     * just the ranked product list so the dropdown can redraw it in place.
     */
    public function topProductsData()
    {
        $this->authorizeAjaxAdmin();

        $selectedCompanyId = session('selected_company_id');
        $now = Carbon::now();

        $period = request()->get('period', 'today');
        if (!in_array($period, ['today', 'week', 'month', 'year'], true)) {
            $period = 'today';
        }

        $limit = $this->normalizeTopProductsLimit(request()->get('limit', '20'));
        $topProducts = $this->topSellingProducts($selectedCompanyId, $now, $period, $this->topProductsLimitToInt($limit));

        return response()->json(compact('topProducts', 'period', 'limit'));
    }

    /** 'all' or a limit string outside {10,20,30,all} both fall back to '20'. */
    private function normalizeTopProductsLimit(string $limit): string
    {
        return in_array($limit, ['10', '20', '30', 'all'], true) ? $limit : '20';
    }

    private function topProductsLimitToInt(string $limit): ?int
    {
        return $limit === 'all' ? null : (int) $limit;
    }

    /**
     * AJAX endpoint behind the Overview period select — returns just the
     * Total Income / Total Confirm / Pending Product figures so the dropdown
     * can redraw the three stat cards in place.
     */
    public function overviewStats()
    {
        $this->authorizeAjaxAdmin();

        $selectedCompanyId = session('selected_company_id');
        $now = Carbon::now();

        $orderQuery = Order::query()
            ->when($selectedCompanyId, fn ($q) => $q->where('company_id', $selectedCompanyId));

        $period = request()->get('period', 'month');
        if (!in_array($period, ['today', 'week', 'month', 'year'], true)) {
            $period = 'month';
        }

        $stats = $this->buildOverviewStats($orderQuery, $selectedCompanyId, $now, $period);

        return response()->json(array_merge(['period' => $period], $stats));
    }

    /**
     * Only admin, no login-page redirect — the three AJAX endpoints above
     * are called from fetch(), so a redirect response would just come back
     * as opaque HTML instead of sending the user anywhere.
     */
    private function authorizeAjaxAdmin(): void
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Only admin can access this page.');
        }
    }

    /**
     * Revenue bar-chart data for the given period: hourly (today), daily
     * (this week / this month) or monthly (a whole year) buckets, each with
     * its confirmed-order revenue and whether it's in the future (greyed out
     * in the UI) — plus y-axis gridlines auto-scaled to the real numbers.
     *
     * Bar values are raw dollars (not pre-divided into $K) so a quiet day's
     * $40 in sales gets its own sensible axis instead of being dwarfed by a
     * scale sized for whole-year totals; formatAxisValue() picks the
     * K/M suffix per axis based on its own max.
     *
     * @return array{0: array, 1: array<string>, 2: float} [chartData, yAxisSteps (formatted labels), yAxisMax (raw)]
     */
    private function buildReportChart($orderQuery, string $period, Carbon $now, int $selectedYear): array
    {
        $chartData = match ($period) {
            'today' => $this->reportChartToday($orderQuery, $now),
            'week' => $this->reportChartWeek($orderQuery, $now),
            'month' => $this->reportChartMonth($orderQuery, $now),
            default => $this->reportChartYear($orderQuery, $selectedYear, $now),
        };

        $maxValue = (float) max(array_column($chartData, 'value'));
        [$rawSteps, $yAxisMax] = $maxValue > 0
            ? $this->niceAxisSteps($maxValue)
            : [[0, 5, 10, 15, 20], 20.0];

        $yAxisSteps = array_map(fn ($v) => $this->formatAxisValue((float) $v), $rawSteps);

        return [$chartData, $yAxisSteps, $yAxisMax];
    }

    /** 12 monthly buckets for the given year. */
    private function reportChartYear($orderQuery, int $year, Carbon $now): array
    {
        $revenue = (clone $orderQuery)
            ->where('status', 'confirmed')
            ->whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as bucket, COALESCE(SUM(total_amount), 0) as amount')
            ->groupBy('bucket')
            ->pluck('amount', 'bucket');

        $chartData = [];
        for ($m = 1; $m <= 12; $m++) {
            $chartData[] = [
                'label' => Carbon::create($year, $m, 1)->format('M'),
                'value' => (float) ($revenue[$m] ?? 0),
                // grey out months in the future so the bar chart doesn't
                // imply data for months that haven't happened yet
                'muted' => Carbon::create($year, $m, 1)->startOfMonth()->isAfter($now),
            ];
        }

        return $chartData;
    }

    /** One bucket per day of the current month. */
    private function reportChartMonth($orderQuery, Carbon $now): array
    {
        $monthStart = $now->copy()->startOfMonth();

        $revenue = (clone $orderQuery)
            ->where('status', 'confirmed')
            ->whereBetween('created_at', [$monthStart, $now->copy()->endOfMonth()])
            ->selectRaw('DAY(created_at) as bucket, COALESCE(SUM(total_amount), 0) as amount')
            ->groupBy('bucket')
            ->pluck('amount', 'bucket');

        $chartData = [];
        for ($d = 1; $d <= $now->daysInMonth; $d++) {
            $day = $monthStart->copy()->addDays($d - 1);
            $chartData[] = [
                'label' => (string) $d,
                'value' => (float) ($revenue[$d] ?? 0),
                'muted' => $day->isAfter($now),
            ];
        }

        return $chartData;
    }

    /** One bucket per day of the current week (Mon–Sun). */
    private function reportChartWeek($orderQuery, Carbon $now): array
    {
        $weekStart = $now->copy()->startOfWeek();

        $revenue = (clone $orderQuery)
            ->where('status', 'confirmed')
            ->whereBetween('created_at', [$weekStart, $now->copy()->endOfWeek()])
            ->selectRaw('DATE(created_at) as bucket, COALESCE(SUM(total_amount), 0) as amount')
            ->groupBy('bucket')
            ->pluck('amount', 'bucket');

        $chartData = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $weekStart->copy()->addDays($i);
            $chartData[] = [
                'label' => $day->format('D'),
                'value' => (float) ($revenue[$day->format('Y-m-d')] ?? 0),
                'muted' => $day->isAfter($now),
            ];
        }

        return $chartData;
    }

    /** One bucket per hour of today. */
    private function reportChartToday($orderQuery, Carbon $now): array
    {
        $revenue = (clone $orderQuery)
            ->where('status', 'confirmed')
            ->whereBetween('created_at', [$now->copy()->startOfDay(), $now->copy()->endOfDay()])
            ->selectRaw('HOUR(created_at) as bucket, COALESCE(SUM(total_amount), 0) as amount')
            ->groupBy('bucket')
            ->pluck('amount', 'bucket');

        $chartData = [];
        for ($h = 0; $h < 24; $h++) {
            $hourStart = $now->copy()->startOfDay()->addHours($h);
            $chartData[] = [
                'label' => $hourStart->format('ga'),
                'value' => (float) ($revenue[$h] ?? 0),
                'muted' => $hourStart->isAfter($now),
            ];
        }

        return $chartData;
    }

    /**
     * "Nice round number" axis: 5 evenly-spaced gridlines (0..max) where the
     * step is 1/2/5 × a power of ten and the top gridline is always >= the
     * real max — instead of a fixed ceiling that dwarfs small real numbers
     * on a quiet day/week.
     *
     * @return array{0: array<float>, 1: float} [rawSteps (5 values), axisMax]
     */
    private function niceAxisSteps(float $maxValue): array
    {
        $roughStep = $maxValue / 4;
        $magnitude = 10 ** floor(log10($roughStep));
        $residual = $roughStep / $magnitude;

        $niceResidual = match (true) {
            $residual <= 1 => 1,
            $residual <= 2 => 2,
            $residual <= 5 => 5,
            default => 10,
        };

        $step = $niceResidual * $magnitude;
        $axisMax = $step * 4;

        while ($axisMax < $maxValue) {
            $axisMax += $step;
        }

        $steps = [];
        for ($i = 0; $i <= 4; $i++) {
            $steps[] = round($axisMax * $i / 4, 2);
        }

        return [$steps, $axisMax];
    }

    /** Formats a raw dollar axis value with a K/M suffix once it's large enough to need one. */
    private function formatAxisValue(float $value): string
    {
        if ($value >= 1000000) {
            return rtrim(rtrim(number_format($value / 1000000, 1), '0'), '.') . 'M';
        }
        if ($value >= 1000) {
            return rtrim(rtrim(number_format($value / 1000, 1), '0'), '.') . 'K';
        }

        return number_format($value, 0);
    }

    /**
     * Total Income / Total Confirmed / Pending Product for the given period,
     * each with a % change vs the prior equivalent period. Pending Product
     * itself is a live, unfiltered "how many are pending right now" snapshot
     * — only its trend comparison moves with the period filter, against the
     * count as of the start of the selected period.
     */
    private function buildOverviewStats($orderQuery, ?int $companyId, Carbon $now, string $period): array
    {
        [$from, $to, $prevFrom, $prevTo] = $this->periodRange($now, $period);

        $confirmedOrders = fn (Carbon $from, Carbon $to) => (clone $orderQuery)
            ->where('status', 'confirmed')
            ->whereBetween('created_at', [$from, $to]);

        $totalIncome = (float) $confirmedOrders($from, $to)->sum('total_amount');
        $totalIncomePrev = (float) $confirmedOrders($prevFrom, $prevTo)->sum('total_amount');
        $totalIncomeChangePct = $this->percentChange($totalIncomePrev, $totalIncome);

        $totalConfirmedOrders = $confirmedOrders($from, $to)->count();
        $totalConfirmedPrev = $confirmedOrders($prevFrom, $prevTo)->count();
        $totalConfirmedChangePct = $this->percentChange((float) $totalConfirmedPrev, (float) $totalConfirmedOrders);

        // TODO: swap the 'is_visible' condition below if "pending" should
        // mean something else in your data model (e.g. a dedicated
        // approval/status column instead of visibility).
        $pendingProductBase = fn () => Item::query()
            ->where('is_visible', false)
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId));
        $pendingProductCount = $pendingProductBase()->count();
        $pendingProductPrevCount = $pendingProductBase()->where('created_at', '<', $from)->count();
        $pendingProductChangePct = $this->percentChange((float) $pendingProductPrevCount, (float) $pendingProductCount);

        return [
            'totalIncome' => $totalIncome,
            'totalIncomeChangePct' => $totalIncomeChangePct,
            'totalConfirmedOrders' => $totalConfirmedOrders,
            'totalConfirmedChangePct' => $totalConfirmedChangePct,
            'pendingProductCount' => $pendingProductCount,
            'pendingProductChangePct' => $pendingProductChangePct,
        ];
    }

    /**
     * Top N products by qty sold within the given period (null = no limit,
     * i.e. "All"), each annotated with % change in qty vs the prior
     * equivalent period (e.g. this week vs last week).
     */
    private function topSellingProducts(?int $companyId, Carbon $now, string $period, ?int $limit = 20): array
    {
        [$from, $to, $prevFrom, $prevTo] = $this->periodRange($now, $period);

        $rowsFor = function (Carbon $from, Carbon $to) use ($companyId) {
            return OrderItem::query()
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->leftJoin('items', 'items.id', '=', 'order_items.item_id')
                ->where('orders.status', 'confirmed')
                ->whereBetween('orders.created_at', [$from, $to])
                ->when($companyId, fn ($q) => $q->where('orders.company_id', $companyId))
                ->select(
                    'order_items.item_id',
                    DB::raw('COALESCE(MAX(items.display_name), MAX(order_items.item_name), MAX(order_items.item_no)) as item_name'),
                    DB::raw('MAX(items.custom_image_url) as custom_image_url'),
                    DB::raw('MAX(items.image_url) as image_url'),
                    DB::raw('MAX(order_items.unit_price) as unit_price'),
                    DB::raw('SUM(order_items.qty) as total_qty')
                )
                ->groupBy('order_items.item_id')
                ->get()
                ->keyBy('item_id');
        };

        $current = $rowsFor($from, $to);
        $previous = $rowsFor($prevFrom, $prevTo);

        $ranked = $current->sortByDesc('total_qty');
        if ($limit !== null) {
            $ranked = $ranked->take($limit);
        }

        return $ranked->map(function ($row) use ($previous) {
            $prevQty = (float) ($previous[$row->item_id]->total_qty ?? 0);

            return [
                'name' => $row->item_name,
                'sub' => ((int) $row->total_qty) . '+ Sales',
                'value' => '$' . number_format((float) $row->unit_price, 0),
                'thumb' => $row->custom_image_url ?: $row->image_url,
                'change' => $this->percentChange($prevQty, (float) $row->total_qty),
            ];
        })->values()->all();
    }

    /**
     * [from, to, prevFrom, prevTo] boundaries for a named period, used to
     * rank top-selling products and compute their period-over-period change.
     */
    private function periodRange(Carbon $now, string $period): array
    {
        return match ($period) {
            'week' => [
                $now->copy()->startOfWeek(), $now->copy()->endOfWeek(),
                $now->copy()->subWeek()->startOfWeek(), $now->copy()->subWeek()->endOfWeek(),
            ],
            'month' => [
                $now->copy()->startOfMonth(), $now->copy()->endOfMonth(),
                $now->copy()->subMonthNoOverflow()->startOfMonth(), $now->copy()->subMonthNoOverflow()->endOfMonth(),
            ],
            'year' => [
                $now->copy()->startOfYear(), $now->copy()->endOfYear(),
                $now->copy()->subYear()->startOfYear(), $now->copy()->subYear()->endOfYear(),
            ],
            default => [
                $now->copy()->startOfDay(), $now->copy()->endOfDay(),
                $now->copy()->subDay()->startOfDay(), $now->copy()->subDay()->endOfDay(),
            ],
        };
    }

    private function percentChange(float $prev, float $current): int
    {
        if ($prev <= 0) {
            return $current > 0 ? 100 : 0;
        }

        return (int) round((($current - $prev) / $prev) * 100);
    }
}