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

        @if ($canAccessPage('notifications'))
            <a href="{{ route('admin.notifications.index') }}" class="mobile-topbar-btn mobile-topbar-bell"
                aria-label="Notifications">
                <i class="bi bi-bell-fill"></i>
                <span class="noti-dot {{ $unreadNotificationCount > 0 ? 'show' : '' }}" aria-hidden="true"></span>
            </a>
        @else
            {{-- Keeps the title centred when the bell is hidden --}}
            <span class="mobile-topbar-btn" style="visibility:hidden" aria-hidden="true"></span>
        @endif

        @if ($backUrl === '')
            <nav class="mobile-menu-panel" id="mobileMenuPanel">
                @foreach ($mobileMenuItems as $item)
                    @php
                        $isActive = false;
                        foreach ($item['match'] as $pattern) {
                            if (request()->is($pattern)) {
                                $isActive = true;
                                break;
                            }
                        }

                        if ($onCompanySelectScreen) {
                            $isActive = false;
                        }

                        $iconToShow = $item['icon'] ?? null;
                        if ($isActive && !empty($item['icon_active'])) {
                            $iconToShow = $item['icon_active'];
                        }
                    @endphp
                    <a href="{{ $item['url'] }}" class="mobile-menu-link {{ $isActive ? 'active' : '' }}">
                        @if (!empty($iconToShow))
                            <img src="{{ asset($iconToShow) }}" alt="" class="mobile-menu-link-icon">
                        @else
                            <i class="bi {{ $item['icon_class'] ?? 'bi-circle' }} mobile-menu-link-icon"></i>
                        @endif
                        {{ $item['name'] }}
                    </a>
                @endforeach
                <div class="mobile-menu-divider"></div>

                @if ($canAccessPage('home'))
                    <a href="{{ route('user.index') }}" class="mobile-menu-link">
                        <img src="{{ asset('/images/aside/open admin (2).png') }}" alt="" class="mobile-menu-link-icon">
                        Open User
                    </a>
                @endif
                @if ($canAccessPage('orders'))
                    <a href="{{ route('admin.orders.index') }}" class="mobile-menu-link">
                        <img src="{{ asset('/images/management/management.png') }}" alt=""
                            class="mobile-menu-link-icon">
                        Open POS system
                    </a>
                @endif
                <a href="{{ route('admin.password.change') }}" class="mobile-menu-link">
                    <i class="bi bi-key"></i>
                    Change password
                </a>
                @if ($canAccessLoginSetup)
                    @php $loginSetupActive = request()->is('login-settings', 'login-settings/*'); @endphp
                    <a href="{{ route('login-settings.index') }}" class="mobile-menu-link {{ $loginSetupActive ? 'active' : '' }}">
                        <img src="{{ asset('images/management/login_setup_active.png') }}" alt=""
                            class="mobile-menu-link-icon">
                        Login page setup
                    </a>
                @endif
                <a href="/logout" class="mobile-menu-link mobile-menu-link-danger">
                    <img src="{{ asset('images/aside/logout.png') }}" alt="" class="mobile-menu-link-icon">
                    Log out
                </a>
            </nav>
        @endif
    </header>

    <div class="mobile-menu-backdrop" id="mobileMenuBackdrop"></div>

    <nav class="mobile-bottom-nav">
        @foreach ($bottomNavItems as $item)
            @php
                $isActive = false;
                foreach ($item['match'] as $pattern) {
                    if (request()->is($pattern)) {
                        $isActive = true;
                        break;
                    }
                }
                if ($onCompanySelectScreen) {
                    $isActive = false;
                }

                $iconToShow = $item['icon'] ?? null;
                if ($isActive && !empty($item['icon_active'])) {
                    $iconToShow = $item['icon_active'];
                }

            @endphp
            @if (!empty($item['children']))
                <button type="button" class="mobile-bottom-nav-item {{ $isActive ? 'active' : '' }}"
                    data-open-mobile-menu>
                    <span class="mobile-bottom-nav-icon">
                        @if (!empty($iconToShow))
                            <img src="{{ asset($iconToShow) }}" alt="{{ $item['name'] }} Icon">
                        @else
                            <i class="bi {{ $item['icon_class'] ?? 'bi-circle' }}"></i>
                        @endif
                    </span>
                    <span class="mobile-bottom-nav-label">{{ $item['name'] }}</span>
                </button>
            @else
                <a href="{{ $item['url'] }}" class="mobile-bottom-nav-item {{ $isActive ? 'active' : '' }}">
                    <span class="mobile-bottom-nav-icon">
                        @if (!empty($iconToShow))
                            <img src="{{ asset($iconToShow) }}" alt="{{ $item['name'] }} Icon">
                        @else
                            <i class="bi {{ $item['icon_class'] ?? 'bi-circle' }}"></i>
                        @endif
                    </span>
                    <span class="mobile-bottom-nav-label">{{ $item['name'] }}</span>
                </a>
            @endif
        @endforeach

        @php $profileActive = request()->is('admin/profile', 'admin/profile/*'); @endphp
        <a href="{{ route('admin.profile') }}" class="mobile-bottom-nav-item {{ $profileActive ? 'active' : '' }}">
            <span class="mobile-bottom-nav-icon">
                <img src="{{ asset($profileActive ? '/images/aside/edit profile.png' : '/images/management/admin_profile.png') }}" alt="My Profile Icon">
            </span>
            <span class="mobile-bottom-nav-label">My Profile</span>
        </a>
    </nav>
