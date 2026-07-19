@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;
    use App\Models\ManagementSystem\Company;

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

    // ------------------------------------------------------------------
    // Sidebar nav items — one array entry per link.
    // 'match' is the list of URL patterns (request()->is()) that should
    // mark this item as active.
    // 'icon' is the normal icon, 'icon_active' is shown while the item
    // is active. If you don't have a separate active icon yet, just
    // point 'icon_active' to the same file as 'icon'.
    // ------------------------------------------------------------------
    $navItems = [
        [
            'name' => 'Dashboard',
            'url' => '/',
            'match' => ['/', 'pos-system'],
            'icon' => 'images/aside/SidbarDaskboard.png',
            'icon_active' => 'images/aside/UserDaskboardActive.png',
        ],
        [
            'name' => 'Cart',
            'url' => '/pos-system/cart',
            'match' => ['pos-system/cart'],
            'icon' => 'images/aside/SidebarCart.png',
            'icon_active' => 'images/aside/UserCartActive.png',
        ],
        [
            'name' => 'Favorite',
            'url' => '/pos-system/favorites',
            'match' => ['pos-system/favorites'],
            'icon' => 'images/aside/SidebarFavorite.png',
            'icon_active' => 'images/aside/FavoriteActive.png',
        ],
        [
            'name' => 'Order History',
            'url' => '/pos-system/order-history',
            'match' => ['pos-system/order-history'],
            'icon' => 'images/aside/SidebarOrder.png',
            'icon_active' => 'images/aside/OrderHistoryActive.png',
        ],
        [
            'name' => 'Notification',
            'url' => '/pos-system/notifications',
            'match' => ['pos-system/notifications'],
            'icon' => 'images/aside/SidebarNotification.png',
            'icon_active' => 'images/aside/NotificationActive.png',
            'notification' => true,
        ],

    ];
@endphp

<div class="sidebar-wrap">
    <aside class="sidebar">
        <div class="sidebar-top">
            <div class="brand">
                <div class="company-logo-box">
                    <img src="{{ $companyLogoUrl }}"
                         alt="Company Logo"
                         class="company-logo-img"
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

                        $iconToShow = $item['icon'];
                        if ($isActive && !empty($item['icon_active'])) {
                            $iconToShow = $item['icon_active'];
                        }
                    @endphp
                    <a href="{{ $item['url'] }}">
                        <button class="nav-btn {{ $isActive ? 'active' : '' }}" type="button">
                            <span class="nav-icon {{ !empty($item['notification']) ? 'nav-icon-notification' : '' }}">
                                <img src="{{ asset($iconToShow) }}" alt="{{ $item['name'] }} Icon">
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
            @php $authUser = Auth::user(); @endphp
            {{-- <a href="{{ route('profile') }}" class="user-link"> --}}
            @php
                $avatarUrl = $userAvatar;
            @endphp
            <div class="profile">
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
                    <a href="{{ route('profile') }}" class="settings-link">Edit Profile</a>
                    <a href="{{ route('user.password.change') }}" class="settings-link">Change new password</a>
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

    <button class="collapse-handle" id="collapseHandle" type="button">
        <span>‹</span>
    </button>

    <div id="globalToastContainer" class="global-toast-container"></div>
</div>
{{-- <link rel="stylesheet" href="{{ asset('css/management-system/dashboard.css') }}" /> --}}
<link rel="stylesheet" href="{{ asset('css/management-system/aside.css') }}" />

<link rel="stylesheet" href="{{ asset('css/views/ManagementSystemViews/UserViews/Layouts/aside.css') }}">

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const appShell = document.getElementById('appShell');
        const collapseHandle = document.getElementById('collapseHandle');
        const settingsBtn = document.getElementById('settingsBtn');
        const settingsBox = document.getElementById('settingsBox');
        const navButtons = document.querySelectorAll('.nav-btn');
        const unreadNotiDot = document.getElementById('unreadNotiDot');

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
        const toastContainer = document.getElementById('globalToastContainer');
        let shownNotificationIds = new Set(JSON.parse(localStorage.getItem('shownNotificationIds') || '[]'));

        function createToast(notification) {
            if (shownNotificationIds.has(notification.id)) return;

            const item = document.createElement('div');
            item.className = 'global-toast';

            // Using a default avatar if notification doesn't have a sender image
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

            // Handle Close Button
            item.querySelector('.global-toast-close').addEventListener('click', (e) => {
                e.stopPropagation();
                item.remove();
            });

            // Handle Dismiss Button
            item.querySelector('.btn-toast-dismiss').addEventListener('click', (e) => {
                e.stopPropagation();
                item.remove();
            });

            // Handle View/Click
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
            if (!unreadNotiDot) return;
            if (Number(unreadCount) > 0) {
                unreadNotiDot.classList.add('show');
            } else {
                unreadNotiDot.classList.remove('show');
            }
        }

        function fetchUnreadNotifications() {
            fetch('{{ route('user.notifications.unread') }}', {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (!data || typeof data.unread_count === 'undefined') return;
                    updateNotificationDot(data.unread_count);
                    if (data.unread && Array.isArray(data.unread)) {
                        data.unread.forEach(notification => createToast(notification));
                    }
                })
                .catch(err => console.debug('Unread notification check failed', err));
        }

        fetchUnreadNotifications();
        setInterval(fetchUnreadNotifications, 15000);

    });
</script>
