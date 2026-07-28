@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'POS Dashboard')

@push('styles')

    <link rel="stylesheet" href="{{ asset('/css/views/POSViews/POSUserViews/Daskboard/dashboard.css') }}" />

@endpush

@section('content')

    <main class="content-scroll">
        @include('ManagementSystemViews.UserViews.Layouts.header_mobile')
        @include('ManagementSystemViews.UserViews.Layouts.footer')

        {{-- ============ HERO (Products / Order / Pending) ============
             Lives OUTSIDE .dashboard now so it doesn't inherit the
             grid's `gap` — it has its own margin-bottom in dashboard.css
             instead, which fixes the extra top spacing issue. --}}
        <div class="area-hero">
            <section class="card hero-card">
                <a href="{{ route('user.posinterface') }}" class="hero-card-link">
                    <div class="hero-card-image">
                        <img src="{{ asset('/images/pos/product icons.png') }}" alt="Products">
                        <div class="hero-card-info">
                            <span class="hero-card-label">Products: </span>
                            <span class="hero-card-value">{{ number_format((int) ($totalProducts ?? 0)) }}</span>
                        </div>
                    </div>
                </a>
            </section>
            <section class="card hero-card">
                <a href="{{ route('user.pos.order.history') }}" class="hero-card-link">
                    <div class="hero-card-image">
                        <img src="{{ asset('/images/pos/orders icon.png') }}" alt="Orders">
                        <div class="hero-card-info">
                            <span class="hero-card-label">Order: </span>
                            <span class="hero-card-value">{{ number_format((int) ($totalOrders ?? 0)) }}</span>
                        </div>
                    </div>
                </a>
            </section>
            <section class="card hero-card">
                <a href="{{ route('user.pos.order.history') }}?status=pending" class="hero-card-link">
                    <div class="hero-card-image">
                        <img src="{{ asset('/images/pos/pending order.png') }}" alt="Pending">
                        <div class="hero-card-info">
                            <span class="hero-card-label">Pending: </span>
                            <span class="hero-card-value">{{ number_format((int) ($pendingOrders ?? 0)) }}</span>
                        </div>
                    </div>
                </a>
            </section>
        </div>

      <div class="dashboard" id="dashboardGrid">
        <div class="area-products-summary products-summary">
            <div class="summary-stats">
                <div class="summary-stat stat-blue">
                    <span class="stat-icon"><i class="bi bi-check-circle"></i></span>
                    <span class="stat-label">Admin Confirmed</span>
                    <span class="stat-value">${{ number_format((float) ($confirmedAmount ?? 0), 2) }}</span>
                </div>
                <div class="summary-stat stat-teal">
                    <span class="stat-icon"><i class="bi bi-hourglass-split"></i></span>
                    <span class="stat-label">Pending</span>
                    <span class="stat-value">${{ number_format((float) ($pendingAmount ?? 0), 2) }}</span>
                </div>
            </div>
        </div>

        <section class="card area-recent recent-card">
            <div class="card-head">
                <h3>Recent order</h3>
                <form method="GET" action="{{ route('user.index') }}" class="year-filter-form filter-form-inline">
                    @foreach (request()->except(['year', 'month', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <select name="month" class="year-filter month-filter" onchange="this.form.submit()">
                        <option value="all" {{ $selectedMonth === null ? 'selected' : '' }}>All months</option>
                        @foreach ($monthOptions as $opt)
                            <option value="{{ $opt['value'] }}" {{ $selectedMonth === $opt['value'] ? 'selected' : '' }}>
                                {{ $opt['label'] }}
                            </option>
                        @endforeach
                    </select>
                    <select name="year" class="year-filter" onchange="this.form.submit()">
                        @foreach ($availableYears as $yr)
                            <option value="{{ $yr }}" {{ (int) $selectedYear === (int) $yr ? 'selected' : '' }}>
                                {{ $yr }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="order-list">
                @forelse($recentOrders as $order)
                    @php
                        $orderItems = $order->items;
                        $firstOrderItem = $orderItems->first();
                        $itemName = $firstOrderItem?->item?->display_name
                            ?? $firstOrderItem?->item_name
                            ?? 'Unknown item';
                        $totalQty = (int) ($orderItems->sum('qty') ?? 0);
                        $fallbackImage = 'https://cdn-icons-png.flaticon.com/512/11181/11181220.png';
                        $resolveThumbSrc = fn ($path) => $path
                            ? (str_starts_with($path, 'http') ? $path : asset($path))
                            : null;

                        $thumbImages = $orderItems
                            ->take(3)
                            ->map(fn ($oi) =>
                                $resolveThumbSrc(optional($oi->itemVariant)->image_url)
                                    ?? $resolveThumbSrc(optional($oi->item)->custom_image_url)
                                    ?? $resolveThumbSrc(optional($oi->item)->image_url)
                                    ?? $fallbackImage
                            )
                            ->values();
                        $isStacked = $orderItems->count() > 1;
                    @endphp
                    <a href="{{ route('user.pos.order.show', $order->id) }}" class="order-item">
                        <div class="order-left">
                            <div class="order-thumb-stack {{ $isStacked ? 'stacked' : 'single' }}">
                                @forelse($thumbImages as $thumbSrc)
                                    <img src="{{ $thumbSrc }}" alt="{{ $itemName }}"
                                        onerror="this.onerror=null;this.src='{{ asset('images/aside/history.png') }}';">
                                @empty
                                    <img src="{{ $fallbackImage }}" alt="{{ $itemName }}">
                                @endforelse
                            </div>
                            <div>
                                <div class="order-name">{{ $order->order_no }}</div>
                                <div class="order-sub">
                                    Date: {{ optional($order->created_at)->format('d-m-y') }} •
                                    Item {{ $totalQty }}
                                </div>
                            </div>
                        </div>
                        <div class="order-actions">
                            <span>${{ number_format((float) ($order->total_amount ?? 0), 2) }}</span>
                        </div>
                    </a>
                @empty
                    <img class="NoOrderImage" src="/images/pos/No recent Orders.png" alt="">
                @endforelse
            </div>
            <a href="{{ route('user.pos.order.history') }}" class="gold-link">summary orders →</a>
        </section>

        {{-- ============ REPORT (chart, single canvas reused by CSS sizing) ============ --}}
        <section class="card area-report report-card">
            <div class="card-head">
                <h3 class="report-title">Report</h3>
                {{-- Real year + month filter: "All months" plots Jan-Dec
                     totals for the selected year; picking a month plots
                     daily totals for that month. Only admin-confirmed
                     orders are counted (see controller). --}}
                <form method="GET" action="{{ route('user.index') }}" class="year-filter-form filter-form-inline">
                    @foreach (request()->except(['year', 'month', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <select name="month" class="year-filter month-filter" onchange="this.form.submit()">
                        <option value="all" {{ $selectedMonth === null ? 'selected' : '' }}>All months</option>
                        @foreach ($monthOptions as $opt)
                            <option value="{{ $opt['value'] }}" {{ $selectedMonth === $opt['value'] ? 'selected' : '' }}>
                                {{ $opt['label'] }}
                            </option>
                        @endforeach
                    </select>
                    <select name="year" class="year-filter" onchange="this.form.submit()">
                        @foreach ($availableYears as $yr)
                            <option value="{{ $yr }}" {{ (int) $selectedYear === (int) $yr ? 'selected' : '' }}>
                                {{ $yr }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="chart-box">
                <canvas id="reportChart"></canvas>
            </div>
        </section>

        {{-- ============ NOTIFICATION ============ --}}
        <section class="card area-notif notification-card">
            <div class="card-head">
                <h3>Notification</h3>
                <span class="unread-count">{{ number_format((int) ($unreadNotificationCount ?? 0)) }} unread</span>
            </div>
            <div class="notification">
                @forelse($recentNotifications as $notification)
                    <a href="{{ route('user.notifications') }}" class="notif-item">
                        <div class="notif-left">
                            <div class="notif-badge">
                                @if ($notification->display_icon === 'admin')
                                    @if($notification->sender_profile_image)
                                        <img src="{{ $notification->sender_profile_image }}" alt="{{ $notification->title ?? 'Notification' }}">
                                    @else
                                        <i class="bi bi-person-circle"></i>
                                    @endif
                                @elseif ($notification->display_icon === 'global')
                                    <i class="bi bi-percent"></i>
                                @elseif ($notification->display_icon === 'cancelled')
                                    <i class="bi bi-x-circle"></i>
                                @elseif ($notification->display_icon === 'confirmed')
                                    <i class="bi bi-check-circle"></i>
                                @else
                                    <i class="bi bi-truck"></i>
                                @endif
                            </div>
                            <div>
                                <div class="notif-title">{{ \Illuminate\Support\Str::limit($notification->title ?? 'Notification', 30) }}</div>
                                <div class="notif-sub">{{ \Illuminate\Support\Str::limit($notification->message ?? 'Get discount codes from sharing with friends.', 45) }}</div>
                            </div>
                        </div>
                        <div class="notif-time">{{ optional($notification->created_at)->format('H:i d/m/Y') }}</div>
                    </a>
                @empty
                    {{-- <div class="small-text">No notifications yet.</div> --}}
                    <img class="img_notification" src="/images/pos/no notification from admin.png" alt="">
                @endforelse
            </div>
            <a href="{{ route('user.notifications') }}" class="gold-link">All messages →</a>
        </section>

        {{-- ============ TOP ITEM (full width) ============ --}}
        <section class="card area-topitem summary-card">
            <div class="card-head">
                <h3>Top Item</h3>
                {{-- Now uses the same year + month filter as Recent order
                     and Report, instead of its own separate
                     week/month/year purchase_period control. --}}
                <form method="GET" action="{{ route('user.index') }}" class="year-filter-form filter-form-inline">
                    @foreach (request()->except(['year', 'month', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <select name="month" class="year-filter month-filter" onchange="this.form.submit()">
                        <option value="all" {{ $selectedMonth === null ? 'selected' : '' }}>All months</option>
                        @foreach ($monthOptions as $opt)
                            <option value="{{ $opt['value'] }}" {{ $selectedMonth === $opt['value'] ? 'selected' : '' }}>
                                {{ $opt['label'] }}
                            </option>
                        @endforeach
                    </select>
                    <select name="year" class="year-filter" onchange="this.form.submit()">
                        @foreach ($availableYears as $yr)
                            <option value="{{ $yr }}" {{ (int) $selectedYear === (int) $yr ? 'selected' : '' }}>
                                {{ $yr }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="small-sub" style="margin-bottom:10px;">
                Total Qty: <strong>{{ number_format((int) ($purchaseQtyTotal ?? 0)) }}</strong>
                •
                Total Amount: <strong>${{ number_format((float) ($purchaseAmountTotal ?? 0), 2) }}</strong>
            </div>
            <div class="order-list">
                @forelse($topPurchasedItems as $item)
                    @php
                        $boughtItemName = $item->item_name ?: 'Unknown item';

                        // Priority: item's admin-set custom_image_url
                        // override, then the item's synced image_url, then
                        // the flaticon fallback. This is an aggregate across
                        // possibly several variants, so there's no single
                        // variant image to prefer here.
                        // image_url/custom_image_url may be stored either
                        // as a full URL or a relative storage path.
                        $resolveTopImg = fn ($path) => $path
                            ? (str_starts_with($path, 'http') ? $path : asset($path))
                            : null;

                        $boughtItemImage = $resolveTopImg($item->custom_image_url ?? null)
                            ?? $resolveTopImg($item->image_url ?? null)
                            ?? 'https://cdn-icons-png.flaticon.com/512/11181/11181220.png';
                    @endphp
                    <div class="order-item">
                        <div class="order-left">
                            <div class="order-thumb-stack single">
                                <img src="{{ $boughtItemImage }}" alt="{{ $boughtItemName }}"
                                    onerror="this.onerror=null;this.src='{{ asset('images/aside/history.png') }}';">
                            </div>
                            <div>
                                <div class="order-name">{{ \Illuminate\Support\Str::limit($boughtItemName, 32) }}</div>
                                <div class="order-sub">ID: {{ $item->item_no ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="order-actions">
                            <span>{{ number_format((int) ($item->total_qty ?? 0)) }} qty</span>
                        </div>
                    </div>
                @empty
                <img class="no_top_item" src="{{ asset('/images/pos/no top item in the list.png') }}" alt="no top item">

                @endforelse
            </div>
        </section>

      </div>
    </main>
    {{-- ============ MOBILE BOTTOM NAV ============ --}}
    <div class="mobile-nav">
        <a href="{{ route('user.index') }}" class="active">
            <i class="bi bi-house-door-fill"></i><span>home</span>
        </a>
        <a href="{{ route('user.posinterface') }}">
            <i class="bi bi-box-seam"></i><span>product</span>
        </a>
        <a href="#">
            <i class="bi bi-heart"></i><span>wishlist</span>
        </a>
        <a href="{{ route('profile') }}">
            <i class="bi bi-person"></i><span>user</span>
        </a>
    </div>

@endsection

@push('styles')
    <style>
        /* Minimal styling so the year/month <select>s match the old
           button-styled ".year-filter" chip instead of the browser
           default. Safe to delete if dashboard.css already covers a
           select variant of this class. */
        select.year-filter {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            cursor: pointer;
        }
        .filter-form-inline {
            display: flex;
            gap: 8px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const track = document.getElementById("promoTrack");
            const slides = track ? track.querySelectorAll(".promo-slide") : [];
            const dotsWrap = document.getElementById("promoDots");
            const dots = dotsWrap ? dotsWrap.querySelectorAll(".promo-dot") : [];
            if (!track || slides.length === 0) return;

            let index = 0;
            setInterval(() => {
                index = index + 1 >= slides.length ? 0 : index + 1;
                track.style.transform = `translateX(-${index * 100}%)`;
                dots.forEach((dot, i) => dot.classList.toggle("active", i === index));
            }, 3000);
        });
    </script>
    <script>
        (() => {
            if (typeof Chart === 'undefined') return;

            const labels = @json($reportLabels ?? []);
            const dates = @json($reportDates ?? []);
            const values = @json($reportValues ?? []);

            const avg = values.length ? values.reduce((a, b) => a + b, 0) / values.length : 0;
            const solid = '#10c7c7';
            const light = '#d7f1f1';
            const barColors = values.map((v) => (v >= avg ? solid : light));

            // One chart config, one canvas — CSS grid handles the sizing
            // difference between desktop and mobile now, so we no longer
            // need a second duplicate chart instance for mobile.
            const ctx = document.getElementById('reportChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            data: values,
                            backgroundColor: barColors,
                            borderRadius: 6,
                            borderSkipped: false,
                            maxBarThickness: 26,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    title: (items) => dates[items?.[0]?.dataIndex ?? 0] || '',
                                    label: (item) => `Money: $${Number(item.raw || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`,
                                }
                            }
                        },
                        scales: {
                            x: { grid: { display: false } },
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0,0,0,0.05)' },
                                ticks: {
                                    callback: (value) => `${Number(value) >= 1000 ? (value / 1000) + 'K' : value}`
                                }
                            }
                        }
                    }
                });
            }
        })();
    </script>
@endpush