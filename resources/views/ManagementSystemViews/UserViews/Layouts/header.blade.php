
    <!-- Your CSS -->
    <link rel="stylesheet" href="{{ asset('css/pos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ManagementSystem/aside.css') }}">

{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="top-bar">
    <h1 class="page-title">{{ $title ?? 'Default Title' }}</h1>
</div>

<style>
   .top-bar {
    /* Existing Styles */
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 25px;
    margin-bottom: 12px;
    width: 100%;
    box-sizing: border-box;
    border-radius: 20px;    
    height: 70px;

    /* REQUIRED Sticky Fixes */
    position: sticky;
    top: 10px;                /* Sticks to very top */
    z-index: 100;          /* High number to stay on top of products */
}
    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary); /* Uses your aqua/teal color */

    }
</style>