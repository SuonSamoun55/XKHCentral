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

        $exchangeRate = (float) ($order->exchange_rate ?? 0);
        $totalRiel = $exchangeRate > 0 ? $total * $exchangeRate : null;

        $statusRaw = strtolower((string) ($order->status ?? 'pending'));
        $statusClass = match ($statusRaw) {
            'pending' => 'pending',
            'confirmed', 'completed', 'paid', 'delivered' => 'success',
            'cancelled', 'canceled', 'failed' => 'cancelled',
            default => 'default',
        };
        $statusText = [
            'pending'   => ['title' => 'Order is pending',   'subtitle' => 'We will notify you by inbox'],
            'success'   => ['title' => 'Order is confirmed', 'subtitle' => 'Your order has been approved'],
            'cancelled' => ['title' => 'Order is cancelled', 'subtitle' => 'This order was cancelled'],
            'default'   => ['title' => 'Order status: ' . ucfirst($order->status ?? 'Unknown'), 'subtitle' => ''],
        ][$statusClass];

        $orderActions = $order->actions?->sortByDesc('created_at') ?? collect();
        $approvedAction = $orderActions->first(fn ($a) => in_array(strtolower((string) $a->action_type), ['confirmed', 'approved'], true));
        $cancelledAction = $orderActions->first(fn ($a) => in_array(strtolower((string) $a->action_type), ['cancelled', 'canceled'], true));
    @endphp

    <div id="order-detail-page">
        @if (session('success'))
            <div class="od-alert success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="od-alert error">{{ session('error') }}</div>
        @endif

        {{-- Top bar --}}
        <div class="od-topbar">
            <a href="{{ route('user.pos.order.history') }}" class="od-back-btn">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div class="od-topbar-title">Order Detail</div>
            <a href="{{ route('user.pos.order.download', $order->id) }}" class="od-icon-btn" title="Download">
                <i class="bi bi-download"></i>
            </a>
        </div>

        {{-- Status banner --}}
        <div class="od-status-banner {{ $statusClass }}">
            <div class="od-status-icon">
                <i class="bi bi-box-seam"></i>
            </div>
            <div>
                <div class="od-status-title">{{ $statusText['title'] }}</div>
                @if ($statusText['subtitle'])
                    <div class="od-status-subtitle">{{ $statusText['subtitle'] }}</div>
                @endif
            </div>
        </div>

        {{-- Order info --}}
        <div class="od-row">
            <span>Invoice number</span>
            <strong class="od-accent">#{{ $order->order_no }}</strong>
        </div>
        <div class="od-row">
            <span>Order date</span>
            <strong>{{ optional($order->created_at)->format('j F Y') }}</strong>
        </div>
        <div class="od-row">
            <span>Customer number</span>
            <strong>{{ $order->customer_no ?? 'Guest / N/A' }}</strong>
        </div>
        <div class="od-row">
            <span>Sync status</span>
            <strong>{{ ucfirst($order->sync_status ?? 'Pending') }}</strong>
        </div>

        <div class="od-gap"></div>

        {{-- Items --}}
        <div class="od-section-title">Purchased Item ({{ (int) ($order->items->sum('qty') ?? 0) }})</div>

        @forelse ($order->items as $line)
            <div class="od-item-row">
                <img class="od-item-img"
                    src="{{ optional($line->item)->image_url ?: asset('images/no-image.png') }}"
                    alt="{{ $line->item_name ?? 'Item' }}"
                    onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';">
                <div class="od-item-info">
                    <div class="od-item-name">{{ $line->item_name ?? 'Unknown Item' }}</div>
                    <div class="od-item-sku">{{ (int) ($line->qty ?? 0) }} &times; ${{ number_format((float) ($line->unit_price ?? 0), 2) }}</div>
                </div>
                <div class="od-item-total">${{ number_format((float) ($line->line_total ?? 0), 2) }}</div>
            </div>
        @empty
            <div class="od-empty">No items found in this order.</div>
        @endforelse

        <div class="od-gap"></div>

        {{-- Payment --}}
        <div class="od-section-title">Payment</div>

        <div class="od-row muted">
            <span>Subtotal</span>
            <strong>${{ number_format($subtotal, 2) }}</strong>
        </div>
        <div class="od-row muted">
            <span>Discount</span>
            <strong class="{{ $discount > 0 ? 'od-negative' : '' }}">- ${{ number_format($discount, 2) }}</strong>
        </div>
        <div class="od-row muted">
            <span>Delivery Fee</span>
            <strong>${{ number_format($shipping, 2) }}</strong>
        </div>
        <div class="od-row muted">
            <span>Estimated Tax</span>
            <strong>${{ number_format($tax, 2) }}</strong>
        </div>

        <div class="od-line"></div>

        <div class="od-row od-total">
            <span>Total in USD</span>
            <strong>${{ number_format($total, 2) }}</strong>
        </div>
        @if (!is_null($totalRiel))
            <div class="od-row od-total">
                <span>Total in Riel</span>
                <strong>Riel {{ number_format($totalRiel, 0) }}</strong>
            </div>
        @endif

        @if ($approvedAction || $cancelledAction)
            <div class="od-gap"></div>
            <div class="od-section-title">Approval / Cancel Report</div>

            @if ($approvedAction)
                <div class="od-row">
                    <span>Approved By</span>
                    <strong>{{ $approvedAction->actionBy->name ?? 'Admin' }}<br>
                        <small>{{ optional($approvedAction->created_at)->format('M d, Y h:i A') }}</small>
                    </strong>
                </div>
            @endif

            @if ($cancelledAction)
                @php
                    $isCustomerDirectCancel = (int) ($cancelledAction->action_by ?? 0) === (int) ($order->user_id ?? 0);
                @endphp
                <div class="od-row">
                    <span>Cancelled By</span>
                    <strong>
                        {{ $cancelledAction->actionBy->name ?? 'Unknown User' }}
                        ({{ $isCustomerDirectCancel ? 'Customer Direct Cancel' : 'Admin Cancel' }})<br>
                        <small>{{ optional($cancelledAction->created_at)->format('M d, Y h:i A') }}</small>
                    </strong>
                </div>

                @if (!empty($cancelledAction->note))
                    <div class="od-row">
                        <span>Cancel Note</span>
                        <strong>{{ $cancelledAction->note }}</strong>
                    </div>
                @endif
            @endif
        @endif

        {{-- Actions --}}
        <div class="od-gap"></div>

        <a href="{{ route('user.pos.order.download', $order->id) }}" class="od-download-btn">
            Download Invoice <i class="bi bi-download"></i>
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
@endsection
