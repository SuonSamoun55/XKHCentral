<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'POS Admin')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/views/POSViews/POSAdminViews/app.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/views/POSViews/POSAdminViews/Layout/POSAdminSidebar.css') }}">

    @stack('styles')
</head>
<body class="{{ trim((string) $__env->yieldContent('hideMobileNav', '')) !== '' ? 'admin-chat-page' : '' }}">

    <div class="main-wrapper app-shell" id="posAdminShell">
        @include('Layout.POSAdmin.aside')

        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    <script src="{{ asset('js/admin/sidebar.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
