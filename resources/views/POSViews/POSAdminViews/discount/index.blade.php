@extends('POSViews.POSAdminViews.app')

@section('title', 'Discount Management')

@push('styles')
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
        --warning:#f59e0b;
        --success:#10b981;
        --info:#3b82f6;
        --shadow:0 8px 24px rgba(15, 23, 42, 0.06);
        --radius:16px;
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
.containter{
    width: 100%;
}
    .main-wrap{
        width:100%;
        min-width:100%;
        height:100vh;    
        overflow-y:auto;
        background: white;
        padding: 14px;
        border-radius: 15px;
        /* padding:18px; */
    }

    .page-title{
        font-size:22px;
        font-weight:700;
        color:#34a6b5;
        margin-bottom:4px;
    }

    .page-subtitle{
        font-size:12px;
        color:#8b95a7;
        margin-bottom:16px;
    }

    .table-card{
        background:#fff;
        border:1px solid var(--border);
        border-radius:16px;
        box-shadow:var(--shadow);
        width:100%;
    }

    .toolbar-card{
        /* padding:14px; */
        /* margin-bottom:14px; */
    }

    .toolbar-row{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:10px;
        flex-wrap:wrap;
        width:100%;
        margin-bottom: 8px;
    }

    .toolbar-left,
    .toolbar-right{
        display:flex;
        align-items:center;
        gap:8px;
        flex-wrap:wrap;
    }

    .toolbar-left{
        flex:1;
        min-width:0;
    }

    .search-box{
        position:relative;
        width:280px;
        max-width:100%;
    }

    .search-box i{
        position:absolute;
        top:50%;
        left:12px;
        transform:translateY(-50%);
        color:#98a2b3;
        font-size:13px;
    }

    .search-box input{
        width:100%;
        height:30px;
        border:1px solid var(--border);
        border-radius:5px;
        /* background:#f7f8fa;/ */
        padding:0 8px 0 34px;
        outline:none;
        font-size:12px;
        color:var(--text);
    }

    .search-box input:focus{
        border-color:var(--primary);
        background:#fff;
    }

    .filter-select{
        height:30px;
        border:1px solid #dfe3e8;
        border-radius:5px;
        background:#fff;
        padding:0 10px;
        font-size:12px;
        color:#5f6b7a;
        outline:none;
        /* min-width:125px; */
    }

    .filter-select:focus{
        border-color:var(--primary);
    }

    .btn-main,
    .btn-light-main,
    .btn-danger-soft,
    .btn-info-soft,
    .btn-success-soft{
        border:none;
        height:30px;
        padding:0 8px;
        border-radius:5px;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:6px;
        font-size:12px;
        font-weight:600;
        text-decoration:none;
        transition:.2s ease;
        cursor:pointer;
        white-space:nowrap;
    }

    .btn-main{
        background:var(--primary);
        color:#fff;
        box-shadow:0 6px 16px rgba(27,184,201,.20);
    }
    .btn-main:hover{
        background:var(--primary-dark);
        color:#fff;
    }

    .btn-light-main{
        background:#fff;
        color:#5f6b7a;
        border:1px solid #dfe3e8;
    }

    .btn-light-main:hover{
        border-color:var(--primary);
        color:var(--primary);
    }

    .btn-danger-soft{
        background:#fff1f2;
        color:var(--danger);
        border:1px solid #ffd5da;
    }

    .btn-danger-soft:hover{
        background:#ffe4e6;
        color:var(--danger);
    }

    .btn-info-soft{
        background:#eff6ff;
        color:var(--info);
        border:1px solid #dbeafe;
    }

    .btn-info-soft:hover{
        background:#dbeafe;
        color:var(--info);
    }

    .btn-success-soft{
        background:#ecfdf5;
        color:var(--success);
        border:1px solid #c6f6d5;
    }

    .btn-success-soft:hover{
        background:#d1fae5;
        color:var(--success);
    }

    .summary-chip{
        height:30px;
        padding:0 8px;
        border-radius:5px;
        /* background:#f3f8fa; */
        color:#5f6b7a;
        display:inline-flex;
        align-items:center;
        gap:6px;
        font-size:12px;
        border:1px solid #e2edf1;
    }

    .summary-chip strong{
        color:var(--primary);
        font-size:13px;
    }

    .table-card{
        /* padding:px; */
    }

    .table-top{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:10px;
        flex-wrap:wrap;
        margin-bottom:12px;
    }

    .table-top h2{
        margin:0;
        font-size:16px;
        font-weight:700;
        color:#1f2937;
    }

    .table-badge{
        background:#eafafb;
        color:var(--primary);
        border:1px solid #d8f3f7;
        border-radius:999px;
        /* padding:6px 12px; */
        font-size:12px;
        font-weight:600;
    }

    .table-wrap{
        overflow:auto;
        width:100%;
        /* height: 100vh; */
        max-height:calc(79vh - 5px);
        /* height: 70%; */
    }
