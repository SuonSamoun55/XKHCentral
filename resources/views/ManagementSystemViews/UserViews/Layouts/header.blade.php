
    <!-- Your CSS -->
    <link rel="stylesheet" href="{{ asset('css/pos/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/management-system/aside.css') }}">

{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="top-bar">
    <h1 class="page-title">{{ $title ?? 'Default Title' }}</h1>
</div>

<link rel="stylesheet" href="{{ asset('css/views/ManagementSystemViews/UserViews/Layouts/header.css') }}">
