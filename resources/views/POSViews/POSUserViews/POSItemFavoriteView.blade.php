@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'POS Favorites')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/POSsystem/favorite.css') }}">
@endpush

@section('content')
    <div class="page-wrap">

        <main class="content-area">

            <div id="messageBox" class="message-box"></div>
            <div class="top">
                <div class="topbar">
                    @include('ManagementSystemViews.UserViews.Layouts.header', [
                        'title' => 'Favorite Items',
                    ])

                    <div class="cart-box">
                        <i class="bi bi-cart3"></i>
                        <span class="cart-count" id="cartCount">{{ (int) ($cartCount ?? 0) }}</span>
                    </div>
                </div>

                <div id="messageBox" class="message-box"></div>
                </div>

                @if ($favorites->isEmpty())
                    <div class="empty-box">No favorite items found.</div>
                @else
                    <div class="products-grid" id="productsGrid">
                        @foreach ($favorites as $item)
                            @php
                                $normalPrice = (float) ($item->unit_price ?? 0);
                                $discountPercent = (float) ($item->effective_discount_percent ?? 0);
                                $salePrice = (float) ($item->final_price ?? $normalPrice);
                                $oldPrice = $discountPercent > 0 ? $normalPrice : 0;
                            @endphp
                            <div class="product-card product-item" data-name="{{ strtolower($item->display_name ?? '') }}"
                                data-uom="{{ strtolower($item->base_unit_of_measure_code ?? '') }}">

                                <div class="product-img-box">
                                    <button type="button" class="fav-btn" data-item-id="{{ $item->id }}">
                                        <i class="bi bi-heart-fill text-danger"></i>
                                    </button>

                                    <img src="{{ $item->image_url ?: asset('images/no-image.png') }}"
                                        alt="{{ $item->display_name ?? 'No Name' }}" loading="lazy"
                                        onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';">
                                </div>

                                <div class="product-info">
                                    <div class="product-title">{{ $item->display_name ?? 'No Name' }}</div>

                                    {{-- <div class="product-desc">
                                    {{ $item->description ?: ('Thick ' . ($item->display_name ?? 'product')) }}
                                </div> --}}

                                    <div class="price-row {{ $oldPrice > $salePrice ? 'has-discount' : 'no-discount' }}">
                                        <div class="old-price">
                                            @if ($oldPrice > $salePrice)
                                                ${{ number_format($oldPrice, 2) }}
                                            @endif
                                        </div>
                                        <div class="new-price">${{ number_format($salePrice, 2) }}</div>
                                    </div>

                                    <div class="qty-section">
                                        <span class="qty-label">Quantity:</span>
                                        <div class="qty-box">
                                            <button type="button" class="qty-btn minus">−</button>
                                            <span class="qty">1</span>
                                            <button type="button" class="qty-btn plus">+</button>
                                        </div>
                                    </div>

                                    <button type="button" class="add-cart-btn" data-id="{{ $item->id }}">
                                        Add to cart
                                    </button>
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
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const productsGrid = document.getElementById("productsGrid");
            const messageBox = document.getElementById("messageBox");

            const ensureEmptyState = () => {
                if (!productsGrid) return;
                if (productsGrid.querySelectorAll(".product-card").length > 0) return;
                productsGrid.remove();
                if (!document.querySelector(".empty-box")) {
                    const empty = document.createElement("div");
                    empty.className = "empty-box";
                    empty.textContent = "No favorite items found.";
                    messageBox?.insertAdjacentElement("afterend", empty);
                }
            };

            document.querySelectorAll(".add-cart-btn").forEach(button => {
                button.addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const itemId = this.dataset.id;
                    const card = this.closest(".product-card");
                    const qty = parseInt(card.querySelector(".qty").innerText, 10) || 1;
                    this.disabled = true;

                    fetch("{{ route('user.pos.cart.add') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": csrfToken
                            },
                            body: JSON.stringify({
                                item_id: itemId,
                                qty: qty
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success && cartCount) {
                                cartCount.innerText = data.cartCount;
                            }
                            if (data.success && typeof window.showAppToast === "function") {
                                window.showAppToast("Added to cart successfully.", "success");
                            }
                        })
                        .catch(() => {
                            if (typeof window.showAppToast === "function") {
                                window.showAppToast("Failed to add item to cart.", "error");
                            }
                        })
                        .finally(() => {
                            this.disabled = false;
                        });
                });
            });

            document.querySelectorAll(".fav-btn").forEach(button => {
                button.addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const itemId = this.dataset.itemId;
                    const icon = this.querySelector("i");
                    if (this.disabled) return;
                    this.disabled = true;

                    fetch("{{ route('user.pos.favorite.toggle') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": csrfToken
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
                                ensureEmptyState();
                                if (typeof window.showAppToast === "function") {
                                    window.showAppToast("Removed from favorites.", "success");
                                }
                            }
                        })
                        .finally(() => {
                            this.disabled = false;
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
                    if (qty > 1) {
                        qty--;
                        qtySpan.innerText = qty;
                    }
                });
            });
        });
    </script>
@endpush
