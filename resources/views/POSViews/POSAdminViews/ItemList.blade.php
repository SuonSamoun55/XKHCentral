@extends('POSViews.POSAdminViews.app')

@section('title', 'POS System')

@push('styles')
<style>
    :root{
        --primary:#18bccb;
        --primary-dark:#12a6b4;
        --bg:#f3f4f6;
        --white:#ffffff;
        --text:#1f2937;
        --muted:#98a2b3;
        --border:#e7eaee;
        --danger:#ef4444;
    }

    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
    }

    body{
        background:var(--bg);
        font-family:Arial, Helvetica, sans-serif;
        color:var(--text);
    }

    .main-wrap{
        background:#fff;
        flex:1;
        min-width:0;
        height:100vh;
        overflow:hidden;
        border-radius:20px;
        /* margin-top:10px;
        margin-left:10px; */
        padding:28px 28px 20px;
        display:flex;
        flex-direction:column;
    }

    .page-title{
        font-size:24px;
        font-weight:800;
        color:#34a6b5;
        margin-bottom:24px;
        flex-shrink:0;
    }

    .toolbar-row{
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:18px;
        margin-bottom:18px;
        flex-wrap:wrap;
        flex-shrink:0;
    }

    .toolbar-left{
        width:100%;
        max-width:470px;
        position:relative;
    }

    .search-box{
        position:relative;
        margin-bottom:10px;
    }

    .search-box i{
        position:absolute;
        left:12px;
        top:50%;
        transform:translateY(-50%);
        font-size:13px;
        color:#9ca3af;
    }

    .search-box input{
        width:100%;
        height:32px;
        border:1px solid var(--border);
        border-radius:16px;
        background:#f8fafc;
        padding:0 14px 0 34px;
        font-size:12px;
        outline:none;
    }

    .search-box input:focus{
        background:#fff;
        border-color:var(--primary);
    }

    .filter-wrap{
        position:relative;
        display:inline-block;
    }

    .filter-btn{
        height:28px;
        padding:0 12px;
        border:1px solid #d7dce2;
        border-radius:10px;
        background:#fff;
        color:#4b5563;
        font-size:11px;
        display:inline-flex;
        align-items:center;
        gap:6px;
        cursor:pointer;
    }

    .filter-panel{
        position:absolute;
        top:36px;
        left:0;
        width:240px;
        max-height:400px;
        overflow-y:auto;
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:8px;
        box-shadow:0 14px 30px rgba(15, 23, 42, 0.12);
        padding:10px 10px 12px;
        z-index:99;
        display:none;
    }

    .filter-panel.show{
        display:block;
    }

    .filter-title{
        font-size:11px;
        font-weight:700;
        color:#374151;
        margin-bottom:10px;
    }

    .filter-section{
        margin-bottom:12px;
        border-bottom:1px solid #f1f5f9;
        padding-bottom:10px;
    }

    .filter-section:last-of-type{
        border-bottom:none;
        margin-bottom:10px;
        padding-bottom:0;
    }

    .filter-section-header{
        margin-bottom:8px;
    }

    .filter-section-header h6{
        font-size:10px;
        font-weight:700;
        margin:0;
        color:#374151;
    }

    .check-list{
        display:flex;
        flex-direction:column;
        gap:6px;
        max-height:140px;
        overflow-y:auto;
        padding-right:2px;
    }

    .check-item{
        display:flex;
        align-items:center;
        gap:7px;
        font-size:10px;
        color:#4b5563;
        line-height:1.2;
    }

    .check-item input{
        width:11px;
        height:11px;
        accent-color:var(--primary);
        cursor:pointer;
        margin:0;
    }

    .range-values{
        display:flex;
        justify-content:space-between;
        align-items:center;
        font-size:10px;
        color:#6b7280;
        margin-bottom:8px;
    }

    .slider-wrap{
        position:relative;
        height:34px;
        margin-bottom:10px;
    }

    .slider-track{
        position:absolute;
        top:14px;
        left:0;
        right:0;
        height:4px;
        background:#e5e7eb;
        border-radius:999px;
    }

    .slider-range{
        position:absolute;
        top:14px;
        height:4px;
        background:var(--primary);
        border-radius:999px;
    }

    .range-input{
        position:absolute;
        left:0;
        top:6px;
        width:100%;
        pointer-events:none;
        appearance:none;
        -webkit-appearance:none;
        background:none;
        height:16px;
        margin:0;
    }

    .range-input::-webkit-slider-thumb{
        -webkit-appearance:none;
        appearance:none;
        width:14px;
        height:14px;
        border-radius:50%;
        background:var(--primary);
        border:2px solid #fff;
        box-shadow:0 1px 4px rgba(0,0,0,.25);
        cursor:pointer;
        pointer-events:auto;
    }

    .range-input::-moz-range-thumb{
        width:14px;
        height:14px;
        border-radius:50%;
        background:var(--primary);
        border:2px solid #fff;
        box-shadow:0 1px 4px rgba(0,0,0,.25);
        cursor:pointer;
        pointer-events:auto;
    }

    .range-input::-webkit-slider-runnable-track{
        height:4px;
        background:transparent;
    }

    .range-input::-moz-range-track{
        height:4px;
        background:transparent;
    }

    .range-box-row{
        display:flex;
        gap:8px;
    }

    .range-box{
        flex:1;
    }

    .range-box label{
        display:block;
        font-size:9px;
        color:#6b7280;
        margin-bottom:4px;
    }

    .range-box input{
        width:100%;
        height:28px;
        border:1px solid #e5e7eb;
        border-radius:6px;
        padding:0 8px;
        font-size:11px;
        outline:none;
    }

    .filter-actions{
        display:flex;
        gap:8px;
        margin-top:8px;
    }

    .filter-reset,
    .filter-apply{
        flex:1;
        height:30px;
        border:none;
        border-radius:6px;
        font-size:11px;
        font-weight:700;
        cursor:pointer;
    }

    .filter-reset{
        background:#f3f4f6;
        color:#4b5563;
    }

    .filter-apply{
        background:var(--primary);
        color:#fff;
    }

    .toolbar-right{
        display:flex;
        flex-direction:column;
        align-items:flex-end;
        gap:8px;
    }

    .sync-btn{
        border:none;
        background:var(--primary);
        color:#fff;
        height:30px;
        min-width:168px;
        padding:0 16px;
        border-radius:4px;
        font-size:11px;
        font-weight:700;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        cursor:pointer;
    }

    .view-switch{
        display:flex;
        gap:8px;
    }

    .view-btn{
        min-width:64px;
        height:32px;
        border:none;
        border-radius:4px;
        background:#f3f5f7;
        color:var(--primary);
        font-size:11px;
        display:flex;
        align-items:center;
        justify-content:center;
        gap:7px;
        cursor:pointer;
    }

    .view-btn.active{
        background:#eefbfd;
        box-shadow:inset 0 0 0 1px #d7f2f5;
    }

    .items-scroll{
        flex:1;
        min-height:0;
        overflow-y:auto;
        padding-right:4px;
    }

    .item-grid{
        display:grid;
        grid-template-columns:repeat(4, minmax(0, 1fr)) !important;
        gap:10px;
    }

    .product-card{
        background:#fff;
        border:1px solid #eceff3;
        border-radius:8px;
        overflow:hidden;
        display:flex;
        flex-direction:column;
        min-height:345px;
    }

    .product-image{
        width:100%;
        height:172px;
        background:#eef2f7;
        position:relative;
    }

    .product-image img{
        width:100%;
        height:100%;
        object-fit:cover;
        display:block;
    }

    .sale-badge{
        position:absolute;
        top:8px;
        left:8px;
        background:#ef4444;
        color:#fff;
        font-size:9px;
        font-weight:700;
        padding:4px 7px;
        border-radius:4px;
        z-index:2;
    }

    .product-body{
        padding:12px 12px 14px;
        display:flex;
        flex-direction:column;
        flex:1;
    }

    .product-title{
        font-size:14px;
        font-weight:700;
        color:#111827;
        line-height:1.35;
        margin-bottom:3px;
    }

    .product-sub{
        font-size:10px;
        color:#c0c7d2;
        line-height:1.35;
        min-height:26px;
        margin-bottom:8px;
    }

    .price-row{
        display:flex;
        align-items:flex-end;
        gap:8px;
        margin-bottom:12px;
        flex-wrap:wrap;
    }

    .product-price{
        font-size:15px;
        font-weight:800;
        color:#111827;
        line-height:1;
    }

    .old-price{
        font-size:12px;
        font-weight:700;
        color:#6b7280;
        text-decoration:line-through;
        line-height:1;
    }

    .discount-percent{
        font-size:10px;
        font-weight:700;
        color:#ef4444;
        line-height:1;
    }

    .stock-text{
        font-size:12px;
        font-weight:700;
        color:#10bcd0;
        margin-bottom:14px;
    }

    .stock-text.out{
        color:#ef4444;
    }

    .view-more-btn{
        margin-top:auto;
        width:100%;
        height:30px;
        border:none;
        border-radius:4px;
        background:var(--primary);
        color:#fff !important;
        font-size:12px;
        font-weight:700;
        text-decoration:none;
        display:flex;
        align-items:center;
        justify-content:center;
    }

    .item-list{
        display:flex;
        flex-direction:column;
        gap:12px;
    }

    .list-card{
        display:flex;
        gap:14px;
        align-items:center;
        background:#fff;
        border:1px solid #eceff3;
        border-radius:10px;
        padding:10px 12px;
    }

    .list-image{
        width:100px;
        height:80px;
        border-radius:8px;
        overflow:hidden;
        background:#eef2f7;
        flex-shrink:0;
    }

    .list-image img{
        width:100%;
        height:100%;
        object-fit:cover;
    }

    .list-info{
        flex:1;
        min-width:0;
    }

    .list-title{
        font-size:14px;
        font-weight:700;
        margin-bottom:4px;
    }

    .list-sub{
        font-size:11px;
        color:#9aa4b2;
        margin-bottom:6px;
    }

    .list-stock{
        font-size:12px;
        font-weight:700;
        color:#10bcd0;
    }

    .list-stock.out{
        color:#ef4444;
    }

    .list-price{
        min-width:130px;
        text-align:right;
    }

    .list-price .now{
        font-size:16px;
        font-weight:800;
        color:#111827;
    }

    .list-price .old{
        font-size:11px;
        color:#6b7280;
        text-decoration:line-through;
    }

    .list-action{
        min-width:120px;
    }

    .empty-box{
        grid-column:1 / -1;
        background:#fff;
        border:1px dashed #dce3ea;
        border-radius:12px;
        padding:50px 20px;
        text-align:center;
        color:#6b7280;
    }

    .sync-toast-wrap{
        position:fixed;
        top:22px;
        right:22px;
        z-index:1200;
        display:flex;
        flex-direction:column;
        gap:10px;
        pointer-events:none;
    }

    .sync-toast{
        min-width:280px;
        max-width:380px;
        border-radius:10px;
        box-shadow:0 10px 26px rgba(15, 23, 42, 0.18);
        border:1px solid;
        overflow:hidden;
        background:#fff;
        pointer-events:auto;
        animation:toastSlideIn .2s ease;
    }

    .sync-toast.success{
        border-color:#bbf7d0;
    }

    .sync-toast.error{
        border-color:#fecaca;
    }

    .sync-toast-head{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:10px;
        padding:10px 12px;
        font-size:13px;
        font-weight:700;
    }

    .sync-toast.success .sync-toast-head{
        background:#ecfdf3;
        color:#166534;
    }

    .sync-toast.error .sync-toast-head{
        background:#fef2f2;
        color:#991b1b;
    }

    .sync-toast-body{
        padding:10px 12px 12px;
        font-size:12px;
        color:#374151;
        line-height:1.4;
    }

    .sync-toast-close{
        border:none;
        background:transparent;
        color:inherit;
        font-size:16px;
        font-weight:700;
        line-height:1;
        cursor:pointer;
        padding:0;
    }

    @keyframes toastSlideIn{
        from{
            opacity:0;
            transform:translateY(-8px);
        }
        to{
            opacity:1;
            transform:translateY(0);
        }
    }

    @media (max-width: 991px){
        .item-grid{
            grid-template-columns:repeat(2, minmax(0, 1fr)) !important;
        }
    }

    @media (max-width: 576px){
        .item-grid{
            grid-template-columns:1fr !important;
        }

        .main-wrap{
            padding:18px;
            margin-left:0;
            border-radius:0;
        }

        .toolbar-right{
            align-items:flex-start;
        }
    }
