<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'User App')</title>
    <link rel="icon" type="image/png" href="../images/pos/xtricate.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/views/POSViews/POSUserViews/Layout/aside.css') }}?v={{ filemtime(public_path('css/views/POSViews/POSUserViews/Layout/aside.css')) }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('/css/views/POSViews/POSUserViews/Layout/footer.css') }}?v={{ filemtime(public_path('css/views/POSViews/POSUserViews/Layout/footer.css')) }}">
    <link rel="stylesheet" href="{{ asset('/css/views/POSViews/POSUserViews/Layout/header_mobile.css') }}?v={{ filemtime(public_path('css/views/POSViews/POSUserViews/Layout/header_mobile.css')) }}">

    @stack('styles')
</head>

<body>
    
    <div class="app-shell" id="appShell">
        @include('Layout.POSUser.aside')
        @yield('content')


    </div>

    @stack('scripts')
</body>

</html>