.table-wrap::-webkit-scrollbar {
    width: 0px;
    height: 0px;
}
    .discount-table{
        width:100%;
        /* min-width:1220px; */
        /* height: 70%; */
        border-collapse:separate;
        border-spacing:0;
    }

    .discount-table thead th{
        background:#1bb8c9;
        color:white;
        font-size:11px;
        font-weight:700;
        text-transform:uppercase;
        letter-spacing:.3px;
        padding:8px 10px;
        border-top:1px solid #edf1f5;
        border-bottom:1px solid #edf1f5;
        white-space:nowrap;
        position:sticky;
        top:0;
        z-index:2;

    }
    .discount-table thead{
        border-radius: 8px;
    }
    .discount-table thead th:first-child{
        border-top-left-radius:5px;
    }

    .discount-table thead th:last-child{
        border-top-right-radius:12px;
    }

    .discount-table tbody td{
        padding:8px 10px;
        border-bottom:1px solid #f0f2f5;
        font-size:12px;
        color:#243041;
        vertical-align:middle;
        background:#fff;
        white-space:nowrap;
    }

    .discount-table tbody tr:hover td{
        background:#fcfeff;
    }

    .checkbox-col{
        width:42px;
        text-align:center;
        /* color: #10b981; */
    }

    .checkbox-input{
        width:13px;
        height:13px;
        accent-color:var(--primary);
        cursor:pointer;
    }

    .item-cell{
        display:flex;
        align-items:center;
        gap:10px;
        min-width:220px;
    }

    .item-thumb{
        width:40px;
        height:30px;
        border-radius:10px;
        overflow:hidden;
        background:#eef2f7;
        display:flex;
        align-items:center;
        justify-content:center;
        flex-shrink:0;
    }

    .item-thumb img{
        width:100%;
        height:100%;
        object-fit:cover;
        display:block;
    }

    .thumb-fallback{
        color:#90a4b4;
        font-size:14px;
    }

    .item-name{
        font-size:12px;
        font-weight:700;
        color:#1f2937;
        line-height:1.3;
        white-space:normal;
    }

    .item-sub{
        font-size:11px;
        color:#94a3b8;
        margin-top:2px;
    }

    .status-badge{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-width:80px;
        height:25px;
        padding:0 10px;
        border-radius:5px;
        font-size:11px;
        font-weight:700;
    }

    .status-badge.forever{
        background:rgba(16,185,129,.12);
        color:var(--success);
    }

    .status-badge.scheduled{
        background:rgba(245,158,11,.12);
        color:var(--warning);
    }

    .status-badge.expired{
        background:rgba(239,68,68,.12);
        color:var(--danger);
    }

    .action-group{
        display:flex;
        align-items:center;
        gap:6px;
        flex-wrap:nowrap;
    }

    .icon-btn{
        width:32px;
        height:32px;
        border:none;
        border-radius:9px;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        font-size:13px;
        transition:.2s ease;
        text-decoration:none;
        cursor:pointer;
    }

    .edit-btn{
        background:#eafafb;
        color:var(--primary);
    }

    .edit-btn:hover{
        background:#d8f3f7;
        color:var(--primary);
    }

    .delete-btn{
        background:#fff1f2;
        color:var(--danger);
    }

    .delete-btn:hover{
        background:#ffe4e6;
    }

    .ajax-loading{
        opacity:.65;
        pointer-events:none;
    }

    .empty-box{
        text-align:center;
        padding:40px 16px;
        color:#94a3b8;
    }

    .empty-box i{
        font-size:24px;
        margin-bottom:8px;
        display:block;
        color:#c3cdd8;
    }

    .pagination-bar{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:10px;
        flex-wrap:nowrap;
        padding:5px 14px;
    }

    .page-info{
        font-size:12px;
        color:#64748b;
        font-weight:600;
    }

    .page-controls,
    .footer-left{
        display:flex;
        align-items:center;
        gap:8px;
        flex-wrap:nowrap;
    }

    .footer-right{
        margin-left:auto;
        white-space:nowrap;
    }

    .footer-label{
        font-size:12px;
        color:#64748b;
    }

    .page-indicator{
        font-size:12px;
        font-weight:700;
        color:#334155;
        white-space:nowrap;
    }

    .per-page-right{
        margin-left:0;
        min-width:90px;
    }

    .page-number{
        min-width:36px;
        height:36px;
        border:1px solid #dfe3e8;
        border-radius:10px;
        background:#fff;
        font-size:12px;
        font-weight:600;
        color:#5f6b7a;
        cursor:pointer;
    }

    .page-number.active{
        background:var(--primary);
        color:#fff;
        border-color:var(--primary);
    }

    @media (max-width: 992px){
        body{
            overflow:auto;
        }

        .main-wrap{
            height:auto;
            overflow:visible;
        }

        .toolbar-left,
        .toolbar-right{
            width:100%;
        }

        .search-box{
            width:100%;
        }

        .pagination-bar{
            flex-wrap:wrap;
        }

        .footer-right{
            margin-left:0;
            width:100%;
        }
    }
