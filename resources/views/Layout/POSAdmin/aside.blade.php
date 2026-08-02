@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;
    use App\Models\ManagementSystem\Company;

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

    /*
    |--------------------------------------------------------------------------
    | COMPANY NAME / LOGO
    |--------------------------------------------------------------------------
    */
    $companyName = $company->display_name ?? $company->name ?? 'Orange';
    $companyLogoUrl = asset('images/default-company.png');

    if ($company && !empty($company->logo)) {
        if (preg_match('/^https?:\/\//i', $company->logo)) {
            $companyLogoUrl = $company->logo;
        } else {
            $companyLogoUrl = Storage::url($company->logo);
        }
    }

    $navItems = [
        [
            'name' => 'Pos System',
            'url' => '/pos/interface',
            'match' => ['pos/interface', 'pos/*'],
            'icon' => '/images/management/managemetn_POS.png',
            'icon_active' => '/images/management/management_POS_active.png',
        ],
        [
            'name' => 'Order',
            'url' => '/admin/orders',
            'match' => ['admin/orders', 'admin/orders/*'],
            'icon' => '/images/AdminPOS/Admin_POS_Approval_Order.png',
            'icon_active' => '/images/AdminPOS/Admin_POS_Approval_Order_active.png',
        ],
        [
            'name' => 'Store Management',
            'url' => '/store-management',
            'match' => ['store-management', 'store-management/*'],
            'icon' => '/images/AdminPOS/admin_store_management.png',
            'icon_active' => '/images/AdminPOS/admin_store_management_active.png',
        ],
        [
            'name' => 'Discount',
            'url' => '/discounts',
            'match' => ['discounts', 'discounts/*'],
            'icon' => '/images/AdminPOS/Admin_POS_Discount.png',
            'icon_active' => '/images/AdminPOS/Admin_POS_Discount_active.png',
        ],
        [
            'name' => 'Notification',
            'url' => '/admin/notification',
            'match' => ['admin/notification', 'admin/notification/*'],
            'icon' => '/images/aside/SidebarNotifications.png',
            'icon_active' => '/images/aside/NotificationActive.png',
            'notification' => true,
        ],
    ];
@endphp

<div class="sidebar-wrap">
    <aside class="sidebar" id="appSidebar">
        <div class="sidebar-top">
            <div class="brand">
                <div class="company-logo-box">
                    <img src="{{ $companyLogoUrl }}"
                         alt="Company Logo"
                         class="company-logo-img"
                         onerror="this.onerror=null;this.src='{{ asset('images/default-company.png') }}';">
                </div>
            </div>

            <nav class="nav-list">
                @foreach ($navItems as $item)
                    @php
                        $isActive = false;
                        foreach ($item['match'] as $pattern) {
                            if (request()->is($pattern)) {
                                $isActive = true;
                                break;
                            }
                        }

                        $iconToShow = $item['icon'];
                        if ($isActive && !empty($item['icon_active'])) {
                            $iconToShow = $item['icon_active'];
                        }
                    @endphp
                    <a href="{{ $item['url'] }}" class="nav-link-mobile-close">
                        <button class="nav-btn {{ $isActive ? 'active' : '' }}" type="button">
                            <span class="nav-icon {{ !empty($item['notification']) ? 'nav-icon-notification' : '' }}">
                                @if(!empty($iconToShow))
                                    <img src="{{ asset($iconToShow) }}" alt="{{ $item['name'] }} Icon">
                                @else
                                    <i class="bi bi-percent" style="font-size:14px;"></i>
                                @endif
                                @if (!empty($item['notification']))
                                    <span id="unreadNotiDot" class="noti-dot" aria-hidden="true"></span>
                                @endif
                            </span>
                            <span class="nav-label">{{ $item['name'] }}</span>
                        </button>
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="sidebar-bottom">
            <div class="profiles">
                <img src="{{ $userAvatar }}" alt="User" id="sidebarProfileImage"
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
                        <img src="{{ asset('/images/aside/setting.png') }}" alt="Settings Icon">
                    </span>
                    <span class="nav-label">Settings</span>
                    <span class="settings-arrow">⌄</span>
                </button>

                <div class="settings-menu">
                    <a href="{{ route('profile') }}" class="settings-link nav-link-mobile-close">Edit Profile</a>
                    <a href="{{ route('admin.password.change') }}" class="settings-link nav-link-mobile-close">Change Password</a>
                    <a href="#" class="settings-link">Policy</a>
                </div>
            </div>

            <a href="/logout" class="logout-link">
                <button class="logout-btn" type="button">
                    <span class="nav-icon">
                        <img src="{{ asset('images/aside/logout.png') }}" alt="Logout Icon">
                    </span>
                    <span class="nav-label">Log out</span>
                </button>
            </a>
        </div>
    </aside>

    <button class="collapse-handle" id="collapseHandle" type="button" aria-label="Collapse sidebar">
        <span>‹</span>
    </button>
</div>
<link rel="stylesheet" href="{{ asset('/css/views/POSViews/POSUserViews/Layout/aside.css') }}">