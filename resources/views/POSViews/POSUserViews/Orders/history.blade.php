@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'Order History')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pos/history.css') }}">
@endpush

@section('content')
    <div class="page-wrap">
        @include('ManagementSystemViews.UserViews.Layouts.header_mobile')
        @include('ManagementSystemViews.UserViews.Layouts.footer')

        <div class="header">
            <div class="order-history-container">
                <h2 class="history-title">Order History</h2>
                <form action="{{ route('user.pos.order.history') }}" method="GET" class="filter-form">
                    <div class="left-section">
                        <div class="search-box" style="position: relative;">
                            <input type="text" id="orderSearchInput" name="search"
                                placeholder="Search Order No or Status..." value="{{ request('search') }}"
                                autocomplete="off">
                            <img src="{{ asset('images/pos/search.png') }}" alt="Search">
                            <div id="orderSuggestions" class="search-suggestions"></div>
                        </div>

                        <div class="status-tabs">
                            @php
                                $tabQuery = request()->except(['status', 'page']);
                            @endphp
                            @foreach (['All', 'Pending', 'On The Way', 'Delivered'] as $status)
                                <a href="{{ route('user.pos.order.history', array_merge($tabQuery, ['status' => $status])) }}"
                                    class="tab-btn {{ request('status', 'All') == $status ? 'active' : '' }}">
                                    {{ $status }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="right-section">
                        <div class="date-filter-wrapper">
                            <div id="actionBar" class="action-bar" style="display:none;">
                                <span id="selectedCount">Selected 0</span>
                                <button type="button" id="cancelSelection">Cancel</button>
                                <button type="button" id="deleteSelected" class="delete-btn">Delete</button>
                            </div>

                            <label for="dateInput" class="floating-label">Date</label>
                            <input type="date" name="date" id="dateInput" value="{{ request('date') }}"
                                onchange="this.form.submit()">
                            <img src="{{ asset('images/pos/icon.png') }}" class="calendar-custom-img" alt="calendar">
                        </div>
                    </div>
                </form>
                <form id="deleteForm" method="POST" action="{{ route('user.pos.order.deleteMultiple') }}">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>

        <!-- PHONE-ONLY CARD LIST -->
        <div class="mobile-order-list">
            @forelse($orders as $order)
                <div class="order-card-mobile" data-detail-url="{{ route('user.pos.order.show', $order->id) }}">
                    <div class="order-card-img">
                        <img src="{{ $order->image_url ?? asset('images/no-image.png') }}"
                            alt="Order" loading="lazy"
                            onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';">
                    </div>

                    <div class="order-card-info">
                        <div class="order-card-id">
                            <span class="label">Order ID :</span> {{ $order->order_no }}
                        </div>
                        <div class="order-card-price">${{ number_format($order->total_amount, 2) }}</div>
                        <div class="order-card-date">
                            Order date: {{ optional($order->created_at)->format('d F Y') }}
                        </div>
                    </div>

                    <div class="order-card-side">
                        <span class="status-pill {{ strtolower(str_replace(' ', '-', $order->status)) }}">
                            {{ ucfirst($order->status) }}
                        </span>

                        <a href="{{ route('user.pos.order.download', $order->id) }}"
                            class="mobile-download-btn"
                            onclick="event.stopPropagation();"
                            title="Download invoice">
                            <i class="bi bi-download"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="empty-box">No orders found.</div>
            @endforelse
        </div>

        <!-- DESKTOP + TABLET TABLE -->
        <div class="custom-table-card">
            <table class="table">
                <thead>
                    <tr>
                        <th width="50"><input type="checkbox" id="selectAll"></th>
                        <th width="40">#</th>
                        <th>Order</th>
                        <th></th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-end">Invoice</th>
                    </tr>
                </thead>

                <tbody id="orderTableBody">
                    @php
                        $companyName = auth()->user()->local_name ?? auth()->user()->name ?? 'U';
                        $companyInitial = strtoupper(mb_substr(trim($companyName), 0, 1)) ?: 'U';
                    @endphp

                    @forelse($orders as $order)
                        <tr class="order-row" data-order-no="{{ $order->order_no }}" data-status="{{ $order->status }}"
                            data-customer="{{ $order->customer_no }}"
                            data-detail-url="{{ route('user.pos.order.show', $order->id) }}"
                            data-track-url="{{ route('user.pos.order.bc-status', $order->bc_document_no ?: $order->id) }}">

                            <td><input type="checkbox" class="rowCheckbox" value="{{ $order->id }}"></td>
                            <td>{{ $orders->firstItem() + $loop->index }}</td>
                            <td>
                                <a href="{{ route('user.pos.order.show', $order->id) }}"
                                    class="order-link">#{{ $order->order_no }}</a>
                            </td>
                            <td>
                                <div class="order-image">
                                    @if(!empty($companyImage))
                                        <img
                                            src="{{ $companyImage }}"
                                            alt="{{ $companyName }}"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                        >
                                        <div class="order-image-fallback" style="display:none;">{{ $companyInitial }}</div>
                                    @else
                                        <div class="order-image-fallback">{{ $companyInitial }}</div>
                                    @endif
                                </div>
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
                            <td colspan="8" class="text-center">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION BAR --}}
        <div class="pagination-container">
            <form method="GET" action="{{ route('user.pos.order.history') }}" class="pager-size-form">
                @foreach (request()->except(['limit', 'page']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach

                <span>Show</span>
                <select id="orderLimitSelect" name="limit" onchange="this.form.submit()">
                    @foreach ([10, 20, 50, 100] as $size)
                        <option value="{{ $size }}" {{ (int) request('limit', 10) === $size ? 'selected' : '' }}>
                            {{ $size }}
                        </option>
                    @endforeach
                </select>
                <span>orders</span>

                @if ($orders->onFirstPage())
                    <span class="pager-page-btn disabled">Previous</span>
                @else
                    <a class="pager-page-btn" href="{{ $orders->previousPageUrl() }}">Previous</a>
                @endif

                <span class="pager-page-info">Page {{ $orders->currentPage() }} of {{ $orders->lastPage() }}</span>

                @if ($orders->hasMorePages())
                    <a class="pager-page-btn" href="{{ $orders->nextPageUrl() }}">Next</a>
                @else
                    <span class="pager-page-btn disabled">Next</span>
                @endif
            </form>

            <div class="pager-result-count">
                Showing <strong>{{ $orders->count() }}</strong> of <strong>{{ $orders->total() }}</strong> orders
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const selectAll = document.getElementById('selectAll');
        const rowCheckboxes = document.querySelectorAll('.rowCheckbox');
        const actionBar = document.getElementById('actionBar');
        const selectedCount = document.getElementById('selectedCount');
        const cancelBtn = document.getElementById('cancelSelection');

        selectAll.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => cb.checked = this.checked);
            updateSelectionUI();
        });

        rowCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateSelectionUI);
        });

        function updateSelectionUI() {
            let checked = document.querySelectorAll('.rowCheckbox:checked').length;
            if (checked > 0) {
                actionBar.style.display = 'flex';
                selectedCount.textContent = `Selected ${checked}`;
            } else {
                actionBar.style.display = 'none';
            }
            selectAll.checked = checked === rowCheckboxes.length;
        }

        cancelBtn.addEventListener('click', function() {
            rowCheckboxes.forEach(cb => cb.checked = false);
            selectAll.checked = false;
            updateSelectionUI();
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById('orderSearchInput');
            const searchSuggestions = document.getElementById('orderSuggestions');
            const orderRows = document.querySelectorAll('.order-row');

            const allOrders = Array.from(orderRows).map(row => ({
                orderNo: row.dataset.orderNo || '',
                status: row.dataset.status || '',
                element: row
            }));

            function displayStatus(value) {
                return String(value || 'pending').replaceAll('-', ' ').replace(/\b\w/g, char => char.toUpperCase());
            }

            function statusClass(value) {
                return String(value || 'pending').toLowerCase().replaceAll(' ', '-');
            }

            function isFinalStatus(value) {
                return ['delivery', 'delivered', 'cancelled', 'canceled', 'failed'].includes(String(value || '').toLowerCase());
            }

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.trim().toLowerCase();

                if (searchTerm.length === 0) {
                    searchSuggestions.classList.remove('active');
                    orderRows.forEach(row => row.style.display = '');
                    return;
                }

                const filtered = allOrders.filter(order =>
                    order.orderNo.toLowerCase().includes(searchTerm) ||
                    order.status.toLowerCase().includes(searchTerm)
                );

                if (filtered.length > 0) {
                    searchSuggestions.innerHTML = filtered.slice(0, 5).map((order) => `
                    <div class="suggestion-item" onclick="selectOrderSuggestion('${order.orderNo}')">
                        <strong>#${order.orderNo}</strong> - <small>${order.status}</small>
                    </div>
                `).join('');
                    searchSuggestions.classList.add('active');
                } else {
                    searchSuggestions.classList.remove('active');
                }

                orderRows.forEach(row => {
                    const isMatch = filtered.some(f => f.element === row);
                    row.style.display = isMatch ? '' : 'none';
                });
            });

            window.selectOrderSuggestion = function(orderNo) {
                searchInput.value = orderNo;
                orderRows.forEach(row => {
                    row.style.display = (row.dataset.orderNo === orderNo) ? '' : 'none';
                });
                searchSuggestions.classList.remove('active');
            };

            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const term = this.value.trim().toLowerCase();
                    orderRows.forEach(row => {
                        const match = row.dataset.orderNo.toLowerCase().includes(term) ||
                            row.dataset.status.toLowerCase().includes(term);
                        row.style.display = match ? '' : 'none';
                    });
                    searchSuggestions.classList.remove('active');
                }
            });

            document.addEventListener('click', (e) => {
                if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
                    searchSuggestions.classList.remove('active');
                }
            });

            orderRows.forEach((row) => {
                row.style.cursor = 'pointer';
                row.addEventListener('click', (e) => {
                    if (e.target.closest('input[type="checkbox"], .btn-download, .btn-download *, .order-link')) {
                        return;
                    }
                    const url = row.dataset.detailUrl;
                    if (url) window.location.href = url;
                });
            });

            // Phone card list click-through
            document.querySelectorAll('.order-card-mobile').forEach(card => {
                card.addEventListener('click', () => {
                    const url = card.dataset.detailUrl;
                    if (url) window.location.href = url;
                });
            });

            async function trackRow(row) {
                const badge = row?.querySelector('.status-badge');
                const trackUrl = row?.dataset.trackUrl;

                if (!row || !badge || !trackUrl || row.dataset.tracking === '1' || isFinalStatus(row.dataset.status)) {
                    return;
                }

                row.dataset.tracking = '1';

                try {
                    const refreshUrl = new URL(trackUrl, window.location.origin);
                    refreshUrl.searchParams.set('_', Date.now().toString());

                    const response = await fetch(refreshUrl.toString(), {
                        cache: 'no-store',
                        headers: { 'Accept': 'application/json', 'Cache-Control': 'no-cache' }
                    });
                    const result = await response.json();

                    if (!response.ok || !result.success) return;

                    const nextStatus = result.data?.tracking_status || result.data?.local_status || row.dataset.status;
                    row.dataset.status = nextStatus;
                    badge.className = 'status-badge ' + statusClass(nextStatus);
                    badge.textContent = displayStatus(nextStatus);

                    if (['confirmed', 'on-the-way'].includes(String(nextStatus || '').toLowerCase())) {
                        window.setTimeout(() => trackRow(row), 5000);
                        window.setTimeout(() => trackRow(row), 15000);
                    }
                } catch (error) {
                    return;
                } finally {
                    row.dataset.tracking = '0';
                }
            }

            function refreshVisibleRows() {
                if (document.hidden) return;
                Array.from(orderRows)
                    .filter(row => row.style.display !== 'none' && !isFinalStatus(row.dataset.status))
                    .slice(0, 5)
                    .forEach((row, index) => {
                        window.setTimeout(() => trackRow(row), index * 1200);
                    });
            }

            refreshVisibleRows();
            window.setInterval(refreshVisibleRows, 10000);
        });

        const deleteBtn = document.getElementById('deleteSelected');
        deleteBtn.addEventListener('click', function() {
            let selected = document.querySelectorAll('.rowCheckbox:checked');
            if (selected.length === 0) {
                alert('Please select at least one order');
                return;
            }
            if (!confirm('Are you sure you want to delete selected orders?')) return;

            let form = document.getElementById('deleteForm');
            form.innerHTML = `@csrf @method('DELETE')`;

            selected.forEach(cb => {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                form.appendChild(input);
            });

            form.submit();
        });
    </script>
@endpush