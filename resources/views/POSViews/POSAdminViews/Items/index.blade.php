@extends('POSViews.POSAdminViews.app')

@section('title', 'POS System')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/POSViews/POSAdminViews/Items/index.css') }}">
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
            <a href="{{ route('store.management.tracking') }}" class="sync-btn sync-btn-alt">
                <i class="bi bi-activity"></i>
                Stock Tracking
            </a>

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
                        number: item.number || item.no || item.No || item.itemNo || item.itemNumber,
                        displayName: item.displayName || item.display_name || item.description || item.Description || item.name,
                        unitPrice: item.unitPrice ?? item.unit_price ?? item.price ?? item.UnitPrice ?? 0,
                        vatPercent: item.vatPercent ?? item.vat_percentage ?? item.vatpercent ?? 0,
                        taxAmount: item.taxAmount ?? item.tax_amount ?? item.taxamount ?? 0,
                        discountAmount: item.discountAmount ?? item.discount_amount ?? item.discountamount ?? 0,
                        discountStartDate: item.discountStartDate ?? item.discount_start_date ?? item.discountstartdate ?? null,
                        discountEndDate: item.discountEndDate ?? item.discount_end_date ?? item.discountenddate ?? null,
                        inventory: item.inventory ?? item.Inventory ?? item.quantityOnHand ?? item.qtyOnHand ?? 0,
                        blocked: item.blocked ?? item.Blocked ?? item.isBlocked ?? false,
                        itemCategoryCode: item.itemCategoryCode || item.item_category_code || item.categoryCode || item.CategoryCode,
                        baseUnitOfMeasureCode: item.baseUnitOfMeasureCode || item.base_unit_of_measure_code || item.unitOfMeasureCode,
                        priceIncludesTax: item.priceIncludesTax ?? item.price_includes_tax ?? false,
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
