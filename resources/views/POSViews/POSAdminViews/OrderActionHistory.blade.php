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
  <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        .page {
            max-width: 1250px;
            margin: 30px auto;
            padding: 0 16px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 12px;
            flex-wrap: wrap;
        }

        .title {
            font-size: 28px;
            font-weight: bold;
        }

        .link-btn {
            display: inline-block;
            text-decoration: none;
            background: #2563eb;
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
        }

        .table-wrap {
            overflow-x: auto;
            background: white;
            border-radius: 14px;
            box-shadow: 0 3px 14px rgba(0,0,0,0.08);
            padding: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #e5e7eb;
            text-align: left;
            font-size: 14px;
        }

        th {
            background: #f9fafb;
        }

        .confirmed {
            color: #15803d;
            font-weight: bold;
        }

        .cancelled {
            color: #b91c1c;
            font-weight: bold;
        }
    </style>
