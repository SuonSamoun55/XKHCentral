{{-- @include('ManagementSystemViews.AdminViews.Layouts.navbar')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/views/ManagementSystemViews/AdminViews/Layouts/UserinfoView/user.css') }}">
</head>
<body>

<div class="main-wrapper">
    @include('ManagementSystemViews.AdminViews.Layouts.aside')

    <div class="content-area">
        <div class="page-card">
            <div class="page-title">User Management</div>

            <div class="top-bar">
                <div class="left-tools">
                    <div class="user-search-box">
                        <i class="bi bi-search"></i>
                        <input
                            type="text"
                            id="userSearch"
                            class="user-search-input"
                            placeholder="Search by name, email, customer no, phone"
                        >
                    </div>
                </div>

                <div class="right-tools-inline">
                    <select id="statusFilter" class="status-select">
                        <option value="">All Status</option>
                        <option value="connected">Connected</option>
                        <option value="not_connected">Not Connected</option>
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
                                    <th>Status</th>
                                    <th>Role</th>
                                    <th>Phone Number</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody id="userTableBody">
                                @forelse($customers as $customer)
                                    <tr
                                        class="user-row"
                                        data-name="{{ strtolower($customer->name ?? '') }}"
                                        data-email="{{ strtolower($customer->email ?? '') }}"
                                        data-id="{{ strtolower($customer->bc_customer_no ?? '') }}"
                                        data-phone="{{ strtolower($customer->phone ?? '') }}"
                                        data-status="{{ $customer->connect_status ?? 'not_connected' }}"
                                    >
                                        <td>
                                            <input
                                                type="checkbox"
                                                class="row-check"
                                                name="selected_ids[]"
                                                value="{{ $customer->id }}"
                                            >
                                        </td>

                                        <td>
                                            <div class="avatar-cell">
                                                <div class="avatar-circle">
                                                    {{ strtoupper(substr($customer->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <span class="name-text">{{ $customer->name ?? '-' }}</span>
                                            </div>
                                        </td>

                                        <td title="{{ $customer->email }}">
                                            {{ $customer->email ?? '-' }}
                                        </td>

                                        <td title="{{ $customer->bc_customer_no }}">
                                            {{ $customer->bc_customer_no ?? '-' }}
                                        </td>

                                        <td>
                                            @if($customer->connect_status === 'connected')
                                                <span class="status-badge status-connected">Connected</span>
                                            @else
                                                <span class="status-badge status-disconnected">Not Connected</span>
                                            @endif
                                        </td>

                                        <td class="role-text">
                                            {{ $customer->role ?? '-' }}
                                        </td>

                                        <td title="{{ $customer->phone }}">
                                            {{ $customer->phone ?? '-' }}
                                        </td>

                                        <td>
                                            <div class="action-icons">
                                                @if($customer->connect_status !== 'connected')
                                                    <button
                                                        type="button"
                                                        class="open-connect-modal"
                                                        title="Connect"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#connectCustomerModal"
                                                        data-id="{{ $customer->id }}"
                                                        data-bcno="{{ $customer->bc_customer_no ?? $customer->number ?? '-' }}"
                                                        data-name="{{ $customer->name ?? $customer->display_name ?? '-' }}"
                                                        data-email="{{ $customer->email ?? '-' }}"
                                                        data-phone="{{ $customer->phone ?? $customer->phone_number ?? '-' }}"
                                                    >
                                                        <i class="bi bi-link-45deg text-success"></i>
                                                    </button>
                                                @else
                                                    <span title="Connected">
                                                        <i class="bi bi-check-circle text-secondary"></i>
                                                    </span>
                                                @endif

                                                <a href="{{ route('users.show', $customer->id) }}" title="View">
                                                    <i class="bi bi-eye text-primary"></i>
                                                </a>

                                                @if($customer->connect_status === 'connected')
                                                    <a href="{{ route('users.edit', $customer->id) }}" title="Edit">
                                                        <i class="bi bi-pencil text-warning"></i>
                                                    </a>
                                                @endif

                                                <form
                                                    method="POST"
                                                    action="{{ route('users.destroy', $customer->id) }}"
                                                    onsubmit="return confirm('Are you sure you want to delete this user?')"
                                                    style="display:inline-block;"
                                                >
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
                <div>
                    Total users:
                    <strong id="visibleCount">{{ count($customers) }}</strong>
                    / {{ count($customers) }}
                </div>
            </div>
        </div>
    </div>
</div>

@include('ManagementSystemViews.AdminViews.Layouts.UserinfoView.create')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkAll = document.getElementById('checkAll');
        const rowChecks = document.querySelectorAll('.row-check');
        const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
        const bulkDeleteForm = document.getElementById('bulkDeleteForm');
        const searchInput = document.getElementById('userSearch');
        const statusFilter = document.getElementById('statusFilter');
        const rows = document.querySelectorAll('.user-row');
        const noResultRow = document.getElementById('noResultRow');
        const visibleCount = document.getElementById('visibleCount');

        function updateCheckAllState() {
            const total = document.querySelectorAll('.row-check').length;
            const checked = document.querySelectorAll('.row-check:checked').length;

            if (checkAll) {
                checkAll.checked = total > 0 && total === checked;
            }
        }

        if (checkAll) {
            checkAll.addEventListener('change', function () {
                rowChecks.forEach(function (checkbox) {
                    if (checkbox.closest('tr').style.display !== 'none') {
                        checkbox.checked = checkAll.checked;
                    }
                });
            });
        }

        rowChecks.forEach(function (checkbox) {
            checkbox.addEventListener('change', updateCheckAllState);
        });

        if (deleteSelectedBtn && bulkDeleteForm) {
            deleteSelectedBtn.addEventListener('click', function () {
                const checked = document.querySelectorAll('.row-check:checked').length;

                if (checked === 0) {
                    alert('Please select at least one user.');
                    return;
                }

                if (confirm('Are you sure you want to delete selected users?')) {
                    bulkDeleteForm.submit();
                }
            });
        }

        function filterTable() {
            const searchValue = (searchInput.value || '').toLowerCase().trim();
            const selectedStatus = statusFilter.value;
            let visibleRows = 0;

            rows.forEach(function (row) {
                const name = row.dataset.name || '';
                const email = row.dataset.email || '';
                const id = row.dataset.id || '';
                const phone = row.dataset.phone || '';
                const status = row.dataset.status || '';

                const matchesSearch =
                    searchValue === '' ||
                    name.includes(searchValue) ||
                    email.includes(searchValue) ||
                    id.includes(searchValue) ||
                    phone.includes(searchValue);

                const matchesStatus =
                    selectedStatus === '' || status === selectedStatus;

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                    const checkbox = row.querySelector('.row-check');
                    if (checkbox) checkbox.checked = false;
                }
            });

            if (visibleCount) {
                visibleCount.textContent = visibleRows;
            }

            if (noResultRow) {
                noResultRow.style.display = visibleRows === 0 ? '' : 'none';
            }

            updateCheckAllState();
        }

        if (searchInput) {
            searchInput.addEventListener('input', filterTable);
        }

        if (statusFilter) {
            statusFilter.addEventListener('change', filterTable);
        }

        const buttons = document.querySelectorAll('.open-connect-modal');
        const form = document.getElementById('connectCustomerForm');

        const modalBcNo = document.getElementById('modalBcNo');
        const modalName = document.getElementById('modalName');
        const modalEmail = document.getElementById('modalEmail');
        const modalPhone = document.getElementById('modalPhone');
        const connectAvatar = document.getElementById('connectAvatar');

        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                const id = this.dataset.id || '';
                const bcno = this.dataset.bcno || '-';
                const name = this.dataset.name || '-';
                const email = this.dataset.email || '-';
                const phone = this.dataset.phone || '-';

                modalBcNo.textContent = bcno;
                modalName.textContent = name;
                modalEmail.textContent = email;
                modalPhone.textContent = phone;
                connectAvatar.textContent = name.charAt(0).toUpperCase();

                form.action = "{{ url('/users/store') }}/" + id;

                form.querySelector('select[name="role"]').value = '';
                form.querySelector('input[name="password"]').value = '';
                form.querySelector('input[name="password_confirmation"]').value = '';
            });
        });
    });
</script>

</body>
</html> --}}
