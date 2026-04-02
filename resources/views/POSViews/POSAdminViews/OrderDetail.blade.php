<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Detail</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        *{ box-sizing:border-box; }

        body{
            margin:0;
            background:#f5f6f8;
            font-family:Arial, Helvetica, sans-serif;
            color:#2b2b2b;
        }

        .page-layout{
            display:flex;
            min-height:100vh;
        }

        .sidebar-wrap{
            width:250px;
            flex-shrink:0;
            background:#fff;
            border-right:1px solid #ececec;
            min-height:100vh;
            overflow-y:auto;
        }

        .content-wrap{
            flex:1;
            min-width:0;
            padding:26px 24px;
        }

        .top-bar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:12px;
            flex-wrap:wrap;
            margin-bottom:18px;
        }

        .back-btn{
            display:inline-flex;
            align-items:center;
            gap:8px;
            text-decoration:none;
            color:#555;
            font-weight:600;
        }

        .page-title{
            font-size:24px;
            font-weight:700;
            color:#17b8c8;
            margin:0;
        }

        .profile-card,
        .detail-card,
        .table-wrap{
            background:#fff;
            border-radius:18px;
            padding:22px;
            box-shadow:0 6px 20px rgba(0,0,0,0.04);
            margin-bottom:20px;
        }

        .profile-card{
            display:flex;
            align-items:center;
            gap:18px;
            flex-wrap:wrap;
        }

        .profile-avatar{
            width:84px;
            height:84px;
            border-radius:50%;
            object-fit:cover;
            border:3px solid #eafbfc;
            flex-shrink:0;
        }

        .profile-name{
            font-size:24px;
            font-weight:700;
            margin-bottom:4px;
        }

        .profile-role{
            font-size:14px;
            color:#17b8c8;
            font-weight:600;
            margin-bottom:10px;
        }

        .profile-meta{
            display:grid;
            grid-template-columns:repeat(2, minmax(180px, 1fr));
            gap:8px 18px;
            font-size:14px;
            color:#555;
        }

        .detail-grid{
            display:grid;
            grid-template-columns:repeat(2, minmax(0, 1fr));
            gap:16px;
        }

        .detail-item{
            background:#f8fafb;
            border:1px solid #edf1f3;
            border-radius:12px;
            padding:14px;
        }

        .detail-label{
            font-size:12px;
            color:#8b8b8b;
            margin-bottom:6px;
        }

        .detail-value{
            font-size:15px;
            font-weight:700;
            color:#2b2b2b;
        }

        .status-pill{
            display:inline-flex;
            align-items:center;
            gap:8px;
            font-weight:600;
            color:#444;
        }

        .status-dot{
            width:10px;
            height:10px;
            border-radius:50%;
            display:inline-block;
        }

        .status-dot.pending{ background:#16c2d5; }
        .status-dot.confirmed{ background:#10b981; }
        .status-dot.cancelled{ background:#ef4444; }

        .section-title{
            font-size:18px;
            font-weight:700;
            margin:22px 0 12px;
            color:#2b2b2b;
        }

        .table-wrap{
            overflow-x:auto;
        }

        table{
            width:100%;
            border-collapse:collapse;
            min-width:700px;
        }

        th{
            background:#79d5df;
            color:#2c3e50;
            font-weight:600;
            padding:14px;
            text-align:left;
        }

        th:first-child{ border-top-left-radius:12px; }
        th:last-child{ border-top-right-radius:12px; }

        td{
            padding:14px;
            border-bottom:1px solid #ececec;
        }

        .action-box{
            margin-top:18px;
            display:flex;
            gap:10px;
            flex-wrap:wrap;
        }

        .approve-btn{
            border:none;
            background:#17bfd0;
            color:#fff;
            border-radius:999px;
            height:38px;
            padding:0 18px;
            font-size:14px;
            font-weight:700;
        }

        .reject-btn{
            border:1px solid #df4b4b;
            background:#fff;
            color:#df4b4b;
            border-radius:999px;
            height:38px;
            padding:0 18px;
            font-size:14px;
            font-weight:700;
        }

        textarea{
            resize:none;
        }

        @media (max-width: 768px){
            .sidebar-wrap{ display:none; }
            .content-wrap{ padding:18px; }
            .detail-grid{ grid-template-columns:1fr; }
            .profile-meta{ grid-template-columns:1fr; }
        }
    </style>
</head>
<body>

<div class="page-layout">
    <div class="sidebar-wrap">
        @include('POSViews.POSAdminViews.aside')
    </div>

    <div class="content-wrap">
        <div class="top-bar">
            <a href="{{ route('admin.orders.index') }}" class="back-btn">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

            <h1 class="page-title">Order Detail</h1>
        </div>

        <div class="profile-card">
            <img
                class="profile-avatar"
                src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name ?? 'User') }}&background=17bfd0&color=fff&size=128"
                alt="User"
            >

            <div>
                <div class="profile-name">{{ $order->user->name ?? 'N/A' }}</div>
                <div class="profile-role">{{ ucfirst($order->user->role ?? 'N/A') }}</div>

                <div class="profile-meta">
                    <div><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</div>
                    <div><strong>Phone:</strong> {{ $order->user->phone ?? 'N/A' }}</div>
                    <div><strong>BC Customer No:</strong> {{ $order->user->bc_customer_no ?? 'N/A' }}</div>
                    <div><strong>User ID:</strong> {{ $order->user->id ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Order No</div>
                    <div class="detail-value">{{ $order->order_no }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        <span class="status-pill">
                            <span class="status-dot {{ strtolower($order->status) }}"></span>
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Currency</div>
                    <div class="detail-value">{{ $order->currency_code ?? 'USD' }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Location Code</div>
                    <div class="detail-value">{{ $order->location_code ?? 'N/A' }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Subtotal</div>
                    <div class="detail-value">${{ number_format($order->subtotal ?? 0, 2) }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Discount</div>
                    <div class="detail-value">${{ number_format($order->discount_amount ?? 0, 2) }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Total</div>
                    <div class="detail-value">${{ number_format($order->total_amount ?? 0, 2) }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Checked Out At</div>
                    <div class="detail-value">
                        {{ \Carbon\Carbon::parse($order->checked_out_at ?? $order->created_at)->format('M d, Y h:i A') }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">BC Document No</div>
                    <div class="detail-value">{{ $order->bc_document_no ?? 'N/A' }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Sync Status</div>
                    <div class="detail-value">{{ ucfirst($order->sync_status ?? 'pending') }}</div>
                </div>
            </div>

            @if($order->status === 'pending')
                <div class="action-box">
                    <form action="{{ route('admin.orders.confirm', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="approve-btn">Approve</button>
                    </form>

                    <button type="button"
                            class="reject-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#cancelModal">
                        Reject
                    </button>
                </div>
            @endif
        </div>

        <div class="section-title">Order Items</div>

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
                            <td>${{ number_format($item->unit_price ?? 0, 2) }}</td>
                            <td>${{ number_format($item->line_total ?? 0, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($order->status === 'pending')
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST">
                @csrf

                <div class="modal-header border-0">
                    <h5 class="modal-title">Reject Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label fw-semibold">Reason</label>
                    <textarea
                        name="note"
                        class="form-control"
                        rows="5"
                        placeholder="Please input reason for cancelling this order..."
                        required></textarea>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Confirm Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
