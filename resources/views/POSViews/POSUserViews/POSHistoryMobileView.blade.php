@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'Order')

@section('content')
    <div class="mobile-order-wrapper">

        {{-- HEADER --}}
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

                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search your invoice...">


            </div>
            <div class="toggle-switch">
                <input type="checkbox" id="switch">
                <label for="switch"></label>
            </div>
        </form>

        {{-- FILTER --}}
        <form method="GET" action="{{ route('user.pos.order.history.mobile') }}" class="filter-row">

            <input type="hidden" name="search" value="{{ request('search') }}">

            <select name="status" class="mobile-status-select" onchange="this.form.submit()">

                <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>
                    <i class="bi bi-funnel"></i>
                    Filter
                </option>

                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                    Pending
                </option>

                <option value="on-the-way" {{ request('status') == 'on-the-way' ? 'selected' : '' }}>
                    On The Way
                </option>

                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>
                    Delivered
                </option>

                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                    Cancelled
                </option>

            </select>

            <button type="button" class="export-btn">
                Export
                <i class="bi bi-download"></i>
            </button>

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
        {{-- TABLE HEADER --}}
        <div class="table-head">
            <span>Tracking</span>
            <span>Total</span>
            <span>Date</span>
            <span>Status</span>
        </div>

        {{-- ORDER LIST --}}
        <div class="order-list">

            @forelse($orders as $order)
                <a href="{{ route('user.pos.order.detail', $order->id) }}" class="order-card">

                    {{-- LEFT --}}
                    <div class="order-left">

                        <div class="order-top">
                            <span class="tracking-no">
                                #{{ $order->order_no }}
                            </span>

                            <span class="order-total">
                                ${{ number_format($order->total_amount, 2) }}
                            </span>
                        </div>

                        <div class="order-bottom">

                            <div class="date-wrapper">
                                <span class="date-text">
                                    {{ optional($order->created_at)->format('M d, Y') }}
                                </span>

                                <span class="time-text">
                                    {{ $order->created_at->format('h:i A') }}
                                </span>
                            </div>

                            <span class="status-badge {{ strtolower(str_replace(' ', '-', $order->status)) }}">
                                {{ ucfirst($order->status) }}
                            </span>

                        </div>

                    </div>

                </a>

            @empty

                <div class="empty-order">
                    No orders found.
                </div>
            @endforelse

        </div>
        <div class="mobile-bottom-nav">

            <a href="{{ route('user.posinterface') }}"
                class="{{ request()->routeIs('user.posinterface') ? 'active' : '' }}">
                <i class="bi bi-house-door-fill"></i>
                <span>home</span>
            </a>

            <a href="{{ route('user.pos.categories') }}"
                class="{{ request()->routeIs('user.pos.categories*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span>products</span>
            </a>

            <a href="{{ route('user.pos.favorites') }}"
                class="{{ request()->routeIs('user.pos.favorites') ? 'active' : '' }}">
                <i class="bi bi-heart"></i>
                <span>wishlist</span>
            </a>

            <a href="{{ route('user.notifications') }}"
                class="{{ request()->routeIs('user.notifications') ? 'active' : '' }}">
                <i class="bi bi-person"></i>
                <span>user</span>
            </a>

        </div>
        <div class="pagination-container">
            {{ $orders->links('vendor.pagination.custom-pos') }}
        </div>
    </div>
@endsection


<style>
    /* =========================
   HIDE DESKTOP SIDEBAR
========================= */

    @media (max-width: 768px) {


        .mobile-order-wrapper {
            padding: 18px;
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
            min-width: 70px;
            padding: 5px 10px;
            font-size: 10px;
        }

    }

    .mobile-bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 72px;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-around;
        border-top-left-radius: 18px;
        border-top-right-radius: 18px;
        box-shadow: 0 -10px 30px rgba(15, 23, 42, 0.08);
        z-index: 1200;
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

    .mobile-bottom-nav a {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        color: #64748b;
        font-size: 11px;
        text-decoration: none;
    }

    .mobile-bottom-nav a i {
        font-size: 20px;
        color: var(--primary);
    }

    .mobile-bottom-nav a.active {
        color: #0f172a;
    }

    .mobile-bottom-nav a.active i {
        color: var(--primary);
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
        left: 350px;
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

    .filter-btn,
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
    }

    .filter-btn,
    .export-btn {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }

    .date-input {
        width: 100%;
        outline: none;
    }

    /* =========================
   STATUS TABS
========================= */

    .status-tabs {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        margin-bottom: 18px;
        scrollbar-width: none;
    }

    .status-tabs::-webkit-scrollbar {
        display: none;
    }

    .status-pill {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        border-radius: 30px;
        text-decoration: none;
        font-size: 12px;
        white-space: nowrap;
        border: 1px solid transparent;
        font-weight: 500;
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
        background: #eef4ff;
        color: #3b82f6;
    }

    .status-pill.pending span {
        background: #3b82f6;
    }

    /* delivered */
    .status-pill.delivered {
        background: #edfff3;
        color: #16a34a;
    }

    .status-pill.delivered span {
        background: #16a34a;
    }

    /* cancel */
    .status-pill.cancel {
        background: #fff1f2;
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
        width: 100%;
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
    .order-bottom {
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
        min-width: 90px;
        height: 34px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 30px;

        font-size: 11px;
        font-weight: 700;

        padding: 0 14px;
    }

    /* pending */

    .status-badge.pending {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
    }

    /* delivered */

    .status-badge.delivered {
        background: #ecfdf5;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }

    /* cancelled */

    .status-badge.cancelled {
        background: #fff1f2;
        color: #e11d48;
        border: 1px solid #fecdd3;
    }

    /* on the way */

    .status-badge.on-the-way {
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

    .pagination-wrapper {
        margin-top: 18px;
    }

    .pagination-container {
        background: #e6dede;
        /* flex: 0 0 10%; */
        min-height: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        /* overflow: hidden;  */
        padding: 0;
    }

    .pagination-container {
        /* flex: 0 0 10%; */
        min-height: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        /* overflow: hidden; */
        padding: 0;
    }

    /* =========================
   MOBILE
========================= */
</style>
