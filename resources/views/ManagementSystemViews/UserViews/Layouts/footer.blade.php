 <div class="mobile-bottom-nav">

            {{-- HOME --}}
            <a href="{{ route('user.posinterface') }}"
                class="{{ request()->routeIs('user.posinterface') ? 'active' : '' }}">
                <i class="bi bi-house-door-fill"></i>
                <span>home</span>
            </a>

            {{-- PRODUCTS (categories + category products) --}}
            <a href="{{ route('user.pos.categories') }}"
                class="{{ request()->routeIs('user.pos.categories') || request()->routeIs('user.pos.categories.products') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span>products</span>
            </a>

            {{-- WISHLIST --}}
            <a href="{{ route('user.pos.favorites') }}"
                class="{{ request()->routeIs('user.pos.favorites') ? 'active' : '' }}">
                <i class="bi bi-heart"></i>
                <span>wishlist</span>
            </a>

{{-- USER --}}
<a href="{{ route('profile_mobile') }}"
   class="{{ request()->routeIs('profile_mobile') ? 'active' : '' }}">
    <i class="bi bi-person"></i>
    <span>user</span>
</a>
        </div>
        <style>
 .mobile-bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 70px;
        background: #fff;
        display: flex;
        justify-content: space-around;
        align-items: center;
        border-top-left-radius: 18px;
        border-top-right-radius: 18px;
        box-shadow: 0 -6px 20px rgba(0, 0, 0, 0.08);
        z-index: 999;
    }

    .mobile-bottom-nav a {
        display: flex;
        flex-direction: column;
        align-items: center;
        font-size: 11px;
        color: #94a3b8;
        text-decoration: none;
    }

    .mobile-bottom-nav a i {
        font-size: 20px;
    }

    .mobile-bottom-nav a.active {
        color: var(--primary);
    }
</style>