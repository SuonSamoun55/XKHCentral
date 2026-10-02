@extends('Layout.POSAdmin.app')
@section('title', 'Web Shop')

@push('styles')
    <link rel="stylesheet"
        href="{{ asset('css/views/POSViews/POSAdminViews/Items/index.css') }}?v={{ @filemtime(public_path('css/views/POSViews/POSAdminViews/Items/index.css')) }}">
@endpush

@section('content')
    <main class="main-wrap">
        <h1 class="page-title">Web Shop</h1>

        <div class="toolbar-row">
            <div class="toolbar-left">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="Search">
                </div>

                <div class="filter-wrap">
                    <button type="button" class="filter-btn" id="filterBtn">
                        <span class="filter-btn-text">Filter</span>
                        <img src="{{ asset('images/AdminPOS/filter (1).png') }}" alt="Filter" class="filter-btn-icon">
                    </button>

                    <div class="filter-panel" id="filterPanel">
                        <div class="filter-panel-handle"></div>
                        <div class="filter-panel-head">
                            <div class="filter-title">Filter by</div>
                            <button type="button" class="filter-panel-close" id="filterPanelClose"
                                aria-label="Close filter">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>

                        <div class="filter-panel-body">
                            <div class="filter-section">
                                <div class="filter-section-header">
                                    <h6>Category</h6>
                                </div>
                                <div class="category-search-box">
                                    <i class="bi bi-search"></i>
                                    <input type="text" id="categorySearchInput" placeholder="Search category ...">
                                </div>
                                <div class="check-list" id="categoryCheckboxList"></div>
                                <div class="check-list-empty" id="categoryCheckboxEmpty" hidden>No matching categories.
                                </div>
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

                                    <input type="range" id="inventoryMinRange" class="range-input" min="0"
                                        max="200" value="0">
                                    <input type="range" id="inventoryMaxRange" class="range-input" min="0"
                                        max="200" value="200">
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
                        </div>

                        <div class="filter-actions">
                            <button type="button" class="filter-reset" onclick="resetFilters()">Reset</button>
                            <button type="button" class="filter-apply" onclick="applyFilters()">Apply</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="filter-backdrop" id="filterBackdrop"></div>

            <div class="toolbar-right">

                <div class="sync-actions-row">
                    <button id="pickAllBtn" type="button" class="sync-btn sync-btn-alt has-tip" onclick="togglePickAll()"
                        data-tip="Tick every shown item, so its picture is replaced with the latest one on the next sync.">
                        <i class="bi bi-check2-square" id="pickAllIcon"></i>
                        <span id="pickAllLabel">Select all</span>
                    </button>

                    <button id="syncBtn" type="button" class="sync-btn sync-btn-alt has-tip tip-right" onclick="updateItems()"
                        data-tip="Get the latest items, prices, stock, discounts and variants. Only ticked items get a new picture.">
                        <i class="bi bi-arrow-repeat"></i>
                        Sync Products
                    </button>
                </div>

                <div class="toolbar-secondary-row">
                    <div class="view-switch">
                        <button type="button" class="view-btn active" id="gridBtn" onclick="setView('grid')">
                            <i class="bi bi-grid-3x3-gap-fill"></i> <span class="view-btn-text">Grid</span>
                        </button>
                        <button type="button" class="view-btn" id="listBtn" onclick="setView('list')">
                            <i class="bi bi-list-ul"></i> <span class="view-btn-text">List</span>
                        </button>
                    </div>

                    <a href="{{ route('store.management.index') }}" class="sync-btn sync-btn-alt">
                        <i class="bi bi-shop"></i>
                        Manage Products
                    </a>
                </div>
            </div>
        </div>

        <div class="items-scroll">
            <div id="itemContainer" class="item-grid"></div>
        </div>
    </main>
    <div id="syncToastWrap" class="sync-toast-wrap"></div>

    {{-- Styled confirmation popup shared by "Sync products" and "Select all" (image replace) --}}
    <div class="sync-confirm-backdrop" id="syncConfirmBackdrop" hidden>
        <div class="sync-confirm" role="dialog" aria-modal="true" aria-labelledby="syncConfirmTitle">
            <div class="sync-confirm-icon"><i class="bi bi-arrow-repeat" id="syncConfirmIcon"></i></div>
            <h3 class="sync-confirm-title" id="syncConfirmTitle">Sync products?</h3>
            <p class="sync-confirm-text" id="syncConfirmText"></p>
            <div class="sync-confirm-note" id="syncConfirmNote"></div>
            <div class="sync-confirm-actions">
                <button type="button" class="sync-confirm-cancel" id="syncConfirmCancel">Cancel</button>
                <button type="button" class="sync-confirm-ok" id="syncConfirmOk">Yes, sync</button>
            </div>
        </div>
    </div>

    <script>
        const PRODUCTS = @json($items ?? []);
        // Built from route names so the page follows any URL change in routes/web.php.
        const ITEM_DETAIL_URL = id => @json(route('pos.items.detail', '__ID__')).replace('__ID__', id);
        const SYNC_URL = @json(route('pos.items.sync'));
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
            // Error messages (e.g. "number series is not set up") are longer,
            // so they stay up longer than a success toast.
            setTimeout(() => toast.remove(), type === 'success' ? 3500 : 8000);
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

            if (explicitDiscountPrice !== null && explicitDiscountPrice !== '' && Number(explicitDiscountPrice) <
                basePrice) {
                nowPrice = Number(explicitDiscountPrice);
                oldPrice = basePrice;
                discountPercent = Math.round(((oldPrice - nowPrice) / oldPrice) * 100);
            } else if (explicitOldPrice !== null && explicitOldPrice !== '' && Number(explicitOldPrice) > basePrice) {
                nowPrice = basePrice;
                oldPrice = Number(explicitOldPrice);
                discountPercent = explicitDiscountPercent > 0 ?
                    explicitDiscountPercent :
                    Math.round(((oldPrice - nowPrice) / oldPrice) * 100);
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

        // Filters the checkbox list itself by category name (not the product
        // grid) — same "search categories" behavior as the mobile user site,
        // for when there are a lot of categories to scroll through.
        function filterCategoryCheckboxList() {
            const input = document.getElementById('categorySearchInput');
            const emptyEl = document.getElementById('categoryCheckboxEmpty');
            const text = (input?.value || '').trim().toLowerCase();
            let visibleCount = 0;

            document.querySelectorAll('#categoryCheckboxList .check-item').forEach(row => {
                const label = row.querySelector('span')?.textContent?.trim().toLowerCase() || '';
                const matched = !text || label.includes(text);
                row.hidden = !matched;
                if (matched) visibleCount++;
            });

            if (emptyEl) emptyEl.hidden = visibleCount > 0;
        }

        function getCheckedValues(selector) {
            return [...document.querySelectorAll(selector + ':checked')].map(el => el.value);
        }

        function getInventoryBounds() {
            const inventories = PRODUCTS.map(getItemInventory);
            const min = inventories.length ? Math.min(...inventories) : 0;
            const max = inventories.length ? Math.max(...inventories) : 200;
            return {
                min,
                max: max > min ? max : min + 1
            };
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

                const matchKeyword = !keyword ||
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

            const categorySearchInput = document.getElementById('categorySearchInput');
            if (categorySearchInput) categorySearchInput.value = '';
            filterCategoryCheckboxList();

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

        // Items (by BC id) whose picture should be re-pulled from Business
        // Central on the next sync. Kept outside renderItems() so the choice
        // survives filtering and switching between grid/list view.
        const replaceImageSelection = new Set();

        function pickBox(item) {
            const id = String(item.id ?? '');
            if (!id) return '';
            return `
                <span class="pick-box" title="Replace this item's image on next sync"
                    onclick="event.preventDefault(); event.stopPropagation(); toggleReplacePick('${esc(id)}', this);">
                    <input type="checkbox" class="pick-check" data-id="${esc(id)}"
                        ${replaceImageSelection.has(id) ? 'checked' : ''} tabindex="-1">
                </span>`;
        }
        function toggleReplacePick(id, box) {
            const nowPicked = !replaceImageSelection.has(id);
            nowPicked ? replaceImageSelection.add(id) : replaceImageSelection.delete(id);

            const input = box?.querySelector('.pick-check');
            if (input) input.checked = nowPicked;

            updatePickSummary(nowPicked);
        }

        function updatePickSummary(mayBeAllPicked = true) {
            const btn = document.getElementById('pickAllBtn');
            const label = document.getElementById('pickAllLabel');
            if (!btn || !label) return;
            const allPicked = mayBeAllPicked
                && filteredProducts.length > 0
                && filteredProducts.every(item => replaceImageSelection.has(String(item.id ?? '')));

            btn.dataset.allPicked = allPicked ? '1' : '';
            btn.classList.toggle('active', replaceImageSelection.size > 0);

            const count = replaceImageSelection.size ? ` (${replaceImageSelection.size})` : '';
            label.textContent = (allPicked ? 'Clear' : 'Select all') + count;

            const icon = document.getElementById('pickAllIcon');
            if (icon) icon.className = allPicked ? 'bi bi-x-square' : 'bi bi-check2-square';
            btn.dataset.tip = allPicked
                ? `Untick all items. ${replaceImageSelection.size} item(s) are ticked for a new picture now.`
                : 'Tick every shown item, so its picture is replaced with the latest one on the next sync.';

            // Sync Products always works (a sync with nothing checked just
            // pulls data without replacing any images) — checking items only
            // recolors the button to show image replacement is armed.
            const syncBtn = document.getElementById('syncBtn');
            if (syncBtn) {
                syncBtn.classList.toggle('active', replaceImageSelection.size > 0);
            }
        }
        async function togglePickAll() {
            const select = document.getElementById('pickAllBtn')?.dataset.allPicked !== '1';

            if (select) {
                const count = filteredProducts.filter(item => item.id ?? '').length;
                if (!count || !await askPickAllConfirm(count)) return;
            }

            filteredProducts.forEach(item => {
                const id = String(item.id ?? '');
                if (!id) return;
                select ? replaceImageSelection.add(id) : replaceImageSelection.delete(id);
            });
            refreshPickUI();
        }

        function refreshPickUI() {
            document.querySelectorAll('.pick-check').forEach(el => {
                el.checked = replaceImageSelection.has(el.dataset.id);
            });
            updatePickSummary();
        }

        function renderItems() {
            const container = document.getElementById('itemContainer');

            if (!filteredProducts.length) {
                container.className = currentView === 'grid' ? 'item-grid' : 'item-list';
                container.innerHTML = `<div class="empty-box">No products found.</div>`;
                refreshPickUI();
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
                            ${pickBox(item)}
                            ${pricing.hasDiscount ? `<span class="sale-badge">SAVE ${pricing.discountPercent}%</span>` : ``}
                            <img
                                src="${esc(item.imageUrl || item.customImageUrl || '')}"
                                alt="${esc(name)}"
                                loading="lazy"
                                onerror="this.src='https://placehold.co/500x320/e5e7eb/94a3b8?text=No+Photo'">
                        </div>

                        <div class="product-body">
                            <div class="product-title">${esc(name)}</div>

                            <div class="price-row">
                                <div class="product-price">${money(pricing.nowPrice)}</div>
                                ${pricing.hasDiscount ? `<div class="old-price">${money(pricing.oldPrice)}</div>` : ``}
                                ${pricing.hasDiscount ? `<div class="discount-percent">${pricing.discountPercent}%</div>` : ``}
                            </div>

                            ${stockText(inventory)}

                            <a href="${ITEM_DETAIL_URL(item.id)}" class="view-more-btn">
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
                    <a href="${ITEM_DETAIL_URL(item.id)}" class="list-card">
                        ${pickBox(item)}
                        <div class="list-image">
                            <img
                                src="${esc(item.imageUrl || item.customImageUrl || '')}"
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
                    </a>
                `;
                }).join('');
            }

            refreshPickUI();
        }

        function toggleFilterPanel() {
            const open = document.getElementById('filterPanel').classList.toggle('show');
            document.getElementById('filterBackdrop')?.classList.toggle('show', open);
            document.body.classList.toggle('filter-panel-open', open);
            document.getElementById('filterBtn')?.classList.toggle('active', open);
        }

        function closeFilterPanel() {
            document.getElementById('filterPanel').classList.remove('show');
            document.getElementById('filterBackdrop')?.classList.remove('show');
            document.body.classList.remove('filter-panel-open');
            document.getElementById('filterBtn')?.classList.remove('active');
        }

        // Styled replacement for window.confirm(): resolves true on "Yes, sync",
        // false on Cancel / backdrop click / Esc.
        function askSyncConfirm(replaceCount) {
            return askConfirm({
                icon: 'bi bi-arrow-repeat',
                title: 'Sync products?',
                text: 'Are you sure you want to sync products?',
                note: replaceCount > 0
                    ? { className: 'sync-confirm-note replace', html: `<i class="bi bi-image"></i> The image of <strong>${replaceCount}</strong> selected item(s) will be <strong>replaced</strong> with the latest picture. All other items keep their current image.` }
                    : { className: 'sync-confirm-note', html: `<i class="bi bi-info-circle"></i> No items are selected, so all existing item images will be kept.` },
                okLabel: 'Yes, sync',
            });
        }

        // Confirmation asked before "Select all" marks every shown item's
        // image to be overwritten on the next sync — easy to click by
        // mistake given how many items that can affect at once.
        function askPickAllConfirm(count) {
            return askConfirm({
                icon: 'bi bi-image',
                title: 'Replace images for all items?',
                text: 'Are you sure you want to allow image replacement for all shown items?',
                note: {
                    className: 'sync-confirm-note replace',
                    html: `<i class="bi bi-image"></i> On the next sync, <strong>${count}</strong> item(s) will have their image <strong>replaced</strong> with the latest picture.`,
                },
                okLabel: 'Yes, select all',
            });
        }

        // Shared engine behind askSyncConfirm / askPickAllConfirm: resolves
        // true on "Yes", false on Cancel / backdrop click / Esc.
        function askConfirm({ icon, title, text, note, okLabel }) {
            const backdrop = document.getElementById('syncConfirmBackdrop');
            const iconEl = document.getElementById('syncConfirmIcon');
            const titleEl = document.getElementById('syncConfirmTitle');
            const textEl = document.getElementById('syncConfirmText');
            const noteEl = document.getElementById('syncConfirmNote');
            const okBtn = document.getElementById('syncConfirmOk');
            const cancelBtn = document.getElementById('syncConfirmCancel');

            iconEl.className = icon;
            titleEl.textContent = title;
            textEl.textContent = text;
            noteEl.className = note.className;
            noteEl.innerHTML = note.html;
            okBtn.textContent = okLabel;

            backdrop.hidden = false;
            okBtn.focus();

            return new Promise(resolve => {
                const finish = (result) => {
                    backdrop.hidden = true;
                    okBtn.removeEventListener('click', onOk);
                    cancelBtn.removeEventListener('click', onCancel);
                    backdrop.removeEventListener('click', onBackdrop);
                    document.removeEventListener('keydown', onKey);
                    resolve(result);
                };
                const onOk = () => finish(true);
                const onCancel = () => finish(false);
                const onBackdrop = (e) => { if (e.target === backdrop) finish(false); };
                const onKey = (e) => { if (e.key === 'Escape') finish(false); };

                okBtn.addEventListener('click', onOk);
                cancelBtn.addEventListener('click', onCancel);
                backdrop.addEventListener('click', onBackdrop);
                document.addEventListener('keydown', onKey);
            });
        }

        async function updateItems() {
            const btn = document.getElementById('syncBtn');
            const oldHtml = btn.innerHTML;
            const replaceImageIds = [...replaceImageSelection];

            const confirmed = await askSyncConfirm(replaceImageIds.length);
            if (!confirmed) {
                return;
            }

            btn.disabled = true;
            btn.innerHTML = `<i class="bi bi-arrow-repeat"></i> Syncing...`;

            try {
                // The server fetches from Business Central itself and saves the
                // result — the browser no longer talks to BC directly.
                const res = await fetch(SYNC_URL, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ replace_image_ids: replaceImageIds })
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

                const syncedCount = data?.count ?? 0;
                const variantsSaved = data?.variantsSaved ?? 0;
                const variantsSkipped = data?.variantsSkipped ?? 0;
                const variantsError = data?.variantsError ?? null;

                if (variantsError) {
                    showSyncToast('error', 'Items Synced, Variants Failed',
                        `${syncedCount} item(s) synced. Variants: ${variantsError}`);
                } else {
                    let variantMsg = `${variantsSaved} variant(s) saved`;
                    if (variantsSkipped) {
                        variantMsg += `, ${variantsSkipped} skipped`;
                    }
                    showSyncToast('success', 'Sync Successful', `${syncedCount} item(s) synced. ${variantMsg}.`);
                }

                // Reload so the freshly synced items (and photos) show up.
                setTimeout(() => window.location.reload(), 1200);
            } catch (error) {
                console.error(error);
                showSyncToast('error', 'Sync Failed', error?.message || 'Could not sync items.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = oldHtml;
            }
        }

        window.addEventListener('DOMContentLoaded', function() {
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

            document.getElementById('filterBtn').addEventListener('click', function(e) {
                e.stopPropagation();
                toggleFilterPanel();
            });

            document.getElementById('filterPanel').addEventListener('click', function(e) {
                e.stopPropagation();
            });

            document.getElementById('filterPanelClose')?.addEventListener('click', closeFilterPanel);
            document.getElementById('filterBackdrop')?.addEventListener('click', closeFilterPanel);
            document.getElementById('categorySearchInput')?.addEventListener('input', filterCategoryCheckboxList);

            document.addEventListener('click', function() {
                closeFilterPanel();
            });

            minSlider.addEventListener('input', function() {
                if (Number(minSlider.value) > Number(maxSlider.value)) {
                    minSlider.value = maxSlider.value;
                }
                syncRangeToInputs();
            });

            maxSlider.addEventListener('input', function() {
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
