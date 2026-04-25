<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>POS Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/ManagementSystem/dashboard.css') }}" />

</head>
<body>
    <div class="app-shell" id="appShell">



        @include('ManagementSystemViews.UserViews.Layouts.aside')

        <main class="content-scroll">
            <div class="dashboard-grid">

                <section class="card hero-card">
                    <div class="hero-title">Product</div>
                    <a href="/pos-system">

                        <div class="pos-product">
                            <img src="{{ asset('images/pos/product 1.png') }}" alt="Product">
                        </div>
                    </a>
                </section>

                <section class="card hero-card">
                    <div class="hero-title">Orders</div>
                    <a href="{{ route('user.pos.order.history') }}">
                        <div class="Orders">
                            <img src="{{ asset('images/pos/product 2.png') }}" alt="Orders">
                        </div>
                    </a>
                    <div class="small-sub">Total Orders: <strong>{{ number_format((int) ($totalOrders ?? 0)) }}</strong></div>
                </section>

                <section class="card hero-card">
                    <div class="hero-title">Pending Orders</div>
                    <a href="{{ route('user.pos.order.history', ['status' => 'Pending']) }}">
                        <div class="pos-system">
                            <img src="{{ asset('images/pos/product 3.png') }}" alt="POS">
                        </div>
                    </a>
                    <div class="small-sub">Pending: <strong>{{ number_format((int) ($pendingOrders ?? 0)) }}</strong></div>
                </section>

                <section class="card recent-card">
                    <div class="card-head">
                        <h3>Recent Order</h3>
                        <div class="small-text">Sort by Newest ⌄</div>
                    </div>
                    <div class="order-list">
                        @forelse($recentOrders as $order)
                            @php
                                $firstOrderItem = $order->items->first();
                                $itemName = $firstOrderItem?->item?->display_name
                                    ?? $firstOrderItem?->item_name
                                    ?? 'Unknown item';
                                $itemImage = 'https://cdn-icons-png.flaticon.com/512/11181/11181220.png';
                                $totalQty = (int) ($order->items->sum('qty') ?? 0);
                            @endphp
                            <a href="{{ route('user.pos.order.show', $order->id) }}" class="order-item">
                                <div class="order-left">
                                    <img class="recent-order-icon" src="{{ $itemImage }}" alt="{{ $itemName }}"
                                        onerror="this.onerror=null;this.src='{{ asset('images/aside/history.png') }}';">
                                    <div>
                                        <div class="order-name">{{ $order->order_no }}</div>
                                        <div class="order-sub">
                                            {{ \Illuminate\Support\Str::limit($itemName, 30) }} •
                                            {{ $totalQty }} item(s)
                                        </div>
                                    </div>
                                </div>
                                <div class="order-actions">
                                    <span>${{ number_format((float) ($order->total_amount ?? 0), 2) }}</span>
                                </div>
                            </a>
                        @empty
                            <div class="small-text">No recent orders yet.</div>
                        @endforelse
                    </div>

                    <a href="{{ route('user.pos.order.history') }}" class="gold-link">summary orders →</a>
                </section>

                <section class="card report-card">
                    <div class="card-head">
                        <h3 class="report-title">Report</h3>
                        <div class="small-text">Orders & Money</div>
                    </div>

                    <div class="chart-box">
                        <canvas id="reportChart"></canvas>
                    </div>

                    <div class="chart-years" style="font-size:12px;">
                        <span>Total: {{ number_format((int) ($totalOrders ?? 0)) }}</span>
                        <span>Amount: ${{ number_format((float) ($totalOrderAmount ?? 0), 2) }}</span>
                    </div>
                </section>

                <section class="mini-stats">
                    <div class="card mini-card">
                        <div class="mini-label">This month orders</div>
                        <div class="mini-value">{{ number_format((int) ($thisMonthOrders ?? 0)) }}</div>
                        <div class="mini-sub accent">{{ now()->format('F Y') }}</div>
                    </div>

                    <div class="card mini-card">
                        <div class="mini-label">This month amount</div>
                        <div class="mini-value">${{ number_format((float) ($thisMonthAmount ?? 0), 2) }}</div>
                        <div class="mini-sub">based on your orders</div>
                    </div>

                    <div class="card mini-card">
                        <div class="mini-label">Pending orders</div>
                        <div class="mini-value">{{ number_format((int) ($pendingOrders ?? 0)) }}</div>
                        <div class="mini-sub">need approval/delivery</div>
                    </div>
                </section>

                <section class="card summary-card">
                    <div class="card-head">
                        <h3>Items You Bought</h3>
                        <form method="GET" action="{{ route('user.index') }}">
                            <select name="purchase_period" onchange="this.form.submit()" class="period-filter">
                                <option value="week" {{ ($purchasePeriod ?? 'month') === 'week' ? 'selected' : '' }}>Week</option>
                                <option value="month" {{ ($purchasePeriod ?? 'month') === 'month' ? 'selected' : '' }}>Month</option>
                                <option value="year" {{ ($purchasePeriod ?? 'month') === 'year' ? 'selected' : '' }}>Year</option>
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
                                $boughtItemImage = $item->image_url ?: 'https://cdn-icons-png.flaticon.com/512/11181/11181220.png';
                            @endphp
                            <div class="order-item">
                                <div class="order-left">
                                    <img src="{{ $boughtItemImage }}" alt="{{ $boughtItemName }}"
                                        onerror="this.onerror=null;this.src='{{ asset('images/aside/history.png') }}';">
                                    <div>
                                        <div class="order-name">{{ \Illuminate\Support\Str::limit($boughtItemName, 32) }}</div>
                                        <div class="order-sub">
                                            ID: {{ $item->item_no ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="order-actions">
                                    <span>{{ number_format((int) ($item->total_qty ?? 0)) }} qty</span>
                                </div>
                            </div>
                        @empty
                            <div class="small-text">No bought items in this period.</div>
                        @endforelse
                    </div>
                </section>

                <section class="card notification-card">
                    <a href="{{ route('user.notifications') }}">
                        <h3>Notification</h3>
                    </a>
                    <div class="small-sub">{{ number_format((int) ($unreadNotificationCount ?? 0)) }} unread messages</div>

                    <div class="order-list" style="margin-top:10px;">
                        @forelse($recentNotifications as $notification)
                            <a href="{{ route('user.notifications') }}" class="order-item">
                                <div class="order-left">
                                    <img src="{{ $notification->sender_profile_image ?: asset('images/default-user.png') }}"
                                        alt="{{ $notification->title ?? 'Notification' }}">
                                    <div>
                                        <div class="order-name">{{ \Illuminate\Support\Str::limit($notification->title ?? 'Notification', 26) }}</div>
                                        <div class="order-sub">{{ optional($notification->created_at)->format('M d, Y h:i A') }}</div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="small-text">No notifications yet.</div>
                        @endforelse
                    </div>

                    <a href="{{ route('user.notifications') }}" class="gold-link">All messages →</a>
                </section>

            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (() => {
            const ctx = document.getElementById('reportChart');
            if (!ctx || typeof Chart === 'undefined') return;

            const labels = @json($reportLabels ?? []);
            const dates = @json($reportDates ?? []);
            const values = @json($reportValues ?? []);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        data: values,
                        borderColor: '#22b8a7',
                        backgroundColor: 'rgba(34, 184, 167, 0.14)',
                        fill: true,
                        borderWidth: 2,
                        tension: 0.35,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        pointBackgroundColor: '#22b8a7',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                title: (items) => {
                                    const i = items?.[0]?.dataIndex ?? 0;
                                    return dates[i] || '';
                                },
                                label: (item) => `Money: $${Number(item.raw || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`,
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (value) => `$${Number(value).toLocaleString()}`
                            }
                        }
                    }
                }
            });
        })();
    </script>

</body>

</html>
