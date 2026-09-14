<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'User App')</title>
    <link rel="icon" type="image/png" href="{{ $activeFaviconUrl ?? asset('images/pos/xtricate.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('/css/views/shared/admin-variables.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/views/Management/Layout/admin-sidebar.css') }}">
    @stack('styles')

</head>

<body>

    <div class="app-shell" id="managementShell">
        <script>
            try {
                if (localStorage.getItem('managementSidebarCollapsed') === 'true') {
                    document.getElementById('managementShell').classList.add('collapsed');
                }
            } catch (_) {}
        </script>
        @include('Layout/Management.aside')

        <div class="app-content">
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
