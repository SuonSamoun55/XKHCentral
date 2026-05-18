@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'POS Cart')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/POSsystem/cart.css') }}">
    <style>
        :root {
            --primary-teal: #00cad1;
            --text-gray: #777;
            --text-dark: #333;
            --bg-light: white;
        }

        body {
            background-color: #fff;
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
        }

        .cart-container {
            width: 100%;
            margin: 0 auto;
            padding: 1% 2%;
            background-color: var(--bg-light);
            border-radius: 12px;
            /* height:%; */
        }

        #cartMainContent {
            width: 100%;
            height: 100vh;
            overflow-y: auto;
            padding: 0 20% 1% 10%;
            display: flex;
            flex-direction: column;
            min-height: 0;
        }

        /* Header */
        .cart-nav {
            display: flex;
            align-items: center;
            margin-bottom: 1%;
        }

        .back-btn {
            background: #f5f5f5;
            border-radius: 50%;
            width: 3.5%;
            aspect-ratio: 1 / 1;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #333;
            margin-right: 1.5%;
            min-width: 2.1rem;
            max-width: 2.6rem;
        }

        .nav-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--primary-teal);
        }

        /* --- Scroll Logic Classes --- */
        .cart-list-wrapper {
            padding: 0 4% 0 4%;
            width: 100%;
            height: auto;
            min-height: 0;
        }

        /* If items > 5 */
        .scroll-limit-5 {
            max-height: min(60vh, 24rem);
            overflow-y: auto;
            padding-right: 1%;
        }

        /* If items > 10 */
        .scroll-limit-10 {
            max-height: min(56vh, 32rem);
            overflow-y: auto;
            padding-right: 1%;
        }

        /* Custom Scrollbar for better UI */
        .scroll-limit-5::-webkit-scrollbar,
        .scroll-limit-10::-webkit-scrollbar {
            width: 0px;
        }

        .scroll-limit-5::-webkit-scrollbar-thumb,
        .scroll-limit-10::-webkit-scrollbar-thumb {
            background: #ddd;
            border-radius: 10px;
        }

        /* Compact Item Row */
        .item-card {
            display: flex;
            align-items: center;
            gap: 2%;
            padding: 1.6% 0;
            border-bottom: 1px solid #f0f0f0;
            position: relative;
        }

        .item-image {
            width: 5%;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-radius: 10%;
            flex-shrink: 0;
            max-width: 6rem;
        }

        .item-details {
            flex: 1;
        }

        .item-details h3 {
            font-size: 12px;
            /* margin: 0 0 8px 0;  */
            font-weight: 600;
        }

        /* Compact Quantity Controls */
        .qty-controls {
            display: flex;
            align-items: center;
            gap: 6%;
            background: #f5f5f5;
            /* padding: 1% 3%; */
            border-radius: 8px;
            width: 10%;

            justify-content: space-between;
        }

        .qty-btn {
            border: none;
            background: none;
            font-size: 16px;
            cursor: pointer;
            color: #555;
            padding: 0;
        }

        .qty-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .qty-val {
            font-size: 13px;
            font-weight: 600;
        }

        .remove-icon {
            position: absolute;
            right: 0%;
            top: 15%;
            color: #ff5b5b;
            font-size: 18px;
            cursor: pointer;
        }

        /* Summary Section */
        .summary-box {
            background-color: #FAFEFF;
            /* margin-top: 2%; */
            padding: 2% 4% 0.5% 4%;
            border-top: 1px solid #eee;
        }

        .summary-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 13px;
            color: var(--text-gray);
        }

        .summary-line.total-usd {
            color: var(--text-dark);
            font-weight: 600;
            font-size: 12px;
            /* margin-top: 10px; */
        }

        .summary-line.total-riel {
            font-weight: 600;
            color: #000;
            font-size: 12px;
        }

        /* Place Order Button */
        .place-order-btn {
            background: var(--primary-teal);
            color: white;
            border: none;
            width: 30%;
            padding: 1% 2%;
            border-radius: 30px;
            font-weight: bold;
            font-size: 14px;
            display: block;
            margin: 0% auto 0;
            cursor: pointer;
            text-transform: uppercase;
            box-shadow: 0 4px 12px rgba(0, 202, 209, 0.2);
            transition: transform 0.2s;
            margin-top: 1%;
        }

        .place-order-btn:active {
            transform: scale(0.98);
        }

        .empty-state {
            text-align: center;
            padding: 5% 0;
        }

        .empty-state-image {
            width: 25%;
            margin-bottom: 2%;
            max-width: 10rem;
        }

        .empty-state-link {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        #orderSuccessContent {
            text-align: center;
            padding-top: 4%;
        }

        .success-icon-circle {
            background: var(--primary-teal);
            width: 14%;
            aspect-ratio: 1 / 1;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2%;
            color: white;
            font-size: 2.2rem;
            max-width: 5rem;
            min-width: 4rem;
        }

        .empty-description {
            color: #666464;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 25px;
            margin-top: 10px;
        }

        .shopingBtn {

            padding: 12px;
            border-radius: 12px;
            background: #2dd4bf;
            color: #ffffff;
            font-weight: 600;
            font-size: 15px;
            border: none;
        }

        .bi-arrow-left {
            width: 40px;
            height: 40px;
            border-radius: 50px;
            background: #dee8ec;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .success-desc {
            color: #666;
            font-size: 14px;
        }

        .back-home-btn {
            text-decoration: none;
            margin-top: 2%;
        }

        .desktop-only {
            display: block;
        }

        .mobile-only {
            display: none;
        }

        /* =========================
            MOBILE CART UI REWRITE
        ========================= */
        @media (max-width: 768px) {

            body {
                background: #f6f7f9;
            }

            .cart-container {
                padding: 0;
                /* space for sticky bottom */
                border-radius: 0;
                height: 100vh;
                overflow-y: auto;
            }

            #cartMainContent {
                width: 100%;
                height: 100vh;
                overflow-y: auto;
                padding: 0 !important;
                display: flex;
                flex-direction: column;
                min-height: 0;
            }
            /* Header */
            .cart-nav {
                display: none;
            }

            .nav-title {
                font-size: 16px;
                font-weight: 600;
                text-align: center;
                flex: 1;
                color: #000;
            }

            /* Cart list spacing */
            .cart-list-wrapper {
                padding: 0;
                margin-top: 8px;
            }

            /* Each item becomes a CARD */
            .item-card {
                background: #f1f5f9;
                border-radius: 14px;
                padding: 12px;
                margin-bottom: 12px;
                border: none;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
                gap: 12px;
            }

            .item-image {
                width: 80px;
                height: 80px;
                border-radius: 12px;
                object-fit: contain;
                background: #f1f5f9;
            }

            .item-details h3 {
                font-size: 14px;
                margin-bottom: 6px;
            }

            /* Quantity controls – touch friendly */
            .qty-controls {
                width: 120px;
                height: 36px;
                background: #eef2f7;
                border-radius: 999px;
                padding: 0 8px;
            }

            .qty-btn {
                font-size: 18px;
                width: 28px;
                height: 28px;
            }

            .qty-val {
                font-size: 14px;
            }

            /* Remove icon */
            .remove-icon {
                top: 10px;
                right: 10px;
                font-size: 20px;
            }

            /* =========================
                STICKY BOTTOM SUMMARY
            ========================= */
            .summary-box {
                position: fixed;
                bottom: 8%;
                left: 0;
                right: 0;
                z-index: 100;
                background: #1f7a85;
                /* teal like screenshot */
                color: #fff;
                padding: 12px 16px 16px;
                border-radius: 16px 16px 0 0;
            }

            .summary-line {
                font-size: 13px;
                color: rgba(255, 255, 255, 0.85);
            }

            .summary-line.total-usd,
            .summary-line.total-riel {
                color: #fff;
                font-size: 14px;
            }

            /* Checkout button */
            .place-order-btn {
                width: 100%;
                background: #2fd4c7;
                /* margin-top: 100%; */
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                padding: 34px;
                font-size: 15px;
                border-radius: 14px;
                box-shadow: none;
            }

            /* Hide scrollbar for clean mobile feel */
            .scroll-limit-5,
            .scroll-limit-10 {
                max-height: none;
                overflow: visible;
            }

            .desktop-only {
                display: none !important;
            }

            .mobile-only {
                display: block !important;
            }
            /* Mobile Empty Cart Layout */
            .empty-cart-mobile {
                padding: 16px;
                text-align: center;

                min-height: 100vh;
                background: #fff;

                /*
     position: sticky;
        bottom: 0;
        background: #fff; */

            }

            .empty-cart-content {

                padding: 16px;
                padding-bottom: 220px;

            }

            .empty-cart-footer {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 20;
                background: #fff;
            }


            /* Item count */
            .empty-cart-mobile .item-count {
                text-align: left;
                font-size: 14px;
                color: #374151;
                margin-bottom: 20px;
            }

            /* Illustration */
            .empty-cart-illustration {
                width: 240px;
                max-width: 80%;
                margin: 0 auto 24px;
                display: block;
            }

            /* Title */
            .empty-title {
                font-size: 18px;
                font-weight: 600;
                margin-bottom: 6px;
            }

            /* Description */
            .empty-desc {
                font-size: 13px;
                color: #6B7280;
                margin-bottom: 20px;
                line-height: 1.4;
            }

            /* Shop Now button */
            .shop-now-btn {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: #14B8A6;
                color: #ffffff;
                padding: 12px 18px;
                border-radius: 12px;
                text-decoration: none;
                font-weight: 600;
                margin-bottom: 28px;
            }

            /* Summary card */
            .empty-summary-card {

                width: 100%;
                background: #1F7A85;
                color: #fff;
                padding: 14px 16px;
                font-size: 13px;


            }

            .empty-summary-card>div {

                display: flex;
                justify-content: space-between;
                line-height: 1.6;

            }

            .empty-summary-card hr {

                border: none;
                border-top: 1px solid rgba(255, 255, 255, 0.3);
                margin: 8px 0;

            }

            .empty-summary-card .total {
                font-weight: 600;
            }

            /* Disabled checkout */
            .checkout-disabled {

                width: 100%;
                border: none;
                background: #2EC4B6;
                color: #fff;
                padding: 15px 0;
                font-size: 15px;
                border-radius: 0;
                margin: 0;

            }

            .icon-btn {
                width: 40px;
                height: 40px;
                border-radius: 12px;
                background: #d4eaf5;
                display: flex;
                align-items: center;
                justify-content: center;
            }
             .checkout-container {
                display: flex;
        padding: 16px;
        width: 100%;
        min-height: 100vh;
        overflow-y: auto;
        background: #ffffff;
    }

    /* Header */
    .checkout-nav {
        display: flex;
        align-items: center;
        gap: 100px;
        font-weight: 600;
        padding-bottom: 18px;
    }

    .icon-btn {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: #d4eaf5;
        display: flex;
        align-items: center;
        justify-content: center;
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
        margin: 12px 0;
    }

    .payment-row.total {
        font-weight: 700;
    }

    /* Button */
    .bottom-space {
        height: 90px;
    }

    .placeOrderBtn {
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
        background: #1F7A85;
        /* initial color */
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
        background: #2BB3A3;
        /* new color */
        z-index: 1;
        transition: transform 0.9s ease;
        transform: translateY(0);
    }

    /* active pull-down */
    .process-screen.pull-down .process-color-overlay {
        transform: translateY(100%);
    }

     .sidebar{
        display: none ;
    }
    .sidebar-wrap{
        display: none ;
    }
    /* Page wrapper */
