@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\Auth;
    use App\Models\ManagementSystem\Company;

    $company = null;

    if (session('selected_company_id')) {
        $company = Company::find(session('selected_company_id'));
    }

    if (!$company) {
        $company = Company::first();
    }

    $companyName = $company->display_name ?? $company->name ?? 'Company';
    $companyLogoUrl = asset('images/default-company.png');

    if ($company && !empty($company->logo)) {
        if (preg_match('/^https?:\/\//i', $company->logo)) {
            $companyLogoUrl = $company->logo;
        } else {
            $companyLogoUrl = Storage::url($company->logo);
        }
    }

    $authUser = Auth::user();
    $isAdmin = strtolower($authUser->role ?? '') === 'admin';

    $mobileNavItems = [
        [
            'name' => 'Dashboard',
            'route' => route('user.index'),
            'match' => ['/'],
            'icon' => 'images/aside/SidbarDaskboards.png',
            'icon_active' => 'images/aside/UserDaskboardActive.png',
        ],
        [
            'name' => 'Cart',
            'route' => route('user.pos.cart'),
            'match' => ['pos-system/cart'],
            'icon' => 'images/aside/SidebarCarts.png',
            'icon_active' => 'images/aside/UserCartActive.png',
        ],
        [
            'name' => 'Favorite',
            'route' => url('/pos-system/favorites'),
            'match' => ['pos-system/favorites'],
            'icon' => 'images/aside/SidebarFavorites.png',
            'icon_active' => 'images/aside/FavoriteActive.png',
        ],
        [
            'name' => 'Order History',
            'route' => route('user.pos.order.history'),
            'match' => ['pos-system/order-history'],
            'icon' => '/images/AdminPOS/Admin_POS_Approval_Order.png',
            'icon_active' => '/images/AdminPOS/Admin_POS_Approval_Order_active.png',
        ],
        [
            'name' => 'Notification',
            'route' => route('user.notifications'),
            'match' => ['pos-system/notifications'],
            'icon' => 'images/aside/SidebarNotifications.png',
            'icon_active' => 'images/aside/NotificationActive.png',
        ],
        [
            'name' => 'Open Admin',
            'route' => url('/admin'),
            'match' => ['admin', 'admin/*'],
            'icon' => '/images/aside/open admin (2).png',
            'icon_active' => '/images/aside/open admin (2).png',
            'admin_only' => true,
        ],
        [
            'name' => 'Log out',
            'route' => '/logout',
            'match' => [],
            'icon' => 'images/aside/logout.png',
            'icon_active' => 'images/aside/logout.png',
        ],
    ];
@endphp

<div class="mobile">
<header class="cart-boxM">

    <button type="button" class="menu-btn" onclick="toggleMobileMenu()" aria-label="Open menu" aria-controls="mobileMenuPanel" aria-expanded="false" aria-haspopup="true">
        <i class="bi bi-list"></i>
    </button>

    <div class="logo-wrap">
        <img src="{{ $companyLogoUrl }}"
             alt="{{ $companyName }} Logo"
             class="logo"
             onerror="this.onerror=null;this.src='{{ asset('images/default-company.png') }}';">
    </div>

    <a href="{{ route('user.pos.cart') }}" class="cart">
        <img src="{{ asset('images/pos/Button - Square.png') }}" alt="Cart" class="cart-icon">
        <span class="cart-count {{ (int) ($cartCount ?? 0) > 0 ? '' : 'is-empty' }}" id="cartCount">{{ (int) ($cartCount ?? 0) }}</span>
    </a>
</header>

<nav class="mobile-menu-panel" id="mobileMenuPanel">
    @foreach ($mobileNavItems as $item)
        @if (!empty($item['admin_only']) && !$isAdmin)
            @continue
        @endif

        @php
            $isActive = false;

            if ($item['name'] === 'Products') {
                $isActive = request()->routeIs('user.posinterface');
            } else {
                foreach ($item['match'] as $pattern) {
                    if (request()->is($pattern)) {
                        $isActive = true;
                        break;
                    }
                }
            }

            $iconToShow = $isActive && !empty($item['icon_active']) ? $item['icon_active'] : $item['icon'];
        @endphp
        <a href="{{ $item['route'] }}" class="mobile-menu-link {{ $isActive ? 'active' : '' }}">
            @if ($item['name'] === 'Notification')
                <span class="nav-icon-notification">
                    <img src="{{ asset($iconToShow) }}" alt="{{ $item['name'] }} Icon" class="mobile-menu-icon">
                    <span id="mobileUnreadNotiDot" class="noti-dot" aria-hidden="true"></span>
                </span>
            @elseif ($item['name'] === 'Cart')
                <span class="nav-icon-notification">
                    <img src="{{ asset($iconToShow) }}" alt="{{ $item['name'] }} Icon" class="mobile-menu-icon">
                    <span id="mobileCartDot" class="noti-dot {{ (int) ($cartCount ?? 0) > 0 ? 'show' : '' }}" aria-hidden="true"></span>
                </span>
            @else
                <img src="{{ asset($iconToShow) }}" alt="{{ $item['name'] }} Icon" class="mobile-menu-icon">
            @endif
            {{ $item['name'] }}
        </a>
    @endforeach
</nav>
</div>

<div class="mobile-menu-backdrop" id="mobileMenuBackdrop" onclick="toggleMobileMenu()"></div>


<script>
    function toggleMobileMenu() {
        const panel = document.getElementById('mobileMenuPanel');
        const backdrop = document.getElementById('mobileMenuBackdrop');
        const btn = document.querySelector('.menu-btn');
        if (!panel) return;

        const isOpen = panel.classList.toggle('open');
        document.body.classList.toggle('menu-open', isOpen);
        if (btn) btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.mobile-menu-link').forEach((link) => {
            link.addEventListener('click', () => {
                document.getElementById('mobileMenuPanel')?.classList.remove('open');
                document.getElementById('mobileMenuBackdrop')?.classList.remove('show');
                document.body.classList.remove('menu-open');
            });
        });
    });
</script>
