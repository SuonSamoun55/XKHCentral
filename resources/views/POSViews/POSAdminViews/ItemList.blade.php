<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Customer POS Interface</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

  <style>
    :root {
      --primary-color: #ff85a2;
      --primary-light: #ffedf1;
      --primary-dark: #e06b85;
      --text-color: #333;
      --light-gray: #f8f9fa;
      --white: #ffffff;
      --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      --transition: all 0.25s ease;
      --border: 1px solid #e2e8f0;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Poppins', sans-serif;
      color: var(--text-color);
      line-height: 1.6;
      background-color: #f9f9f9;
      height: 100vh;
      overflow: hidden;
    }

    .top-panel {
      position: sticky;
      top: 70px;
      z-index: 90;
      background: #f9f9f9;
      padding: 16px 25px 10px;
      border-bottom: 1px solid rgba(226, 232, 240, 0.6);
      backdrop-filter: blur(6px);
    }

    .stats-row {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      margin-bottom: 12px;
      display: none;
    }

    .stat-chip {
      background: white;
      border-radius: 10px;
      padding: 10px 16px;
      font-size: 0.85rem;
      color: #475569;
      border: var(--border);
      box-shadow: 0 2px 4px rgba(0,0,0,0.04);
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .stat-chip strong {
      color: var(--primary-color);
      font-weight: 800;
    }

    .filters-row {
      display: grid;
      grid-template-columns: 1fr 220px 160px;
      gap: 12px;
    }

    .search-box,
    .select-box {
      background: white;
      border: var(--border);
      border-radius: 12px;
      padding: 10px 12px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.04);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .search-box i {
      color: #94a3b8;
      font-size: 1rem;
    }

    .search-box input {
      width: 100%;
      border: none;
      outline: none;
      font-size: 0.95rem;
      font-family: inherit;
      color: #0f172a;
    }

    .select-box select {
      width: 100%;
      border: none;
      outline: none;
      font-size: 0.95rem;
      font-family: inherit;
      color: #0f172a;
      background: transparent;
      cursor: pointer;
    }

    .update-btn {
      border: none;
      border-radius: 12px;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      color: white;
      font-weight: 700;
      font-size: 0.95rem;
      font-family: inherit;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(255, 133, 162, 0.25);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: var(--transition);
      min-height: 48px;
    }

    .update-btn:hover {
      transform: translateY(-1px);
    }

    .update-btn:disabled {
      opacity: 0.7;
      cursor: not-allowed;
      transform: none;
    }

    .spin {
      display: inline-block;
      animation: spin 0.9s linear infinite;
    }

    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    .main-content {
      height: calc(100vh - 70px);
      overflow-y: auto;
      padding: 0 25px 25px;
    }

    .container {
      max-width: 100%;
      margin: 0 auto;
    }

    .product-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 20px;
      padding: 16px 0 10px;
    }

    .product-card {
      background: white;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: var(--transition);
      position: relative;
      display: flex;
      flex-direction: column;
      cursor: pointer;
      border: 1px solid rgba(226, 232, 240, 0.8);
    }

    .product-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.14);
      border-color: rgba(255, 133, 162, 0.35);
    }

    .product-image {
      position: relative;
      overflow: hidden;
      height: 200px;
      background: #f5f5f5;
    }

    .product-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: var(--transition);
    }

    .product-card:hover .product-image img {
      transform: scale(1.08);
    }

    .stock-badge {
      position: absolute;
      top: 10px;
      left: 10px;
      padding: 4px 10px;
      border-radius: 999px;
      font-size: 0.7rem;
      font-weight: 700;
      text-transform: uppercase;
      z-index: 2;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
      letter-spacing: 0.3px;
    }

    .stock-badge.in-stock {
      background: linear-gradient(135deg, #4caf50, #45a049);
      color: white;
    }

    .stock-badge.low-stock {
      background: linear-gradient(135deg, #ff9800, #f57c00);
      color: white;
    }

    .stock-badge.out-of-stock {
      background: linear-gradient(135deg, #f44336, #d32f2f);
      color: white;
    }

    .product-actions {
      position: absolute;
      bottom: 15px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 8px;
      opacity: 0;
      visibility: hidden;
      transition: var(--transition);
      z-index: 3;
    }

    .product-card:hover .product-actions {
      opacity: 1;
      visibility: visible;
      bottom: 20px;
    }

    .action-btn {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background: white;
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: var(--transition);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      color: var(--text-color);
    }

    .action-btn:hover {
      background: var(--primary-color);
      color: white;
      transform: scale(1.12);
      box-shadow: 0 6px 20px rgba(255, 133, 162, 0.35);
    }

    .product-info {
      padding: 14px 14px 16px;
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .product-category {
      font-size: 0.7rem;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      margin-bottom: 6px;
      font-weight: 600;
    }

    .product-name {
      font-size: 0.92rem;
      font-weight: 700;
      margin-bottom: 8px;
      color: #0f172a;
      line-height: 1.35;
      min-height: 2.7em;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .product-price {
      display: flex;
      align-items: baseline;
      gap: 8px;
      margin-bottom: 12px;
      flex-wrap: wrap;
    }

    .current-price {
      font-size: 1.15rem;
      font-weight: 800;
      color: var(--primary-color);
    }

    .location-chip {
      display: inline-block;
      margin-bottom: 10px;
      padding: 6px 10px;
      border-radius: 999px;
      font-size: 0.72rem;
      font-weight: 700;
      background: #fdf2f8;
      color: #be185d;
      border: 1px solid #fbcfe8;
    }

    .add-to-cart-btn {
      width: 100%;
      padding: 10px;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      color: white;
      border: none;
      border-radius: 10px;
      font-weight: 700;
      cursor: pointer;
      transition: var(--transition);
      text-transform: uppercase;
      font-size: 0.78rem;
      letter-spacing: 0.5px;
      box-shadow: 0 4px 12px rgba(255, 133, 162, 0.25);
      margin-top: auto;
    }

    .add-to-cart-btn:hover:not(:disabled) {
      transform: translateY(-2px);
    }

    .add-to-cart-btn:disabled {
      background: linear-gradient(135deg, #cbd5e1, #94a3b8);
      cursor: not-allowed;
      box-shadow: none;
    }

    .stock-info {
      margin-top: 8px;
      font-size: 0.75rem;
      text-align: center;
      color: #64748b;
    }

    .stock-info.low {
      color: #f59e0b;
      font-weight: 700;
    }

    .stock-info.out {
      color: #ef4444;
      font-weight: 700;
    }

    .empty-state {
      grid-column: 1/-1;
      text-align: center;
      padding: 60px 20px;
      color: #94a3b8;
    }

    .empty-state i {
      font-size: 3rem;
      margin-bottom: 15px;
      display: block;
    }

    .toast-notification {
      position: fixed;
      top: 26px;
      right: 26px;
      background: white;
      padding: 18px 20px;
      border-radius: 14px;
      box-shadow: 0 12px 40px rgba(0, 0, 0, 0.18);
      z-index: 9999;
      opacity: 0;
      transform: translateX(360px);
      transition: all 0.35s cubic-bezier(0.68, -0.55, 0.265, 1.55);
      min-width: 320px;
      border-left: 4px solid var(--primary-color);
    }

    .toast-notification.show {
      opacity: 1;
      transform: translateX(0);
    }

    .toast-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 8px;
    }

    .toast-header h6 {
      margin: 0;
      color: var(--primary-color);
      font-weight: 800;
      font-size: 1.05rem;
    }

    .toast-close {
      background: none;
      border: none;
      font-size: 1.4rem;
      cursor: pointer;
      color: #94a3b8;
      transition: var(--transition);
      padding: 0;
      width: 28px;
      height: 28px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
    }

    .toast-close:hover {
      background: #f1f5f9;
      color: var(--primary-color);
    }

    .toast-body {
      color: #475569;
      font-size: 0.92rem;
      line-height: 1.5;
    }

    .modal-overlay {
      display: none;
      position: fixed;
      z-index: 2000;
      inset: 0;
      background: rgba(0,0,0,0.5);
      align-items: center;
      justify-content: center;
      backdrop-filter: blur(4px);
      padding: 18px;
    }

    .modal-overlay.open {
      display: flex;
    }

    .modal-content {
      background: white;
      padding: 26px;
      border-radius: 20px;
      width: 520px;
      max-width: 100%;
      position: relative;
      box-shadow: 0 25px 60px rgba(0,0,0,0.2);
      animation: modalIn 0.18s ease;
    }

    @keyframes modalIn {
      from { transform: scale(0.98); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }

    .modal-close {
      position: absolute;
      right: 14px;
      top: 12px;
      font-size: 24px;
      cursor: pointer;
      color: #94a3b8;
      background: none;
      border: none;
      width: 36px;
      height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 10px;
    }

    .modal-close:hover {
      background: #f1f5f9;
      color: #475569;
    }

    .modal-img {
      width: 100%;
      height: 240px;
      object-fit: contain;
      background: #f8fafc;
      border-radius: 14px;
      margin-bottom: 16px;
    }

    .modal-title {
      font-size: 1.25rem;
      font-weight: 800;
      margin: 0 0 6px;
      color: #0f172a;
    }

    .modal-number {
      color: #94a3b8;
      font-size: 0.85rem;
      margin-bottom: 14px;
    }

    .modal-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-top: 1px solid #f1f5f9;
      padding-top: 16px;
      margin-top: 16px;
      gap: 12px;
      flex-wrap: wrap;
    }

    .modal-price {
      font-size: 1.9rem;
      font-weight: 900;
      color: var(--primary-color);
    }

    .btn-cart {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      color: white;
      padding: 12px 22px;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      font-weight: 800;
      font-size: 0.95rem;
      transition: 0.2s;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      white-space: nowrap;
    }

    .btn-cart:hover {
      transform: translateY(-1px);
    }

    .modal-details {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
      margin-top: 12px;
    }

    .detail-chip {
      background: #f8fafc;
      border-radius: 12px;
      padding: 10px 14px;
      border: 1px solid #eef2f7;
    }

    .detail-chip label {
      font-size: 0.72rem;
      color: #94a3b8;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      display: block;
      margin-bottom: 3px;
    }

    .detail-chip span {
      font-size: 0.95rem;
      font-weight: 700;
      color: #1e293b;
    }

    @media (min-width: 1400px) {
      .product-grid { grid-template-columns: repeat(5, 1fr); }
    }

    @media (min-width: 1024px) and (max-width: 1399px) {
      .product-grid { grid-template-columns: repeat(4, 1fr); }
      .product-image { height: 180px; }
    }

    @media (min-width: 768px) and (max-width: 1023px) {
      .product-grid { grid-template-columns: repeat(3, 1fr); }
      .product-image { height: 170px; }
      .filters-row { grid-template-columns: 1fr 200px 140px; }
    }

    @media (max-width: 767px) {
      .product-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
      .product-image { height: 160px; }
      .top-panel { top: 60px; padding: 12px 15px 10px; }
      .main-content { height: calc(100vh - 60px); padding: 0 15px 15px; }
      .filters-row { grid-template-columns: 1fr; }
      .toast-notification { right: 10px; top: 10px; min-width: auto; width: calc(100% - 20px); }
    }

    @media (max-width: 400px) {
      .product-grid { grid-template-columns: 1fr; }
      .product-image { height: 220px; }
    }
  </style>
</head>

<body>
  @include('ManagementSystemViews.AdminViews.Layouts.navbar')

  <div class="top-panel">
    <div class="stats-row">
      <div class="stat-chip">Total Items: <strong id="totalCount">—</strong></div>
      <div class="stat-chip">In Stock: <strong id="inStockCount">—</strong></div>
      <div class="stat-chip"><span id="itemCount">Loading items...</span></div>
    </div>

    <div class="filters-row">
      <div class="search-box">
        <i class="bi bi-search"></i>
        <input id="searchInput" type="text" placeholder="Search products by name..." autocomplete="off" />
      </div>

      <div class="select-box">
        <select id="categorySelect">
          <option value="">All Categories</option>
        </select>
      </div>

      <button id="updateBtn" type="button" class="update-btn" onclick="updateItems()">
        <i class="bi bi-arrow-repeat"></i>
        <span>Update</span>
      </button>
    </div>
  </div>

  <div class="main-content">
    <div class="container">
      <div id="itemGrid" class="product-grid"></div>
    </div>
  </div>

  <div id="itemModal" class="modal-overlay" role="dialog" aria-modal="true" aria-label="Item detail" onclick="closeModal()">
    <div class="modal-content" onclick="event.stopPropagation()">
      <button class="modal-close" aria-label="Close modal" onclick="closeModal()">×</button>
      <div id="modalBody">
        <p style="text-align:center;color:#94a3b8;">Loading details...</p>
      </div>
    </div>
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

    function stockBadge(qty) {
      qty = Math.round(qty || 0);
      if (qty <= 0) return `<span class="stock-badge out-of-stock">Out of Stock</span>`;
      if (qty < 5) return `<span class="stock-badge low-stock">Low: ${qty}</span>`;
      return `<span class="stock-badge in-stock">In Stock</span>`;
    }

    function stockInfo(qty) {
      qty = Math.round(qty || 0);
      if (qty <= 0) return `<div class="stock-info out">Currently unavailable</div>`;
      if (qty < 5) return `<div class="stock-info low">Only ${qty} items left!</div>`;
      return `<div class="stock-info">${qty} items left</div>`;
    }

    let currentFiltered = [...PRODUCTS];

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
        const matchName = !q || name.includes(q);
        const matchCat = !cat || category === cat;
        return matchName && matchCat;
      });

      renderItems(currentFiltered);
    }

    function renderStats(data) {
      document.getElementById('totalCount').textContent = PRODUCTS.length;
      document.getElementById('inStockCount').textContent = PRODUCTS.filter(p => (p.inventory || 0) > 0).length;
      document.getElementById('itemCount').textContent = `${data.length} product(s) found`;
    }

    function renderItems(data) {
      const grid = document.getElementById('itemGrid');

      renderStats(data);

      if (!data.length) {
        grid.innerHTML = `<div class="empty-state"><i class="bi bi-box"></i><p>No products found.</p></div>`;
        return;
      }

      grid.innerHTML = data.map(item => {
        const inventory = Math.round(item.inventory || 0);
        const disabled = inventory <= 0 ? 'disabled' : '';
        const btnText = inventory <= 0 ? 'Out of Stock' : 'Add to Cart';
        const locationCode = item.defaultLocationCode || item.locationCode || null;

        return `
          <div class="product-card" data-id="${esc(item.id)}">
            <div class="product-image" onclick="viewDetail('${esc(item.id)}')">
              ${stockBadge(inventory)}
              <img
                src="/item-image/${esc(item.id)}"
                alt="${esc(item.displayName)}"
                loading="lazy"
                onerror="this.src='https://placehold.co/200x200/f1f5f9/94a3b8?text=No+Photo'">
              <div class="product-actions">
                <button class="action-btn quick-view" title="Quick View"
                        onclick="event.stopPropagation(); viewDetail('${esc(item.id)}')">
                  <i class="bi bi-eye"></i>
                </button>
                <button class="action-btn favorite-btn" title="Add to Favorites"
                        onclick="event.stopPropagation(); toggleFavorite(this, '${esc(item.id)}', '${esc(item.displayName)}')">
                  <i class="bi bi-heart"></i>
                </button>
              </div>
            </div>

            <div class="product-info">
              <div class="product-category">${esc(item.itemCategoryCode || 'General')}</div>
              <h3 class="product-name">${esc(item.displayName)}</h3>

              ${locationCode ? `<div class="location-chip">Location: ${esc(locationCode)}</div>` : ''}

              <div class="product-price">
                <span class="current-price">${money(item.unitPrice)}</span>
              </div>

              <button class="add-to-cart-btn" ${disabled}
                      onclick="addToCart('${esc(item.id)}', '${esc(item.displayName)}', ${Number(item.unitPrice || 0)})">
                ${btnText}
              </button>

              ${stockInfo(inventory)}
            </div>
          </div>
        `;
      }).join('');
    }

    async function viewDetail(id) {
      const modal = document.getElementById('itemModal');
      const body = document.getElementById('modalBody');

      modal.classList.add('open');
      body.innerHTML = `<p style="text-align:center;color:#94a3b8;">Loading details...</p>`;

      try {
        const res = await fetch(`/pos/item-detail/${encodeURIComponent(id)}`);
        if (!res.ok) throw new Error('Bad response');

        const item = await res.json();
        const locationCode = item.defaultLocationCode || item.locationCode || '—';

        body.innerHTML = `
          <img src="/item-image/${esc(item.id)}" class="modal-img"
               onerror="this.src='https://placehold.co/300x240/f1f5f9/94a3b8?text=No+Photo'">

          <p class="modal-title">${esc(item.displayName)}</p>
          <p class="modal-number"><i class="bi bi-upc"></i> Item #${esc(item.number)}</p>

          <div class="modal-details">
            <div class="detail-chip">
              <label>Category</label>
              <span>${esc(item.itemCategoryCode || '—')}</span>
            </div>
            <div class="detail-chip">
              <label>Unit</label>
              <span>${esc(item.baseUnitOfMeasureCode || '—')}</span>
            </div>
            <div class="detail-chip">
              <label>Inventory</label>
              <span>${Math.round(item.inventory || 0)} units</span>
            </div>
            <div class="detail-chip">
              <label>Tax Included</label>
              <span>${item.priceIncludesTax ? 'Yes' : 'No'}</span>
            </div>
            <div class="detail-chip">
              <label>Location</label>
              <span>${esc(locationCode)}</span>
            </div>
          </div>

          <div class="modal-footer">
            <span class="modal-price">${money(item.unitPrice)}</span>
            <button class="btn-cart"
                    onclick="addToCart('${esc(item.id)}', '${esc(item.displayName)}', ${Number(item.unitPrice || 0)}); closeModal();">
              <i class="bi bi-cart-plus"></i> Add to Cart
            </button>
          </div>
        `;
      } catch (err) {
        body.innerHTML = `<p style="color:#ef4444;text-align:center;">Error loading item details.</p>`;
      }
    }

    function closeModal() {
      document.getElementById('itemModal').classList.remove('open');
    }

    function addToCart(id, name, price) {
      showToast('Added to Cart', `${name} (${money(price)}) has been added to your cart.`);

      const card = document.querySelector(`.product-card[data-id="${CSS.escape(String(id))}"]`);
      if (!card) return;

      const btn = card.querySelector('.add-to-cart-btn');
      if (!btn) return;

      const originalText = btn.textContent.trim();
      btn.classList.add('adding');
      btn.textContent = '✓ Added!';

      setTimeout(() => {
        btn.classList.remove('adding');
        btn.textContent = originalText;
      }, 1500);
    }

    function toggleFavorite(button, id, name) {
      const icon = button.querySelector('i');
      const adding = icon.classList.contains('bi-heart');

      icon.classList.toggle('bi-heart');
      icon.classList.toggle('bi-heart-fill');

      showToast(
        adding ? 'Added to Favorites' : 'Removed from Favorites',
        adding ? `${name} has been added to your wishlist.` : `${name} has been removed from your wishlist.`
      );
    }

    function showToast(title, message) {
      const existingToast = document.querySelector('.toast-notification');
      if (existingToast) existingToast.remove();

      const toast = document.createElement('div');
      toast.className = 'toast-notification';
      toast.innerHTML = `
        <div class="toast-header">
          <h6>${esc(title)}</h6>
          <button class="toast-close" aria-label="Close toast">&times;</button>
        </div>
        <div class="toast-body">${esc(message)}</div>
      `;

      document.body.appendChild(toast);

      setTimeout(() => toast.classList.add('show'), 10);

      const timeout = setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
      }, 3200);

      toast.querySelector('.toast-close').addEventListener('click', () => {
        clearTimeout(timeout);
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
      });
    }

    async function updateItems() {
      const btn = document.getElementById('updateBtn');
      const oldHtml = btn.innerHTML;

      btn.disabled = true;
      btn.innerHTML = `<i class="bi bi-arrow-repeat spin"></i><span>Updating...</span>`;

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

        showToast('Update Success', `${data.count ?? PRODUCTS.length} item(s) synced successfully.`);
      } catch (error) {
        showToast('Update Failed', error.message || 'Could not update items.');
      } finally {
        btn.disabled = false;
        btn.innerHTML = oldHtml;
      }
    }

    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') closeModal();
    });

    window.addEventListener('DOMContentLoaded', () => {
      buildCategories();
      renderItems(PRODUCTS);

      document.getElementById('searchInput').addEventListener('input', applyFilters);
      document.getElementById('categorySelect').addEventListener('change', applyFilters);
    });
  </script>
</body>
</html>
