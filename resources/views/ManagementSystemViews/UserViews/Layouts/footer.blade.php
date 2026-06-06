 <div class="mobile-bottom-nav">

            {{-- HOME --}}
            <a href="{{ route('user.index') }}"
                class="{{ request()->routeIs('user.index') ? 'active' : '' }}">
                <i class="bi bi-house-door-fill"></i>
                <span>home</span>
            </a>

            {{-- PRODUCTS (categories + category products) --}}
            <a href="{{ route('user.posinterface') }}"
                class="{{ request()->routeIs('user.posinterface') ? 'active' : '' }}">
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
<a href="{{ route('profile') }}"
   class="{{ request()->routeIs('profile') ? 'active' : '' }}">
    <i class="bi bi-person"></i>
    <span>user</span>
</a>
        </div>
        <link rel="stylesheet" href="{{ asset('css/views/ManagementSystemViews/UserViews/Layouts/footer.css') }}">
