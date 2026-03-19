<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Customer POS Interface</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #18b8c7;
            --primary-dark: #119aa7;
            --bg: #f5f6f8;
            --white: #ffffff;
            --text: #1f2937;
            --muted: #6b7280;
            --border: #e5e7eb;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .page-layout {
            display: flex;
            min-height: 100vh;
            overflow: hidden;
        }

        .sidebar-area {
            width: 250px;
            flex-shrink: 0;
            background: #fff;
            border-right: 1px solid var(--border);
            height: 100vh;
            overflow-y: auto;
        }

        .content-area {
            flex: 1;
            min-width: 0;
            height: 100vh;
            overflow-y: auto;
            padding: 20px;
        }

        .top-panel {
            position: sticky;
            top: 0;
            z-index: 20;
            background: var(--bg);
            padding-bottom: 18px;
        }

        .filter-box {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 18px;
            box-shadow: var(--shadow);
        }

        .filters-row {
            display: grid;
            grid-template-columns: 1fr 240px 160px;
            gap: 14px;
        }

        .search-box,
        .select-box {
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #fff;
            padding: 12px 14px;
        }

        .search-box input,
        .select-box select {
            width: 100%;
            border: none;
            outline: none;
            background: transparent;
            font-family: inherit;
            font-size: 14px;
        }

        .update-btn {
            border: none;
            border-radius: 12px;
            background: var(--primary);
            color: #fff;
            font-weight: 700;
            cursor: pointer;
            min-height: 48px;
            transition: 0.2s;
        }

        .update-btn:hover {
            background: var(--primary-dark);
        }

        .update-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 20px;
        }

        .product-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
        }

        .product-image {
            width: 100%;
            height: 220px;
            background: #eef2f7;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .product-info {
            padding: 14px 16px 16px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .product-category {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 4px;
        }

        .product-name {
            font-size: 15px;
            font-weight: 700;
            line-height: 1.4;
            margin-bottom: 10px;
            min-height: 42px;
        }

        .location-chip {
            display: inline-block;
            margin-bottom: 10px;
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 999px;
            background: #ecfeff;
            color: #0f766e;
            width: fit-content;
        }

        .product-price {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .stock-info {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 14px;
        }

        .stock-info.out {
            color: #dc2626;
        }

        .add-to-cart-btn {
            margin-top: auto;
            width: 100%;
            border: none;
            border-radius: 10px;
            padding: 11px;
            background: var(--primary);
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }

        .add-to-cart-btn:hover:not(:disabled) {
            background: var(--primary-dark);
        }

        .add-to-cart-btn:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
        }

        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 50px 20px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            color: var(--muted);
        }

        .toast-notification {
            position: fixed;
            top: 24px;
            right: 24px;
            background: #fff;
            border-left: 4px solid var(--primary);
            padding: 14px 16px;
            border-radius: 12px;
            box-shadow: 0 10px 24px rgba(0,0,0,0.12);
            z-index: 9999;
            min-width: 260px;
        }

        .toast-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .toast-header h6 {
            font-size: 15px;
            color: var(--primary-dark);
        }

        .toast-close {
            border: none;
            background: none;
            font-size: 18px;
            cursor: pointer;
        }

        .toast-body {
            font-size: 14px;
            color: var(--muted);
        }

        @media (max-width: 1400px) {
            .product-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 1100px) {
            .filters-row {
                grid-template-columns: 1fr;
            }

            .product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            .page-layout {
                flex-direction: column;
            }

            .sidebar-area {
                width: 100%;
                height: auto;
                overflow: visible;
                border-right: none;
                border-bottom: 1px solid var(--border);
            }

            .content-area {
                height: auto;
                overflow: visible;
            }

            .product-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="page-layout">
    <aside class="sidebar-area">
        @include('POSViews.POSAdminViews.aside')
    </aside>

    <main class="content-area">
        <div class="top-panel">
            <div class="filter-box">
                <div class="filters-row">
                    <div class="search-box">
                        <input id="searchInput" type="text" placeholder="Search products by name..." autocomplete="off">
                    </div>

                    <div class="select-box">
                        <select id="categorySelect">
                            <option value="">All Categories</option>
                        </select>
                    </div>

                    <button id="updateBtn" type="button" class="update-btn" onclick="updateItems()">
                        Update
                    </button>
                </div>
            </div>
        </div>

        <div id="itemGrid" class="product-grid"></div>
    </main>
</div>

<script>
    const PRODUCTS = @json($items);

    const money = (n) => `$${Number(n || 0).toFixed(2)}`;

    const esc = (s) => String(s ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');

    let currentFiltered = [...PRODUCTS];

    function stockInfo(qty) {
        qty = Math.round(qty || 0);
        if (qty <= 0) return `<div class="stock-info out">Out of stock</div>`;
        return `<div class="stock-info">${qty} items left</div>`;
    }

    function buildCategories() {
        const select = document.getElementById('categorySelect');
        const categories = [...new Set(PRODUCTS.map(p => p.itemCategoryCode || 'General'))].sort();

        categories.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c;
            opt.textContent = c;
            select.appendChild(opt);
        });
    }

    function applyFilters() {
        const q = document.getElementById('searchInput').value.trim().toLowerCase();
        const cat = document.getElementById('categorySelect').value;

        currentFiltered = PRODUCTS.filter(p => {
            const name = String(p.displayName || '').toLowerCase();
            const category = p.itemCategoryCode || 'General';
            return (!q || name.includes(q)) && (!cat || category === cat);
        });

        renderItems(currentFiltered);
    }

    function renderItems(data) {
        const grid = document.getElementById('itemGrid');

        if (!data.length) {
            grid.innerHTML = `<div class="empty-state">No products found.</div>`;
            return;
        }

        grid.innerHTML = data.map(item => {
            const inventory = Math.round(item.inventory || 0);
            const disabled = inventory <= 0 ? 'disabled' : '';
            const btnText = inventory <= 0 ? 'Out of Stock' : 'Add to Cart';
            const locationCode = item.defaultLocationCode || item.locationCode || null;

            return `
                <div class="product-card">
                    <div class="product-image">
                        <img
                            src="/item-image/${esc(item.id)}"
                            alt="${esc(item.displayName)}"
                            loading="lazy"
                            onerror="this.src='https://placehold.co/400x300/e5e7eb/94a3b8?text=No+Photo'">
                    </div>

                    <div class="product-info">
                        <div class="product-category">${esc(item.itemCategoryCode || 'General')}</div>
                        <div class="product-name">${esc(item.displayName)}</div>

                        ${locationCode ? `<div class="location-chip">Location: ${esc(locationCode)}</div>` : ''}

                        <div class="product-price">${money(item.unitPrice)}</div>
                        ${stockInfo(inventory)}

                        <button class="add-to-cart-btn" ${disabled}
                            onclick="addToCart('${esc(item.id)}', '${esc(item.displayName)}', ${Number(item.unitPrice || 0)})">
                            ${btnText}
                        </button>
                    </div>
                </div>
            `;
        }).join('');
    }

    function addToCart(id, name, price) {
        showToast('Added to Cart', `${name} (${money(price)}) has been added to your cart.`);
    }

    function showToast(title, message) {
        const oldToast = document.querySelector('.toast-notification');
        if (oldToast) oldToast.remove();

        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.innerHTML = `
            <div class="toast-header">
                <h6>${esc(title)}</h6>
                <button class="toast-close" onclick="this.closest('.toast-notification').remove()">&times;</button>
            </div>
            <div class="toast-body">${esc(message)}</div>
        `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    async function updateItems() {
        const btn = document.getElementById('updateBtn');
        const oldText = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = 'Updating...';

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

            const data = await res.json();

            if (!res.ok) {
                throw new Error(data.message || 'Update failed');
            }

            showToast('Success', `${data.count ?? PRODUCTS.length} item(s) synced successfully.`);
        } catch (error) {
            showToast('Failed', error.message || 'Could not update items.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = oldText;
        }
    }

    window.addEventListener('DOMContentLoaded', () => {
        buildCategories();
        renderItems(PRODUCTS);

        document.getElementById('searchInput').addEventListener('input', applyFilters);
        document.getElementById('categorySelect').addEventListener('change', applyFilters);
    });
</script>

</body>
</html>
