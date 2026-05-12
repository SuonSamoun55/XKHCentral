@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'Product Detail')

@push('styles')
<style>
:root {
    --primary: #10b8c3;
}

/* ===================== GLOBAL ===================== */
html, body {
    height: 100%;
    overflow-y: auto;
}

.sidebar-wrap {
    display: none !important;
}

.page-wrap {
    background: #f7fafc;
}

/* ===================== CONTENT ===================== */
.product-detail {
    min-height: 100vh;
    padding-bottom: 120px; /* space for fixed add-to-cart */
}

/* ===================== TOP BAR ===================== */
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

/* ===================== IMAGE ===================== */
.product-image {
    text-align: center;
    margin-top: 10px;
}

.product-image img {
    width: 80%;
    max-height: 220px;
    object-fit: contain;
}

/* ===================== INFO ===================== */
.product-info-box {
    background: #fff;
    border-radius: 20px;
    padding: 16px;
    margin-top: 12px;
}

.badge {
    background: var(--primary);
    color: #fff;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
}

.product-header {
    display: flex;
    justify-content: space-between;
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

.product-stock {
    font-size: 13px;
    color: #6b7280;
    margin-top: 4px;
}

/* ===================== COLOR (FAKE UI) ===================== */
.color-options {
    display: flex;
    gap: 10px;
    padding: 10px;
}

.color-dot {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    cursor: pointer;
    border: 2px solid transparent;
}

.color-dot.active {
    border-color: var(--primary);
}

/* ===================== RECOMMEND ===================== */
.recommend-section {
    margin-top: 16px;
}

.recommend-header {
    font-size: 14px;
    font-weight: 600;
    padding: 0 10px;
}

/* small icons list */
.recommend-list {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    padding: 10px;
}

.rec-card {
    background: #fff;
    border-radius: 14px;
    padding: 10px;
    text-align: center;
}

.rec-card img {
    width: 60%;
    height: 40px;
    object-fit: contain;
}

/* main recommendation (2 columns) */
.recommend-list1 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    padding: 10px;
}

.rec-card1 {
    background: #fff;
    border-radius: 14px;
    padding: 10px;
    text-align: center;
}

.rec-card1 img {
    width: 100%;
    height: 100px;
    object-fit: contain;
}

.rec-title {
    font-size: 12px;
    font-weight: 600;
}

.rec-price {
    font-size: 14px;
    font-weight: 700;
}

/* ===================== ADD TO CART ===================== */
.add-cart-btn {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: var(--primary);
    color: #fff;
    border: none;
    padding: 22px;
    font-size: 16px;
    z-index: 1000;
}

/* ===================== TOAST ===================== */
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
    transition: all 0.3s ease;
    z-index: 9999;
}

.toast.show {
    opacity: 1;
}

.toast.success {
    background: #10b8c3;
}

.toast.error {
    background: #ef4444;
}

/* header tweaks */
.logo-wrap {
    display: none !important;
}

.cart {
    margin-left: auto;
}
</style>
@endpush

@section('content')
<div class="page-wrap">
    <div class="product-detail">

        {{-- TOP BAR --}}
        <div class="top-bar">
            <a href="{{ url()->previous() }}" class="icon-btn">
                <i class="bi bi-arrow-left"></i>
            </a>
            @include('ManagementSystemViews.UserViews.Layouts.header_mobile')
        </div>

        {{-- IMAGE --}}
        <div class="product-image">
            <img src="{{ $item->image_url ?? asset('images/no-image.png') }}">
        </div>

        {{-- INFO --}}
        <div class="product-info-box">
            <div class="badge">Table</div>

            <div class="product-header">
                <div class="product-title">{{ $item->display_name }}</div>
                <div class="product-price">$ {{ number_format($item->unit_price,0) }}</div>
            </div>

            <div class="product-stock">
                stock: {{ $item->inventory ?? 0 }} unit
            </div>
        </div>

        {{-- COLOR (FAKE) --}}
        <div class="recommend-section">
            <div class="recommend-header"><span>Color</span></div>
            <div class="color-options">
                <div class="color-dot active" style="background:#000"></div>
                <div class="color-dot" style="background:#fff;border:1px solid #ccc"></div>
                <div class="color-dot" style="background:#ef4444"></div>
                <div class="color-dot" style="background:#3b82f6"></div>
                <div class="color-dot" style="background:#22c55e"></div>
            </div>
        </div>

        {{-- SMALL RECOMMEND --}}
        <div class="recommend-section">
            <div class="recommend-header"><span>Styles</span></div>
            <div class="recommend-list">
                @foreach ($recommendations as $rec)
                    <div class="rec-card">
                        <img src="{{ $rec->image_url }}">
                        <div class="rec-title">{{ $rec->display_name }}</div>
                        <div class="rec-price">$ {{ number_format($rec->unit_price,0) }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- MAIN RECOMMEND --}}
        <div class="recommend-section">
            <div class="recommend-header"><span>Recommendation</span></div>
            <div class="recommend-list1">
                @foreach ($recommendations as $rec)
                    <div class="rec-card1">
                        <img src="{{ $rec->image_url }}">
                        <div class="rec-title">{{ $rec->display_name }}</div>
                        <div class="rec-price">$ {{ number_format($rec->unit_price,0) }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ADD TO CART --}}
        <button type="button" class="add-cart-btn" id="addToCartBtn" data-id="{{ $item->id }}">
            Add to cart
        </button>
    </div>

    <div class="toast" id="toast"></div>
</div>
@endsection

<script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".color-dot").forEach(dot => {
        dot.addEventListener("click", () => {
            document.querySelectorAll(".color-dot").forEach(d => d.classList.remove("active"));
            dot.classList.add("active");
        });
    });
});
</script>

<script>
function showToast(type, message) {
    const toast = document.getElementById("toast");
    toast.textContent = message;
    toast.className = `toast show ${type}`;
    setTimeout(() => toast.className = "toast", 2500);
}
</script>