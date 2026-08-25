@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\Route;
    use App\Models\ManagementSystem\Company;
    use App\Models\ManagementSystem\Notification;

    $authUser = Auth::user();
    $unreadNotificationCount = Notification::adminUnreadTotal(session('selected_company_id'));
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

    $companyName = $company->display_name ?? ($company->name ?? 'Orange');
    $companyLogoUrl = asset('images/default-company.png');

    if ($company && !empty($company->logo)) {
        if (preg_match('/^https?:\/\//i', $company->logo)) {
            $companyLogoUrl = $company->logo;
        } else {
            $companyLogoUrl = Storage::url($company->logo);
        }
    }

    // 3. Setup User Avatar Logic
    $userAvatar = $authUser->profile_image_display ?? asset('images/default-user.png');

    // A role only bypasses page-permission checks if it's the legacy 'admin'
// string flag — same rule as CheckPagePermission middleware, so the nav
// never shows a link the user would immediately get a 403 from.
$canAccessPage = function (string $page) use ($authUser) {
    if (!$authUser) {
        return false;
    }

    if (strtolower((string) $authUser->role) === 'admin') {
        return true;
    }

    return $authUser->hasPermission($page);
};

$navItems = [
    [
        'name' => 'Dashboard',
        'url' => '/admin',
        'match' => ['admin'],
        'icon' => '/images/aside/SidbarDaskboards.png',
        'icon_active' => '/images/aside/UserDaskboardActive.png',
        'permission' => 'dashboard',
    ],
    [
        'name' => 'Users',
        'url' => '/users',
        'match' => ['users', 'users/*'],
        'icon' => '/images/management/management_user.png',
        'icon_active' => '/images/management/management_user_active.png',
        'permission' => 'users',
    ],
    [
        'name' => 'Pos System',
        'url' => '/pos/interface',
        'match' => ['pos/interface', 'pos/*'],
        'icon' => '/images/management/managemetn_POS.png',
        'icon_active' => '/images/management/management_POS_active.png',
        'permission' => 'pos',
    ],
    [
        'name' => 'Companies',
        'url' => '/companies',
        'match' => ['companies', 'companies/*'],
        'icon' => 'images/management/management_company.png',
        'icon_active' => 'images/management/management_company_active.png',
        'permission' => 'companies',
    ],
    [
        'name' => 'Roles',
        'url' => '/roles',
        'match' => ['roles', 'roles/*'],
        'icon' => '/images/aside/setting.png',
        'icon_active' => '/images/aside/setting.png',
        'permission' => 'roles',
    ],
    [
        'name' => 'Page List',
        'url' => '/permissions',
        'match' => ['permissions', 'permissions/*'],
        'icon' => '/images/aside/setting.png',
        'icon_active' => '/images/aside/setting.png',
        'permission' => 'page_management',
    ],
];
$navItems = array_values(array_filter($navItems, fn($item) => $canAccessPage($item['permission'])));
$activeNavItem = null;
foreach ($navItems as $item) {
    foreach ($item['match'] as $pattern) {
        if (request()->is($pattern)) {
            $activeNavItem = $item;
            break 2;
        }
    }
    if ($item['name'] === 'Companies' && request()->is('companies/select')) {
        $activeNavItem = null;
    }
}
$activeNavIcon = $activeNavItem['icon_active'] ?? ($activeNavItem['icon'] ?? null);

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
            <button type="button" class="mobile-topbar-btn" id="mobileMenuToggle" aria-label="Open menu"
                aria-controls="mobileMenuPanel" aria-expanded="false" aria-haspopup="true">
                <i class="bi bi-list"></i>
            </button>
        @endif

        <span class="mobile-topbar-title">
            @if ($backUrl === '' && $activeNavIcon)
            @endif
            @yield('title', 'Management')
        </span>

        <a href="{{ route('admin.notifications.index') }}" class="mobile-topbar-btn mobile-topbar-bell"
            aria-label="Notifications">
            <i class="bi bi-bell-fill"></i>
            <span class="noti-dot {{ $unreadNotificationCount > 0 ? 'show' : '' }}" aria-hidden="true"></span>
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

                        if ($item['name'] === 'Companies' && request()->is('companies/select')) {
                            $isActive = false;
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
                    <img src="{{ asset('/images/aside/open admin (2).png') }}" alt="" class="mobile-menu-link-icon">
                    Open User
                </a>
                @if ($canAccessPage('orders'))
                    <a href="{{ route('admin.orders.index') }}" class="mobile-menu-link">
                        <img src="{{ asset('/images/management/management.png') }}" alt=""
                            class="mobile-menu-link-icon">
                        Open POS system
                    </a>
                @endif
                <a href="{{ route('admin.profile') }}" class="mobile-menu-link">
                    <img src="{{ asset('/images/aside/edit profile.png') }}" alt="" class="mobile-menu-link-icon">
                    My Profile
                </a>
                <a href="{{ route('admin.password.change') }}" class="mobile-menu-link">
                    <i class="bi bi-key"></i>
                    Change password
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

                if ($item['name'] === 'Companies' && request()->is('companies/select')) {
                    $isActive = false;
                }

                $iconToShow = $item['icon'];
                if ($isActive && !empty($item['icon_active'])) {
                    $iconToShow = $item['icon_active'];
                }
            @endphp
            <a href="{{ $item['url'] }}" class="mobile-bottom-nav-item {{ $isActive ? 'active' : '' }}">
                <span class="mobile-bottom-nav-icon">
                    <img src="{{ asset($iconToShow) }}" alt="{{ $item['name'] }} Icon">
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
                    <img src="{{ $companyLogoUrl }}" alt="Company Logo" class="company-logo-img"
                        onerror="this.onerror=null;this.src='{{ asset('images/default-company.png') }}';">
                </div>
                {{-- <div class="brand-text">{{ $companyName }}</div> --}}
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

                        // "Companies" should not appear active on the select-company screen
                        if ($item['name'] === 'Companies' && request()->is('companies/select')) {
                            $isActive = false;
                        }

                        $iconToShow = $item['icon'];
                        if ($isActive && !empty($item['icon_active'])) {
                            $iconToShow = $item['icon_active'];
                        }
                    @endphp
                    <a href="{{ $item['url'] }}" class="nav-link-mobile-close">
                        <button class="nav-btn {{ $isActive ? 'active' : '' }}" type="button">
                            <span class="nav-icon">
                                <img src="{{ asset($iconToShow) }}" alt="{{ $item['name'] }} Icon">
                            </span>
                            <span class="nav-label">{{ $item['name'] }}</span>
                        </button>
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="sidebar-bottom">
            @php $authUser = Auth::user(); @endphp
            {{-- <a href="{{ route('admin.profile') }}" class="user-link"> --}}
            @php
                $avatarUrl = $userAvatar;
            @endphp
            <div class="profiles">
                <img src="{{ $avatarUrl }}" alt="User" id="sidebarProfileImage"
                    onerror="this.onerror=null;this.src='{{ asset('images/default-user.png') }}';">
                <div class="profile-text">
                    <div class="user-meta">
                        <div class="user-name">{{ $authUser ? $authUser->name : 'Guest' }}</div>
                        <div class="user-role">{{ $authUser ? ucfirst($authUser->role ?? 'User') : 'Guest' }}</div>
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
                    @if ($canAccessPage('orders'))
                        <a href="{{ route('admin.orders.index') }}" class="settings-link nav-link-mobile-close">Open
                            POS system</a>
                    @endif
                    <a href="{{ route('admin.profile') }}" class="settings-link nav-link-mobile-close">Admin
                        Profile</a>
                    <a href="{{ route('admin.password.change') }}" class="settings-link nav-link-mobile-close">Change
                        password</a>
                    {{-- <a href="#" class="settings-link">Policy</a> --}}
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

    <div id="globalToastContainer" class="global-toast-container"></div>
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

<script>
    (function() {
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

            document.addEventListener('click', function(e) {
                const link = e.target.closest('a[href="/logout"]');
                if (!link) return;
                e.preventDefault();
                openLogoutModal(link.getAttribute('href'));
            });

            okBtn?.addEventListener('click', () => {
                window.location.href = pendingHref;
            });
            cancelBtn?.addEventListener('click', closeLogoutModal);
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) closeLogoutModal();
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && overlay.classList.contains('show')) closeLogoutModal();
            });
        }
    })();

    (function() {
        const appShell = document.getElementById('managementShell');
        if (!appShell || appShell.dataset.sidebarJsBound === 'true') {
            return;
        }
        appShell.dataset.sidebarJsBound = 'true';

        function init() {
            const collapseHandle = document.getElementById('collapseHandle');
            const settingsBtn = document.getElementById('settingsBtn');
            const settingsBox = document.getElementById('settingsBox');

            if (collapseHandle) {
                collapseHandle.addEventListener('click', () => {
                    appShell.classList.toggle('collapsed');

                    if (appShell.classList.contains('collapsed')) {
                        settingsBox?.classList.remove('open');
                        appShell.classList.remove('settings-active');
                    }
                });
            }

            if (settingsBtn) {
                const settingsMenu = settingsBox?.querySelector('.settings-menu');
                if (settingsMenu) {
                    settingsMenu.style.overflow = 'hidden';
                    settingsMenu.style.maxHeight = '0px';
                    settingsMenu.style.transition = 'max-height 0.25s ease';
                }

                settingsBtn.addEventListener('click', (e) => {
                    e.stopPropagation();

                    if (appShell.classList.contains('collapsed')) {
                        return;
                    }

                    const willOpen = !settingsBox?.classList.contains('open');
                    settingsBox?.classList.toggle('open', willOpen);
                    appShell.classList.toggle('settings-active', willOpen);

                    if (!settingsMenu) return;

                    if (willOpen) {
                        const targetHeight = settingsMenu.scrollHeight;
                        settingsMenu.style.maxHeight = targetHeight + 'px';

                        const scrollAfterExpand = (evt) => {
                            if (evt.propertyName !== 'max-height') return;
                            settingsMenu.removeEventListener('transitionend', scrollAfterExpand);
                            settingsMenu.scrollIntoView({
                                block: 'end',
                                behavior: 'smooth'
                            });
                        };
                        settingsMenu.addEventListener('transitionend', scrollAfterExpand);
                    } else {
                        settingsMenu.style.maxHeight = '0px';
                    }
                });
            }

            appShell.setAttribute('data-sidebar-ready', 'true');
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }

        // Mobile hamburger menu open/close — mirrors POS Admin's aside.blade.php.
        const menuToggle = document.getElementById('mobileMenuToggle');
        const menuPanel = document.getElementById('mobileMenuPanel');
        const backdrop = document.getElementById('mobileMenuBackdrop');

        if (menuToggle && menuPanel && backdrop) {
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

            menuToggle.addEventListener('click', function() {
                if (menuPanel.classList.contains('open')) {
                    closeMenu();
                } else {
                    openMenu();
                }
            });

            backdrop.addEventListener('click', closeMenu);

            menuPanel.querySelectorAll('.mobile-menu-link').forEach(function(link) {
                link.addEventListener('click', closeMenu);
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeMenu();
            });
        }
    })();
</script>
