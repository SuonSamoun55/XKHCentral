@extends('ManagementSystemViews.UserViews.Layouts.app')
@section('content')
<div class="checkout-container">

    {{-- Header --}}
    <div class="checkout-nav">
        <a href="{{ url('/pos-system/cart') }}" class="back-btn">
            <i class="bi bi-arrow-left"></i>
        </a>
        <span class="nav-title">Checkout</span>
    </div>

    {{-- Items --}}
    <div class="checkout-section">
        <h4 class="section-title">Items</h4>

        @foreach($cart->items as $cartItem)
        <div class="checkout-item-card">
            <img src="{{ optional($cartItem->item)->image_url ?? asset('images/no-image.png') }}">

            <div class="item-content">
                <div class="item-top">
                    <strong>{{ $cartItem->item_name }}</strong>
                    <span class="item-price">
                        ${{ number_format($cartItem->price * $cartItem->qty, 0) }}
                    </span>
                </div>

                <div class="item-meta">
                    Variant: {{ $cartItem->variant ?? 'default' }}
                </div>
                <div class="item-meta">
                    x{{ $cartItem->qty }}
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Payment --}}
    <div class="checkout-section payment-box">
        <h4 class="section-title">Payment</h4>

        <div class="payment-row"><span>Subtotal</span><span>${{ number_format($subtotal, 2) }}</span></div>
        <div class="payment-row"><span>Discount</span><span>$0</span></div>
        <div class="payment-row"><span>Delivery Fee</span><span>$0</span></div>
        <div class="payment-row"><span>Estimated Tax</span><span>${{ number_format($taxAmount, 2) }}</span></div>

        <div class="divider"></div>

        <div class="payment-row total"><span>Total in USD</span><span>${{ number_format($total, 2) }}</span></div>
        <div class="payment-row riel"><span>Total in Riel</span><span>riel {{ number_format($total * 4100, 0) }}</span></div>
    </div>

    <div class="bottom-space"></div>

    <button id="placeOrderBtn" class="place-order-btn">
        Place Order
    </button>

</div>
@endsection
@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

document.getElementById('placeOrderBtn').onclick = async function () {
    const res = await fetch('/pos-system/checkout/confirm', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    });

    const data = await res.json();
    if (data.success) {
        window.location.href = "/pos-system/cart?success=1";
    } else {
        alert('Failed to place order');
    }
};
</script>
@endpush
<style>
   /* Hide sidebar for mobile checkout */
.sidebar,
.sidebar-wrap {
    display: none !important;
}

/* Layout */
.checkout-container {
    padding: 16px;
    width: 100% !important;
    height: 100vh;
    background: #ffffff;
}

/* Header */
.checkout-nav {
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 600;
    padding: 12px;

}

/* Sections */
.checkout-section {
    margin-bottom: 20px;
}

.section-title {
    padding-top: 10px;
    padding-bottom: 10px;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 12px;
}

/* Item card (iOS style) */
.checkout-item-card {
    display: flex;
    gap: 12px;
    background: #F6F9FF;
    padding: 12px;
    border-radius: 12px;
    border: 1px solid #E6EDFF;
    margin-bottom: 12px;
}

.checkout-item-card img {
    width: 56px;
    height: 56px;
    object-fit: cover;
    border-radius: 8px;
}

/* Item content */
.item-content {
    flex: 1;
}

.item-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.item-top strong {
    font-size: 14px;
}

.item-price {
    font-weight: 600;
    font-size: 14px;
}

.item-meta {
    font-size: 12px;
    color: #6B7280;
    margin-top: 2px;
}

/* Payment box */
.payment-box {
    background: #F9FAFB;
    padding: 14px;
    border-radius: 12px;
}

/* Rows */
.payment-row {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    margin: 6px 0;
}

.payment-row.total {
    font-weight: 700;
}

.payment-row.riel {
    font-weight: 600;
}

.divider {
    height: 1px;
    background: #E5E7EB;
    margin: 10px 0;
}

/* Bottom spacing so button doesn't cover content */
.bottom-space {
    height: 80px;
}

/* Fixed bottom button */
.place-order-btn {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: #33C6B7;
    color: #fff;
    border: none;
    padding: 30px;
    font-size: 16px;
    font-weight: 600;
}
</style>