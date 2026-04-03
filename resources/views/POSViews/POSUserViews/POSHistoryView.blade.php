<link rel="stylesheet" href="{{ asset('css/POSsystem/history.css') }}">
<style>

</style>

<body>
    <div class="app-shell" id="appShell"style="overflow: visible;">
        @include('ManagementSystemViews.UserViews.Layouts.aside')

        <div class="page-wrap">
            <div class="header">
                <div class="order-history-container">
                    <h2 class="history-title">Order History</h2>

                    <form action="{{ route('user.pos.order.history') }}" method="GET" class="filter-form">
                        <div class="left-section">
                            <div class="search-box" style="position: relative;">
                                <i class="bi bi-search"></i>
                                <input type="text" id="orderSearchInput" name="search"
                                    placeholder="Search Order No or Status..." value="{{ request('search') }}"
                                    autocomplete="off">
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
                </div>
            </div>

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
                            <tr class="order-row" data-order-no="{{ $order->order_no }}"
                                data-status="{{ $order->status }}" data-customer="{{ $order->customer_no }}">

                                <td><input type="checkbox"></td>

                                <td>
                                    <a href="#" class="order-link">#{{ $order->order_no }}</a>
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
                                    <a href="{{ route('user.pos.order.download', $order->id) }}"
                                        class="btn-download text-decoration-none">
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


            </div>
            {{-- Pagination --}}
            <div class="pagination-container">
                @if ($orders->hasPages())
                    {{ $orders->links('vendor.pagination.custom-pos') }}
                @endif
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