.order-success-wrapper {
    position: fixed;
    inset: 0;
    z-index: 10000;
    min-height: 100vh;
    min-width: 100vw;
    padding: 24px 16px 40px;
    text-align: center;
    background: #ffffff;
    overflow-y: auto;
}

.hidden-success {
    display: none;
}

.hidden-order-detail {
    display: none !important;
}

.order-detail-page {
    position: fixed;
    inset: 0;
    z-index: 10000;
    background: #ffffff;
    overflow-y: auto;
    padding: 16px;
    min-height: 100vh;
    width: 100%;
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
/* Order Detail */


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
    overflow-y: auto;
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


        }
    </style>
@endpush

@section('content')
    <div class="cart-container">
        <div class="cart-nav">
            <a href="/pos-system" class="icon-btn"><i class="bi bi-arrow-left"></i></a>
            <span class="nav-title">My Cart</span>
        </div>

        <div id="cartMainContent">
            @if (!$cart || $cart->items->isEmpty())

                <div class="empty-state desktop-only">
                    <img src="{{ asset('images/pos/Empty.png') }}" class="empty-state-image">
                    <h3 style="color: #ccc;">Your cart is Empty</h3>
                    <p class="empty-description">Looks like you haven’t <br> added anything to your cart yet</p>
                    <button class="shopingBtn">
                        <a href="/pos-system" class="empty-state-link">Continue Shopping</a>
                    </button>
                </div>

                <div class="empty-cart-mobile mobile-only">
                    <div class="empty-cart-content">
                        <div class="item-count">0 items</div>

                        <img src="{{ asset('images/pos/image_16.png') }}" alt="Empty cart" class="empty-cart-illustration">

                        <h3 class="empty-title">Your cart is empty</h3>

                        <p class="empty-desc">
                            Looks like you haven’t added anything<br>
                            to your cart yet
                        </p>

                        <a href="{{ route('user.pos.products') }}" class="shop-now-btn">
                            Shop now <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                    <div class="empty-cart-footer">
                        <div class="empty-summary-card">
                            <div><span>Subtotal</span><span>$0</span></div>
                            <div><span>Discount</span><span>$0</span></div>
                            <div><span>Delivery Fee</span><span>$0</span></div>
                            <div><span>Estimated Tax</span><span>$0</span></div>

                            <hr>

                            <div class="total"><span>Total in USD</span><span>$0</span></div>
                            <div class="total"><span>Total in Riel</span><span>Riel 0</span></div>
                        </div>

                        <button class="checkout-disabled" disabled>
                            Checkout
                        </button>
                    </div>
                </div>
            @else
                @php
                    $count = $cart->items->count();
                    $scrollClass = '';
                    if ($count > 10) {
                        $scrollClass = 'scroll-limit-10';
                    } elseif ($count > 5) {
                        $scrollClass = 'scroll-limit-5';
                    }
                @endphp

                <div class="cart-list-wrapper {{ $scrollClass }}">
                    @foreach ($cart->items as $cartItem)
                        <div class="item-card" data-cart-item-id="{{ $cartItem->id }}" data-qty="{{ $cartItem->qty }}">
                            <img src="{{ optional($cartItem->item)->image_url ?? asset('images/no-image.png') }}"
                                class="item-image">

                            <div class="item-details">
                                <h3>{{ $cartItem->item_name }} (L)</h3>
                                <div class="qty-controls">
                                    <button class="qty-btn qty-update" data-id="{{ $cartItem->id }}"
                                        data-action="minus">−</button>
                                    <span class="qty-val">{{ $cartItem->qty }}</span>
                                    <button class="qty-btn qty-update" data-id="{{ $cartItem->id }}"
                                        data-action="plus">+</button>
                                </div>
                            </div>

                            <i class="bi bi-x-circle remove-icon remove-item" data-id="{{ $cartItem->id }}"></i>
                        </div>
                    @endforeach
                </div>

                <div class="summary-box">
                    <div class="summary-line">
                        <span>Subtotal</span>
                        <span id="subtotalAmount">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="summary-line">
                        <span>Delivery</span>
                        <span id="deliveryAmount">$0.00</span>
                    </div>
                    <div class="summary-line">
                        <span>Estimated Tax <i class="bi bi-question-circle"></i></span>
                        <span id="taxAmount">${{ number_format($taxAmount ?? 0, 2) }}</span>
                    </div>

                    <div class="summary-line total-usd">
                        <span>Total in USD</span>
                        <span id="totalUsd">${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="summary-line total-riel">
                        <span>Total in Khmer Riel</span>
                        <span id="totalRiel">riel {{ number_format($total * 4100, 0) }}</span>
                    </div>
                </div>

                {{-- Desktop checkout --}}
                <button id="checkoutDesktopBtn" type="button" class="place-order-btn desktop-only">
                    PLACE ORDER
                </button>

                {{-- Mobile checkout --}}
                <button id="checkoutMobileBtn" type="button" class="place-order-btn mobile-only">
                    CHECK OUT
                </button>
            @endif
        </div>

        <div class="checkout-container" id="checkoutContent" style="display:none;">

            <div class="checkout-nav">
                <a href="javascript:void(0);" onclick="hideCheckout()" class="icon-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <span class="nav-title">Checkout</span>
            </div>

            <div class="checkout-section">
                <h4 class="section-title">Items</h4>

                @if ($cart && $cart->items->isNotEmpty())
                    @foreach ($cart->items as $cartItem)
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
                @else
                    <p class="text-muted">No items in the cart.</p>
                @endif
            </div>

            <div class="checkout-section payment-box">
                <h4 class="section-title">Payment</h4>

                <div class="payment-row"><span>Subtotal</span><span>${{ number_format($subtotal, 2) }}</span></div>
                <div class="payment-row"><span>Discount</span><span>$0</span></div>
                <div class="payment-row"><span>Delivery Fee</span><span>$0</span></div>
                <div class="payment-row"><span>Estimated Tax</span><span>${{ number_format($taxAmount, 2) }}</span></div>

                <div class="divider"></div>

                <div class="payment-row total"><span>Total in USD</span><span>${{ number_format($total, 2) }}</span></div>
                <div class="payment-row riel"><span>Total in Riel</span><span>riel {{ number_format($total * 4100, 0) }}</span>
                </div>
            </div>

            <div class="bottom-space"></div>

            <button id="placeOrderBtn" class="placeOrderBtn" type="button">
                Place Order
            </button>

        </div>

        <div class="order-success-wrapper hidden-success" id="successContent">

            {{-- Header --}}
            <div class="order-success-header">
                <span class="header-title">Order</span>
                <a href="{{ route('user.posinterface') }}" class="close-btn">
                    <i class="bi bi-x"></i>
                </a>
            </div>

            {{-- Illustration --}}
            <div class="success-illustration">
                <img src="{{ asset('images/pos/emptycart.png') }}" alt="Empty cart" class="empty-cart-illustration">
            </div>

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
                    <strong id="orderNumber">#{{ $orderNumber ?? 'JKL4522A' }}</strong>
                </div>

                <div class="detail-row">
                    <span>Amount paid</span>
                    <strong id="amountPaid">{{ $amountPaid ?? 'None' }}</strong>
                </div>
            </div>
            {{-- Action --}}
            <a id="orderDetailBtn" href="#" class="primary-btn">
                Check order
            </a>

        </div>

        @php $orderDetail = $orderDetail ?? null; @endphp
        <div id="orderDetailPage" class="order-detail-page hidden-order-detail mobile-only">
            <div class="order-header">
                <a href="{{ url()->previous() }}" class="back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h4>Order Detail</h4>
            </div>

            <div class="status-card">
                <div class="status-icon">📦</div>
                <div>
                    <strong>Processing order</strong>
                    <p>Orders will be received {{ optional($orderDetail ?? null)->checked_out_at ? optional($orderDetail ?? null)->checked_out_at->format('d F Y') : '-' }}</p>
                </div>
            </div>

            <div class="order-meta">
                <div>
                    <span>Invoice number</span>
                    <strong>#{{ optional($orderDetail ?? null)->order_no ?? 'N/A' }}</strong>
                </div>
                <div>
                    <span>Order date</span>
                    <strong>{{ optional($orderDetail ?? null)->created_at ? optional($orderDetail ?? null)->created_at->format('d F Y') : '-' }}</strong>
                </div>
            </div>

            @if(optional($orderDetail ?? null)->items)
                <h5 class="section-title">Purchased Item</h5>
                @foreach(optional($orderDetail ?? null)->items as $item)
                    <div class="item-card">
                        <img src="{{ optional($item->item)->image_url ? asset($item->item->image_url) : asset('images/pos/product-placeholder.png') }}" alt="{{ $item->item_name }}">
                        <div class="item-info">
                            <strong>{{ $item->item_name }}</strong>
                            <p>Variant: default</p>
                            <span>x{{ $item->qty }}</span>
                        </div>
                        <strong>${{ number_format($item->line_total, 0) }}</strong>
                    </div>
                @endforeach
            @endif

            <h5 class="section-title">Payment</h5>
            <div class="payment-row">
                <span>Subtotal</span>
                <span>${{ number_format(optional($orderDetail ?? null)->subtotal ?? 0, 2) }}</span>
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
                <span>${{ number_format(optional($orderDetail ?? null)->tax_amount ?? 0, 2) }}</span>
            </div>

            <div class="divider"></div>
            <div class="payment-row total">
                <span>Total in USD</span>
                <span>${{ number_format(optional($orderDetail ?? null)->amount_paid ?? 0, 2) }}</span>
            </div>
            <div class="payment-row">
                <span>Total in Riel</span>
                <span>Riel {{ number_format((optional($orderDetail ?? null)->amount_paid ?? 0) * 4100, 0) }}</span>
            </div>
        </div>

        <div id="processingScreen" class="process-screen hidden">
            <div class="process-color-overlay"></div>
            <img src="{{ asset('images/pos/checkout.png') }}" alt="process" class="process-image">
            <p class="process-text">Processing your order…</p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const cartMainContent = document.getElementById('cartMainContent');
        const checkoutContent = document.getElementById('checkoutContent');
        const successContent = document.getElementById('successContent');
        const orderDetailPage = document.getElementById('orderDetailPage');
        const cartListWrapper = document.querySelector('.cart-list-wrapper');
        const pendingQtyByItem = new Map();
        const syncingItems = new Set();
        const debounceTimerByItem = new Map();
        const rielRate = 4100;
        const showCheckout = {{ isset($showCheckout) && $showCheckout ? 'true' : 'false' }};
        const showOrderDetail = {{ isset($showOrderDetail) && $showOrderDetail ? 'true' : 'false' }};

        const formatUsd = (value) =>
            `$${Number(value || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        const formatRiel = (value) => `riel ${Math.round(Number(value || 0)).toLocaleString('en-US')}`;

        const updateSummary = (summary) => {
            const subtotalEl = document.getElementById('subtotalAmount');
            const taxEl = document.getElementById('taxAmount');
            const totalUsdEl = document.getElementById('totalUsd');
            const totalRielEl = document.getElementById('totalRiel');

            if (!subtotalEl || !taxEl || !totalUsdEl || !totalRielEl || !summary) return;

            subtotalEl.textContent = formatUsd(summary.subtotal);
            taxEl.textContent = formatUsd(summary.tax_amount);
            totalUsdEl.textContent = formatUsd(summary.total);
            totalRielEl.textContent = formatRiel(summary.total * rielRate);
        };

        const applyScrollClass = () => {
            if (!cartListWrapper) return;
            const itemCount = document.querySelectorAll('.item-card').length;
            cartListWrapper.classList.remove('scroll-limit-5', 'scroll-limit-10');
            if (itemCount > 10) cartListWrapper.classList.add('scroll-limit-10');
            else if (itemCount > 5) cartListWrapper.classList.add('scroll-limit-5');
        };

        const handleDesktopSuccessRedirect = () => {
            const hasMobileViewOpen = (successContent && !successContent.classList.contains('hidden-success')) ||
                (orderDetailPage && !orderDetailPage.classList.contains('hidden-order-detail'));

            if (window.innerWidth >= 768 && hasMobileViewOpen) {
                window.location.href = "{{ route('user.pos.cart') }}";
            }
        };

        window.addEventListener('resize', handleDesktopSuccessRedirect);
        handleDesktopSuccessRedirect();

        if (showOrderDetail && orderDetailPage) {
            if (window.innerWidth >= 768) {
                window.location.href = "{{ route('user.pos.cart') }}";
            } else {
                cartMainContent.style.display = 'none';
                if (checkoutContent) {
                    checkoutContent.style.display = 'none';
                }
                orderDetailPage.classList.remove('hidden-order-detail');
                orderDetailPage.style.display = 'block';
            }
        }

        const renderEmptyState = () => {
            cartMainContent.innerHTML = `
            <div class="empty-state">
                <img src="{{ asset('images/pos/Empty.png') }}" class="empty-state-image">
                <h3 style="color: #ccc;">Your cart is Empty</h3>
                <a href="/pos-system" class="empty-state-link">Continue Shopping</a>
            </div>
        `;
        };

        const refreshCartSummary = async () => {
            const res = await fetch('/pos-system/cart/data', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            if (!res.ok) return;
            const data = await res.json();
            if (!data.success) return;
            updateSummary(data);
        };

        const setItemButtonsDisabled = (row, disabled) => {
            row.querySelectorAll('.qty-update').forEach(btn => {
                btn.disabled = disabled;
            });
        };

        const syncQty = async (id, row) => {
            if (syncingItems.has(id)) return;
            syncingItems.add(id);
            setItemButtonsDisabled(row, true);

            try {
                const qty = pendingQtyByItem.get(id);
                const res = await fetch(`/pos-system/cart/update/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        qty
                    })
                });

                if (!res.ok) {
                    throw new Error('Update failed');
                }

                await refreshCartSummary();

                const latestQty = parseInt(row.dataset.qty, 10);
                if (pendingQtyByItem.get(id) !== latestQty) {
                    syncingItems.delete(id);
                    setItemButtonsDisabled(row, false);
                    return syncQty(id, row);
                }
            } catch (error) {
                alert('Failed to update quantity. Please try again.');
            } finally {
                syncingItems.delete(id);
                setItemButtonsDisabled(row, false);
            }
        };

        // Update Quantity
        document.querySelectorAll('.qty-update').forEach(btn => {
            btn.onclick = async function() {
                const id = this.dataset.id;
                const row = this.closest('.item-card');
                const qtyLabel = row.querySelector('.qty-val');
                const currentQty = parseInt(qtyLabel.innerText, 10);
                const newQty = this.dataset.action === 'plus' ? currentQty + 1 : currentQty - 1;

                if (newQty < 1) return;

                qtyLabel.innerText = newQty;
                row.dataset.qty = newQty;
                pendingQtyByItem.set(id, newQty);

                const oldTimer = debounceTimerByItem.get(id);
                if (oldTimer) clearTimeout(oldTimer);

                const timer = setTimeout(() => {
                    syncQty(id, row);
                }, 180);
                debounceTimerByItem.set(id, timer);
            }
        });

        // Remove Item
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.onclick = async function() {
                if (!confirm('Remove this item?')) return;
                const row = this.closest('.item-card');
                const res = await fetch(`/pos-system/cart/remove/${this.dataset.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (!res.ok) {
                    alert('Failed to remove item. Please try again.');
                    return;
                }

                row.remove();
                applyScrollClass();
                await refreshCartSummary();

                if (document.querySelectorAll('.item-card').length === 0) {
                    renderEmptyState();
                }
            }
        });
    </script>
    <script>
        /* ✅ DESKTOP */
        const checkoutDesktopBtn = document.getElementById('checkoutDesktopBtn');
        if (checkoutDesktopBtn) {
            checkoutDesktopBtn.onclick = async function() {
                try {
                    checkoutDesktopBtn.disabled = true;

                    const res = await fetch('/pos-system/checkout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken // ✅ already defined
                        },
                        body: JSON.stringify({
                            currency: 'USD',
                            factor: 1
                        })
                    });

                    const data = await res.json();

                    if (data.success) {
                        // For desktop, redirect back to cart page
                        window.location.href = "{{ route('user.pos.cart') }}";
                    } else {
                        alert('Checkout failed');
                    }
                } catch (e) {
                    alert('Error');
                } finally {
                    checkoutDesktopBtn.disabled = false;
                }
            };
        }

        /* ✅ MOBILE */
        const checkoutMobileBtn = document.getElementById('checkoutMobileBtn');
        if (checkoutMobileBtn) {
            checkoutMobileBtn.onclick = function() {
                if (checkoutContent) {
                    cartMainContent.style.display = 'none';
                    checkoutContent.style.display = 'block';
                } else {
                    window.location.href = '/pos-system/checkout';
                }
            };
        }

        if (showCheckout && checkoutContent) {
            cartMainContent.style.display = 'none';
            checkoutContent.style.display = 'block';
        }

        const orderDetailBtn = document.getElementById('orderDetailBtn');
        if (orderDetailBtn) {
            orderDetailBtn.addEventListener('click', function(event) {
                if (window.innerWidth >= 768) {
                    event.preventDefault();
                    window.location.href = "{{ route('user.pos.cart') }}";
                }
            });
        }

        /* CHECKOUT PROCESS */
        const placeOrderBtn = document.getElementById('placeOrderBtn');
        if (placeOrderBtn) {
            placeOrderBtn.addEventListener('click', async () => {
                placeOrderBtn.disabled = true;
                if (checkoutContent) {
                    checkoutContent.style.display = 'none';
                }
                const processing = document.getElementById('processingScreen');
                if (processing) {
                    processing.classList.remove('hidden');
                }

                try {
                    const res = await fetch('/pos-system/checkout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            currency_code: 'USD',
                            currency_factor: 1
                        })
                    });

                    const data = await res.json();

                    if (!data.success) {
                        throw new Error(data.message || 'Checkout failed');
                    }

                    setTimeout(() => {
                        if (processing) {
                            processing.classList.add('pull-down');
                        }
                    }, 300);

                    setTimeout(() => {
                        if (processing) {
                            processing.style.display = 'none';
                        }
                        if (checkoutContent) {
                            checkoutContent.style.display = 'none';
                        }
                        if (window.innerWidth >= 768) {
                            window.location.href = "{{ route('user.pos.cart') }}";
                            return;
                        }
                        const successContent = document.getElementById('successContent');
                        if (successContent) {
                            successContent.classList.remove('hidden-success');
                            successContent.style.display = 'block';
                        }
                        document.getElementById('orderNumber').innerText = `#${data.order_no}`;
                        document.getElementById('amountPaid').innerText = `$${data.total}`;
                        document.getElementById('orderDetailBtn').href = `/pos-system/order-detail/${data.order_id}`;
                    }, 1200);
                } catch (error) {
                    alert('Order failed. Please try again.');
                    window.location.href = '/pos-system/cart';
                } finally {
                    placeOrderBtn.disabled = false;
                }
            });
        }
    </script>
@endpush

