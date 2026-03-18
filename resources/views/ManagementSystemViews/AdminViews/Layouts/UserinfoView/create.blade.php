@include('ManagementSystemViews.AdminViews.Layouts.navbar')

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Create User</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  <style>
    :root{
      --primary: #ff6f91;       /* soft pink */
      --primary-dark: #e85a7a;
      --ink: #0f172a;           /* strong text */
      --muted: rgba(15,23,42,.62);
      --bg: #eef2f7;
      --card: #ffffff;
      --border: rgba(15,23,42,.10);
      --shadow: 0 16px 40px rgba(2,6,23,.10);
    }

    body{
      background: var(--bg);
      font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      margin: 0;
    }

    /* ===== Layout ===== */
    .app-layout{
      max-width: 1200px;
      margin: 0 auto;
      padding: 18px 16px;
      display: flex;
      gap: 18px;
      align-items: flex-start;
    }

    .app-content{
      flex: 1;
      min-width: 0;
    }

    /* Ensure your aside has class="sidebar" */
    .sidebar{
      position: sticky;
      top: 90px;                 /* below navbar */
      height: calc(100vh - 110px);
      overflow: auto;
      flex-shrink: 0;
    }

    /* ===== Page Header ===== */
    .page-top{
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap: 12px;
      margin-bottom: 14px;
    }

    .page-title{
      display:flex;
      align-items:center;
      gap: 12px;
      margin: 0;
      font-weight: 800;
      color: var(--ink);
      letter-spacing: .2px;
    }

    .page-title i{
      width: 44px;
      height: 44px;
      border-radius: 14px;
      display:grid;
      place-items:center;
      color:#fff;
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      box-shadow: 0 14px 26px rgba(255,111,145,.22);
    }

    .page-sub{
      margin: 6px 0 0 0;
      color: var(--muted);
      font-weight: 600;
      font-size: .95rem;
    }

    .breadcrumb-lite{
      color: rgba(15,23,42,.45);
      font-weight: 700;
      font-size: .92rem;
    }

    /* ===== Form Card ===== */
    .form-card{
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 18px;
      box-shadow: var(--shadow);
      overflow: hidden;
    }

    .form-card__top{
      padding: 16px 18px;
      background: #fff;
      border-bottom: 1px solid var(--border);
      position: relative;
    }

    /* Accent bar */
    .form-card__top::before{
      content:"";
      position:absolute;
      left: 0;
      top: 0;
      height: 100%;
      width: 6px;
      background: linear-gradient(180deg, var(--primary), var(--primary-dark));
    }

    .form-card__top h5{
      margin: 0;
      font-weight: 800;
      color: var(--ink);
    }

    .form-card__body{
      padding: 18px;
    }

    /* ===== Inputs ===== */
    .form-label{
      font-weight: 700;
      color: var(--ink);
      margin-bottom: 8px;
    }

    .form-control, .form-select{
      border-radius: 14px;
      padding: 12px 12px;
      background: #fff;
      border: 1px solid rgba(15,23,42,.12);
      box-shadow: none !important;
      transition: border-color .18s ease, box-shadow .18s ease;
    }

    .form-control:focus, .form-select:focus{
      border-color: rgba(255,111,145,.75);
      box-shadow: 0 0 0 .22rem rgba(255,111,145,.18) !important;
    }

    .form-select{ cursor: pointer; }

    /* Icons inside inputs */
    .input-icon{ position: relative; }
    .input-icon i{
      position:absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: rgba(15,23,42,.45);
      font-size: 1.05rem;
      pointer-events:none;
    }
    .input-icon .form-control,
    .input-icon .form-select{
      padding-left: 40px;
    }

    /* ===== Buttons ===== */
    .btn-primary-soft{
      border: 0;
      border-radius: 14px;
      padding: 12px 14px;
      font-weight: 800;
      color: #fff;
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      box-shadow: 0 14px 28px rgba(255,111,145,.22);
      transition: transform .18s ease, box-shadow .18s ease, opacity .18s ease;
    }

    .btn-primary-soft:hover{
      transform: translateY(-1px);
      box-shadow: 0 18px 34px rgba(255,111,145,.28);
      opacity: .96;
      color:#fff;
    }

    .btn-cancel-soft{
      border-radius: 14px;
      padding: 12px 14px;
      font-weight: 800;
      color: var(--ink);
      background: #fff;
      border: 1px solid rgba(15,23,42,.14);
      transition: transform .18s ease, background .18s ease;
    }

    .btn-cancel-soft:hover{
      transform: translateY(-1px);
      background: rgba(15,23,42,.03);
      color: var(--ink);
    }

    /* Alerts */
    .alert{
      border-radius: 14px;
      border: 1px solid rgba(15,23,42,.08);
    }

    /* ===== Responsive ===== */
    @media (max-width: 900px){
      .app-layout{ flex-direction: column; }
      .sidebar{
        position: relative;
        top: 0;
        height: auto;
        overflow: visible;
        width: 100%;
      }
    }
  </style>
