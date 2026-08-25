@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;
    use App\Models\ManagementSystem\Company;
    use App\Models\ManagementSystem\Notification;
    use App\Models\POS\Order;

    $authUser = Auth::user();

    $company = null;
    if (session('selected_company_id')) {
        $company = Company::find(session('selected_company_id'));
    }
    if (!$company) {
        $company = Company::first();
    }
    $unreadNotificationCount = Notification::adminUnreadTotal(session('selected_company_id'));
    $pendingOrdersCount = Order::where('status', 'pending')
        ->when(session('selected_company_id'), fn ($q) => $q->where('company_id', session('selected_company_id')))
        ->count();

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
            'name' => 'Approval Order',
            'url' => '/admin/orders',
            'match' => ['admin/orders', 'admin/orders/*'],
            'icon' => '/images/AdminPOS/Admin_POS_Approval_Order.png',
            'icon_active' => '/images/AdminPOS/Admin_POS_Approval_Order_active.png',
            'notification' => $pendingOrdersCount > 0,
        ],
        [
            'name' => 'Store',
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
            'name' => 'Tax Groups',
            'url' => '/tax-groups',
            'match' => ['tax-groups', 'tax-groups/*'],
            'icon' => '/images/AdminPOS/Admin_POS_Discount.png',
            'icon_active' => '/images/AdminPOS/Admin_POS_Discount_active.png',
        ],
        [
            'name' => 'Notification',
            'url' => '/admin/notifications',
            'match' => ['admin/notifications', 'admin/notifications/*'],
            'icon' => '/images/aside/SidebarNotifications.png',
            'icon_active' => '/images/aside/NotificationActive.png',
            'notification' => $unreadNotificationCount > 0,
        ],
    ];

    $activeNavItem = null;
    foreach ($navItems as $item) {
        foreach ($item['match'] as $pattern) {
            if (request()->is($pattern)) {
                $activeNavItem = $item;
                break 2;
            }
        }
    }
    $activeNavIcon = $activeNavItem['icon_active'] ?? $activeNavItem['icon'] ?? null;

    $backUrl = trim((string) $__env->yieldContent('backUrl', ''));
    $hideMobileChrome = trim((string) $__env->yieldContent('hideMobileNav', '')) !== '';
@endphp
@unless ($hideMobileChrome)
<header class="mobile-topbar">
    @if ($backUrl !== '')
        <a href="{{ $backUrl }}" class="mobile-topbar-btn" aria-label="Go back">
            <i class="bi bi-chevron-left"></i>
        </a>
    @else
        <button type="button" class="mobile-topbar-btn" id="mobileMenuToggle" aria-label="Open menu" aria-controls="mobileMenuPanel" aria-expanded="false" aria-haspopup="true">
            <i class="bi bi-list"></i>
            {{-- Combines both signals — the menu this opens holds both the
                 Notification and Approval Order items. --}}
            <span class="noti-dot {{ ($unreadNotificationCount > 0 || $pendingOrdersCount > 0) ? 'show' : '' }}" aria-hidden="true"></span>
        </button>
    @endif

    <span class="mobile-topbar-title">
        @if ($backUrl === '' && $activeNavIcon)
            {{-- <img src="{{ asset($activeNavIcon) }}" alt="" class="mobile-topbar-title-icon"> --}}
        @endif
        @yield('title', 'POS Admin')
    </span>

    <a href="{{ route('admin.notifications.index') }}" class="mobile-topbar-btn mobile-topbar-bell" aria-label="Notifications">
        <i class="bi bi-bell-fill"></i>
        <span id="mobileNotiDot" class="noti-dot {{ $unreadNotificationCount > 0 ? 'show' : '' }}" aria-hidden="true"></span>
    </a>

    @if ($backUrl === '')
        <nav class="mobile-menu-panel" id="mobileMenuPanel">
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
                <a href="{{ $item['url'] }}" class="mobile-menu-link {{ $isActive ? 'active' : '' }}">
                    <img src="{{ asset($iconToShow) }}" alt="" class="mobile-menu-link-icon">
                    {{ $item['name'] }}
                </a>
            @endforeach

            <div class="mobile-menu-divider"></div>

            <a href="{{ route('user.index') }}" class="mobile-menu-link">
                <img src="{{ asset('images/aside/open admin (2).png') }}" alt="" class="mobile-menu-link-icon">
                Open User
            </a>
            <a href="{{ route('pos.index') }}" class="mobile-menu-link">
                <img src="{{ asset('images/aside/open admin active.png') }}" alt="" class="mobile-menu-link-icon">
                Open Management
            </a>
            <a href="/logout" class="mobile-menu-link mobile-menu-link-danger">
                <img src="{{ asset('images/aside/logout.png') }}" alt="" class="mobile-menu-link-icon">
                Log out
            </a>
        </nav>
    @endif
