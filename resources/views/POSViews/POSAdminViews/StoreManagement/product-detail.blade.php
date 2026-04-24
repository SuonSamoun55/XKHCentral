@extends('POSViews.POSAdminViews.app')

@section('title', 'Product Detail')

@push('styles')
<style>
    .sm-detail-page{
        padding:20px;
        background:#f6f8fb;
        height:100vh;
        overflow-y:auto;
        overflow-x:hidden;
    }
    .sm-card{
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:14px;
        box-shadow:0 6px 18px rgba(15, 23, 42, .05);
        padding:16px;
        margin-bottom:14px;
    }
    .sm-head{ display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; }
    .sm-title{ margin:0; font-size:24px; font-weight:800; color:#1f7f8b; }
    .sm-btn{
        height:36px;
        border:none;
        border-radius:8px;
        padding:0 14px;
        font-size:13px;
        font-weight:700;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:6px;
        text-decoration:none;
        cursor:pointer;
    }
    .sm-btn-primary{ background:#11bfd1; color:#fff; }
    .sm-btn-alt{ background:#fff; color:#475569; border:1px solid #dbe2ea; }
    .sm-grid{
        display:grid;
        grid-template-columns:300px 1fr;
        gap:18px;
        margin-top:14px;
    }
    .sm-image{
        width:100%;
        height:260px;
        border-radius:10px;
        border:1px solid #e2e8f0;
        object-fit:cover;
        background:#f8fafc;
    }
    .sm-label{ color:#64748b; font-size:12px; font-weight:700; margin-bottom:2px; }
    .sm-value{ color:#0f172a; font-size:14px; font-weight:700; margin-bottom:10px; }
    .sm-stats{ display:flex; flex-wrap:wrap; gap:10px; margin-top:10px; }
    .sm-stat{
        background:#eff9fb;
        border:1px solid #d7f0f4;
        border-radius:10px;
        padding:10px 12px;
        min-width:140px;
    }
    .sm-stat .k{ color:#64748b; font-size:11px; font-weight:700; }
    .sm-stat .v{ color:#0f172a; font-size:16px; font-weight:800; }
    .sm-tools{
        margin-top:12px;
        display:flex;
        gap:8px;
        flex-wrap:wrap;
        align-items:center;
    }
    .sm-input, .sm-select{
        height:36px;
        border:1px solid #dbe2ea;
        border-radius:8px;
        padding:0 10px;
        font-size:13px;
    }
    .sm-table-wrap{ width:100%; overflow:auto; margin-top:10px; }
    .sm-table{ width:100%; min-width:760px; border-collapse:collapse; }
    .sm-table th, .sm-table td{
        border-bottom:1px solid #edf2f7;
        padding:10px;
        font-size:13px;
        color:#334155;
        text-align:left;
    }
    .sm-table th{ background:#f8fafc; color:#475569; font-weight:700; white-space:nowrap; }
    .sm-muted{ color:#64748b; font-size:12px; }
    .d-none{ display:none !important; }
    @media (max-width: 900px) { .sm-grid{ grid-template-columns:1fr; } }
</style>
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
