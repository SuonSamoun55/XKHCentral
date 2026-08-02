@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\Route;
    use App\Models\ManagementSystem\Company;

    $authUser = Auth::user();

    // 1. Fetch Company Logic (Same as User)
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

    $navItems = [
        [
            'name' => 'Dashboard',
            'url' => '/admin',
            'match' => ['admin'],
            'icon' => '/images/aside/SidbarDaskboards.png',
            'icon_active' => '/images/aside/UserDaskboardActive.png',
        ],
        [
            'name' => 'Users',
            'url' => '/users',
            'match' => ['users', 'users/*'],
            'icon' => '/images/management/management_user.png',
            'icon_active' => '/images/management/management_user_active.png',
        ],
        [
            'name' => 'Pos System',
            'url' => '/pos/interface',
            'match' => ['pos/interface', 'pos/*'],
            'icon' => '/images/management/managemetn_POS.png',
            'icon_active' => '/images/management/management_POS_active.png',
        ],
        [
            'name' => 'Companies',
            'url' => '/companies',
            'match' => ['companies', 'companies/*'],
            'icon' => 'images/management/management_company.png',
            'icon_active' => 'images/management/management_company_active.png',
        ],

    ];
@endphp
{{--
    FIX: this wrapper now carries BOTH class="app-shell" and id="appShell".
    The CSS rules (.app-shell.collapsed ..., .app-shell.settings-active ...)
    require the class "app-shell" on whichever element gets the "collapsed"
    or "settings-active" state toggled onto it by the JS below. Previously
    this div only had class="sidebar-wrap", so those rules never matched
    anything, even though the JS was toggling the classes correctly.
--}}
<div class="sidebar-wrap app-shell" id="appShell">
    {{-- id="appSidebar" added so the mobile hamburger (header_mobile.blade.php)
         has a stable, unambiguous target instead of guessing by tag/class. --}}
    <aside class="sidebar" id="appSidebar">
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
                    <a href="{{ route('admin.profile') }}" class="settings-link nav-link-mobile-close">Edit Profile</a>
                    <a href="{{ route('admin.password.change') }}" class="settings-link nav-link-mobile-close">Change new password</a>
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

<link rel="stylesheet" href="{{ asset('/css/views/ManagementSystemViews/UserViews/Layouts/aside.css') }}">

<script>
(function () {
    const appShell = document.getElementById('appShell');

    // FIX: idempotency guard. If this partial/script ever gets injected or
    // re-run more than once on the same page (common with Livewire/Turbo/AJAX
    // navigation, which is likely given this app sets data-sidebar-ready),
    // this stops a second copy of the script from attaching a second set of
    // click listeners to the same buttons. Without this guard, one physical
    // click can fire the toggle logic twice, leaving classes like "open" and
    // "settings-active" out of sync with each other — which is exactly the
    // symptom you were seeing.
    if (!appShell || appShell.dataset.sidebarJsBound === 'true') {
        return;
    }
    appShell.dataset.sidebarJsBound = 'true';

    function init() {
        const collapseHandle = document.getElementById('collapseHandle');
        const settingsBtn = document.getElementById('settingsBtn');
        const settingsBox = document.getElementById('settingsBox');
        const appSidebar = document.getElementById('appSidebar');

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

            // Force a known-good starting state via inline styles, so the
            // menu's visibility no longer depends on any external CSS rule
            // actually being present/loaded/unconflicted.
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
                    // Expand to the menu's actual content height (not a
                    // guessed fixed number), then scroll it into view once
                    // the expand transition finishes.
                    const targetHeight = settingsMenu.scrollHeight;
                    settingsMenu.style.maxHeight = targetHeight + 'px';

                    const scrollAfterExpand = (evt) => {
                        if (evt.propertyName !== 'max-height') return;
                        settingsMenu.removeEventListener('transitionend', scrollAfterExpand);
                        settingsMenu.scrollIntoView({ block: 'end', behavior: 'smooth' });
                    };
                    settingsMenu.addEventListener('transitionend', scrollAfterExpand);
                } else {
                    settingsMenu.style.maxHeight = '0px';
                }
            });
        }

        // Tapping any nav/settings/logout link inside the sidebar while
        // it's open as a mobile overlay should close the menu, same as
        // tapping the backdrop. No-ops harmlessly on desktop since the
        // sidebar there isn't in the mobile-open overlay state.
        document.querySelectorAll('.nav-link-mobile-close').forEach((link) => {
            link.addEventListener('click', () => {
                if (appSidebar?.classList.contains('mobile-open') && typeof toggleMobileSidebar === 'function') {
                    toggleMobileSidebar();
                }
            });
        });

        appShell.setAttribute('data-sidebar-ready', 'true');
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>