</style>
@endpush

@section('content')
<main class="main-wrap">
    <h1 class="page-title">POS System</h1>

    <div class="toolbar-row">
        <div class="toolbar-left">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Search">
            </div>

            <div class="filter-wrap">
                <button type="button" class="filter-btn" id="filterBtn">
                    Filter
                    <i class="bi bi-sliders2"></i>
                </button>

                <div class="filter-panel" id="filterPanel">
                    <div class="filter-title">Filter by</div>

                    <div class="filter-section">
                        <div class="filter-section-header">
                            <h6>Category</h6>
                        </div>
                        <div class="check-list" id="categoryCheckboxList"></div>
                    </div>

                    <div class="filter-section">
                        <div class="filter-section-header">
                            <h6>Inventory</h6>
                        </div>

                        <div class="range-values">
                            <span id="inventoryMinLabel">0</span>
                            <span id="inventoryMaxLabel">200</span>
                        </div>

                        <div class="slider-wrap">
                            <div class="slider-track"></div>
                            <div class="slider-range" id="sliderRange"></div>

                            <input type="range" id="inventoryMinRange" class="range-input" min="0" max="200" value="0">
                            <input type="range" id="inventoryMaxRange" class="range-input" min="0" max="200" value="200">
                        </div>

                        <div class="range-box-row">
                            <div class="range-box">
                                <label for="minInventory">Min</label>
                                <input type="number" id="minInventory" min="0" value="0">
                            </div>
                            <div class="range-box">
                                <label for="maxInventory">Max</label>
                                <input type="number" id="maxInventory" min="0" value="200">
                            </div>
                        </div>
                    </div>

                    <div class="filter-actions">
                        <button type="button" class="filter-reset" onclick="resetFilters()">Reset</button>
                        <button type="button" class="filter-apply" onclick="applyFilters()">Apply</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="toolbar-right">
            <button id="syncBtn" type="button" class="sync-btn" onclick="updateItems()">
                <i class="bi bi-arrow-repeat"></i>
                Sync BC Product
            </button>

            <div class="view-switch">
                <button type="button" class="view-btn active" id="gridBtn" onclick="setView('grid')">
                    <i class="bi bi-grid-3x3-gap-fill"></i> Grid
                </button>
                <button type="button" class="view-btn" id="listBtn" onclick="setView('list')">
                    <i class="bi bi-list-ul"></i> List
                </button>
            </div>
        </div>
    </div>

    <div class="items-scroll">
        <div id="itemContainer" class="item-grid"></div>
    </div>
