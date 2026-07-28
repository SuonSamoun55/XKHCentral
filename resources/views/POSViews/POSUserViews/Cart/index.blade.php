@extends('ManagementSystemViews.UserViews.Layouts.app')

    @section('title', 'POS Cart')

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/views/POSViews/POSUserViews/ItemCart/cart.css') }}">
    @endpush

    @section('content')
        <div class="page-wrap">
            <div class="cart-container">
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

                    @if (!$cart || $cart->items->isEmpty())
                        <div class="empty-state desktop-only">
                            <img src="{{ asset('images/pos/Empty.png') }}" class="empty-state-image">
                            <h3 style="color: #ccc;">Your cart is Empty</h3>
                            <p class="empty-description">Looks like you haven't <br> added anything to your cart yet</p>
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
                                    Looks like you haven't added anything<br>
                                    to your cart yet
                                </p>

                                <a href="{{ route('user.posinterface') }}" class="shop-now-btn">
                                    Shop now <i class="bi bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- CART ITEM LIST -->
                        <div class="cart-list-wrapper">
                            <div class="cart-list">
                                @foreach ($cart->items as $cartItem)
                                    @php
                                        $originalUnitPrice = $cartItem->unit_price;

                                        // Discount % lives on Item::active_discount_percent
                                        // (handles the start/end date range check internally).
                                        $discountPercent = optional($cartItem->item)->active_discount_percent ?? 0;
                                        $unitDiscount = round($originalUnitPrice * ($discountPercent / 100), 2);
                                        $finalUnitPrice = max($originalUnitPrice - $unitDiscount, 0);

                                        $itemVatPercent = (!empty(optional($cartItem->item)->price_includes_tax))
                                            ? 0
                                            : max(0, (float) (optional($cartItem->item)->vat_percent ?? 0));
                                        $lineTotal = $finalUnitPrice * $cartItem->qty;
                                        $lineVat = round($lineTotal * ($itemVatPercent / 100), 2);
                                        $resolveImg = fn ($path) => $path
                                            ? (str_starts_with($path, 'http') ? $path : asset($path))
                                            : null;

                                        $cartItemImage = $resolveImg(optional($cartItem->itemVariant)->image_url)
                                            ?? $resolveImg(optional($cartItem->item)->custom_image_url)
                                            ?? $resolveImg(optional($cartItem->item)->image_url)
                                            ?? asset('images/no-image.png');
                                    @endphp

                                    <!-- SINGLE CART ITEM -->
                                    <div class="item-card" data-cart-item-id="{{ $cartItem->id }}" data-qty="{{ $cartItem->qty }}">

                                        <img src="{{ $cartItemImage }}"
                                            class="item-image"
                                            onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';">

                                        <!-- Group 1: name / variant / vat -->
                                        <div class="item-info">
                                            <div class="cart-name">{{ $cartItem->item_name }}</div>
                                            <div class="cart-variant">Variant: {{ optional($cartItem->itemVariant)->code ?? 'Default' }}</div>
                                            <div class="cart-vat-cell">
                                                <span class="cart-vat-chip cart-variant ">VAT: {{ $itemVatPercent }}%: ${{ number_format($lineVat, 2) }}</span>
                                            </div>
                                            <span class="cart-discount-chip cart-variant">Discount: -{{ $discountPercent }}% off</span>
                                        </div>
                                        <!-- Group 3: qty controls / remove -->
                                        <div class="item-qty-block">
                                            <div class="qty-controls">
                                                <button class="qty-btn qty-update" data-id="{{ $cartItem->id }}"
                                                    data-action="minus">−</button>

                                                <input type="number" class="qty-val" data-id="{{ $cartItem->id }}"
                                                    value="{{ $cartItem->qty }}" min="1" inputmode="numeric">

                                                <button class="qty-btn qty-update" data-id="{{ $cartItem->id }}"
                                                    data-action="plus">+</button>
                                            </div>

                                            <p class="remove-item" data-id="{{ $cartItem->id }}">Remove</p>
                                        </div>

                                        <!-- Group 4: subtotal -->
                                        <div class="cart-subtotal">${{ number_format($lineTotal, 2) }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- CART SUMMARY BOX -->

                        <div class="summary-box">
                            <div class="summary-line">
                                <span>Subtotal</span>
                                <span id="subtotalAmount">${{ number_format($subtotal ?? 0, 2) }}</span>
                            </div>

                            <div class="summary-line discount-line">
                                <span>Discount</span>
                                <span id="discountAmount">-${{ number_format($discount ?? 0, 2) }}</span>
                            </div>

                            <div class="summary-line">
                                <span>Delivery</span>
                                <span id="deliveryAmount">$0.00</span>
                            </div>

                            <div class="summary-line">
                                <span>VAT <i class="bi bi-question-circle"></i></span>
                                <span id="taxAmount">${{ number_format($taxAmount ?? 0, 2) }}</span>
                            </div>

                            <div class="summary-line total-usd">
                                <span>Order total in USD</span>
                                <span id="totalUsd">${{ number_format($total ?? 0, 2) }}</span>
                            </div>

                            <div class="summary-line total-riel">
                                <span>Order total in Khmer Riel</span>
                                <span id="totalRiel">Riel {{ number_format(($total ?? 0) * 4100, 0) }}</span>
                            </div>
                        </div>

                        <!---------------------------- DESKTOP CHECKOUT BUTTON -->
                        <button id="checkoutDesktopBtn" type="button" class="place-order-btn desktop-only">
                            PLACE ORDER <i class="bi bi-chevron-right"></i>
                        </button>
                        <p class="or-text desktop">or <a href="/pos-system" class="continue-link">Continue Shopping <i class="bi bi-arrow-right"></i></a></p>

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
                        <button type="button" id="checkoutBackBtn" class="icon-btn_mobile"><i class="bi bi-arrow-left"></i></button>
                        <span class="nav-title"><b> Checkout</b></span>
                    </div>

                    <!-- ITEMS IN CHECKOUT (scrollable) -->
                    <div class="checkout-items-wrapper">
                        <div class="checkout-section">
                            <h4 class="section-title">Items</h4>

                            @if ($cart && $cart->items->isNotEmpty())
                                @foreach ($cart->items as $cartItem)
                                    @php
                                        $coUnitPrice = $cartItem->unit_price;

                                        // Discount % lives on Item::active_discount_percent
                                        // (handles the start/end date range check internally).
                                        $coDiscountPercent = optional($cartItem->item)->active_discount_percent ?? 0;
                                        $coUnitDiscount = round($coUnitPrice * ($coDiscountPercent / 100), 2);
                                        $coFinalUnitPrice = max($coUnitPrice - $coUnitDiscount, 0);

                                        $coVatPercent = (!empty(optional($cartItem->item)->price_includes_tax))
                                            ? 0
                                            : max(0, (float) (optional($cartItem->item)->vat_percent ?? 0));
                                        $coLineTotal = $coFinalUnitPrice * $cartItem->qty;
                                        $coLineVat = round($coLineTotal * ($coVatPercent / 100), 2);

                                        // Priority: selected variant's own image, then the
                                        // item's admin-set custom_image_url override, then the
                                        // item's synced image_url, then placeholder.
                                        // image_url may be stored either as a full URL or
                                        // a relative storage path, so resolve either shape.
                                        $coResolveImg = fn ($path) => $path
                                            ? (str_starts_with($path, 'http') ? $path : asset($path))
                                            : null;

                                        $coItemImage = $coResolveImg(optional($cartItem->itemVariant)->image_url)
                                            ?? $coResolveImg(optional($cartItem->item)->custom_image_url)
                                            ?? $coResolveImg(optional($cartItem->item)->image_url)
                                            ?? asset('images/no-image.png');
                                    @endphp
                                    <div class="checkout-item-card">
                                        <img src="{{ $coItemImage }}"
                                            onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';">

                                        <div class="item-content">
                                            <div class="item-top">
                                                <strong>{{ $cartItem->item_name }}</strong>
                                                <span class="item-price">
                                                    ${{ number_format($coLineTotal, 2) }}
                                                </span>
                                            </div>
                                            <div class="cart-variant">{{ optional($cartItem->itemVariant)->code ?? 'Default' }}</div>
                                            <div class="item-meta">x{{ $cartItem->qty }}</div>

                                            <div class="item-meta-row">
                                                @if ($coUnitDiscount > 0)
                                                    <span class="cart-discount-chip">-{{ $coDiscountPercent }}% off</span>
                                                @endif
                                                <span class="cart-vat-chip">VAT {{ $coVatPercent }}%: ${{ number_format($coLineVat, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">No items in the cart.</p>
                            @endif
                        </div>
                    </div>

                    <!-- STICKY BOTTOM: PAYMENT + PLACE ORDER -->                    <div class="checkout-bottom">
                        <!-- PAYMENT SUMMARY -->
                        <div class="checkout-section payment-box">
                            <h4 class="section-title">Payment</h4>

                            <div class="payment-row"><span>Subtotal</span><span>${{ number_format($subtotal ?? 0, 2) }}</span></div>
                            <div class="payment-row discount"><span>Discount</span><span>-${{ number_format($discount ?? 0, 2) }}</span></div>
                            <div class="payment-row"><span>Delivery Fee</span><span>$0</span></div>
                            <div class="payment-row"><span>Estimated Tax</span><span>${{ number_format($taxAmount ?? 0, 2) }}</span>
                            </div>

                            <div class="divider"></div>

                            <div class="payment-row total">
                                <span>Total in USD</span>
                                <span>${{ number_format($total ?? 0, 2) }}</span>
                            </div>

                            <div class="payment-row riel">
                                <span>Total in Riel</span>
                                <span>riel {{ number_format(($total ?? 0) * 4100, 0) }}</span>
                            </div>
                        </div>

                        <button id="placeOrderBtn" class="placeOrderBtn" type="button">
                            Place Order
                        </button>
                    </div>
                </div>
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
                            @php
                                $odUnitPrice = $item->unit_price ?? ($item->qty > 0 ? $item->line_total / $item->qty : 0);
                                $odVatPercent = (!empty(optional($item->item)->price_includes_tax))
                                    ? 0
                                    : max(0, (float) (optional($item->item)->vat_percent ?? 0));
                                $odLineVat = round(($item->line_total ?? 0) * ($odVatPercent / 100), 2);
                                $odResolveImg = fn ($path) => $path
                                    ? (str_starts_with($path, 'http') ? $path : asset($path))
                                    : null;

                                $odImage = $odResolveImg(optional($item->itemVariant)->image_url)
                                    ?? $odResolveImg(optional($item->item)->custom_image_url)
                                    ?? $odResolveImg(optional($item->item)->image_url)
                                    ?? asset('images/pos/product-placeholder.png');
                            @endphp
                            <div class="item-card">
                                <img src="{{ $odImage }}"
                                    alt="{{ $item->item_name }}"
                                    onerror="this.onerror=null;this.src='{{ asset('images/pos/product-placeholder.png') }}';">
                                <div class="item-info">
                                    <strong>{{ $item->item_name }}</strong>
                                    <p>Variant: {{ $item->variant ?? 'Default' }}</p>
                                    <span>x{{ $item->qty }}</span>
                                    <div class="item-meta-row">
                                        <span class="cart-vat-chip">VAT {{ $odVatPercent }}%: ${{ number_format($odLineVat, 2) }}</span>
                                    </div>
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
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const cartMainContent = document.getElementById('cartMainContent');
            const checkoutContent = document.getElementById('checkoutContent');
            const successContent = document.getElementById('successContent');
            const orderDetailPage = document.getElementById('orderDetailPage');
            const pendingQtyByItem = new Map();
            const syncingItems = new Set();
            const debounceTimerByItem = new Map();
            const rielRate = 4100;
            const showCheckout = {{ isset($showCheckout) && $showCheckout ? 'true' : 'false' }};
            const showOrderDetail = {{ isset($showOrderDetail) && $showOrderDetail ? 'true' : 'false' }};
            const CHECKOUT_STEP_KEY = 'posCheckoutStep';

            const goToCheckoutStep = () => {
                cartMainContent.style.display = 'none';
                checkoutContent.style.display = 'flex';
                sessionStorage.setItem(CHECKOUT_STEP_KEY, '1');
            };

            const goToCartStep = () => {
                checkoutContent.style.display = 'none';
                cartMainContent.style.display = 'flex';
                sessionStorage.removeItem(CHECKOUT_STEP_KEY);
            };
            const handleDesktopScreenRedirect = () => {
                if (window.innerWidth < 768) return;
                const isCheckoutOpen =
                    checkoutContent &&
                    checkoutContent.style.display !== 'none' &&
                    checkoutContent.style.display !== '';
                if (isCheckoutOpen) {
                    goToCartStep();
                }
                const isSuccessOpen =
                    successContent &&
                    !successContent.classList.contains('hidden-success');
                const isOrderDetailOpen =
                    orderDetailPage &&
                    !orderDetailPage.classList.contains('hidden-order-detail');

                if (isSuccessOpen || isOrderDetailOpen) {
                    window.location.href = "{{ route('user.pos.cart') }}";
                }
            };

            window.addEventListener('resize', handleDesktopScreenRedirect);
            handleDesktopScreenRedirect();

            const formatUsd = (value) =>
                `$${Number(value || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            const formatRiel = (value) => `Riel ${Math.round(Number(value || 0)).toLocaleString('en-US')}`;

            const updateSummary = (summary) => {
                const subtotalEl = document.getElementById('subtotalAmount');
                const discountEl = document.getElementById('discountAmount');
                const taxEl = document.getElementById('taxAmount');
                const totalUsdEl = document.getElementById('totalUsd');
                const totalRielEl = document.getElementById('totalRiel');

                if (!subtotalEl || !taxEl || !totalUsdEl || !totalRielEl || !summary) return;

                subtotalEl.textContent = formatUsd(summary.subtotal);
                if (discountEl) {
                    discountEl.textContent = `-${formatUsd(summary.discount || 0)}`;
                }
                taxEl.textContent = formatUsd(summary.tax_amount);
                totalUsdEl.textContent = formatUsd(summary.total);
                totalRielEl.textContent = formatRiel(summary.total * rielRate);
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

            // Update Quantity (+ / − buttons)
            document.querySelectorAll('.qty-update').forEach(btn => {
                btn.onclick = async function() {
                    const id = this.dataset.id;
                    const row = this.closest('.item-card');
                    const qtyInput = row.querySelector('.qty-val');
                    const currentQty = parseInt(qtyInput.value, 10) || 1;
                    const newQty = this.dataset.action === 'plus' ? currentQty + 1 : currentQty - 1;

                    if (newQty < 1) return;

                    qtyInput.value = newQty;
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

            // Update Quantity (typed directly into the input)
            document.querySelectorAll('.qty-val').forEach(input => {
                input.addEventListener('change', function() {
                    const id = this.dataset.id;
                    const row = this.closest('.item-card');
                    let newQty = parseInt(this.value, 10);

                    if (isNaN(newQty) || newQty < 1) {
                        newQty = 1;
                    }

                    this.value = newQty;
                    row.dataset.qty = newQty;
                    pendingQtyByItem.set(id, newQty);

                    const oldTimer = debounceTimerByItem.get(id);
                    if (oldTimer) clearTimeout(oldTimer);

                    syncQty(id, row);
                });
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
                            checkoutDesktopBtn.style.display = 'none';
                            window.scrollTo(0, 0);
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
                        goToCheckoutStep();
                    } else {
                        window.location.href = '/pos-system/checkout';
                    }
                };
            }

            // Back button on the checkout header — goes back to the cart step,
            // not out to the product page.
            const checkoutBackBtn = document.getElementById('checkoutBackBtn');
            if (checkoutBackBtn) {
                checkoutBackBtn.onclick = goToCartStep;
            }

            if (checkoutContent && (showCheckout || sessionStorage.getItem(CHECKOUT_STEP_KEY) === '1')) {
                goToCheckoutStep();
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
                        sessionStorage.removeItem(CHECKOUT_STEP_KEY);

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
