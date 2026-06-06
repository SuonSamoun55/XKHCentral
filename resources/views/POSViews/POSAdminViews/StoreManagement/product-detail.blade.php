@extends('POSViews.POSAdminViews.app')

@section('title', 'Product Detail')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/POSViews/POSAdminViews/StoreManagement/product-detail.css') }}">
@endpush

@section('content')
<div class="sm-detail-page">
    <div class="sm-card">
        <div class="sm-head">
            <h1 class="sm-title">Product Detail</h1>
            <a href="{{ route('store.management.index') }}" class="sm-btn sm-btn-alt">Back</a>
        </div>

        <div class="sm-grid">
            <div>
                <img
                    class="sm-image"
                    src="{{ $item->image_url ?: 'https://placehold.co/800x600/e5e7eb/94a3b8?text=No+Photo' }}"
                    alt="{{ $item->display_name ?? 'Item' }}"
                    onerror="this.src='https://placehold.co/800x600/e5e7eb/94a3b8?text=No+Photo'">
            </div>

            <div>
                <div class="sm-label">Product Name</div>
                <div class="sm-value">{{ $item->display_name ?: 'No Name' }}</div>

                <div class="sm-label">Item Number</div>
                <div class="sm-value">{{ $item->number ?: '-' }}</div>

                <div class="sm-label">Category</div>
                <div class="sm-value">{{ $item->item_category_code ?: '-' }}</div>

                <div class="sm-label">Price</div>
                <div class="sm-value">${{ number_format((float) $item->unit_price, 2) }}</div>

                <div class="sm-label">Stock</div>
                <div class="sm-value">{{ (int) $item->inventory }}</div>

                <div class="sm-label">Status</div>
                <div class="sm-value">{{ $item->is_visible ? 'ACTIVE' : 'INACTIVE' }}</div>
            </div>
        </div>
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
</script>
@endsection
