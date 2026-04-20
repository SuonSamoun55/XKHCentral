@extends('POSViews.POSAdminViews.app')

@section('title', 'Stock Tracking')

@push('styles')
<style>
    .tracking-page{
        width:100%;
        height:100vh;
        overflow:auto;
        padding:20px;
        background:#f6f8fb;
    }

    .tracking-card{
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:14px;
        box-shadow:0 6px 18px rgba(15, 23, 42, 0.05);
        padding:16px;
        margin-bottom:14px;
    }

    .tracking-head{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:10px;
        flex-wrap:wrap;
        margin-bottom:10px;
    }

    .tracking-title{
        margin:0;
        font-size:24px;
        font-weight:800;
        color:#1f7f8b;
    }

    .tracking-tools,
    .tracking-links{
        display:flex;
        gap:8px;
        flex-wrap:wrap;
        align-items:center;
    }

    .tracking-input,
    .tracking-select{
        height:36px;
        border:1px solid #dbe2ea;
        border-radius:8px;
        padding:0 10px;
        font-size:13px;
        color:#334155;
        background:#fff;
    }

    .tracking-input{
        min-width:220px;
    }

    .tracking-btn{
        height:36px;
        border:none;
        border-radius:8px;
        padding:0 14px;
        font-size:13px;
        font-weight:700;
        color:#fff;
        background:#11bfd1;
        text-decoration:none;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:6px;
    }

    .tracking-btn-alt{
        background:#fff;
        color:#475569;
        border:1px solid #dbe2ea;
    }

    .table-wrap{
        width:100%;
        overflow:auto;
    }

    .tracking-table{
        width:100%;
        min-width:980px;
        border-collapse:collapse;
    }

    .tracking-table th,
    .tracking-table td{
        border-bottom:1px solid #edf2f7;
        padding:10px;
        font-size:13px;
        color:#334155;
        vertical-align:middle;
    }

    .tracking-table th{
        background:#f8fafc;
        color:#475569;
        font-weight:700;
        white-space:nowrap;
    }

    .qty-in{
        color:#15803d;
        font-weight:700;
    }

    .qty-out{
        color:#b91c1c;
        font-weight:700;
    }

    .chip{
        display:inline-flex;
        padding:4px 10px;
        border-radius:999px;
        font-size:12px;
        font-weight:700;
    }

    .chip-sync{
        background:#e0f2fe;
        color:#0369a1;
    }

    .chip-sale{
        background:#fef3c7;
        color:#92400e;
    }

    .muted{
        color:#64748b;
        font-size:12px;
    }
</style>
@endpush

@section('content')
<div class="tracking-page">
    <div class="tracking-card">
        <div class="tracking-head">
            <h1 class="tracking-title">Stock Tracking</h1>

            <div class="tracking-links">
                <a href="{{ route('store.management.index') }}" class="tracking-btn tracking-btn-alt">Back Visibility</a>
                <a href="{{ route('store.management.tracking') }}" class="tracking-btn tracking-btn-alt">Show All</a>
            </div>
        </div>

        <form method="GET" action="{{ route('store.management.tracking') }}" class="tracking-tools">
            <input type="text" name="search" value="{{ $search }}" class="tracking-input" placeholder="Search product, buyer, order...">

            <select name="source" class="tracking-select">
                <option value="all" {{ $source === 'all' ? 'selected' : '' }}>All Type</option>
                <option value="sync" {{ $source === 'sync' ? 'selected' : '' }}>Sync Only</option>
                <option value="sale" {{ $source === 'sale' ? 'selected' : '' }}>Sale Only</option>
            </select>

            <input type="date" name="date_from" value="{{ $dateFrom }}" class="tracking-select">
            <input type="date" name="date_to" value="{{ $dateTo }}" class="tracking-select">

            <select name="per_page" class="tracking-select">
                <option value="20" {{ (int) $perPage === 20 ? 'selected' : '' }}>20</option>
                <option value="50" {{ (int) $perPage === 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ (int) $perPage === 100 ? 'selected' : '' }}>100</option>
                <option value="200" {{ (int) $perPage === 200 ? 'selected' : '' }}>200</option>
            </select>

            <button type="submit" class="tracking-btn">Filter</button>
        </form>
    </div>

    <div class="tracking-card">
        <div class="tracking-head">
            <h2 class="tracking-title" style="font-size:20px;">Product Summary</h2>
        </div>

        <div class="table-wrap">
            <table class="tracking-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Item No</th>
                        <th>Added From Sync</th>
                        <th>Reduced From Sync</th>
                        <th>Sold Qty</th>
                        <th>Last Activity</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($summaryRows as $row)
                        <tr>
                            <td>{{ $row['item']->display_name ?? 'N/A' }}</td>
                            <td>{{ $row['item']->number ?? '-' }}</td>
                            <td class="qty-in">+{{ (int) ($row['added_qty'] ?? 0) }}</td>
                            <td class="qty-out">-{{ (int) ($row['reduced_qty'] ?? 0) }}</td>
                            <td class="qty-out">-{{ (int) ($row['sold_qty'] ?? 0) }}</td>
                            <td>{{ optional($row['last_activity'])->format('m/d/Y h:i A') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="muted">No summary data found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="tracking-card">
        <div class="tracking-head">
            <h2 class="tracking-title" style="font-size:20px;">Movement Details</h2>
            <span class="muted">{{ $movements->total() }} record(s)</span>
        </div>

        <div class="table-wrap">
            <table class="tracking-table">
                <thead>
                    <tr>
                        <th>Date/Time</th>
                        <th>Type</th>
                        <th>Product</th>
                        <th>Qty Change</th>
                        <th>Stock (Old -> New)</th>
                        <th>Order</th>
                        <th>Buyer</th>
                        <th>Updated By</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $move)
                        <tr>
                            <td>{{ optional($move->happened_at)->format('m/d/Y h:i A') ?? optional($move->created_at)->format('m/d/Y h:i A') }}</td>
                            <td>
                                <span class="chip {{ $move->source === 'sale' ? 'chip-sale' : 'chip-sync' }}">
                                    {{ strtoupper($move->source) }}
                                </span>
                            </td>
                            <td>
                                <div>{{ $move->item->display_name ?? 'N/A' }}</div>
                                <div class="muted">{{ $move->item->number ?? '-' }}</div>
                            </td>
                            <td class="{{ (int) $move->quantity_change >= 0 ? 'qty-in' : 'qty-out' }}">
                                {{ (int) $move->quantity_change > 0 ? '+' : '' }}{{ (int) $move->quantity_change }}
                            </td>
                            <td>{{ (int) $move->old_inventory }} -> {{ (int) $move->new_inventory }}</td>
                            <td>
                                <div>{{ $move->order->order_no ?? ($move->reference_no ?? '-') }}</div>
                            </td>
                            <td>
                                <div>{{ $move->buyer->name ?? '-' }}</div>
                                <div class="muted">{{ ucfirst($move->buyer->role ?? '') }}</div>
                            </td>
                            <td>{{ $move->actor->name ?? '-' }}</td>
                            <td>{{ $move->note ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="muted">No movement data found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:12px;">
            {{ $movements->links() }}
        </div>
    </div>
</div>
@endsection

