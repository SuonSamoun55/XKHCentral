@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;
    use App\Models\MagamentSystemModel\Company;

    $authUser = Auth::user();

    $company = null;

    if (session('selected_company_id')) {
        $company = Company::find(session('selected_company_id'));
    }

    if (!$company) {
        $company = Company::first();
    }

    $userAvatar = asset('images/default-user.png');

    if ($authUser) {
        $possibleUserImages = [
            $authUser->profile_image_display ?? null,
            $authUser->avatar ?? null,
            $authUser->profile_image ?? null,
            $authUser->image ?? null,
            $authUser->photo ?? null,
            $authUser->bc_image_url ?? null,
            $authUser->profile_image_url ?? null,
        ];

        foreach ($possibleUserImages as $img) {
            if (empty($img)) {
                continue;
            }

            if (preg_match('/^https?:\/\//i', $img)) {
                $userAvatar = $img;
                break;
            }

            if (str_starts_with($img, 'storage/')) {
                $userAvatar = asset($img);
                break;
            }

            if (
                str_starts_with($img, 'profile_') ||
                str_starts_with($img, 'profile-images/') ||
                str_starts_with($img, 'profile_images/') ||
                str_starts_with($img, 'avatars/') ||
                str_starts_with($img, 'users/') ||
                str_starts_with($img, 'uploads/') ||
                str_starts_with($img, 'user_images/')
            ) {
                $userAvatar = Storage::url($img);
                break;
            }

            $userAvatar = asset($img);
            break;
        }
    }

    $companyLogoUrl = asset('images/default-company.png');

    if ($company && !empty($company->logo)) {
        if (preg_match('/^https?:\/\//i', $company->logo)) {
            $companyLogoUrl = $company->logo;
        } else {
            $companyLogoUrl = Storage::url($company->logo);
        }
    }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discount Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/ManagementSystem/adminSidbar.css') }}">

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
            --shadow:0 8px 24px rgba(15, 23, 42, 0.06);
            --radius:18px;
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

        .page-wrap{
            display:flex;
            min-height:100vh;
            width:100%;
        }

        .sidebar-wrap{
            width:250px;
            flex-shrink:0;
            background:#fff;
            border-right:1px solid var(--border);
            height:100vh;
            overflow-y:auto;
        }

        .main-wrap{
            flex:1;
            min-width:0;
            height:100vh;
            overflow-y:auto;
            padding:28px 26px 30px;
        }

        .page-title{
            font-size:24px;
            font-weight:700;
            color:#34a6b5;
            margin-bottom:6px;
        }

        .page-subtitle{
            font-size:13px;
            color:#8b95a7;
            margin-bottom:22px;
        }

        .toolbar-card,
        .table-card{
            background:#fff;
            border:1px solid var(--border);
            border-radius:18px;
            box-shadow:var(--shadow);
        }

        .toolbar-card{
            padding:16px;
            margin-bottom:18px;
        }

        .toolbar-row{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:16px;
            flex-wrap:wrap;
        }

        .toolbar-left{
            flex:1;
            min-width:280px;
            display:flex;
            align-items:center;
            gap:12px;
            flex-wrap:wrap;
        }

        .toolbar-right{
            display:flex;
            align-items:center;
            gap:10px;
            flex-wrap:wrap;
        }

        .search-box{
            position:relative;
            min-width:260px;
            flex:1;
        }

        .search-box i{
            position:absolute;
            top:50%;
            left:14px;
            transform:translateY(-50%);
            color:#98a2b3;
            font-size:14px;
        }

        .search-box input{
            width:100%;
            height:42px;
            border:1px solid var(--border);
            border-radius:999px;
            background:#f7f8fa;
            padding:0 16px 0 38px;
            outline:none;
            font-size:13px;
            color:var(--text);
        }

        .search-box input:focus{
            border-color:var(--primary);
            background:#fff;
        }

        .filter-select{
            height:38px;
            border:1px solid #dfe3e8;
            border-radius:10px;
            background:#fff;
            padding:0 12px;
            font-size:12px;
            color:#5f6b7a;
            outline:none;
            min-width:130px;
        }

        .filter-select:focus{
            border-color:var(--primary);
        }

        .btn-main,
        .btn-light-main{
            border:none;
            height:40px;
            padding:0 16px;
            border-radius:10px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            font-size:13px;
            font-weight:600;
            text-decoration:none;
            transition:.2s ease;
            cursor:pointer;
        }

        .btn-main{
            background:var(--primary);
            color:#fff;
            box-shadow:0 6px 16px rgba(27,184,201,.25);
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

        .summary-chip{
            height:38px;
            padding:0 14px;
            border-radius:10px;
            background:#f3f8fa;
            color:#5f6b7a;
            display:inline-flex;
            align-items:center;
            gap:8px;
            font-size:12px;
            border:1px solid #e2edf1;
        }

        .summary-chip strong{
            color:var(--primary);
            font-size:14px;
        }

        .table-card{
            padding:18px;
        }

        .table-top{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:12px;
            flex-wrap:wrap;
            margin-bottom:14px;
        }

        .table-top h2{
            margin:0;
            font-size:18px;
            font-weight:700;
            color:#1f2937;
        }

        .table-badge{
            background:#eafafb;
            color:var(--primary);
            border:1px solid #d8f3f7;
            border-radius:999px;
            padding:6px 12px;
            font-size:12px;
            font-weight:600;
        }

        .table-wrap{
            overflow:auto;
            width:100%;
        }

        .discount-table{
            width:100%;
            min-width:1200px;
            border-collapse:separate;
            border-spacing:0;
        }

        .discount-table thead th{
            background:#f8fafc;
            color:#64748b;
            font-size:12px;
            font-weight:700;
            text-transform:uppercase;
            letter-spacing:.3px;
            padding:14px;
            border-top:1px solid #edf1f5;
            border-bottom:1px solid #edf1f5;
            white-space:nowrap;
        }

        .discount-table thead th:first-child{
            border-top-left-radius:12px;
        }

        .discount-table thead th:last-child{
            border-top-right-radius:12px;
        }

        .discount-table tbody td{
            padding:14px;
            border-bottom:1px solid #f0f2f5;
            font-size:13px;
            color:#243041;
            vertical-align:middle;
            background:#fff;
        }

        .discount-table tbody tr:hover td{
            background:#fcfeff;
        }

        .item-cell{
            display:flex;
            align-items:center;
            gap:12px;
        }

        .item-thumb{
            width:44px;
            height:44px;
            border-radius:12px;
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
            font-size:16px;
        }

        .item-name{
            font-size:13px;
            font-weight:700;
            color:#1f2937;
            line-height:1.3;
        }

        .item-sub{
            font-size:11px;
            color:#94a3b8;
            margin-top:3px;
        }

        .status-badge{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            min-width:82px;
            height:28px;
            padding:0 12px;
            border-radius:999px;
            font-size:12px;
            font-weight:700;
        }

        .status-badge.active{
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

        .status-badge.inactive{
            background:#eef2f7;
            color:#64748b;
        }

        .action-group{
            display:flex;
            align-items:center;
            justify-content:center;
            gap:8px;
        }

        .icon-btn{
            width:34px;
            height:34px;
            border:none;
            border-radius:10px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            font-size:14px;
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

        .empty-box{
            text-align:center;
            padding:44px 16px;
            color:#94a3b8;
        }

        .empty-box i{
            font-size:28px;
            margin-bottom:8px;
            display:block;
            color:#c3cdd8;
        }

        @media (max-width: 1100px){
            .sidebar-wrap{
                width:220px;
            }
        }

        @media (max-width: 768px){
            body{
                overflow:auto;
            }

            .page-wrap{
                flex-direction:column;
            }

            .sidebar-wrap{
                width:100%;
                height:auto;
                overflow:visible;
            }

            .main-wrap{
                height:auto;
                overflow:visible;
                padding:18px;
            }
        }
    </style>
</head>
<body>

<div class="page-wrap">
    <div class="sidebar-wrap" id="appShell">
        <aside class="sidebar">

            <div class="sidebar-top">
                <div class="brand">
                    <div class="company-logo-box">
                        <img src="{{ $companyLogoUrl }}"
                             alt="Company Logo"
                             onerror="this.onerror=null;this.src='{{ asset('images/default-company.png') }}';">
                    </div>
                </div>

                <nav class="nav-list">
                    <a href="/admin" class="nav-link-wrap">
                        <div class="nav-btn {{ request()->is('admin') ? 'active' : '' }}">
                            <span class="nav-icon">
                                <img src="{{ asset('images/aside/dashboard.png') }}" alt="Dashboard Icon">
                            </span>
                            <span class="nav-label">Dashboard</span>
                        </div>
                    </a>

                    <a href="/users" class="nav-link-wrap">
                        <div class="nav-btn {{ request()->is('users') || request()->is('users/*') ? 'active' : '' }}">
                            <span class="nav-icon">
                                <img src="{{ asset('images/aside/user.png') }}" alt="Users Icon">
                            </span>
                            <span class="nav-label">Users</span>
                        </div>
                    </a>

                    <a href="/pos/interface" class="nav-link-wrap">
                        <div class="nav-btn {{ request()->is('pos/interface') || request()->is('pos/*') ? 'active' : '' }}">
                            <span class="nav-icon">
                                <img src="{{ asset('images/aside/Cart.png') }}" alt="POS Icon">
                            </span>
                            <span class="nav-label">Pos System</span>
                        </div>
                    </a>

                    <a href="{{ route('discounts.index') }}" class="nav-link-wrap">
                        <div class="nav-btn {{ request()->is('discounts') || request()->is('discounts/*') ? 'active' : '' }}">
                            <span class="nav-icon">
                                <i class="bi bi-percent" style="font-size:18px;"></i>
                            </span>
                            <span class="nav-label">Discount</span>
                        </div>
                    </a>

                    <a href="/companies" class="nav-link-wrap">
                        <div class="nav-btn {{ (request()->is('companies') || request()->is('companies/*')) && !request()->is('companies/select') ? 'active' : '' }}">
                            <span class="nav-icon">
                                <img src="{{ asset('images/aside/company.png') }}" alt="Companies Icon">
                            </span>
                            <span class="nav-label">Companies</span>
                        </div>
                    </a>

                    <a href="/companies/select" class="nav-link-wrap">
                        <div class="nav-btn {{ request()->is('companies/select') ? 'active' : '' }}">
                            <span class="nav-icon">
                                <img src="{{ asset('images/aside/switch.png') }}" alt="Select Company Icon">
                            </span>
                            <span class="nav-label">Select Company</span>
                        </div>
                    </a>

                    <a href="/admin/notification" class="nav-link-wrap">
                        <div class="nav-btn {{ request()->is('admin/notification') || request()->is('admin/notification/*') ? 'active' : '' }}">
                            <span class="nav-icon">
                                <img src="{{ asset('images/aside/Notification.png') }}" alt="Notification Icon">
                            </span>
                            <span class="nav-label">Notification</span>
                        </div>
                    </a>
                </nav>
            </div>

            <div class="sidebar-bottom">
                <div class="profile">
                    <img src="{{ $userAvatar }}"
                         alt="User"
                         onerror="this.onerror=null;this.src='{{ asset('images/default-user.png') }}';">

                    <div class="profile-text">
                        <div class="user-meta">
                            <div class="user-name">{{ $authUser->name ?? 'Guest' }}</div>
                            <div class="user-role">{{ ucfirst($authUser->role ?? 'Guest') }}</div>
                        </div>
                    </div>
                </div>

                <div class="settings-box" id="settingsBox">
                    <button class="settings-btn" id="settingsBtn" type="button">
                        <span class="nav-icon">
                            <img src="{{ asset('images/aside/setting.png') }}" alt="Settings Icon">
                        </span>
                        <span class="nav-label">Settings</span>
                        <span class="settings-arrow">⌄</span>
                    </button>

                    <div class="settings-menu">
                        <a href="{{ route('profile') }}" class="settings-link">Edit Profile</a>
                        <a href="#" class="settings-link">Change Password</a>
                        <a href="#" class="settings-link">Policy</a>
                    </div>
                </div>

                <a href="/logout" class="logout-btn">
                    <span class="nav-icon">
                        <img src="{{ asset('images/aside/logout.png') }}" alt="Logout Icon">
                    </span>
                    <span class="nav-label">Log out</span>
                </a>
            </div>
        </aside>

        <button class="collapse-handle" id="collapseHandle" type="button" aria-label="Collapse sidebar">
            <span>‹</span>
        </button>
    </div>

    <main class="main-wrap">
        <h1 class="page-title">Discount Management</h1>
        <div class="page-subtitle">Manage item discounts with the same style table</div>

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
                        <option value="active">Active</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="expired">Expired</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="toolbar-right">
                    <div class="summary-chip">
                        <span>Total</span>
                        <strong id="discountCount">{{ $items->count() }}</strong>
                    </div>

                    <a href="{{ route('discounts.create') }}" class="btn-main">
                        <i class="bi bi-plus-lg"></i>
                        Add Discount
                    </a>

                    <button type="button" class="btn-light-main" onclick="window.location.reload()">
                        <i class="bi bi-arrow-repeat"></i>
                        Refresh
                    </button>
                </div>
            </div>
        </div>

        <div class="table-card">
            <div class="table-top">
                <h2>Discount List</h2>
                <span class="table-badge" id="showingCount">Showing {{ $items->count() }}</span>
            </div>

            <div class="table-wrap">
                <table class="discount-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Item No.</th>
                            <th>Category</th>
                            <th>Discount</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th style="text-align:center;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="discountTableBody">
                        @forelse($items as $index => $item)
                            <tr class="discount-row"
                                data-status="{{ strtolower($item->discount_status ?? 'inactive') }}"
                                data-search="{{ strtolower(($item->display_name ?? '') . ' ' . ($item->number ?? '') . ' ' . ($item->item_category_code ?? '')) }}">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="item-cell">
                                        <div class="item-thumb">
                                            @if(!empty($item->image_url))
                                                <img src="{{ $item->image_url }}"
                                                     alt="Item Image"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="thumb-fallback" style="display:none;">
                                                    <i class="bi bi-image"></i>
                                                </div>
                                            @else
                                                <div class="thumb-fallback">
                                                    <i class="bi bi-image"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="item-name">{{ $item->display_name ?? 'No Name' }}</div>
                                            <div class="item-sub">Product discount</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item->number ?? '-' }}</td>
                                <td>{{ $item->item_category_code ?? '-' }}</td>
                                <td><strong>${{ number_format((float)($item->discount_amount ?? 0), 2) }}</strong></td>
                                <td>{{ $item->discount_start_date ? \Carbon\Carbon::parse($item->discount_start_date)->format('d M Y') : '-' }}</td>
                                <td>{{ $item->discount_end_date ? \Carbon\Carbon::parse($item->discount_end_date)->format('d M Y') : '-' }}</td>
                                <td>
                                    <span class="status-badge {{ strtolower($item->discount_status ?? 'inactive') }}">
                                        {{ ucfirst($item->discount_status ?? 'inactive') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-group">
                                        <a href="{{ route('discounts.edit', $item->id) }}" class="icon-btn edit-btn" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('discounts.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this discount?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="icon-btn delete-btn" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyRow">
                                <td colspan="9">
                                    <div class="empty-box">
                                        <i class="bi bi-percent"></i>
                                        <div>No discount data found.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                        <tr id="jsEmptyRow" style="display:none;">
                            <td colspan="9">
                                <div class="empty-box">
                                    <i class="bi bi-search"></i>
                                    <div>No matching discount found.</div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script src="{{ asset('JS/AdminJS/SideBarjs/sidebar.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput   = document.getElementById('discountSearch');
        const statusFilter  = document.getElementById('statusFilter');
        const rows          = Array.from(document.querySelectorAll('.discount-row'));
        const emptyRow      = document.getElementById('jsEmptyRow');
        const showingCount  = document.getElementById('showingCount');
        const discountCount = document.getElementById('discountCount');

        function filterTable() {
            const keyword = (searchInput.value || '').trim().toLowerCase();
            const status  = (statusFilter.value || '').trim().toLowerCase();

            let visibleCount = 0;

            rows.forEach(row => {
                const rowSearch = row.dataset.search || '';
                const rowStatus = row.dataset.status || '';

                const matchKeyword = !keyword || rowSearch.includes(keyword);
                const matchStatus  = !status || rowStatus === status;

                const show = matchKeyword && matchStatus;
                row.style.display = show ? '' : 'none';

                if (show) visibleCount++;
            });

            if (emptyRow) {
                emptyRow.style.display = visibleCount === 0 ? '' : 'none';
            }

            if (showingCount) {
                showingCount.textContent = `Showing ${visibleCount}`;
            }

            if (discountCount) {
                discountCount.textContent = visibleCount;
            }
        }

        searchInput.addEventListener('input', filterTable);
        statusFilter.addEventListener('change', filterTable);
    });
</script>

</body>
</html>
