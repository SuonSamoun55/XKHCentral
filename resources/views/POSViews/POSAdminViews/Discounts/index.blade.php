@extends('Layout.POSAdmin.app')
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

    $scheduledCount = $discountItems->filter(fn($i) => strtolower($i->discount_status ?? '') === 'scheduled')->count();
    $expiredCount   = $discountItems->filter(fn($i) => strtolower($i->discount_status ?? '') === 'expired')->count();
    $activeCount    = $discountItems->filter(fn($i) => in_array(strtolower($i->discount_status ?? ''), ['active', 'forever']))->count();

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
    <div class="alert-container" id="alertContainer"></div>

    {{-- Phone-only header: replaces the stat cards with a compact title. The
         Add Discount button lives in .bulk-row below instead (see there). --}}
   

    {{-- ---- Stats bar ---- --}}
    <div class="dm-stats-bar">
        <div class="dm-stats-row">
            <div class="dm-stat-item">
                <img src="{{ asset('/images/AdminPOS/loyalty.png') }}" alt="Active Discount">
                <div class="dm-stat-divider"></div>
                <div class="dm-stat-text">
                    <div class="dm-stat-label">Active Discount</div>
                    <div class="dm-stat-value">{{ $activeCount }}</div>
                    <div class="dm-stat-sub">Currently Running</div>
                </div>
            </div>

            <div class="dm-stat-item">
                <img src="{{ asset('/images/AdminPOS/calendar_month.png') }}" alt="Schedule Discount">
                <div class="dm-stat-divider"></div>
                <div class="dm-stat-text">
                    <div class="dm-stat-label">Schedule Discount</div>
                    <div class="dm-stat-value">{{ $scheduledCount }}</div>
                    <div class="dm-stat-sub">Starting Soon</div>
                </div>
            </div>

            <div class="dm-stat-item">
                <img src="{{ asset('/images/AdminPOS/update_disabled.png') }}" alt="Expired">
                <div class="dm-stat-divider"></div>
                <div class="dm-stat-text">
                    <div class="dm-stat-label">Expired</div>
                    <div class="dm-stat-value">{{ $expiredCount }}</div>
                    <div class="dm-stat-sub">No Longer Coming</div>
                </div>
            </div>
        </div>

        <a href="{{ route('discounts.create') }}" class="btn-main dm-add-btn">
            Add Discount
            <i class="bi bi-plus-circle"></i>
        </a>
    </div>

    <div class="toolbar-card">
        <div class="toolbar-row">
            <div class="toolbar-left">
                <div class="search-filter-row">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" id="discountSearch" placeholder="Search by name or ID">
                    </div>

                    <button type="button" class="mobile-date-toggle-btn" id="mobileDateToggleBtn" aria-label="Filter by date">
                        <img src="{{ asset('images/AdminPOS/calendar (3).png') }}" alt="" class="mobile-toggle-icon">
                    </button>

                    <button type="button" class="mobile-filter-toggle-btn" id="mobileFilterToggleBtn" aria-label="Show filters">
                        <i class="bi bi-sliders2"></i>
                    </button>
                </div>

                <div class="filter-controls-row" id="filterControlsRow">
                    <select id="statusFilter" class="filter-select">
                        <option value="">All Status</option>
                        <option value="__active__">Active</option>
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

                    <label class="date-filter" onclick="document.getElementById('dateFilter').showPicker && document.getElementById('dateFilter').showPicker()">
                        <i class="bi bi-calendar3"></i>
                        <input type="date" id="dateFilter">
                    </label>
                </div>
            </div>
        </div>
    </div>

    {{-- Phone-only status pills — mirror of #statusFilter's Active/Expired/Scheduled buckets --}}
    <div class="status-tab-row" id="statusTabRow">
        <button type="button" class="status-tab-btn active" data-status="">All <span>({{ $discountItems->count() }})</span></button>
        <button type="button" class="status-tab-btn" data-status="__active__">Active <span>({{ $activeCount }})</span></button>
        <button type="button" class="status-tab-btn" data-status="expired">Expired <span>({{ $expiredCount }})</span></button>
        <button type="button" class="status-tab-btn" data-status="scheduled">Scheduled <span>({{ $scheduledCount }})</span></button>
    </div>

    <div class="bulk-row">
        <div class="bulk-row-left">
            <label class="select-all-check">
                <input type="checkbox" id="selectAllCheckbox">
                <span>Select all</span>
            </label>

            {{-- Phone-only: appears next to Select all only once something is checked. --}}
            <button type="button" id="mobileDeleteSelectedBtn" class="js-delete-selected-btn mobile-delete-icon-btn" title="Delete selected" aria-label="Delete selected">
                <i class="bi bi-trash"></i>
            </button>
        </div>

        <div class="bulk-row-right">
            <button type="button" id="unselectAllLink" class="bulk-link">Unselect all</button>

            <div class="bulk-menu-wrap">
                <button type="button" id="bulkMenuBtn" class="bulk-menu-btn"><i class="bi bi-three-dots"></i></button>
                <div id="bulkMenuDropdown" class="bulk-menu-dropdown">
                    <button type="button" id="deleteSelectedBtn" class="js-delete-selected-btn danger">
                        <i class="bi bi-trash"></i> Delete Selected
                    </button>
                </div>
            </div>

            {{-- Phone-only: relocated from .mobile-page-header --}}
            <a href="{{ route('discounts.create') }}" class="mobile-add-btn">
                Add Discount
                <i class="bi bi-plus-circle"></i>
            </a>
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

<div class="modal fade confirm-action-modal" id="confirmActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content confirm-action-content">
            <div class="modal-body confirm-action-body">
                <h5 class="confirm-action-title">Are you sure?</h5>
                <p class="confirm-action-message" id="confirmActionMessage"></p>
            </div>
            <div class="modal-footer confirm-action-footer">
                <button type="button" class="confirm-action-delete-btn" id="confirmActionConfirmBtn">Delete</button>
                <button type="button" class="confirm-action-cancel-btn" data-bs-dismiss="modal">Cancel Request</button>
            </div>
        </div>
    </div>
</div>

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
    const dateFilter      = document.getElementById('dateFilter');
    const perPageFilter   = document.getElementById('perPageFilter');
    const discountTableBody = document.getElementById('discountTableBody');
    const emptyState      = document.getElementById('emptyState');
    const pageInfo        = document.getElementById('pageInfo');
    const prevPageBtn     = document.getElementById('prevPageBtn');
    const nextPageBtn     = document.getElementById('nextPageBtn');
    const pageNumbers     = document.getElementById('pageNumbers');
    const masterCheckbox  = document.getElementById('masterCheckbox');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const unselectAllLink = document.getElementById('unselectAllLink');
    const bulkMenuBtn     = document.getElementById('bulkMenuBtn');
    const bulkMenuDropdown= document.getElementById('bulkMenuDropdown');
    const deleteSelectedBtns = Array.from(document.querySelectorAll('.js-delete-selected-btn'));
    const mobileDeleteSelectedBtn = document.getElementById('mobileDeleteSelectedBtn');
    const alertContainer  = document.getElementById('alertContainer');
    const mobileFilterToggleBtn = document.getElementById('mobileFilterToggleBtn');
    const filterControlsRow = document.getElementById('filterControlsRow');
    const statusTabButtons  = Array.from(document.querySelectorAll('.status-tab-btn'));

    let currentPage = 1;
    let filteredItems = [...allItems];
    let selectedIds = new Set();
    let currentStatusTab = '';

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function showAlert(message, type = 'success') {
        if (!alertContainer) return;
        const el = document.createElement('div');
        el.className = `custom-alert alert-${type}`;
        el.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'}"></i><span>${escapeHtml(message)}</span>`;
        alertContainer.appendChild(el);
        setTimeout(() => {
            el.classList.add('fade-out');
            setTimeout(() => el.remove(), 300);
        }, 4000);
    }

    let bsConfirmModal = null;
    let pendingConfirmCallback = null;
    const confirmModalEl = document.getElementById('confirmActionModal');
    if (confirmModalEl) {
        bsConfirmModal = new bootstrap.Modal(confirmModalEl);
    }
    document.getElementById('confirmActionConfirmBtn')?.addEventListener('click', function () {
        bsConfirmModal?.hide();
        const callback = pendingConfirmCallback;
        pendingConfirmCallback = null;
        callback?.();
    });

    function showConfirmModal(message, onConfirm) {
        const messageEl = document.getElementById('confirmActionMessage');
        if (messageEl) messageEl.textContent = message;
        pendingConfirmCallback = onConfirm;
        bsConfirmModal?.show();
    }

    @if(session('success'))
        showAlert(@json(session('success')), 'success');
    @endif

    function getDeleteUrlById(id) {
        const item = allItems.find(entry => Number(entry.id) === Number(id));
        return item ? item.delete_url : null;
    }

    function getPerPage() {
        return perPageFilter.value === 'all' ? 'all' : parseInt(perPageFilter.value, 10);
    }

    function filterItems() {
        const keyword = (searchInput.value || '').trim().toLowerCase();
        const category = (categoryFilter.value || '').trim().toLowerCase();
        const dateValue = dateFilter.value;

        filteredItems = allItems.filter(item => {
            const searchText = `${item.display_name} ${item.number} ${item.item_category_code}`.toLowerCase();
            const itemStatus = (item.discount_status || '').toLowerCase();
            const itemCategory = (item.item_category_code || '').toLowerCase();

            const matchKeyword = !keyword || searchText.includes(keyword);
            let matchStatus = true;
            if (currentStatusTab === '__active__') {
                matchStatus = itemStatus === 'active' || itemStatus === 'forever';
            } else if (currentStatusTab) {
                matchStatus = itemStatus === currentStatusTab;
            }
            const matchCategory = !category || itemCategory === category;

            let matchDate = true;
            if (dateValue) {
                const target = new Date(dateValue).toDateString();
                const startOk = item.discount_start_date && item.discount_start_date !== '-' &&
                    new Date(item.discount_start_date).toDateString() === target;
                matchDate = !!startOk;
            }

            return matchKeyword && matchStatus && matchCategory && matchDate;
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
                            <div class="item-info">
                                <div class="item-name-row">
                                    <div class="item-name">${escapeHtml(item.display_name)}</div>
                                    <div class="item-off">${escapeHtml(item.discount_amount)}% OFF</div>
                                </div>
                                <div class="item-sub">Discount item</div>
                                <div class="item-meta-row">
                                    <span class="item-date-range">${escapeHtml(item.discount_start_date)} &rarr; ${escapeHtml(item.discount_end_date)}</span>
                                    <span class="status-badge status-badge-mobile ${escapeHtml(item.discount_status)}">
                                        ${escapeHtml(item.discount_status.charAt(0).toUpperCase() + item.discount_status.slice(1))}
                                    </span>
                                </div>
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

                        <div class="row-menu-wrap">
                            <button type="button" class="row-menu-btn" aria-label="More actions">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <div class="row-menu-dropdown">
                                <a href="${escapeHtml(item.edit_url)}" class="row-menu-item">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <button type="button" class="row-menu-item row-menu-item-danger ajax-delete-btn" data-id="${item.id}" data-url="${escapeHtml(item.delete_url)}">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');

        emptyState.style.display = total === 0 ? '' : 'none';

        const visibleCount = visibleItems.length;
        pageInfo.textContent = `Showing ${visibleCount} of ${total} items`;

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
            button.addEventListener('click', function () {
                const id = Number(this.dataset.id);
                const url = this.dataset.url;
                const row = this.closest('tr');

                showConfirmModal('This action is permanent and cannot be undone. This discount will be deleted.', async function () {
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
                            body: JSON.stringify({ _method: 'DELETE' })
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
        });

        document.querySelectorAll('.row-menu-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.stopPropagation();
                const dropdown = this.nextElementSibling;
                const wasOpen = dropdown.classList.contains('show');
                document.querySelectorAll('.row-menu-dropdown.show').forEach(d => d.classList.remove('show'));
                if (!wasOpen) dropdown.classList.add('show');
            });
        });
    }

    document.addEventListener('click', function () {
        document.querySelectorAll('.row-menu-dropdown.show').forEach(d => d.classList.remove('show'));
    });

    function updateMasterCheckbox() {
        const visibleCheckboxes = Array.from(document.querySelectorAll('.row-checkbox'));
        if (!visibleCheckboxes.length) {
            masterCheckbox.checked = false;
            masterCheckbox.indeterminate = false;
            if (selectAllCheckbox) { selectAllCheckbox.checked = false; selectAllCheckbox.indeterminate = false; }
            updateBulkDeleteButtonState();
            return;
        }

        const checkedCount = visibleCheckboxes.filter(cb => cb.checked).length;
        const allChecked = checkedCount > 0 && checkedCount === visibleCheckboxes.length;
        const someChecked = checkedCount > 0 && checkedCount < visibleCheckboxes.length;

        masterCheckbox.checked = allChecked;
        masterCheckbox.indeterminate = someChecked;

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked;
        }

        updateBulkDeleteButtonState();
    }

    function updateBulkDeleteButtonState() {
        const hasSelection = selectedIds.size > 0;
        deleteSelectedBtns.forEach(btn => { btn.disabled = !hasSelection; });
        mobileDeleteSelectedBtn?.classList.toggle('show', hasSelection);
    }

    function syncStatusTabsUI() {
        statusTabButtons.forEach(btn => {
            btn.classList.toggle('active', btn.dataset.status === currentStatusTab);
        });
    }

    searchInput.addEventListener('input', filterItems);
    statusFilter.addEventListener('change', function () {
        currentStatusTab = statusFilter.value;
        syncStatusTabsUI();
        filterItems();
    });
    categoryFilter.addEventListener('change', filterItems);
    dateFilter.addEventListener('change', filterItems);
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

    // Select all / Unselect all — applies across ALL filtered items, not just the visible page
    selectAllCheckbox?.addEventListener('change', function () {
        if (this.checked) {
            filteredItems.forEach(item => selectedIds.add(item.id));
            document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = true);
        } else {
            selectedIds.clear();
            document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
        }
        updateMasterCheckbox();
    });

    unselectAllLink.addEventListener('click', function () {
        selectedIds.clear();
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
        updateMasterCheckbox();
    });

    mobileFilterToggleBtn?.addEventListener('click', function (e) {
        e.stopPropagation();
        filterControlsRow?.classList.toggle('show');
    });

    // Opens the native date picker directly — no need to open the filter
    // panel first. Works even while that panel is collapsed because its
    // mobile "collapsed" CSS keeps children rendered (clipped, not
    // display:none), which showPicker() requires.
    document.getElementById('mobileDateToggleBtn')?.addEventListener('click', function (e) {
        e.stopPropagation();
        const dateInput = document.getElementById('dateFilter');
        if (!dateInput) return;
        if (dateInput.showPicker) {
            dateInput.showPicker();
        } else {
            dateInput.focus();
        }
    });

    statusTabButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            currentStatusTab = this.dataset.status;
            syncStatusTabsUI();
            // Keep the desktop <select> in sync — every pill maps to a real option now.
            statusFilter.value = ['', '__active__', 'scheduled', 'expired'].includes(currentStatusTab) ? currentStatusTab : '';
            filterItems();
        });
    });

    bulkMenuBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        bulkMenuDropdown.classList.toggle('show');
    });
    document.addEventListener('click', function () {
        bulkMenuDropdown.classList.remove('show');
    });

    deleteSelectedBtns.forEach(btn => btn.addEventListener('click', function () {
        const ids = Array.from(selectedIds);
        if (!ids.length) {
            showAlert('Please select at least one discount.', 'danger');
            return;
        }

        const plural = ids.length > 1 ? 's' : '';
        showConfirmModal(`This action is permanent and cannot be undone. ${ids.length} selected discount${plural} will be deleted.`, async function () {
            deleteSelectedBtns.forEach(b => { b.disabled = true; });

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
                        body: JSON.stringify({ _method: 'DELETE' })
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
    }));

    filterItems();
});
</script>
@endpush
