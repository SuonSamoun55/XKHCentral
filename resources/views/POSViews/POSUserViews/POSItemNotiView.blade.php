<!DOCTYPE html>
<html>

<head>
    <title>Notifications</title>

    <link rel="stylesheet" href="{{ asset('css/ManagementSystem/aside.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <div class="app-shell" id="appShell">

        {{-- Sidebar --}}
        @include('ManagementSystemViews.UserViews.Layouts.aside')

        {{-- Content --}}
        <div class="page-wrap">

            <div class="container mt-4">

                <h2>Notifications</h2>

                <div class="list-group mt-3">

                    <div class="list-group-item">
                        <strong>New Order</strong>
                        <p class="mb-1">Customer placed a new order.</p>
                        <small class="text-muted">5 minutes ago</small>
                    </div>

                    <div class="list-group-item">
                        <strong>Payment Received</strong>
                        <p class="mb-1">Payment successfully completed.</p>
                        <small class="text-muted">30 minutes ago</small>
                    </div>

                    <div class="list-group-item">
                        <strong>Low Stock Alert</strong>
                        <p class="mb-1">Battery stock is running low.</p>
                        <small class="text-muted">1 hour ago</small>
                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>
