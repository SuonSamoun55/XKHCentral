<?php

namespace App\Http\Controllers\Api\ManagementSystem;

use App\Http\Controllers\Controller;
use App\Models\ManagementSystem\Company;
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


    private function resolveCompanyId(): ?int
    {
        return session('selected_company_id') ?? Company::query()->value('id');
    }

//show page daskboard admin and offer filter
    public function index()
    {
        /** @var \App\Models\ManagementSystem\User|null $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        if (!$user->isAdmin() && !$user->hasPermission('dashboard')) {
            abort(403, 'You do not have access to this page.');
        }

        ini_set('memory_limit', '512M');

        $selectedCompanyId = $this->resolveCompanyId();
        $now = Carbon::now();

        $orderQuery = Order::query()
            ->when($selectedCompanyId, fn($q) => $q->where('company_id', $selectedCompanyId));

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

        $reportPeriod = request()->get('report_period', 'year');
        if (!in_array($reportPeriod, ['today', 'week', 'month', 'year'], true)) {
            $reportPeriod = 'year';
        }
        // hero 3 card
        $totalProducts = Item::query()
            ->when($selectedCompanyId, fn($q) => $q->where('company_id', $selectedCompanyId))
            ->count();
        $totalCustomers = User::where('bc_customer_no', 'not like', 'STAFF-%')
            ->when($selectedCompanyId, fn($q) => $q->where('company_id', $selectedCompanyId))
            ->count();
        $onlineCustomers = User::where('bc_customer_no', 'not like', 'STAFF-%')
            ->when($selectedCompanyId, fn($q) => $q->where('company_id', $selectedCompanyId))
            ->online()
            ->count();
        [$chartData, $yAxisSteps, $yAxisMax] = $this->buildReportChart($orderQuery, $reportPeriod, $now, $selectedYear);

        //unread notification
        $unreadNotificationCount = Notification::query()
            ->where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        // notification
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
                    'role' => $n->sender->company_name ?? ($n->title ?? ''),
                    'avatar' => $n->sender ? $n->sender->profile_image_display : asset(self::DEFAULT_AVATAR),
                ];
            })
            ->all();
        // filter top 10 product
        $topProductsPeriod = request()->get('products_period', 'today');
        if (!in_array($topProductsPeriod, ['today', 'week', 'month', 'year'], true)) {
            $topProductsPeriod = 'today';
        }
        $topProductsLimit = $this->normalizeTopProductsLimit(request()->get('products_limit', '20'));
        $topProducts = $this->topSellingProducts($selectedCompanyId, $now, $topProductsPeriod, $this->topProductsLimitToInt($topProductsLimit));


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
// graph chat inside daskbord
    public function reportChart()
    {
        $this->authorizeAjaxAdmin();

        $selectedCompanyId = $this->resolveCompanyId();
        $now = Carbon::now();

        $orderQuery = Order::query()
            ->when($selectedCompanyId, fn($q) => $q->where('company_id', $selectedCompanyId));

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

        $reportPeriod = request()->get('report_period', 'year');
        if (!in_array($reportPeriod, ['today', 'week', 'month', 'year'], true)) {
            $reportPeriod = 'year';
        }

        [$chartData, $yAxisSteps, $yAxisMax] = $this->buildReportChart($orderQuery, $reportPeriod, $now, $selectedYear);

        return response()->json(compact('chartData', 'yAxisSteps', 'yAxisMax', 'selectedYear'));

    }
//top product
    public function topProductsData()
    {
        $this->authorizeAjaxAdmin();

        $selectedCompanyId = $this->resolveCompanyId();
        $now = Carbon::now();

        $period = request()->get('period', 'today');
        if (!in_array($period, ['today', 'week', 'month', 'year'], true)) {
            $period = 'today';
        }

        $limit = $this->normalizeTopProductsLimit(request()->get('limit', '20'));
        $topProducts = $this->topSellingProducts($selectedCompanyId, $now, $period, $this->topProductsLimitToInt($limit));

        return response()->json(compact('topProducts', 'period', 'limit'));
    }
    private function normalizeTopProductsLimit(string $limit): string
    {
        return in_array($limit, ['10', '20', '30', 'all'], true) ? $limit : '20';
    }

    private function topProductsLimitToInt(string $limit): ?int
    {
        return $limit === 'all' ? null : (int) $limit;
    }
    public function overviewStats()
    {
        $this->authorizeAjaxAdmin();

        $selectedCompanyId = $this->resolveCompanyId();
        $now = Carbon::now();

        $orderQuery = Order::query()
            ->when($selectedCompanyId, fn($q) => $q->where('company_id', $selectedCompanyId));

        $period = request()->get('period', 'month');
        if (!in_array($period, ['today', 'week', 'month', 'year'], true)) {
            $period = 'month';
        }

        $stats = $this->buildOverviewStats($orderQuery, $selectedCompanyId, $now, $period);

        return response()->json(array_merge(['period' => $period], $stats));
    }

    private function authorizeAjaxAdmin(): void
    {
        /** @var \App\Models\ManagementSystem\User|null $user */
        $user = Auth::user();

        if (!$user || (!$user->isAdmin() && !$user->hasPermission('dashboard'))) {
            abort(403, 'You do not have access to this page.');
        }
    }

    /**

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

        $yAxisSteps = array_map(fn($v) => $this->formatAxisValue((float) $v), $rawSteps);

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
                'muted' => Carbon::create($year, $m, 1)->startOfMonth()->isAfter($now),
            ];
        }

        return $chartData;
    }

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
    private function buildOverviewStats($orderQuery, ?int $companyId, Carbon $now, string $period): array
    {
        [$from, $to, $prevFrom, $prevTo] = $this->periodRange($now, $period);

        $confirmedOrders = fn(Carbon $from, Carbon $to) => (clone $orderQuery)
            ->where('status', 'confirmed')
            ->whereBetween('created_at', [$from, $to]);

        $totalIncome = (float) $confirmedOrders($from, $to)->sum('total_amount');
        $totalIncomePrev = (float) $confirmedOrders($prevFrom, $prevTo)->sum('total_amount');
        $totalIncomeChangePct = $this->percentChange($totalIncomePrev, $totalIncome);

        $totalConfirmedOrders = $confirmedOrders($from, $to)->count();
        $totalConfirmedPrev = $confirmedOrders($prevFrom, $prevTo)->count();
        $totalConfirmedChangePct = $this->percentChange((float) $totalConfirmedPrev, (float) $totalConfirmedOrders);

        $pendingProductBase = fn() => Item::query()
            ->where('is_visible', false)
            ->when($companyId, fn($q) => $q->where('company_id', $companyId));
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

    private function topSellingProducts(?int $companyId, Carbon $now, string $period, ?int $limit = 20): array
    {
        [$from, $to, $prevFrom, $prevTo] = $this->periodRange($now, $period);

        $rowsFor = function (Carbon $from, Carbon $to) use ($companyId) {
            return OrderItem::query()
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->leftJoin('items', 'items.id', '=', 'order_items.item_id')
                ->where('orders.status', 'confirmed')
                ->whereBetween('orders.created_at', [$from, $to])
                ->when($companyId, fn($q) => $q->where('orders.company_id', $companyId))
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

    private function periodRange(Carbon $now, string $period): array
    {
        return match ($period) {
            'week' => [
                $now->copy()->startOfWeek(),
                $now->copy()->endOfWeek(),
                $now->copy()->subWeek()->startOfWeek(),
                $now->copy()->subWeek()->endOfWeek(),
            ],
            'month' => [
                $now->copy()->startOfMonth(),
                $now->copy()->endOfMonth(),
                $now->copy()->subMonthNoOverflow()->startOfMonth(),
                $now->copy()->subMonthNoOverflow()->endOfMonth(),
            ],
            'year' => [
                $now->copy()->startOfYear(),
                $now->copy()->endOfYear(),
                $now->copy()->subYear()->startOfYear(),
                $now->copy()->subYear()->endOfYear(),
            ],
            default => [
                $now->copy()->startOfDay(),
                $now->copy()->endOfDay(),
                $now->copy()->subDay()->startOfDay(),
                $now->copy()->subDay()->endOfDay(),
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
