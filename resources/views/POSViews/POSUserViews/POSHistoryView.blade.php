<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<style>
/* Page */
body {
    background: #f5f6fa;
}

/* Wrapper */
.page-wrap {
    padding: 30px;
 background: var(--card);
 border-radius: 20px;
 ;    
}

/* Title */
.history-title {
    color: #00a8a8;
    font-weight: 600;
    margin-bottom: 20px;
}

/* Container FULL WIDTH */
.order-history-container {
    width: 100%;
    padding: 0 10px;
}

/* FILTER ROW */
.filter-form {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
    flex-wrap: wrap;
}

/* LEFT SIDE */
.left-section {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

/* RIGHT SIDE */
.right-section {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

/* Search */
.search-box {
    position: relative;
    width: 350px;
}
.search-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #aaa;
}
.search-box input {
    width: 100%;
    padding: 10px 12px 10px 36px;
    border: 1px solid #ddd;
    border-radius: 10px;
}

/* Tabs */
.status-tabs {
    display: flex;
    gap: 8px;
}
.tab-btn {
    border: 1px solid #ddd;
    background: #f3f4f6;
    padding: 6px 16px;
    border-radius: 8px;
    cursor: pointer;
}
.tab-btn.active {
    background: #00b5cc;
    color: #fff;
    border-color: #00b5cc;
}

/* Date */
.right-section input {
    border: 1px solid #00b5cc;
    border-radius: 8px;
    padding: 6px 10px;
}

/* TABLE CARD FULL WIDTH */
.custom-table-card {
    margin-top: 20px;
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #eee;
    width: 100%;
}

/* TABLE */
.table {
    width: 100%;
    border-collapse: collapse;
}

.table thead th {
    background: #f1f3f5 !important;
    padding: 18px 16px;
    font-weight: 600;
    text-align: left;
}

.table tbody td {
    padding: 18px 16px;
    border-bottom: 1px solid #eee;
}

/* Order link */
.order-link {
    color: #00b5cc;
    font-weight: 600;
    text-decoration: none;
}

/* Date */
.date-text {
    font-weight: 500;
}
.time-text {
    font-size: 13px;
    color: #777;
}

/* Total */
.total-text {
    font-weight: 700;
}

/* STATUS BADGE */
.status-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

/* Colors */
.status-badge.pending {
    background: #fef3c7;
    color: #d97706;
}
.status-badge.shipping {
    background: #dbeafe;
    color: #0284c7;
}
.status-badge.delivered {
    background: #d1fae5;
    color: #059669;
}

/* Download */
.btn-download {
    background: #e6f7fb;
    color: #00b5cc;
    border: none;
    padding: 6px 16px;
    border-radius: 20px;
    font-weight: 600;
    cursor: pointer;
}
.btn-download:hover {
    background: #00b5cc;
    color: #fff;
}
</style>

<body>

    <div class="app-shell" id="appShell">
    @include('ManagementSystemViews.UserViews.Layouts.aside')

    <div class="page-wrap">

        <div class="order-history-container">

            <h2 class="history-title">Order History</h2>

            <!-- FILTER -->
            <form action="{{ route('user.pos.order.history') }}" method="GET" class="filter-form">

                <div class="left-section">

                    <!-- Search -->
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" placeholder="Search..."
                            value="{{ request('search') }}">
                    </div>

                    <!-- Tabs -->
                    <div class="status-tabs">
                        @foreach (['All', 'Pending', 'On The Way', 'Delivered'] as $status)
                            <button type="submit" name="status" value="{{ $status }}"
                                class="tab-btn {{ request('status','All') == $status ? 'active' : '' }}">
                                {{ $status }}
                            </button>
                        @endforeach
                    </div>

                </div>

                <!-- Date -->
                <div class="right-section">
                    <label>Date</label>
                    <input type="date" name="date"
                        value="{{ request('date') }}"
                        onchange="this.form.submit()">
                </div>

            </form>

            <!-- TABLE -->
            <div class="custom-table-card">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="50"><input type="checkbox"></th>
                            <th>Order</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-end">Invoice</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td><input type="checkbox"></td>

                            <td>
                                <a href="#" class="order-link">
                                    #{{ $order->order_no }}
                                </a>
                            </td>

                            <td>
                                <div class="date-text">
                                    {{ optional($order->created_at)->format('M d, Y') }}
                                </div>
                                <div class="time-text">
                                    {{ $order->created_at->format('h:i A') }}
                                </div>
                            </td>

                            <td class="total-text">
                                ${{ number_format($order->total_amount, 2) }}
                            </td>

                            <td>
                                <span class="status-badge {{ strtolower(str_replace(' ', '-', $order->status)) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>

                            <td class="text-end">
                                <button class="btn-download">
                                    Download <i class="bi bi-download"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No orders found.</td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>

                @if ($orders->hasPages())
                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                @endif

            </div>

        </div>

    </div>
</div>

</body>
</html>