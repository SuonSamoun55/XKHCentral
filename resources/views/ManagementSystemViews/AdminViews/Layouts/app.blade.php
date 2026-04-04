<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin System')</title>

    <link rel="stylesheet" href="{{ asset('css/ManagementSystem/adminSidbar.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* In app.blade.php or adminSidbar.css */
        .main-wrapper {
            display: flex;
            min-height: 100vh;
            /* Remove the gap here so it doesn't affect the login page */
        }

        .content-area {
            flex: 1;
            width: 100%;
        }

        /* Add the gap only when the aside is actually there */
        aside+.content-area {
            margin-left: 10px;
        }
    </style>
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

    <script src="{{ asset('js/AdminJS/sidebar.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

</body>

</html>
