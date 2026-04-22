@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'POS Favorites')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pos.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/POSsystem/favorite.css') }}" />
@endpush

@section('content')
    <div class="page-wrap">
        <main class="content-area">
            @include('ManagementSystemViews.UserViews.Layouts.header', ['title' => 'Favorite Items'])

            <div id="messageBox" class="message-box"></div>

            @if ($favorites->isEmpty())
                <div class="empty-box">No favorite items found.</div>
            @else
                <div class="products-grid" id="productsGrid">
                    @foreach ($favorites as $item)
                        <div class="product-card product-item"
                            data-name="{{ strtolower($item->display_name ?? '') }}"
                            data-uom="{{ strtolower($item->base_unit_of_measure_code ?? '') }}">

                            <div class="product-img-box">
                                <button class="fav-btn" data-item-id="{{ $item->id }}">
                                    <i class="bi bi-heart-fill text-danger"></i>
                                </button>

                                <img src="{{ $item->image_url ?: asset('images/no-image.png') }}"
                                    alt="{{ $item->display_name ?? 'No Name' }}" loading="lazy"
                                    onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';">
                            </div>

                            <div class="product-info">
                                <div class="product-title">
                                    {{ $item->display_name ?? 'No Name' }}
                                </div>

                                <div class="product-bottom">
                                    <div class="price">
                                        ${{ number_format($item->unit_price ?? 0, 2) }}
                                    </div>

                                    <div class="qty-box">
                                        <button class="qty-btn minus">-</button>
                                        <span class="qty">0</span>
                                        <button class="qty-btn plus add-to-cart-btn" data-id="{{ $item->id }}">+</button>
                                    </div>
                                </div>

                                <a href="/pos-system/cart">
                                    <button class="btn btn-outline-primary btn-sm move-to-cart-btn add-to-cart-btn"
                                        data-id="{{ $item->id }}">
                                        <i class="bi bi-cart3"></i> Move to cart
                                    </button>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cartCount = document.getElementById("cartCount");

            document.querySelectorAll(".add-to-cart-btn").forEach(button => {
                button.addEventListener("click", function() {
                    const itemId = this.dataset.id;
                    fetch("{{ route('user.pos.cart.add') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').content
                            },
                            body: JSON.stringify({
                                item_id: itemId,
                                qty: 1
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success && cartCount) {
                                cartCount.innerText = data.cartCount;
                            }
                        });
                });
            });

            document.querySelectorAll(".fav-btn").forEach(button => {
                button.addEventListener("click", function() {
                    const itemId = this.dataset.itemId;
                    const icon = this.querySelector("i");

                    fetch("{{ route('user.pos.favorite.toggle') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').content
                            },
                            body: JSON.stringify({
                                item_id: itemId
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.favorited) {
                                icon.classList.remove("bi-heart");
                                icon.classList.add("bi-heart-fill", "text-danger");
                            } else {
                                icon.classList.remove("bi-heart-fill", "text-danger");
                                icon.classList.add("bi-heart");
                                button.closest('.product-card').remove();
                            }
                        });
                });
            });

            document.querySelectorAll(".product-card").forEach(card => {
                const plusBtn = card.querySelector(".plus");
                const minusBtn = card.querySelector(".minus");
                const qtySpan = card.querySelector(".qty");

                plusBtn.addEventListener("click", function() {
                    let qty = parseInt(qtySpan.innerText);
                    qty++;
                    qtySpan.innerText = qty;
                });

                minusBtn.addEventListener("click", function() {
                    let qty = parseInt(qtySpan.innerText);
                    if (qty > 0) {
                        qty--;
                        qtySpan.innerText = qty;
                    }
                });
            });
        });
    </script>
@endpush
