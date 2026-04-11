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
            /* min-height:120vh; */
            height: 100%;
            width: 100%;
            /* padding:8px; */
                        /* height: 100; */

        }

        .content-area{
            /* flex:1; */
            /* min-width:0; */
            overflow:hidden;
            width: 100%;
            /* height: 1000px; */
            /* height: 100%; */
        }

        .page-card{
            padding: 0px;
            margin-top: 10px;
            margin-right: 10px;
            background:#fff;
            border-radius:14px;
            padding:14px;
            /* height: 1000vh; */
            box-shadow:0 8px 24px rgba(15, 23, 42, 0.05);
            /* min-height:calc(100vh - 16px); */
            height: 100%;
            overflow:hidden;
        }

        .page-title{
            font-size:18px;
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
            font-size:12px;
            pointer-events:none;
        }

        .user-search-input{
            width:100%;
            height:32px;
            padding:0 10px 0 30px;
            outline:none;
            font-size:11px;
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
            height:32px;
            padding:0 11px;
            border:none;
            border-radius:5px;
            font-size:11px;
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

        .status-select,
        .page-size-select{
            height:32px;
            min-width:120px;
            border:1px solid #d6dde5;
            border-radius:5px;
            padding:0 8px;
            background:#fff;
            font-size:11px;
            color:#475569;
            outline:none;
        }

        .message-box{
            margin-bottom:10px;
        }

        .alert{
            padding:8px 12px;
            font-size:11px;
            margin-bottom:8px;
        }

        .table-container{
            border:1px solid #dce5ec;
            border-radius:8px;
            background:#fff;
            overflow:hidden;
            height:calc(100vh - 140px);
            min-height:100%;
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
            min-width:100%;
        }

        .table thead th{
            background:#18bfd0;
            color:#fff;
            border:none;
            font-size:11px;
            padding:9px 6px;
            white-space:nowrap;
            position:sticky;
            top:0;
            z-index:5;
            font-weight:500;
        }

        .table tbody td{
            padding:7px 6px;
            font-size:11px;
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
            width:30px;
            text-align:center;
        }

        .table th:nth-child(2),
        .table td:nth-child(2){
            width:150px;
        }

        .table th:nth-child(3),
        .table td:nth-child(3){
            width:150px;
        }

        .table th:nth-child(4),
        .table td:nth-child(4){
            width:90px;
        }

        .table th:nth-child(5),
        .table td:nth-child(5){
            width:90px;
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
            width:90px;
        }

        .avatar-cell{
            display:flex;
            align-items:center;
            gap:8px;
            min-width:0;
        }

        .avatar-wrap{
            width:40px;
            height:40px;
            position:relative;
            flex-shrink:0;
        }

        .avatar-image{
            width:40px;
            height:40px;
            border-radius:50%;
            object-fit:cover;
            border:1px solid #dbe4ee;
            background:#f8fafc;
            display:block;
        }

        .avatar-fallback{
            width:40px;
            height:40px;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#e2e8f0;
            color:#475569;
            font-size:13px;
            font-weight:700;
            border:1px solid #dbe4ee;
        }

        .avatar-status-dot{
            position:absolute;
            right:-1px;
            bottom:-1px;
            width:11px;
            height:11px;
            border-radius:50%;
            border:2px solid #fff;
            box-shadow:0 0 0 1px rgba(148,163,184,0.15);
        }

        .avatar-status-online{
            background:#2563eb;
        }

        .avatar-status-offline{
            background:#94a3b8;
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
            font-size:11px;
            line-height:1.2;
        }

        .sub-text{
            overflow:hidden;
            text-overflow:ellipsis;
            white-space:nowrap;
            font-size:10px;
            color:#64748b;
            line-height:1.2;
            margin-top:2px;
        }

        .status-badge{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            min-width:88px;
            height:22px;
            padding:0 8px;
            border-radius:999px;
            font-size:10px;
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
            font-size:11px;
        }

        .action-icons{
            display:flex;
            align-items:center;
            gap:4px;
        }

        .action-icons a,
        .action-icons button{
            width:26px;
            height:26px;
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
            font-size:14px;
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
            font-size:11px;
        }

        .pagination-tools{
            display:flex;
            align-items:center;
            gap:8px;
            flex-wrap:wrap;
        }

        .page-btn{
            height:30px;
            min-width:70px;
            padding:0 10px;
            border:1px solid #d6dde5;
            background:#fff;
            color:#334155;
            border-radius:5px;
            font-size:11px;
            font-weight:600;
            cursor:pointer;
        }

        .page-btn:disabled{
            opacity:0.5;
            cursor:not-allowed;
        }

        .page-info{
            font-size:11px;
            color:#475569;
            font-weight:600;
        }

        .row-check,
        #checkAll{
            width:13px;
            height:13px;
            cursor:pointer;
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
                min-width:1250px;
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

</body>
</html>
