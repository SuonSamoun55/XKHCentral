<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Orange')</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- Custom Styles --}}
    @stack('styles')

    <style>
        body{
            margin:0;
            background:#f4f6f8;
            font-family:Arial, Helvetica, sans-serif;
        }

        .main-wrapper{
            display:flex;
            min-height:100vh;
        }

        .sidebar{
            width:240px;
            background:#fff;
            border-right:1px solid #e5e7eb;
            padding:15px;
        }

        .content{
            flex:1;
            padding:20px;
        }
    </style>
</head>
<body>

<div class="main-wrapper">
    {{-- Sidebar --}}
    <div class="sidebar">
        <h5>🍊 Orange</h5>
        <hr>

        <div>Dashboard</div>
        <div style="color:#0ea5b7; font-weight:bold;">Company</div>
        <div>POS System</div>
        <div>User</div>
        <div>Approval Order</div>
        <div>Notification</div>
    </div>

    {{-- Main Content --}}
    <div class="content">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

</body>
</html>
