@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'Product Detail')

@push('styles')
<style>
    #pos-product-detail-scope {
        --primary: #27c4c8;
        --primary-hover: #1eb2b6;
    }

    #pos-product-detail-scope, 
    #pos-product-detail-scope * { 
        box-sizing: border-box; 
       
    }
    
    #pos-product-detail-scope {
        background-color: #ffffff;
        padding: 10px 20px;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
         width:100%;
    }

    #pos-product-detail-scope .detail-wrap {
        /* max-width: 1000px; */
        width:100%;
        /* margin: 0 auto; */
    }

    /* Top Navigation */
    #pos-product-detail-scope .top-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    #pos-product-detail-scope .top-nav-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    #pos-product-detail-scope .nav-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 6px;
        border: 1px solid #eef2f6;
        color: #333;
        text-decoration: none;
        font-size: 18px;
        transition: all 0.2s;
        background: #fff;
        box-shadow: 0 4px 40px rgba(138, 149, 158, 0.2);
    }

    #pos-product-detail-scope .nav-btn:hover {
        border-color: #d6dde5;
        color: var(--primary);
    }

    #pos-product-detail-scope .cart-box {
        position: relative;
        width: 40px;
        height: 40px;
        border-radius: 6px;
        border: 1px solid #eef2f6;
        background: #ffffff;
        box-shadow: 0 4px 40px rgba(138, 149, 158, 0.2);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #333;
        text-decoration: none;
        font-size: 20px;
        transition: all 0.2s;
    }

    #pos-product-detail-scope .cart-box:hover {
        color: var(--primary);
        border-color: #d6dde5;
    }

    #pos-product-detail-scope .cart-count {
        position: absolute;
        top: -8px;
        right: -10px;
        background: var(--primary);
        color: #fff;
        border-radius: 999px;
        min-width: 22px;
        height: 22px;
        padding: 0 6px;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        line-height: 1;
    }

    #pos-product-detail-scope .cart-count.is-empty {
        display: none;
    }

    /* Grid Layout */
    #pos-product-detail-scope .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 50px;
        align-items: start;
    }

    /* Image Container */
    #pos-product-detail-scope .image-container {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        background-color: #f8fafc;
        aspect-ratio: 1 / 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #f1f5f9;
    }

    #pos-product-detail-scope .image-container img {
        width: 100%;
        height: 100%;
        object-fit: contain; 
        transition: opacity 0.3s ease;
    }

    /* --- THE FIX: Fixed size for the fallback icon --- */
    #pos-product-detail-scope .image-container img.is-fallback {
        width: 120px !important;  /* Forces it to stay small */
        height: 120px !important; /* Forces it to stay small */
        opacity: 0.3; /* Makes it a subtle watermark */
    }

    #pos-product-detail-scope .discount-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        background: #ff0000;
        color: white;
        font-size: 14px;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 4px;
        letter-spacing: 0.5px;
        z-index: 10;
    }

    /* Right Side: Product Info */
    #pos-product-detail-scope .product-title {
        font-size: 22px;
        font-weight: 700;
        color: #000;
        margin: 0 0 20px 0;
        line-height: 1.2;
    }

    #pos-product-detail-scope .price-old {
        font-size: 18px;
        color: #666;
        text-decoration: line-through;
        font-weight: 500;
        margin-bottom: 5px;
    }

    #pos-product-detail-scope .price-new {
        font-size: 18px;
        color: #000;
        font-weight: 600;
        margin-bottom: 30px;
    }

    /* Meta Details */
    #pos-product-detail-scope .product-meta {
        color: #666;
        font-size: 15px;
        line-height: 1.8;
        margin-bottom: 30px;
        border-top: 1px solid #eee;
        padding-top: 20px;
    }

    #pos-product-detail-scope .product-meta span {
        display: block;
        margin-bottom: 8px;
    }
    
    #pos-product-detail-scope .product-meta strong {
        color: #333;
        font-weight: 600;
    }

    /* Controls: Quantity & Add to Cart */
    #pos-product-detail-scope .quantity-wrapper {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
    }

    #pos-product-detail-scope .qty-box {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        border-radius: 6px;
        padding: 4px;
        border: 1px solid #e2e8f0;
    }

    #pos-product-detail-scope .qty-btn {
        background: none;
        border: none;
        width: 36px;
        height: 36px;
        font-size: 18px;
        font-weight: 600;
        color: #333;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
    }

    #pos-product-detail-scope .qty-btn:hover { background: #e9ecef; }

    #pos-product-detail-scope .qty-input {
        width: 50px;
        text-align: center;
        border: none;
        background: transparent;
        font-size: 16px;
        font-weight: 700;
        color: #000;
        -moz-appearance: textfield;
    }
    #pos-product-detail-scope .qty-input::-webkit-outer-spin-button,
    #pos-product-detail-scope .qty-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

    #pos-product-detail-scope .add-to-cart-btn {
        background: var(--primary);
        color: white;
        border: none;
        padding: 14px 40px;
        font-size: 15px;
        font-weight: 700;
        border-radius: 10px;
        cursor: pointer;
        transition: background 0.2s;
        display: inline-block;
        width: 100%;
        max-width: 100%;
        text-align: center;
    }

    #pos-product-detail-scope .add-to-cart-btn:hover { background: var(--primary-hover); }
    #pos-product-detail-scope .add-to-cart-btn:disabled { background: #a5d8c9; cursor: not-allowed; }

    @media (max-width: 768px) {
        #pos-product-detail-scope .detail-grid { grid-template-columns: 1fr; gap: 30px; }
        #pos-product-detail-scope .product-title { font-size: 28px; }
        #pos-product-detail-scope .add-to-cart-btn { max-width: 100%; }
    }
</style>
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

