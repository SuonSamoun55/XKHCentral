@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'Product Detail')

@push('styles')
    <style>
        :root {
            --primary: #10b8c3;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        /* ❌ hide sidebar */
        .sidebar-wrap {
            display: none !important;
        }

        /* layout */
        .page-wrap {

            min-height: 100% !important;
            background: #f7fafc;
        }

        .product-detail {
            margin: auto;
        }

        /* TOP BAR */
        .top-bar {
            display: flex;
            justify-content: space-between;
            padding: 12px;
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

        /* IMAGE */
        .product-image {
            text-align: center;
        }

        .product-image img {
            width: 80%;
            max-height: 220px;
            object-fit: contain;
        }

        /* INFO BOX */
        .product-info-box {
            background: #fff;
            border-radius: 20px;
            padding: 16px;
            margin-top: 10px;
        }

        /* badge */
        .badge {
            background: var(--primary);
            color: #fff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
        }

        /* title + price */
        .product-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
        }

        .product-title {
            font-size: 18px;
            font-weight: 700;
        }

        .product-price {
            font-size: 20px;
            font-weight: 800;
        }

        /* stock */
        .product-stock {
            font-size: 13px;
            color: #6b7280;
            margin-top: 4px;
        }

        /* RECOMMEND */
        .recommend-section {
            margin-top: 14px;
        }

        .recommend-header {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            font-weight: 600;
            padding: 10px;
        }

        .recommend-list {
            display: grid;
            grid-template-columns: repeat(4, 0.5fr);
            /* 👈 3 items per row */
            margin-top: 10px;
            padding: 10px;
        }

        .recommend-list1 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            /* 👈 3 items per row */
            margin-top: 10px;
            padding: 10px;
        }


        /* small card */
        .rec-card {
            max-width: 78px;
            background: white;
            border-radius: 14px;
            padding: 10px;
        }

        .rec-car1 {
            background: white;

        }

        .rec-card img {
            width: 60%;
            height: 40px;
            object-fit: contain;
        }

        .rec-card1 img {
            width: 100%;
            height: 100px;
            object-fit: contain;
        }

        .rec-title {
            font-size: 10px;
            font-weight: 600;
        }

        .rec-price {
            font-size: 13px;
            font-weight: 700;
        }

        /* ADD TO CART */
     

        .cart-box {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            background: rgba(16, 184, 195, 0.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .cart-count {
            top: -6px;
            right: -6px;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
        }
         .add-cart-btn {
        position: fixed;
            bottom: 0px;
            left: 0;
            right: 0;
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 34px;
            border-radius: 6px;
            font-size: 16px;
    }
    /* TOAST */
/* TOAST - TOP RIGHT */
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #111827;
    color: #fff;
    padding: 14px 18px;
    border-radius: 12px;
    font-size: 14px;
    opacity: 0;
    pointer-events: none;
    transform: translateX(20px);
    transition: all 0.3s ease;
    z-index: 9999;
    min-width: 220px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.toast.show {
    opacity: 1;
    transform: translateX(0);
}

.toast.success {
    background: #10b8c3;
}

.toast.error {
    background: #ef4444;
}

        /* BOTTOM NAV */
    </style>
@endpush

@section('content')
    <div class="page-wrap">
        <div class="product-detail">

            <!-- TOP -->
            <div class="top-bar">
                <a href="{{ url()->previous() }}" class="icon-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>

                <a href="{{ route('user.pos.cart') }}" class="cart-box">
                    <i class="bi bi-cart3"></i>
                    <span class="cart-count" id="cartCount">0</span>
                </a>
            </div>

            <!-- IMAGE -->
            <div class="product-image">
                <img src="{{ $item->image_url ?? asset('images/no-image.png') }}">
            </div>

            <!-- INFO -->
            <div class="product-info-box">
                <div class="badge">Table</div>

                <div class="product-header">
                    <div class="product-title">{{ $item->display_name }}</div>
                    <div class="product-price">$ {{ number_format($item->unit_price, 0) }}</div>
                </div>

                <div class="product-stock">
                    stock: {{ $item->inventory ?? 0 }} unit
                </div>
            </div>

            <!-- RECOMMEND -->
            <div class="recommend-section">
                <div class="recommend-header">
                    <span>color</span>
                </div>

                <div class="recommend-list">
                    @foreach ($recommendations as $rec)
                        <div class="rec-card">
                            <img src="{{ $rec->image_url }}">
                            <div class="rec-title">{{ $rec->display_name }}</div>
                            <div class="rec-price">$ {{ number_format($rec->unit_price, 0) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="recommend-section">
                <div class="recommend-header">
                    <span>Recommendation</span>
                </div>

                <div class="recommend-list1">
                    @foreach ($recommendations as $rec)
                        <div class="rec-card1">
                            <img src="{{ $rec->image_url }}">
                            <div class="rec-title">{{ $rec->display_name }}</div>
                            <div class="rec-price">$ {{ number_format($rec->unit_price, 0) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- ADD -->
           {{-- <button
    type="button"
    class="add-cart-btn"
    id="addToCartBtn"
    data-id="{{ $item->id }}"
>
    <span class="add-cart-text">Add to cart</span>
</button> --}}
            <button type="button" class="add-cart-btn" id="addToCartBtn" data-id="{{ $item->id }}">
                <span class="add-cart-text">Add to cart</span>
            </button>

        </div>
            <!-- TOAST -->
            <div class="toast" id="toast"></div>


    </div>
@endsection
<script>
document.addEventListener("DOMContentLoaded", function () {
    const addBtn = document.getElementById("addToCartBtn");
    const cartCountEl = document.getElementById("cartCount");
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");

    if (!addBtn) return;

    addBtn.addEventListener("click", async function () {
        const itemId = this.dataset.id;

        if (!itemId) {
            alert("Item ID not found.");
            return;
        }

        this.disabled = true;
        this.querySelector(".add-cart-text").textContent = "Adding...";

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
                    qty: 1
                })
            });

            const data = await response.json();

            if (data.success) {
                if (cartCountEl && data.cartCount !== undefined) {
                    cartCountEl.textContent = data.cartCount;
                }
showToast("success", data.message || "Added to cart successfully");            } else {
                showToast("error", data.message || "Failed to add to cart");
            }
        } catch (error) {
            console.error(error);
            showToast("error", "Something went wrong.");
        } finally {
            this.disabled = false;
            this.querySelector(".add-cart-text").textContent = "Add to cart";
        }
    });
});
</script>
<script>
function showToast(type, message) {
    const toast = document.getElementById("toast");
    if (!toast) return;

    toast.textContent = message;
    toast.className = `toast show ${type}`;

    setTimeout(() => {
        toast.className = "toast";
    }, 2500);
}
</script>