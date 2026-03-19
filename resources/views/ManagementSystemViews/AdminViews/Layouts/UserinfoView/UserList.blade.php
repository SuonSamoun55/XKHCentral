@include('ManagementSystemViews.AdminViews.Layouts.navbar')

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>BC Customer List</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
body{
    background:#eef2f7;
    font-family:system-ui;
}

.app-layout{
    display:flex;
    gap:20px;
    padding:20px;
}

.app-content{
    flex:1;
}

.container-box{
    background:white;
    padding:25px;
    border-radius:14px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

.table{
    margin-bottom:0;
}

.table thead{
    background:#f5f7fb;
}

.action-btn{
    display:inline-flex;
    align-items:center;
    gap:5px;
}

.badge-connected{
    background:#198754;
}

.badge-not-connected{
    background:#6c757d;
}

@media(max-width:768px){
    .app-layout{
        flex-direction:column;
    }
}
</style>

</head>

<body>

<div class="app-layout">

    @include('ManagementSystemViews.AdminViews.Layouts.aside')

    <div class="app-content">

        <div class="container-box">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">BC Customer List</h3>

                <a href="{{ route('users.sync') }}" class="btn btn-success">
                    <i class="bi bi-arrow-repeat"></i> Sync BC Customers
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th width="220">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td>{{ $customer->id }}</td>
                                <td>{{ $customer->bc_customer_no }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email ?? '-' }}</td>
                                <td>{{ $customer->phone ?? '-' }}</td>
                                <td>
                                    @if($customer->connect_status === 'connected')
                                        <span class="badge badge-connected">Connected</span>
                                    @else
                                        <span class="badge badge-not-connected">Not Connected Yet</span>
                                    @endif
                                </td>
                                <td>
                                    @if($customer->connect_status === 'connected')
                                        <button class="btn btn-sm btn-secondary action-btn" disabled>
                                            <i class="bi bi-check-circle"></i> Connected
                                        </button>
                                    @else
                                        <a href="{{ route('users.create', $customer->id) }}" class="btn btn-sm btn-primary action-btn">
                                            <i class="bi bi-link-45deg"></i> Connect
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No BC customers found.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>

    </div>

</div>

</body>
</html>
