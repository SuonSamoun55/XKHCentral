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
.shopingBtn{
   
    padding: 12px;
    border-radius: 12px;
    background: #2dd4bf;
    color: #ffffff;
    font-weight: 600;
    font-size: 15px;
    border: none;
}
.bi-arrow-left{
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
                position: sticky;
                top: 0;
                z-index: 50;
                background: #fff;
                padding: 8px;
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
    width: calc(100% + 32px);
    
 margin-left: -16px;
    margin-right: -16px;

    background: #1F7A85;
    color: white;
    padding: 16px;
  border-radius: 0;  
      font-size: 13px;
    text-align: left;
    position: relative;
    top: 38px;
}

.empty-summary-card > div {
    display: flex;
    justify-content: space-between;
    margin: 6px 0;
}

.empty-summary-card hr {
    border: none;
    height: 1px;
    background: rgba(255,255,255,0.3);
    margin: 12px 0;
}

.empty-summary-card .total {
    font-weight: 600;
}

/* Disabled checkout */
.checkout-disabled {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: #2EC4B6;
    color: rgba(255,255,255,0.6);
    padding: 22px;
    font-size: 16px;
    font-weight: 600;
    border: none;
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

    <div class="item-count">0 items</div>

    <img
        src="{{ asset('images/pos/emptycart.png') }}"
        alt="Empty cart"
        class="empty-cart-illustration"
    >

    <h3 class="empty-title">Your cart is empty</h3>

    <p class="empty-desc">
        Looks like you haven’t added anything<br>
        to your cart yet
    </p>

    <a href="/pos-system" class="shop-now-btn">
        Shop now <i class="bi bi-chevron-right"></i>
    </a>

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

        <div id="orderSuccessContent" style="display: none;">
            <div class="success-icon-circle">
                <i class="bi bi-check-lg"></i>
            </div>
            <h2 style="color: #4DB37E;">Confirmed!</h2>
            <p class="success-desc">Your order is being prepared.</p>
            <a href="/pos-system" class="place-order-btn back-home-btn">Back Home</a>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const cartMainContent = document.getElementById('cartMainContent');
        const cartListWrapper = document.querySelector('.cart-list-wrapper');
        const pendingQtyByItem = new Map();
        const syncingItems = new Set();
        const debounceTimerByItem = new Map();
        const rielRate = 4100;

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
                        document.getElementById('cartMainContent').style.display = 'none';
                        document.getElementById('orderSuccessContent').style.display = 'block';
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
                window.location.href = '/pos-system/checkout';
            };
        }
    </script>
@endpush
