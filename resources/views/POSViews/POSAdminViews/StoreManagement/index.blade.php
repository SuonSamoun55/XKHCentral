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

<style>
    .content-area{
        width:100%;
        height:100vh;
        overflow:hidden;
    }

    .store-page-wrap{
        width:100%;
        height:100%;
    }

    .store-panel{
        background:#ffffff;
        border-radius:18px;
        padding:20px;
        box-shadow:0 10px 26px rgba(15, 23, 42, 0.05);
        height:100%;
        overflow:hidden;
        display:flex;
        flex-direction:column;
    }

    #storeAjaxContainer{
        flex:1;
        min-height:0;
        display:flex;
        flex-direction:column;
        overflow:hidden;
    }

    .store-header-row{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        margin-bottom:16px;
        flex-wrap:wrap;
        flex-shrink:0;
    }

    .store-page-title{
        margin:0;
        font-size:28px;
        font-weight:800;
        color:#25b8c8;
    }

    .custom-alert{
        border:none;
        border-radius:8px;
        padding:11px 14px;
        font-size:13px;
        margin-bottom:16px;
    }

    .store-top-tools{
        margin-bottom:10px;
        flex-shrink:0;
    }

    .store-toolbar-row{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        flex-wrap:wrap;
    }

    .store-toolbar-left,
    .store-toolbar-right{
        display:flex;
        align-items:center;
        gap:8px;
        flex-wrap:wrap;
    }

    .store-toolbar-right{
        justify-content:flex-end;
        flex:1;
    }

    .store-tab-switcher-inline{
        display:flex;
        align-items:center;
        gap:8px;
        flex-wrap:wrap;
    }

    .store-tab-btn{
        border:none;
        height:30px;
        padding:0 8px;
        border-radius:5px;
        background:#eef7f8;
        color:#1f7f8b;
        font-size:13px;
        font-weight:700;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        cursor:pointer;
        transition:all .2s ease;
        white-space:nowrap;
    }

    .store-tab-btn.active{
        background:#20b8c8;
        color:#fff;
        box-shadow:0 4px 12px rgba(32, 184, 200, 0.18);
    }

    .toolbar-control,
    .store-select-control,
    .store-action-btn{
        height:30px;
        border-radius:5px;
        font-size:13px;
    }

    .search-input-box{
        min-width:200px;
        display:flex;
        align-items:center;
        height:30px;
        gap:5px;
        padding:0 8px;
        background:#fff;
        border:1px solid #dce5ec;
        transition:all .2s ease;
        border-radius:5px;
    }

    .search-input-box:focus-within{
        border-color:#25b8c8;
        box-shadow:0 0 0 3px rgba(37, 184, 200, 0.10);
    }

    .search-input-box i{
        color:#94a3b8;
        font-size:13px;
    }

    .search-input-box input{
        border:none;
        outline:none;
        background:transparent;
        width:100%;
        font-size:13px;
        color:#334155;
    }

    .store-select-control{
        min-width:140px;
        border:1px solid #dce5ec;
        padding:0 12px;
        font-weight:500;
        color:#475569;
        background:#fff;
        outline:none;
        cursor:pointer;
    }

    .store-action-btn{
        border:none;
        padding:0 18px;
        font-weight:700;
        color:#fff;
        transition:all .2s ease;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:6px;
        white-space:nowrap;
        cursor:pointer;
    }

    .store-action-btn:hover{
        transform:translateY(-1px);
        opacity:.97;
    }

    .btn-active-custom{
        background:linear-gradient(90deg, #21c0d1, #47bcc0);
    }

    .btn-inactive-custom{
        background:linear-gradient(90deg, #ef4444, #dc2626);
    }

    /* MAIN TAB LAYOUT */
    .store-tab-content{
        height:calc(100% - 0px);
        display:flex;
        flex-direction:column;
        min-height:0;
    }

    /* TABLE/CARDS AREA = 90% */
    .store-body-area{
        flex:0 0 90%;
        max-height:92%;
        min-height:0;
        overflow:hidden;
        display:flex;
        flex-direction:column;
    }

    /* FOOTER = 10% */
    .store-footer-bar{
        flex:0 0 0%;
        min-height:60px;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        border-top:1px solid #e5e7eb;
        /* padding-top:12px ; */
        /* margin-top:12px; */
    }

    .store-table-scroll{
        flex:1;
        min-height:0;
        border:1px solid #e2e8f0;
        border-radius:8px;
        overflow:hidden;
        display:flex;
        flex-direction:column;
        background:#fff;
    }

    .table-scroll-wrap{
        width:100%;
        height:100%;
        overflow-x:auto;
        overflow-y:auto;
        min-height:0;
    }

    .table-scroll-wrap::-webkit-scrollbar,
    .category-list-grid::-webkit-scrollbar,
    .custom-pagination-wrap::-webkit-scrollbar{
        height:8px;
        width:8px;
    }

    .table-scroll-wrap::-webkit-scrollbar-thumb,
    .category-list-grid::-webkit-scrollbar-thumb,
    .custom-pagination-wrap::-webkit-scrollbar-thumb{
        background:#cbd5e1;
        border-radius:999px;
    }

    .manage-store-table{
        width:100%;
        min-width:950px;
        border-collapse:separate;
        border-spacing:0;
    }

    .manage-store-table thead th{
        position:sticky;
        top:0;
        z-index:2;
        background:#dff3f6;
        color:#334155;
        font-size:11px;
        font-weight:800;
        padding:12px;
        white-space:nowrap;
        border:none;
    }

    .manage-store-table tbody td{
        background:#f8fcfd;
        padding:5px 8px;
        color:#334155;
        vertical-align:middle;
        border-bottom:1px solid #edf2f7;
    }

    .manage-store-table tbody tr:nth-child(even) td{
        background:#edf8fa;
    }

    .manage-store-table tbody tr:hover td{
        background:#dff4f6;
    }

    .col-check{
        width:20px;
        text-align:center;
    }

    .product-cell{
        display:flex;
        align-items:center;
        gap:12px;
        min-width:0;
    }

    .product-thumb-box{
        width:40px;
        height:40px;
        border-radius:10px;
        overflow:hidden;
        border:1px solid #dde7eb;
        background:#fff;
        flex-shrink:0;
    }

    .product-thumb-box img{
        width:100%;
        height:100%;
        object-fit:cover;
        display:block;
    }

    .thumb-placeholder{
        width:100%;
        height:100%;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:18px;
        color:#94a3b8;
    }

    .product-main-name{
        font-size:12px;
        font-weight:700;
        color:#0f172a;
        line-height:1.35;
    }

    .product-sub-line{
        font-size:11px;
        color:#64748b;
        margin-top:2px;
    }

    .status-action-wrap{
        display:flex;
        align-items:center;
        gap:10px;
        flex-wrap:wrap;
    }

    .status-chip{
        min-width:92px;
        height:30px;
        padding:0 8px;
        border-radius:5px;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        font-size:10px;
        font-weight:800;
        letter-spacing:.3px;
    }

    .status-chip.active{
        background:#11bfd1;
        color:#fff;
    }

    .status-chip.inactive{
        background:#fff;
        color:#a63434;
        border:2px solid #a63434;
    }

   .toggle-switch{
    width:40px;
    height:22px;
    border:none;
    border-radius:999px;
    position:relative;
    padding:0;
    cursor:pointer;
    transition:all .2s ease;
}

.toggle-switch.on{
    background:#11bfd1;
}

.toggle-switch.off{
    background:#b53b3b;
}

.toggle-switch.loading{
    opacity:.7;
    pointer-events:none;
}

.toggle-dot{
    width:16px;
    height:16px;
    border-radius:50%;
    background:#fff;
    position:absolute;
    top:3px;
    left:3px;
    transition:all .2s ease;
    box-shadow:0 2px 6px rgba(15, 23, 42, 0.12);
}

/* IMPORTANT: recalculate position */
.toggle-switch.on .toggle-dot{
    left:21px; /* 40 - 16 - 3 = 21 */
}

.toggle-switch.off .toggle-dot{
    left:3px;
}
    .category-list-grid{
        flex:1;
        min-height:0;
        display:grid;
        grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));
        gap:14px;
        overflow:auto;
        padding-right:2px;
    }

    .category-item-card{
        background:#f8fcfd;
        border:1px solid #e2edf1;
        border-radius:8px;
        padding:15px;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        transition:all .2s ease;
        min-height:84px;
    }

    .category-item-card:hover{
        background:#edf8fa;
    }

    .category-left-wrap,
    .category-right-wrap{
        display:flex;
        align-items:center;
        gap:10px;
    }

    .category-title-text{
        font-size:13px;
        font-weight:800;
        color:#0f172a;
    }

    .category-sub-text{
        font-size:11px;
        color:#64748b;
        margin-top:2px;
    }

    .empty-state-box{
        text-align:center;
        color:#64748b;
        font-size:13px;
        font-weight:700;
        padding:28px 10px;
    }

    .store-footer-left,
    .store-footer-center,
    .store-footer-right{
        display:flex;
        align-items:center;
        gap:12px;
        flex-wrap:wrap;
    }

    .store-footer-center{
        flex:1;
        justify-content:center;
        min-width:0;
    }

    .store-footer-right{
        margin-left:auto;
    }

    .selected-box{
        display:inline-flex;
        align-items:center;
        gap:8px;
        background:#eff9fb;
        border-radius:5px;
        height: 30px;
        padding: 0px 8px;
        /* font-size:13px; */
        font-weight:700;
        color:#475569;
    }

    .selected-box span{
        min-width:24px;
        /* height:20px;
        padding:8px 0px; */

        /* background:#22b8ca; */
        color:#22b8ca;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        /* font-size:11px; */
        /* font-weight:800; */
    }

    .footer-show-box{
        display:flex;
        align-items:center;
        gap:8px;
        font-size:13px;
        color:#64748b;
        font-weight:500;
    }

    .store-footer-select{
        min-width:88px;
        height:30px;
        border:1px solid #dce5ec;
        border-radius:5px;
        padding:0 12px;
        background:#fff;
        font-size:14px;
        color:#475569;
        outline:none;
    }

    .custom-pagination-wrap{
        display:flex;
        align-items:center;
        justify-content:center;
        flex-wrap:nowrap;
        gap:6px;
        overflow-x:auto;
        overflow-y:hidden;
        white-space:nowrap;
        /* padding-bottom:2px; */
        /* max-width:100%; */
    }

    .custom-page-btn{
        flex:0 0 auto;
        border:none;
        min-width:38px;
        height:30px;
        border-radius:5px;
        display:flex;
        align-items:center;
        justify-content:center;
        color:#334155;
        background:#f1f5f9;
        /* font-size:12px; */
        font-weight:500;
        cursor:pointer;
        padding:0 10px;
    }

    .custom-page-btn.active{
        background:#22b8ca;
        color:#fff;
    }

    .custom-page-btn:disabled{
        background:#e2e8f0;
        color:#94a3b8;
        cursor:not-allowed;
    }

    .pagination-ellipsis{
        cursor:default;
        pointer-events:none;
    }

    .footer-result-text{
        font-size:13px;
        color:#64748b;
        font-weight:600;
    }

    .row-check-input{
        width:15px;
        height:15px;
        cursor:pointer;
    }

    .d-none{
        display:none !important;
    }

    @media (max-width: 1200px){
        .store-toolbar-row{
            align-items:flex-start;
        }

        .store-toolbar-right{
            justify-content:flex-start;
        }
    }

    @media (max-width: 992px){
        .store-toolbar-row{
            flex-direction:column;
            align-items:stretch;
        }

        .store-toolbar-left,
        .store-toolbar-right{
            width:100%;
            justify-content:flex-start;
        }

        .search-input-box{
            min-width:220px;
            flex:1 1 220px;
        }

        .store-footer-bar{
            flex:0 0 auto;
            min-height:auto;
            flex-direction:column;
            align-items:flex-start;
        }

        .store-footer-center{
            width:100%;
            justify-content:flex-start;
        }

        .store-footer-right{
            margin-left:0;
        }
    }

    @media (max-width: 768px){
        .store-panel{
            padding:16px;
            border-radius:14px;
        }

        .store-page-title{
            font-size:24px;
        }

        .store-tab-btn,
        .store-select-control,
        .store-action-btn{
            width:100%;
        }

        .search-input-box{
            width:100%;
            min-width:100%;
        }

        .store-toolbar-left,
        .store-toolbar-right,
        .store-tab-switcher-inline{
            width:100%;
        }

        .category-item-card{
            flex-direction:column;
            align-items:flex-start;
        }

        .category-right-wrap{
            width:100%;
            justify-content:space-between;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ajaxContainer = document.getElementById('storeAjaxContainer');
    const flashBox = document.getElementById('storeFlashBox');

    let activeTab = 'products';
    let currentProductPage = 1;
    let currentCategoryPage = 1;

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
                const row = cb.closest('tr');
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

                const row = productToggle.closest('tr');
                const chip = row ? row.querySelector('.status-chip') : null;

                if (chip) {
                    chip.textContent = result.label;
                    chip.classList.toggle('active', result.is_visible);
                    chip.classList.toggle('inactive', !result.is_visible);
                }

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

                const card = categoryToggle.closest('.category-item-card');
                const chip = card ? card.querySelector('.status-chip') : null;

                if (chip) {
                    chip.textContent = result.label;
                    chip.classList.toggle('active', result.is_visible);
                    chip.classList.toggle('inactive', !result.is_visible);
                }

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
                const row = cb.closest('tr');
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
