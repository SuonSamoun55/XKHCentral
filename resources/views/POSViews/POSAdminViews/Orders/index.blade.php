@extends('Layout.POSAdmin.app')
@section('title', 'Approval Order')

@push('styles')
    <link rel="stylesheet"
        href="{{ asset('css/views/POSViews/POSAdminViews/Orders/index.css') }}?v={{ filemtime(public_path('css/views/POSViews/POSAdminViews/Orders/index.css')) }}">
@endpush
@section('content')
    <div class="approval-page">
        <div class="approval-header">
            <h1 class="approval-title">Approval Order</h1>
        </div>

        <div class="alert-container" id="alertContainer"></div>

        @if ($activityFilterCustomerName || $activityFilterIsMine)
            <div class="activity-filter-banner">
                <span>
                    <i class="bi bi-funnel-fill"></i>
                    Showing {{ $activityFilterIsMine ? 'orders you approved' : 'orders' }}
                    @if ($activityFilterCustomerName)
                        for <strong>{{ $activityFilterCustomerName }}</strong>
                    @endif
                </span>
                <a href="{{ route('admin.orders.index', ['tab' => $tab]) }}" class="activity-filter-clear">
                    Clear filter <i class="bi bi-x-lg"></i>
                </a>
            </div>
        @endif

        <!-- ===================== MOBILE ONLY ===================== -->
        <div class="mobile-top-tools">
            <div class="mobile-search-row">
                <form method="GET" action="{{ route('admin.orders.index') }}" class="search-form">
                    <input type="hidden" name="tab" value="{{ $tab ?? 'new' }}">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by name or ID">
                    </div>
                </form>

                <button type="button" class="mobile-filter-btn" id="mobileDateToggle" aria-label="Filter by date">
                    <img src="{{ asset('images/AdminPOS/calendar (3).png') }}" alt="" class="mobile-toggle-icon">
                </button>
            </div>

            <form method="GET" action="{{ route('admin.orders.index') }}" class="mobile-date-form" id="mobileDatePanel">
                <input type="hidden" name="tab" value="{{ $tab ?? 'new' }}">
                @if (request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <input type="date" name="date" id="mobileDateInput" value="{{ request('date') }}"
                    onchange="this.form.submit()">
            </form>

            {{-- <button type="button" class="mobile-export-btn" title="Export isn't wired up yet" disabled>
            <i class="bi bi-download"></i> Export
        </button> --}}
        </div>
        <div class="top-tools">
            <div class="top-tools-left">
                <form method="GET" action="{{ route('admin.orders.index') }}" class="search-form">
                    <input type="hidden" name="tab" value="{{ $tab ?? 'new' }}">

                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search order number or status">
                    </div>
                </form>
            </div>

            <div class="tab-actions">
                <a href="{{ route('admin.orders.index', array_merge(request()->except('page', 'tab'), ['tab' => 'new'])) }}"
                    class="tab-btn {{ ($tab ?? 'new') === 'new' ? 'tab-btn-primary' : 'tab-btn-secondary tab-btn-inactive' }}">
                    New Order ({{ $newOrdersCount }})
                </a>

                <a href="{{ route('admin.orders.index', array_merge(request()->except('page', 'tab'), ['tab' => 'approved'])) }}"
                    class="tab-btn {{ ($tab ?? 'new') === 'approved' ? 'tab-btn-primary' : 'tab-btn-secondary tab-btn-inactive' }}">
                    Order History ({{ $approvedOrdersCount }})
                </a>

                <form method="GET" action="{{ route('admin.orders.index') }}" class="date-filter-form">
                    <input type="hidden" name="tab" value="{{ $tab ?? 'new' }}">
                    @if (request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()">
                </form>
            </div>
        </div>
        @if (($tab ?? 'new') === 'new')
            <div class="awaiting-banner">
                <i class="bi bi-check-circle-fill"></i>
                {{ $newOrdersCount }} {{ \Illuminate\Support\Str::plural('order', $newOrdersCount) }} awaiting approval
            </div>
        @endif

        <div class="main-grid">
            @if ($orders->count())
                <div class="table-card">
                    <div class="table-wrap">
                        <table class="approval-table">
                            <thead>
                                <tr>
                                    <th style="width:36px;"><input type="checkbox" id="selectAllOrders"></th>
                                    <th>Customer</th>
                                    @if (($tab ?? 'new') === 'approved')
                                        <th>Date</th>
                                        <th>Approved By</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Remark</th>
                                    @else
                                        <th>Role</th>
                                        <th>Date</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($orders as $order)
                                    @php
                                        $orderAction = $order->actions->first(
                                            fn($a) => strtolower($a->status ?? $a->action_type) ===
                                                strtolower($order->status),
                                        );
                                    @endphp
                                    <tr class="clickable-row" data-href="{{ route('admin.orders.show', $order->id) }}">
                                        <td onclick="event.stopPropagation();">
                                            <input type="checkbox" class="order-row-checkbox" value="{{ $order->id }}">
                                        </td>

                                        <td>
                                            <div class="customer-cell">
                                                <img class="customer-avatar"
                                                    src="{{ $order->user->profile_image_display ?? 'https://ui-avatars.com/api/?name=' . urlencode($order->user->name ?? 'User') . '&background=17bfd0&color=fff' }}"
                                                    alt="{{ $order->user->name ?? 'User' }}"
                                                    onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($order->user->name ?? 'User') }}&background=17bfd0&color=fff';">

                                                <div>
                                                    <div class="customer-name">{{ $order->user ? ucwords($order->user->name) : 'N/A' }}</div>
                                                    <div class="table-order-no">{{ $order->order_no }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        @if (($tab ?? 'new') === 'approved')
                                            <td>
                                                <div class="date-text">
                                                    {{ \Carbon\Carbon::parse($order->checked_out_at ?? $order->created_at)->format('m/d/y') }}
                                                </div>
                                                <span class="date-subtext">
                                                    at
                                                    {{ \Carbon\Carbon::parse($order->checked_out_at ?? $order->created_at)->format('h:i A') }}
                                                </span>
                                            </td>

                                            <td>
                                                @if ($orderAction)
                                                    <div class="approved-by-name">
                                                        {{ $orderAction->actionBy->name ?? 'Admin' }}</div>
                                                @else
                                                    <span class="date-subtext">&mdash;</span>
                                                @endif
                                            </td>

                                            <td>
                                                <span class="role-badge">
                                                    {{ ucfirst($order->user->role ?? 'N/A') }}
                                                </span>
                                            </td>

                                            <td>
                                                <span class="status-pill-outline">
                                                    <i
                                                        class="bi {{ in_array($order->status, ['cancelled', 'rejected']) ? 'bi-x-circle' : 'bi-check-circle' }}"></i>
                                                    {{ $order->status === 'confirmed' ? 'Approved' : ucfirst($order->status) }}
                                                </span>
                                            </td>

                                            <td>
                                                <span class="remark-text">{{ $orderAction->note ?? '—' }}</span>
                                            </td>
                                        @else
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
                                                    at
                                                    {{ \Carbon\Carbon::parse($order->checked_out_at ?? $order->created_at)->format('h:i A') }}
                                                </span>
                                            </td>

                                            <td>
                                                <div class="price-text">${{ number_format($order->total_amount ?? 0, 2) }}
                                                </div>
                                            </td>

                                            <td>
                                                <span class="status-pill status-{{ strtolower($order->status) }}">
                                                    @if ($order->status !== 'pending')
                                                        <i
                                                            class="bi {{ in_array($order->status, ['cancelled', 'rejected']) ? 'bi-x-circle-fill' : 'bi-check-circle-fill' }}"></i>
                                                    @endif
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>

                                            <td onclick="event.stopPropagation();">
                                                <div class="action-group">
                                                    <form action="{{ route('admin.orders.confirm', $order->id) }}"
                                                        method="POST" class="action-inline-form js-approve-order-form">
                                                        @csrf
                                                        <button type="button" class="approve-btn js-approve-order-btn">
                                                            <i class="bi bi-check-circle-fill"></i> Approve
                                                        </button>
                                                    </form>

                                                    <button type="button" class="reject-btn" data-bs-toggle="modal"
                                                        data-bs-target="#cancelModal{{ $order->id }}">
                                                        <i class="bi bi-x-circle"></i> Reject
                                                    </button>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ===================== MOBILE ONLY ===================== -->
                <div class="mobile-order-cards">
                    @foreach ($orders as $order)
                        @php
                            $itemsSummary = $order->items
                                ->map(fn($i) => ($i->item_name ?? 'Item') . ' ×' . (int) ($i->qty ?? 1))
                                ->implode(' • ');

                            $statusIcon = in_array($order->status, ['cancelled', 'rejected'])
                                ? 'bi-x-circle-fill'
                                : (in_array($order->status, ['confirmed', 'completed', 'approved'])
                                    ? 'bi-check-circle-fill'
                                    : null);

                            // "Confirmed" is the raw approval-workflow status; the customer-facing
                            // label for a finished order is "Completed".
                            $statusLabel = $order->status === 'confirmed' ? 'Completed' : ucfirst($order->status);
                        @endphp
                        <div class="mobile-order-card">
                            <div class="mobile-order-top">
                                <img class="mobile-order-avatar"
                                    src="{{ $order->user->profile_image_display ?? 'https://ui-avatars.com/api/?name=' . urlencode($order->user->name ?? 'User') . '&background=17bfd0&color=fff' }}"
                                    alt="{{ $order->user->name ?? 'User' }}"
                                    onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($order->user->name ?? 'User') }}&background=17bfd0&color=fff';">
                                <div class="mobile-order-name-wrap">
                                    <div class="mobile-order-name">{{ $order->user ? ucwords($order->user->name) : 'N/A' }}</div>
                                    <span class="status-pill status-{{ strtolower($order->status) }}">
                                        @if ($statusIcon)
                                            <i class="bi {{ $statusIcon }}"></i>
                                        @endif
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                                <div class="mobile-order-price">${{ number_format($order->total_amount ?? 0, 2) }}</div>
                            </div>

                            <div class="mobile-order-id">Order #{{ $order->order_no }}</div>

                            @if ($itemsSummary)
                                <div class="mobile-order-items">{{ $itemsSummary }}</div>
                            @endif

                            <div class="mobile-order-date">
                                <i class="bi bi-calendar3"></i>
                                {{ \Carbon\Carbon::parse($order->checked_out_at ?? $order->created_at)->format('F j, Y') }}
                                &bull;
                                {{ \Carbon\Carbon::parse($order->checked_out_at ?? $order->created_at)->format('g:i A') }}
                            </div>

                            <div class="mobile-order-divider"></div>

                            <div class="mobile-order-bottom">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="mobile-view-details-link">
                                    {{ ($tab ?? 'new') === 'approved' ? 'View Invoice' : 'View details' }} <i
                                        class="bi bi-chevron-right"></i>
                                </a>

                                @if (($tab ?? 'new') !== 'approved' && $order->status === 'pending')
                                    <div class="mobile-order-actions">
                                        <button type="button" class="mobile-reject-btn" data-bs-toggle="modal"
                                            data-bs-target="#cancelModal{{ $order->id }}">
                                            <i class="bi bi-x-circle"></i> Reject
                                        </button>

                                        <form action="{{ route('admin.orders.confirm', $order->id) }}" method="POST"
                                            class="mobile-approve-form js-approve-order-form">
                                            @csrf
                                            <button type="button" class="mobile-approve-btn js-approve-order-btn">
                                                <i class="bi bi-check-circle-fill"></i> Approve
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- =================== END MOBILE ONLY =================== -->

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
        @if ($orders->count() && ($tab ?? 'new') !== 'approved')
            @foreach ($orders as $order)
                @if ($order->status === 'pending')
                    <div class="modal fade remark-modal" id="cancelModal{{ $order->id }}" tabindex="-1"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content remark-modal-content">
                                <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST">
                                    @csrf

                                    <div class="modal-header remark-modal-header">
                                        <h5 class="remark-modal-title">Remark</h5>
                                        <button type="button" class="remark-modal-close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body remark-modal-body">
                                        <textarea name="note" class="remark-textarea" required></textarea>
                                        <div class="remark-hint">
                                            <i class="bi bi-exclamation-circle-fill"></i>
                                            Please provide a reason you reject the items
                                        </div>
                                    </div>

                                    <div class="modal-footer remark-modal-footer">
                                        <button type="button" class="remark-cancel-btn"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="remark-save-btn">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
        @if ($orders->count() && ($tab ?? 'new') !== 'approved')
            <div class="modal fade confirm-action-modal" id="confirmApproveModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content confirm-action-content">
                        <div class="modal-body confirm-action-body">
                            <h5 class="confirm-action-title">Approve this order?</h5>
                            <p class="confirm-action-message">Once approved, this order will be confirmed and stored in
                                Business Central as a Sales Order. This action cannot be undone.</p>
                        </div>
                        <div class="modal-footer confirm-action-footer">
                            <button type="button" class="confirm-action-delete-btn confirm-action-approve-btn"
                                id="confirmApproveBtn">Approve</button>
                            <button type="button" class="confirm-action-cancel-btn"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alertContainer = document.getElementById('alertContainer');

            function showAlert(message, type = 'success') {
                if (!alertContainer) return;
                const el = document.createElement('div');
                el.className = `custom-alert alert-${type}`;
                el.innerHTML =
                    `<i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'}"></i><span>${message}</span>`;
                alertContainer.appendChild(el);
                setTimeout(() => {
                    el.classList.add('fade-out');
                    setTimeout(() => el.remove(), 300);
                }, 4000);
            }

            @if (session('success'))
                showAlert(@json(session('success')), 'success');
            @endif

            @if (session('error'))
                showAlert(@json(session('error')), 'danger');
            @endif

            @if ($errors->any())
                showAlert(@json($errors->first()), 'danger');
            @endif

            // Approve requires confirming in a modal first, instead of submitting
            // the form the instant the button is clicked. One shared modal serves
            // every row — we just track which row's form to submit on confirm.
            let bsApproveModal = null;
            let pendingApproveForm = null;
            const approveModalEl = document.getElementById('confirmApproveModal');
            if (approveModalEl) {
                bsApproveModal = new bootstrap.Modal(approveModalEl);
            }

            document.querySelectorAll('.js-approve-order-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    pendingApproveForm = btn.closest('.js-approve-order-form');
                    bsApproveModal?.show();
                });
            });

            document.getElementById('confirmApproveBtn')?.addEventListener('click', function() {
                bsApproveModal?.hide();
                pendingApproveForm?.submit();
            });

            const selectAllOrders = document.getElementById('selectAllOrders');
            if (selectAllOrders) {
                selectAllOrders.addEventListener('click', function(e) {
                    e.stopPropagation();
                    document.querySelectorAll('.order-row-checkbox').forEach(cb => cb.checked =
                        selectAllOrders.checked);
                });
            }

            document.querySelectorAll('.clickable-row').forEach(function(row) {
                row.addEventListener('click', function(e) {
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
            const mobileDateToggle = document.getElementById('mobileDateToggle');
            const mobileDateInput = document.getElementById('mobileDateInput');
            if (mobileDateToggle && mobileDateInput) {
                mobileDateToggle.addEventListener('click', function() {
                    if (mobileDateInput.showPicker) {
                        mobileDateInput.showPicker();
                    } else {
                        mobileDateInput.focus();
                    }
                });
            }
        });
    </script>
@endpush
