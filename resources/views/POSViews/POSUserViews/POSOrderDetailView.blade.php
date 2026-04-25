@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'Order Detail')

@push('styles')
    <style>
        #order-detail-page {
            --od-bg: white;
            --od-card: #ffffff;
            --od-border: #e5eaf1;
            --od-text: #0f172a;
            --od-muted: #64748b;
            --od-primary: #0ea5b7;
            --od-danger: #dc2626;
            --od-danger-bg: #fef2f2;
            --od-success: #059669;
            --od-success-bg: #ecfdf5;
            --od-warning: #d97706;
            --od-warning-bg: #fffbeb;
            --od-radius: 12px;
            --od-shadow: 0 1px 2px rgb(15 23 42 / 0.06);
            background: var(--od-bg);
            border-radius: 12px;
            margin: 0;
            padding: 24px;
            width: 100%;
            min-height: 100%;
            color: var(--od-text);
            overflow: auto;
        }

        #order-detail-page * {
            box-sizing: border-box;
        }

        #order-detail-page .od-alert {
            border: 1px solid;
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 14px;
            font-size: 14px;
            font-weight: 600;
        }

        #order-detail-page .od-alert.success {
            color: var(--od-success);
            background: var(--od-success-bg);
            border-color: #a7f3d0;
        }

        #order-detail-page .od-alert.error {
            color: var(--od-danger);
            background: var(--od-danger-bg);
            border-color: #fecaca;
        }

        #order-detail-page .od-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        #order-detail-page .od-back {
            color: var(--od-muted);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 8px;
        }

        #order-detail-page .od-back:hover {
            color: var(--od-primary);
        }

        #order-detail-page .od-title {
            margin: 0;
            font-size: 28px;
            line-height: 1.2;
            font-weight: 800;
            color: #0b162b;
            word-break: break-word;
        }

        #order-detail-page .od-subtitle {
            margin: 6px 0 0;
            color: var(--od-muted);
            font-size: 13px;
            font-weight: 500;
        }

        #order-detail-page .od-meta {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-top: 8px;
        }

        #order-detail-page .od-badge {
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: 1px solid transparent;
        }

        #order-detail-page .od-badge.pending {
            color: var(--od-warning);
            background: var(--od-warning-bg);
            border-color: #fcd34d;
        }

        #order-detail-page .od-badge.success {
            color: var(--od-success);
            background: var(--od-success-bg);
            border-color: #86efac;
        }

        #order-detail-page .od-badge.cancelled {
            color: var(--od-danger);
            background: var(--od-danger-bg);
            border-color: #fca5a5;
        }

        #order-detail-page .od-badge.default {
            color: #475569;
            background: #f1f5f9;
            border-color: #e2e8f0;
        }

        #order-detail-page .od-cancel-btn {
            border: 1px solid var(--od-danger);
            color: var(--od-danger);
            background: #fff;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            padding: 9px 14px;
            cursor: pointer;
        }

        #order-detail-page .od-cancel-btn:hover {
            background: var(--od-danger-bg);
        }

        #order-detail-page .od-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        #order-detail-page .od-download-btn {
            background: #e6f7fb;
            color: #00b5cc;
            border: 1px solid #b9e7f0;
            padding: 9px 14px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        #order-detail-page .od-download-btn:hover {
            background: #00b5cc;
            color: #fff;
            border-color: #00b5cc;
        }

        #order-detail-page .od-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 18px;
            align-items: start;
        }

        #order-detail-page .od-card {
            background: var(--od-card);
            border: 1px solid var(--od-border);
            border-radius: var(--od-radius);
            box-shadow: var(--od-shadow);
            overflow: hidden;
            height:100%;
        }

        #order-detail-page .od-card-head {
            padding: 14px 16px;
            border-bottom: 1px solid #eef2f7;
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
        }

        #order-detail-page .od-card-content {
            padding: 16px;
        }

        #order-detail-page .od-table-card-body {
            padding: 0;
        }

        #order-detail-page .od-table-wrap {
            overflow: auto;
            max-height: 460px;
            /* border: 1px solid var(--od-border); */
            border-radius: 10px;
        }
        #order-detail-page .od-table-wrap::-webkit-scrollbar{
            width:0px;
        }

        #order-detail-page .od-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 0;
            table-layout: fixed;
        }

        #order-detail-page .od-table th {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--od-muted);
            text-align: left;
            padding: 12px 12px;
            border-bottom: 2px solid #eef2f7;
            position: sticky;
            top: 0;
            background: #f8fafc;
            z-index: 2;
        }

        #order-detail-page .od-table td {
            font-size: 14px;
            color: #1f2937; 
            padding: 12px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        #order-detail-page .od-table tr:last-child td {
            border-bottom: none;
        }

        #order-detail-page .od-item {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        #order-detail-page .od-item-img {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        #order-detail-page .od-item-name {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #order-detail-page .od-item-sku {
            font-size: 12px;
            color: var(--od-muted);
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #order-detail-page .od-table th:nth-child(1),
        #order-detail-page .od-table td:nth-child(1) {
            width: 52%;
        }

        #order-detail-page .od-table th:nth-child(2),
        #order-detail-page .od-table td:nth-child(2) {
            width: 32px;
            white-space: nowrap;
        }

        #order-detail-page .od-table th:nth-child(3),
        #order-detail-page .od-table td:nth-child(3) {
            width: 100px;
            white-space: nowrap;
        }

        #order-detail-page .od-table th:nth-child(4),
        #order-detail-page .od-table td:nth-child(4) {
            width: 108px;
            white-space: nowrap;
            }

        #order-detail-page .od-right {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        #order-detail-page .od-sum-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: #334155;
            margin-bottom: 10px;
        }

        #order-detail-page .od-sum-row:last-child {
            margin-bottom: 0;
        }

        #order-detail-page .od-sum-row strong {
            color: #0f172a;
        }

        #order-detail-page .od-sum-total {
            margin-top: 12px;
            border-top: 1px solid #e2e8f0;
            padding-top: 12px;
            font-size: 16px;
            font-weight: 800;
        }

        #order-detail-page .od-info {
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #eef2f7;
        }

        #order-detail-page .od-info:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        #order-detail-page .od-info-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 4px;
        }

        #order-detail-page .od-info-value {
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
        }

        #order-detail-page .od-report-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid #eef2f7;
        }

        #order-detail-page .od-report-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        #order-detail-page .od-report-label {
            font-size: 12px;
            color: #64748b;
            font-weight: 700;
            min-width: 88px;
        }

        #order-detail-page .od-report-value {
            font-size: 13px;
            color: #0f172a;
            text-align: right;
            line-height: 1.45;
        }

        @media (max-width: 1080px) {
            #order-detail-page .od-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            #order-detail-page {
                margin-right: 0;
                padding: 16px;
                min-height: auto;
            }

            #order-detail-page .od-title {
                font-size: 22px;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $itemsTotal = (float) ($order->items->sum('line_total') ?? 0);
        $subtotal = (float) ($order->subtotal ?? $itemsTotal);
        $shipping = (float) ($order->shipping_amount ?? 0);
        $tax = (float) ($order->tax_amount ?? 0);
        $discount = (float) ($order->discount_amount ?? 0);
        $calculatedTotal = max(0, $itemsTotal + $shipping + $tax - $discount);
        $storedTotal = (float) ($order->total_amount ?? 0);
        $total = $itemsTotal > 0 ? $calculatedTotal : $storedTotal;

        $statusRaw = strtolower((string) ($order->status ?? 'pending'));
        $statusClass = match ($statusRaw) {
            'pending' => 'pending',
            'confirmed', 'completed', 'paid', 'delivered' => 'success',
            'cancelled', 'canceled', 'failed' => 'cancelled',
            default => 'default',
        };

        $orderActions = $order->actions?->sortByDesc('created_at') ?? collect();
        $approvedAction = $orderActions->first(function ($action) {
            return in_array(strtolower((string) $action->action_type), ['confirmed', 'approved'], true);
        });
        $cancelledAction = $orderActions->first(function ($action) {
            return in_array(strtolower((string) $action->action_type), ['cancelled', 'canceled'], true);
        });
    @endphp

    <div id="order-detail-page">
        @if (session('success'))
            <div class="od-alert success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="od-alert error">{{ session('error') }}</div>
        @endif

        <div class="od-header">
            <div>
                <a href="{{ route('user.pos.order.history') }}" class="od-back">
                    <i class="bi bi-arrow-left"></i> Back to Orders
                </a>
                <h1 class="od-title">Order #{{ $order->order_no }}</h1>
                <div class="od-meta">
                    <span class="od-badge {{ $statusClass }}">
                        {{ ucfirst(str_replace('-', ' ', $order->status ?? 'pending')) }}
                    </span>
                </div>
                <p class="od-subtitle">{{ optional($order->created_at)->format('F j, Y \a\t g:i A') }}</p>
            </div>

            <div class="od-header-actions">
                <a href="{{ route('user.pos.order.download', $order->id) }}" class="od-download-btn">
                    Download <i class="bi bi-download"></i>
                </a>

                @if ($statusRaw === 'pending')
                    <form method="POST" action="{{ route('user.pos.order.cancel', $order->id) }}"
                        onsubmit="return confirm('Are you sure you want to cancel this order?');">
                        @csrf
                        <input type="hidden" name="note" value="Cancelled directly by customer.">
                        <button type="submit" class="od-cancel-btn">Cancel Order</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="od-grid">
            <div class="od-card">
                <div class="od-card-head">Order Items ({{ (int) ($order->items->sum('qty') ?? 0) }})</div>
                <div class="od-table-card-body od-table-wrap">
                    <table class="od-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th style="text-align:center;">Qty</th>
                                <th style="text-align:right;">Unit Price</th>
                                <th style="text-align:right;">Line Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($order->items as $line)
                                <tr>
                                    <td>
                                        <div class="od-item">
                                            <img class="od-item-img"
                                                src="{{ optional($line->item)->image_url ?: asset('images/no-image.png') }}"
                                                alt="{{ $line->item_name ?? 'Item' }}"
                                                onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';">
                                            <div>
                                                <div class="od-item-name">{{ $line->item_name ?? 'Unknown Item' }}</div>
                                                <div class="od-item-sku">SKU: {{ $line->item_no ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="text-align:center;">{{ (int) ($line->qty ?? 0) }}</td>
                                    <td style="text-align:right;">${{ number_format((float) ($line->unit_price ?? 0), 2) }}</td>
                                    <td style="text-align:right;"><strong>${{ number_format((float) ($line->line_total ?? 0), 2) }}</strong></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align:center; padding: 28px 0; color:#64748b;">
                                        No items found in this order.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="od-right">
                <div class="od-card">
                    <div class="od-card-head">Payment Summary</div>
                    <div class="od-card-content">
                        <div class="od-sum-row">
                            <span>Items Total</span>
                            <strong>${{ number_format($itemsTotal, 2) }}</strong>
                        </div>
                        <div class="od-sum-row">
                            <span>Subtotal</span>
                            <strong>${{ number_format($subtotal, 2) }}</strong>
                        </div>
                        @if ($shipping > 0)
                            <div class="od-sum-row">
                                <span>Shipping</span>
                                <strong>+ ${{ number_format($shipping, 2) }}</strong>
                            </div>
                        @endif
                        @if ($tax > 0)
                            <div class="od-sum-row">
                                <span>Tax</span>
                                <strong>+ ${{ number_format($tax, 2) }}</strong>
                            </div>
                        @endif
                        @if ($discount > 0)
                            <div class="od-sum-row">
                                <span>Discount</span>
                                <strong style="color:#059669;">- ${{ number_format($discount, 2) }}</strong>
                            </div>
                        @endif
                        <div class="od-sum-row od-sum-total">
                            <span>Total</span>
                            <strong>${{ number_format($total, 2) }}</strong>
                        </div>
                    </div>
                </div>

                <div class="od-card">
                    <div class="od-card-head">Order Details</div>
                    <div class="od-card-content">
                        <div class="od-info">
                            <div class="od-info-label">Customer Number</div>
                            <div class="od-info-value">{{ $order->customer_no ?? 'Guest / N/A' }}</div>
                        </div>
                        <div class="od-info">
                            <div class="od-info-label">Order Date</div>
                            <div class="od-info-value">{{ optional($order->created_at)->format('M d, Y h:i A') }}</div>
                        </div>
                        <div class="od-info">
                            <div class="od-info-label">Sync Status</div>
                            <div class="od-info-value">{{ ucfirst($order->sync_status ?? 'Pending') }}</div>
                        </div>
                    </div>
                </div>

                <div class="od-card">
                    <div class="od-card-head">Approval / Cancel Report</div>
                    <div class="od-card-content">
                        @if ($approvedAction)
                            <div class="od-report-row">
                                <div class="od-report-label">Approved By</div>
                                <div class="od-report-value">
                                    {{ $approvedAction->actionBy->name ?? 'Admin' }}<br>
                                    {{ optional($approvedAction->created_at)->format('M d, Y h:i A') }}
                                </div>
                            </div>
                        @endif

                        @if ($cancelledAction)
                            @php
                                $isCustomerDirectCancel = (int) ($cancelledAction->action_by ?? 0) === (int) ($order->user_id ?? 0);
                            @endphp
                            <div class="od-report-row">
                                <div class="od-report-label">Cancelled By</div>
                                <div class="od-report-value">
                                    {{ $cancelledAction->actionBy->name ?? 'Unknown User' }}
                                    ({{ $isCustomerDirectCancel ? 'Customer Direct Cancel' : 'Admin Cancel' }})<br>
                                    {{ optional($cancelledAction->created_at)->format('M d, Y h:i A') }}
                                </div>
                            </div>

                            @if (!empty($cancelledAction->note))
                                <div class="od-report-row">
                                    <div class="od-report-label">Cancel Note</div>
                                    <div class="od-report-value">{{ $cancelledAction->note }}</div>
                                </div>
                            @endif
                        @endif

                        @if (!$approvedAction && !$cancelledAction)
                            <div class="od-report-value" style="text-align:left;color:#64748b;">
                                No approval/cancel action yet.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
