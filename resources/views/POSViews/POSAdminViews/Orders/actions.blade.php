@extends('POSViews.POSAdminViews.app')

@section('title', 'Store Management')

@section('content')

<body>
    <div class="page">
        <div class="top-bar">
            <div class="title">Order Action History</div>
            <a href="{{ route('admin.orders.index') }}" class="link-btn">Back To Orders</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Order No</th>
                        <th>Customer Name</th>
                        <th>Customer Role</th>
                        <th>Action By</th>
                        <th>Action Type</th>
                        <th>Note</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($actions as $action)
                        <tr>
                            <td>{{ $action->order->order_no ?? 'N/A' }}</td>
                            <td>{{ $action->user->name ?? 'N/A' }}</td>
                            <td>{{ $action->user->role ?? 'N/A' }}</td>
                            <td>{{ $action->actionBy->name ?? 'N/A' }}</td>
                            <td class="{{ strtolower($action->action_type) }}">
                                {{ ucfirst($action->action_type) }}
                            </td>
                            <td>{{ $action->note }}</td>
                            <td>{{ $action->created_at }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No action history found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
@endsection
  <link rel="stylesheet" href="{{ asset('css/views/POSViews/POSAdminViews/Orders/actions.css') }}">
