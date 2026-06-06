<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin System')</title>

    <link rel="stylesheet" href="{{ asset('css/management-system/admin-sidebar.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/views/ManagementSystemViews/AdminViews/Layouts/app.css') }}">
    @stack('styles')
</head>

<body>

    <div class="main-wrapper">
        @if (!Route::is('login'))
            @include('ManagementSystemViews.AdminViews.Layouts.aside')
        @endif
        <div class="content-area">

            @yield('content')
        </div>
    </div>

    <script src="{{ asset('js/admin/sidebar.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

</body>

</html>
