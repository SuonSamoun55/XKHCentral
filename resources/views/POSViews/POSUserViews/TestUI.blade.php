@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'POS Cart')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/POSsystem/cart.css') }}">
    <style>
        .icon-wrapper {
            position: relative;
            width: 140px;
            height: 140px;
            margin: 0 auto 30px;
        }

        .check-circle {
            background: #00cad1;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 70px;
        }

        .text-confirmed {
            color: #4DB37E;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .text-main, .text-sub {
            color: #555;
            font-size: 16px;
            margin: 5px 0;
        }

        .btn-track {
            background: #00cad1;
            color: white !important;
            padding: 14px 80px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            width: 250px;
        }

        .btn-home {
            color: #00cad1;
            text-decoration: none;
            font-weight: bold;
            margin-top: 15px;
        }

        .dot { position: absolute; width: 8px; height: 8px; border-radius: 50%; }
        .d1 { background: #FFD700; top: 10px; left: 0; }
        .d2 { background: #FF69B4; bottom: 20px; right: -10px; }
        .d3 { background: #7B68EE; top: 40px; right: 0; }
    </style>
@endpush

@section('content')
    <div class="page-wrap">
        <main class="content-area">
            <div id="cartMainContent">
                <div class="cart-header">
                    @include('ManagementSystemViews.UserViews.Layouts.header', ['title' => 'Cart'])
                </div>

                @if (!$cart || $cart->items->isEmpty())
                    <div class="empty-cart">
                        <div class="empty-cart">
                            <img src="{{ asset('images/pos/Empty.png') }}" class="empty-image">
                        </div>
                        <div class="empty-title">Your cart is Empty</div>
                        <div class="empty-text">Add something to make me happy..!!</div>
                        <a href="/pos-system" class="continue-btn">Continue Shopping</a>
                    </div>
                @else
                    <div class="cart-list">
                        @foreach ($cart->items as $cartItem)
                            <div class="cart-row">
                                <div class="cart-image">
                                    <img src="{{ optional($cartItem->item)->image_url ?? asset('images/no-image.png') }}">
                                </div>
                                <div>
                                    <div class="cart-name">{{ $cartItem->item_name }}</div>
                                    <div class="cart-uom">{{ optional($cartItem->item)->base_unit_of_measure_code ?? 'PCS' }}</div>
                                    <div class="cart-price">${{ number_format($cartItem->unit_price, 2) }}</div>
                                </div>
                                <div class="cart-actions">
                                    <button class="remove-btn remove-item" data-id="{{ $cartItem->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <div class="qty-box">
                                        <button class="qty-btn minus qty-update" data-id="{{ $cartItem->id }}" data-action="minus">-</button>
                                        <div class="qty-number">{{ $cartItem->qty }}</div>
                                        <button class="qty-btn plus qty-update" data-id="{{ $cartItem->id }}" data-action="plus">+</button>
                                    </div>
                                </div>
                                <div class="line-total">${{ number_format($cartItem->line_total, 2) }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="summary">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <strong>${{ number_format($subtotal, 2) }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>Discount</span>
                            <strong>- ${{ number_format($discountAmount ?? 0, 2) }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>Tax</span>
                            <strong>${{ number_format($taxAmount ?? 0, 2) }}</strong>
                        </div>
                        <div class="summary-row summary-total">
                            <span>Total</span>
                            <strong>${{ number_format($total, 2) }}</strong>
                        </div>
                        <button id="checkoutBtn" class="checkout-btn">Go to Checkout</button>
                    </div>
                @endif
            </div>

            <div id="orderSuccessContent" style="display: none; padding-top: 50px; text-align: center;">
                <div class="icon-wrapper">
                    <div class="check-circle">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <span class="dot d1"></span>
                    <span class="dot d2"></span>
                    <span class="dot d3"></span>
                </div>

                <h1 class="text-confirmed">Your Order is Confirmed !</h1>
                <p class="text-main">Your order is being packed and will arrive soon.</p>
                <p class="text-sub">Fruits and veggies coming right up!</p>

                <div style="margin-top: 40px; display: flex; flex-direction: column; align-items: center;">
                    <a href="{{ route('user.pos.order.history') }}" class="btn-track">Track Order</a>
                    <a href="/pos-system" class="btn-home">Back to home</a>
                </div>
            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        async function updateQty(id, qty) {
            await fetch(`/pos-system/cart/update/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ qty })
            });
            location.reload();
        }

        document.querySelectorAll('.qty-update').forEach(btn => {
            btn.onclick = function() {
                let row = this.closest('.cart-row');
                let qty = parseInt(row.querySelector('.qty-number').innerText);
                if (this.dataset.action === 'minus') {
                    if (qty > 1) qty--;
                } else {
                    qty++;
                }
                updateQty(this.dataset.id, qty);
            }
        });

        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.onclick = async function() {
                await fetch(`/pos-system/cart/remove/${this.dataset.id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                });
                location.reload();
            }
        });

        const checkoutBtn = document.getElementById('checkoutBtn');
        if(checkoutBtn) {
            checkoutBtn.onclick = async function() {
                let currency = prompt("Choose currency (USD or KHR)", "USD");
                if (!currency) return;
                currency = currency.toUpperCase();

                let factor = 1;
                if (currency === "KHR") {
                    factor = prompt("Enter KHR rate example 4100", "4100");
                    if (!factor) return;
                }

                let res = await fetch('/pos-system/checkout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        currency: currency,
                        factor: factor
                    })
                });

                let data = await res.json();

                if (data.success) {
                    document.getElementById('cartMainContent').style.display = 'none';
                    document.getElementById('orderSuccessContent').style.display = 'block';
                } else {
                    alert(data.message || 'Checkout failed');
                }
            }
        }
    </script>
@endpush
