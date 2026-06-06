@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'Product Detail')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/POSViews/POSUserViews/Products/show.css') }}">
@endpush

@section('content')
<div id="pos-product-detail-scope">
    <div class="detail-wrap">
        
        <div class="top-nav">
            <div class="top-nav-actions">
                <a href="{{ route('user.posinterface') }}" class="nav-btn" title="Back">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            <div class="top-nav-actions">
                <a href="{{ route('user.pos.cart') }}" class="cart-box" title="View Cart">
                    <i class="bi bi-cart3"></i>
                    <span class="cart-count {{ (int) ($cartCount ?? 0) > 0 ? '' : 'is-empty' }}" id="detailCartCount">{{ (int) ($cartCount ?? 0) }}</span>
                </a>
            </div>
        </div>

        <div class="detail-grid">
            
            <div class="image-container">
                @if($discountPercent > 0)
                    <div class="discount-badge">
                        SAVE {{ rtrim(rtrim(number_format($discountPercent, 2), '0'), '.') }}%
                    </div>
                @endif
                
                <img src="{{ $item->image_url }}"
                     alt="{{ $item->display_name ?: 'Product' }}"
                     onerror="this.onerror=null; this.classList.add('is-fallback'); this.src='data:image/svg+xml;charset=UTF-8,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%23cbd5e1\' stroke-width=\'1.5\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3E%3Crect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\' ry=\'2\'/%3E%3Ccircle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'/%3E%3Cpolyline points=\'21 15 16 10 5 21\'/%3E%3C/svg%3E';">
            </div>

            <div>
                <h1 class="product-title">{{ $item->display_name ?: 'Unnamed Product' }}</h1>

                @if($discountPercent > 0)
                    <div class="price-old">${{ number_format($unitPrice, 2) }}</div>
                @endif
                <div class="price-new">${{ number_format($finalPrice, 2) }}</div>

                <div class="product-meta">
                    <span><strong>Item No:</strong> {{ $item->number ?: 'N/A' }}</span>
                    <span><strong>Category:</strong> {{ $item->item_category_code ?: 'General' }}</span>
                    <span><strong>Unit:</strong> {{ $item->base_unit_of_measure_code ?: 'PCS' }}</span>
                    <span><strong>Availability:</strong> <span style="color: {{ ($item->inventory ?? 0) > 0 ? '#20c997' : '#ff0000' }};">{{ (int) ($item->inventory ?? 0) }} in stock</span></span>
                </div>

                <div class="quantity-wrapper">
                    <div class="qty-box">
                        <button type="button" class="qty-btn" id="qtyMinus">-</button>
                        <input type="number" class="qty-input" id="detailQty" value="1" min="1" max="{{ $item->inventory ?? 999 }}">
                        <button type="button" class="qty-btn" id="qtyPlus">+</button>
                    </div>
                </div>

                <button type="button" class="add-to-cart-btn" id="detailAddToCartBtn" data-id="{{ $item->id }}">
                    Add to cart
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const mediaQuery = window.matchMedia("(min-width: 1024px)");

    function handleScreenChange(e) {
        if (e.matches) {
            window.location.href = "{{ route('user.pos.cart') }}";
        }
    }

    // Run on load
    handleScreenChange(mediaQuery);

    // Listen for screen change
    mediaQuery.addEventListener('change', handleScreenChange);
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const btn = document.getElementById("detailAddToCartBtn");
        const cartCountEl = document.getElementById("detailCartCount");
        const qtyInput = document.getElementById("detailQty");
        const btnMinus = document.getElementById("qtyMinus");
        const btnPlus = document.getElementById("qtyPlus");
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        if (btnMinus && btnPlus && qtyInput) {
            btnMinus.addEventListener("click", () => {
                let currentVal = parseInt(qtyInput.value) || 1;
                if (currentVal > 1) qtyInput.value = currentVal - 1;
            });

            btnPlus.addEventListener("click", () => {
                let currentVal = parseInt(qtyInput.value) || 1;
                let maxVal = parseInt(qtyInput.getAttribute("max")) || 999;
                if (currentVal < maxVal) qtyInput.value = currentVal + 1;
            });
            
            qtyInput.addEventListener("change", () => {
                if (qtyInput.value < 1) qtyInput.value = 1;
            });
        }

        if (!btn) return;

        btn.addEventListener("click", async function () {
            const itemId = this.dataset.id;
            const quantity = parseInt(qtyInput.value) || 1;

            if (!itemId) return;

            this.disabled = true;
            const oldText = this.innerHTML;
            this.innerHTML = 'Adding...';

            try {
                const response = await fetch("{{ route('user.pos.cart.add') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        qty: quantity
                    })
                });

                const data = await response.json();
                if (!response.ok || !data.success) {
                    throw new Error(data.message || "Failed to add to cart.");
                }

                if (typeof window.showAppToast === "function") {
                    window.showAppToast(`${quantity} item(s) added.`, "success");
                }

                if (cartCountEl) {
                    const nextCount = Number.isFinite(Number(data.cartCount))
                        ? Number(data.cartCount)
                        : (parseInt(cartCountEl.textContent, 10) || 0) + quantity;

                    cartCountEl.textContent = nextCount;
                    cartCountEl.classList.toggle("is-empty", nextCount <= 0);
                }

                const asideCartCountEl = document.getElementById("asideCartCount");
                if (asideCartCountEl && data.cartCount !== undefined) {
                    asideCartCountEl.textContent = data.cartCount;
                    asideCartCountEl.classList.toggle("is-empty", data.cartCount <= 0);
                }

                this.innerHTML = 'Added!';
                
                setTimeout(() => {
                    this.innerHTML = oldText;
                    this.disabled = false;
                }, 1500);

            } catch (error) {
                this.innerHTML = oldText;
                this.disabled = false;
                if (typeof window.showAppToast === "function") {
                    window.showAppToast(error?.message || "Failed to add to cart.", "error");
                } else {
                    alert(error?.message || "Failed to add to cart.");
                }
            }
        });
    });
</script>
@endpush

