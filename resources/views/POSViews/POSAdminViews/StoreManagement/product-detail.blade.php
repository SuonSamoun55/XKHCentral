@extends('Layout.POSAdmin.app')
@section('title', 'Product Detail')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/POSViews/POSAdminViews/StoreManagement/product-detail.css') }}?v={{ filemtime(public_path('css/views/POSViews/POSAdminViews/StoreManagement/product-detail.css')) }}">
@endpush

@section('content')
<div class="sm-detail-page">
    <div class="sm-crumb">
        <a href="{{ route('store.management.index') }}" class="sm-back">
            <i class="bi bi-chevron-left"></i>
        </a>
        <div>
            <h1 class="sm-title">Product Detail</h1>
        </div>
    </div>

    @if ($stockRisk['level'] === 'critical')
        <div class="sm-stock-alert critical">
            <i class="bi bi-exclamation-octagon-fill"></i>
            <div>
                <strong>Insufficient stock for pending demand.</strong>
                {{ $stockRisk['pending_qty'] }} units are tied up in pending orders, but only {{ $stockRisk['stock'] }} are in stock.
                @if ($stockRisk['remaining_if_confirmed'] === 0)
                    Confirming all pending orders will use up every remaining unit — review pending orders before approving.
                @else
                    Confirming all pending orders will oversell this product by {{ abs($stockRisk['remaining_if_confirmed']) }} units — review pending orders before approving.
                @endif
            </div>
        </div>
    @elseif ($stockRisk['level'] === 'warning')
        <div class="sm-stock-alert warning">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>This product is nearly out of stock.</strong>
                {{ $stockRisk['pending_qty'] }} of {{ $stockRisk['stock'] }} units in stock are already claimed by pending orders
                (only {{ $stockRisk['remaining_if_confirmed'] }} would remain if all were confirmed) — worth checking on.
            </div>
        </div>
    @endif

    <div class="sm-grid">
        <div class="sm-card">
            <div class="sm-detail-cols">
                <div>
                    <img
                        class="sm-image"
                        src="{{ $item->resolved_image_url ?: 'https://placehold.co/800x600/e5e7eb/94a3b8?text=No+Photo' }}"
                        alt="{{ $item->display_name ?? 'Item' }}"
                        onerror="this.onerror=null;this.src='https://placehold.co/800x600/e5e7eb/94a3b8?text=No+Photo'">
                </div>

                <div class="sm-info-list">
                    <div class="sm-row">
                        <span class="sm-row-label">Product Name</span>
                        <span class="sm-row-value">{{ $item->display_name ?: 'No Name' }}</span>
                    </div>
                    <div class="sm-row">
                        <span class="sm-row-label">Item Number</span>
                        <span class="sm-row-value">{{ $item->number ?: '-' }}</span>
                    </div>
                    <div class="sm-row">
                        <span class="sm-row-label">Category</span>
                        <span class="sm-row-value">{{ $item->item_category_code ?: '-' }}</span>
                    </div>
                    <div class="sm-row">
                        <span class="sm-row-label">Price</span>
                        <span class="sm-row-value">${{ number_format((float) $item->unit_price, 2) }}</span>
                    </div>
                    <div class="sm-row">
                        <span class="sm-row-label">Tax (VAT)</span>
                        <span class="sm-row-value">{{ rtrim(rtrim(number_format((float) $item->resolved_vat_percent, 2), '0'), '.') }}%</span>
                    </div>
                    <div class="sm-row">
                        <span class="sm-row-label">Stock</span>
                        <span class="sm-row-value">{{ is_null($stockAtSellingLocation) ? rtrim(rtrim(number_format((float) $item->inventory, 2), '0'), '.') : rtrim(rtrim(number_format($stockAtSellingLocation, 2), '0'), '.') }}</span>
                    </div>
                    <div class="sm-row">
                        <span class="sm-row-label">Warehouse</span>
                        <span class="sm-row-value">{{ optional($storeSetting)->selling_location_code ? ($storeSetting->selling_location_name ?: $storeSetting->selling_location_code) : 'No location selected' }}</span>
                    </div>
                    <div class="sm-row">
                        <span class="sm-row-label">Status</span>
                        <span class="sm-row-value">{{ $item->is_visible ? 'ACTIVE' : 'INACTIVE' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="sm-location-card">
            <div class="sm-location-head">Stock by Location</div>
            <div class="sm-location-scroll">
                <table class="sm-location-table">
                    <thead>
                        <tr>
                            <th>Location</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($item->locationInventories as $loc)
                            <tr>
                                    <td>{{ $loc->location_name ?: ($loc->location_code ?: 'Unassigned') }}{{ ($loc->location_name && $loc->location_code) ? ' (' . $loc->location_code . ')' : '' }}</td>
                                    <td class="{{ $loc->inventory < 0 ? 'stock-negative' : ($loc->inventory == 0 ? 'stock-zero' : '') }}">{{ rtrim(rtrim(number_format((float) $loc->inventory, 2), '0'), '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="sm-muted">No location breakdown yet — sync items to fetch it.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
    </div>

    <div class="sm-card">
        <div class="sm-head">
            <h2 class="sm-title" style="font-size:20px;">Order Status</h2>
        </div>

        <div class="sm-status-stats" id="orderStatusTabs">
            <button type="button" class="sm-status-pill pending" data-status="pending">
                <span class="dot"></span>
                <span class="label">Pending</span>
                <span class="count">{{ $orderStats['pending'] }}</span>
            </button>
            <button type="button" class="sm-status-pill confirmed" data-status="confirmed">
                <span class="dot"></span>
                <span class="label">Confirmed &middot; Posted to BC</span>
                <span class="count">{{ $orderStats['confirmed'] }}</span>
            </button>
            <button type="button" class="sm-status-pill on-the-way" data-status="on_the_way">
                <span class="dot"></span>
                <span class="label">On The Way</span>
                <span class="count">{{ $orderStats['on_the_way'] }}</span>
            </button>
            <button type="button" class="sm-status-pill delivered" data-status="delivered">
                <span class="dot"></span>
                <span class="label">Delivered</span>
                <span class="count">{{ $orderStats['delivered'] }}</span>
            </button>
            <button type="button" class="sm-status-pill cancelled" data-status="cancelled">
                <span class="dot"></span>
                <span class="label">Cancelled</span>
                <span class="count">{{ $orderStats['cancelled'] }}</span>
            </button>
        </div>

        @foreach ($statusRows as $statusKey => $rows)
            <div id="statusPanel-{{ $statusKey }}" class="sm-status-panel d-none">
                <div class="sm-table-wrap">
                    <table class="sm-table">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Order No</th>
                                <th>Qty</th>
                                <th>Amount</th>
                                <th>Ordered At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rows as $row)
                                <tr>
                                    <td>{{ $row->buyer_name }}</td>
                                    <td>{{ $row->order_no }}</td>
                                    <td>{{ (int) $row->qty }}</td>
                                    <td>${{ number_format((float) $row->line_total, 2) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($row->created_at)->format('m/d/Y h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="sm-muted">No orders in this status.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>

    <div class="sm-card">
        <div class="sm-head">
            <h2 class="sm-title" style="font-size:20px;">Buyer Tracking</h2>
            <button type="button" id="toggleBuyerBtn" class="sm-btn sm-btn-primary">Show Buyers</button>
        </div>

        <div class="sm-stats">
            <div class="sm-stat">
                <div class="k">Unique Buyers</div>
                <div class="v">{{ $buyerStats['unique_buyers'] }}</div>
            </div>
            <div class="sm-stat">
                <div class="k">Total Sold Qty</div>
                <div class="v">{{ $buyerStats['total_sold_qty'] }}</div>
            </div>
            <div class="sm-stat">
                <div class="k">Total Revenue</div>
                <div class="v">${{ number_format((float) $buyerStats['total_revenue'], 2) }}</div>
            </div>
        </div>

        <div id="buyerPanel" class="d-none">
            <form method="GET" action="{{ route('store.management.products.detail', $item->id) }}" class="sm-tools">
                <input
                    type="text"
                    name="buyer_search"
                    value="{{ $buyerSearch }}"
                    class="sm-input"
                    placeholder="Search buyer name...">

                <select name="buyer_filter" class="sm-select">
                    <option value="all" {{ $buyerFilter === 'all' ? 'selected' : '' }}>All Buyers</option>
                    <option value="top5" {{ $buyerFilter === 'top5' ? 'selected' : '' }}>Top 5 Buyers</option>
                    <option value="top10" {{ $buyerFilter === 'top10' ? 'selected' : '' }}>Top 10 Buyers</option>
                </select>

                <button type="submit" class="sm-btn sm-btn-primary">Apply</button>
            </form>

            <div class="sm-table-wrap">
                <table class="sm-table">
                    <thead>
                        <tr>
                            <th>Buyer</th>
                            <th>Total Qty</th>
                            <th>Total Orders</th>
                            <th>Total Spent</th>
                            <th>Last Bought</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($buyerRows as $row)
                            <tr>
                                <td>{{ $row->buyer_name }}</td>
                                <td>{{ (int) $row->total_qty }}</td>
                                <td>{{ (int) $row->total_orders }}</td>
                                <td>${{ number_format((float) $row->total_spent, 2) }}</td>
                                <td>{{ $row->last_bought_at ? \Carbon\Carbon::parse($row->last_bought_at)->format('m/d/Y h:i A') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="sm-muted">No buyers found for this product.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const panel = document.getElementById('buyerPanel');
    const btn = document.getElementById('toggleBuyerBtn');
    if (!panel || !btn) return;

    btn.addEventListener('click', function () {
        const hidden = panel.classList.toggle('d-none');
        btn.textContent = hidden ? 'Show Buyers' : 'Hide Buyers';
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.getElementById('orderStatusTabs');
    if (!tabs) return;

    const pills = tabs.querySelectorAll('.sm-status-pill');

    pills.forEach(function (pill) {
        pill.addEventListener('click', function () {
            const status = pill.dataset.status;
            const panel = document.getElementById('statusPanel-' + status);
            if (!panel) return;

            const wasActive = pill.classList.contains('active');

            pills.forEach(function (p) { p.classList.remove('active'); });
            document.querySelectorAll('.sm-status-panel').forEach(function (p) {
                p.classList.add('d-none');
            });

            if (!wasActive) {
                pill.classList.add('active');
                panel.classList.remove('d-none');
            }
        });
    });
});
</script>
@endsection
