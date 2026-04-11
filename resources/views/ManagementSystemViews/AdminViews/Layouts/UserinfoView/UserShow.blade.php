<div class="container-fluid p-0">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <h3 class="mb-4 text-info">User View Detail</h3>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="fw-bold">Customer BC ID</label>
                    <div>{{ $customer->number ?? $customer->bc_customer_no ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="fw-bold">Full Name</label>
                    <div>{{ $customer->display_name ?? $customer->name ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="fw-bold">Email</label>
                    <div>{{ $customer->email ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="fw-bold">Phone</label>
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
        </div>
    </div>
</div>