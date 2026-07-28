@extends('ManagementSystemViews.UserViews.Layouts.app')
@section('title', 'Notifications')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('/css/views/POSViews/POSUserViews/Notifications/notification.css') }}" />
@endpush

@section('content')

    <div class="page-wrap">
        <div class="main-content">
            <div class="header">
                @include('ManagementSystemViews.UserViews.Layouts.header_mobile')
                @include('ManagementSystemViews.UserViews.Layouts.footer')
                <div class="notification-header">
                    <h2 class="page-title">Notification</h2>
                </div>
                <div class="search-date-container">
                    <div class="top-actions">
                        <a href="{{ route('user.chat.index') }}" class="btn-send">
                            <i class="bi bi-inbox icon-img"></i> Inbox
                        </a>
                    </div>

                    <div class="date-filter-wrapper">
                        <label for="dateInput" class="floating-label">Date</label>
                        <input type="date" name="date" id="dateInput" value="{{ request('date') }}">
                        <img src="{{ asset('images/pos/icon.png') }}" class="calendar-custom-img" alt="calendar">
                    </div>
                </div>

                {{-- Tabs --}}
                <div class="tabs">
                    <div class="tab active" data-tab="orderNotification">
                        <span class="tab-icon-wrap">
                            <img class="icon-img" src="{{ asset('images/pos/Notifi_cart icon.png') }}" alt="Order Notification">
                            @if ($orderUnreadCount > 0)
                                <span class="tab-count-badge">{{ $orderUnreadCount }}</span>
                            @endif
                        </span>
                        Order Notification
                    </div>

                    <div class="tab" data-tab="adminMessage">
                        <span class="tab-icon-wrap">
                            <i class="bi bi-person-circle icon-img"></i>
                            @if ($adminUnreadCount > 0)
                                <span class="tab-count-badge">{{ $adminUnreadCount }}</span>
                            @endif
                        </span>
                        Admin Message
                    </div>

                    <button type="button" id="deleteSelectedBtn" class="desktop-delete-selected-btn" onclick="deleteSelectedNotifications()"
                        title="Delete selected" style="display:none;">
                        <i class="bi bi-trash icon-img"></i>
                        <span>Delete Selected</span>
                    </button>
                </div>
            </div>

            <div class="mobile-tabs">
                <a href="{{ route('user.chat.index') }}" class="mt-pill">
                    <i class="bi bi-inbox icon-img"></i>
                    Inbox
                </a>

                <div class="mf-date" id="mfDate">
                    <img src="{{ asset('images/pos/icon-calendar.png') }}" class="icon-img" alt="Calendar">
                    <input type="date" id="mobileDateInput" value="{{ request('date') }}"
                        onchange="
            document.getElementById('dateInput').value = this.value;
            document.getElementById('dateInput').dispatchEvent(new Event('change'));
         ">
                </div>

                <label class="mt-switch">
                    <input type="checkbox" id="mobileUnreadFilter" onchange="filterUnreadMobile()">
                    <span></span>
                </label>
            </div>

            <div class="mobile-sub-tabs">
                <span class="active" data-mobile-subtab="orderNotification">Order Notification ({{ $orderUnreadCount }})</span>
                <span data-mobile-subtab="adminMessage">Admin Message ({{ $adminUnreadCount }})</span>
            </div>

            {{-- Notification List --}}
            <div id="orderNotification" class="tab-content">
                <div class="notification-table">
                    <div class="notification-lists">
                        @forelse($orderNotifications as $notification)
                            <div class="table-row {{ !$notification->is_read ? 'selected' : '' }}"
                                data-id="{{ $notification->id }}"
                                style="cursor:pointer;"
                                onclick="goToNotification({{ $notification->id }})">

                                {{-- LEFT --}}
                                <div class="row-left">
                                    <input type="checkbox" class="checkboxs notification-select"
                                        name="notification_ids[]" value="{{ $notification->id }}"
                                        onclick="event.stopPropagation();">

                                    <button type="button" class="star-btn" onclick="event.stopPropagation();" title="Favorite">
                                        <i class="bi bi-star icon-img"></i>
                                    </button>

                                    <span class="tag">
                                        <span class="avatar notification-type-icon">
                                            @if ($notification->display_icon === 'admin')
                                                <i class="bi bi-person-circle icon-img"></i>
                                            @elseif ($notification->display_icon === 'global')
                                                <i class="bi bi-percent icon-img"></i>
                                            @elseif ($notification->display_icon === 'cancelled')
                                                <i class="bi bi-x-circle icon-img"></i>
                                            @elseif ($notification->display_icon === 'confirmed')
                                                <i class="bi bi-check-circle icon-img"></i>
                                            @else
                                                <i class="bi bi-truck icon-img"></i>
                                            @endif
                                        </span>
                                    </span>

                                    <span class="status">
                                        @if ($notification->is_admin_notification)
                                            Admin Message
                                        @elseif ($notification->type === 'global_message')
                                            Global Message
                                        @else
                                            <strong class="{{ !$notification->is_read ? 'fw-bold' : '' }}">
                                                {{ $notification->title }}
                                            </strong>
                                        @endif
                                    </span>
                                </div>

                                {{-- CENTER --}}
                                <div class="row-center">
                                    <span class="desktop-subject">
                                        {{ $notification->display_subject }}
                                    </span>
                                    <span class="desktop-separator">-</span>
                                    <span class="desktop-message">{{ Str::limit($notification->message, 118) }}</span>
                                    <span class="notification-meta d-none">
                                        {{ $notification->created_at->format('D d/m/Y') }}
                                        <span>{{ $notification->created_at->format('h:i A') }}</span>
                                    </span>

                                    @if ($notification->has_attachment)
                                        <a href="{{ route('user.notifications.show', $notification->id) }}"
                                            onclick="event.stopPropagation();" style="color:#10c4d4; font-weight:600;">
                                            attachment
                                        </a>
                                    @endif
                                </div>

                                {{-- RIGHT --}}
                                <div class="row-right">
                                    <span class="row-date">{{ $notification->created_at->format('h:i') }}
                                        <span class="row-day">{{ $notification->created_at->format('m/d/Y') }}</span>
                                    </span>

                                    <div class="row-actions" onclick="event.stopPropagation();">
                                        <button type="button" title="Delete"
                                            onclick="deleteNotificationById({{ $notification->id }})">
                                            <i class="bi bi-trash icon-img"></i>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-inbox icon-img"></i>
                                <p>You have no notifications yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="notification-list mobile-list-active" data-mobile-list="orderNotification">
                @forelse($orderNotifications as $notification)
                    <div class="notification-card {{ !$notification->is_read ? 'unread' : '' }} type-{{ $notification->type }}"
                        data-id="{{ $notification->id }}" style="cursor: pointer;"
                        onclick="goToNotification({{ $notification->id }})">

                        <div class="notification-content">
                            <div class="avatar notification-type-icon">
                                @if ($notification->display_icon === 'admin')
                                    <i class="bi bi-person-circle icon-img"></i>
                                @elseif ($notification->display_icon === 'global')
                                    <i class="bi bi-percent icon-img"></i>
                                @elseif ($notification->display_icon === 'cancelled')
                                    <i class="bi bi-x-circle icon-img"></i>
                                @elseif ($notification->display_icon === 'confirmed')
                                    <i class="bi bi-check-circle icon-img"></i>
                                @else
                                    <i class="bi bi-truck icon-img"></i>
                                @endif
                            </div>

                            <div class="notification-text">
                                <div class="notification-title-row">
                                    <div class="notification-title">
                                        {{ $notification->title }}
                                    </div>
                                    @if (!$notification->is_read)
                                        <span class="unread-dot"></span>
                                    @endif
                                </div>

                                <div class="notification-desc">
                                    {{ Str::limit($notification->message, 60) }}
                                </div>

                                @if ($notification->has_attachment)
                                    <a href="{{ route('user.notifications.show', $notification->id) }}"
                                        class="notification-attachment" onclick="event.stopPropagation();">
                                        attachment
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="notification-side-meta">
                            <span class="row-date">{{ $notification->created_at->format('H:i') }}</span>
                            <span class="row-day">{{ $notification->created_at->format('m/d/Y') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-inbox icon-img"></i>
                        <p>You have no notifications yet.</p>
                    </div>
                @endforelse
            </div>

            <div class="pagination-container" id="paginationContainer">
                @if ($notifications->hasPages())
                    {{ $notifications->links('vendor.pagination.custom-pos') }}
                @endif
            </div>

            {{-- ADMIN MESSAGE PAGE --}}
            <div id="adminMessage" class="tab-content" style="display:none;">
                <div class="notification-table">
                    <div class="notification-lists">
                        @forelse($adminMessagesDisplay as $notification)
                            <div class="table-row {{ !$notification->is_read ? 'selected' : '' }}"
                                data-id="{{ $notification->id }}"
                                style="cursor:pointer;"
                                onclick="goToNotification({{ $notification->id }})">

                                <div class="row-left">
                                    <input type="checkbox" class="checkboxs notification-select"
                                        name="notification_ids[]" value="{{ $notification->id }}"
                                        onclick="event.stopPropagation();">
                                    <button type="button" class="star-btn" onclick="event.stopPropagation();" title="Favorite">
                                        <i class="bi bi-star icon-img"></i>
                                    </button>
                                    <span class="tag">
                                        <span class="avatar notification-type-icon">
                                            @if ($notification->type === 'global_message')
                                                <img src="{{ asset('images/pos/icon-megaphone.png') }}" class="icon-img" alt="Global Message">
                                            @else
                                                <i class="bi bi-chat-left-text icon-img"></i>
                                            @endif
                                        </span>
                                    </span>
                                    <span class="status">
                                        {{ $notification->display_status }}
                                    </span>
                                </div>

                                <div class="row-center">
                                    <span class="desktop-subject">
                                        {{ $notification->display_status }}
                                    </span>
                                    <span class="desktop-separator">-</span>
                                    <span class="desktop-message">{{ Str::limit($notification->message, 118) }}</span>
                                    <span class="notification-meta d-none">
                                        {{ $notification->created_at->format('D d/m/Y') }}
                                        <span>{{ $notification->created_at->format('h:i A') }}</span>
                                    </span>
                                </div>

                                <div class="row-right">
                                    <span class="row-date">{{ $notification->created_at->format('h:i') }}
                                        <span class="row-day">{{ $notification->created_at->format('m/d/Y') }}</span>
                                    </span>
                                    <div class="row-actions" onclick="event.stopPropagation();">
                                        <button type="button" title="Delete"
                                            onclick="deleteNotificationById({{ $notification->id }})">
                                            <i class="bi bi-trash icon-img"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-chat-left-text icon-img"></i>
                                <p>You have no admin messages yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Mobile Admin Message List --}}
            <div class="notification-list" id="mobileAdminMessage" data-mobile-list="adminMessage">
                @forelse($adminMessagesDisplay as $notification)
                    <div class="notification-card {{ !$notification->is_read ? 'unread' : '' }} type-{{ $notification->type }}"
                        data-id="{{ $notification->id }}" style="cursor: pointer;"
                        onclick="goToNotification({{ $notification->id }})">

                        <div class="notification-content">
                            <div class="avatar notification-type-icon">
                                @if ($notification->type === 'global_message')
                                    <img src="{{ asset('images/pos/icon-megaphone.png') }}" class="icon-img" alt="Global Message">
                                @else
                                    <i class="bi bi-chat-left-text icon-img"></i>
                                @endif
                            </div>

                            <div class="notification-text">
                                <div class="notification-title-row">
                                    <div class="notification-title">
                                        {{ $notification->title }}
                                    </div>
                                    @if (!$notification->is_read)
                                        <span class="unread-dot"></span>
                                    @endif
                                </div>

                                <div class="notification-desc">
                                    {{ Str::limit($notification->message, 60) }}
                                </div>
                            </div>
                        </div>

                        <div class="notification-side-meta">
                            <span class="row-date">{{ $notification->created_at->format('H:i') }}</span>
                            <span class="row-day">{{ $notification->created_at->format('m/d/Y') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-chat-left-text icon-img"></i>
                        <p>You have no admin messages yet.</p>
                    </div>
                @endforelse
            </div>

            {{-- ORDER NOTIFICATION TOOLBAR --}}
            <div class="desktop-notification-toolbar" data-pager="orderNotification">
                <form method="GET" action="{{ route('user.notifications') }}" class="pager-size-form">
                    @foreach (request()->except(['limit', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <span>Show</span>

                    <select name="limit" onchange="this.form.submit()">
                        @foreach ([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ (int) request('limit', 10) === $size ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>

                    <span>items</span>

                    @if ($notifications->onFirstPage())
                        <span class="pager-page-btn disabled">Previous</span>
                    @else
                        <a class="pager-page-btn" href="{{ $notifications->previousPageUrl() }}">Previous</a>
                    @endif

                    <span class="pager-page-info">Page {{ $notifications->currentPage() }} of {{ $notifications->lastPage() }}</span>

                    @if ($notifications->hasMorePages())
                        <a class="pager-page-btn" href="{{ $notifications->nextPageUrl() }}">Next</a>
                    @else
                        <span class="pager-page-btn disabled">Next</span>
                    @endif
                </form>

                <div class="desktop-result-count" data-result-count="orderNotification">
                    Showing <strong>{{ $notifications->count() }}</strong> of <strong>{{ $notifications->total() }}</strong> items
                </div>
            </div>

            {{-- ADMIN MESSAGE TOOLBAR --}}
            <div class="desktop-notification-toolbar" data-pager="adminMessage" style="display:none;">
                <form method="GET" action="{{ route('user.notifications') }}" class="pager-size-form">
                    @foreach (request()->except(['limit', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <span>Show</span>

                    <select name="limit" onchange="this.form.submit()">
                        @foreach ([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ (int) request('limit', 10) === $size ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>

                    <span>items</span>

                    @if ($adminMessages->onFirstPage())
                        <span class="pager-page-btn disabled">Previous</span>
                    @else
                        <a class="pager-page-btn" href="{{ $adminMessages->previousPageUrl() }}">Previous</a>
                    @endif

                    <span class="pager-page-info">Page {{ $adminMessages->currentPage() }} of {{ $adminMessages->lastPage() }}</span>

                    @if ($adminMessages->hasMorePages())
                        <a class="pager-page-btn" href="{{ $adminMessages->nextPageUrl() }}">Next</a>
                    @else
                        <span class="pager-page-btn disabled">Next</span>
                    @endif
                </form>

                <div class="desktop-result-count" data-result-count="adminMessage">
                    Showing <strong>{{ $adminMessages->count() }}</strong> of <strong>{{ $adminMessages->total() }}</strong> items
                </div>
            </div>

            {{-- MOBILE PAGINATION --}}
            <div class="mobile-pagination">
                <div class="mp-left">
                    {{ $notifications->firstItem() }} –
                    {{ $notifications->lastItem() }}
                    of {{ $notifications->total() }} Pages
                </div>

                <div class="mp-center">
                    <span>The page</span>
                    <select onchange="location = this.value;">
                        @for ($i = 1; $i <= $notifications->lastPage(); $i++)
                            <option value="{{ $notifications->url($i) }}"
                                {{ $notifications->currentPage() == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
        @include('ManagementSystemViews.UserViews.Layouts.footer')
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const tabs = document.querySelectorAll('.tab');
        const tabContents = document.querySelectorAll('.tab-content');
        const pagination = document.getElementById('paginationContainer');
        const pagers = document.querySelectorAll('[data-pager]');
        const resultCounts = document.querySelectorAll('[data-result-count]');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(item => item.classList.remove('active'));
                tabContents.forEach(content => { content.style.display = 'none'; });
                this.classList.add('active');

                const target = this.getAttribute('data-tab');
                document.getElementById(target).style.display = 'block';

                resultCounts.forEach(count => {
                    count.style.display = count.dataset.resultCount === target ? 'inline' : 'none';
                });

                pagers.forEach(pager => {
                    pager.style.display = pager.dataset.pager === target ? 'flex' : 'none';
                });

                pagination.style.display = target === 'orderNotification' ? 'block' : 'none';

                updateDeleteSelectedVisibility();
            });
        });

        // MOBILE SUB-TAB SWITCHING
        const mobileSubTabs = document.querySelectorAll('[data-mobile-subtab]');
        const mobileLists = document.querySelectorAll('[data-mobile-list]');

        function switchMobileSubTab(target) {
            mobileSubTabs.forEach(el => {
                el.classList.toggle('active', el.dataset.mobileSubtab === target);
            });
            mobileLists.forEach(el => {
                el.style.removeProperty('display');
                el.classList.toggle('mobile-list-active', el.dataset.mobileList === target);
            });
        }

        mobileSubTabs.forEach(el => {
            el.addEventListener('click', function() {
                switchMobileSubTab(this.dataset.mobileSubtab);
            });
        });

        switchMobileSubTab('orderNotification');

        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
        }

        // DELETE SELECTED BUTTON: hidden until at least one row is checked
        const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');

        function updateDeleteSelectedVisibility() {
            if (!deleteSelectedBtn) return;
            const anyChecked = document.querySelectorAll('.notification-select:checked').length > 0;
            deleteSelectedBtn.style.display = anyChecked ? 'flex' : 'none';
        }

        document.addEventListener('change', function(e) {
            if (e.target.classList && e.target.classList.contains('notification-select')) {
                updateDeleteSelectedVisibility();
            }
        });

        function checkedNotificationIds() {
            const activeTab = document.querySelector('.tab-content[style*="block"]') ||
                document.getElementById('orderNotification');

            return Array.from(activeTab.querySelectorAll('.notification-select:checked'))
                .map(input => input.value)
                .filter(Boolean);
        }

        function removeNotificationRows(ids) {
            ids.forEach(id => {
                document.querySelectorAll(`.notification-select[value="${id}"]`).forEach(input => {
                    input.closest('.table-row, .notification-card')?.remove();
                });
            });
            updateDeleteSelectedVisibility();
        }

        function deleteNotifications(ids) {
            if (!ids.length) {
                alert('Please select at least one message.');
                return;
            }

            if (!confirm('Delete selected message(s)?')) return;

            fetch('{{ route('user.notifications.deleteSelected') }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ notification_ids: ids }),
            })
                .then(response => {
                    if (!response.ok) throw new Error('Delete failed.');
                    removeNotificationRows(ids);
                })
                .catch(error => alert(error.message || 'Delete failed.'));
        }

        function deleteSelectedNotifications() {
            deleteNotifications(checkedNotificationIds());
        }

        function deleteNotificationById(id) {
            deleteNotifications([String(id)]);
        }

        function goToNotification(id) {
            window.location.href = `/pos-system/notifications/${id}`;
        }
    </script>

    <script>
        // Date filter (desktop input; mobile input mirrors into this one and dispatches 'change')
        const dateInput = document.getElementById('dateInput');

        if (dateInput) {
            dateInput.addEventListener('change', function() {
                const currentUrl = new URL(window.location.href);

                if (this.value) {
                    currentUrl.searchParams.set('date', this.value);
                } else {
                    currentUrl.searchParams.delete('date');
                }

                currentUrl.searchParams.set('tab', '{{ $tab }}');
                window.location.href = currentUrl.toString();
            });
        }

        // Mobile "unread" switch: no longer filters via URL. Instead:
        //  - ON  -> hide the date filter, mark all notifications as read
        //  - OFF -> show the date filter again
        function filterUnreadMobile() {
            const checkbox = document.getElementById('mobileUnreadFilter');
            const dateFilter = document.getElementById('mfDate');

            if (checkbox.checked) {
                if (dateFilter) dateFilter.classList.add('hidden');
                markAllNotificationsRead();
            } else {
                if (dateFilter) dateFilter.classList.remove('hidden');
            }
        }

        function markAllNotificationsRead() {
            fetch('{{ route('user.notifications.markAllRead') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
               
        }
    </script>
@endpush