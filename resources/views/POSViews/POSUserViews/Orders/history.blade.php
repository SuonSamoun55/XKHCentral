@extends('Layout.POSUser.app')

@section('title', 'Order History')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/views/POSViews/POSUserViews/Orders/history.css') }}">
@endpush

@section('content')
    <div class="page-wrap">
        @include('Layout.POSUser.header_mobile')
        @include('Layout.POSUser.footer')
        <div class="header">
            <div class="order-history-container">
                <h2 class="history-title">Order History</h2>
                <div id="actionBar" class="action-bar">
                    <span id="selectedCount">Selected 0</span>
                    <button type="button" id="cancelSelection">Cancel</button>
                    <button type="submit" form="deleteForm" class="delete-btn">Delete</button>
                </div>

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
                            @foreach (['All', 'Pending', 'On The Way', 'Delivered', 'Cancel'] as $status)
                                <a href="{{ route('user.pos.order.history', array_merge($tabQuery, ['status' => $status])) }}"
                                    class="tab-btn {{ request('status', 'All') == $status ? 'active' : '' }}">
                                    {{ $status }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="right-section">
                        <div class="date-filter-wrapper">

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

        @if ($orders->isEmpty())
            <div class="order-empty-state">
                <img src="{{ asset('images/pos/UserOrderHistory.png') }}" alt="No orders" class="order-empty-state-img">
            </div>
        @else
            <div class="mobile-order-list">
                @foreach ($orders as $order)
                    <div id="order-item-{{ $order->id }}" class="order-card-mobile" data-order-no="{{ $order->order_no }}" data-detail-url="{{ route('user.pos.order.show', $order->id) }}">
                        <div class="order-card-img item-thumb-stack">
                            @foreach ($order->items->take(3) as $index => $oi)
                                @php
                                    $resolveThumb = fn ($path) => $path
                                        ? (str_starts_with($path, 'http') ? $path : asset($path))
                                        : null;

                                    $thumb = $resolveThumb(optional($oi->itemVariant)->image_url)
                                        ?? $resolveThumb($oi->item->custom_image_url ?? null)
                                        ?? $resolveThumb($oi->item->image_url ?? null)
                                        ?? $resolveThumb($oi->item->image ?? null);
                                @endphp
                                <div class="item-thumb" style="z-index: {{ 10 - $index }};">
                                    <img
                                        src="{{ $thumb ?? asset('images/no-image.png') }}"
                                        alt="{{ $oi->item_name ?? 'Item' }}"
                                        loading="lazy"
                                        onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';"
                                    >
                                </div>
                            @endforeach
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
                @endforeach
            </div>

            <!-- DESKTOP + TABLET TABLE -->
            <div class="custom-table-card">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="44"><input type="checkbox" id="selectAll"></th>
                            <th>Order</th>
                            <th>Date</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Invoice</th>
                            <th class="text-center">Items</th>
                        </tr>
                    </thead>

                    <tbody id="orderTableBody">
                        @foreach ($orders as $order)
                            @php
                                $orderItems = $order->items->take(3);
                            @endphp
                            <tr id="order-item-{{ $order->id }}" class="order-row" data-order-no="{{ $order->order_no }}" data-status="{{ $order->status }}"
                                data-customer="{{ $order->customer_no }}"
                                data-detail-url="{{ route('user.pos.order.show', $order->id) }}"
                                data-track-url="{{ route('user.pos.order.bc-status', $order->bc_document_no ?: $order->id) }}">

                                <td><input type="checkbox" class="rowCheckbox" value="{{ $order->id }}"></td>
                                <td>
                                    <a href="{{ route('user.pos.order.show', $order->id) }}"
                                        class="order-link">#{{ $order->order_no }}</a>
                                </td>
                                <td>
                                    <div class="date-text">
                                        {{ optional($order->created_at)->format('m/d/y') }}
                                    </div>
                                    <div class="time-text">
                                        at {{ $order->created_at->format('h:i A') }}
                                    </div>
                                </td>
                                <td class="total-text">
                                    $ {{ number_format($order->total_amount, 2) }}
                                </td>
                                <td>
                                    <span class="status-badge {{ strtolower(str_replace(' ', '-', $order->status)) }}">
                                        {{ $order->status === 'on-the-way' ? 'On the way' : ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('user.pos.order.download', $order->id) }}"
                                        class="btn-download text-decoration-none">
                                        Download <i class="bi bi-download"></i>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <div class="item-thumb-stack">
                                        @foreach ($orderItems as $index => $oi)
                                            @php
                                            
                                                $tblResolveThumb = fn ($path) => $path
                                                    ? (str_starts_with($path, 'http') ? $path : asset($path))
                                                    : null;

                                                $thumb = $tblResolveThumb(optional($oi->itemVariant)->image_url)
                                                    ?? $tblResolveThumb($oi->item->custom_image_url ?? null)
                                                    ?? $tblResolveThumb($oi->item->image_url ?? null)
                                                    ?? $tblResolveThumb($oi->item->image ?? null);
                                            @endphp
                                            <div class="item-thumb" style="z-index: {{ 10 - $index }};">
                                                <img
                                                    src="{{ $thumb ?? asset('images/no-image.png') }}"
                                                    alt="{{ $oi->item_name ?? 'Item' }}"
                                                    loading="lazy"
                                                    onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';"
                                                >
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
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
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        const SCROLL_STORAGE_KEY = 'posOrderHistoryScroll';

        let lastClickedOrderNo = null;

        function saveScrollPosition(orderNo) {
            try {
                const tableCard = document.querySelector('.custom-table-card');
                const payload = {
                    table: tableCard ? tableCard.scrollTop : 0,
                    page: window.scrollY || window.pageYOffset || 0,
                    orderNo: orderNo || lastClickedOrderNo || null,
                    savedAt: Date.now(),
                };
                localStorage.setItem(SCROLL_STORAGE_KEY, JSON.stringify(payload));
                console.log('[pos-scroll] saved', payload);
            } catch (e) {
                console.log('[pos-scroll] SAVE FAILED', e);
            }
        }

        function highlightReturnedItem(target) {
            const highlightClass = target.classList.contains('order-row')
                ? 'order-row-highlight'
                : 'order-card-highlight';

            target.classList.add(highlightClass);
            target.addEventListener('animationend', () => {
                target.classList.remove(highlightClass);
            }, { once: true });
        }

        let hasRestoredScroll = false;

        function restoreScrollPosition() {
            if (hasRestoredScroll) {
                console.log('[pos-scroll] restore skipped, already ran');
                return;
            }

            try {
                const raw = localStorage.getItem(SCROLL_STORAGE_KEY);
                console.log('[pos-scroll] restore check, raw value:', raw);
                if (!raw) return;

                const { table, page, orderNo, savedAt } = JSON.parse(raw);

                // Ignore stale entries from a much earlier visit (>1 hour)
                if (savedAt && Date.now() - savedAt > 60 * 60 * 1000) {
                    console.log('[pos-scroll] entry too old, discarding');
                    localStorage.removeItem(SCROLL_STORAGE_KEY);
                    return;
                }

                const applyRestore = () => {
                    if (orderNo) {
                        const target = document.querySelector(
                            `[data-order-no="${CSS.escape(orderNo)}"]`
                        );
                        console.log('[pos-scroll] looking for orderNo', orderNo, '-> found:', !!target);

                        if (target) {
                            target.scrollIntoView({ block: 'center' });
                            highlightReturnedItem(target);
                            hasRestoredScroll = true;
                            console.log('[pos-scroll] scrolled to item', orderNo);
                            return;
                        }
                    }
                    const tableCard = document.querySelector('.custom-table-card');
                    if (tableCard && table) {
                        tableCard.scrollTop = table;
                    }
                    if (page) {
                        window.scrollTo(0, page);
                    }
                    hasRestoredScroll = true;
                    console.log('[pos-scroll] fell back to raw offsets', { table, page });
                };
                if (document.readyState === 'complete') {
                    applyRestore();
                } else {
                    window.setTimeout(applyRestore, 50);
                }
            } catch (e) {
                console.log('[pos-scroll] RESTORE FAILED', e);
            }
        }
        document.addEventListener('DOMContentLoaded', () => {
            console.log('[pos-scroll] DOMContentLoaded fired');
            restoreScrollPosition();
        });
        window.addEventListener('load', () => {
            console.log('[pos-scroll] load fired');
            restoreScrollPosition();
        });
        window.addEventListener('pageshow', (e) => {
            console.log('[pos-scroll] pageshow fired, persisted:', e.persisted);
            restoreScrollPosition();
        });
        window.addEventListener('pagehide', () => saveScrollPosition());
    </script>
    <script>

        const selectAll = document.getElementById('selectAll');
        const rowCheckboxes = document.querySelectorAll('.rowCheckbox');
        const actionBar = document.getElementById('actionBar');
        const selectedCount = document.getElementById('selectedCount');
        const cancelBtn = document.getElementById('cancelSelection');
        const deleteForm = document.getElementById('deleteForm');

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                rowCheckboxes.forEach(cb => cb.checked = this.checked);
                updateSelectionUI();
            });

            rowCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateSelectionUI);
            });

            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    rowCheckboxes.forEach(cb => cb.checked = false);
                    selectAll.checked = false;
                    updateSelectionUI();
                });
            }
        }

        function updateSelectionUI() {
            const checked = document.querySelectorAll('.rowCheckbox:checked').length;

            if (actionBar) {
                actionBar.style.display = checked > 0 ? 'flex' : 'none';
            }
            if (selectedCount) {
                selectedCount.textContent = `Selected ${checked}`;
            }
            if (selectAll) {
                selectAll.checked = rowCheckboxes.length > 0 && checked === rowCheckboxes.length;
            }
        }

        if (deleteForm) {
            deleteForm.addEventListener('submit', function(e) {
                deleteForm.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());

                const checkedIds = Array.from(document.querySelectorAll('.rowCheckbox:checked'))
                    .map(cb => cb.value);

                if (checkedIds.length === 0) {
                    e.preventDefault();
                    return;
                }

                checkedIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = id;
                    deleteForm.appendChild(input);
                });
            });
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById('orderSearchInput');
            const searchSuggestions = document.getElementById('orderSuggestions');
            const orderRows = document.querySelectorAll('.order-row');

            if (!searchInput || !searchSuggestions) return;

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
                    lastClickedOrderNo = row.dataset.orderNo || null;
                    saveScrollPosition(lastClickedOrderNo);
                    const url = row.dataset.detailUrl;
                    if (url) window.location.href = url;
                });

                const link = row.querySelector('.order-link');
                if (link) {
                    link.addEventListener('click', () => {
                        lastClickedOrderNo = row.dataset.orderNo || null;
                        saveScrollPosition(lastClickedOrderNo);
                    });
                }
            });

            // Phone card list click-through
            document.querySelectorAll('.order-card-mobile').forEach(card => {
                card.addEventListener('click', () => {
                    lastClickedOrderNo = card.dataset.orderNo || null;
                    saveScrollPosition(lastClickedOrderNo);
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
    </script>
@endpush