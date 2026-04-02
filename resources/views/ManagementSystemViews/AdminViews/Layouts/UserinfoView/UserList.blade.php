@include('ManagementSystemViews.AdminViews.Layouts.navbar')
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
            min-width:150px;
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
            min-height:440px;
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
            min-width:1500px;
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
            width:260px;
        }

        .table th:nth-child(3),
        .table td:nth-child(3){
            width:200px;
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
            width:90px;
        }

        .table th:nth-child(7),
        .table td:nth-child(7){
            width:130px;
        }

        .table th:nth-child(8),
        .table td:nth-child(8){
            width:150px;
        }

        .table th:nth-child(9),
        .table td:nth-child(9){
            width:150px;
        }

        .table th:nth-child(10),
        .table td:nth-child(10){
            width:150px;
        }

        .table th:nth-child(11),
        .table td:nth-child(11){
            width:190px;
        }

        .table th:nth-child(12),
        .table td:nth-child(12){
            width:100px;
        }

        .avatar-cell{
            display:flex;
            align-items:center;
            gap:8px;
            min-width:0;
        }

        .avatar-wrap{
            width:42px;
            height:42px;
            position:relative;
            flex-shrink:0;
        }

        .avatar-image{
            width:42px;
            height:42px;
            border-radius:50%;
            object-fit:cover;
            border:1px solid #dbe4ee;
            background:#f8fafc;
            display:block;
        }

        .avatar-fallback{
            width:42px;
            height:42px;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#e2e8f0;
            color:#475569;
            font-size:14px;
            font-weight:700;
            border:1px solid #dbe4ee;
        }

        .name-block{
            display:flex;
            flex-direction:column;
            min-width:0;
        }

        .name-text{
            overflow:hidden;
            text-overflow:ellipsis;
            white-space:nowrap;
            font-weight:600;
            color:#0f172a;
        }

        .sub-text{
            overflow:hidden;
            text-overflow:ellipsis;
            white-space:nowrap;
            font-size:11px;
            color:#64748b;
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

        .status-online{
            background:#dcfce7;
            color:#166534;
        }

        .status-offline{
            background:#e2e8f0;
            color:#475569;
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

        .debug-url{
            display:block;
            max-width:180px;
            font-size:10px;
            color:#2563eb;
            overflow:hidden;
            text-overflow:ellipsis;
            white-space:nowrap;
            text-decoration:none;
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
                min-width:1500px;
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
                                    <th>Active</th>
                                    <th>Role</th>
                                    <th>Phone Number</th>
                                    <th>Last Seen</th>
                                    <th>Offline Time</th>
                                    <th>Image URL</th>
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
                                        $imageUrl = $customer->profile_image_url ?? '';
                                        $firstLetter = strtoupper(mb_substr(trim($displayName), 0, 1)) ?: 'U';
                                    @endphp

                                    <tr
                                        class="user-row"
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
                                                </div>

                                                <div class="name-block">
                                                    <span class="name-text">{{ $displayName }}</span>
                                                    <span class="sub-text">
                                                        @if($activityStatus === 'online')
                                                            Currently online
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

                                        <td>
                                            @if($customer->connect_status === 'connected')
                                                @if($customer->is_online ?? false)
                                                    <span class="status-badge status-online">Online</span>
                                                @else
                                                    <span class="status-badge status-offline">Offline</span>
                                                @endif
                                            @else
                                                <span class="status-badge status-offline">-</span>
                                            @endif
                                        </td>

                                        <td class="role-text">{{ $displayRole }}</td>
                                        <td title="{{ $displayPhone }}">{{ $displayPhone }}</td>
                                        <td title="{{ $lastSeenText }}">{{ $lastSeenText }}</td>
                                        <td title="{{ $offlineDuration }}">{{ $offlineDuration }}</td>

                                        <td title="{{ $imageUrl }}">
                                            @if(!empty($imageUrl))
                                                <a href="{{ $imageUrl }}" target="_blank" class="debug-url">{{ $imageUrl }}</a>
                                            @else
                                                <span class="text-danger">No image URL</span>
                                            @endif
                                        </td>

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

                                                <a href="{{ route('users.show', $customer->id) }}" title="View">
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
                                        <td colspan="12" class="empty-text">No BC customers found.</td>
                                    </tr>
                                @endforelse

                                <tr id="noResultRow" style="display:none;">
                                    <td colspan="12" class="empty-text">No matching users found.</td>
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
    const activeFilter = document.getElementById('activeFilter');
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
        const selectedActive = activeFilter.value;
        let visibleRows = 0;

        rows.forEach(function (row) {
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

            if (matchesSearch && matchesStatus && matchesActive) {
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

    if (activeFilter) {
        activeFilter.addEventListener('change', filterTable);
    }
});
</script>

</body>
</html>
