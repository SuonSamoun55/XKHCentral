@include('ManagementSystemViews.AdminViews.Layouts.navbar')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#f4f6f8;">
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <h3 class="mb-4 text-info">User Detail</h3>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="fw-bold">BC Customer No</label>
                    <div>{{ $customer->number ?? $customer->bc_customer_no ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="fw-bold">BC Name</label>
                    <div>{{ $customer->display_name ?? $customer->name ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="fw-bold">BC Email</label>
                    <div>{{ $customer->email ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="fw-bold">BC Phone</label>
                    <div>{{ $customer->phone_number ?? $customer->phone ?? '-' }}</div>
                </div>
            </div>

            <hr class="my-4">

            <h5 class="mb-3">Connected User</h5>

            @if($user)
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="fw-bold">Name</label>
                        <div>{{ $user->name ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Email</label>
                        <div>{{ $user->email ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Phone</label>
                        <div>{{ $user->phone ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Role</label>
                        <div>{{ $user->role ?? '-' }}</div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning mb-0">This customer is not connected yet.</div>
            @endif

            <div class="mt-4">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
