<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approval Order</title>

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
            width:100%;
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

        .page-title{
            font-size:24px;
            font-weight:700;
            color:#17b8c8;
            margin-bottom:24px;
        }

        .alert-box{
            border-radius:10px;
            padding:12px 14px;
            margin-bottom:16px;
            font-size:14px;
        }

        .alert-success{
            background:#dcfce7;
            color:#166534;
        }

        .alert-error{
            background:#fee2e2;
            color:#991b1b;
        }

        .top-tools{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:16px;
            flex-wrap:wrap;
            margin-bottom:18px;
        }

        .top-tools-left{
            display:flex;
            gap:12px;
            align-items:center;
            flex-wrap:wrap;
        }

        .search-form{
            display:flex;
            gap:12px;
            align-items:center;
            flex-wrap:wrap;
        }

        .search-box{
            position:relative;
            width:300px;
        }

        .search-box input{
            width:100%;
            height:42px;
            border:1px solid #d8d8d8;
            border-radius:12px;
            padding:0 42px 0 14px;
            outline:none;
            background:#fff;
            font-size:14px;
        }

        .search-box i{
            position:absolute;
            top:50%;
            right:14px;
            transform:translateY(-50%);
            color:#555;
            font-size:16px;
        }

        .tool-btn{
            height:40px;
            min-width:90px;
            border:1px solid #d9d9d9;
            border-radius:12px;
            background:#fff;
            color:#444;
            font-size:14px;
            padding:0 16px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            text-decoration:none;
            transition:.2s ease;
        }

        .tool-btn:hover{
            border-color:#18bfd0;
            color:#18bfd0;
        }

        .tab-actions{
            display:flex;
            align-items:center;
            gap:12px;
            flex-wrap:wrap;
        }

        .tab-btn{
            height:42px;
            min-width:180px;
            border-radius:999px;
            padding:0 24px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            text-decoration:none;
            font-weight:700;
            transition:.2s ease;
        }

        .tab-btn-primary{
            background:#18bfd0;
            color:#fff;
            border:none;
        }

        .tab-btn-secondary{
            background:#fff;
            color:#18bfd0;
            border:1px solid #18bfd0;
        }

        .tab-btn-inactive{
            opacity:.60;
        }

        .main-grid{
            display:block;
        }

        .table-wrap{
            overflow-x:auto;
        }

        table{
            width:100%;
            border-collapse:separate;
            border-spacing:0;
            min-width:920px;
        }

        thead th{
            background:#79d5df;
            color:#2c3e50;
            font-size:14px;
            font-weight:500;
            padding:14px 14px;
            text-align:left;
            white-space:nowrap;
        }

        thead th:first-child{ border-top-left-radius:14px; }
        thead th:last-child{ border-top-right-radius:14px; }

        tbody tr{
            transition:.2s ease;
        }

        tbody tr.clickable-row:hover td{
            background:#fafcfd;
        }

        tbody td{
            padding:14px 10px;
            border-bottom:1px solid #ececec;
            font-size:14px;
            color:#333;
            vertical-align:middle;
            background:transparent;
        }

        .customer-cell{
            display:flex;
            align-items:center;
            gap:10px;
            min-width:170px;
        }

        .customer-avatar{
            width:34px;
            height:34px;
            border-radius:50%;
            object-fit:cover;
            flex-shrink:0;
            border:1px solid #ddd;
        }

        .status-pill{
            display:inline-flex;
            align-items:center;
            gap:8px;
            font-weight:500;
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

        .approve-btn{
            border:none;
            background:#17bfd0;
            color:#fff;
            border-radius:999px;
            height:32px;
            padding:0 16px;
            font-size:13px;
            font-weight:700;
        }

        .reject-btn{
            border:1px solid #df4b4b;
            background:#fff;
            color:#df4b4b;
            border-radius:999px;
            height:32px;
            padding:0 16px;
            font-size:13px;
            font-weight:700;
        }

        .action-group{
            display:flex;
            align-items:center;
            gap:8px;
            flex-wrap:nowrap;
        }

        .action-inline-form{
            margin:0;
        }

        .pagination-wrap{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:16px;
            margin-top:20px;
            flex-wrap:wrap;
        }

        .items-count{
            color:#999;
            font-size:14px;
        }

        .empty-box{
            background:#fff;
            border-radius:16px;
            padding:50px 20px;
            text-align:center;
            color:#777;
            border:1px solid #ececec;
        }

        .done-text{
            color:#999;
            font-weight:600;
        }

        .table-order-no{
            font-size:12px;
            color:#8b8b8b;
            margin-top:3px;
        }

        .pagination-wrap nav,
        .pagination{
            margin:0;
        }

        .pagination .page-link{
            color:#18bfd0;
            border-radius:8px !important;
            margin:0 2px;
            border:1px solid #dce7ea;
        }

        .pagination .active .page-link{
            background:#18bfd0;
            border-color:#18bfd0;
            color:#fff;
        }

        .pagination .page-link:focus{
            box-shadow:none;
        }

        .modal textarea{
            resize:none;
        }

        @media (max-width: 768px){
            .sidebar-wrap{ display:none; }
            .content-wrap{ padding:18px; }
            .search-box{ width:100%; }
            .search-form{ width:100%; }
            .top-tools{ align-items:stretch; }
            .top-tools-left{ width:100%; }
        }
    </style>
</head>
<body>

<div class="page-layout">
    <div class="sidebar-wrap">
        @include('POSViews.POSAdminViews.aside')
    </div>

    <div class="content-wrap">
        <div class="page-title">Approval Order</div>

        @if(session('success'))
            <div class="alert-box alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert-box alert-error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert-box alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="top-tools">
            <div class="top-tools-left">
                <form method="GET" action="{{ route('admin.orders.index') }}" class="search-form">
                    <input type="hidden" name="tab" value="{{ $tab ?? 'new' }}">

                    <div class="search-box">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search for id, name product"
                        >
                        <i class="bi bi-search"></i>
                    </div>

                    <button type="submit" class="tool-btn">Search</button>
                </form>
            </div>

            <div class="tab-actions">
                <a href="{{ route('admin.orders.index', array_merge(request()->except('page', 'tab'), ['tab' => 'new'])) }}"
                   class="tab-btn {{ ($tab ?? 'new') === 'new' ? 'tab-btn-primary' : 'tab-btn-secondary tab-btn-inactive' }}">
                    New Order
                </a>

                <a href="{{ route('admin.orders.index', array_merge(request()->except('page', 'tab'), ['tab' => 'approved'])) }}"
                   class="tab-btn {{ ($tab ?? 'new') === 'approved' ? 'tab-btn-primary' : 'tab-btn-secondary tab-btn-inactive' }}">
                    Approved
                </a>
            </div>
        </div>

        <div class="main-grid">
            @if($orders->count())
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Price</th>
                                <th>Role</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($orders as $order)
                                <tr class="clickable-row"
                                    data-href="{{ route('admin.orders.show', $order->id) }}"
                                    style="cursor:pointer;">

                                    <td>
                                        <div class="customer-cell">
                                            <img
                                                class="customer-avatar"
                                                src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name ?? 'User') }}&background=17bfd0&color=fff"
                                                alt="User"
                                            >
                                            <div>
                                                <div>{{ $order->user->name ?? 'N/A' }}</div>
                                                <div class="table-order-no">{{ $order->order_no }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>${{ number_format($order->total_amount ?? 0, 2) }}</td>
                                    <td>{{ ucfirst($order->user->role ?? 'N/A') }}</td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($order->checked_out_at ?? $order->created_at)->format('m/d/y') }}<br>
                                        <span class="text-muted">
                                            at {{ \Carbon\Carbon::parse($order->checked_out_at ?? $order->created_at)->format('h:i A') }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="status-pill">
                                            <span class="status-dot {{ strtolower($order->status) }}"></span>
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>

                                    <td onclick="event.stopPropagation();">
                                        <div class="action-group">
                                            @if($order->status === 'pending')
                                                <form action="{{ route('admin.orders.confirm', $order->id) }}" method="POST" class="action-inline-form">
                                                    @csrf
                                                    <button type="submit" class="approve-btn">Approve</button>
                                                </form>

                                                <button type="button"
                                                        class="reject-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#cancelModal{{ $order->id }}">
                                                    Reject
                                                </button>

                                                <div class="modal fade" id="cancelModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
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
                                            @else
                                                <span class="done-text">Approved</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrap">
                    <div>{{ $orders->links() }}</div>

                    <div class="items-count">
                        {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} items
                    </div>
                </div>
            @else
                <div class="empty-box">No orders found.</div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.clickable-row').forEach(function(row){
        row.addEventListener('click', function(){
            const href = this.getAttribute('data-href');
            if(href){
                window.location.href = href;
            }
        });
    });
</script>

</body>
</html>
