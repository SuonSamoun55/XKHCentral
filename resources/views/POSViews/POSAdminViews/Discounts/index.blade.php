@extends('POSViews.POSAdminViews.app')

@section('title', 'Discount Management')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/POSViews/POSAdminViews/Discounts/index.css') }}">
@endpush

@section('content')
@php
    $discountItems = collect($items ?? [])->filter(function ($item) {
        return (($item->discount_status ?? '') !== 'inactive')
            || (!is_null($item->discount_amount) && (float)$item->discount_amount > 0)
            || !is_null($item->discount_start_date)
            || !is_null($item->discount_end_date);
    })->values();

    $categoryOptions = $discountItems->pluck('item_category_code')->filter()->unique()->sort()->values();

    $preparedItems = $discountItems->map(function ($item) {
        $status = $item->discount_status ?? 'inactive';

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
                <button type="button" id="deleteSelectedBtn" class="btn-danger-soft">
                    <i class="bi bi-trash"></i>
                    Delete Selected
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
    const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
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

    function getDeleteUrlById(id) {
        const item = allItems.find(entry => Number(entry.id) === Number(id));
        return item ? item.delete_url : null;
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
        updateBulkDeleteButtonState();
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
            updateBulkDeleteButtonState();
            return;
        }

        const checkedCount = visibleCheckboxes.filter(cb => cb.checked).length;
        masterCheckbox.checked = checkedCount > 0 && checkedCount === visibleCheckboxes.length;
        masterCheckbox.indeterminate = checkedCount > 0 && checkedCount < visibleCheckboxes.length;
        updateBulkDeleteButtonState();
    }

    function updateBulkDeleteButtonState() {
        if (!deleteSelectedBtn) return;
        deleteSelectedBtn.disabled = selectedIds.size === 0;
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

    deleteSelectedBtn.addEventListener('click', async function () {
        const ids = Array.from(selectedIds);
        if (!ids.length) {
            showAlert('Please select at least one discount.', 'danger');
            return;
        }

        if (!confirm(`Delete ${ids.length} selected discount(s)?`)) return;

        deleteSelectedBtn.disabled = true;

        const results = await Promise.allSettled(
            ids.map(async (id) => {
                const url = getDeleteUrlById(id);
                if (!url) {
                    throw new Error(`No delete URL for item ${id}.`);
                }

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
                    throw new Error(`Delete failed for item ${id}.`);
                }

                return id;
            })
        );

        let successCount = 0;
        let failCount = 0;

        results.forEach((result) => {
            if (result.status === 'fulfilled') {
                const id = Number(result.value);
                const itemIndex = allItems.findIndex(item => Number(item.id) === id);
                if (itemIndex !== -1) {
                    allItems.splice(itemIndex, 1);
                }
                selectedIds.delete(id);
                successCount++;
            } else {
                failCount++;
            }
        });
        filterItems();

        if (failCount === 0) {
            showAlert(`Deleted ${successCount} discount(s) successfully.`);
        } else {
            showAlert(`Deleted ${successCount} discount(s). ${failCount} failed.`, 'danger');
        }
    });

    filterItems();
});
</script>
@endpush
