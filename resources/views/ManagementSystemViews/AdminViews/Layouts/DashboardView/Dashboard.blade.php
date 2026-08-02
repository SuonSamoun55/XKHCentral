@extends('Layout.Management.app')
@section('title', 'Dashboard')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('/css/management-system/Daskboard/POSadminDaskboard.css') }}">
@endpush

@section('content')
<div class="dashboard">

    {{-- ============ HERO CARDS ============ --}}
    <section class="hero-row">
        <div class="hero-card">
            <div class="hero-text">
                <strong>{{ number_format($totalProducts) }}</strong>
                <span>Total Products</span>
            </div>
            <img src="https://cdn-icons-png.flaticon.com/512/2620/2620988.png" alt="Products" onerror="this.style.display='none'">
        </div>
        <div class="hero-card">
            <div class="hero-text">
                <strong>{{ number_format($totalOrders) }}</strong>
                <span>Total Orders</span>
            </div>
            <img src="https://cdn-icons-png.flaticon.com/512/3081/3081822.png" alt="Orders" onerror="this.style.display='none'">
        </div>
        <div class="hero-card soft-card" data-href="/pos/interface">
            <div class="hero-text">
                <strong>{{ number_format($pendingOrders) }}</strong>
                <span>Pending Orders</span>
            </div>
            <img src="https://cdn-icons-png.flaticon.com/512/891/891462.png" alt="POS System" onerror="this.style.display='none'">
        </div>
    </section>

    <div class="dashboard-body">

        <section class="panel report-panel">
            <div class="panel-head">
                <h2>Report</h2>
                <button class="dropdown-btn" type="button">
                    {{ $selectedYear }}
                    <svg width="10" height="6" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>

            <div class="chart">
                <div class="chart-y">
                    @foreach (array_reverse($yAxisSteps) as $step)
                        <span>{{ $step === 0 ? '0' : $step . 'K' }}</span>
                    @endforeach
                </div>
                <div class="chart-bars">
                    @foreach ($chartData as $bar)
                        <div class="chart-col">
                            <div class="chart-bar {{ $bar['muted'] ? 'muted' : '' }}" style="height:{{ $yAxisMax > 0 ? max(4, ($bar['value'] / $yAxisMax) * 100) : 4 }}%"></div>
                            <span class="chart-label">{{ $bar['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="panel filter-panel">
            <div class="panel-head panel-head-wrap">
                <h2>Top Customers</h2>
                <div class="panel-controls">
                    <div class="segmented" data-target="topCustomersList" role="tablist" aria-label="Compare spend by period">
                        <button type="button" class="segmented-btn active" data-period="week">Week</button>
                        <button type="button" class="segmented-btn" data-period="month">Month</button>
                        <button type="button" class="segmented-btn" data-period="year">Year</button>
                    </div>
                    <select class="filter-select" data-target="topCustomersList">
                        <option value="5">Top 5</option>
                        <option value="10" selected>Top 10</option>
                        <option value="20">Top 20</option>
                        <option value="all">All</option>
                    </select>
                </div>
            </div>

            <ul class="list-simple" id="topCustomersList">
                @forelse ($topCustomers as $c)
                    <li class="list-row" data-week="{{ $c['week'] }}" data-month="{{ $c['month'] }}" data-year="{{ $c['year'] }}">
                        <img class="list-avatar" src="{{ $c['avatar'] }}" alt="{{ $c['name'] }}">
                        <div class="list-info">
                            <div class="list-name">{{ $c['name'] }}</div>
                            <div class="list-sub">{{ $c['sub'] }}</div>
                        </div>
                        <div class="list-right">
                            <span class="list-value">{{ $c['value'] }}</span>
                            <span class="list-change" data-period-label>
                                <svg class="ico-up" width="8" height="8" viewBox="0 0 8 8" fill="none"><path d="M7 7L1 1M1 1V6M1 1H6" stroke="currentColor" stroke-width="1.3"/></svg>
                                <svg class="ico-down" width="8" height="8" viewBox="0 0 8 8" fill="none"><path d="M1 1L7 7M7 7V2M7 7H2" stroke="currentColor" stroke-width="1.3"/></svg>
                                <span class="list-change-value"></span>
                            </span>
                        </div>
                    </li>
                @empty
                    <li class="list-row">
                        <div class="list-info">
                            <div class="list-sub">No customer orders yet.</div>
                        </div>
                    </li>
                @endforelse
            </ul>
        </section>

    </div>
    <div class="dashboard-body dashboard-row-2">

        <section class="panel notification-panel">
            <div class="panel-head">
                <div>
                    <h2>Notification</h2>
                    <p class="panel-sub">{{ min(2, count($notifications)) }} unread messages</p>
                </div>
            </div>

            <ul class="notif-list">
                @forelse ($notifications as $n)
                    <li class="notif-item">
                        @php
                            $initials = collect(explode(' ', $n['name']))->map(fn($w) => mb_substr($w, 0, 1))->take(2)->implode('');
                        @endphp
                        <span class="notif-avatar-wrap">
                            <span class="notif-avatar-fallback">{{ strtoupper($initials) }}</span>
                            <img class="notif-avatar" src="{{ $n['avatar'] }}" alt="{{ $n['name'] }}" onerror="this.style.display='none'">
                        </span>
                        <div class="notif-name-block">
                            <div class="notif-name">{{ $n['name'] }}</div>
                            <div class="notif-role">{{ $n['role'] }}</div>
                        </div>
                        <button class="chat-btn" type="button">Chat</button>
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

        <section class="panel filter-panel">
            <div class="panel-head">
                <h2>Top Items</h2>
                <select class="filter-select" data-target="topItemsList">
                    <option value="5">Top 5</option>
                    <option value="10" selected>Top 10</option>
                    <option value="20">Top 20</option>
                    <option value="all">All</option>
                </select>
            </div>

            <ul class="list-simple" id="topItemsList">
                @forelse ($topItems as $it)
                    <li class="list-row">
                        <img class="list-thumb" src="{{ $it['thumb'] ?? asset('images/product-placeholder.png') }}" alt="{{ $it['name'] }}" onerror="this.style.background='var(--primary-faint)';this.src='';">
                        <div class="list-info">
                            <div class="list-name">{{ $it['name'] }}</div>
                            <div class="list-sub">{{ $it['sub'] }}</div>
                        </div>
                        <span class="list-value">{{ $it['value'] }}</span>
                    </li>
                @empty
                    <li class="list-row">
                        <div class="list-info">
                            <div class="list-sub">No item sales for {{ $selectedYear }}.</div>
                        </div>
                    </li>
                @endforelse
            </ul>
        </section>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.soft-card').forEach(card => {
    card.addEventListener('click', function (e) {
        if (e.target.closest('a')) return;
        const href = card.getAttribute('data-href');
        if (href) window.location.href = href;
    });
});

// Top Customers / Top Items count filter (5 / 10 / 20 / All)
function applyListFilter(select) {
    const list = document.getElementById(select.dataset.target);
    if (!list) return;
    const rows = list.querySelectorAll('.list-row');
    const limit = select.value === 'all' ? rows.length : parseInt(select.value, 10);
    rows.forEach((row, i) => {
        row.style.display = i < limit ? '' : 'none';
    });
}

document.querySelectorAll('.filter-select').forEach((select) => {
    applyListFilter(select); // apply the default selection on load
    select.addEventListener('change', () => applyListFilter(select));
});

// Top Customers — Week / Month / Year spend-change comparison
function applyPeriod(list, period) {
    list.querySelectorAll('.list-row[data-week]').forEach((row) => {
        const changeEl = row.querySelector('.list-change');
        const valueEl = row.querySelector('.list-change-value');
        if (!changeEl || !valueEl) return;

        const raw = row.dataset[period]; // 'week' | 'month' | 'year'
        const pct = parseInt(raw, 10) || 0;
        const isDown = pct < 0;

        changeEl.classList.toggle('down', isDown);
        valueEl.textContent = Math.abs(pct) + '%';
    });
}

document.querySelectorAll('.segmented').forEach((group) => {
    const list = document.getElementById(group.dataset.target);
    if (!list) return;

    const buttons = group.querySelectorAll('.segmented-btn');
    buttons.forEach((btn) => {
        btn.addEventListener('click', () => {
            buttons.forEach((b) => b.classList.remove('active'));
            btn.classList.add('active');
            applyPeriod(list, btn.dataset.period);
        });
    });

    // apply the default (Week) selection on load
    const active = group.querySelector('.segmented-btn.active');
    if (active) applyPeriod(list, active.dataset.period);
});
</script>
@endpush