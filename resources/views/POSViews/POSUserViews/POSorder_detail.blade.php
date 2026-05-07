@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('content')

<div class="order-detail-container">

    <!-- Header -->
    <div class="order-header">
        <a href="{{ url('/pos-system/order-history') }}" class="back-btn">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4>Order Detail</h4>
    </div>

    <!-- Status Banner -->
    <div class="status-card">
        <div class="status-icon">📦</div>
        <div>
            <strong>Processing order</strong>
            <p>Orders will be received {{ $order->checked_out_at->format('d F Y') }}</p>
        </div>
    </div>

    <!-- Order Meta -->
    <div class="order-meta">
        <div>
            <span>Invoice number</span>
            <strong>#{{ $order->order_no }}</strong>
        </div>
        <div>
            <span>Order date</span>
            <strong>{{ $order->created_at->format('d F Y') }}</strong>
        </div>
    </div>

    <!-- Purchased Items -->
    <h5 class="section-title">Purchased Item</h5>

    @foreach($order->items as $item)
    <div class="item-card">
        <img src="{{ asset('images/pos/product-placeholder.png') }}" alt="item">
        <div class="item-info">
            <strong>{{ $item->item_name }}</strong>
            <p>Variant: default</p>
            <span>x{{ $item->qty }}</span>
        </div>
        <strong>${{ number_format($item->line_total, 0) }}</strong>
    </div>
    @endforeach

    <!-- Payment -->
    <h5 class="section-title">Payment</h5>

    <div class="payment-row">
        <span>Subtotal</span>
        <span>${{ number_format($order->subtotal, 2) }}</span>
    </div>
    <div class="payment-row">
        <span>Discount</span>
        <span>-$0</span>
    </div>
    <div class="payment-row">
        <span>Delivery Fee</span>
        <span>$0</span>
    </div>
    <div class="payment-row">
        <span>Estimated Tax</span>
        <span>${{ number_format($order->tax_amount ?? 0, 2) }}</span>
    </div>

    <div class="divider"></div>

    <div class="payment-row total">
        <span>Total in USD</span>
        <span>${{ number_format($order->amount_paid, 2) }}</span>
    </div>
    <div class="payment-row">
        <span>Total in Riel</span>
        <span>Riel {{ number_format($order->amount_paid * 4100, 0) }}</span>
    </div>

</div>

@endsection
<style>
    .sidebar{
        display: none;
    }
    .sidebar-wrap{
        display: none;
    }
    .app-shell{
        display: none;
    }
    .order-detail-container {
    padding: 16px;
    background: #fff;
    min-height: 100vh;
    min-width: 100%;
}

.order-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.back-btn {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #F1F5F9;
    display: flex;
    align-items: center;
    justify-content: center;
}

.status-card {
    display: flex;
    gap: 12px;
    padding: 14px;
    background: #27d3c4;
    color: #fff;
    border-radius: 12px;
    margin-bottom: 16px;
}

.status-icon {
    font-size: 24px;
}

.order-meta {
    background: #F9FAFB;
    padding: 12px;
    border-radius: 12px;
    margin-bottom: 16px;
}

.order-meta div {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
}

.section-title {
    font-weight: 600;
    margin: 18px 0 10px;
}

.item-card {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #F6F9FF;
    border-radius: 12px;
    padding: 12px;
    margin-bottom: 12px;
}

.item-card img {
    width: 56px;
    height: 56px;
    border-radius: 8px;
}

.item-info {
    flex: 1;
    font-size: 13px;
}

.payment-row {
    display: flex;
    justify-content: space-between;
    margin: 10px 0;
    font-size: 14px;
}

.payment-row.total {
    font-weight: 700;
}

.divider {
    height: 1px;
    background: #E5E7EB;
    margin: 12px 0;
}
</style>