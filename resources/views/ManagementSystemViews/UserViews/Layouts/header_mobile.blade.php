@php
    use Illuminate\Support\Facades\Storage;
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
@endphp

<div class="mobile">
<header class="cart-boxM">

    {{-- Hamburger — opens a small menu listing page name + link,
         defined right here instead of toggling the desktop sidebar. --}}
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
        <span class="cart-count" id="cartCount">{{ (int) ($cartCount ?? 0) }}</span>
    </a>
</header>

{{-- The menu itself — page name + link pairs, edit this list directly
     to add/remove/rename pages. Kept in sync with the same routes used
     in the sidebar. --}}
<nav class="mobile-menu-panel" id="mobileMenuPanel">
    <a href="{{ route('user.index') }}" class="mobile-menu-link {{ request()->is('/') ? 'active' : '' }}">
        <i class="bi bi-house-door"></i> Dashboard
    </a>
    <a href="{{ route('user.pos.cart') }}" class="mobile-menu-link {{ request()->is('pos-system/cart') ? 'active' : '' }}">
        <i class="bi bi-cart3"></i> Cart
    </a>
    <a href="{{ url('/pos-system/favorites') }}" class="mobile-menu-link {{ request()->is('pos-system/favorites') ? 'active' : '' }}">
        <i class="bi bi-heart"></i> Favorite
    </a>
    <a href="{{ route('user.pos.order.history') }}" class="mobile-menu-link {{ request()->is('pos-system/order-history') ? 'active' : '' }}">
        <i class="bi bi-receipt"></i> Order History
    </a>
    <a href="{{ route('user.notifications') }}" class="mobile-menu-link {{ request()->is('pos-system/notifications') ? 'active' : '' }}">
        <i class="bi bi-bell"></i> Notification
    </a>
    <a href="{{ route('profile') }}" class="mobile-menu-link">
        <i class="bi bi-person"></i> Edit Profile
    </a>
    <a href="/logout" class="mobile-menu-link">
        <i class="bi bi-box-arrow-right"></i> Log out
    </a>
</nav>
</div>

{{-- Backdrop behind the menu; tapping it closes the menu --}}
<div class="mobile-menu-backdrop" id="mobileMenuBackdrop" onclick="toggleMobileMenu()"></div>

<link rel="stylesheet" href="{{ asset('/css/views/POSViews/POSUserViews/Layout/header_mobile.css') }}">

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

    // Tapping any link inside closes the menu before navigation.
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
