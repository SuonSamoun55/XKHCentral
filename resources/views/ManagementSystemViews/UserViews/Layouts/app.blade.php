<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'User App')</title>
    <link rel="icon" type="image/png" href="../images/pos/xtricate.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/views/POSViews/POSUserViews/Layout/aside.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    @stack('styles')
</head>

<body>
    
    <div class="app-shell" id="appShell">
        @include('ManagementSystemViews.UserViews.Layouts.aside')
        @yield('content')


    </div>

    @stack('scripts')
</body>

</html>