</header>

<div class="mobile-menu-backdrop" id="mobileMenuBackdrop"></div>

<nav class="mobile-bottom-nav">
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
        <a href="{{ $item['url'] }}" class="mobile-bottom-nav-item {{ $isActive ? 'active' : '' }}">
            <span class="mobile-bottom-nav-icon {{ !empty($item['notification']) ? 'nav-icon-notification' : '' }}">
                <img src="{{ asset($iconToShow) }}" alt="{{ $item['name'] }} Icon">
                @if (!empty($item['notification']))
                    <span class="noti-dot show" aria-hidden="true"></span>
                @endif
            </span>
            <span class="mobile-bottom-nav-label">{{ $item['name'] }}</span>
        </a>
    @endforeach
</nav>
@endunless

<div class="sidebar-wrap" id="sidebarWrap">
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
                                    <span class="noti-dot show" aria-hidden="true"></span>
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
                    <a href="{{ route('user.index') }}" class="settings-link nav-link-mobile-close">Open User</a>
                    <a href="{{ route('pos.index') }}" class="settings-link nav-link-mobile-close">Open Management</a>
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

<!-- ===== Logout confirmation modal ===== -->
<div class="logout-confirm-overlay" id="logoutConfirmOverlay">
    <div class="logout-confirm-box">
        <div class="logout-confirm-icon"><i class="bi bi-box-arrow-right"></i></div>
        <h3 class="logout-confirm-title">Log out?</h3>
        <p class="logout-confirm-text">Are you sure you want to log out of your account?</p>
        <div class="logout-confirm-actions">
            <button type="button" class="logout-confirm-btn cancel" id="logoutConfirmCancel">Cancel</button>
            <button type="button" class="logout-confirm-btn confirm" id="logoutConfirmOk">Yes, Log out</button>
        </div>
    </div>
</div>
{{--
<link rel="stylesheet" href="{{ asset('/') }}">
<link rel="stylesheet" href="{{ asset('/css/views/Layout/POSAdmin/mobile-nav.css') }}"> --}}

<script>
(function () {
    const overlay = document.getElementById('logoutConfirmOverlay');
    if (overlay && overlay.dataset.bound !== 'true') {
        overlay.dataset.bound = 'true';

        const okBtn = document.getElementById('logoutConfirmOk');
        const cancelBtn = document.getElementById('logoutConfirmCancel');
        let pendingHref = '/logout';

        const openLogoutModal = (href) => {
            pendingHref = href || '/logout';
            overlay.classList.add('show');
        };
        const closeLogoutModal = () => overlay.classList.remove('show');

        document.addEventListener('click', function (e) {
            const link = e.target.closest('a[href="/logout"]');
            if (!link) return;
            e.preventDefault();
            openLogoutModal(link.getAttribute('href'));
        });

        okBtn?.addEventListener('click', () => { window.location.href = pendingHref; });
        cancelBtn?.addEventListener('click', closeLogoutModal);
        overlay.addEventListener('click', (e) => { if (e.target === overlay) closeLogoutModal(); });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && overlay.classList.contains('show')) closeLogoutModal();
        });
    }
})();

(function () {
    const menuToggle = document.getElementById('mobileMenuToggle');
    const menuPanel = document.getElementById('mobileMenuPanel');
    const backdrop = document.getElementById('mobileMenuBackdrop');

    if (!menuToggle || !menuPanel || !backdrop) return;

    function openMenu() {
        menuPanel.classList.add('open');
        backdrop.classList.add('show');
        menuToggle.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
        menuPanel.classList.remove('open');
        backdrop.classList.remove('show');
        menuToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }

    menuToggle.addEventListener('click', function () {
        if (menuPanel.classList.contains('open')) {
            closeMenu();
        } else {
            openMenu();
        }
    });

    backdrop.addEventListener('click', closeMenu);

    menuPanel.querySelectorAll('.mobile-menu-link').forEach(function (link) {
        link.addEventListener('click', closeMenu);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeMenu();
    });
})();
</script>
