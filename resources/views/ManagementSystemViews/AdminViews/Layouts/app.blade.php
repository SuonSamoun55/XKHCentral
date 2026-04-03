<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin System')</title>

    <link rel="stylesheet" href="{{ asset('css/ManagementSystem/adminSidbar.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .main-wrapper{
            display: flex;
            gap: 10px
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="main-wrapper">
    @include('ManagementSystemViews.AdminViews.Layouts.aside')

    <div class="content-area">

        @yield('content')
    </div>
</div>

<script src="{{ asset('js/AdminJS/sidebar.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

</body>
</html>
