
    <div class="sidebar-wrap">
      <aside class="sidebar">
        <div class="sidebar-top">
          <div class="brand">
            <div class="brand-logo"></div>
            <div class="brand-text">Orange</div>
          </div>

          <div class="search-box">
            <span class="search-icon">⌕</span>
            <input type="text" placeholder="Search products">
          </div>

          <nav class="nav-list">
            <a href="/admin">
              <button class="nav-btn active" type="button">
                <span class="nav-icon">⌗</span>
                <span class="nav-label">Dashboard</span>
              </button>
            </a>

            <a href="/users">
              <button class="nav-btn" type="button">
                <span class="nav-icon">👤</span>
                <span class="nav-label">Users</span>
              </button>
            </a>
            <a href="/pos/interface">
                <button class="nav-btn" type="button">
                  <span class="nav-icon">🛒</span>
                  <span class="nav-label">Pos System</span>
                </button>
            </a>
            <a href="/companies">
    <button class="nav-btn" type="button">
        <span class="nav-icon">🏢</span>
        <span class="nav-label">Companies</span>
    </button>
</a>

<a href="/companies/select">
    <button class="nav-btn" type="button">
        <span class="nav-icon">🔄</span>
        <span class="nav-label">Select Company</span>
    </button>
</a>
            {{-- <a href="/favorites">
                <button class="nav-btn" type="button">
                  <span class="nav-icon">☆</span>
                  <span class="nav-label">Favorite</span>
                </button>
            </a> --}}

            {{-- <button class="nav-btn" type="button">
              <span class="nav-icon">📋</span>
              <span class="nav-label">Order History</span>
            </button> --}}

            <a href="/admin/notification">
              <button class="nav-btn" type="button">
                <span class="nav-icon">◔</span>
                <span class="nav-label">Notification</span>
              </button>
            </a>
          </nav>
        </div>
{{-- <a href="/admin/orders">
              <button class="nav-btn" type="button">
                <span class="nav-icon">◔</span>
                <span class="nav-label">Approval order</span>
              </button>
            </a> --}}
        <div class="sidebar-bottom">
                @php $authUser = Auth::user(); @endphp
                 {{-- <a href="{{ route('profile') }}" class="user-link"> --}}
          <div class="profile">
            <img src="https://i.pravatar.cc/80?img=12" alt="User">
            <div class="profile-text">
              <div class="user-meta">
          <div class="user-name">{{ $authUser ? $authUser->name : 'Guest' }}</div>
          <div class="user-role">{{ $authUser ? ucfirst($authUser->role) : 'Guest' }}</div>
        </div>
            </div>
          </div>

          <div class="settings-box" id="settingsBox">
            <button class="settings-btn" id="settingsBtn" type="button">
              <span class="nav-icon">⚙</span>
              <span class="nav-label">Settings</span>
              <span class="settings-arrow">⌄</span>
            </button>

            <div class="settings-menu">
              <a href="#" class="settings-link">Edit Profile</a>
              <a href="#" class="settings-link">Change new password</a>
              <a href="#" class="settings-link">Policy</a>
            </div>
          </div>

          <button class="logout-btn" type="button">
            <span class="nav-icon">⎋</span>
            <a class="dd-item danger" href="/logout">
            <span class="nav-label">Log out</span>
            </a>
          </button>

        </div>
      </aside>

      <button class="collapse-handle" id="collapseHandle" type="button">
        <span>‹</span>
      </button>
    </div>
  <link rel="stylesheet" href="{{ asset('css/ManagementSystem/dashboard.css') }}" />
</script>
  <script>
    const appShell = document.getElementById('appShell');
    const collapseHandle = document.getElementById('collapseHandle');
    const settingsBtn = document.getElementById('settingsBtn');
    const settingsBox = document.getElementById('settingsBox');
    const navButtons = document.querySelectorAll('.nav-btn');

    collapseHandle.addEventListener('click', () => {
      appShell.classList.toggle('collapsed');

      if (appShell.classList.contains('collapsed')) {
        settingsBox.classList.remove('open');
      }
    });

    settingsBtn.addEventListener('click', () => {
      if (appShell.classList.contains('collapsed')) return;
      settingsBox.classList.toggle('open');
    });

    navButtons.forEach((btn) => {
      btn.addEventListener('click', () => {
        navButtons.forEach((item) => item.classList.remove('active'));
        btn.classList.add('active');
      });
    });
  </script>
