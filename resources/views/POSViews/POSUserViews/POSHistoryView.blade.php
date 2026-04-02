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
        font-size: 14px;
    }

    /* Search */
    .search-box {
        padding-top: 18px;
        padding-bottom: 10px;
        position: relative;
        width: 350px;
    }

    .search-box i {
        position: absolute;
        left: 12px;
        top: 58%;
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
        padding: 16px 14px;
        font-weight: 600;
        text-align: left;
        font-size: 14px;
    }

    .table tbody td {
        padding: 14px 12px;
        font-size: 14px;
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

    .search-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        z-index: 1000;
        display: none;
        max-height: 250px;
        overflow-y: auto;
    }

    .search-suggestions.active {
        display: block;
        font-size: 14px;
        border: solid none;
        color: rgb(101, 93, 93);
    }

    .suggestion-item {
        padding: 10px 15px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
    }

    .suggestion-item:hover {
        background-color: #f8f9fa;
    }

    .badge-small {
        font-size: 10px;
        padding: 2px 6px;
        background: #e0f2fe;
        color: #0369a1;
        border-radius: 4px;
        margin-left: 5px;
    }
</style>

<body>

    <div class="app-shell" id="appShell">
        @include('ManagementSystemViews.UserViews.Layouts.aside')

        <div class="page-wrap">

            <div class="order-history-container">
    <h2 class="history-title">Order History</h2>

    <form action="{{ route('user.pos.order.history') }}" method="GET" class="filter-form">
        <div class="left-section">
            <div class="search-box" style="position: relative;">
                <i class="bi bi-search"></i>
                <input type="text" id="orderSearchInput" name="search" placeholder="Search Order No or Status..."
                    value="{{ request('search') }}" autocomplete="off">
                <div id="orderSuggestions" class="search-suggestions"></div>
            </div>

            <div class="status-tabs">
                @foreach (['All', 'Pending', 'On The Way', 'Delivered'] as $status)
                    <button type="submit" name="status" value="{{ $status }}"
                        class="tab-btn {{ request('status', 'All') == $status ? 'active' : '' }}">
                        {{ $status }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="right-section">
            <label>Date</label>
            <input type="date" name="date" id="dateInput" value="{{ request('date') }}"
                onchange="this.form.submit()">
        </div>
    </form>

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

            <tbody id="orderTableBody">
                @forelse($orders as $order)
                    <tr class="order-row" 
                        data-order-no="{{ $order->order_no }}"
                        data-status="{{ $order->status }}" 
                        data-customer="{{ $order->customer_no }}">
                        
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
    <a href="{{ route('user.pos.order.download', $order->id) }}" class="btn-download text-decoration-none">
        Download <i class="bi bi-download"></i>
    </a>
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
   <script>
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById('orderSearchInput');
    const searchSuggestions = document.getElementById('orderSuggestions');
    const orderRows = document.querySelectorAll('.order-row');
    const filterForm = document.querySelector('.filter-form');

    // Prepare data
    const allOrders = Array.from(orderRows).map(row => ({
        orderNo: row.dataset.orderNo || '',
        status: row.dataset.status || '',
        customer: row.dataset.customer || '',
        element: row
    }));

    // Handle typing
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.trim().toLowerCase();
        if (searchTerm.length === 0) {
            searchSuggestions.classList.remove('active');
            orderRows.forEach(row => row.style.display = ''); // Show all if empty
            return;
        }

        const filtered = allOrders.filter(order =>
            order.orderNo.toLowerCase().includes(searchTerm) ||
            order.status.toLowerCase().includes(searchTerm)
        );

        // Show dropdown
        if (filtered.length > 0) {
            searchSuggestions.innerHTML = filtered.slice(0, 5).map((order) => `
                <div class="suggestion-item" onclick="selectOrderSuggestion('${order.orderNo}')">
                    <strong>#${order.orderNo}</strong> - <small>${order.status}</small>
                </div>
            `).join('');
            searchSuggestions.classList.add('active');
        }

        // Live filter the table as you type
        orderRows.forEach(row => {
            const isMatch = filtered.some(f => f.element === row);
            row.style.display = isMatch ? '' : 'none';
        });
    });

    // --- THE "ONLY SHOW ONE" LOGIC ---
   window.selectOrderSuggestion = function(orderNo) {
    const targetRow = Array.from(orderRows).find(row => row.dataset.orderNo === orderNo);
    
    if (targetRow) {
        // ... (your existing hide/show logic) ...

        // OPTIONAL: Automatically trigger the download link in that row
        const downloadLink = targetRow.querySelector('.btn-download');
        if (downloadLink) {
            window.location.href = downloadLink.getAttribute('href');
        }
    }
};
    // Handle "Enter" key to filter strictly
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Stop page reload
            const term = this.value.trim().toLowerCase();
            
            // Hide all that don't match exactly
            orderRows.forEach(row => {
                const match = row.dataset.orderNo.toLowerCase() === term || 
                              row.dataset.orderNo.toLowerCase().includes(term);
                row.style.display = match ? '' : 'none';
            });
            searchSuggestions.classList.remove('active');
        }
    });

    // Close suggestions clicking outside
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
            searchSuggestions.classList.remove('active');
        }
    });
});
</script>
</body>

</html>
