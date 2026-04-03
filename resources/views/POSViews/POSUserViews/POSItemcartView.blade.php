<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Cart</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/POSsystem/cart.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="app-shell" id="appShell">


        <!-- Sidebar -->
        @include('ManagementSystemViews.UserViews.Layouts.aside')
        <div class="page-wrap">
            <!-- Main -->

            <main class="content-area">

                <div class="cart-header">
                    <div class="cart-title">Cart</div>
                </div>


                @if (!$cart || $cart->items->isEmpty())

                    <div class="empty-cart">

                        <div class="empty-cart">
                            <img src="{{ asset('images/pos/Empty.png') }}" class="empty-image">
                        </div>

                        <div class="empty-title">
                            Your cart is Empty
                        </div>

                        <div class="empty-text">
                            Add something to make me happy..!!
                        </div>

                        <a href="/pos-system" class="continue-btn">
                            Continue Shopping
                        </a>

                    </div>
                @else
                    <div class="cart-list">

                        @foreach ($cart->items as $cartItem)
                            <div class="cart-row">

                                <div class="cart-image">
                                    <img
                                        src="{{ optional($cartItem->item)->image_url ?? asset('images/no-image.png') }}">
                                </div>

                                <div>
                                    <div class="cart-name">{{ $cartItem->item_name }}</div>
                                    <div class="cart-uom">
                                        {{ optional($cartItem->item)->base_unit_of_measure_code ?? 'PCS' }}</div>
                                    <div class="cart-price">${{ number_format($cartItem->unit_price, 2) }}</div>
                                </div>

                                <div class="cart-actions">

                                    <button class="remove-btn remove-item" data-id="{{ $cartItem->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                    <div class="qty-box">

                                        <button class="qty-btn minus qty-update" data-id="{{ $cartItem->id }}"
                                            data-action="minus">-</button>

                                        <div class="qty-number">{{ $cartItem->qty }}</div>

                                        <button class="qty-btn plus qty-update" data-id="{{ $cartItem->id }}"
                                            data-action="plus">+</button>

                                    </div>

                                </div>

                                <div class="line-total">
                                    ${{ number_format($cartItem->line_total, 2) }}
                                </div>

                            </div>
                        @endforeach

                    </div>


                    <!-- Summary -->

                    <div class="summary">

                        <div class="summary-row">
                            <span>Subtotal</span>
                            <strong>${{ number_format($subtotal, 2) }}</strong>
                        </div>

                        <div class="summary-row summary-total">
                            <span>Total</span>
                            <strong>${{ number_format($total, 2) }}</strong>
                        </div>

                        <button id="checkoutBtn" class="checkout-btn">
                            Go to Checkout
                        </button>

                    </div>

                @endif

            </main>

        </div>
    </div>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


        async function updateQty(id, qty) {

            const res = await fetch(`/pos-system/cart/update/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    qty
                })
            })

            location.reload()

        }

        document.querySelectorAll('.qty-update').forEach(btn => {

            btn.onclick = function() {

                let row = this.closest('.cart-row')
                let qty = parseInt(row.querySelector('.qty-number').innerText)

                if (this.dataset.action === 'minus') {
                    if (qty > 1) qty--
                } else {
                    qty++
                }

                updateQty(this.dataset.id, qty)

            }

        })


        document.querySelectorAll('.remove-item').forEach(btn => {

            btn.onclick = async function() {

                await fetch(`/pos-system/cart/remove/${this.dataset.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })

                location.reload()

            }

        })


        document.getElementById('checkoutBtn').onclick = async function() {
            let currency = prompt("Choose currency (USD or KHR)", "USD");

            if (!currency) return;

            currency = currency.toUpperCase();

            let factor = 1;

            if (currency === "KHR") {
                factor = prompt("Enter KHR rate example 4100", "4100");
                if (!factor) return;
            }

            try {
                const response = await fetch("{{ route('user.pos.checkout') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        currency_code: currency,
                        currency_factor: factor
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert("Order created successfully");
                    window.location.href = "{{ route('user.pos.order.history') }}";
                } else {
                    alert(data.message + (data.error ? "\n\n" + data.error : ""));
                    console.error(data);
                }
            } catch (error) {
                console.error(error);
                alert("Checkout request failed.");
            }
        };
    </script>

</body>

</html>
