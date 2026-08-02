<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'User App')</title>
    <link rel="icon" type="image/png" href="../images/pos/xtricate.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style> 
        .app-content{
            padding: 20px 20px 0px 20px;
            background:#fff;
        border-radius:12px;
        }
    </style>
    @stack('styles')
    
</head>

<body>

    <div class="app-shell" id="appShell">
        @include('Layout/Management.aside')

        <div class="app-content">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS bundle (required for modal, dropdown, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>