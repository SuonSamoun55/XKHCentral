<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Order List</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #222;
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
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .title {
            font-size: 28px;
            font-weight: bold;
        }

        .link-btn {
            display: inline-block;
            text-decoration: none;
            background: #111827;
            color: #fff;
            padding: 10px 16px;
            border-radius: 8px;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .order-card {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 3px 14px rgba(0,0,0,0.08);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        .order-number {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .meta {
            color: #555;
            font-size: 14px;
            line-height: 1.8;
        }

        .status {
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status.pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status.confirmed {
            background: #dcfce7;
            color: #166534;
        }

        .status.cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .table-wrap {
            overflow-x: auto;
            margin-top: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
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

        .totals {
            margin-top: 14px;
            display: grid;
            gap: 8px;
            font-size: 14px;
            color: #333;
        }

        .actions {
            margin-top: 18px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            border: none;
            cursor: pointer;
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-confirm {
            background: #16a34a;
            color: white;
        }

        .btn-cancel {
            background: #dc2626;
            color: white;
        }

        .cancel-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            width: 100%;
            margin-top: 10px;
        }

        .cancel-form input {
            flex: 1;
            min-width: 220px;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
        }

        .empty-box {
            background: #fff;
            border-radius: 14px;
            padding: 40px 20px;
            text-align: center;
            color: #6b7280;
            box-shadow: 0 3px 14px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="top-bar">
            <div class="title">Admin Order List</div>
            <a href="{{ route('admin.orders.actions') }}" class="link-btn">View Action History</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @forelse($orders as $order)
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <div class="order-number">{{ $order->order_no }}</div>
                        <div class="meta">
                            <div><strong>User:</strong> {{ $order->user->name ?? 'N/A' }}</div>
                            <div><strong>Role:</strong> {{ $order->user->role ?? 'N/A' }}</div>
                            <div><strong>Currency:</strong> {{ $order->currency_code }}</div>
                            <div><strong>Checkout At:</strong> {{ $order->checked_out_at }}</div>
                        </div>
                    </div>

                    <div class="status {{ strtolower($order->status) }}">
                        {{ $order->status }}
                    </div>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Item No</th>
                                <th>Item Name</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Line Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->items as $item)
                                <tr>
                                    <td>{{ $item->item_no }}</td>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>{{ number_format($item->unit_price, 2) }}</td>
                                    <td>{{ number_format($item->line_total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No items found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="totals">
                    <div><strong>Subtotal:</strong> {{ number_format($order->subtotal, 2) }}</div>
                    <div><strong>Discount:</strong> {{ number_format($order->discount_amount, 2) }}</div>
                    <div><strong>Total:</strong> {{ number_format($order->total_amount, 2) }}</div>
                </div>

                @if($order->status === 'pending')
                    <div class="actions">
                        <form action="{{ route('admin.orders.confirm', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-confirm">Confirm</button>
                        </form>
                    </div>

                    <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST" class="cancel-form">
                        @csrf
                        <input type="text" name="note" placeholder="Cancel reason (optional)">
                        <button type="submit" class="btn btn-cancel">Cancel</button>
                    </form>
                @endif
            </div>
        @empty
            <div class="empty-box">No orders found.</div>
        @endforelse
    </div>
</body>
</html>
