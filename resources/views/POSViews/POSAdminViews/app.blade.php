<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'POS Admin')</title>

    <link rel="stylesheet" href="{{ asset('css/management-system/admin-sidebar.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/views/POSViews/POSAdminViews/app.css') }}">

    @stack('styles')
</head>
<body>

    <div class="main-wrapper">
        @include('POSViews.POSAdminViews.aside')

        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    <script src="{{ asset('js/admin/sidebar.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
