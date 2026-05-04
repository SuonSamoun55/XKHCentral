@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('content')

{{-- MAIN CHECKOUT --}}
<div class="checkout-container" id="checkoutContent">

  <div class="checkout-nav">
    <a href="{{ url('/pos-system/cart') }}" class="back-btn">
        <i class="bi bi-arrow-left"></i>
    </a>
    <span class="nav-title">Checkout</span>
</div>

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

    <button id="placeOrderBtn" class="place-order-btn" type="button">
        Place Order
    </button>

</div>

{{-- PROCESSING --}}
<div id="processingScreen" class="process-screen hidden">
    <div class="process-color-overlay"></div>
<img src="{{asset('images/pos/checkout.png')}}" alt="process" class="process-image">
    <p class="process-text">Processing your order…</p>
</div>

@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    const placeBtn = document.getElementById('placeOrderBtn');
    const checkout = document.getElementById('checkoutContent');
   const processing = document.getElementById('processingScreen');

    placeBtn.onclick = async () => {

        placeBtn.disabled = true;

        // Show processing UI
        checkout.style.display = 'none';
        processing.classList.remove('hidden');

        try {
            const res = await fetch('/pos-system/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    currency: 'USD',
                    factor: 1
                })
            });

            const data = await res.json();
            if (!data.success) throw new Error('failed');

            // PULL‑DOWN COLOR CHANGE (this is the magic)
            setTimeout(() => {
                processing.classList.add('pull-down');
            }, 400);

            // // Redirect after animation completes
            setTimeout(() => {
                window.location.href = '/pos-system/cart?success=1';
            }, 1600);

        } catch (err) {
            alert('Order failed. Please try again.');
            placeBtn.disabled = false;
            checkout.style.display = 'block';
            processing.classList.add('hidden');
        }
    };
});
</script>

@endpush

<style>

/* Hide sidebar */
.sidebar,
.sidebar-wrap {
    display: none !important;
}

/* Layout */
.checkout-container {
    padding: 16px;
    width: 100%;
    min-height: 100vh;
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

/* Item Cards */
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
    border-radius: 8px;
    object-fit: cover;
}

.item-top {
    display: flex;
    justify-content: space-between;
}

.item-meta {
    font-size: 12px;
    color: #6B7280;
}

/* Payment */
.payment-box {
    background: #F9FAFB;
    padding: 14px;
    border-radius: 12px;
}

.payment-row {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    margin: 6px 0;
}

.payment-row.total {
    font-weight: 700;
}

/* Button */
.bottom-space {
    height: 90px;
}

.place-order-btn {
    position: fixed;
    left: 0;
    bottom: 0;
    width: 100%;
    padding: 22px;
    background: #33C6B7;
    color: #fff;
    border: none;
    font-weight: 600;
    font-size: 16px;
}

/* ===== Processing Screens ===== */
.process-screen {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    transition: background-color .6s ease;
}

.process-screen.hidden {
    display: none;
}

.process-screen.processing {
    background: #1F7A85;
}

.process-screen.processed {
    background: #2BB3A3;
}

.process-image {
    width: 220px;
    max-width: 80%;
    margin-bottom: 24px;
}

.process-text {
    color: #fff;
    font-size: 14px;
    opacity: .9;
}
/* ===== PROCESSING SCREEN ===== */
.process-screen {
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: #1F7A85; /* initial color */
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    overflow: hidden;
}

/* hidden state */
.process-screen.hidden {
    display: none;
}

/* image */
.process-image {
    width: 220px;
    max-width: 80%;
    margin-bottom: 24px;
    z-index: 2;
}

/* text */
.process-text {
    color: #ffffff;
    font-size: 14px;
    opacity: 0.9;
    z-index: 2;
}

/* sliding color overlay */
.process-color-overlay {
    position: absolute;
    top: -100%;
    left: 0;
    width: 100%;
    height: 100%;
    background: #2BB3A3; /* new color */
    z-index: 1;
    transition: transform 0.9s ease;
    transform: translateY(0);
}

/* active pull-down */
.process-screen.pull-down .process-color-overlay {
    transform: translateY(100%);
}

</style>