@extends('Layout.POSAdmin.app')
@section('title', 'Store Management')
@section('content')
    <div class="store-page-wrap">
        <div class="store-panel">
            <div id="storeFlashBox" class="alert-container"></div>
            <div id="storeAjaxContainer">
                @include('POSViews.POSAdminViews.StoreManagement.content')
            </div>
        </div>
    </div>
    <div class="modal fade export-columns-modal" id="exportStoreModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content export-columns-content">
                <div class="modal-body">
                    <h5 class="export-columns-title">Choose columns to export</h5>
                    <p class="export-columns-sub" id="exportStoreSub">Downloads whatever is currently visible — respects
                        your search and status filters.</p>

                    <label class="export-column-row export-column-all">
                        <input type="checkbox" id="exportStoreSelectAll" checked>
                        <span>Select all</span>
                    </label>

                    <div class="export-column-list" id="exportProductColumns">
                        <label class="export-column-row">
                            <input type="checkbox" class="export-column-checkbox" value="series" checked>
                            <span>Number Series</span>
                        </label>
                        <label class="export-column-row">
                            <input type="checkbox" class="export-column-checkbox" value="number" checked>
                            <span>Item No.</span>
                        </label>
                        <label class="export-column-row">
                            <input type="checkbox" class="export-column-checkbox" value="name" checked>
                            <span>Product</span>
                        </label>
                        <label class="export-column-row">
                            <input type="checkbox" class="export-column-checkbox" value="category" checked>
                            <span>Category</span>
                        </label>
                        <label class="export-column-row">
                            <input type="checkbox" class="export-column-checkbox" value="price" checked>
                            <span>Price</span>
                        </label>
                        <label class="export-column-row">
                            <input type="checkbox" class="export-column-checkbox" value="stock" checked>
                            <span>Stock</span>
                        </label>
                        <label class="export-column-row">
                            <input type="checkbox" class="export-column-checkbox" value="location" checked>
                            <span>Location</span>
                        </label>
                        <label class="export-column-row">
                            <input type="checkbox" class="export-column-checkbox" value="visible" checked>
                            <span>Visible</span>
                        </label>
                    </div>

                    <div class="export-column-list d-none" id="exportCategoryColumns">
                        <label class="export-column-row">
                            <input type="checkbox" class="export-column-checkbox" value="category" checked>
                            <span>Category</span>
                        </label>
                        <label class="export-column-row">
                            <input type="checkbox" class="export-column-checkbox" value="total_items" checked>
                            <span>Total Items</span>
                        </label>
                        <label class="export-column-row">
                            <input type="checkbox" class="export-column-checkbox" value="visible" checked>
                            <span>Visible</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer export-columns-footer">
                    <button type="button" class="export-cancel-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="export-confirm-btn" id="exportStoreConfirmBtn">
                        <i class="bi bi-download"></i> Download
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

