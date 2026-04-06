@extends('POSViews.POSAdminViews.app')

@section('title', 'Approval Order')

@push('styles')
<style>
    .content-area{
        width: 100%;
    }

    .approval-page{
        padding: 20px;
        background: #f6f8fb;
        min-height: 100vh;
        border-radius: 15px;
        width: 100%;
    }

    .approval-header{
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .approval-title{
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .alert-box{
        padding: 12px 14px;
        border-radius: 10px;
        font-size: 14px;
        margin-bottom: 14px;
        border: 1px solid transparent;
    }

    .alert-success{
        background: #ecfdf5;
        color: #047857;
        border-color: #a7f3d0;
    }

    .alert-error{
        background: #fef2f2;
        color: #b91c1c;
        border-color: #fecaca;
    }

    .top-tools{
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .top-tools-left{
        flex: 1 1 520px;
        min-width: 280px;
    }

    .search-form{
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .search-box{
        position: relative;
        flex: 1 1 320px;
        min-width: 240px;
    }

    .search-box input{
        width: 100%;
        height: 42px;
        border: 1px solid #dbe2ea;
        border-radius: 10px;
        padding: 0 42px 0 14px;
        outline: none;
        background: #fff;
        font-size: 14px;
        color: #334155;
        transition: .2s ease;
    }

    .search-box input:focus{
        border-color: #11bfd1;
        box-shadow: 0 0 0 3px rgba(17, 191, 209, 0.12);
    }

    .search-box i{
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 15px;
    }

    .tool-btn{
        height: 42px;
        padding: 0 18px;
        border: none;
        border-radius: 10px;
        background: #11bfd1;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        transition: .2s ease;
        white-space: nowrap;
    }

    .tool-btn:hover{
        background: #0ea5b7;
    }

    .tab-actions{
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .tab-btn{
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        height: 42px;
        padding: 0 16px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        transition: .2s ease;
        border: 1px solid transparent;
    }

    .tab-btn-primary{
        background: #11bfd1;
        color: #fff;
    }

    .tab-btn-primary:hover{
        background: #0ea5b7;
        color: #fff;
    }

    .tab-btn-secondary{
        background: #fff;
        color: #475569;
        border-color: #dbe2ea;
    }

    .tab-btn-secondary:hover{
        background: #f8fafc;
        color: #1e293b;
    }

    .tab-btn-inactive{
        opacity: 1;
    }

    .main-grid{
        width: 100%;
    }

    .table-card{
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .table-wrap{
        width: 100%;
        overflow-x: auto;
    }

    .approval-table{
        width: 100%;
        /* min-width: 980px; */
        border-collapse: separate;
        border-spacing: 0;
    }

    .approval-table thead th{
        background: #f8fafc;
        color: #475569;
        font-size: 13px;
        font-weight: 700;
        padding: 14px 16px;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .approval-table tbody td{
        padding: 14px 16px;
        font-size: 14px;
        color: #334155;
        border-bottom: 1px solid #edf2f7;
        vertical-align: middle;
        background: #fff;
    }

    .approval-table tbody tr:last-child td{
        border-bottom: none;
    }

    .approval-table tbody tr{
        transition: .18s ease;
    }

    .approval-table tbody tr:hover{
        background: #f8fdff;
    }

    .clickable-row{
        cursor: pointer;
    }

    .customer-cell{
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
        /* min-width: 220px; */
    }

    .customer-avatar{
        width: 42px;
        height: 42px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
        border: 2px solid #e2e8f0;
        background: #f8fafc;
    }

    .customer-name{
        font-size: 14px;
        font-weight: 600;
        color: #0f172a;
        line-height: 1.2;
    }

    .table-order-no{
        font-size: 12px;
        color: #64748b;
        margin-top: 3px;
        word-break: break-word;
    }

    .price-text{
        font-weight: 700;
        color: #0f172a;
        white-space: nowrap;
    }

    .role-badge{
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 10px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 700;
        text-transform: capitalize;
    }

    .date-text{
        font-size: 14px;
        color: #0f172a;
        font-weight: 600;
    }

    .date-subtext{
        display: inline-block;
        margin-top: 4px;
        font-size: 12px;
        color: #64748b;
    }

    .status-pill{
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        border-radius: 999px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        white-space: nowrap;
    }

    .status-dot{
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }

    .status-dot.pending{ background: #f59e0b; }
    .status-dot.approved{ background: #10b981; }
    .status-dot.cancelled{ background: #ef4444; }
    .status-dot.rejected{ background: #ef4444; }
    .status-dot.completed{ background: #10b981; }
    .status-dot.confirmed{ background: #10b981; }

    .action-group{
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .action-inline-form{
        margin: 0;
    }

    .approve-btn,
    .reject-btn{
        border: none;
        border-radius: 8px;
        height: 34px;
        padding: 0 14px;
        font-size: 13px;
        font-weight: 600;
        transition: .18s ease;
        white-space: nowrap;
    }

    .approve-btn{
        background: #dcfce7;
        color: #15803d;
    }

    .approve-btn:hover{
        background: #bbf7d0;
    }

    .reject-btn{
        background: #fee2e2;
        color: #b91c1c;
    }

    .reject-btn:hover{
        background: #fecaca;
    }

    .done-text{
        display: inline-flex;
        align-items: center;
        padding: 7px 12px;
        border-radius: 999px;
        background: #ecfdf5;
        color: #047857;
        font-size: 13px;
        font-weight: 700;
    }

    .pagination-wrap{
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        padding: 14px 2px 0;
    }

    .pagination-wrap .pagination{
        margin-bottom: 0;
    }

    .pagination-wrap .page-link{
        border-radius: 8px !important;
        margin: 0 2px;
        color: #334155;
        border-color: #dbe2ea;
        font-size: 13px;
        box-shadow: none !important;
    }

    .pagination-wrap .page-item.active .page-link{
        background: #11bfd1;
        border-color: #11bfd1;
        color: #fff;
    }

    .items-count{
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
    }

    .empty-box{
        background: #fff;
        border: 1px dashed #cbd5e1;
        border-radius: 14px;
        padding: 50px 20px;
        text-align: center;
        color: #64748b;
        font-size: 15px;
        font-weight: 500;
    }

    .modal-content{
        border-radius: 18px !important;
    }

    .modal-title{
        font-weight: 700;
        color: #0f172a;
    }

    .form-label{
        color: #334155;
    }

    .modal textarea.form-control{
        border-radius: 10px;
        border: 1px solid #dbe2ea;
        box-shadow: none;
    }

    .modal textarea.form-control:focus{
        border-color: #11bfd1;
        box-shadow: 0 0 0 3px rgba(17, 191, 209, 0.12);
    }

    @media (max-width: 768px){
        .approval-page{
            padding: 14px;
        }

        .approval-title{
            font-size: 20px;
        }

        .top-tools{
            align-items: stretch;
        }

        .search-form{
            width: 100%;
        }

        .tool-btn{
            width: 100%;
        }

        .tab-actions{
            width: 100%;
        }

        .tab-btn{
            flex: 1 1 auto;
        }

        .pagination-wrap{
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')
<div class="approval-page">
    <div class="approval-header">
        <h1 class="approval-title">Approval Order</h1>
    </div>

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
            <div class="table-card">
                <div class="table-wrap">
                    <table class="approval-table">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Price</th>
                                <th>Role</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th style="min-width: 180px;">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($orders as $order)
                                <tr class="clickable-row" data-href="{{ route('admin.orders.show', $order->id) }}">
                                    <td>
                                        <div class="customer-cell">
                                            <img
                                                class="customer-avatar"
                                                src="{{ $order->user->profile_image_display ?? 'https://ui-avatars.com/api/?name=' . urlencode($order->user->name ?? 'User') . '&background=17bfd0&color=fff' }}"
                                                alt="{{ $order->user->name ?? 'User' }}"
                                                onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($order->user->name ?? 'User') }}&background=17bfd0&color=fff';"
                                            >

                                            <div>
                                                <div class="customer-name">{{ $order->user->name ?? 'N/A' }}</div>
                                                <div class="table-order-no">{{ $order->order_no }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="price-text">
                                            ${{ number_format($order->total_amount ?? 0, 2) }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="role-badge">
                                            {{ ucfirst($order->user->role ?? 'N/A') }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="date-text">
                                            {{ \Carbon\Carbon::parse($order->checked_out_at ?? $order->created_at)->format('m/d/y') }}
                                        </div>
                                        <span class="date-subtext">
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
                                                        <div class="modal-content border-0 shadow">
                                                            <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST">
                                                                @csrf

                                                                <div class="modal-header border-0">
                                                                    <h5 class="modal-title">Reject Order</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>

                                                                <div class="modal-body pt-0">
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
                                                <span class="done-text">{{ ucfirst($order->status) }}</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="pagination-wrap">
                <div>
                    {{ $orders->links() }}
                </div>

                <div class="items-count">
                    {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} items
                </div>
            </div>
        @else
            <div class="empty-box">No orders found.</div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.clickable-row').forEach(function (row) {
            row.addEventListener('click', function (e) {
                if (
                    e.target.closest('button') ||
                    e.target.closest('a') ||
                    e.target.closest('form') ||
                    e.target.closest('.modal')
                ) {
                    return;
                }

                const href = this.getAttribute('data-href');
                if (href) {
                    window.location.href = href;
                }
            });
        });
    });
</script>
@endpush