</style>
@endpush

@section('content')
@php
    $discountItems = collect($items ?? [])->filter(function ($item) {
        return !is_null($item->discount_amount) && (float)$item->discount_amount > 0;
    })->values();

    $categoryOptions = $discountItems->pluck('item_category_code')->filter()->unique()->sort()->values();

    $preparedItems = $discountItems->map(function ($item) {
        $today = \Carbon\Carbon::today();
        $startDate = $item->discount_start_date ? \Carbon\Carbon::parse($item->discount_start_date) : null;
        $endDate = $item->discount_end_date ? \Carbon\Carbon::parse($item->discount_end_date) : null;

        if (!$startDate && !$endDate) {
            $status = 'forever';
        } elseif ($endDate && $endDate->lt($today)) {
            $status = 'expired';
        } else {
            $status = 'scheduled';
        }

        return [
            'id' => $item->id,
            'display_name' => $item->display_name ?? 'No Name',
            'number' => $item->number ?? '-',
            'item_category_code' => $item->item_category_code ?? '-',
            'discount_amount' => number_format((float)($item->discount_amount ?? 0), 2),
            'discount_amount_raw' => (float)($item->discount_amount ?? 0),
            'discount_start_date' => $item->discount_start_date ? \Carbon\Carbon::parse($item->discount_start_date)->format('d M Y') : '-',
            'discount_end_date' => $item->discount_end_date ? \Carbon\Carbon::parse($item->discount_end_date)->format('d M Y') : 'Forever',
            'discount_status' => $status,
            'image_url' => $item->image_url ?? '',
            'edit_url' => route('discounts.edit', $item->id),
            'delete_url' => route('discounts.destroy', $item->id),
        ];
    })->values();
