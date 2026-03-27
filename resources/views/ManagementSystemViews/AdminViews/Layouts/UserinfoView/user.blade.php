{{-- @include('ManagementSystemViews.AdminViews.Layouts.navbar')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            background:#f4f6f8;
            font-family:Arial, Helvetica, sans-serif;
            color:#334155;
        }

        .main-wrapper{
            display:flex;
            gap:10px;
            min-height:100vh;
            padding:8px;
        }

        .content-area{
            flex:1;
            min-width:0;
            overflow:hidden;
        }

        .page-card{
            background:#fff;
            border-radius:14px;
            padding:14px;
            box-shadow:0 8px 24px rgba(15, 23, 42, 0.05);
            min-height:calc(100vh - 16px);
            overflow:hidden;
        }

        .page-title{
            font-size:20px;
            font-weight:700;
            color:#18bfd0;
            margin-bottom:8px;
        }

        .top-bar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:10px;
            margin-bottom:10px;
            flex-wrap:wrap;
        }

        .left-tools{
            flex:1;
            min-width:240px;
        }

        .right-tools-inline{
            display:flex;
            align-items:center;
            gap:8px;
            flex-wrap:wrap;
        }

        .user-search-box{
            position:relative;
            width:100%;
            max-width:320px;
        }

        .user-search-box i{
            position:absolute;
            left:10px;
            top:50%;
            transform:translateY(-50%);
            color:#64748b;
            font-size:13px;
            pointer-events:none;
        }

        .user-search-input{
            width:100%;
            height:34px;
            padding:0 10px 0 32px;
            outline:none;
            font-size:12px;
            color:#334155;
            background:#fff;
            border:1px solid #d6dde5;
            border-radius:5px;
            transition:0.2s ease;
        }

        .user-search-input:focus{
            border-color:#18bfd0;
            box-shadow:0 0 0 2px rgba(24,191,208,0.10);
        }

        .sync-btn,
        .delete-selected-btn{
            height:34px;
            padding:0 12px;
            border:none;
            border-radius:5px;
            font-size:12px;
            font-weight:600;
            text-decoration:none;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:6px;
            white-space:nowrap;
            transition:0.2s ease;
            cursor:pointer;
        }

        .sync-btn{
            background:#18bfd0;
            color:#fff;
        }

        .sync-btn:hover{
            background:#0daebe;
            color:#fff;
        }

        .delete-selected-btn{
            background:#ef4444;
            color:#fff;
        }

        .delete-selected-btn:hover{
            background:#dc2626;
            color:#fff;
        }

        .status-select{
            height:34px;
            min-width:140px;
            border:1px solid #d6dde5;
            border-radius:5px;
            padding:0 8px;
            background:#fff;
            font-size:12px;
            color:#475569;
            outline:none;
        }

        .message-box{
            margin-bottom:10px;
        }

        .alert{
            padding:8px 12px;
            font-size:12px;
            margin-bottom:8px;
        }

        .table-container{
            border:1px solid #dce5ec;
            border-radius:8px;
            background:#fff;
            overflow:hidden;
            height:calc(100vh - 230px);
            min-height:420px;
        }

        .table-scroll{
            width:100%;
            height:100%;
            overflow:auto;
        }

        .table-scroll::-webkit-scrollbar{
            width:8px;
            height:8px;
        }

        .table-scroll::-webkit-scrollbar-thumb{
            background:#cbd5e1;
            border-radius:8px;
        }

        .table{
            width:100%;
            margin-bottom:0;
            table-layout:fixed;
            min-width:980px;
        }

        .table thead th{
            background:#18bfd0;
            color:#fff;
            border:none;
            font-size:12px;
            padding:10px 6px;
            white-space:nowrap;
            position:sticky;
            top:0;
            z-index:5;
            font-weight:600;
        }

        .table tbody td{
            padding:8px 6px;
            font-size:12px;
            color:#475569;
            border-color:#e8eef4;
            vertical-align:middle;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
        }

        .table tbody tr:hover td{
            background:#f8fcfd;
        }

        .table th:nth-child(1),
        .table td:nth-child(1){
            width:40px;
            text-align:center;
        }

        .table th:nth-child(2),
        .table td:nth-child(2){
            width:180px;
        }

        .table th:nth-child(3),
        .table td:nth-child(3){
            width:220px;
        }

        .table th:nth-child(4),
        .table td:nth-child(4){
            width:110px;
        }

        .table th:nth-child(5),
        .table td:nth-child(5){
            width:120px;
        }

        .table th:nth-child(6),
        .table td:nth-child(6){
            width:100px;
        }

        .table th:nth-child(7),
        .table td:nth-child(7){
            width:140px;
        }

        .table th:nth-child(8),
        .table td:nth-child(8){
            width:150px;
        }

        .avatar-cell{
            display:flex;
            align-items:center;
            gap:8px;
            min-width:0;
        }

        .avatar-circle{
            width:28px;
            height:28px;
            border-radius:50%;
            background:#e2e8f0;
            color:#0f172a;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:11px;
            font-weight:700;
            flex-shrink:0;
        }

        .name-text{
            overflow:hidden;
            text-overflow:ellipsis;
            white-space:nowrap;
        }

        .status-badge{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            min-width:90px;
            height:24px;
            padding:0 8px;
            border-radius:999px;
            font-size:11px;
            font-weight:700;
        }

        .status-connected{
            background:#dcfce7;
            color:#166534;
        }

        .status-disconnected{
            background:#fee2e2;
            color:#991b1b;
        }

        .role-text{
            text-transform:capitalize;
            font-weight:600;
        }

        .action-icons{
            display:flex;
            align-items:center;
            gap:4px;
        }

        .action-icons a,
        .action-icons span,
        .action-icons button{
            width:28px;
            height:28px;
            display:flex;
            align-items:center;
            justify-content:center;
            border:none;
            background:transparent;
            padding:0;
            border-radius:4px;
            text-decoration:none;
            transition:0.2s ease;
            cursor:pointer;
        }

        .action-icons a:hover,
        .action-icons button:hover{
            background:#f1f5f9;
        }

        .action-icons i{
            font-size:15px;
            line-height:1;
        }

        .delete-icon{
            color:#ef4444 !important;
        }

        .empty-text{
            text-align:center;
            padding:24px !important;
            color:#94a3b8 !important;
        }

        .bottom-bar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:10px;
            flex-wrap:wrap;
            padding:12px 2px 0;
            color:#64748b;
            font-size:12px;
        }

        .connect-modal-content{
            border:none;
            border-radius:18px;
            padding:10px 18px 20px;
            box-shadow:0 20px 60px rgba(15, 23, 42, 0.18);
        }

        .connect-modal-title{
            color:#14b8c4;
            font-size:22px;
            font-weight:800;
            margin:0;
        }

        .connect-profile-wrap{
            display:flex;
            justify-content:flex-start;
            margin-bottom:10px;
        }

        .connect-avatar{
            width:78px;
            height:78px;
            border-radius:50%;
            background:linear-gradient(135deg, #dbeafe, #cbd5e1);
            color:#0f172a;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:28px;
            font-weight:800;
            overflow:hidden;
        }

        .connect-label{
            display:block;
            font-size:15px;
            font-weight:700;
            color:#0f172a;
            margin-bottom:6px;
        }

        .connect-static-text{
            min-height:42px;
            display:flex;
            align-items:center;
            font-size:16px;
            color:#1e293b;
            background:#fff;
        }

        .connect-input{
            height:42px;
            border-radius:6px;
            font-size:15px;
        }

        .connect-input:focus{
            border-color:#14b8c4;
            box-shadow:0 0 0 0.15rem rgba(20,184,196,0.15);
        }

        .connect-cancel-btn{
            min-width:180px;
            height:50px;
            border:none;
            border-radius:6px;
            background:#ff6464;
            color:#fff;
            font-size:16px;
            font-weight:700;
        }

        .connect-cancel-btn:hover{
            background:#ef4444;
            color:#fff;
        }

        .connect-submit-btn{
            min-width:180px;
            height:50px;
            border:none;
            border-radius:6px;
            background:#18bfd0;
            color:#fff;
            font-size:16px;
            font-weight:700;
        }

        .connect-submit-btn:hover{
            background:#0ea5b7;
            color:#fff;
        }

        @media (max-width: 768px){
            .main-wrapper{
                flex-direction:column;
            }

            .page-card{
                padding:10px;
            }

            .right-tools-inline{
                width:100%;
            }

            .user-search-box{
                max-width:100%;
            }

            .table{
                min-width:980px;
            }

            .table-container{
                height:420px;
            }
        }
    </style>
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
