@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;
    use App\Models\MagamentSystemModel\Company;

    $authUser = Auth::user();

    // 1. Fetch Company Logic (Same as Admin)
    $company = null;
    if (session('selected_company_id')) {
        $company = Company::find(session('selected_company_id'));
    }

    if (!$company) {
        $company = Company::first();
    }

    // 2. Setup Company Logo and Name
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
    $userAvatar = asset('images/default-user.png');
    if ($authUser) {
        $img = $authUser->avatar ?? $authUser->profile_image ?? $authUser->image;
        if ($img) {
            if (preg_match('/^https?:\/\//i', $img)) {
                $userAvatar = $img;
            } else {
                $userAvatar = asset($img);
            }
        }
    }
@endphp

<div class="sidebar-wrap">
    <aside class="sidebar">
        <div class="sidebar-top">
            <div class="brand">
                <div class="company-logo-box" style="width: 45px; height: 45px; overflow: hidden; border-radius: 8px;">
                    <img src="{{ $companyLogoUrl }}" 
                         alt="Company Logo" 
                         style="width: 100%; height: 100%; object-fit: cover;"
                         onerror="this.onerror=null;this.src='{{ asset('images/default-company.png') }}';">
                </div>
                {{-- <div class="brand-text">{{ $companyName }}</div> --}}
            </div>
            <nav class="nav-list">
                <a href="/">
                    <button class="nav-btn {{ request()->is('/') || request()->is('pos-system') ? 'active' : '' }}"
                        type="button">
                        {{-- <span class="nav-icon">⌗111111111111</span> --}}
                        <span class="nav-icon">
                            <img src="{{ asset('images/aside/dashboard.png') }}" alt="Dashboard Icon">
                        </span> <span class="nav-label">Dashboard</span>
                    </button>
                </a>

                {{-- <a href="/users">
              <button class="nav-btn" type="button">
                <span class="nav-icon">👤</span>
                <span class="nav-label">Users</span>
              </button>
            </a> --}}
                <a href="/pos-system/cart">
                    <button class="nav-btn {{ request()->is('pos-system/cart') ? 'active' : '' }}" type="button">
                        <span class="nav-icon">
                            <img src="{{ asset('images/aside/Cart.png') }}" alt="Cart Icon">
                        </span>
                        <span class="nav-label">Cart</span>
                    </button>
                </a>

                <a href="/pos-system/favorites">
                    <button class="nav-btn {{ request()->is('pos-system/favorites') ? 'active' : '' }}" type="button">
                        <span class="nav-icon">
                            <img src="{{ asset('images/aside/Heart Button.png') }}" alt="Favorite Icon">
                        </span>
                        <span class="nav-label">Favorite</span>
                    </button>
                </a>

                    <a href="/pos-system/order-history">
                        <button class="nav-btn {{ request()->is('pos-system/order-history') ? 'active' : '' }}"
                            type="button">
                            <span class="nav-icon">
                                <img src="{{ asset('images/aside/history.png') }}" alt="Order History Icon">
                            </span>
                            <span class="nav-label">Order History</span>
                        </button>
                    </a>

                    <a href="/pos-system/notifications">
                        <button class="nav-btn {{ request()->is('pos-system/notifications') ? 'active' : '' }}"
                            type="button">
                            <span class="nav-icon">
                                <img src="{{ asset('images/aside/Notification.png') }}" alt="Notification Icon">
                            </span>
                            <span class="nav-label">Notification</span>
                        </button>
                    </a>
            </nav>
        </div>

        <div class="sidebar-bottom">
            @php $authUser = Auth::user(); @endphp
            {{-- <a href="{{ route('profile') }}" class="user-link"> --}}
            @php
                $avatarUrl = $userAvatar;
            @endphp
            <div class="profile">
                <img src="{{ $avatarUrl }}" alt="User" id="sidebarProfileImage">
                <div class="profile-text">
                    <div class="user-meta">
                        <div class="user-name">{{ $authUser ? $authUser->name : 'Guest' }}</div>
                        <div class="user-role">{{ $authUser ? ucfirst($authUser->role) : 'Guest' }}</div>
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
{{-- <link rel="stylesheet" href="{{ asset('css/ManagementSystem/dashboard.css') }}" /> --}}
<link rel="stylesheet" href="{{ asset('css/ManagementSystem/aside.css') }}" />

<style>
    .global-toast-container {
        position: fixed;
        top: 1.5rem;
        right: 1.5rem;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .global-toast {
        background: #ffffff;
        border-radius: 12px;
        padding: 16px;
        min-width: 340px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        /* Softer shadow like the image */
        border: 1px solid #f0f0f0;
        position: relative;
        cursor: pointer;
        animation: toast-slide-in 0.3s ease-out;
    }

    .toast-content-wrapper {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .toast-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
    }

    .toast-text-side {
        display: flex;
        flex-direction: column;
    }

    .toast-title {
        font-size: 15px;
        color: #1a1a1a;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .toast-date {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 12px;
    }

    /* Action Buttons */
    .toast-actions-mini {
        display: flex;
        gap: 8px;
    }

    .btn-toast-view {
        background: #5d2df5;
        /* Purple from your first image */
        color: white;
        border: none;
        padding: 5px 18px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
    }

    .btn-toast-dismiss {
        background: white;
        color: #1a1a1a;
        border: 1px solid #d1d5db;
        padding: 5px 18px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
    }

    .global-toast-close {
        position: absolute;
        top: 10px;
        right: 12px;
        border: none;
        background: none;
        font-size: 20px;
        color: #9ca3af;
        cursor: pointer;
    }

    @keyframes toast-slide-in {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const appShell = document.getElementById('appShell');
        const collapseHandle = document.getElementById('collapseHandle');
        const settingsBtn = document.getElementById('settingsBtn');
        const settingsBox = document.getElementById('settingsBox');
        const navButtons = document.querySelectorAll('.nav-btn');

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
            }, 32000);
        }

        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, m => map[m]);
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

                    // Update nav badge if present
                    // document.querySelectorAll('.nav-label').forEach(el => {
                    //     if (el.textContent.trim().toLowerCase() === 'notification') {
                    //         let badge = el.nextElementSibling;
                    //         if (!badge || !badge.classList.contains('badge-noti-count')) {
                    //             badge = document.createElement('span');
                    //             badge.className = 'badge-noti-count';
                    //             badge.style.cssText = 'background:#ff5252;color:#fff;border-radius:12px;font-size:9px; padding:2px 6px; position:relative;left:-100px; top:-10px; positoin:sticky;';
                    //             el.parentElement.appendChild(badge);
                    //         }
                    //         badge.textContent = data.unread_count > 0 ? data.unread_count : '';
                    //     }
                    // });

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
