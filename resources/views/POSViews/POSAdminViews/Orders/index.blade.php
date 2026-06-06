@extends('POSViews.POSAdminViews.app')

@section('title', 'Approval Order')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/POSViews/POSAdminViews/Orders/index.css') }}">
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
