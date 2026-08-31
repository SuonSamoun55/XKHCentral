@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\Route;
    use App\Models\ManagementSystem\Company;

    /** @var \App\Models\ManagementSystem\User $authUser */
    $authUser = Auth::user();

    // 1. Fetch Company Logic (Same as Admin)
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

    $companyName = $company->display_name ?? $company->name ?? 'Orange';
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

    // Used to conditionally show the "Open Admin" link below — mirrors the
    // 'permission:dashboard' gate on the /admin route itself, so this link
    // only appears for roles that can actually get in.
    $isAdmin = $authUser->isAdmin() || $authUser->hasPermission('dashboard');
        $navItems = [
        [
            'name' => 'Dashboard',
            'url' => '/',
            'match' => ['/', 'pos-system'],
            'icon' => 'images/aside/SidbarDaskboards.png',
            'icon_active' => 'images/aside/UserDaskboardActive.png',
        ],
        [
            'name' => 'Cart',
            'url' => '/pos-system/cart',
            'match' => ['pos-system/cart'],
            'icon' => 'images/aside/SidebarCarts.png',
            'icon_active' => 'images/aside/UserCartActive.png',
            'badge' => 'cart',
        ],
        [
            'name' => 'Favorite',
            'url' => '/pos-system/favorites',
            'match' => ['pos-system/favorites'],
            'icon' => 'images/aside/SidebarFavorites.png',
            'icon_active' => 'images/aside/FavoriteActive.png',
        ],
        [
            'name' => 'Order History',
            'url' => '/pos-system/order-history',
            'match' => ['pos-system/order-history'],
            'icon' => '/images/AdminPOS/Admin_POS_Approval_Order.png',
            'icon_active' => '/images/AdminPOS/Admin_POS_Approval_Order_active.png',
        ],
        [
            'name' => 'Notification',
            'url' => '/pos-system/notifications',
            'match' => ['pos-system/notifications'],
            'icon' => 'images/aside/SidebarNotifications.png',
            'icon_active' => 'images/aside/NotificationActive.png',
            'badge' => 'notification',
        ],

    ];
@endphp
<div class="sidebar-wrap">
    <aside class="sidebar" id="appSidebar">
        <div class="sidebar-top">
            <div class="brand">
                <div class="company-logo-box">
                    <img src="{{ $companyLogoUrl }}" alt="Company Logo" class="company-logo-img" onerror="this.onerror=null;this.src='{{ asset('images/default-company.png') }}';">
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
                                    <span id="asideCartCount" class="noti-dot {{ (int) ($cartCount ?? 0) > 0 ? 'show' : '' }}" aria-hidden="true"></span>
                                @endif
                            </span>
                            <span class="nav-label">{{ $item['name'] }}</span>
                        </button>
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="sidebar-bottom">
            @php $authUser = Auth::user(); @endphp
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
                        <img src="{{ asset('images/aside/setting.png') }}" alt="Settings Icon">
                    </span>
                    <span class="nav-label">Settings</span>
                    <span class="settings-arrow">⌄</span>
                </button>

                <div class="settings-menu">
                    <a href="{{ route('profile') }}" class="settings-link nav-link-mobile-close">My Profile</a>
                    <a href="{{ route('user.password.change') }}" class="settings-link nav-link-mobile-close">Change password</a>
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
(function () {
    const overlay = document.getElementById('logoutConfirmOverlay');
    if (!overlay || overlay.dataset.bound === 'true') return;
    overlay.dataset.bound = 'true';

    const okBtn = document.getElementById('logoutConfirmOk');
    const cancelBtn = document.getElementById('logoutConfirmCancel');
    let pendingHref = '/logout';

    function openModal(href) {
        pendingHref = href || '/logout';
        overlay.classList.add('show');
    }
    function closeModal() {
        overlay.classList.remove('show');
    }
    document.addEventListener('click', function (e) {
        const link = e.target.closest('a[href="/logout"]');
        if (!link) return;
        e.preventDefault();
        openModal(link.getAttribute('href'));
    });

    okBtn?.addEventListener('click', function () {
        window.location.href = pendingHref;
    });
    cancelBtn?.addEventListener('click', closeModal);
    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) closeModal();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && overlay.classList.contains('show')) closeModal();
    });
})();
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const appShell = document.getElementById('appShell');
        const collapseHandle = document.getElementById('collapseHandle');
        const settingsBtn = document.getElementById('settingsBtn');
        const settingsBox = document.getElementById('settingsBox');
        const navButtons = document.querySelectorAll('.nav-btn');
        const unreadNotiDot = document.getElementById('unreadNotiDot');
        const appSidebar = document.getElementById('appSidebar');

        if (collapseHandle && appShell) {
            collapseHandle.addEventListener('click', () => {
                appShell.classList.toggle('collapsed');

                if (appShell.classList.contains('collapsed')) {
                    settingsBox?.classList.remove('open');
                }
            });
        }

        if (settingsBtn) {
            settingsBtn.addEventListener('click', () => {
                if (appShell?.classList.contains('collapsed')) return;
                settingsBox?.classList.toggle('open');
                appShell.classList.toggle('settings-active');  // ✅ ADD THIS
            });
        };
        document.querySelectorAll('.nav-link-mobile-close').forEach((link) => {
            link.addEventListener('click', () => {
                if (appSidebar?.classList.contains('mobile-open') && typeof toggleMobileSidebar === 'function') {
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
        setInterval(fetchUnreadNotifications, 15000);

    });
</script>
