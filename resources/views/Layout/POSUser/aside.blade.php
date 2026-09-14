
<div class="sidebar-wrap">
    <aside class="sidebar" id="appSidebar">
        <div class="sidebar-top">
            <div class="brand">
                <div class="company-logo-box">
                    <img src="{{ $companyLogoUrl }}" alt="Company Logo" class="company-logo-img"
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
                            <span class="nav-icon {{ !empty($item['badge']) ? 'nav-icon-notification' : '' }}">
                                <img src="{{ asset($iconToShow) }}" alt="{{ $item['name'] }} Icon">
                                @if (($item['badge'] ?? null) === 'notification')
                                    <span id="unreadNotiDot" class="noti-dot" aria-hidden="true"></span>
                                @elseif (($item['badge'] ?? null) === 'cart')
                                    <span id="asideCartCount"
                                        class="noti-dot {{ (int) ($cartCount ?? 0) > 0 ? 'show' : '' }}"
                                        aria-hidden="true"></span>
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
                        <div class="user-name">{{ $authUser ? ucwords($authUser->name) : 'Guest' }}</div>
                        <div class="user-role">{{ $authUser ? ucfirst($authUser->role ?? 'User') : 'Guest' }}</div>
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
                    <a href="{{ route('profile') }}" class="settings-link nav-link-mobile-close">My Profile</a>
                    <a href="{{ route('user.password.change') }}" class="settings-link nav-link-mobile-close">Change
                        password</a>
                    <a href="{{ route('profile') }}" class="settings-link">Policy</a>
                    @if ($isAdmin)
                        <a href="{{ url('/admin') }}" class="settings-link nav-link-mobile-close">Open Admin</a>
                    @endif
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

    <button class="collapse-handle" id="collapseHandle" type="button">
        <span>‹</span>
    </button>

    <div id="globalToastContainer" class="global-toast-container"></div>
</div>

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

<link rel="stylesheet" href="{{ asset('/css/views/POSViews/POSUserViews/Layout/aside.css') }}">

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const appSidebarRoot = document.getElementById('appSidebar');
        if (!appSidebarRoot || appSidebarRoot.dataset.bound === 'true') return;
        appSidebarRoot.dataset.bound = 'true';

        // --- Logout confirmation modal ---
        const overlay = document.getElementById('logoutConfirmOverlay');
        if (overlay) {
            const okBtn = document.getElementById('logoutConfirmOk');
            const cancelBtn = document.getElementById('logoutConfirmCancel');
            let pendingHref = '/logout';

            const openModal = (href) => {
                pendingHref = href || '/logout';
                overlay.classList.add('show');
            };
            const closeModal = () => overlay.classList.remove('show');

            document.addEventListener('click', function(e) {
                const link = e.target.closest('a[href="/logout"]');
                if (!link) return;
                e.preventDefault();
                openModal(link.getAttribute('href'));
            });

            okBtn?.addEventListener('click', () => window.location.href = pendingHref);
            cancelBtn?.addEventListener('click', closeModal);
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) closeModal();
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && overlay.classList.contains('show')) closeModal();
            });
        }

        // --- Sidebar collapse / settings / notifications ---
        const appShell = document.getElementById('appShell');
        const collapseHandle = document.getElementById('collapseHandle');
        const settingsBtn = document.getElementById('settingsBtn');
        const settingsBox = document.getElementById('settingsBox');
        const navButtons = document.querySelectorAll('.nav-btn');
        const unreadNotiDot = document.getElementById('unreadNotiDot');
        const appSidebar = appSidebarRoot;
        const COLLAPSE_STORAGE_KEY = 'posUserSidebarCollapsed';

        if (collapseHandle && appShell) {
            collapseHandle.addEventListener('click', () => {
                appShell.classList.toggle('collapsed');

                try {
                    localStorage.setItem(COLLAPSE_STORAGE_KEY, appShell.classList.contains(
                    'collapsed'));
                } catch (_) {
                }

                if (appShell.classList.contains('collapsed')) {
                    settingsBox?.classList.remove('open');
                }
            });
        }

        if (settingsBtn) {
            settingsBtn.addEventListener('click', () => {
                if (appShell?.classList.contains('collapsed')) return;
                settingsBox?.classList.toggle('open');
                appShell.classList.toggle('settings-active'); // ✅ ADD THIS
            });
        };
        document.querySelectorAll('.nav-link-mobile-close').forEach((link) => {
            link.addEventListener('click', () => {
                if (appSidebar?.classList.contains('mobile-open') &&
                    typeof toggleMobileSidebar === 'function') {
                    toggleMobileSidebar();
                }
            });
        });

        const toastContainer = document.getElementById('globalToastContainer');
        let shownNotificationIds = new Set(JSON.parse(localStorage.getItem('shownNotificationIds') || '[]'));

        function createToast(notification) {
            if (shownNotificationIds.has(notification.id)) return;

            const item = document.createElement('div');
            item.className = 'global-toast';
            const avatarUrl = notification.sender_image || '/images/default-avatar.png';
            item.innerHTML = `
        <button class="global-toast-close" aria-label="Close">&times;</button>
        <div class="toast-content-wrapper">
            <img src="${avatarUrl}" class="toast-avatar" alt="User">
            <div class="toast-text-side">
                <strong class="toast-title">${escapeHtml(notification.title)}</strong>
                <small class="toast-date">${new Date(notification.created_at).toLocaleString()}</small>
                <div class="toast-actions-mini">
                    <button class="btn-toast-view">View</button>
                    <button class="btn-toast-dismiss">Dismiss</button>
                </div>
            </div>
        </div>
    `;
            item.querySelector('.global-toast-close').addEventListener('click', (e) => {
                e.stopPropagation();
                item.remove();
            });
            item.querySelector('.btn-toast-dismiss').addEventListener('click', (e) => {
                e.stopPropagation();
                item.remove();
            });
            item.addEventListener('click', () => {
                window.location.href = '{{ route('user.notifications') }}';
            });

            toastContainer.appendChild(item);
            shownNotificationIds.add(notification.id);
            localStorage.setItem('shownNotificationIds', JSON.stringify(Array.from(shownNotificationIds)));

            setTimeout(() => {
                if (item.parentElement) item.remove();
            }, 3000);
        }

        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return String(text ?? '').replace(/[&<>"']/g, m => map[m]);
        }

        function updateNotificationDot(unreadCount) {
            const mobileUnreadNotiDot = document.getElementById('mobileUnreadNotiDot');
            const hasUnread = Number(unreadCount) > 0;

            unreadNotiDot?.classList.toggle('show', hasUnread);
            mobileUnreadNotiDot?.classList.toggle('show', hasUnread);
        }

        function fetchUnreadNotifications() {
            fetch('{{ route('user.notifications.unread') }}', {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (!data || typeof data.count === 'undefined') return;
                    updateNotificationDot(data.count);
                    if (data.notifications && Array.isArray(data.notifications)) {
                        data.notifications.forEach(notification => createToast(notification));
                    }
                })
                .catch(err => console.debug('Unread notification check failed', err));
        }

        fetchUnreadNotifications();

        setInterval(fetchUnreadNotifications, 30000);

    });
</script>
