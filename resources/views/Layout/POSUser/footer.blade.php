 <div class="mobile-bottom-nav">

     {{-- DASHBOARD --}}
     @php $isActive = request()->routeIs('user.index'); @endphp
     <a href="{{ route('user.index') }}" class="{{ $isActive ? 'active' : '' }}">
         <img src="{{ asset($isActive ? 'images/aside/UserDaskboardActive.png' : 'images/aside/SidbarDaskboards.png') }}" alt=""
             class="nav-icon-img">
         <span>Dashboard</span>
     </a>

     {{-- PRODUCTS (categories + category products) --}}
     @php $isActive = request()->routeIs('user.posinterface'); @endphp
     <a href="{{ route('user.posinterface') }}" class="{{ $isActive ? 'active' : '' }}">
         <img src="{{ asset($isActive ? 'images/aside/ProductActive.png' : 'images/aside/Product.png') }}" alt=""
             class="nav-icon-img">
         <span>Products</span>
     </a>
     {{-- WISHLIST --}}
     @php $isActive = request()->routeIs('user.pos.favorites'); @endphp
     <a href="{{ route('user.pos.favorites') }}" class="{{ $isActive ? 'active' : '' }}">
         <img src="{{ asset($isActive ? 'images/aside/FavoriteActive.png' : 'images/aside/SidebarFavorites.png') }}"
             alt="" class="nav-icon-img">
         <span>Favorite</span>
     </a>
     @php $isActive = request()->routeIs('profile'); @endphp
     <a href="{{ route('profile') }}" class="{{ $isActive ? 'active' : '' }}">
         <img src="{{ asset($isActive ? 'images/management/management_user_active.png' : 'images/management/management_user.png') }}"
             alt="" class="nav-icon-img">
         <span>My Profile</span>
     </a>
 </div>