@endunless

<div class="sidebar-wrap" id="sidebarWrap">
    <aside class="sidebar" id="appSidebar">
        <div class="sidebar-top">
            <div class="brand">
                <div class="company-logo-box">
                    <a href="{{ route('pos.index') }}" style="display:contents" aria-label="Go to dashboard">
                    <img src="{{ $companyLogoUrl }}" alt="Company Logo" class="company-logo-img"
                        onerror="this.onerror=null;this.src='{{ asset('images/default-company.png') }}';">
                    </a>
                </div>
            </div>
            {{-- <div class="brand-text">{{ $companyName }}</div> --}}
            {{-- <marquee behavior="scroll" direction="left">
                <span class="brand-text">{{ $companyName }}</span>
            </marquee> --}}
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

                        // The select-company screen shouldn't appear active on
// the Companies/Company nav item.
if ($onCompanySelectScreen) {
    $isActive = false;
}

$iconToShow = $item['icon'] ?? null;
if ($isActive && !empty($item['icon_active'])) {
    $iconToShow = $item['icon_active'];
                        }
                    @endphp
                    @if (!empty($item['children']))
                        <div class="nav-group" data-nav-group-key="{{ \Illuminate\Support\Str::slug($item['name']) }}">
                            <button class="nav-btn nav-btn-group {{ $isActive ? 'active' : '' }}" type="button"
                                data-nav-group-toggle>
                                <span class="nav-icon">
                                    @if (!empty($iconToShow))
                                        <img src="{{ asset($iconToShow) }}" alt="{{ $item['name'] }} Icon">
                                    @else
                                        <i class="bi {{ $item['icon_class'] ?? 'bi-circle' }}"
                                            style="font-size:16px;"></i>
                                    @endif
                                </span>
                                <span class="nav-label">{{ $item['name'] }}</span>
                                <i class="bi bi-chevron-down nav-group-chevron"></i>
                            </button>

                            <div class="nav-submenu">
                                @foreach ($item['children'] as $child)
                                    @php
                                        $childActive = false;
                                        foreach ($child['match'] as $pattern) {
                                            if (request()->is($pattern)) {
                                                $childActive = true;
                                                break;
                                            }
                                        }
                                        if ($onCompanySelectScreen) {
                                            $childActive = false;
                                        }
                                    @endphp
                                    <a href="{{ $child['url'] }}"
                                        class="nav-sublink nav-link-mobile-close {{ $childActive ? 'active' : '' }}">
                                        <span class="nav-sublink-label">{{ $child['name'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $item['url'] }}" class="nav-link-mobile-close">
                            <button class="nav-btn {{ $isActive ? 'active' : '' }}" type="button">
                                <span class="nav-icon">
                                    @if (!empty($iconToShow))
                                        <img src="{{ asset($iconToShow) }}" alt="{{ $item['name'] }} Icon">
                                    @else
                                        <i class="bi {{ $item['icon_class'] ?? 'bi-circle' }}"
                                            style="font-size:16px;"></i>
                                    @endif
                                </span>
                                <span class="nav-label">{{ $item['name'] }}</span>
                            </button>
                        </a>
                    @endif
                @endforeach
            </nav>
        </div>

        <div class="sidebar-bottom">
            {{-- <a href="{{ route('admin.profile') }}" class="user-link"> --}}
            <div class="profiles">
                <img src="{{ $userAvatar }}" alt="User" id="sidebarProfileImage"
                    onerror="this.onerror=null;this.src='{{ asset('images/default-user.png') }}';">
                <div class="profile-text">
                    <div class="user-meta">
                        <div class="user-name">{{ $authUser ? ucwords($authUser->name) : 'Guest' }}</div>
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
                    @if ($canAccessPage('home'))
                        <a href="{{ route('user.index') }}" class="settings-link nav-link-mobile-close">Open User</a>
                    @endif
                    @if ($canAccessPage('orders'))
                        <a href="{{ route('admin.orders.index') }}" class="settings-link nav-link-mobile-close">Open
                            POS system</a>
                    @endif
                    <a href="{{ route('admin.profile') }}" class="settings-link nav-link-mobile-close">Admin
                        Profile</a>
                    <a href="{{ route('admin.password.change') }}" class="settings-link nav-link-mobile-close">Change
                        password</a>
                    @if ($canAccessLoginSetup)
                        <a href="{{ route('login-settings.index') }}" class="settings-link nav-link-mobile-close">Login
                            page setup</a>
                    @endif
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
            // Restoring the collapsed class itself happens synchronously in
            // an inline <script> right after #managementShell opens (see
            // app.blade.php), so it's applied before this file even runs —
            // no flash of the expanded sidebar on page load.
            const COLLAPSE_STORAGE_KEY = 'managementSidebarCollapsed';

            if (collapseHandle) {
                collapseHandle.addEventListener('click', () => {
                    appShell.classList.toggle('collapsed');

                    try {
                        localStorage.setItem(COLLAPSE_STORAGE_KEY, appShell.classList.contains(
                            'collapsed'));
                    } catch (_) {
                        // localStorage unavailable — nothing to do.
                    }

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

            // Bottom-nav items that represent a group (User, Company) have no
            // page of their own — tapping them opens the full slide-out menu
            // instead, where their individual pages are listed.
            document.querySelectorAll('.mobile-bottom-nav-item[data-open-mobile-menu]').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    openMenu();
                });
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeMenu();
            });
        }
    })();

    (function() {
        const STORAGE_KEY = 'managementOpenNavGroups';

        function getOpenGroups() {
            try {
                return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
            } catch (_) {
                return [];
            }
        }

        function setGroupOpen(key, isOpen) {
            if (!key) return;
            try {
                const open = new Set(getOpenGroups());
                if (isOpen) {
                    open.add(key);
                } else {
                    open.delete(key);
                }
                localStorage.setItem(STORAGE_KEY, JSON.stringify([...open]));
            } catch (_) {
                // localStorage unavailable — state just won't persist across page loads.
            }
        }

        // Restore the open group from the last page before this one navigated
        // away — full page loads otherwise reset every group shut. Only the
        // first match is restored (accordion: at most one group open), in
        // case an older stored value still lists more than one key.
        const openGroups = new Set(getOpenGroups());
        let restoredOne = false;
        document.querySelectorAll('.nav-group[data-nav-group-key]').forEach(function(group) {
            if (!restoredOne && openGroups.has(group.dataset.navGroupKey)) {
                group.classList.add('open');
                restoredOne = true;
            }
        });

        document.querySelectorAll('[data-nav-group-toggle]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const group = btn.closest('.nav-group');
                if (!group) return;

                const willOpen = !group.classList.contains('open');

                // Settings and the menu groups share the space: opening a
                // group closes Settings, so the sidebar never needs to scroll.
                if (willOpen && document.getElementById('settingsBox')?.classList.contains('open')) {
                    document.getElementById('settingsBtn')?.click();
                }

                // Accordion behavior: only one submenu open at a time, so the
                // sidebar's total height never grows enough to push/overlap
                // the profile and settings area pinned at the bottom.
                document.querySelectorAll('.nav-group[data-nav-group-key].open').forEach(function(openGroup) {
                    if (openGroup === group) return;
                    openGroup.classList.remove('open');
                    setGroupOpen(openGroup.dataset.navGroupKey, false);
                });

                group.classList.toggle('open', willOpen);
                setGroupOpen(group.dataset.navGroupKey, willOpen);
            });
        });

        // ...and opening Settings closes the open menu group. Checked just
        // after the click, once Settings' own handler has opened it.
        document.getElementById('settingsBtn')?.addEventListener('click', function() {
            setTimeout(function() {
                if (!document.getElementById('settingsBox')?.classList.contains('open')) return;
                document.querySelectorAll('.nav-group[data-nav-group-key].open').forEach(function(openGroup) {
                    openGroup.classList.remove('open');
                    setGroupOpen(openGroup.dataset.navGroupKey, false);
                });
            });
        });
    })();
</script>
