@extends('ManagementSystemViews.AdminViews.Layouts.app')

@section('title', 'User Management')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/views/ManagementSystemViews/AdminViews/Layouts/UserinfoView/UserList.css') }}">
@endpush

@section('content')
<div class="main-wrapper">
    

    <div class="content-area">
        <div class="page-card">
            <div class="page-title">User Management</div>

            <div class="top-bar">
                <div class="left-tools">
                    <div class="user-search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" id="userSearch" class="user-search-input" placeholder="Search by name, email, customer no, phone">
                    </div>
                </div>

                <div class="right-tools-inline">
                    <select id="statusFilter" class="status-select">
                        <option value="">All Connect Status</option>
                        <option value="connected">Connected</option>
                        <option value="not_connected">Not Connected</option>
                    </select>

                    <select id="activeFilter" class="status-select">
                        <option value="">All Activity</option>
                        <option value="online">Online</option>
                        <option value="offline">Offline</option>
                    </select>

                    <a href="{{ route('users.sync') }}" class="sync-btn">
                        <i class="bi bi-arrow-repeat"></i>
                        Sync BC Customer
                    </a>

                    <button type="button" class="delete-selected-btn" id="deleteSelectedBtn">
                        <i class="bi bi-trash"></i>
                        Delete Selected
                    </button>
                </div>
            </div>

            <div class="message-box">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>

            <form id="bulkDeleteForm" method="POST" action="{{ route('users.deleteSelected') }}">
                @csrf

                <div class="table-container">
                    <div class="table-scroll">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="checkAll"></th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Customer No</th>
                                    <th>Connect</th>
                                    <th>Role</th>
                                    <th>Last Seen</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody id="userTableBody">
                                @forelse($customers as $customer)
                                    @php
                                        $displayBcNo = $customer->bc_customer_no ?? '-';
                                        $displayName = $customer->local_name ?? $customer->name ?? '-';
                                        $displayEmail = $customer->local_email ?? $customer->email ?? '-';
                                        $displayPhone = $customer->local_phone ?? $customer->phone ?? '-';
                                        $displayRole = $customer->role ?? '-';

                                        $activityStatus = ($customer->connect_status === 'connected' && ($customer->is_online ?? false))
                                            ? 'online'
                                            : 'offline';

                                        $lastSeenText = $customer->last_seen_at
                                            ? \Carbon\Carbon::parse($customer->last_seen_at)->format('Y-m-d h:i A')
                                            : '-';

                                        $offlineDuration = $customer->offline_duration ?? '-';
                                        $imageToShow = $customer->profile_image_display ?? null;
                                        $imageUrl = $customer->profile_image_display ?? '';
                                        $firstLetter = strtoupper(mb_substr(trim($displayName), 0, 1)) ?: 'U';
                                    @endphp

                                    <tr
                                        class="user-row"
                                        data-row-id="{{ $customer->id }}"
                                        data-name="{{ strtolower($displayName) }}"
                                        data-email="{{ strtolower($displayEmail) }}"
                                        data-id="{{ strtolower($displayBcNo) }}"
                                        data-phone="{{ strtolower($displayPhone) }}"
                                        data-status="{{ $customer->connect_status ?? 'not_connected' }}"
                                        data-active="{{ $activityStatus }}"
                                    >
                                        <td>
                                            <input type="checkbox" class="row-check" name="selected_ids[]" value="{{ $customer->id }}">
                                        </td>

                                        <td>
                                            <div class="avatar-cell">
                                                <div class="avatar-wrap">
                                                    @if(!empty($imageToShow))
                                                        <img
                                                            src="{{ $imageToShow }}"
                                                            alt="User"
                                                            class="avatar-image"
                                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                        >
                                                        <div class="avatar-fallback" style="display:none;">{{ $firstLetter }}</div>
                                                    @else
                                                        <div class="avatar-fallback">{{ $firstLetter }}</div>
                                                    @endif

                                                    <span class="avatar-status-dot {{ $activityStatus === 'online' ? 'avatar-status-online' : 'avatar-status-offline' }}"></span>
                                                </div>

                                                <div class="name-block">
                                                    <span class="name-text">{{ $displayName }}</span>
                                                    <span class="sub-text">
                                                        @if($activityStatus === 'online')
                                                            Online
                                                        @else
                                                            {{ $offlineDuration }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <td title="{{ $displayEmail }}">{{ $displayEmail }}</td>
                                        <td title="{{ $displayBcNo }}">{{ $displayBcNo }}</td>

                                        <td>
                                            @if($customer->connect_status === 'connected')
                                                <span class="status-badge status-connected">Connected</span>
                                            @else
                                                <span class="status-badge status-disconnected">Not Connected</span>
                                            @endif
                                        </td>

                                        <td class="role-text">{{ $displayRole }}</td>
                                        <td title="{{ $lastSeenText }}">{{ $lastSeenText }}</td>

                                        <td>
                                            <div class="action-icons">
                                                @if($customer->connect_status !== 'connected')
                                                    <button
                                                        type="button"
                                                        class="open-user-modal"
                                                        title="Connect"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#userModal"
                                                        data-mode="connect"
                                                        data-id="{{ $customer->id }}"
                                                        data-bcno="{{ $displayBcNo }}"
                                                        data-name="{{ $displayName }}"
                                                        data-email="{{ $displayEmail }}"
                                                        data-phone="{{ $displayPhone }}"
                                                        data-role=""
                                                        data-image-url="{{ $imageUrl }}"
                                                    >
                                                        <i class="bi bi-link-45deg text-success"></i>
                                                    </button>
                                                @else
                                                    <button
                                                        type="button"
                                                        class="open-user-modal"
                                                        title="Edit"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#userModal"
                                                        data-mode="edit"
                                                        data-id="{{ $customer->id }}"
                                                        data-bcno="{{ $displayBcNo }}"
                                                        data-name="{{ $displayName }}"
                                                        data-email="{{ $displayEmail }}"
                                                        data-phone="{{ $displayPhone }}"
                                                        data-role="{{ $displayRole }}"
                                                        data-image-url="{{ $imageUrl }}"
                                                    >
                                                        <i class="bi bi-pencil text-warning"></i>
                                                    </button>
                                                @endif

                                                <a href="javascript:void(0)"
                                                   onclick="showUser('{{ route('admin.users.show', $customer->id) }}')"
                                                   title="View">
                                                    <i class="bi bi-eye text-primary"></i>
                                                </a>

                                                <form method="POST" action="{{ route('users.destroy', $customer->id) }}" onsubmit="return confirm('Are you sure you want to delete this user?')" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="delete-icon" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="noDataRow">
                                        <td colspan="8" class="empty-text">No BC customers found.</td>
                                    </tr>
                                @endforelse

                                <tr id="noResultRow" style="display:none;">
                                    <td colspan="8" class="empty-text">No matching users found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>

            <div class="bottom-bar">
                <div class="pagination-tools">
                    <span>Show</span>
                    <select id="pageSize" class="page-size-select">
                        <option value="10">10</option>
                        <option value="20" selected>20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span>users</span>

                    <button type="button" class="page-btn" id="prevPageBtn">Previous</button>
                    <span class="page-info" id="pageInfo">Page 1</span>
                    <button type="button" class="page-btn" id="nextPageBtn">Next</button>
                </div>

                <div>
                    Showing <strong id="visibleCount">0</strong> of <strong id="totalCount">{{ count($customers) }}</strong> users
                </div>
            </div>
        </div>
    </div>
</div>

@include('ManagementSystemViews.AdminViews.Layouts.UserinfoView.create')
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showUser(url) {
    fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(html => {
            document.getElementById('modalContent').innerHTML = html;
            var myModal = new bootstrap.Modal(document.getElementById('userViewModal'));
            myModal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Could not load user details.");
        });
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('checkAll');
    const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
    const bulkDeleteForm = document.getElementById('bulkDeleteForm');
    const searchInput = document.getElementById('userSearch');
    const statusFilter = document.getElementById('statusFilter');
    const activeFilter = document.getElementById('activeFilter');
    const pageSize = document.getElementById('pageSize');
    const prevPageBtn = document.getElementById('prevPageBtn');
    const nextPageBtn = document.getElementById('nextPageBtn');
    const pageInfo = document.getElementById('pageInfo');
    const visibleCount = document.getElementById('visibleCount');
    const totalCount = document.getElementById('totalCount');
    const tableBody = document.getElementById('userTableBody');
    const tableScroll = document.querySelector('.table-scroll');

    let currentPage = 1;
    let filteredRows = [];

    function getRows() {
        return Array.from(document.querySelectorAll('.user-row'));
    }

    function getVisibleRows() {
        return getRows().filter(function (row) {
            return row.style.display !== 'none';
        });
    }

    function escapeHtml(value) {
        if (value === null || value === undefined) return '';
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function clearHiddenSelections() {
        getRows().forEach(function (row) {
            if (row.style.display === 'none') {
                const checkbox = row.querySelector('.row-check');
                if (checkbox) {
                    checkbox.checked = false;
                }
            }
        });
    }

    function updateCheckAllState() {
        const visibleCheckboxes = getVisibleRows()
            .map(row => row.querySelector('.row-check'))
            .filter(Boolean);

        const checkedVisible = visibleCheckboxes.filter(cb => cb.checked).length;

        checkAll.indeterminate = false;
        checkAll.checked = false;

        if (visibleCheckboxes.length === 0) {
            return;
        }

        if (checkedVisible === visibleCheckboxes.length) {
            checkAll.checked = true;
        } else if (checkedVisible > 0) {
            checkAll.indeterminate = true;
        }
    }

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('row-check')) {
            updateCheckAllState();
        }
    });

    if (checkAll) {
        checkAll.addEventListener('change', function () {
            const visibleRows = getVisibleRows();

            visibleRows.forEach(function (row) {
                const checkbox = row.querySelector('.row-check');
                if (checkbox) {
                    checkbox.checked = checkAll.checked;
                }
            });

            updateCheckAllState();
        });
    }

    if (deleteSelectedBtn && bulkDeleteForm) {
        deleteSelectedBtn.addEventListener('click', function () {
            clearHiddenSelections();

            const checkedBoxes = Array.from(
                bulkDeleteForm.querySelectorAll('.row-check:checked')
            );

            if (checkedBoxes.length === 0) {
                alert('Please select at least one user.');
                return;
            }

            if (confirm('Are you sure you want to delete selected users?')) {
                bulkDeleteForm.submit();
            }
        });
    }

    function getMatchedRows() {
        const rows = getRows();
        const searchValue = (searchInput.value || '').toLowerCase().trim();
        const selectedStatus = statusFilter.value;
        const selectedActive = activeFilter.value;

        return rows.filter(function (row) {
            const name = row.dataset.name || '';
            const email = row.dataset.email || '';
            const id = row.dataset.id || '';
            const phone = row.dataset.phone || '';
            const status = row.dataset.status || '';
            const active = row.dataset.active || '';

            const matchesSearch =
                searchValue === '' ||
                name.includes(searchValue) ||
                email.includes(searchValue) ||
                id.includes(searchValue) ||
                phone.includes(searchValue);

            const matchesStatus =
                selectedStatus === '' || status === selectedStatus;

            const matchesActive =
                selectedActive === '' || active === selectedActive;

            return matchesSearch && matchesStatus && matchesActive;
        });
    }

    function renderTable() {
        const rows = getRows();
        const perPage = parseInt(pageSize.value || '20', 10);
        filteredRows = getMatchedRows();
        const totalFiltered = filteredRows.length;
        const totalPages = Math.max(1, Math.ceil(totalFiltered / perPage));

        if (currentPage > totalPages) {
            currentPage = totalPages;
        }

        const start = (currentPage - 1) * perPage;
        const end = start + perPage;

        rows.forEach(function (row) {
            row.style.display = 'none';
            const checkbox = row.querySelector('.row-check');
            if (checkbox) {
                checkbox.checked = false;
            }
        });

        filteredRows.forEach(function (row, index) {
            if (index >= start && index < end) {
                row.style.display = '';
            }
        });

        const noResultRow = document.getElementById('noResultRow');
        if (noResultRow) {
            noResultRow.style.display = totalFiltered === 0 ? '' : 'none';
        }

        if (visibleCount) {
            visibleCount.textContent = totalFiltered;
        }

        if (pageInfo) {
            pageInfo.textContent = 'Page ' + currentPage + ' of ' + totalPages;
        }

        if (prevPageBtn) {
            prevPageBtn.disabled = currentPage <= 1;
        }

        if (nextPageBtn) {
            nextPageBtn.disabled = currentPage >= totalPages;
        }

        updateCheckAllState();
    }

    function buildRow(customer) {
        const tr = document.createElement('tr');

        const displayName = customer.name || '-';
        const displayEmail = customer.email || '-';
        const displayBcNo = customer.bc_customer_no || '-';
        const displayPhone = customer.phone || '-';
        const displayRole = customer.role || '-';
        const activityStatus = customer.activity_status || 'offline';
        const imageToShow = customer.profile_image_display || '';
        const imageUrl = customer.profile_image_display || '';
        const firstLetter = displayName.trim().charAt(0).toUpperCase() || 'U';
        const subText = activityStatus === 'online' ? 'Online' : (customer.offline_duration || '-');

        tr.className = 'user-row';
        tr.setAttribute('data-row-id', customer.id);
        tr.setAttribute('data-name', displayName.toLowerCase());
        tr.setAttribute('data-email', displayEmail.toLowerCase());
        tr.setAttribute('data-id', displayBcNo.toLowerCase());
        tr.setAttribute('data-phone', displayPhone.toLowerCase());
        tr.setAttribute('data-status', customer.connect_status || 'not_connected');
        tr.setAttribute('data-active', activityStatus);

        let imageHtml = '';
        if (imageToShow) {
            imageHtml = `
                <img
                    src="${escapeHtml(imageToShow)}"
                    alt="User"
                    class="avatar-image"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                >
                <div class="avatar-fallback" style="display:none;">${escapeHtml(firstLetter)}</div>
            `;
        } else {
            imageHtml = `<div class="avatar-fallback">${escapeHtml(firstLetter)}</div>`;
        }

        let actionHtml = '';
        if (customer.connect_status !== 'connected') {
            actionHtml += `
                <button
                    type="button"
                    class="open-user-modal"
                    title="Connect"
                    data-bs-toggle="modal"
                    data-bs-target="#userModal"
                    data-mode="connect"
                    data-id="${escapeHtml(customer.id)}"
                    data-bcno="${escapeHtml(displayBcNo)}"
                    data-name="${escapeHtml(displayName)}"
                    data-email="${escapeHtml(displayEmail)}"
                    data-phone="${escapeHtml(displayPhone)}"
                    data-role=""
                    data-image-url="${escapeHtml(imageUrl)}"
                >
                    <i class="bi bi-link-45deg text-success"></i>
                </button>
            `;
        } else {
            actionHtml += `
                <button
                    type="button"
                    class="open-user-modal"
                    title="Edit"
                    data-bs-toggle="modal"
                    data-bs-target="#userModal"
                    data-mode="edit"
                    data-id="${escapeHtml(customer.id)}"
                    data-bcno="${escapeHtml(displayBcNo)}"
                    data-name="${escapeHtml(displayName)}"
                    data-email="${escapeHtml(displayEmail)}"
                    data-phone="${escapeHtml(displayPhone)}"
                    data-role="${escapeHtml(displayRole)}"
                    data-image-url="${escapeHtml(imageUrl)}"
                >
                    <i class="bi bi-pencil text-warning"></i>
                </button>
            `;
        }

        actionHtml += `
            <a href="${escapeHtml(customer.show_url)}" title="View">
                <i class="bi bi-eye text-primary"></i>
            </a>

            <form method="POST" action="${escapeHtml(customer.destroy_url)}" onsubmit="return confirm('Are you sure you want to delete this user?')" style="display:inline-block;">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="delete-icon" title="Delete">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        `;

        tr.innerHTML = `
            <td>
                <input type="checkbox" class="row-check" name="selected_ids[]" value="${escapeHtml(customer.id)}">
            </td>

            <td>
                <div class="avatar-cell">
                    <div class="avatar-wrap">
                        ${imageHtml}
                        <span class="avatar-status-dot ${activityStatus === 'online' ? 'avatar-status-online' : 'avatar-status-offline'}"></span>
                    </div>

                    <div class="name-block">
                        <span class="name-text">${escapeHtml(displayName)}</span>
                        <span class="sub-text">${escapeHtml(subText)}</span>
                    </div>
                </div>
            </td>

            <td title="${escapeHtml(displayEmail)}">${escapeHtml(displayEmail)}</td>
            <td title="${escapeHtml(displayBcNo)}">${escapeHtml(displayBcNo)}</td>

            <td>
                ${
                    customer.connect_status === 'connected'
                    ? '<span class="status-badge status-connected">Connected</span>'
                    : '<span class="status-badge status-disconnected">Not Connected</span>'
                }
            </td>

            <td class="role-text">${escapeHtml(displayRole)}</td>
            <td title="${escapeHtml(customer.last_seen_at || '-')}">${escapeHtml(customer.last_seen_at || '-')}</td>

            <td>
                <div class="action-icons">
                    ${actionHtml}
                </div>
            </td>
        `;

        return tr;
    }

    function refreshTableBody(customers) {
        const currentScrollTop = tableScroll ? tableScroll.scrollTop : 0;

        const oldNoResultRow = document.getElementById('noResultRow');
        if (oldNoResultRow) {
            oldNoResultRow.remove();
        }

        tableBody.querySelectorAll('.user-row, #noDataRow').forEach(el => el.remove());

        if (!customers.length) {
            const emptyRow = document.createElement('tr');
            emptyRow.id = 'noDataRow';
            emptyRow.innerHTML = `<td colspan="8" class="empty-text">No BC customers found.</td>`;
            tableBody.appendChild(emptyRow);
        } else {
            customers.forEach(customer => {
                const row = buildRow(customer);
                tableBody.appendChild(row);
            });
        }

        const noResultTr = document.createElement('tr');
        noResultTr.id = 'noResultRow';
        noResultTr.style.display = 'none';
        noResultTr.innerHTML = `<td colspan="8" class="empty-text">No matching users found.</td>`;
        tableBody.appendChild(noResultTr);

        if (totalCount) {
            totalCount.textContent = customers.length;
        }

        renderTable();

        if (tableScroll) {
            tableScroll.scrollTop = currentScrollTop;
        }
    }

    function loadUsersSilently() {
        fetch("{{ route('users.data') }}", {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                refreshTableBody(result.data || []);
            }
        })
        .catch(error => {
            console.log('Load users failed:', error);
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            currentPage = 1;
            renderTable();
        });
    }

    if (statusFilter) {
        statusFilter.addEventListener('change', function () {
            currentPage = 1;
            renderTable();
        });
    }

    if (activeFilter) {
        activeFilter.addEventListener('change', function () {
            currentPage = 1;
            renderTable();
        });
    }

    if (pageSize) {
        pageSize.addEventListener('change', function () {
            currentPage = 1;
            renderTable();
        });
    }

    if (prevPageBtn) {
        prevPageBtn.addEventListener('click', function () {
            if (currentPage > 1) {
                currentPage--;
                renderTable();
            }
        });
    }

    if (nextPageBtn) {
        nextPageBtn.addEventListener('click', function () {
            const perPage = parseInt(pageSize.value || '20', 10);
            const totalPages = Math.max(1, Math.ceil(getMatchedRows().length / perPage));

            if (currentPage < totalPages) {
                currentPage++;
                renderTable();
            }
        });
    }

    function sendHeartbeat() {
        fetch("{{ route('heartbeat') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({})
        }).catch(function (error) {
            console.log('Heartbeat failed:', error);
        });
    }

    renderTable();
    sendHeartbeat();

    setInterval(sendHeartbeat, 60000);
    setInterval(loadUsersSilently, 15000);
});
</script>
@endpush
