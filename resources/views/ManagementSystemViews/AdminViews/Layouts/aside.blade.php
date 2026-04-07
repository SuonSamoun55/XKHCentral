@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;
    use App\Models\MagamentSystemModel\Company;

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

    $companyName = $company->display_name ?? $company->name ?? 'Orange';

    $companyLogoUrl = asset('images/default-company.png');

    if ($company && !empty($company->logo)) {
        if (preg_match('/^https?:\/\//i', $company->logo)) {
            $companyLogoUrl = $company->logo;
        } else {
            $companyLogoUrl = Storage::url($company->logo);
        }
    }
@endphp

<link rel="stylesheet" href="{{ asset('css/ManagementSystem/adminSidbar.css') }}">

<div class="sidebar-wrap" id="appShell">
    <aside class="sidebar">

        <div class="sidebar-top">
            <div class="brand">
                <div class="company-logo-box">
                    <img src="{{ $companyLogoUrl }}"
                         alt="Company Logo"
                         onerror="this.onerror=null;this.src='{{ asset('images/default-company.png') }}';">
                </div>
            </div>

            <nav class="nav-list">
                <a href="/admin" class="nav-link-wrap">
                    <div class="nav-btn {{ request()->is('admin') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <img src="{{ asset('images/aside/dashboard.png') }}" alt="Dashboard Icon">
                        </span>
                        <span class="nav-label">Dashboard</span>
                    </div>
                </a>

                <a href="/users" class="nav-link-wrap">
                    <div class="nav-btn {{ request()->is('users') || request()->is('users/*') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <img src="{{ asset('images/aside/user.png') }}" alt="Users Icon">
                        </span>
                        <span class="nav-label">Users</span>
                    </div>
                </a>

                <a href="/pos/interface" class="nav-link-wrap">
                    <div class="nav-btn {{ request()->is('pos/interface') || request()->is('pos/*') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <img src="{{ asset('images/aside/Cart.png') }}" alt="POS Icon">
                        </span>
                        <span class="nav-label">Pos System</span>
                    </div>
                </a>

                <a href="/companies" class="nav-link-wrap">
                    <div class="nav-btn {{ (request()->is('companies') || request()->is('companies/*')) && !request()->is('companies/select') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <img src="{{ asset('images/aside/company.png') }}" alt="Companies Icon">
                        </span>
                        <span class="nav-label">Companies</span>
                    </div>
                </a>

                <a href="/companies/select" class="nav-link-wrap">
                    <div class="nav-btn {{ request()->is('companies/select') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <img src="{{ asset('images/aside/switch.png') }}" alt="Select Company Icon">
                        </span>
                        <span class="nav-label">Select Company</span>
                    </div>
                </a>

                <a href="/admin/notification" class="nav-link-wrap">
                    <div class="nav-btn {{ request()->is('admin/notification') || request()->is('admin/notification/*') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <img src="{{ asset('images/aside/Notification.png') }}" alt="Notification Icon">
                        </span>
                        <span class="nav-label">Notification</span>
                    </div>
                </a>
            </nav>
        </div>

        <div class="sidebar-bottom">
            <div class="profile">
                <img src="{{ $userAvatar }}"
                     alt="User"
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
                        <img src="{{ asset('images/aside/setting.png') }}" alt="Settings Icon">
                    </span>
                    <span class="nav-label">Settings</span>
                    <span class="settings-arrow">⌄</span>
                </button>

                <div class="settings-menu">
                    <a href="{{ route('profile') }}" class="settings-link">Edit Profile</a>
                    <a href="#" class="settings-link">Change Password</a>
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

<script src="{{ asset('JS/AdminJS/SideBarjs/sidebar.js') }}"></script>
