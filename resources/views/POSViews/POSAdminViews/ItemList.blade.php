<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root{
            --primary:#1bb8c9;
            --primary-dark:#12a5b5;
            --bg:#f5f6f8;
            --white:#ffffff;
            --text:#202938;
            --muted:#8b95a7;
            --border:#eceef2;
            --danger:#ef4444;
            --shadow:0 8px 24px rgba(15, 23, 42, 0.06);
            --radius:18px;
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
            overflow:hidden;
        }

        .page-wrap{
            display:flex;
            min-height:100vh;
            width:100%;
        }

        .sidebar-wrap{
            width:250px;
            flex-shrink:0;
            background:#fff;
            border-right:1px solid var(--border);
            height:100vh;
            overflow-y:auto;
        }

        .main-wrap{
            flex:1;
            min-width:0;
            height:100vh;
            overflow-y:auto;
            padding:28px 26px 30px;
        }

        .page-title{
            font-size:24px;
            font-weight:700;
            color:#34a6b5;
            margin-bottom:26px;
        }

        .toolbar-row{
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            gap:16px;
            flex-wrap:wrap;
            margin-bottom:18px;
        }

        .toolbar-left{
            flex:1;
            min-width:280px;
            max-width:520px;
        }

        .search-box{
            position:relative;
            margin-bottom:12px;
        }

        .search-box i{
            position:absolute;
            top:50%;
            left:14px;
            transform:translateY(-50%);
            color:#98a2b3;
            font-size:14px;
        }

        .search-box input{
            width:100%;
            height:42px;
            border:1px solid var(--border);
            border-radius:999px;
            background:#f7f8fa;
            padding:0 16px 0 38px;
            outline:none;
            font-size:13px;
            color:var(--text);
        }

        .search-box input:focus{
            border-color:var(--primary);
            background:#fff;
        }

        .filter-btn{
            display:inline-flex;
            align-items:center;
            gap:8px;
            height:34px;
            padding:0 14px;
            border:1px solid #dfe3e8;
            border-radius:12px;
            background:#fff;
            font-size:12px;
            color:#5f6b7a;
            cursor:pointer;
            transition:0.2s ease;
        }

        .filter-btn:hover{
            border-color:var(--primary);
            color:var(--primary);
        }

        .toolbar-right{
            display:flex;
            flex-direction:column;
            align-items:flex-end;
            gap:12px;
        }

        .sync-btn{
            border:none;
            background:var(--primary);
            color:#fff;
            height:40px;
            min-width:160px;
            padding:0 18px;
            border-radius:6px;
            font-size:13px;
            font-weight:600;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            box-shadow:0 6px 16px rgba(27,184,201,0.25);
            transition:0.2s ease;
        }

        .sync-btn:hover{
            background:var(--primary-dark);
        }

        .sync-btn:disabled{
            opacity:.75;
            cursor:not-allowed;
        }

        .view-switch{
            display:flex;
            gap:10px;
        }

        .view-btn{
            height:34px;
            min-width:72px;
            border:none;
            border-radius:6px;
            background:#f3f5f7;
            color:var(--primary);
            font-size:12px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            cursor:pointer;
            transition:0.2s ease;
        }

        .view-btn.active{
            background:#eafafb;
            color:var(--primary);
            box-shadow:inset 0 0 0 1px #d8f3f7;
        }

        .item-grid{
            display:grid;
            grid-template-columns:repeat(4, minmax(0,1fr));
            gap:14px;
        }

        .product-card{
            background:#fff;
            border-radius:10px;
            overflow:hidden;
            border:1px solid #f0f1f3;
            box-shadow:0 2px 8px rgba(15,23,42,0.03);
            display:flex;
            flex-direction:column;
        }

        .product-image{
            width:100%;
            height:142px;
            background:#eef2f7;
        }

        .product-image img{
            width:100%;
            height:100%;
            object-fit:cover;
            display:block;
        }

        .product-body{
            padding:10px 10px 12px;
            display:flex;
            flex-direction:column;
            min-height:165px;
        }

        .product-title{
            font-size:14px;
            font-weight:700;
            color:#222;
            margin-bottom:4px;
            line-height:1.35;
        }

        .product-sub{
            font-size:10px;
            color:#9aa4b2;
            margin-bottom:8px;
            line-height:1.4;
            min-height:28px;
        }

        .product-price{
            font-size:22px;
            font-weight:700;
            color:#1f2937;
            margin-bottom:8px;
        }

        .product-tax{
            font-size:11px;
            color:#64748b;
            margin-bottom:6px;
        }

        .stock-text{
            font-size:12px;
            font-weight:600;
            color:#1bb8c9;
            margin-bottom:12px;
        }

        .stock-text.out{
            color:#ef4444;
        }

        .view-more-btn{
            margin-top:auto;
            width:100%;
            height:34px;
            border:none;
            border-radius:4px;
            background:var(--primary);
            color:#fff;
            font-size:13px;
            font-weight:600;
            transition:0.2s ease;
        }

        .view-more-btn:hover{
            background:var(--primary-dark);
        }

        .item-list{
            display:flex;
            flex-direction:column;
            gap:12px;
        }

        .list-card{
            display:flex;
            align-items:center;
            gap:14px;
            background:#fff;
            border:1px solid #eef1f4;
            border-radius:12px;
            padding:10px 12px;
        }

        .list-image{
            width:100px;
            height:80px;
            border-radius:10px;
            overflow:hidden;
            flex-shrink:0;
            background:#eef2f7;
        }

        .list-image img{
            width:100%;
            height:100%;
            object-fit:cover;
            display:block;
        }

        .list-info{
            flex:1;
            min-width:0;
        }

        .list-title{
            font-size:15px;
            font-weight:700;
            margin-bottom:4px;
        }

        .list-sub{
            font-size:12px;
            color:#8b95a7;
            margin-bottom:6px;
        }

        .list-tax{
            font-size:11px;
            color:#64748b;
            margin-bottom:6px;
        }

        .list-stock{
            font-size:12px;
            font-weight:600;
            color:var(--primary);
        }

        .list-stock.out{
            color:var(--danger);
        }

        .list-price{
            font-size:20px;
            font-weight:700;
            min-width:100px;
            text-align:right;
        }

        .list-action{
            min-width:120px;
        }

        .empty-box{
            background:#fff;
            border:1px dashed #d9e0e7;
            border-radius:16px;
            padding:50px 20px;
            text-align:center;
            color:#8892a0;
            grid-column:1/-1;
        }

        .toast-wrap{
            position:fixed;
            top:24px;
            right:24px;
            z-index:9999;
            min-width:280px;
            background:#fff;
            border-left:4px solid var(--primary);
            border-radius:12px;
            box-shadow:0 12px 30px rgba(0,0,0,.12);
            padding:14px 16px;
        }

        .toast-head{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:4px;
        }

        .toast-head h6{
            margin:0;
            font-size:14px;
            font-weight:700;
            color:#0f766e;
        }

        .toast-close{
            border:none;
            background:none;
            font-size:18px;
            cursor:pointer;
            line-height:1;
        }

        .toast-body{
            font-size:13px;
            color:#667085;
        }

        @media (max-width: 1400px){
            .item-grid{
                grid-template-columns:repeat(3, minmax(0,1fr));
            }
        }

        @media (max-width: 1100px){
            .sidebar-wrap{
                width:220px;
            }

            .item-grid{
                grid-template-columns:repeat(2, minmax(0,1fr));
            }
        }

        @media (max-width: 768px){
            body{
                overflow:auto;
            }

            .page-wrap{
                flex-direction:column;
            }

            .sidebar-wrap{
                width:100%;
                height:auto;
                overflow:visible;
            }

            .main-wrap{
                height:auto;
                overflow:visible;
                padding:18px;
            }

            .toolbar-right{
                align-items:flex-start;
            }

            .item-grid{
                grid-template-columns:1fr;
            }

            .list-card{
                flex-wrap:wrap;
            }

            .list-price,
            .list-action{
                text-align:left;
                min-width:auto;
                width:100%;
            }
        }
    </style>
