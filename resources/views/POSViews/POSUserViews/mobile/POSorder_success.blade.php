@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('content')

<div class="order-success-wrapper">

    {{-- Header --}}
    <div class="order-success-header">
        <span class="header-title">Order</span>
        <a href="{{ route('user.posinterface') }}" class="close-btn">
            <i class="bi bi-x"></i>
        </a>
    </div>

    {{-- Illustration --}}
    <div class="success-illustration">
  <img
        src="{{ asset('images/pos/emptycart.png') }}"
        alt="Empty cart"
        class="empty-cart-illustration"
    >    </div>

    {{-- Message --}}
    <h2 class="success-title">Your order has been placed!</h2>
    <p class="success-desc">
        The order will be forwarded to the seller.<br>
        Please check status of your order in the order list.
    </p>

  {{-- Order Detail --}}
<div class="order-detail-card">
    <div class="order-detail-header">
        <h4>Order detail</h4>
    </div>

    <div class="detail-row">
        <span>Order number</span>
        <strong>#{{ $orderNumber ?? 'JKL4522A' }}</strong>
    </div>

    <div class="detail-row">
        <span>Amount paid</span>
        <strong>{{ $amountPaid ?? 'None' }}</strong>
    </div>
</div>
    {{-- Action --}}
   <a href="{{ route('user.pos.order.detail', $orderId) }}" class="primary-btn">
    Check order
</a>

</div>

@endsection
<style>
    .sidebar{
        display: none !important;
    }
    .sidebar-wrap{
        display: none !important;
    }
    /* Page wrapper */
.order-success-wrapper {
    min-height: 100vh;
    min-width: 100vw;
    padding: 24px 16px 40px;
    text-align: center;
    background: #ffffff;
}

/* Header */
.order-success-header {
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    font-weight: 600;
    margin-bottom: 24px;
}

.header-title {
    font-size: 16px;
}

.close-btn {
    position: absolute;
    right: 0;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #000;
    text-decoration: none;
}

/* Illustration */
.success-illustration {
    margin-bottom: 24px;
}

.success-illustration img {
    width: 220px;
    max-width: 80%;
}

/* Text */
.success-title {
    font-size: 18px;
    font-weight: 600;
    color: #06b6d4;
    margin-bottom: 8px;
}

.success-desc {
    font-size: 13px;
    color: #6b7280;
    line-height: 1.5;
    margin-bottom: 28px;
}

/* Detail card */
.order-detail-card {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 14px 16px;
    margin-bottom: 24px;
    text-align: left;
}
.order-detail-card {
    width: 100%;
    background: #f8f9fc;
    border: 1px solid #d9dfef;
    border-radius: 10px;
    overflow: hidden;
    margin-top: 20px;
}

.order-detail-header {
    background: #eef2fa;
    padding: 14px 16px;
    border-bottom: 1px solid #d9dfef;
}

.order-detail-header h4 {
    margin: 0;
    font-size: 15px;
    font-weight: 700;
    color: #1d2a57;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 16px;
    border-bottom: 1px solid #e2e7f2;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-row span {
    font-size: 13px;
    color: #7a86a8;
    font-weight: 500;
}

.detail-row strong {
    font-size: 14px;
    color: #111827;
    font-weight: 700;
}
/* Button */
.primary-btn {
    display: block;
    width: 100%;
    padding: 18px;
    border-radius: 12px;
    background: #2dd4bf;
    color: #ffffff;
    font-weight: 600;
    text-decoration: none;
    font-size: 15px;
}
</style>