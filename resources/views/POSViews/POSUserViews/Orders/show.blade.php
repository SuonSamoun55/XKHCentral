@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'Order Detail')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/views/POSViews/POSUserViews/Orders/show.css') }}">
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