</head>
<body>

<div class="page-wrap">
    <aside class="sidebar-wrap">
        @include('POSViews.POSAdminViews.aside')
    </aside>

    <main class="main-wrap">
        <h1 class="page-title">POS System</h1>

        <div class="toolbar-row">
            <div class="toolbar-left">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="Search">
                </div>

                <button type="button" class="filter-btn" data-bs-toggle="modal" data-bs-target="#filterModal">
                    Filter
                    <i class="bi bi-sliders2"></i>
                </button>
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

        <div id="itemContainer" class="item-grid"></div>
    </main>
</div>

<div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title">Filter Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body pt-0">
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Category</label>
                    <select id="categorySelect" class="form-select">
                        <option value="">All Categories</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Stock Status</label>
                    <select id="stockSelect" class="form-select">
                        <option value="">All</option>
                        <option value="in">In Stock</option>
                        <option value="out">Out of Stock</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" onclick="resetFilters()">Reset</button>
                <button type="button" class="btn btn-info text-white" data-bs-dismiss="modal" onclick="applyFilters()">Apply</button>
            </div>
        </div>
    </div>
</div>

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

    function stockText(qty) {
        qty = Math.round(Number(qty || 0));
        if (qty <= 0) {
            return `<div class="stock-text out">Out of Stock</div>`;
        }
        return `<div class="stock-text">${qty} items left</div>`;
    }

    function buildCategories() {
        const select = document.getElementById('categorySelect');
        select.innerHTML = `<option value="">All Categories</option>`;

        const categories = [...new Set(PRODUCTS.map(item => item.itemCategoryCode || 'General'))].sort();

        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category;
            option.textContent = category;
            select.appendChild(option);
        });
    }

    function applyFilters() {
        const keyword = document.getElementById('searchInput').value.trim().toLowerCase();
        const category = document.getElementById('categorySelect').value;
        const stock = document.getElementById('stockSelect').value;

        filteredProducts = PRODUCTS.filter(item => {
            const name = String(item.displayName || item.display_name || '').toLowerCase();
            const number = String(item.number || '').toLowerCase();
            const itemCategory = item.itemCategoryCode || item.item_category_code || 'General';
            const inventory = Math.round(Number(item.inventory || 0));

            const matchKeyword = !keyword || name.includes(keyword) || number.includes(keyword);
            const matchCategory = !category || itemCategory === category;
            const matchStock =
                !stock ||
                (stock === 'in' && inventory > 0) ||
                (stock === 'out' && inventory <= 0);

            return matchKeyword && matchCategory && matchStock;
        });

        renderItems();
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('categorySelect').value = '';
        document.getElementById('stockSelect').value = '';
        filteredProducts = [...PRODUCTS];
        renderItems();
    }

    function setView(view) {
        currentView = view;

        document.getElementById('gridBtn').classList.toggle('active', view === 'grid');
        document.getElementById('listBtn').classList.toggle('active', view === 'list');

        renderItems();
    }

    function showToast(title, message) {
        const oldToast = document.querySelector('.toast-wrap');
        if (oldToast) oldToast.remove();

        const toast = document.createElement('div');
        toast.className = 'toast-wrap';
        toast.innerHTML = `
            <div class="toast-head">
                <h6>${esc(title)}</h6>
                <button class="toast-close" onclick="this.closest('.toast-wrap').remove()">&times;</button>
            </div>
            <div class="toast-body">${esc(message)}</div>
        `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
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
                const inventory = Math.round(Number(item.inventory || 0));
                const vatPercent = Number(item.vatPercent ?? item.vat_percent ?? 0);
                const taxAmount = Number(item.taxAmount ?? item.tax_amount ?? 0);

                return `
                    <div class="product-card">
                        <div class="product-image">
                            <img
                                src="/item-image/${esc(item.id)}"
                                alt="${esc(item.displayName || item.display_name || 'No Name')}"
                                loading="lazy"
                                onerror="this.src='https://placehold.co/500x320/e5e7eb/94a3b8?text=No+Photo'">
                        </div>

                        <div class="product-body">
                            <div class="product-title">${esc(item.displayName || item.display_name || 'No Name')}</div>
                            <div class="product-sub">${esc(item.description || item.itemCategoryCode || item.item_category_code || 'Fresh product')}</div>
                            <div class="product-price">${money(item.unitPrice ?? item.unit_price)}</div>
                            <div class="product-tax">VAT: ${vatPercent}% | Tax: ${money(taxAmount)}</div>
                            ${stockText(inventory)}
                            <a href="/pos/items/${item.id}" class="view-more-btn text-decoration-none d-flex align-items-center justify-content-center">
                                View More
                            </a>
                        </div>
                    </div>
                `;
            }).join('');
        } else {
            container.className = 'item-list';
            container.innerHTML = filteredProducts.map(item => {
                const inventory = Math.round(Number(item.inventory || 0));
                const vatPercent = Number(item.vatPercent ?? item.vat_percent ?? 0);
                const taxAmount = Number(item.taxAmount ?? item.tax_amount ?? 0);

                return `
                    <div class="list-card">
                        <div class="list-image">
                            <img
                                src="/item-image/${esc(item.id)}"
                                alt="${esc(item.displayName || item.display_name || 'No Name')}"
                                loading="lazy"
                                onerror="this.src='https://placehold.co/500x320/e5e7eb/94a3b8?text=No+Photo'">
                        </div>

                        <div class="list-info">
                            <div class="list-title">${esc(item.displayName || item.display_name || 'No Name')}</div>
                            <div class="list-sub">${esc(item.description || item.itemCategoryCode || item.item_category_code || 'Fresh product')}</div>
                            <div class="list-tax">VAT: ${vatPercent}% | Tax: ${money(taxAmount)}</div>
                            <div class="list-stock ${inventory <= 0 ? 'out' : ''}">
                                ${inventory <= 0 ? 'Out of Stock' : inventory + ' items left'}
                            </div>
                        </div>

                        <div class="list-price">${money(item.unitPrice ?? item.unit_price)}</div>

                        <div class="list-action">
                            <a href="/pos/items/${item.id}" class="view-more-btn text-decoration-none d-flex align-items-center justify-content-center">
                                View More
                            </a>
                        </div>
                    </div>
                `;
            }).join('');
        }
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

                        vatPercent: item.vatPercent ?? item.vat_percentage ?? item.vatpercent ?? 0,
                        taxAmount: item.taxAmount ?? item.tax_amount ?? item.taxamount ?? 0,
                        discountAmount: item.discountAmount ?? item.discount_amount ?? item.discountamount ?? 0,
                        discountStartDate: item.discountStartDate ?? item.discount_start_date ?? item.discountstartdate ?? null,
                        discountEndDate: item.discountEndDate ?? item.discount_end_date ?? item.discountenddate ?? null,

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

            const data = await res.json();

            if (!res.ok) {
                throw new Error(data.message || 'Sync failed');
            }

            showToast('Success', `${data.count ?? PRODUCTS.length} item(s) synced successfully.`);
            window.location.reload();
        } catch (error) {
            showToast('Failed', error.message || 'Could not sync items.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = oldHtml;
        }
    }

    window.addEventListener('DOMContentLoaded', function () {
        buildCategories();
        renderItems();

        document.getElementById('searchInput').addEventListener('input', applyFilters);
        document.getElementById('categorySelect').addEventListener('change', applyFilters);
        document.getElementById('stockSelect').addEventListener('change', applyFilters);
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
