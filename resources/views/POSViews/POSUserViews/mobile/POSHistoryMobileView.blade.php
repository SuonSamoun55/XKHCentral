@extends('ManagementSystemViews.UserViews.Layouts.app')
@include('ManagementSystemViews.UserViews.Layouts.footer')
@section('title', 'Order')

@section('content')
    <div class="mobile-order-wrapper">

        {{-- HEADER --}}
        <div class="header_sticky">
            <div class="mobile-header">
                <a href="{{ route('user.posinterface') }}" class="header-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>

                <h4 class="page-title">Order</h4>

                <a href="{{ route('user.pos.cart') }}" class="header-btn cart-btn">
                    <i class="bi bi-cart3"></i>
                    <span class="cart-badge">0</span>
                </a>
            </div>

            {{-- SEARCH --}}
            <form method="GET" action="{{ route('user.pos.order.history.mobile') }}">
                <div class="search-box">
                    <i class="bi bi-search"></i>

                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search your invoice...">


                </div>
                <div class="toggle-switch">
                    <input type="checkbox" id="switch">
                    <label for="switch"></label>
                </div>
            </form>
            <form method="GET" action="{{ route('user.pos.order.history.mobile') }}" class="filter-row" id="dateFilter"
                style="display: none;">

                <input type="hidden" name="search" value="{{ request('search') }}">

                <input type="date" name="date" class="date-input" value="{{ request('date') }}"
                    onchange="this.form.submit()">
            </form>
            {{-- STATUS TABS --}}
            <div class="status-tabs">

                <a class="status-pill all {{ request('status', 'all') === 'all' ? 'active' : '' }}"
                    href="{{ route('user.pos.order.history.mobile', ['status' => 'all']) }}">

                    All
                    <span>{{ $allCount }}</span>
                </a>

                <a class="status-pill pending {{ request('status') === 'pending' ? 'active' : '' }}"
                    href="{{ route('user.pos.order.history.mobile', ['status' => 'pending']) }}">

                    Pending
                    <span>{{ $pendingCount }}</span>
                </a>

                <a class="status-pill delivered {{ request('status') === 'delivered' ? 'active' : '' }}"
                    href="{{ route('user.pos.order.history.mobile', ['status' => 'delivered']) }}">

                    Delivered
                    <span>{{ $deliveredCount }}</span>
                </a>

                <a class="status-pill cancel {{ request('status') === 'cancelled' ? 'active' : '' }}"
                    href="{{ route('user.pos.order.history.mobile', ['status' => 'cancelled']) }}">

                    Cancel
                    <span>{{ $cancelledCount }}</span>
                </a>

            </div>
        </div>

        {{-- ORDER LIST --}}
        <div class="order-list">

            @forelse($orders as $order)
                <a href="{{ route('user.pos.order.detail', $order->id) }}" class="order-card">

                    {{-- IMAGE --}}
                    <div class="order-image">
                        <img src="{{ optional($order->items->first()->item)->image_url ?? asset('images/pos/default-food.png') }}"
                            alt="Order image">
                    </div>

                    {{-- CONTENT --}}
                    <div class="order-content">

                        <div class="order-top">
                            <div class="order-id">
                                Order ID : <strong>{{ $order->order_no }}</strong>
                            </div>

                            <span class="status-pill {{ strtolower($order->status) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        <div class="order-price">
                            ${{ number_format($order->total_amount, 2) }}
                        </div>

                        <div class="order-date">
                            Order date: {{ $order->created_at->format('d F Y') }}
                        </div>

                    </div>

                </a>
            @empty
                <div class="empty-order">No orders found.</div>
            @endforelse

        </div>

        <div class="pagination-container">
            {{ $orders->links('vendor.pagination.custom-pos') }}
        </div>
   


        <div class="mobile-pagination">
            <div class="mp-left">
                {{ $orders->firstItem() }} –
                {{ $orders->lastItem() }}
                of {{ $orders->total() }} Orders
            </div>


            <div class="mp-center">
                <span>Page</span>
                <select onchange="location = this.value;">
                    @for ($i = 1; $i <= $orders->lastPage(); $i++)
                        <option value="{{ $orders->url($i) }}" {{ $orders->currentPage() == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>
        </div>
    </div>
    @endsection
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toggle = document.getElementById('switch');
                const dateFilter = document.getElementById('dateFilter');

                if (!toggle || !dateFilter) return;

                if (toggle.checked) {
                    dateFilter.style.display = 'block';
                }

                toggle.addEventListener('change', function() {
                    dateFilter.style.display = this.checked ? 'block' : 'none';
                });
            });
        </script>
    @endpush

    <style>
        /* =========================
   HIDE DESKTOP SIDEBAR
========================= */

        @media (max-width: 768px) {


            .mobile-order-wrapper {

                min-height: 100vh;
                overflow-y: auto;
                overflow-x: hidden;
                min-width: 100%;
                background: #f4f6fb;
            }

            .table-head,
            .order-card {
                grid-template-columns: 1.3fr .9fr .9fr 1fr;
            }

            .status-badge {
                min-width: 0px;
                padding: 5px 10px;
                font-size: 10px;
            }

            .header_sticky {

                background: white;
                padding: 10px;
                position: sticky;
                top: 0;
                z-index: 10;
            }

            /* Order list */
            .order-list {
                margin-top: 6px;
                display: flex;
                flex-direction: column;
                gap: 14px;
            }

            /* Card */
            .order-card {
                display: flex;
                gap: 12px;
                background: #fff;
                border-radius: 14px;
                box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
                text-decoration: none;
                color: inherit;
                margin-left: 10px;
                margin-right: 10px;
            }

            /* Image */
            .order-image {
                width: 76px;
                height: 76px;
                flex-shrink: 0;
                border-radius: 12px;
                overflow: hidden;
            }

            .order-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            /* Content */
            .order-content {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .order-top {
                display: flex;
                justify-content: space-between;
                align-items: center;
                font-size: 13px;
            }

            /* Price */
            .order-price {
                font-size: 16px;
                font-weight: 600;
                color: #14b8a6;
                margin: 6px 0;
            }

            /* Date */
            .order-date {
                font-size: 12px;
                color: #6b7280;
            }

            /* Status pill */
            .status-pill {
                font-size: 11px;
                padding: 4px 10px;
                border-radius: 999px;
                white-space: nowrap;
            }

            /* Status colors */
            .status-pill.pending {
                background: #fff7ed;
                color: #f97316;
            }

            .status-pill.completed,
            .status-pill.delivered {
                background: #ecfdf5;
                color: #10b981;
            }

            .status-pill.cancelled {
                background: #fef2f2;
                color: #ef4444;
            }





            .mobile-status-select {
                height: 42px;
                border: none;
                border-radius: 12px;
                background: #fff;
                padding: 0 14px;
                font-size: 13px;
                font-weight: 500;
                color: #0f172a;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
                outline: none;
                cursor: pointer;
            }

            .sidebar,
            .sidebar-wrap {
                display: none;
            }

            /* =========================
   BODY
========================= */

            body {
                background: #f4f6fb;
            }

            html,
            body {
                overflow-y: auto !important;
                overflow-x: hidden;
                height: auto !important;
            }

            /* =========================
   MAIN WRAPPER
========================= */



            /* =========================
   HEADER
========================= */

            .mobile-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 20px;
            }

            .page-title {
                font-size: 18px;
                font-weight: 700;
                margin: 0;
                color: #1e293b;
            }

            .header-btn {
                width: 44px;
                height: 44px;
                border-radius: 14px;
                background: #eef2ff;
                display: flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                color: #1e293b;
                position: relative;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            }

            .header-btn i {
                font-size: 18px;
            }

            .cart-badge {
                position: absolute;
                top: -3px;
                right: -2px;
                background: #ff2d55;
                color: #fff;
                width: 18px;
                height: 18px;
                border-radius: 50%;
                font-size: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 600;
            }

            /* =========================
   SEARCH BOX
========================= */

            .search-box {
                background: #fff;
                border: 1px solid #e5e7eb;
                height: 42px;
                border-radius: 14px;
                padding: 0 14px;
                display: flex;
                align-items: center;
                gap: 10px;
                margin-bottom: 16px;
            }

            .search-box i {
                font-size: 18px;
                color: #64748b;
            }

            .search-box input {
                border: none;
                outline: none;
                width: 100%;
                background: transparent;
                font-size: 13px;
            }

            /* =========================
   TOGGLE
========================= */

            .toggle-switch {
                position: relative;
                left: 368px;
                bottom: 8px;
            }

            .toggle-switch input {
                display: none;
            }

            .toggle-switch label {
                width: 38px;
                height: 20px;
                background: #d1d5db;
                border-radius: 20px;
                position: relative;
                cursor: pointer;
                display: block;
            }

            .toggle-switch label::before {
                content: '';
                position: absolute;
                width: 16px;
                height: 16px;
                background: #fff;
                border-radius: 50%;
                top: 2px;
                left: 2px;
                transition: .3s;
            }

            .toggle-switch input:checked+label {
                background: #22d3ee;
            }

            .toggle-switch input:checked+label::before {
                left: 20px;
            }

            /* =========================
   FILTER
========================= */

            .filter-row {
                display: flex;
                gap: 10px;
                margin-bottom: 18px;
            }

            /* .filter-btn,
    .export-btn,
    .date-input {
        height: 42px;
        border-radius: 12px;
        border: 1px solid #dbe2ea;
        background: #fff;
        padding: 0 14px;
        font-size: 12px;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 6px;
    } */
            .date-input {
                height: 42px;
                width: auto;
                margin-left: auto;
                border-radius: 12px;
                border: 1px solid #dbe2ea;
                background: #fff;
                padding: 0 14px;
                font-size: 12px;
                color: #111827;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .filter-btn,
            .export-btn {
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            }

            .date-input {
                outline: none;
            }

            /* =========================
   STATUS TABS
========================= */

            .status-tabs {
                display: flex;
                gap: 14px;
                overflow-x: auto;

                scrollbar-width: none;
            }

            .status-tabs::-webkit-scrollbar {
                display: none;
            }

            .status-pill {
                height: 42px;
                border-radius: 12px;
                border: 1px solid #dbe2ea;
                background: #fff;
                padding: 0 14px;
                font-size: 12px;
                color: #111827;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .status-pill span {
                width: 20px;
                height: 20px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                font-size: 10px;
                font-weight: 700;
            }

            /* all */
            .status-pill.all {
                background: #fff;
                color: #0f172a;
                border-color: #dbe2ea;
            }

            .status-pill.all span {
                background: #0f172a;
            }

            /* pending */
            .status-pill.pending {
                background: white;
                color: #3b82f6;
            }

            .status-pill.pending span {
                background: #3b82f6;
            }

            /* delivered */
            .status-pill.delivered {
                background: white;
                color: black;
            }

            .status-pill.delivered span {
                background: #16a34a;
            }

            /* cancel */
            .status-pill.cancel {
                background: white;
                color: #f43f5e;
            }

            .status-pill.cancel span {
                background: #f43f5e;
            }

            /* =========================
   TABLE HEADER
========================= */

            .table-head {
                display: grid;
                grid-template-columns: 1.4fr 1fr 1fr 1fr;
                padding: 0 6px 8px;
                font-size: 12px;
                color: #111827;
                font-weight: 600;
            }

            /* =========================
   ORDER CARD
========================= */

            /* =========================
   ORDER LIST
========================= */

            .order-list {
                display: flex;
                flex-direction: column;
                gap: 6px;
            }

            /* =========================
   ORDER CARD
========================= */

            .order-card {
                width: auto;
                background: #fff;
                border-radius: 18px;
                padding: 14px;
                text-decoration: none;

                border: 1px solid #edf2f7;

                box-shadow:
                    0 2px 10px rgba(0, 0, 0, 0.03);

                transition: .2s ease;
            }

            .order-card:active {
                transform: scale(.98);
            }

            /* =========================
   ORDER CONTENT
========================= */

            .order-left {
                display: flex;
                flex-direction: column;
            }

            .order-top,
            .order-bottom,
            .amount {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
            }

            /* =========================
   TRACKING
========================= */

            .tracking-no {
                font-size: 15px;
                font-weight: 700;
                color: #14b8a6;
            }

            /* =========================
   TOTAL
========================= */

            .order-total {
                font-size: 15px;
                font-weight: 700;
                color: #0f172a;
            }

            /* =========================
   DATE
========================= */

            .date-wrapper {

                display: flex;
                flex-direction: column;
                gap: 2px;
            }

            .date-text {
                font-size: 12px;
                color: #475569;
                font-weight: 500;
            }

            .time-text {
                font-size: 11px;
                color: #94a3b8;
            }

            /* =========================
   STATUS
========================= */
            .status-badge {
                height: 34px;
                border-radius: 20px;
                font-size: 11px;
                font-weight: 700;

                display: flex;
                /* 🔥 required */
                align-items: center;
                /* vertical center */
                justify-content: flex-end;
                /* push content to right */
                padding-right: 10px;
                /* space from edge */
            }

            /* pending */

            .status-badge.pending {
                margin-left: auto;
                justify-content: center;
                align-items: center;
                width: 80px;
                background: #eff6ff;
                color: #2563eb;
                border: 1px solid #bfdbfe;
            }

            /* delivered */

            .status-badge.delivered {
                margin-left: auto;
                justify-content: center;
                align-items: center;
                width: 80px;
                background: #ecfdf5;
                color: #16a34a;
                border: 1px solid #bbf7d0;
            }

            /* cancelled */

            .status-badge.cancelled {
                margin-left: auto;
                justify-content: center;
                align-items: center;
                width: 80px;
                background: #fff1f2;
                color: #e11d48;
                border: 1px solid #fecdd3;
            }

            /* on the way */

            .status-badge.on-the-way {
                margin-left: auto;
                justify-content: center;
                align-items: center;
                width: 80px;
                background: #fffbeb;
                color: #d97706;
                border: 1px solid #fde68a;
            }

            /* =========================
   EMPTY
========================= */

            .empty-order {
                text-align: center;
                padding: 50px 20px;
                font-size: 14px;
                color: #94a3b8;
            }

            /* =========================
   PAGINATION
========================= */

          
  .mobile-pagination {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 6px;
      padding: 10px 6px;
      font-size: 11px;
      color: #475569;
  }
        }

            /* =========================
   MOBILE
========================= */
    </style>