</head>

<body>
  <div class="app-layout">

        @include('ManagementSystemViews.AdminViews.Layouts.aside')

    <main class="app-content">

      <div class="page-top">
        <div>
          <h2 class="page-title">
            <i class="bi bi-person-plus"></i>
            Create New User
          </h2>
          <p class="page-sub">Add a new account with role permissions.</p>
        </div>

        <div class="text-end">
          <div class="breadcrumb-lite">Users / Create</div>
        </div>
      </div>

      <div class="form-card">
        <div class="form-card__top">
          <h5>Account Information</h5>
        </div>

        <div class="form-card__body">

          @if(session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
          @endif

          @if($errors->any())
            <div class="alert alert-danger mb-3">
              <ul class="mb-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="row g-3">

  <!-- Name -->
  <div class="col-12 col-lg-6">
    <label class="form-label">Name</label>
    <div class="input-icon">
      <i class="bi bi-person"></i>
      <input class="form-control"
             type="text"
             name="name"
             value="{{ old('name') }}"
             placeholder="Enter full name"
             required>
    </div>
  </div>

  <!-- Email -->
  <div class="col-12 col-lg-6">
    <label class="form-label">Email</label>
    <div class="input-icon">
      <i class="bi bi-envelope"></i>
      <input class="form-control"
             type="email"
             name="email"
             value="{{ old('email') }}"
             placeholder="example@email.com"
             required>
    </div>
  </div>

  <!-- Password -->
  <div class="col-12 col-lg-6">
    <label class="form-label">Password</label>
    <div class="input-icon">
      <i class="bi bi-lock"></i>
      <input class="form-control"
             type="password"
             name="password"
             placeholder="Create a strong password"
             required>
    </div>
  </div>

  <!-- Role -->
  <div class="col-12 col-lg-6">
    <label class="form-label">Role</label>
    <div class="input-icon">
      <i class="bi bi-shield-check"></i>
      <select class="form-select" name="role" required>
        <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select role</option>
        <option value="customer" {{ old('role')=='customer'?'selected':'' }}>Customer</option>
        <option value="admin" {{ old('role')=='admin'?'selected':'' }}>Admin</option>
      </select>
    </div>
  </div>

  <!-- BC Customer No -->
  <div class="col-12 col-lg-6">
    <label class="form-label">BC Customer No</label>
    <div class="input-icon">
      <i class="bi bi-person-badge"></i>
      <input class="form-control"
             type="text"
             name="BCcustomer_no"
             value="{{ old('BCcustomer_no') }}"
             placeholder="Enter BC Customer Number (Example: 10000)"
             required>
    </div>
  </div>

</div>

            <div class="d-flex gap-3 mt-4">
              <button class="btn btn-primary-soft flex-grow-1" type="submit">
                <i class="bi bi-check2-circle me-1"></i> Create User
              </button>

              <a class="btn btn-cancel-soft flex-grow-1" href="/users">
                Cancel
              </a>
            </div>

          </form>
        </div>
      </div>

    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
