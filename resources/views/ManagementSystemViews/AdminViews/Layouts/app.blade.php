<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'POS Portal'))</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background:#f6f8fb; }
        .app-navbar { height:64px; background:#fff; border-bottom:1px solid #e9eef2; display:flex; align-items:center; padding:0 20px; }
        .brand { color:#ff85a2; font-weight:800; display:flex; align-items:center; gap:8px }
        .app-container { display:flex; gap:20px; padding:20px; }
        .aside { width:240px; }
        .main { flex:1; }
        .card { border-radius:10px; }
        .user-avatar { width:36px; height:36px; border-radius:50%; background:#ff85a2; color:#fff; display:inline-flex; align-items:center; justify-content:center; font-weight:700 }
    </style>
</head>
<body>

<div class="app-container">
        @hasSection('aside')
            <aside class="aside">
                @yield('aside')
            </aside>
        @endif

        <main class="main">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</main>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
