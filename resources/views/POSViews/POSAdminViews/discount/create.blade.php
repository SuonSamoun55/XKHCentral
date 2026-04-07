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

    $discountItemsJson = $items->map(function ($item) {
        return [
            'id' => $item->id,
            'name' => $item->display_name ?? 'No Name',
            'number' => $item->number ?? '-',
            'category' => $item->item_category_code ?? '-',
            'image' => $item->image_url ?? '',
        ];
    })->values()->toJson();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Discount</title>

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
            --success:#10b981;
            --shadow:0 8px 24px rgba(15, 23, 42, 0.06);
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

        .form-card{
            background:#fff;
            border:1px solid var(--border);
            border-radius:18px;
            box-shadow:var(--shadow);
            overflow:hidden;
        }

        .form-card-head{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            padding:18px 20px;
            border-bottom:1px solid #eef1f4;
            background:#fcfdfd;
        }

        .form-card-head h2{
            margin:0;
            font-size:18px;
            font-weight:700;
            color:#1f2937;
        }

        .form-card-body{
            padding:22px 20px;
        }

        .form-grid{
            display:grid;
            grid-template-columns:repeat(2, minmax(0,1fr));
            gap:18px;
        }

        .form-group{
            display:flex;
            flex-direction:column;
            gap:8px;
        }

        .form-group.full{
            grid-column:1 / -1;
        }

        .form-label{
            font-size:13px;
            font-weight:700;
            color:#344054;
            margin:0;
        }

        .form-control-custom,
        .form-select-custom{
            width:100%;
            height:44px;
            border:1px solid #dfe3e8;
            border-radius:12px;
            background:#fff;
            padding:0 14px;
            outline:none;
            font-size:13px;
            color:#202938;
            transition:.2s ease;
        }

        .form-control-custom:focus,
        .form-select-custom:focus{
            border-color:var(--primary);
            box-shadow:0 0 0 3px rgba(27,184,201,.10);
        }

        .input-note{
            font-size:11px;
            color:#98a2b3;
            margin-top:2px;
        }

        .search-select-wrap{
            position:relative;
        }

        .search-select-input{
            width:100%;
            height:44px;
            border:1px solid #dfe3e8;
            border-radius:12px;
            background:#fff;
            padding:0 14px;
            outline:none;
            font-size:13px;
            color:#202938;
        }

        .search-select-input:focus{
            border-color:var(--primary);
            box-shadow:0 0 0 3px rgba(27,184,201,.10);
        }

        .search-dropdown{
            position:absolute;
            top:100%;
            left:0;
            right:0;
            background:#fff;
            border:1px solid #dfe3e8;
            border-radius:12px;
            box-shadow:0 10px 30px rgba(15,23,42,.08);
            margin-top:6px;
            max-height:260px;
            overflow:auto;
            z-index:999;
            display:none;
        }

        .search-option{
            padding:12px 14px;
            font-size:13px;
            cursor:pointer;
            border-bottom:1px solid #f1f4f7;
        }

        .search-option:last-child{
            border-bottom:none;
        }

        .search-option:hover{
            background:#f7fcfd;
        }

        .search-option-title{
            font-weight:700;
            color:#1f2937;
        }

        .search-option-sub{
            font-size:11px;
            color:#8b95a7;
            margin-top:3px;
        }

        .product-preview{
            margin-top:10px;
            display:flex;
            align-items:center;
            gap:12px;
            padding:12px;
            border:1px solid #eef1f4;
            border-radius:14px;
            background:#fbfdff;
        }

        .product-preview-thumb{
            width:54px;
            height:54px;
            border-radius:14px;
            overflow:hidden;
            background:#eef2f7;
            display:flex;
            align-items:center;
            justify-content:center;
            flex-shrink:0;
        }

        .product-preview-thumb img{
            width:100%;
            height:100%;
            object-fit:cover;
            display:block;
        }

        .product-preview-fallback{
            color:#94a3b8;
            font-size:18px;
        }

        .product-preview-name{
            font-size:14px;
            font-weight:700;
            color:#1f2937;
            line-height:1.3;
        }

        .product-preview-sub{
            font-size:12px;
            color:#8b95a7;
            margin-top:4px;
        }

        .error-text{
            font-size:12px;
            color:var(--danger);
            margin-top:2px;
        }

        .action-row{
            display:flex;
            justify-content:flex-end;
            gap:10px;
            flex-wrap:wrap;
            margin-top:24px;
        }

        .btn-main,
        .btn-light-main{
            border:none;
            height:42px;
            padding:0 18px;
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

        .hidden{
            display:none !important;
        }

        @media (max-width: 1100px){
            .sidebar-wrap{
                width:220px;
            }
        }

        @media (max-width: 900px){
            .form-grid{
                grid-template-columns:1fr;
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
        <h1 class="page-title">Create Discount</h1>
        <div class="page-subtitle">Add discount by item or category</div>

        @if ($errors->any())
            <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-3">
                <strong>Please check the form.</strong>
            </div>
        @endif

        <div class="form-card">
            <div class="form-card-head">
                <h2>Discount Form</h2>
            </div>

            <div class="form-card-body">
                <form action="{{ route('discounts.store') }}" method="POST">
                    @csrf

                    <div class="form-grid">
                        <div class="form-group full">
                            <label for="discount_type" class="form-label">Discount Target</label>
                            <select name="discount_type" id="discount_type" class="form-select-custom" required>
                                <option value="item" {{ old('discount_type', 'item') === 'item' ? 'selected' : '' }}>Item</option>
                                <option value="category" {{ old('discount_type') === 'category' ? 'selected' : '' }}>Category</option>
                            </select>
                        </div>

                        <div class="form-group full" id="itemSearchGroup">
                            <label class="form-label">Search Item by ID or Name</label>

                            <div class="search-select-wrap">
                                <input
                                    type="text"
                                    id="itemSearchInput"
                                    class="search-select-input"
                                    placeholder="Click to show all items, then search by code or name..."
                                    autocomplete="off"
                                >
                                <div class="search-dropdown" id="itemSearchDropdown"></div>
                            </div>

                            <input type="hidden" name="item_id" id="item_id" value="{{ old('item_id') }}">

                            @error('item_id')
                                <div class="error-text">{{ $message }}</div>
                            @enderror

                            <div class="product-preview" id="productPreview" style="display:none;">
                                <div class="product-preview-thumb" id="previewThumb">
                                    <div class="product-preview-fallback">
                                        <i class="bi bi-image"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="product-preview-name" id="previewName">-</div>
                                    <div class="product-preview-sub" id="previewSub">-</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group full hidden" id="categoryGroup">
                            <label for="category_code" class="form-label">Category</label>
                            <select name="category_code" id="category_code" class="form-select-custom">
                                <option value="">Select category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ old('category_code') == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>

                            @error('category_code')
                                <div class="error-text">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="discount_amount" class="form-label">Discount Amount</label>
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="discount_amount"
                                id="discount_amount"
                                class="form-control-custom"
                                value="{{ old('discount_amount') }}"
                                placeholder="Enter discount amount"
                                required
                            >
                            <div class="input-note">Example: 5.00 or 10.50</div>
                            @error('discount_amount')
                                <div class="error-text">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="discount_start_date" class="form-label">Start Date</label>
                            <input
                                type="date"
                                name="discount_start_date"
                                id="discount_start_date"
                                class="form-control-custom"
                                value="{{ old('discount_start_date') }}"
                            >
                            @error('discount_start_date')
                                <div class="error-text">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="discount_end_date" class="form-label">End Date</label>
                            <input
                                type="date"
                                name="discount_end_date"
                                id="discount_end_date"
                                class="form-control-custom"
                                value="{{ old('discount_end_date') }}"
                            >
                            @error('discount_end_date')
                                <div class="error-text">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="action-row">
                        <a href="{{ route('discounts.index') }}" class="btn-light-main">
                            <i class="bi bi-arrow-left"></i>
                            Back
                        </a>

                        <button type="submit" class="btn-main">
                            <i class="bi bi-check2-circle"></i>
                            Save Discount
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script src="{{ asset('JS/AdminJS/SideBarjs/sidebar.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const discountType = document.getElementById('discount_type');
        const itemSearchGroup = document.getElementById('itemSearchGroup');
        const categoryGroup = document.getElementById('categoryGroup');

        const itemSearchInput = document.getElementById('itemSearchInput');
        const itemSearchDropdown = document.getElementById('itemSearchDropdown');
        const itemIdInput = document.getElementById('item_id');

        const productPreview = document.getElementById('productPreview');
        const previewThumb = document.getElementById('previewThumb');
        const previewName = document.getElementById('previewName');
        const previewSub = document.getElementById('previewSub');

        const items = {!! $discountItemsJson !!};

        function toggleTarget() {
            const type = discountType.value;

            if (type === 'category') {
                itemSearchGroup.classList.add('hidden');
                categoryGroup.classList.remove('hidden');
            } else {
                itemSearchGroup.classList.remove('hidden');
                categoryGroup.classList.add('hidden');
            }
        }

        function escapeHtml(text) {
            return String(text ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        function renderDropdown(list) {
            if (!list.length) {
                itemSearchDropdown.innerHTML = `
                    <div class="search-option">
                        <div class="search-option-title">No item found</div>
                    </div>
                `;
                itemSearchDropdown.style.display = 'block';
                return;
            }

            itemSearchDropdown.innerHTML = list.map(item => `
                <div class="search-option"
                     data-id="${item.id}"
                     data-name="${escapeHtml(item.name)}"
                     data-number="${escapeHtml(item.number)}"
                     data-category="${escapeHtml(item.category)}"
                     data-image="${escapeHtml(item.image)}">
                    <div class="search-option-title">${escapeHtml(item.name)}</div>
                    <div class="search-option-sub">Code: ${escapeHtml(item.number)} | Category: ${escapeHtml(item.category)}</div>
                </div>
            `).join('');

            itemSearchDropdown.style.display = 'block';
        }

        function updatePreview(item) {
            if (!item) {
                productPreview.style.display = 'none';
                previewName.textContent = '-';
                previewSub.textContent = '-';
                previewThumb.innerHTML = `
                    <div class="product-preview-fallback">
                        <i class="bi bi-image"></i>
                    </div>
                `;
                return;
            }

            previewName.textContent = item.name || 'No Name';
            previewSub.textContent = `Code: ${item.number || '-'} | Category: ${item.category || '-'}`;
            productPreview.style.display = 'flex';

            if (item.image) {
                previewThumb.innerHTML = `
                    <img src="${item.image}" alt="Product"
                         onerror="this.remove(); document.getElementById('previewThumb').innerHTML = '<div class=&quot;product-preview-fallback&quot;><i class=&quot;bi bi-image&quot;></i></div>';">
                `;
            } else {
                previewThumb.innerHTML = `
                    <div class="product-preview-fallback">
                        <i class="bi bi-image"></i>
                    </div>
                `;
            }
        }

        function searchItems(keyword) {
            const q = keyword.trim().toLowerCase();

            if (!q) {
                renderDropdown(items.slice(0, 50));
                return;
            }

            const filtered = items.filter(item => {
                return (
                    String(item.name).toLowerCase().includes(q) ||
                    String(item.number).toLowerCase().includes(q) ||
                    String(item.id).toLowerCase().includes(q)
                );
            }).slice(0, 50);

            renderDropdown(filtered);
        }

        itemSearchInput.addEventListener('focus', function () {
            renderDropdown(items.slice(0, 50));
        });

        itemSearchInput.addEventListener('click', function () {
            renderDropdown(items.slice(0, 50));
        });

        itemSearchInput.addEventListener('input', function () {
            itemIdInput.value = '';
            updatePreview(null);
            searchItems(this.value);
        });

        document.addEventListener('click', function (e) {
            if (!itemSearchInput.contains(e.target) && !itemSearchDropdown.contains(e.target)) {
                itemSearchDropdown.style.display = 'none';
            }
        });

        itemSearchDropdown.addEventListener('click', function (e) {
            const option = e.target.closest('.search-option');
            if (!option || !option.dataset.id) return;

            const selectedItem = {
                id: option.dataset.id,
                name: option.dataset.name,
                number: option.dataset.number,
                category: option.dataset.category,
                image: option.dataset.image,
            };

            itemIdInput.value = selectedItem.id;
            itemSearchInput.value = `${selectedItem.name} (${selectedItem.number})`;
            itemSearchDropdown.style.display = 'none';
            updatePreview(selectedItem);
        });

        function setOldItem() {
            const oldId = itemIdInput.value;
            if (!oldId) return;

            const selected = items.find(item => String(item.id) === String(oldId));
            if (!selected) return;

            itemSearchInput.value = `${selected.name} (${selected.number})`;
            updatePreview(selected);
        }

        discountType.addEventListener('change', toggleTarget);

        toggleTarget();
        setOldItem();
    });
</script>

</body>
</html>
