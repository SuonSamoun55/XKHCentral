@extends('POSViews.POSAdminViews.app')

@section('title', 'Store Management')

@section('content')
<div class="store-page-wrap">
    <div class="store-panel">
        <div id="storeFlashBox"></div>
        <div id="storeAjaxContainer"></div>
    </div>
</div>
@endsection

<link rel="stylesheet" href="{{ asset('css/views/POSViews/POSAdminViews/StoreManagement/index.css') }}">

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ajaxContainer = document.getElementById('storeAjaxContainer');
    const flashBox = document.getElementById('storeFlashBox');

    let activeTab = 'products';
    let currentProductPage = 1;
    let currentCategoryPage = 1;
    let storeMenuOutsideBound = false;

    function showMessage(message, type = 'success') {
        flashBox.innerHTML = `
            <div class="alert alert-${type === 'success' ? 'success' : 'danger'} custom-alert">
                ${message}
            </div>
        `;

        setTimeout(() => {
            flashBox.innerHTML = '';
        }, 2200);
    }

    function fixAjaxTabLayout() {
        const productsTab = document.getElementById('productsTabContent');
        const categoriesTab = document.getElementById('categoriesTabContent');

        [productsTab, categoriesTab].forEach(tab => {
            if (!tab) return;

            if (tab.querySelector(':scope > .store-body-area')) return;

            const footer = tab.querySelector(':scope > .store-footer-bar');
            const bodyItems = Array.from(tab.children).filter(el => !el.classList.contains('store-footer-bar'));

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

    async function fetchPage() {
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
            bindClientFiltering();
            bindMenuToggle();
            updateSelectedCounts();
            switchTab('products');
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
        const productCountEl = document.getElementById('selectedProductCount');
        const categoryCountEl = document.getElementById('selectedCategoryCount');

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

        const selectAll = document.getElementById('selectAllProducts');

        if (productCountEl) productCountEl.textContent = checkedVisibleProducts.length;
        if (categoryCountEl) categoryCountEl.textContent = checkedVisibleCategories.length;

        if (selectAll) {
            if (visibleProductCheckboxes.length === 0) {
                selectAll.checked = false;
                selectAll.indeterminate = false;
            } else {
                selectAll.checked = checkedVisibleProducts.length === visibleProductCheckboxes.length;
                selectAll.indeterminate =
                    checkedVisibleProducts.length > 0 &&
                    checkedVisibleProducts.length < visibleProductCheckboxes.length;
            }
        }
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
                btn.addEventListener('click', function () {
                    onPageClick(page);
                });
            }

            return btn;
        };

        container.appendChild(
            makeBtn('Previous', currentPage - 1, { disabled: currentPage === 1 })
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
                container.appendChild(makeBtn('...', null, { ellipsis: true }));
            } else {
                container.appendChild(makeBtn(String(item), item, { active: item === currentPage }));
            }
        });

        container.appendChild(
            makeBtn('Next', currentPage + 1, { disabled: currentPage === totalPages })
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

            const matchKeyword = !keyword || text.includes(keyword);
            const matchStatus = status === 'all' || rowStatus === status;
            const matchStock = stock === 'all' || rowStock === stock;

            row.style.display = 'none';

            if (matchKeyword && matchStatus && matchStock) {
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
            createPagination(pagination, totalPages, currentProductPage, function (page) {
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
            createPagination(pagination, totalPages, currentCategoryPage, function (page) {
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

        currentProductPage = 1;
        currentCategoryPage = 1;

        runCurrentTabFilter();
    }

    function bindClientFiltering() {
        const searchInput = document.getElementById('storeSearchInput');
        const statusFilter = document.getElementById('storeStatusFilter');
        const stockFilter = document.getElementById('storeStockFilter');
        const perPageProduct = document.getElementById('storePerPage');
        const perPageCategory = document.getElementById('storePerPageCategory');

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                if (activeTab === 'products') currentProductPage = 1;
                if (activeTab === 'categories') currentCategoryPage = 1;
                runCurrentTabFilter();
            });
        }

        if (statusFilter) {
            statusFilter.addEventListener('change', function () {
                if (activeTab === 'products') currentProductPage = 1;
                if (activeTab === 'categories') currentCategoryPage = 1;
                runCurrentTabFilter();
            });
        }

        if (stockFilter) {
            stockFilter.addEventListener('change', function () {
                currentProductPage = 1;
                filterProducts();
            });
        }

        if (perPageProduct) {
            perPageProduct.addEventListener('change', function () {
                currentProductPage = 1;
                filterProducts();
            });
        }

        if (perPageCategory) {
            perPageCategory.addEventListener('change', function () {
                currentCategoryPage = 1;
                filterCategories();
            });
        }

        document.querySelectorAll('.js-store-tab').forEach(btn => {
            btn.addEventListener('click', function () {
                switchTab(this.dataset.tab);
            });
        });

        runCurrentTabFilter();
    }

    function bindMenuToggle() {
        const trigger = document.getElementById('storeMenuTrigger');
        const panel = document.getElementById('storeMenuPanel');
        const wrap = document.getElementById('storeMenuWrap');

        if (!trigger || !panel || !wrap) return;

        trigger.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            panel.classList.toggle('d-none');
        });

        if (!storeMenuOutsideBound) {
            document.addEventListener('click', function (e) {
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

    document.addEventListener('click', async function (e) {
        const productToggle = e.target.closest('.js-toggle-product');
        if (productToggle) {
            e.preventDefault();

            if (productToggle.classList.contains('loading')) return;
            productToggle.classList.add('loading');

            try {
                const result = await postJson(productToggle.dataset.url);

                if (!result.success) {
                    showMessage(result.message || 'Failed to update product.', 'error');
                    return;
                }

                const row = productToggle.closest('.product-row');
                if (row) {
                    row.dataset.status = result.is_visible ? 'active' : 'inactive';
                }

                productToggle.classList.toggle('on', result.is_visible);
                productToggle.classList.toggle('off', !result.is_visible);

                showMessage(result.message || 'Updated successfully.');
                runCurrentTabFilter();
            } catch (error) {
                showMessage('Failed to update product.', 'error');
            } finally {
                productToggle.classList.remove('loading');
            }
            return;
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

        const bulkProductActivate = e.target.closest('#bulkProductActivate');
        if (bulkProductActivate) {
            e.preventDefault();
            const ids = getSelectedProductIds();
            if (!ids.length) return showMessage('Please select at least one product.', 'error');

            try {
                const result = await postJson(bulkProductActivate.dataset.url, { ids, action: 'activate' });
                if (!result.success) return showMessage(result.message || 'Failed to update.', 'error');
                showMessage(result.message || 'Updated successfully.');
                fetchPage();
            } catch {
                showMessage('Failed to update.', 'error');
            }
            return;
        }

        const bulkProductDeactivate = e.target.closest('#bulkProductDeactivate');
        if (bulkProductDeactivate) {
            e.preventDefault();
            const ids = getSelectedProductIds();
            if (!ids.length) return showMessage('Please select at least one product.', 'error');

            try {
                const result = await postJson(bulkProductDeactivate.dataset.url, { ids, action: 'deactivate' });
                if (!result.success) return showMessage(result.message || 'Failed to update.', 'error');
                showMessage(result.message || 'Updated successfully.');
                fetchPage();
            } catch {
                showMessage('Failed to update.', 'error');
            }
            return;
        }

        const bulkCategoryActivate = e.target.closest('#bulkCategoryActivate');
        if (bulkCategoryActivate) {
            e.preventDefault();
            const codes = getSelectedCategoryCodes();
            if (!codes.length) return showMessage('Please select at least one category.', 'error');

            try {
                const result = await postJson(bulkCategoryActivate.dataset.url, { codes, action: 'activate' });
                if (!result.success) return showMessage(result.message || 'Failed to update.', 'error');
                showMessage(result.message || 'Updated successfully.');
                fetchPage();
            } catch {
                showMessage('Failed to update.', 'error');
            }
            return;
        }

        const bulkCategoryDeactivate = e.target.closest('#bulkCategoryDeactivate');
        if (bulkCategoryDeactivate) {
            e.preventDefault();
            const codes = getSelectedCategoryCodes();
            if (!codes.length) return showMessage('Please select at least one category.', 'error');

            try {
                const result = await postJson(bulkCategoryDeactivate.dataset.url, { codes, action: 'deactivate' });
                if (!result.success) return showMessage(result.message || 'Failed to update.', 'error');
                showMessage(result.message || 'Updated successfully.');
                fetchPage();
            } catch {
                showMessage('Failed to update.', 'error');
            }
            return;
        }
    });

    document.addEventListener('change', function (e) {
        if (e.target && e.target.id === 'selectAllProducts') {
            document.querySelectorAll('.product-checkbox').forEach(cb => {
                const row = cb.closest('.product-row');
                if (row && row.style.display !== 'none') {
                    cb.checked = e.target.checked;
                }
            });
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

    fetchPage();
});
</script>
