@extends('Layout.Management.app')
@section('title', 'Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/Management/Daskboard/POSadminDaskboard.css') }}">
@endpush

@section('content')
    <div class="dashboard">
        <section class="hero-row">
            <div class="hero-card">
                <div class="hero-icon-box">
                    <img src="{{ asset('/images/pos/product icons.png') }}" alt="Products" onerror="this.style.display='none'">
                </div>
                <span class="hero-label">
                    <span class="hero-card-title">Products</span><span class="hero-colon">: </span><span
                        class="hero-card-value">{{ number_format($totalProducts) }}</span><span class="hero-chevron">&rsaquo;</span>
                </span>
            </div>

            <div class="hero-card soft-card" data-href="{{ route('users.index', ['status' => 'connected']) }}">
                <div class="hero-icon-box">
                    <img src="{{ asset('images/management/Total Customers.png') }}" alt="Total Customers"
                        onerror="this.style.display='none'">
                </div>
                <span class="hero-label">
                    <span class="hero-card-title">Total Customers</span><span class="hero-colon">: </span><span
                        class="hero-card-value">{{ number_format($totalCustomers) }}</span><span class="hero-chevron">&rsaquo;</span>
                </span>
            </div>
            <div class="hero-card soft-card" data-href="{{ route('users.index', ['active' => 'online']) }}">
                <div class="hero-icon-box">
                    <img src="{{ asset('images/management/Online Customers.png') }}" alt="Online Customers"
                        onerror="this.style.display='none'">
                </div>
                <span class="hero-label">
                    <span class="hero-card-title">Online Customers</span><span class="hero-colon">: </span><span
                        class="hero-card-value">{{ number_format($onlineCustomers) }}</span><span class="hero-chevron">&rsaquo;</span>
                </span>
            </div>
        </section>

        <div class="dashboard-grid">

            <section class="panel report-panel">
                <div class="panel-head">
                    <h2>Report</h2>
                    <div class="panel-controls">
                        <select class="filter-select" id="reportPeriodSelect">
                            <option value="today" {{ $reportPeriod === 'today' ? 'selected' : '' }}>Today</option>
                            <option value="week" {{ $reportPeriod === 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ $reportPeriod === 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="year" {{ $reportPeriod === 'year' ? 'selected' : '' }}>This Year</option>
                        </select>
                        <select class="filter-select" id="reportYearSelect"
                            style="{{ $reportPeriod === 'year' ? '' : 'display:none;' }}">
                            @foreach ($availableYears as $yr)
                                <option value="{{ $yr }}"
                                    {{ (int) $selectedYear === (int) $yr ? 'selected' : '' }}>{{ $yr }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="chart">
                    <div class="chart-y" id="chartYAxis">
                        @foreach ($yAxisSteps as $step)
                            <span>{{ $step }}</span>
                        @endforeach
                    </div>
                    <div class="chart-bars {{ count($chartData) > 14 ? 'dense' : '' }}" id="chartBars">
                        @foreach ($chartData as $bar)
                            <div class="chart-col">
                                <div class="chart-bar {{ $bar['muted'] ? 'muted' : '' }}"
                                    style="height:{{ $yAxisMax > 0 ? max(4, ($bar['value'] / $yAxisMax) * 100) : 4 }}%"
                                    data-value="{{ $bar['value'] }}" data-label="{{ $bar['label'] }}" tabindex="0">
                                </div>
                                <span class="chart-label">{{ $bar['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="chart-tooltip" id="chartTooltip"></div>
                </div>
            </section>

            <section class="panel filter-panel">
                <div class="panel-head">
                    <div class="panel-title-icon">
                        <span class="icon-badge">
                            {{-- Swap this src for your own icon --}}
                            <img src="{{ asset('images/management/Top Sale.png') }}" alt=""
                                onerror="this.style.display='none'">
                        </span>
                        <h2>Top Products</h2>
                    </div>
                    <div class="panel-controls">
                        <select class="filter-select" id="productsPeriodSelect">
                            <option value="today" {{ $topProductsPeriod === 'today' ? 'selected' : '' }}>Today</option>
                            <option value="week" {{ $topProductsPeriod === 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ $topProductsPeriod === 'month' ? 'selected' : '' }}>This Month
                            </option>
                            <option value="year" {{ $topProductsPeriod === 'year' ? 'selected' : '' }}>This Year</option>
                        </select>
                        <span class="limit-filter" title="How many to show">
                            <select class="filter-select" id="topProductsLimitSelect">
                                <option value="10" {{ $topProductsLimit === '10' ? 'selected' : '' }}>Top 10</option>
                                <option value="20" {{ $topProductsLimit === '20' ? 'selected' : '' }}>Top 20</option>
                                <option value="30" {{ $topProductsLimit === '30' ? 'selected' : '' }}>Top 30</option>
                                <option value="all" {{ $topProductsLimit === 'all' ? 'selected' : '' }}>All</option>
                            </select>
                        </span>
                    </div>
                </div>

                <ul class="product-list" id="topProductsList">
                    @forelse ($topProducts as $p)
                        <li class="product-item">
                            <span class="product-thumb-wrap">
                                <span
                                    class="product-thumb-fallback">{{ strtoupper(mb_substr(trim($p['name'] ?? ''), 0, 1)) ?: '?' }}</span>
                                @if (!empty($p['thumb']))
                                    <img class="product-thumb" src="{{ $p['thumb'] }}" alt="{{ $p['name'] }}"
                                        onerror="this.style.display='none';">
                                @endif
                            </span>
                            <div class="product-info">
                                <div class="product-name">{{ $p['name'] }}</div>
                                <div class="product-sub">{{ $p['value'] }} &bull; {{ $p['sub'] }}</div>
                            </div>
                        </li>
                    @empty
                        <li class="product-item">
                            <div class="product-info">
                                <div class="product-sub">No sales for this period.</div>
                            </div>
                        </li>
                    @endforelse
                </ul>
            </section>

            <section class="panel notification-panel soft-card" data-href="{{ route('admin.notifications.index') }}">
                <div class="panel-head">
                    <div>
                        <h2>Notification</h2>
                        <p class="panel-sub">{{ number_format($unreadNotificationCount) }} unread
                            {{ Str::plural('message', $unreadNotificationCount) }}</p>
                    </div>
                </div>

                <ul class="notif-list">
                    @forelse ($notifications as $n)
                        <li class="notif-item">
                            @php
                                $initials = collect(explode(' ', $n['name']))
                                    ->map(fn($w) => mb_substr($w, 0, 1))
                                    ->take(2)
                                    ->implode('');
                            @endphp
                            <span class="notif-avatar-wrap">
                                <span class="notif-avatar-fallback">{{ strtoupper($initials) }}</span>
                                <img class="notif-avatar" src="{{ $n['avatar'] }}" alt="{{ $n['name'] }}"
                                    onerror="this.style.display='none'">
                            </span>
                            <div class="notif-name-block">
                                <div class="notif-name">{{ $n['name'] }}</div>
                                <div class="notif-role">{{ $n['role'] }}</div>
                            </div>
                            <a href="{{ route('admin.notifications.show', $n['id']) }}" class="chat-btn">Chat</a>
                        </li>
                    @empty
                        <li class="notif-item notif-empty">
                            <div class="notif-name-block">
                                <div class="notif-role">No notifications yet.</div>
                            </div>
                        </li>
                    @endforelse
                </ul>
            </section>

            <div class="stat-cards-wrap panel">
                <div class="panel-head">
                    <h2>Overview</h2>
                    <select class="filter-select" id="statsPeriodSelect">
                        <option value="today" {{ $statsPeriod === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ $statsPeriod === 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ $statsPeriod === 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="year" {{ $statsPeriod === 'year' ? 'selected' : '' }}>This Year</option>
                    </select>
                </div>

                <div class="stat-cards">
                    <div class="stat-card">
                        <span class="stat-icon">
                            {{-- Swap this src for your own icon --}}
                            <img src="{{ asset('/images/management/Total Income.png') }}" alt=""
                                onerror="this.style.display='none'">
                        </span>
                        <div class="stat-info">
                            <strong id="statIncomeValue">${{ number_format($totalIncome) }}</strong>
                            <span>Total Income</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <span class="stat-icon">
                            {{-- Swap this src for your own icon --}}
                            <img src="{{ asset('images/management/Total Confirm.png') }}" alt=""
                                onerror="this.style.display='none'">
                        </span>
                        <div class="stat-info">
                            <strong id="statConfirmedValue">{{ number_format($totalConfirmedOrders) }}</strong>
                            <span>Total Confirm</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <span class="stat-icon">
                            <img src="{{ asset('images/management/Pending Products.png') }}" alt=""
                                onerror="this.style.display='none'">
                        </span>
                        <div class="stat-info">
                            <strong id="statPendingValue">{{ number_format($pendingProductCount) }}</strong>
                            <span>Pending Product</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.soft-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (e.target.closest('a, button')) return;
                const href = card.getAttribute('data-href');
                if (href) window.location.href = href;
            });
        });

        function escapeHtml(value) {
            if (value === null || value === undefined) return '';
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }
        const productsPeriodSelect = document.getElementById('productsPeriodSelect');
        const topProductsLimitSelect = document.getElementById('topProductsLimitSelect');

        function loadTopProducts() {
            const period = productsPeriodSelect?.value || 'today';
            const limit = topProductsLimitSelect?.value || '20';
            const listEl = document.getElementById('topProductsList');

            fetch(`{{ route('admin.dashboard.top-products') }}?period=${encodeURIComponent(period)}&limit=${encodeURIComponent(limit)}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                })
                .then(res => res.json())
                .then(({
                    topProducts
                }) => {
                    if (!topProducts.length) {
                        listEl.innerHTML = `
                    <li class="product-item">
                        <div class="product-info">
                            <div class="product-sub">No sales for this period.</div>
                        </div>
                    </li>
                `;
                        return;
                    }

                    listEl.innerHTML = topProducts.map(p => {
                        const initial = escapeHtml((p.name || '?').trim().charAt(0).toUpperCase() || '?');
                        const thumbHtml = p.thumb ?
                            `<img class="product-thumb" src="${escapeHtml(p.thumb)}" alt="${escapeHtml(p.name)}" onerror="this.style.display='none';">` :
                            '';

                        return `
                    <li class="product-item">
                        <span class="product-thumb-wrap">
                            <span class="product-thumb-fallback">${initial}</span>
                            ${thumbHtml}
                        </span>
                        <div class="product-info">
                            <div class="product-name">${escapeHtml(p.name)}</div>
                            <div class="product-sub">${escapeHtml(p.value)} &bull; ${escapeHtml(p.sub)}</div>
                        </div>
                    </li>
                `;
                    }).join('');
                })
                .catch(err => console.error('Failed to load top products:', err));
        }

        productsPeriodSelect?.addEventListener('change', loadTopProducts);
        topProductsLimitSelect?.addEventListener('change', loadTopProducts);

        document.getElementById('statsPeriodSelect')?.addEventListener('change', function() {
            const period = this.value;

            fetch(`{{ route('admin.dashboard.overview-stats') }}?period=${encodeURIComponent(period)}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                })
                .then(res => res.json())
                .then((stats) => {
                    document.getElementById('statIncomeValue').textContent = '$' + Math.round(stats.totalIncome)
                        .toLocaleString();
                    document.getElementById('statConfirmedValue').textContent = Number(stats
                        .totalConfirmedOrders).toLocaleString();
                    document.getElementById('statPendingValue').textContent = Number(stats.pendingProductCount)
                        .toLocaleString();
                })
                .catch(err => console.error('Failed to load overview stats:', err));
        });

        const reportPeriodSelect = document.getElementById('reportPeriodSelect');
        const reportYearSelect = document.getElementById('reportYearSelect');

        function loadReportChart() {
            const period = reportPeriodSelect?.value || 'year';
            const yAxisEl = document.getElementById('chartYAxis');
            const barsEl = document.getElementById('chartBars');

            const url = new URL('{{ route('admin.dashboard.report-chart') }}', window.location.origin);
            url.searchParams.set('report_period', period);
            if (period === 'year' && reportYearSelect?.value) {
                url.searchParams.set('year', reportYearSelect.value);
            }

            fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                })
                .then(res => res.json())
                .then(({
                    chartData,
                    yAxisSteps,
                    yAxisMax
                }) => {
                    yAxisEl.innerHTML = yAxisSteps
                        .map(step => `<span>${step}</span>`)
                        .join('');

                    barsEl.classList.toggle('dense', chartData.length > 14);

                    barsEl.innerHTML = chartData
                        .map(bar => {
                            const height = yAxisMax > 0 ? Math.max(4, (bar.value / yAxisMax) * 100) : 4;
                            return `
                        <div class="chart-col">
                            <div class="chart-bar ${bar.muted ? 'muted' : ''}" style="height:${height}%" data-value="${bar.value}" data-label="${escapeHtml(bar.label)}" tabindex="0"></div>
                            <span class="chart-label">${bar.label}</span>
                        </div>
                    `;
                        })
                        .join('');
                })
                .catch(err => console.error('Failed to load report chart:', err));
        }

        reportPeriodSelect?.addEventListener('change', function() {
            if (reportYearSelect) {
                reportYearSelect.style.display = this.value === 'year' ? '' : 'none';
            }
            loadReportChart();
        });

        reportYearSelect?.addEventListener('change', loadReportChart);

        (function() {
            const chartEl = document.querySelector('.report-panel .chart');
            const tooltipEl = document.getElementById('chartTooltip');
            if (!chartEl || !tooltipEl) return;

            function positionTooltip(barEl) {
                const value = Number(barEl.dataset.value || 0);
                const label = barEl.dataset.label || '';
                tooltipEl.textContent = `${label}: $${value.toLocaleString(undefined, { maximumFractionDigits: 0 })}`;

                const barRect = barEl.getBoundingClientRect();
                const chartRect = chartEl.getBoundingClientRect();
                tooltipEl.style.left = (barRect.left - chartRect.left + barRect.width / 2) + 'px';
                tooltipEl.style.top = (barRect.top - chartRect.top) + 'px';
                tooltipEl.classList.add('show');
            }

            function hideTooltip() {
                tooltipEl.classList.remove('show');
            }

            chartEl.addEventListener('mouseover', (e) => {
                const bar = e.target.closest('.chart-bar');
                if (bar) positionTooltip(bar);
            });
            chartEl.addEventListener('mouseout', (e) => {
                if (e.target.closest('.chart-bar')) hideTooltip();
            });
            chartEl.addEventListener('focusin', (e) => {
                const bar = e.target.closest('.chart-bar');
                if (bar) positionTooltip(bar);
            });
            chartEl.addEventListener('focusout', (e) => {
                if (e.target.closest('.chart-bar')) hideTooltip();
            });
            // Tap-to-toggle on touch devices, where there's no hover.
            chartEl.addEventListener('click', (e) => {
                const bar = e.target.closest('.chart-bar');
                if (!bar) return;
                if (tooltipEl.classList.contains('show') && tooltipEl.dataset.activeBar === bar.dataset.label) {
                    hideTooltip();
                } else {
                    positionTooltip(bar);
                    tooltipEl.dataset.activeBar = bar.dataset.label;
                }
            });
        })();
    </script>
@endpush