<link rel="stylesheet"
    href="{{ asset('css/views/POSViews/POSAdminViews/StoreManagement/index.css') }}?v={{ @filemtime(public_path('css/views/POSViews/POSAdminViews/StoreManagement/index.css')) }}">
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ajaxContainer = document.getElementById('storeAjaxContainer');
        const flashBox = document.getElementById('storeFlashBox');

        let activeTab = 'products';
        let currentProductPage = 1;
        let currentCategoryPage = 1;
        let storeMenuOutsideBound = false;

        function showMessage(message, type = 'success') {
            flashBox.innerHTML = `
            <div class="custom-alert ${type === 'success' ? 'alert-success' : 'alert-danger'}">
                <i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'}"></i>
                <span>${message}</span>
            </div>
        `;

            const alertEl = flashBox.querySelector('.custom-alert');

            setTimeout(() => {
                if (!alertEl || !flashBox.contains(alertEl)) return;
                let cleared = false;
                const clear = () => {
                    if (cleared) return;
                    cleared = true;
                    if (flashBox.contains(alertEl)) flashBox.innerHTML = '';
                };

                alertEl.classList.add('fade-out');
                alertEl.addEventListener('transitionend', clear, {
                    once: true
                });
                setTimeout(clear, 350);
            }, 2200);
        }

        function fixAjaxTabLayout() {
            const productsTab = document.getElementById('productsTabContent');
            const categoriesTab = document.getElementById('categoriesTabContent');

            [productsTab, categoriesTab].forEach(tab => {
                if (!tab) return;

                if (tab.querySelector(':scope > .store-body-area')) return;

                const footer = tab.querySelector(':scope > .store-footer-bar');
                const bodyItems = Array.from(tab.children).filter(el => !el.classList.contains(
                    'store-footer-bar'));

                const bodyArea = document.createElement('div');
                bodyArea.className = 'store-body-area';

                bodyItems.forEach(el => bodyArea.appendChild(el));

                tab.innerHTML = '';
                tab.appendChild(bodyArea);

                if (footer) {
                    tab.appendChild(footer);
                }
            });

            const productTableWrap = document.querySelector('#productsTabContent .table-scroll-wrap');
            if (productTableWrap && !productTableWrap.parentElement.classList.contains('store-table-scroll')) {
                const tableScroll = document.createElement('div');
                tableScroll.className = 'store-table-scroll';
                productTableWrap.parentNode.insertBefore(tableScroll, productTableWrap);
                tableScroll.appendChild(productTableWrap);
            }
        }

        function getInnerScrollWrap() {
            return activeTab === 'products' ?
                document.querySelector('#productsTabContent .table-scroll-wrap') :
                document.querySelector('#categoriesTabContent .category-list-grid');
        }

        function captureUiState() {
            return {
                scrollY: window.scrollY,
                innerScrollTop: getInnerScrollWrap()?.scrollTop || 0,
                search: document.getElementById('storeSearchInput')?.value || '',
                status: document.getElementById('storeStatusFilter')?.value || 'all',
                stock: document.getElementById('storeStockFilter')?.value || 'all',
                setup: document.getElementById('storeSetupFilter')?.value || 'all',
                perPageProduct: document.getElementById('storePerPage')?.value || '10',
                perPageCategory: document.getElementById('storePerPageCategory')?.value || '10',
                selectedProductIds: getSelectedProductIds(),
                selectedCategoryCodes: getSelectedCategoryCodes()
            };
        }

        function restoreUiState(state) {
            const searchInput = document.getElementById('storeSearchInput');
            if (searchInput) searchInput.value = state.search;

            const statusFilter = document.getElementById('storeStatusFilter');
            if (statusFilter) statusFilter.value = state.status;

            const stockFilter = document.getElementById('storeStockFilter');
            if (stockFilter) stockFilter.value = state.stock;

            const setupFilter = document.getElementById('storeSetupFilter');
            if (setupFilter) setupFilter.value = state.setup;

            const perPageProduct = document.getElementById('storePerPage');
            if (perPageProduct) perPageProduct.value = state.perPageProduct;

            const perPageCategory = document.getElementById('storePerPageCategory');
            if (perPageCategory) perPageCategory.value = state.perPageCategory;

            document.querySelectorAll('.product-checkbox').forEach(cb => {
                cb.checked = state.selectedProductIds.includes(cb.value);
            });

            document.querySelectorAll('.category-checkbox').forEach(cb => {
                cb.checked = state.selectedCategoryCodes.includes(cb.value);
            });
        }

        async function fetchPage({
            preserveState = false
        } = {}) {
            const savedState = preserveState ? captureUiState() : null;

            try {
                const response = await fetch(window.location.href, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Load failed');

                const data = await response.json();

                if (!data.success || !data.html) {
                    throw new Error('Invalid response');
                }

                ajaxContainer.innerHTML = data.html;
                fixAjaxTabLayout();

                if (savedState) {
                    restoreUiState(savedState);
                }

                bindClientFiltering();
                bindMenuToggle();
                updateSelectedCounts();
                switchTab(activeTab);

                if (savedState) {
                    requestAnimationFrame(() => {
                        window.scrollTo(0, savedState.scrollY);
                        const wrap = getInnerScrollWrap();
                        if (wrap) wrap.scrollTop = savedState.innerScrollTop;
                    });
                }
            } catch (error) {
                showMessage('Failed to load data.', 'error');
            }
        }

        async function postJson(url, payload = {}) {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            return await response.json();
        }

        function getSelectedProductIds() {
            return Array.from(document.querySelectorAll('.product-checkbox:checked')).map(cb => cb.value);
        }

        function getSelectedCategoryCodes() {
            return Array.from(document.querySelectorAll('.category-checkbox:checked')).map(cb => cb.value);
        }

        function updateSelectedCounts() {
            const visibleProductCheckboxes = Array.from(document.querySelectorAll('.product-checkbox'))
                .filter(cb => {
                    const row = cb.closest('.product-row');
                    return row && row.style.display !== 'none';
                });

            const checkedVisibleProducts = visibleProductCheckboxes.filter(cb => cb.checked);

            const visibleCategoryCheckboxes = Array.from(document.querySelectorAll('.category-checkbox'))
                .filter(cb => {
                    const card = cb.closest('.category-card, .category-item-card');
                    return card && card.style.display !== 'none';
                });

            const checkedVisibleCategories = visibleCategoryCheckboxes.filter(cb => cb.checked);

            document.querySelectorAll('.js-selected-product-count').forEach(el => {
                el.textContent = checkedVisibleProducts.length;
            });

            document.querySelectorAll('.js-selected-category-count').forEach(el => {
                el.textContent = checkedVisibleCategories.length;
            });

            // The shared "Select all" (desktop table header + phone inline row)
            // reflects whichever tab is currently active.
            const activeVisible = activeTab === 'products' ? visibleProductCheckboxes :
                visibleCategoryCheckboxes;
            const activeChecked = activeTab === 'products' ? checkedVisibleProducts : checkedVisibleCategories;

            document.querySelectorAll('.js-select-all-products').forEach(selectAll => {
                if (activeVisible.length === 0) {
                    selectAll.checked = false;
                    selectAll.indeterminate = false;
                } else {
                    selectAll.checked = activeChecked.length === activeVisible.length;
                    selectAll.indeterminate =
                        activeChecked.length > 0 &&
                        activeChecked.length < activeVisible.length;
                }
            });

            // Phone-only inline "Select all" row — stays out of the way in the
            // toolbar until at least one row is actually checked, instead of
            // always sitting there before there's anything to bulk-act on.
            document.querySelectorAll('.store-select-all-inline').forEach(wrap => {
                wrap.classList.toggle('has-selection', activeChecked.length > 0);
            });
        }

        function createPagination(container, totalPages, currentPage, onPageClick) {
            container.innerHTML = '';

            if (totalPages <= 1) return;

            const makeBtn = (label, page, options = {}) => {
                const btn = document.createElement('button');
                btn.className = 'custom-page-btn';

                if (options.active) btn.classList.add('active');
                if (options.ellipsis) btn.classList.add('pagination-ellipsis');

                btn.textContent = label;

                if (options.disabled || options.ellipsis) {
                    btn.disabled = true;
                } else {
                    btn.addEventListener('click', function() {
                        onPageClick(page);
                    });
                }

                return btn;
            };

            container.appendChild(
                makeBtn('Previous', currentPage - 1, {
                    disabled: currentPage === 1
                })
            );

            const pages = [];

            if (totalPages <= 7) {
                for (let i = 1; i <= totalPages; i++) {
                    pages.push(i);
                }
            } else {
                pages.push(1);

                let start = Math.max(2, currentPage - 1);
                let end = Math.min(totalPages - 1, currentPage + 1);

                if (currentPage <= 3) {
                    start = 2;
                    end = 5;
                }

                if (currentPage >= totalPages - 2) {
                    start = totalPages - 4;
                    end = totalPages - 1;
                }

                if (start > 2) pages.push('...');

                for (let i = start; i <= end; i++) {
                    pages.push(i);
                }

                if (end < totalPages - 1) pages.push('...');

                pages.push(totalPages);
            }

            pages.forEach(item => {
                if (item === '...') {
                    container.appendChild(makeBtn('...', null, {
                        ellipsis: true
                    }));
                } else {
                    container.appendChild(makeBtn(String(item), item, {
                        active: item === currentPage
                    }));
                }
            });

            container.appendChild(
                makeBtn('Next', currentPage + 1, {
                    disabled: currentPage === totalPages
                })
            );
        }

        function updateShowingText(type, total, shown) {
            if (type === 'products') {
                const el = document.getElementById('productShowingText');
                if (el) el.textContent = `Showing ${shown} of ${total} products`;
            } else {
                const el = document.getElementById('categoryShowingText');
                if (el) el.textContent = `Showing ${shown} of ${total} categories`;
            }
        }

        function filterProducts() {
            const keyword = (document.getElementById('storeSearchInput')?.value || '').toLowerCase().trim();
            const status = document.getElementById('storeStatusFilter')?.value || 'all';
            const stock = document.getElementById('storeStockFilter')?.value || 'all';
            const setup = document.getElementById('storeSetupFilter')?.value || 'all';
            const perPage = parseInt(document.getElementById('storePerPage')?.value || '10', 10);

            const rows = Array.from(document.querySelectorAll('.product-row'));
            let matched = [];

            rows.forEach(row => {
                const text = [
                    (row.dataset.name || '').toLowerCase(),
                    (row.dataset.number || '').toLowerCase(),
                    (row.dataset.category || '').toLowerCase()
                ].join(' ');

                const rowStatus = row.dataset.status || 'inactive';
                const rowStock = row.dataset.stock || 'out';
                const rowSetup = row.dataset.setup || 'incomplete';

                const matchKeyword = !keyword || text.includes(keyword);
                const matchStatus = status === 'all' || rowStatus === status;
                const matchStock = stock === 'all' || rowStock === stock;
                const matchSetup = setup === 'all' || rowSetup === setup;

                row.style.display = 'none';

                if (matchKeyword && matchStatus && matchStock && matchSetup) {
                    matched.push(row);
                }
            });

            const totalPages = Math.max(1, Math.ceil(matched.length / perPage));
            if (currentProductPage > totalPages) currentProductPage = 1;

            const start = (currentProductPage - 1) * perPage;
            const end = start + perPage;
            const visibleRows = matched.slice(start, end);

            visibleRows.forEach(row => {
                row.style.display = '';
            });

            const noRow = document.getElementById('noProductRow');
            if (noRow) {
                noRow.style.display = matched.length ? 'none' : '';
            }

            const pagination = document.getElementById('productPagination');
            if (pagination) {
                createPagination(pagination, totalPages, currentProductPage, function(page) {
                    currentProductPage = page;
                    filterProducts();
                });
            }

            updateShowingText('products', matched.length, visibleRows.length);
            updateSelectedCounts();
        }

        function filterCategories() {
            const keyword = (document.getElementById('storeSearchInput')?.value || '').toLowerCase().trim();
            const status = document.getElementById('storeStatusFilter')?.value || 'all';
            const perPage = parseInt(document.getElementById('storePerPageCategory')?.value || '10', 10);

            const cards = Array.from(document.querySelectorAll('.category-card'));
            let matched = [];

            cards.forEach(card => {
                const text = (card.dataset.name || '').toLowerCase();
                const cardStatus = card.dataset.status || 'inactive';
                const matchKeyword = !keyword || text.includes(keyword);
                const matchStatus = status === 'all' || cardStatus === status;

                card.style.display = 'none';

                if (matchKeyword && matchStatus) {
                    matched.push(card);
                }
            });

            const totalPages = Math.max(1, Math.ceil(matched.length / perPage));
            if (currentCategoryPage > totalPages) currentCategoryPage = 1;

            const start = (currentCategoryPage - 1) * perPage;
            const end = start + perPage;
            const visibleCards = matched.slice(start, end);

            visibleCards.forEach(card => {
                card.style.display = '';
            });

            const noCard = document.getElementById('noCategoryCard');
            if (noCard) {
                noCard.style.display = matched.length ? 'none' : '';
            }

            const pagination = document.getElementById('categoryPagination');
            if (pagination) {
                createPagination(pagination, totalPages, currentCategoryPage, function(page) {
                    currentCategoryPage = page;
                    filterCategories();
                });
            }

            updateShowingText('categories', matched.length, visibleCards.length);
            updateSelectedCounts();
        }

        function runCurrentTabFilter() {
            if (activeTab === 'products') {
                filterProducts();
            } else {
                filterCategories();
            }
        }

        function switchTab(tab) {
            const isTabChange = tab !== activeTab;
            activeTab = tab;

            document.querySelectorAll('.js-store-tab').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.tab === tab);
            });

            document.getElementById('productsTabContent')?.classList.toggle('d-none', tab !== 'products');
            document.getElementById('categoriesTabContent')?.classList.toggle('d-none', tab !== 'categories');

            document.querySelectorAll('.products-only-btn').forEach(btn => {
                btn.classList.toggle('d-none', tab !== 'products');
            });

            document.querySelectorAll('.categories-only-btn').forEach(btn => {
                btn.classList.toggle('d-none', tab !== 'categories');
            });

            const stockWrap = document.querySelector('.stock-filter-wrap');
            if (stockWrap) {
                stockWrap.classList.toggle('d-none', tab !== 'products');
            }

            const setupWrap = document.querySelector('.setup-filter-wrap');
            if (setupWrap) {
                setupWrap.classList.toggle('d-none', tab !== 'products');
            }

            if (isTabChange) {
                currentProductPage = 1;
                currentCategoryPage = 1;
            }

            runCurrentTabFilter();
        }

        function bindClientFiltering() {
            const searchInput = document.getElementById('storeSearchInput');
            const statusFilter = document.getElementById('storeStatusFilter');
            const stockFilter = document.getElementById('storeStockFilter');
            const setupFilter = document.getElementById('storeSetupFilter');
            const perPageProduct = document.getElementById('storePerPage');
            const perPageCategory = document.getElementById('storePerPageCategory');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    if (activeTab === 'products') currentProductPage = 1;
                    if (activeTab === 'categories') currentCategoryPage = 1;
                    runCurrentTabFilter();
                });
            }

            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    if (activeTab === 'products') currentProductPage = 1;
                    if (activeTab === 'categories') currentCategoryPage = 1;
                    runCurrentTabFilter();
                });
            }

            if (stockFilter) {
                stockFilter.addEventListener('change', function() {
                    currentProductPage = 1;
                    filterProducts();
                });
            }

            if (setupFilter) {
                setupFilter.addEventListener('change', function() {
                    currentProductPage = 1;
                    filterProducts();
                });
            }

            if (perPageProduct) {
                perPageProduct.addEventListener('change', function() {
                    currentProductPage = 1;
                    filterProducts();
                });
            }

            if (perPageCategory) {
                perPageCategory.addEventListener('change', function() {
                    currentCategoryPage = 1;
                    filterCategories();
                });
            }

            document.querySelectorAll('.js-store-tab').forEach(btn => {
                btn.addEventListener('click', function() {
                    switchTab(this.dataset.tab);
                });
            });

            const sellingLocation = document.getElementById('storeSellingLocation');
            if (sellingLocation) {
                sellingLocation.addEventListener('change', async function() {
                    this.disabled = true;
                    try {
                        const data = await postJson(
                            '{{ route('store.management.sellingLocation.update') }}', {
                                location_code: this.value
                            });

                        if (!data.success) {
                            throw new Error(data.message || 'Failed to update selling location.');
                        }

                        showMessage('Selling location updated.');
                        await fetchPage({
                            preserveState: true
                        });
                    } catch (error) {
                        showMessage('Failed to update selling location.', 'error');
                        this.disabled = false;
                    }
                });
            }

            runCurrentTabFilter();
        }

        function bindMenuToggle() {
            const trigger = document.getElementById('storeMenuTrigger');
            const panel = document.getElementById('storeMenuPanel');
            const wrap = document.getElementById('storeMenuWrap');

            if (!trigger || !panel || !wrap) return;

            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                panel.classList.toggle('d-none');
            });

            if (!storeMenuOutsideBound) {
                document.addEventListener('click', function(e) {
                    const currentWrap = document.getElementById('storeMenuWrap');
                    const currentPanel = document.getElementById('storeMenuPanel');
                    if (!currentWrap || !currentPanel) return;
                    if (!currentWrap.contains(e.target)) {
                        currentPanel.classList.add('d-none');
                    }
                });
                storeMenuOutsideBound = true;
            }
        }

        // ── Visible / Oversell dropdowns (desktop) ──
        // The phone card still uses the on/off switches; every path that
        // changes a value goes through these two helpers so the dropdowns,
        // switches and the row's filter data never disagree.
        // isVisible: true = Visible, false = Hidden, null = Not reviewed (grey).
        function setVisibleState(row, isVisible) {
            if (!row) return;
            const notReviewed = isVisible === null || isVisible === undefined;
            const state = notReviewed ? 'not-setup' : (isVisible ? 'on' : 'off');

            row.dataset.status = notReviewed ? 'not_setup' : (isVisible ? 'active' : 'inactive');

            row.querySelectorAll('.js-toggle-product').forEach(btn => {
                btn.classList.toggle('not-setup', notReviewed);
                btn.classList.toggle('on', state === 'on');
                btn.classList.toggle('off', state === 'off');
            });

            row.querySelectorAll('.js-select-visible').forEach(select => {
                select.value = notReviewed ? '' : (isVisible ? '1' : '0');
                const pill = select.closest('.pill-select');
                if (pill) pill.dataset.state = state;
            });
        }

        function setOversellState(row, allowOversell, fallbackBtn) {
            const title = allowOversell ?
                'Customers can buy this out of stock' :
                'Hidden from customers once out of stock';

            (row ? row.querySelectorAll('.js-toggle-oversell') : [fallbackBtn]).forEach(btn => {
                if (!btn) return;
                btn.classList.toggle('on', allowOversell);
                btn.classList.toggle('off', !allowOversell);
                btn.title = title;
            });

            row?.querySelectorAll('.js-select-oversell').forEach(select => {
                select.value = allowOversell ? '1' : '0';
                const pill = select.closest('.pill-select');
                if (pill) pill.dataset.state = allowOversell ? 'on' : 'off';
            });
        }

        // ── Row "⋯" actions menu (desktop) ──
        // Fixed-position so the table's scroll area can't clip it.
        function closeRowActions() {
            document.querySelectorAll('.row-actions-panel:not(.d-none)').forEach(panel => {
                panel.classList.add('d-none');
            });
        }

        function toggleRowActions(trigger) {
            const panel = trigger.closest('.row-actions')?.querySelector('.row-actions-panel');
            if (!panel) return;

            const wasOpen = !panel.classList.contains('d-none');
            closeRowActions();
            if (wasOpen) return;

            panel.classList.remove('d-none');

            const rect = trigger.getBoundingClientRect();
            const panelRect = panel.getBoundingClientRect();
            const fitsBelow = rect.bottom + 6 + panelRect.height <= window.innerHeight - 8;

            panel.style.top = (fitsBelow ? rect.bottom + 6 : Math.max(8, rect.top - 6 - panelRect.height)) + 'px';
            panel.style.left = Math.max(8, Math.min(rect.left, window.innerWidth - panelRect.width - 8)) + 'px';
        }

        window.addEventListener('resize', closeRowActions);
        document.addEventListener('scroll', closeRowActions, true);

        document.addEventListener('change', async function(e) {
            const visibleSelect = e.target.closest?.('.js-select-visible');
            const oversellSelect = e.target.closest?.('.js-select-oversell');
            const select = visibleSelect || oversellSelect;
            if (!select) return;

            const pill = select.closest('.pill-select');
            const row = select.closest('.product-row');
            const previous = pill?.dataset.state === 'on' ? '1' : pill?.dataset.state === 'off' ? '0' : '';

            pill?.classList.add('loading');
            select.disabled = true;

            try {
                const result = await postJson(select.dataset.url, visibleSelect ? {
                    // '' = Not reviewed -> null on the server
                    is_visible: select.value === '' ? null : select.value === '1'
                } : {
                    allow_oversell: select.value === '1'
                });

                if (!result.success) {
                    throw new Error(result.message || 'Failed to update product.');
                }

                if (visibleSelect) {
                    setVisibleState(row, result.is_visible);
                    runCurrentTabFilter();
                } else {
                    setOversellState(row, !!result.allow_oversell);
                }

                showMessage(result.message || 'Updated successfully.');
            } catch (error) {
                select.value = previous;
                showMessage(error.message || 'Failed to update product.', 'error');
            } finally {
                select.disabled = false;
                pill?.classList.remove('loading');
            }
        });

        document.addEventListener('click', async function(e) {

            const rowActionsTrigger = e.target.closest('.js-row-actions-trigger');
            if (rowActionsTrigger) {
                e.preventDefault();
                e.stopPropagation();
                toggleRowActions(rowActionsTrigger);
                return;
            }
            if (!e.target.closest('.row-actions-panel')) {
                closeRowActions();
            }

            const categoryToggle = e.target.closest('.js-toggle-category');
            if (categoryToggle) {
                e.preventDefault();

                if (categoryToggle.classList.contains('loading')) return;
                categoryToggle.classList.add('loading');

                try {
                    const result = await postJson(categoryToggle.dataset.url);

                    if (!result.success) {
                        showMessage(result.message || 'Failed to update category.', 'error');
                        return;
                    }

                    const card = categoryToggle.closest('.category-card');
                    if (card) {
                        card.dataset.status = result.is_visible ? 'active' : 'inactive';
                    }

                    categoryToggle.classList.toggle('on', result.is_visible);
                    categoryToggle.classList.toggle('off', !result.is_visible);

                    showMessage(result.message || 'Updated successfully.');
                    runCurrentTabFilter();
                } catch (error) {
                    showMessage('Failed to update category.', 'error');
                } finally {
                    categoryToggle.classList.remove('loading');
                }
                return;
            }

            const bulkActionBtn = e.target.closest('.js-bulk-action');
            if (bulkActionBtn) {
                e.preventDefault();

                const scope = bulkActionBtn.dataset.scope;
                const action = bulkActionBtn.dataset.action;
                const isProduct = scope === 'product';
                const values = isProduct ? getSelectedProductIds() : getSelectedCategoryCodes();

                if (!values.length) {
                    return showMessage(`Please select at least one ${scope}.`, 'error');
                }

                const payload = isProduct ?
                    {
                        ids: values,
                        action
                    } :
                    {
                        codes: values,
                        action
                    };

                try {
                    const result = await postJson(bulkActionBtn.dataset.url, payload);
                    if (!result.success) return showMessage(result.message || 'Failed to update.',
                        'error');
                    showMessage(result.message || 'Updated successfully.');
                    fetchPage({
                        preserveState: true
                    });
                } catch {
                    showMessage('Failed to update.', 'error');
                }
                return;
            }

            const bulkOversellBtn = e.target.closest('.js-bulk-oversell');
            if (bulkOversellBtn) {
                e.preventDefault();

                if (bulkOversellBtn.classList.contains('loading')) return;
                bulkOversellBtn.classList.add('loading');

                const action = bulkOversellBtn.dataset.action;

                try {
                    const result = await postJson(bulkOversellBtn.dataset.url, {
                        action
                    });
                    if (!result.success) {
                        showMessage(result.message || 'Failed to update.', 'error');
                        return;
                    }
                    showMessage(result.message || 'Updated successfully.');
                    fetchPage({
                        preserveState: true
                    });
                } catch {
                    showMessage('Failed to update.', 'error');
                } finally {
                    bulkOversellBtn.classList.remove('loading');
                }
                return;
            }

            const productRow = e.target.closest('.product-row');
            if (productRow && window.innerWidth <= 768) {
                if (e.target.closest('input') || e.target.closest('select') || e.target.closest('button') || e.target.closest(
                    'a')) {
                    return;
                }
                const href = productRow.getAttribute('data-href');
                if (href) {
                    window.location.href = href;
                }
            }
        });

        document.addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('js-select-all-products')) {
                if (activeTab === 'products') {
                    document.querySelectorAll('.product-checkbox').forEach(cb => {
                        const row = cb.closest('.product-row');
                        if (row && row.style.display !== 'none') {
                            cb.checked = e.target.checked;
                        }
                    });
                } else {
                    document.querySelectorAll('.category-checkbox').forEach(cb => {
                        const card = cb.closest('.category-card, .category-item-card');
                        if (card && card.style.display !== 'none') {
                            cb.checked = e.target.checked;
                        }
                    });
                }
                updateSelectedCounts();
                return;
            }

            if (
                e.target.classList.contains('product-checkbox') ||
                e.target.classList.contains('category-checkbox')
            ) {
                updateSelectedCounts();
            }
        });
        /* ── Export to CSV/Excel — modal lives outside #storeAjaxContainer, so
           it's bound once here rather than re-bound on every AJAX refresh. */
        function getVisibleExportColumnList() {
            return activeTab === 'products' ?
                document.getElementById('exportProductColumns') :
                document.getElementById('exportCategoryColumns');
        }

        function syncExportSelectAll() {
            const list = getVisibleExportColumnList();
            const selectAll = document.getElementById('exportStoreSelectAll');
            if (!list || !selectAll) return;
            const boxes = Array.from(list.querySelectorAll('.export-column-checkbox'));
            selectAll.checked = boxes.every(cb => cb.checked);
            selectAll.indeterminate = !selectAll.checked && boxes.some(cb => cb.checked);
        }

        document.getElementById('exportStoreModal')?.addEventListener('show.bs.modal', function() {
            const isProducts = activeTab === 'products';
            document.getElementById('exportProductColumns')?.classList.toggle('d-none', !isProducts);
            document.getElementById('exportCategoryColumns')?.classList.toggle('d-none', isProducts);
            syncExportSelectAll();
        });

        document.getElementById('exportStoreSelectAll')?.addEventListener('change', function() {
            getVisibleExportColumnList()?.querySelectorAll('.export-column-checkbox').forEach(cb => cb
                .checked = this.checked);
        });

        document.addEventListener('change', function(e) {
            if (e.target.classList && e.target.classList.contains('export-column-checkbox')) {
                syncExportSelectAll();
            }
        });

        function csvCell(value) {
            const s = String(value ?? '');
            return /[",\n]/.test(s) ? '"' + s.replace(/"/g, '""') + '"' : s;
        }

        const EXPORT_PRODUCT_LABELS = {
            number: 'Item No.',
            name: 'Product',
            series: 'Number Series',
            category: 'Category',
            price: 'Price',
            stock: 'Stock',
            location: 'Location',
            visible: 'Visible',
        };

        const EXPORT_CATEGORY_LABELS = {
            category: 'Category',
            total_items: 'Total Items',
            visible: 'Visible',
        };

        // Reads straight from the DOM's data-* attributes and rendered cell
        // text — rows stay in the DOM (just display:none) across pagination,
        // so this naturally covers every filtered row, not only the current page.
        function collectExportRows() {
            const keyword = (document.getElementById('storeSearchInput')?.value || '').toLowerCase().trim();
            const status = document.getElementById('storeStatusFilter')?.value || 'all';

            if (activeTab === 'products') {
                const stock = document.getElementById('storeStockFilter')?.value || 'all';
                const setup = document.getElementById('storeSetupFilter')?.value || 'all';

                return Array.from(document.querySelectorAll('.product-row')).filter(row => {
                    const text = [row.dataset.name, row.dataset.number, row.dataset.category].join(' ');
                    const matchKeyword = !keyword || text.includes(keyword);
                    const matchStatus = status === 'all' || row.dataset.status === status;
                    const matchStock = stock === 'all' || row.dataset.stock === stock;
                    const matchSetup = setup === 'all' || row.dataset.setup === setup;
                    return matchKeyword && matchStatus && matchStock && matchSetup;
                }).map(row => {
                    const cellText = (selector) => row.querySelector(selector)?.textContent.trim() || '-';
                    return {
                        number: row.querySelector('.product-sub-line')?.textContent.trim() || '-',
                        name: row.querySelector('.product-main-name')?.textContent.trim() || '-',
                        series: row.dataset.series || '-',
                        category: cellText('.col-category'),
                        price: cellText('.col-price'),
                        stock: cellText('.col-stock'),
                        location: row.dataset.location || '-',
                        visible: row.dataset.status === 'active' ? 'Active' : row.dataset.status ===
                            'inactive' ? 'Inactive' : 'Not Reviewed',
                    };
                });
            }

            return Array.from(document.querySelectorAll('.category-card')).filter(card => {
                const matchKeyword = !keyword || (card.dataset.name || '').includes(keyword);
                const matchStatus = status === 'all' || card.dataset.status === status;
                return matchKeyword && matchStatus;
            }).map(card => ({
                category: card.querySelector('.category-title-text')?.textContent.trim() || '-',
                total_items: (card.querySelector('.category-sub-text')?.textContent || '').replace(
                    /[^\d]/g, '') || '0',
                visible: card.dataset.status === 'active' ? 'Active' : 'Inactive',
            }));
        }

        document.getElementById('exportStoreConfirmBtn')?.addEventListener('click', function() {
            const list = getVisibleExportColumnList();
            const selected = Array.from(list?.querySelectorAll('.export-column-checkbox') || [])
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            if (!selected.length) {
                alert('Please select at least one column to export.');
                return;
            }

            const labels = activeTab === 'products' ? EXPORT_PRODUCT_LABELS : EXPORT_CATEGORY_LABELS;
            const rows = collectExportRows();
            const header = selected.map(col => csvCell(labels[col]));
            const body = rows.map(row => selected.map(col => csvCell(row[col])));
            const csv = '﻿' + [header, ...body].map(r => r.join(',')).join('\r\n');

            const blob = new Blob([csv], {
                type: 'text/csv;charset=utf-8;'
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `${activeTab}-${new Date().toISOString().slice(0, 10)}.csv`;
            document.body.appendChild(a);
            a.click();
            a.remove();
            URL.revokeObjectURL(url);

            bootstrap.Modal.getOrCreateInstance(document.getElementById('exportStoreModal')).hide();
        });

        fixAjaxTabLayout();
        bindClientFiltering();
        bindMenuToggle();
        updateSelectedCounts();
        switchTab(activeTab);
    });
</script>
