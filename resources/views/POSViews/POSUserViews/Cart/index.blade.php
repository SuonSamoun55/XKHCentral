@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'POS Cart')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pos/cart.css') }}">
@endpush


@section('content')
    <div class="page-wrap">
        <div class="cart-container">

            <!-- =========================
                                        TOP NAVIGATION (CART HEADER)
                                    ========================== -->
            <div class="cart-nav">
                <a href="/pos-system" class="icon-btn"><i class="bi bi-arrow-left"></i></a>
                <span class="nav-title">My Cart</span>
            </div>


            <div id="cartMainContent">
                <div class="cart-nav_mobile">
                    <a href="/pos-system" class="icon-btn_mobile"><i class="bi bi-arrow-left"></i></a>
                    <span class="nav-title"><b>Your Cart</b></span>
                    <i class="bi bi-heart-fill heart-icon"></i>
                </div>

                <!-- =========================
                                            SCREEN 1: EMPTY CART STATE (DESKTOP + MOBILE)
                    ========================== -->
                @if (!$cart || $cart->items->isEmpty())

                    <!-- ===== DESKTOP EMPTY CART ===== -->
                    <div class="empty-state desktop-only">
                        <img src="{{ asset('images/pos/Empty.png') }}" class="empty-state-image">
                        <h3 style="color: #ccc;">Your cart is Empty</h3>
                        <p class="empty-description">Looks like you haven’t <br> added anything to your cart yet</p>
                        <button class="shopingBtn">
                            <a href="/pos-system" class="empty-state-link">Continue Shopping</a>
                        </button>
                    </div>

                    <!-- ===== MOBILE EMPTY CART ===== -->
                    <div class="empty-cart-mobile mobile-only">
                        <div class="empty-cart-content">
                            <div class="item-count">0 items</div>

                            <img src="{{ asset('images/pos/image_16.png') }}" alt="Empty cart"
                                class="empty-cart-illustration">

                            <h3 class="empty-title">Your cart is empty</h3>

                            <p class="empty-desc">
                                Looks like you haven’t added anything<br>
                                to your cart yet
                            </p>

                            <a href="{{ route('user.posinterface') }}" class="shop-now-btn">
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
                    <!-- =========================
                                                SCREEN 2: CART WITH ITEMS DESKTOP + MOBILE
                                            ========================== -->

                    @php
                        $count = $cart->items->count();
                        $scrollClass = '';
                        if ($count > 10) {
                            $scrollClass = 'scroll-limit-10';
                        } elseif ($count > 5) {
                            $scrollClass = 'scroll-limit-5';
                        }
                    @endphp

                    <!-- CART ITEM LIST -->
                    <div class="cart-list {{ $scrollClass }}">
                        @foreach ($cart->items as $cartItem)
                            <!-- SINGLE CART ITEM -->
                            <div class="item-card" data-cart-item-id="{{ $cartItem->id }}" data-qty="{{ $cartItem->qty }}">

                                <img src="{{ optional($cartItem->item)->image_url ?? asset('images/no-image.png') }}"
                                    class="item-image">

                                <div class="item-details">
                                    <div class="cart">
                                        <div class="cart-name">{{ $cartItem->item_name }} (L)</div>
                                        <div class="cart-price"><b>${{ number_format($cartItem->unit_price, 2) }}</b></div>
                                    </div>

                                    <div class="qty-controls">
                                        <button class="qty-btn qty-update" data-id="{{ $cartItem->id }}"
                                            data-action="minus">−</button>

                                        <span class="qty-val">{{ $cartItem->qty }}</span>

                                        <button class="qty-btn qty-update" data-id="{{ $cartItem->id }}"
                                            data-action="plus">+</button>
                                    </div>
                                    <p class="remove-item" data-id="{{ $cartItem->id }}">Remove</p>
                                </div>
                                {{-- <i class="bi bi-x-circle remove-icon remove-item" data-id="{{ $cartItem->id }}"></i> --}}
                            </div>
                        @endforeach
                    </div>

                    <!-- CART SUMMARY BOX -->

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

                    <!---------------------------- DESKTOP CHECKOUT BUTTON -->
                    <button id="checkoutDesktopBtn" type="button" class="place-order-btn desktop-only">
                        PLACE ORDER >
                    </button>
                    <p class="or-text desktop">Or <b>Continue Shopping ➡️</b></p>

                    <!-- MOBILE CHECKOUT BUTTON -->
                    <button id="checkoutMobileBtn" type="button" class="place-order-btn mobile-only">
                        CHECK OUT
                    </button>

                @endif
            </div>

            <!-- =========================order success======================== -->

            <div id="orderSuccessContent" class="success-container" style="display: none;">

                <div class="success-card">

                    <!-- ICON -->
                    <div class="empty-cart">
                        <img src="{{ asset('images/pos/Group.png') }}" alt="success image">
                    </div>

                    <!-- TITLE -->
                    <h2 class="success-title">
                        Your Order is Confirmed !
                    </h2>

                    <!-- DESCRIPTION -->
                    <p class="success-text">
                        Your order is being packed and will arrive soon.<br>
                        Fruits and veggies coming right up!
                    </p>

                    <!-- BUTTON -->
                    <a href="{{ route('user.pos.order.history') }}" class="btn-track">
                        Track Order
                    </a>

                    <!-- LINK -->
                    <a href="/pos-system" class="btn-home">
                        Back to home
                    </a>

                </div>
            </div>

            <!-- =========================
                                        SCREEN 3: CHECKOUT PAGE MOBILE SCREEN ONLY
                                    ========================== -->
            <div class="checkout-container" id="checkoutContent" style="display:none;">

                <div class="cart-nav_mobile">
                    <a href="/pos-system" class="icon-btn_mobile"><i class="bi bi-arrow-left"></i></a>
                    <span class="nav-title"><b> Checkout</b></span>
                </div>

                <!-- ITEMS IN CHECKOUT -->
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

                                    <div class="item-meta">Variant: {{ $cartItem->variant ?? 'default' }}</div>
                                    <div class="item-meta">x{{ $cartItem->qty }}</div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No items in the cart.</p>
                    @endif
                </div>

                <!-- PAYMENT SUMMARY -->
                <div class="checkout-section payment-box">
                    <h4 class="section-title">Payment</h4>

                    <div class="payment-row"><span>Subtotal</span><span>${{ number_format($subtotal, 2) }}</span></div>
                    <div class="payment-row"><span>Discount</span><span>$0</span></div>
                    <div class="payment-row"><span>Delivery Fee</span><span>$0</span></div>
                    <div class="payment-row"><span>Estimated Tax</span><span>${{ number_format($taxAmount, 2) }}</span>
                    </div>

                    <div class="divider"></div>

                    <div class="payment-row total">
                        <span>Total in USD</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>

                    <div class="payment-row riel">
                        <span>Total in Riel</span>
                        <span>riel {{ number_format($total * 4100, 0) }}</span>
                    </div>
                </div>

                <div class="bottom-space"></div>

                <button id="placeOrderBtn" class="placeOrderBtn" type="button">
                    Place Order
                </button>
            </div>

            <!-- =========================
                                        SCREEN 4: ORDER SUCCESS MOBILE SCREEN
                                    ========================== -->
            <div class="order-success-wrapper hidden-success" id="successContent">

                <div class="order-success-header">
                    <span class="header-title"><b>Order Success</b></span>
                    <a href="{{ route('user.posinterface') }}" class="close-btn">
                        <i class="bi bi-x"></i>
                    </a>
                </div>

                <div class="success-illustration">
                    <img src="{{ asset('images/pos/emptycart.png') }}" class="empty-cart-illustration">
                </div>

                <h2 class="success-title">Your order has been placed!</h2>

                <p class="success-desc">
                    The order will be forwarded to the seller.<br>
                    Please check status of your order in the order list.
                </p>

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

                <a id="orderDetailBtn" href="#" class="primary-btn">
                    Check order
                </a>
            </div>

            <!-- =========================
                                        SCREEN 5: ORDER DETAIL (MOBILE)
                                    ========================== -->
            <div id="orderDetailPage" class="order-detail-page hidden-order-detail mobile-only">
                <div class="cart-nav_mobile">
                    <a href="{{ url()->previous() }}" class="icon-btn_mobile">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <span class="nav-title"><b> Order Detail</b></span>
                </div>

                <div class="status-card">
                    <div class="status-icon">📦</div>
                    <div>
                        <strong>Processing order</strong>
                        <p>Orders will be received
                            {{ optional($orderDetail ?? null)->checked_out_at ? optional($orderDetail ?? null)->checked_out_at->format('d F Y') : '-' }}
                        </p>
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

                @if (optional($orderDetail ?? null)->items)
                    <h5 class="section-title">Purchased Item</h5>
                    @foreach (optional($orderDetail ?? null)->items as $item)
                        <div class="item-card">
                            <img src="{{ optional($item->item)->image_url ? asset($item->item->image_url) : asset('images/pos/product-placeholder.png') }}"
                                alt="{{ $item->item_name }}">
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


            <!-- =========================
                                        SCREEN 6: PROCESSING OVERLAY MOBILE
                                    ========================== -->
            <div id="processingScreen" class="process-screen hidden">
                <div class="process-color-overlay"></div>
                <img src="{{ asset('images/pos/checkout.png') }}" class="process-image">
                <p class="process-text">Processing your order…</p>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const handleDesktopScreenRedirect = () => {

            // MOBILE CHECKOUT SCREEN
            const isCheckoutOpen =
                checkoutContent &&
                checkoutContent.style.display === 'block';

            // MOBILE SUCCESS SCREEN
            const isSuccessOpen =
                successContent &&
                !successContent.classList.contains('hidden-success');

            // MOBILE ORDER DETAIL SCREEN
            const isOrderDetailOpen =
                orderDetailPage &&
                !orderDetailPage.classList.contains('hidden-order-detail');

            // IF DESKTOP SCREEN
            if (
                window.innerWidth >= 768 &&
                (isCheckoutOpen || isSuccessOpen || isOrderDetailOpen)
            ) {
                window.location.href = "{{ route('user.pos.cart') }}";
            }
        };

        // RUN WHEN RESIZE
        window.addEventListener('resize', handleDesktopScreenRedirect);

        // RUN ON PAGE LOAD
        handleDesktopScreenRedirect();
    </script>

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

                        // ✅ Hide cart
                        cartMainContent.style.display = 'none';

                        // ✅ Show success
                        const successBlock = document.getElementById('orderSuccessContent');
                        if (successBlock) {
                            successBlock.style.display = 'block';
                        }

                        // ✅ Hide button
                        checkoutDesktopBtn.style.display = 'none';

                        // ✅ Scroll to top (VERY IMPORTANT)
                        window.scrollTo(0, 0);

                        // ✅ Redirect after 20 sec
                        setTimeout(() => {
                            window.location.href = "{{ route('user.pos.cart') }}";
                        }, 20000);
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
                        document.getElementById('orderDetailBtn').href =
                            `/pos-system/order-detail/${data.order_id}`;
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