</main>
<div id="syncToastWrap" class="sync-toast-wrap"></div>

<script>
    const PRODUCTS = @json($items ?? []);
    let currentView = 'grid';
    let filteredProducts = [...PRODUCTS];

    const money = (n) => '$' + Number(n || 0).toFixed(2);

    const esc = (s) => String(s ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');

    function showSyncToast(type, title, message) {
        const wrap = document.getElementById('syncToastWrap');
        if (!wrap) return;

        const toast = document.createElement('div');
        toast.className = `sync-toast ${type === 'success' ? 'success' : 'error'}`;

        toast.innerHTML = `
            <div class="sync-toast-head">
                <span>${esc(title)}</span>
                <button type="button" class="sync-toast-close" aria-label="Close">&times;</button>
            </div>
            <div class="sync-toast-body">${esc(message)}</div>
        `;

        const closeBtn = toast.querySelector('.sync-toast-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => toast.remove());
        }

        wrap.appendChild(toast);
        setTimeout(() => toast.remove(), 3500);
    }

    function getItemName(item) {
        return item.displayName || item.display_name || 'No Name';
    }

    function getItemCategory(item) {
        return item.itemCategoryCode || item.item_category_code || 'General';
    }

    function getItemDescription(item) {
        return item.description || ('Fresh ' + getItemCategory(item).toLowerCase());
    }

    function getItemPrice(item) {
        return Number(item.unitPrice ?? item.unit_price ?? 0);
    }

    function getItemInventory(item) {
        return Math.round(Number(item.inventory || 0));
    }

    function getPricing(item) {
        const basePrice = Number(item.unitPrice ?? item.unit_price ?? 0);

        const explicitOldPrice =
            item.old_price ?? item.oldPrice ?? null;

        const explicitDiscountPrice =
            item.discount_price ?? item.discountPrice ??
            item.special_price ?? item.specialPrice ??
            item.final_price ?? item.finalPrice ?? null;

        const explicitDiscountPercent =
            Number(item.discount_percent ?? item.discountPercent ?? 0);

        let nowPrice = basePrice;
        let oldPrice = null;
        let discountPercent = 0;

        if (explicitDiscountPrice !== null && explicitDiscountPrice !== '' && Number(explicitDiscountPrice) < basePrice) {
            nowPrice = Number(explicitDiscountPrice);
            oldPrice = basePrice;
            discountPercent = Math.round(((oldPrice - nowPrice) / oldPrice) * 100);
        } else if (explicitOldPrice !== null && explicitOldPrice !== '' && Number(explicitOldPrice) > basePrice) {
            nowPrice = basePrice;
            oldPrice = Number(explicitOldPrice);
            discountPercent = explicitDiscountPercent > 0
                ? explicitDiscountPercent
                : Math.round(((oldPrice - nowPrice) / oldPrice) * 100);
        } else if (explicitDiscountPercent > 0) {
            oldPrice = basePrice;
            nowPrice = basePrice - (basePrice * explicitDiscountPercent / 100);
            discountPercent = explicitDiscountPercent;
        }

        return {
            nowPrice,
            oldPrice,
            discountPercent,
            hasDiscount: oldPrice !== null && oldPrice > nowPrice
        };
    }

    function stockText(qty) {
        if (qty <= 0) {
            return `<div class="stock-text out">Out of Stock</div>`;
        }
        return `<div class="stock-text">${qty} items left</div>`;
    }

    function buildCheckboxFilters() {
        const categoryBox = document.getElementById('categoryCheckboxList');
        const categories = [...new Set(PRODUCTS.map(getItemCategory))].sort();

        categoryBox.innerHTML = categories.map((category) => `
            <label class="check-item">
                <input type="checkbox" class="category-check" value="${esc(category)}">
                <span>${esc(category)}</span>
            </label>
        `).join('');
    }

    function getCheckedValues(selector) {
        return [...document.querySelectorAll(selector + ':checked')].map(el => el.value);
    }

    function getInventoryBounds() {
        const inventories = PRODUCTS.map(getItemInventory);
        const min = inventories.length ? Math.min(...inventories) : 0;
        const max = inventories.length ? Math.max(...inventories) : 200;
        return { min, max: max > min ? max : min + 1 };
    }

    function updateSliderRangeUI() {
        const minSlider = document.getElementById('inventoryMinRange');
        const maxSlider = document.getElementById('inventoryMaxRange');
        const minLabel = document.getElementById('inventoryMinLabel');
        const maxLabel = document.getElementById('inventoryMaxLabel');
        const sliderRange = document.getElementById('sliderRange');

        const min = Number(minSlider.min);
        const max = Number(minSlider.max);
        const minVal = Number(minSlider.value);
        const maxVal = Number(maxSlider.value);

        minLabel.textContent = minVal;
        maxLabel.textContent = maxVal;

        const left = ((minVal - min) / (max - min)) * 100;
        const right = ((maxVal - min) / (max - min)) * 100;

        sliderRange.style.left = left + '%';
        sliderRange.style.width = (right - left) + '%';
    }

    function syncRangeToInputs() {
        document.getElementById('minInventory').value = document.getElementById('inventoryMinRange').value;
        document.getElementById('maxInventory').value = document.getElementById('inventoryMaxRange').value;
        updateSliderRangeUI();
    }

    function syncInputsToRange() {
        const minInput = document.getElementById('minInventory');
        const maxInput = document.getElementById('maxInventory');
        const minSlider = document.getElementById('inventoryMinRange');
        const maxSlider = document.getElementById('inventoryMaxRange');

        let minVal = Number(minInput.value || minSlider.min);
        let maxVal = Number(maxInput.value || maxSlider.max);

        if (minVal > maxVal) {
            [minVal, maxVal] = [maxVal, minVal];
        }

        minSlider.value = minVal;
        maxSlider.value = maxVal;
        minInput.value = minVal;
        maxInput.value = maxVal;

        updateSliderRangeUI();
    }

    function applyFilters() {
        const keyword = document.getElementById('searchInput').value.trim().toLowerCase();
        const selectedCategories = getCheckedValues('.category-check');
        const minInventory = Number(document.getElementById('minInventory').value || 0);
        const maxInventory = Number(document.getElementById('maxInventory').value || 0);

        filteredProducts = PRODUCTS.filter(item => {
            const name = getItemName(item).toLowerCase();
            const category = getItemCategory(item);
            const inventory = getItemInventory(item);

            const matchKeyword =
                !keyword ||
                name.includes(keyword) ||
                category.toLowerCase().includes(keyword);

            const matchCategory =
                selectedCategories.length === 0 || selectedCategories.includes(category);

            const matchMin = inventory >= minInventory;
            const matchMax = inventory <= maxInventory;

            return matchKeyword && matchCategory && matchMin && matchMax;
        });

        closeFilterPanel();
        renderItems();
    }

    function resetFilters() {
        const bounds = getInventoryBounds();

        document.getElementById('searchInput').value = '';
        document.querySelectorAll('.category-check').forEach(input => input.checked = false);

        document.getElementById('inventoryMinRange').value = bounds.min;
        document.getElementById('inventoryMaxRange').value = bounds.max;
        document.getElementById('minInventory').value = bounds.min;
        document.getElementById('maxInventory').value = bounds.max;

        filteredProducts = [...PRODUCTS];
        updateSliderRangeUI();
        renderItems();
    }

    function setView(view) {
        currentView = view;
        document.getElementById('gridBtn').classList.toggle('active', view === 'grid');
        document.getElementById('listBtn').classList.toggle('active', view === 'list');
        renderItems();
    }

    function renderItems() {
        const container = document.getElementById('itemContainer');

        if (!filteredProducts.length) {
            container.className = currentView === 'grid' ? 'item-grid' : 'item-list';
            container.innerHTML = `<div class="empty-box">No products found.</div>`;
            return;
        }

        if (currentView === 'grid') {
            container.className = 'item-grid';
            container.innerHTML = filteredProducts.map(item => {
                const name = getItemName(item);
                const description = getItemDescription(item);
                const inventory = getItemInventory(item);
                const pricing = getPricing(item);

                return `
                    <div class="product-card">
                        <div class="product-image">
                            ${pricing.hasDiscount ? `<span class="sale-badge">SAVE ${pricing.discountPercent}%</span>` : ``}
                            <img
                                src="/item-image/${esc(item.id)}"
                                alt="${esc(name)}"
                                loading="lazy"
                                onerror="this.src='https://placehold.co/500x320/e5e7eb/94a3b8?text=No+Photo'">
                        </div>

                        <div class="product-body">
                            <div class="product-title">${esc(name)}</div>
                            <div class="product-sub">${esc(description)}</div>

                            <div class="price-row">
                                <div class="product-price">${money(pricing.nowPrice)}</div>
                                ${pricing.hasDiscount ? `<div class="old-price">${money(pricing.oldPrice)}</div>` : ``}
                                ${pricing.hasDiscount ? `<div class="discount-percent">${pricing.discountPercent}%</div>` : ``}
                            </div>

                            ${stockText(inventory)}

                            <a href="/pos/items/${item.id}" class="view-more-btn">
                                View More
                            </a>
                        </div>
                    </div>
                `;
            }).join('');
        } else {
            container.className = 'item-list';
            container.innerHTML = filteredProducts.map(item => {
                const name = getItemName(item);
                const description = getItemDescription(item);
                const inventory = getItemInventory(item);
                const pricing = getPricing(item);

                return `
                    <div class="list-card">
                        <div class="list-image">
                            <img
                                src="/item-image/${esc(item.id)}"
                                alt="${esc(name)}"
                                loading="lazy"
                                onerror="this.src='https://placehold.co/500x320/e5e7eb/94a3b8?text=No+Photo'">
                        </div>

                        <div class="list-info">
                            <div class="list-title">${esc(name)}</div>
                            <div class="list-sub">${esc(description)}</div>
                            <div class="list-stock ${inventory <= 0 ? 'out' : ''}">
                                ${inventory <= 0 ? 'Out of Stock' : inventory + ' items left'}
                            </div>
                        </div>

                        <div class="list-price">
                            <div class="now">${money(pricing.nowPrice)}</div>
                            ${pricing.hasDiscount ? `<div class="old">${money(pricing.oldPrice)}</div>` : ``}
                        </div>

                        <div class="list-action">
                            <a href="/pos/items/${item.id}" class="view-more-btn">
                                View More
                            </a>
                        </div>
                    </div>
                `;
            }).join('');
        }
    }

    function toggleFilterPanel() {
        document.getElementById('filterPanel').classList.toggle('show');
    }

    function closeFilterPanel() {
        document.getElementById('filterPanel').classList.remove('show');
    }

    async function updateItems() {
        const btn = document.getElementById('syncBtn');
        const oldHtml = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = `<i class="bi bi-arrow-repeat"></i> Syncing...`;

        try {
            const res = await fetch('/items/sync-from-al', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    items: PRODUCTS.map(item => ({
                        id: item.id,
                        number: item.number,
                        displayName: item.displayName,
                        unitPrice: item.unitPrice,
                        inventory: item.inventory,
                        blocked: item.blocked,
                        itemCategoryCode: item.itemCategoryCode,
                        baseUnitOfMeasureCode: item.baseUnitOfMeasureCode,
                        priceIncludesTax: item.priceIncludesTax,
                        imageUrl: `/item-image/${item.id}`,
                        defaultLocationCode: item.defaultLocationCode || item.locationCode || null
                    }))
                })
            });

            let data = null;
            try {
                data = await res.json();
            } catch (_) {
                data = null;
            }

            if (!res.ok) {
                throw new Error(data?.message || 'Sync failed.');
            }

            const syncedCount = data?.count ?? PRODUCTS.length;
            showSyncToast('success', 'Sync Successful', `${syncedCount} item(s) synced.`);
        } catch (error) {
            console.error(error);
            showSyncToast('error', 'Sync Failed', error?.message || 'Could not sync items.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = oldHtml;
        }
    }

    window.addEventListener('DOMContentLoaded', function () {
        const bounds = getInventoryBounds();
        const minSlider = document.getElementById('inventoryMinRange');
        const maxSlider = document.getElementById('inventoryMaxRange');
        const minInput = document.getElementById('minInventory');
        const maxInput = document.getElementById('maxInventory');

        minSlider.min = bounds.min;
        minSlider.max = bounds.max;
        maxSlider.min = bounds.min;
        maxSlider.max = bounds.max;

        minSlider.value = bounds.min;
        maxSlider.value = bounds.max;
        minInput.value = bounds.min;
        maxInput.value = bounds.max;

        buildCheckboxFilters();
        updateSliderRangeUI();
        renderItems();

        document.getElementById('searchInput').addEventListener('input', applyFilters);

        document.getElementById('filterBtn').addEventListener('click', function (e) {
            e.stopPropagation();
            toggleFilterPanel();
        });

        document.getElementById('filterPanel').addEventListener('click', function (e) {
            e.stopPropagation();
        });

        document.addEventListener('click', function () {
            closeFilterPanel();
        });

        minSlider.addEventListener('input', function () {
            if (Number(minSlider.value) > Number(maxSlider.value)) {
                minSlider.value = maxSlider.value;
            }
            syncRangeToInputs();
        });

        maxSlider.addEventListener('input', function () {
            if (Number(maxSlider.value) < Number(minSlider.value)) {
                maxSlider.value = minSlider.value;
            }
            syncRangeToInputs();
        });

        minInput.addEventListener('input', syncInputsToRange);
        maxInput.addEventListener('input', syncInputsToRange);
    });
</script>
@endsection
