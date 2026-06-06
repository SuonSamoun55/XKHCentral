{{-- <nav class="pos-navbar">
  <div class="brand">
    <i class="bi bi-shop"></i>
    <span>POS PORTAL</span>
  </div>

  <div class="navbar-right">
    @php $authUser = Auth::user(); @endphp

    <!-- User -->
    <div class="user-menu">
      <button class="user-chip" type="button" id="userMenuBtn">
        <div class="user-avatar">
          {{ $authUser ? strtoupper(substr($authUser->name,0,1)) : 'G' }}
        </div>
        <div class="user-meta">
          <div class="user-name">{{ $authUser ? $authUser->name : 'Guest' }}</div>
          <div class="user-role">{{ $authUser ? ucfirst($authUser->role) : 'Guest' }}</div>
        </div>
        <i class="bi bi-chevron-down chevron"></i>
      </button>

      <!-- Dropdown (optional) -->
      <div class="user-dropdown" id="userDropdown">
        <div class="dd-item">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </div>
        <a class="dd-item danger" href="/logout">
          <i class="bi bi-box-arrow-right"></i>
          <span>Logout</span>
        </a>
      </div>
    </div>

    <!-- Direct logout button (if you prefer without dropdown, keep this) -->
    <a href="/logout" class="logout-btn">
      <i class="bi bi-box-arrow-right"></i>
      <span class="logout-text">Logout</span>
    </a>
  </div>
</nav>
<link rel="stylesheet" href="{{ asset('css/views/ManagementSystemViews/AdminViews/Layouts/navbar.css') }}"> --}}
