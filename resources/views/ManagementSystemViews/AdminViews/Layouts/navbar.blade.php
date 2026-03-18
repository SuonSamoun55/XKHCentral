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
<style>
    :root{
  --primary-color: #ff85a2;
  --primary-dark: #e06b85;
  --text-color: #243041;
  --muted: rgba(36,48,65,.65);
  --white: #fff;
  --border: rgba(15, 23, 42, 0.08);
  --shadow: 0 10px 25px rgba(2, 6, 23, 0.08);
  --shadow2: 0 16px 34px rgba(2, 6, 23, 0.12);
}

/* Navbar */
.pos-navbar{
  position: sticky;
  top: 0;
  z-index: 1000;

  height: 72px;
  padding: 0 22px;

  display: flex;
  align-items: center;
  justify-content: space-between;

  background: rgba(255,255,255,0.88);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);

  border-bottom: 1px solid var(--border);
  box-shadow: 0 6px 18px rgba(2,6,23,0.05);
}

/* Brand */
.brand{
  display: flex;
  align-items: center;
  gap: 10px;

  font-weight: 800;
  letter-spacing: .3px;
  color: var(--primary-color);
  font-size: 1.05rem;
}

.brand i{
  width: 38px;
  height: 38px;
  border-radius: 12px;

  display: grid;
  place-items: center;

  background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
  color: #fff;
  box-shadow: 0 10px 20px rgba(255,133,162,.25);
}

/* Right section */
.navbar-right{
  display: flex;
  align-items: center;
  gap: 12px;
}

/* User menu */
.user-menu{
  position: relative;
}

.user-chip{
  display: flex;
  align-items: center;
  gap: 10px;

  border: 1px solid var(--border);
  background: #fff;

  border-radius: 14px;
  padding: 8px 10px;
  box-shadow: 0 8px 18px rgba(2,6,23,0.06);

  cursor: pointer;
  transition: transform .2s ease, box-shadow .2s ease;
}

.user-chip:hover{
  transform: translateY(-1px);
  box-shadow: var(--shadow);
}

.user-avatar{
  width: 38px;
  height: 38px;
  border-radius: 50%;

  display: grid;
  place-items: center;

  font-weight: 800;
  font-size: 0.9rem;
  color: #fff;

  background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
}

.user-meta{
  line-height: 1.05;
  text-align: left;
}

.user-name{
  font-weight: 700;
  font-size: .92rem;
  color: var(--text-color);
  max-width: 160px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.user-role{
  font-size: .78rem;
  color: var(--muted);
  font-weight: 600;
}

.chevron{
  color: rgba(36,48,65,.55);
  font-size: .9rem;
  margin-left: 2px;
}

/* Dropdown */
.user-dropdown{
  position: absolute;
  right: 0;
  top: calc(100% + 10px);
  width: 200px;

  background: #fff;
  border: 1px solid var(--border);
  border-radius: 14px;
  box-shadow: var(--shadow2);

  padding: 8px;
  display: none;
}

.user-dropdown.show{
  display: block;
  animation: ddIn .18s ease-out;
}

@keyframes ddIn{
  from { transform: translateY(-6px); opacity: 0; }
  to   { transform: translateY(0); opacity: 1; }
}

.dd-item{
  display: flex;
  align-items: center;
  gap: 10px;

  padding: 10px 10px;
  border-radius: 12px;

  color: var(--text-color);
  font-weight: 700;
  text-decoration: none;

  cursor: pointer;
  transition: background .18s ease;
}

.dd-item:hover{
  background: rgba(255,133,162,0.10);
}

.dd-item i{
  font-size: 1.05rem;
}

.dd-item.danger{
  color: #b42318;
}

.dd-item.danger:hover{
  background: rgba(180,35,24,0.08);
}

/* Logout button */
.logout-btn{
  display: inline-flex;
  align-items: center;
  gap: 8px;

  padding: 10px 14px;
  border-radius: 12px;

  color: #fff;
  text-decoration: none;
  font-weight: 800;
  font-size: .88rem;

  background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
  box-shadow: 0 12px 22px rgba(255,133,162,.25);
  transition: transform .2s ease, opacity .2s ease;
}

.logout-btn:hover{
  transform: translateY(-1px);
  opacity: .95;
}

/* Responsive */
@media (max-width: 768px){
  .pos-navbar{ padding: 0 14px; }
  .user-name{ max-width: 110px; }
}

@media (max-width: 520px){
  .user-meta{ display: none; }
  .chevron{ display: none; }
  .logout-text{ display: none; }
  .logout-btn{ padding: 10px 12px; }
}
</style> --}}