@endphp

<main class="main-wrap">
    <h1 class="page-title">Discount Management</h1>
    <div id="ajaxAlertBox"></div>

    @if(session('success'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm mb-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="toolbar-card">
        <div class="toolbar-row">
            <div class="toolbar-left">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="discountSearch" placeholder="Search item, code, category...">
                </div>

                <select id="statusFilter" class="filter-select">
                    <option value="">All Status</option>
                    <option value="scheduled">Scheduled</option>
                    <option value="forever">Forever</option>
                    <option value="expired">Expired</option>
                </select>

                <select id="categoryFilter" class="filter-select">
                    <option value="">All Category</option>
                    @foreach($categoryOptions as $category)
                        <option value="{{ strtolower($category) }}">{{ $category }}</option>
                    @endforeach
                </select>

            </div>

            <div class="toolbar-right">
                {{-- <div class="summary-chip">
                    <span>Total Discount Items</span>
                    <strong id="discountCount">{{ $preparedItems->count() }}</strong>
                </div> --}}

                <button type="button" id="selectVisibleBtn" class="btn-light-main">
                    <i class="bi bi-check2-square"></i>
                    Select Visible
                </button>

                <a href="{{ route('discounts.create') }}" class="btn-main">
                    <i class="bi bi-plus-lg"></i>
                    Add Discount
                </a>
            </div>
        </div>
    </div>

    <div class="table-card">

        <div class="table-wrap">
            <table class="discount-table">
                <thead>
                    <tr>
                        <th class="checkbox-col">
                            <input type="checkbox" id="masterCheckbox" class="checkbox-input">
                        </th>
                        <th>#</th>
                        <th>Item</th>
                        <th>Item No.</th>
                        <th>Category</th>
                        <th>Discount %</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody id="discountTableBody"></tbody>
            </table>
        </div>

        <div id="emptyState" style="display:none;">
            <div class="empty-box">
                <i class="bi bi-search"></i>
                <div>No matching discount found.</div>
            </div>
        </div>

        <div class="pagination-bar">
            <div class="footer-left">
                <span class="footer-label">Show</span>
                <select id="perPageFilter" class="filter-select per-page-right">
                    <option value="10">10</option>
                    <option value="25" selected>25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="all">All</option>
                </select>
                <span class="footer-label">items</span>

                <button type="button" class="btn-light-main" id="prevPageBtn">
                    Previous
                </button>

                <span id="pageNumbers" class="page-indicator">Page 1 of 1</span>

                <button type="button" class="btn-light-main" id="nextPageBtn">
                    Next
                </button>
            </div>
            <div class="page-info footer-right" id="pageInfo">Showing 0 of 0 items</div>
        </div>
    </div>
</main>

<script id="discountItemsData" type="application/json">
{!! json_encode($preparedItems, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken       = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    const allItems        = JSON.parse(document.getElementById('discountItemsData').textContent || '[]');

    const searchInput     = document.getElementById('discountSearch');
    const statusFilter    = document.getElementById('statusFilter');
    const categoryFilter  = document.getElementById('categoryFilter');
    const perPageFilter   = document.getElementById('perPageFilter');
    const discountTableBody = document.getElementById('discountTableBody');
    const showingCount    = document.getElementById('showingCount');
    const discountCount   = document.getElementById('discountCount');
    const emptyState      = document.getElementById('emptyState');
    const pageInfo        = document.getElementById('pageInfo');
    const prevPageBtn     = document.getElementById('prevPageBtn');
    const nextPageBtn     = document.getElementById('nextPageBtn');
    const pageNumbers     = document.getElementById('pageNumbers');
    const masterCheckbox  = document.getElementById('masterCheckbox');
    const selectVisibleBtn = document.getElementById('selectVisibleBtn');
    const ajaxAlertBox    = document.getElementById('ajaxAlertBox');

    let currentPage = 1;
    let filteredItems = [...allItems];
    let selectedIds = new Set();

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function showAlert(message, type = 'success') {
        ajaxAlertBox.innerHTML = `
            <div class="alert alert-${type} rounded-4 border-0 shadow-sm mb-3">
                ${escapeHtml(message)}
            </div>
        `;
        setTimeout(() => {
            ajaxAlertBox.innerHTML = '';
        }, 2500);
    }

    function getPerPage() {
        return perPageFilter.value === 'all' ? 'all' : parseInt(perPageFilter.value, 10);
    }

    function filterItems() {
        const keyword = (searchInput.value || '').trim().toLowerCase();
        const status = (statusFilter.value || '').trim().toLowerCase();
        const category = (categoryFilter.value || '').trim().toLowerCase();

        filteredItems = allItems.filter(item => {
            const searchText = `${item.display_name} ${item.number} ${item.item_category_code}`.toLowerCase();
            const itemStatus = (item.discount_status || '').toLowerCase();
            const itemCategory = (item.item_category_code || '').toLowerCase();

            const matchKeyword = !keyword || searchText.includes(keyword);
            const matchStatus = !status || itemStatus === status;
            const matchCategory = !category || itemCategory === category;

            return matchKeyword && matchStatus && matchCategory;
        });

        currentPage = 1;
        renderTable();
    }

    function getVisibleItems() {
        const perPage = getPerPage();
        if (perPage === 'all') {
            return filteredItems;
        }
        const start = (currentPage - 1) * perPage;
        return filteredItems.slice(start, start + perPage);
    }

    function renderTable() {
        const perPage = getPerPage();
        const total = filteredItems.length;
        const totalPages = perPage === 'all' ? 1 : Math.max(1, Math.ceil(total / perPage));
        currentPage = Math.min(currentPage, totalPages);

        const visibleItems = getVisibleItems();
        const startIndex = total === 0 ? 0 : (perPage === 'all' ? 1 : ((currentPage - 1) * perPage) + 1);
        const endIndex = perPage === 'all' ? total : Math.min(currentPage * perPage, total);

        discountTableBody.innerHTML = visibleItems.map((item, index) => {
            const checked = selectedIds.has(item.id) ? 'checked' : '';
            const imageHtml = item.image_url
                ? `<img src="${escapeHtml(item.image_url)}" alt="Item Image" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                   <div class="thumb-fallback" style="display:none;"><i class="bi bi-image"></i></div>`
                : `<div class="thumb-fallback"><i class="bi bi-image"></i></div>`;

            return `
                <tr data-id="${item.id}">
                    <td class="checkbox-col">
                        <input type="checkbox" class="checkbox-input row-checkbox" data-id="${item.id}" ${checked}>
                    </td>
                    <td>${startIndex + index}</td>
                    <td>
                        <div class="item-cell">
                            <div class="item-thumb">${imageHtml}</div>
                            <div>
                                <div class="item-name">${escapeHtml(item.display_name)}</div>
                                <div class="item-sub">Discount item</div>
                            </div>
                        </div>
                    </td>
                    <td>${escapeHtml(item.number)}</td>
                    <td>${escapeHtml(item.item_category_code)}</td>
                    <td><strong>${escapeHtml(item.discount_amount)}%</strong></td>
                    <td>${escapeHtml(item.discount_start_date)}</td>
                    <td>${escapeHtml(item.discount_end_date)}</td>
                    <td>
                        <span class="status-badge ${escapeHtml(item.discount_status)}">
                            ${escapeHtml(item.discount_status.charAt(0).toUpperCase() + item.discount_status.slice(1))}
                        </span>
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="${escapeHtml(item.edit_url)}" class="icon-btn edit-btn" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <button type="button" class="icon-btn delete-btn ajax-delete-btn" data-id="${item.id}" data-url="${escapeHtml(item.delete_url)}" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');

        emptyState.style.display = total === 0 ? '' : 'none';

        const visibleCount = visibleItems.length;
        if (showingCount) {
            if (perPage === 'all') {
                showingCount.textContent = `Showing ${total}`;
            } else {
                showingCount.textContent = total === 0 ? 'Showing 0' : `Showing ${startIndex}-${endIndex} of ${total}`;
            }
        }
        pageInfo.textContent = `Showing ${visibleCount} of ${total} items`;

        discountCount.textContent = total;
        renderPageNumbers(totalPages);
        updateMasterCheckbox();
        bindRowEvents();
    }

    function renderPageNumbers(totalPages) {
        pageNumbers.textContent = `Page ${totalPages === 0 ? 0 : currentPage} of ${totalPages}`;

        prevPageBtn.disabled = currentPage <= 1;
        nextPageBtn.disabled = currentPage >= totalPages;
    }

    function bindRowEvents() {
        document.querySelectorAll('.row-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const id = Number(this.dataset.id);
                if (this.checked) {
                    selectedIds.add(id);
                } else {
                    selectedIds.delete(id);
                }
                updateMasterCheckbox();
            });
        });

        document.querySelectorAll('.ajax-delete-btn').forEach(button => {
            button.addEventListener('click', async function () {
                const id = Number(this.dataset.id);
                const url = this.dataset.url;

                if (!confirm('Delete this discount?')) return;

                const row = this.closest('tr');
                row.classList.add('ajax-loading');

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            _method: 'DELETE'
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Failed to delete discount.');
                    }

                    const itemIndex = allItems.findIndex(item => item.id === id);
                    if (itemIndex !== -1) {
                        allItems.splice(itemIndex, 1);
                    }
                    selectedIds.delete(id);
                    filterItems();
                    showAlert('Discount deleted successfully.');
                } catch (error) {
                    row.classList.remove('ajax-loading');
                    showAlert(error.message || 'Something went wrong.', 'danger');
                }
            });
        });
    }

    function updateMasterCheckbox() {
        const visibleCheckboxes = Array.from(document.querySelectorAll('.row-checkbox'));
        if (!visibleCheckboxes.length) {
            masterCheckbox.checked = false;
            masterCheckbox.indeterminate = false;
            return;
        }

        const checkedCount = visibleCheckboxes.filter(cb => cb.checked).length;
        masterCheckbox.checked = checkedCount > 0 && checkedCount === visibleCheckboxes.length;
        masterCheckbox.indeterminate = checkedCount > 0 && checkedCount < visibleCheckboxes.length;
    }

    searchInput.addEventListener('input', filterItems);
    statusFilter.addEventListener('change', filterItems);
    categoryFilter.addEventListener('change', filterItems);
    perPageFilter.addEventListener('change', function () {
        currentPage = 1;
        renderTable();
    });

    prevPageBtn.addEventListener('click', function () {
        if (currentPage > 1) {
            currentPage--;
            renderTable();
        }
    });

    nextPageBtn.addEventListener('click', function () {
        const perPage = getPerPage();
        const totalPages = perPage === 'all' ? 1 : Math.max(1, Math.ceil(filteredItems.length / perPage));
        if (currentPage < totalPages) {
            currentPage++;
            renderTable();
        }
    });

    masterCheckbox.addEventListener('change', function () {
        document.querySelectorAll('.row-checkbox').forEach(checkbox => {
            checkbox.checked = masterCheckbox.checked;
            const id = Number(checkbox.dataset.id);
            if (masterCheckbox.checked) {
                selectedIds.add(id);
            } else {
                selectedIds.delete(id);
            }
        });
        updateMasterCheckbox();
    });

    selectVisibleBtn.addEventListener('click', function () {
        document.querySelectorAll('.row-checkbox').forEach(checkbox => {
            checkbox.checked = true;
            selectedIds.add(Number(checkbox.dataset.id));
        });
        updateMasterCheckbox();
    });

    filterItems();
});
</script>
@endpush
