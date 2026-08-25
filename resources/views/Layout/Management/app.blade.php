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
    <link rel="stylesheet" href="{{ asset('/css/views/Management/Layout/admin-sidebar.css') }}">
    <style>
        .app-content {
            padding: 20px 20px 0px 20px;
            background: #fff;
            border-radius: 12px;
            height: 100vh;
            overflow-y: auto;
        }

        @media (max-width: 768px) {
            .app-content {
                height: auto;
            }
        }
    </style>
    @stack('styles')

</head>

<body>

    <div class="app-shell" id="managementShell">
        @include('Layout/Management.aside')

        <div class="app-content">
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
