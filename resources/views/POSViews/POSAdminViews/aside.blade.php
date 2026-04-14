@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;
    use App\Models\MagamentSystemModel\Company;

    $authUser = Auth::user();

    /*
    |--------------------------------------------------------------------------
    | COMPANY LOAD
    |--------------------------------------------------------------------------
    */
    $company = null;

    if (session('selected_company_id')) {
        $company = Company::find(session('selected_company_id'));
    }

    if (!$company) {
        $company = Company::first();
    }

    /*
    |--------------------------------------------------------------------------
    | USER IMAGE RESOLVER
    |--------------------------------------------------------------------------
    */
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
    | COMPANY NAME
    |--------------------------------------------------------------------------
    */
    $companyName = $company->display_name ?? $company->name ?? 'Orange';

    /*
    |--------------------------------------------------------------------------
    | COMPANY LOGO
    |--------------------------------------------------------------------------
    */
    $companyLogoUrl = asset('images/default-company.png');

    if ($company && !empty($company->logo)) {
        if (preg_match('/^https?:\/\//i', $company->logo)) {
            $companyLogoUrl = $company->logo;
        } else {
            $companyLogoUrl = Storage::url($company->logo);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SIDEBAR MENUS
    |--------------------------------------------------------------------------
    */
    $menus = [
        [
            'url' => '/pos/interface',
            'name' => 'Pos System',
            'icon' => asset('images/aside/Cart.png'),
            'active' => ['pos/interface', 'pos/*'],
        ],
        [
            'url' => '/admin/orders',
            'name' => 'Order',
            'icon' => asset('images/aside/user.png'),
            'active' => ['admin/orders', 'admin/orders/*'],
        ],
        [
            'url' => '/store-management',
            'name' => 'Store Management',
            'icon' => asset('images/aside/company.png'),
            'active' => ['store-management', 'store-management/*'],
        ],
        [
            'url' => '/discounts',
            'name' => 'Discount',
            'icon' => null,
            'active' => ['discounts', 'discounts/*'],
        ],

        [
            'url' => '/admin/notification',
            'name' => 'Notification',
            'icon' => asset('images/aside/company.png'),
            'active' => ['admin/notification', 'admin/notification/*'],
        ],
    ];
@endphp

<link rel="stylesheet" href="{{ asset('css/ManagementSystem/adminSidbar.css') }}">

<div class="sidebar-wrap" id="appShell">
    <aside class="sidebar">
        <div class="sidebar-top">
            <div class="brand">
                <div class="brand-logo company-logo-box">
                    <img
                        src="{{ $companyLogoUrl }}"
                        alt="Company Logo"
                        onerror="this.onerror=null;this.src='{{ asset('images/default-company.png') }}';"
                    >
                </div>
                {{--    --}}
            </div>

            <nav class="nav-list">
                @foreach ($menus as $menu)
                    @php
                        $isActive = false;

                        if (!empty($menu['active'])) {
                            foreach ($menu['active'] as $pattern) {
                                if (request()->is($pattern)) {
                                    $isActive = true;
                                    break;
                                }
                            }
                        }
                    @endphp

                    <a href="{{ $menu['url'] }}" class="nav-link-wrap">
                        <div class="nav-btn {{ $isActive ? 'active' : '' }}">
                            <span class="nav-icon">
                                @if(!empty($menu['icon']))
                                    <img src="{{ $menu['icon'] }}" alt="{{ $menu['name'] }} Icon">
                                @else
                                    <i class="bi bi-percent" style="font-size:18px;"></i>
                                @endif
                            </span>
                            <span class="nav-label">{{ $menu['name'] }}</span>
                        </div>
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="sidebar-bottom">
            <div class="profile">
                <img
                    src="{{ $userAvatar }}"
                    alt="User"
                    onerror="this.onerror=null;this.src='{{ asset('images/default-user.png') }}';"
                >

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
                    <a href="{{ route('admin.password.change') }}" class="settings-link">Change Password</a>
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
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const appShell = document.getElementById("appShell");
    const collapseHandle = document.getElementById("collapseHandle");
    const settingsBtn = document.getElementById("settingsBtn");
    const settingsBox = document.getElementById("settingsBox");

    if (collapseHandle && appShell) {
        collapseHandle.addEventListener("click", function () {
            appShell.classList.toggle("collapsed");

            if (appShell.classList.contains("collapsed") && settingsBox) {
                settingsBox.classList.remove("open");
            }
        });
    }

    if (settingsBtn && settingsBox && appShell) {
        settingsBtn.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();

            if (appShell.classList.contains("collapsed")) return;

            settingsBox.classList.toggle("open");
        });
    }

    document.addEventListener("click", function (e) {
        if (!settingsBox || !settingsBtn) return;

        if (!settingsBox.contains(e.target) && !settingsBtn.contains(e.target)) {
            settingsBox.classList.remove("open");
        }
    });
});
</script>
{{-- <script src="{{ asset('JS/AdminJS/SideBarjs/sidebar.js') }}"></script> --}}
