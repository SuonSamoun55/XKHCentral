@extends('ManagementSystemViews.UserViews.Layouts.app')
@section('title', 'Notifications')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pos/notification.css') }}" />
@endpush

@section('content')
    <div class="page-wrap">
        <div class="main-content">
            <div class="header">


                {{-- MOBILE HEADER --}}
                <div class="mobile-notification-header">
                    <a href="{{ route('user.posinterface') }}" class="mn-btn">

                        <i class="bi bi-arrow-left"></i>
                    </a>

                    <div class="mn-title">Notification</div>

                    <a href="#" class="mn-btnx">
                    </a>

                </div>

                <div class="notification-header">
                    <h2 class="page-title">Notification</h2>
                </div>

                {{-- Search and Date --}}
                <div class="search-date-container">
                    <div class="top-actions">

                        <a href="{{ route('user.chat.index') }}" class="btn-send">
                            {{-- <i class="bi bi-send"></i> In Box --}}
                            <i class="bi bi-chat-left"></i> Inbox
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
                        <i class="bi bi-house-door-fill"></i>
                        Order Notification
                    </div>

                    <div class="tab" data-tab="adminMessage">
                        <i class="bi bi-chat-left-text"></i>
                        Admin Message
                    </div>

                    <button type="button" class="desktop-delete-selected-btn" onclick="deleteSelectedNotifications()"
                        title="Delete selected">
                        <i class="bi bi-trash-fill"></i>
                        <span>Delete Selected</span>
                    </button>
                </div>
            </div>

            <div class="mobile-tabs">
                <a href="{{ route('user.notifications') }}" class="mt-pill">
                    <i class="bi bi-inbox"></i>
                    Inbox
                </a>
                <button class="mt-icon" onclick="openNewMessage()">
                    <i class="bi bi-pencil"></i>
                </button>

                <button class="mt-icon" onclick="openAllContact()">
                    <i class="bi bi-archive"></i>
                </button>
                <label class="mt-switch">
                    <input type="checkbox" id="mobileUnreadFilter" onchange="filterUnreadMobile()">
                    <span></span>
                </label>

            </div>

            <div class="mobile-filter-row">

                <div class="mf-date">
                    <i class="bi bi-calendar3"></i>
                    <input type="date" id="mobileDateInput" value="{{ request('date') }}"
                        onchange="
            document.getElementById('dateInput').value = this.value;
            document.getElementById('dateInput').dispatchEvent(new Event('change'));
         ">
                </div>


            </div>
            <div class="mobile-sub-tabs">
                <span class="active">Order Notification ({{ $inboxCount }})</span>
                <span>Admin Message ({{ $globalMessageCount }})</span>
            </div>

            {{-- Notification List --}}
            <div id="orderNotification" class="tab-content">

                <div class="notification-table">
                    <div class="notification-lists">

                        @forelse($notifications->whereNotIn('type', ['admin_message', 'global_message']) as $notification)
                            <div class="table-row {{ !$notification->is_read ? 'selected' : '' }}"
                                data-title="{{ $notification->title }}" data-message="{{ $notification->message }}"
                                data-id="{{ $notification->id }}" data-type="{{ $notification->type }}"
                                style="cursor:pointer;" onclick="openNotificationDetail(this)">

                                {{-- LEFT --}}
                                <div class="row-left">

                                    <input type="checkbox" class="checkboxs notification-select"
                                        name="notification_ids[]" value="{{ $notification->id }}"
                                        onclick="event.stopPropagation();">

                                    <span class="tag">

                                        <span class="avatar notification-type-icon">
                                            @if ($notification->type === 'admin_message')
                                                <i class="bi bi-check-circle-fill"></i>
                                            @elseif ($notification->type === 'global_message')
                                                <i class="bi bi-percent"></i>
                                            @else
                                                <i class="bi bi-truck"></i>
                                            @endif
                                        </span>

                                    </span>

                                    <span class="status">

                                        @if ($notification->type === 'admin_message')
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
                                        @if ($notification->type === 'admin_message')
                                            Admin Message
                                        @elseif ($notification->type === 'global_message')
                                            New Deal
                                        @else
                                            Order Received
                                        @endif
                                    </span>
                                    <span class="desktop-separator">-</span>
                                    <span class="desktop-message">{{ Str::limit($notification->message, 118) }}</span>
                                    <span class="notification-meta d-none">
                                        {{ $notification->created_at->format('D d/m/Y') }}
                                        <span>{{ $notification->created_at->format('h:i A') }}</span>
                                    </span>

                                    @if (str_contains(strtolower($notification->message), 'attachment'))
                                        <a href="{{ route('user.notifications.show', $notification->id) }}"
                                            onclick="event.stopPropagation();" style="color:#10c4d4; font-weight:600;">
                                            attachment
                                        </a>
                                    @endif

                                </div>

                                {{-- RIGHT --}}
                                <div class="row-right">

                                    <span class="row-date">{{ $notification->created_at->format('M d') }}</span>
                                    <div class="row-actions" onclick="event.stopPropagation();">
                                        <button type="button" title="Delete"
                                            onclick="deleteNotificationById({{ $notification->id }})">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="empty-state">

                                <i class="bi bi-inbox"></i>

                                <p>You have no notifications yet.</p>

                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <!------------Mobile Notification List-------------->
            <div class="notification-list">
                @forelse($notifications as $notification)
                    <div class="notification-card {{ !$notification->is_read ? 'unread' : '' }}"
                        data-title="{{ $notification->title }}" data-message="{{ $notification->message }}"
                        data-message="{{ $notification->message }}" data-id="{{ $notification->id }}"
                        data-type="{{ $notification->type }}" style="cursor: pointer;"
                        onclick="openNotificationDetail(this)">

                        <div class="notification-content">
                            <div class="avatar">
                                <img src="{{ $notification->sender_profile_image_display ?? asset('images/pos/Rectangle 2.png') }}"
                                    alt="Sender"
                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;"
                                    onerror="this.src='{{ asset('images/pos/Rectangle 2.png') }}'">
                            </div>

                            <div class="notification-text">

                                {{-- Show ADMIN badge only for admin-sent notifications --}}
                                @if ($notification->type === 'admin_message' || $notification->type === 'global_message')
                                    <span
                                        class="badge-admin">{{ $notification->type === 'global_message' ? 'GLOBAL' : 'ADMIN' }}</span>
                                @endif

                                <div class="notification-title">
                                    {{ $notification->title }}
                                </div>

                                <div class="notification-meta">
                                    {{ $notification->created_at->format('D d/m/Y') }}
                                    <span style="margin: 0 8px;">{{ $notification->created_at->format('h:i A') }}</span>
                                </div>

                                @if (str_contains(strtolower($notification->message), 'attachment'))
                                    <a href="{{ route('user.notifications.show', $notification->id) }}"
                                        class="notification-attachment" onclick="event.stopPropagation();">
                                        attachment
                                    </a>
                                @endif

                                @if (!$notification->is_read)
                                    <form action="{{ route('user.notifications.read', $notification->id) }}"
                                        method="POST" style="display: inline;" onclick="event.stopPropagation();">
                                        @csrf
                                    </form>
                                @endif
                            </div>
                        </div>

                        @if (!$notification->is_read)
                            <div class="notification-badge">{{ max(1, (int) ($notification->unread_count ?? 1)) }}</div>
                        @endif
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
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
                        @forelse($adminMessages as $notification)
                            <div class="table-row {{ !$notification->is_read ? 'selected' : '' }}"
                                data-title="{{ $notification->title }}" data-message="{{ $notification->message }}"
                                data-id="{{ $notification->id }}" data-type="{{ $notification->type }}"
                                style="cursor:pointer;" onclick="openNotificationDetail(this)">

                                <div class="row-left">
                                    <input type="checkbox" class="checkboxs notification-select"
                                        name="notification_ids[]" value="{{ $notification->id }}"
                                        onclick="event.stopPropagation();">
                                    <span class="tag">
                                        <span class="avatar notification-type-icon">
                                            <i class="bi {{ $notification->type === 'global_message' ? 'bi-megaphone-fill' : 'bi-chat-left-text-fill' }}"></i>
                                        </span>
                                    </span>
                                    <span class="status">
                                        {{ $notification->type === 'global_message' ? 'Global Message' : 'Admin Message' }}
                                    </span>
                                </div>

                                <div class="row-center">
                                    <span class="desktop-subject">
                                        {{ $notification->type === 'global_message' ? 'Global Message' : 'Admin Message' }}
                                    </span>
                                    <span class="desktop-separator">-</span>
                                    <span class="desktop-message">{{ Str::limit($notification->message, 118) }}</span>
                                    <span class="notification-meta d-none">
                                        {{ $notification->created_at->format('D d/m/Y') }}
                                        <span>{{ $notification->created_at->format('h:i A') }}</span>
                                    </span>
                                </div>

                                <div class="row-right">
                                    <span class="row-date">{{ $notification->created_at->format('M d') }}</span>
                                    <div class="row-actions" onclick="event.stopPropagation();">
                                        <button type="button" title="Delete"
                                            onclick="deleteNotificationById({{ $notification->id }})">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-chat-left-text"></i>
                                <p>You have no admin messages yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

               <div class="desktop-notification-toolbar">

    <!-- LEFT: SHOW ITEMS -->
    <form method="GET" action="{{ route('user.notifications') }}" class="pager-size-form">

        @foreach (request()->except(['limit', 'page']) as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach

        <label for="desktopLimitSelect">Show</label>

        <select id="desktopLimitSelect" name="limit" onchange="this.form.submit()">
            @foreach ([10, 25, 50, 100] as $size)
                <option value="{{ $size }}" {{ (int) request('limit', 10) === $size ? 'selected' : '' }}>
                    {{ $size }}
                </option>
            @endforeach
        </select>

        <span>items</span>

    </form>

    <!-- CENTER: PAGINATION -->
    <div class="desktop-pagination-center">

        <nav class="desktop-page-numbers" aria-label="Notification pagination">

            {{-- LEFT ARROW --}}
            @if ($notifications->onFirstPage())
                <span class="page-arrow disabled">
                    <i class="bi bi-chevron-left"></i>
                </span>
            @else
                <a class="page-arrow" href="{{ $notifications->previousPageUrl() }}">
                    <i class="bi bi-chevron-left"></i>
                </a>
            @endif

            {{-- PAGES --}}
            @for ($page = 1; $page <= $notifications->lastPage(); $page++)
                @if (
                    $page <= 5 ||
                    $page === $notifications->lastPage() ||
                    abs($page - $notifications->currentPage()) <= 1
                )
                    <a class="page-number {{ $notifications->currentPage() === $page ? 'active' : '' }}"
                       href="{{ $notifications->url($page) }}">
                        {{ $page }}
                    </a>

                @elseif ($page === 6)
                    <span class="page-number muted">...</span>
                @endif
            @endfor

            {{-- RIGHT ARROW --}}
            @if ($notifications->hasMorePages())
                <a class="page-arrow" href="{{ $notifications->nextPageUrl() }}">
                    <i class="bi bi-chevron-right"></i>
                </a>
            @else
                <span class="page-arrow disabled">
                    <i class="bi bi-chevron-right"></i>
                </span>
            @endif

        </nav>

    </div>

    <!-- RIGHT: RESULT COUNT -->
    <div class="desktop-result-count">

        <span data-result-count="orderNotification">
            {{ $notifications->firstItem() ?? 0 }} - {{ $notifications->lastItem() ?? 0 }} of
            {{ $notifications->total() }}
        </span>

        <span data-result-count="adminMessage" style="display:none;">
            {{ $adminMessages->count() ? 1 : 0 }} - {{ $adminMessages->count() }} of {{ $adminMessages->count() }}
        </span>

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

        {{-- Notification Detail Modal --}}
        <div id="notificationModal" class="modal fade" tabindex="-1" aria-labelledby="notificationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="notification-detail-card">
                    <div class="detail-header">
                        <div class="avatar-circle">
                            <i class="bi bi-person-fill" style="font-size: 30px; color: #94a3b8;"></i>
                        </div>
                        <div class="company-info">
                            <div class="name" id="notificationCompanyName">Trey Research</div>
                            <div class="email" id="notificationUserEmail">mary.kumm@contoso.com</div>
                            <div class="status-badge">Read</div>
                        </div>
                    </div>

                    <div class="info-grid">
                        <div>
                            <div class="info-label">Type</div>
                            <div class="info-value" id="notificationType">order</div>
                        </div>
                        <div>
                            <div class="info-label">Date</div>
                            <div class="info-value" id="notificationDateDisplay">Fri 10/04/2026 02:03 PM</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="info-label">Title</div>
                        <div class="info-value" id="notificationTitleText">Order Confirmed</div>
                    </div>

                    <div>
                        <div class="info-label">Message</div>
                        <div class="message-box" id="notificationMessageBody">
                            Your order ORD-20260404092306-0XVQ has been confirmed and stored in Sales Order.
                        </div>
                    </div>

                    <div class="detail-footer">
                        <button type="button" class="btn-back" data-bs-dismiss="modal">Back to List</button>
                        <button type="button" class="btn-delete" id="deleteNotificationBtn">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- ALL CONTACT OVERLAY mobile screen------------------------------------------------>


        @php
            $contactsFromNotifications = isset($contactList) ? collect($contactList) : collect([]);

            $favoriteContacts = $contactsFromNotifications->take(6);
        @endphp

        <!-- ALL CONTACT OVERLAY -->
        <div id="allContactScreen" class="all-contact-screen">

            <div class="contact-header">
                <button class="back-btn" onclick="closeAllContact()">
                    <i class="bi bi-arrow-left"></i>
                </button>
                <h4>All Contact</h4>
            </div>

            <div class="contact-body">

                <div class="favorite-section">
                    <p class="favorite-title">Favorite</p>
                    <div class="favorite-list">
                        @forelse($favoriteContacts as $contact)
                            <a href="{{ route('user.chat.index', ['admin_id' => $contact->id]) }}" class="favorite-item"
                                title="{{ $contact->name }}">
                                <img src="{{ $contact->chat_avatar }}"
                                    onerror="this.src='{{ asset('images/pos/Rectangle 2.png') }}'"
                                    alt="{{ $contact->name }}">
                            </a>
                        @empty
                            <div class="empty-text">No favorites yet</div>
                        @endforelse
                    </div>
                </div>

                <div class="ac-search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="acSearchInput" placeholder="Search" onkeyup="filterContactsList()">
                </div>

                <div class="ac-contact-list">
                    @forelse($contactsFromNotifications as $contact)
                        <a href="{{ route('user.notifications.show', ['id' => $contact->id]) }}" class="ac-contact-row"
                            class="ac-contact-row" data-name="{{ strtolower($contact->name) }}">

                            <div class="ac-contact-avatar">
                                <img src="{{ $contact->chat_avatar }}"
                                    onerror="this.src='{{ asset('images/pos/Rectangle 2.png') }}'"
                                    alt="{{ $contact->name }}">
                            </div>

                            <div class="ac-contact-info">
                                <strong class="ac-contact-name">{{ $contact->name }}</strong>
                                <span class="ac-contact-time">last seen recently</span>
                            </div>
{{--
                            @if ($contact->unread_count > 0)
                                <span class="ac-contact-badge">{{ $contact->unread_count }}</span>
                            @endif --}}
                        </a>
                    @empty
                        <div class="ac-empty-text">No contacts available</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- NEW MESSAGE OVERLAY -->
        <div id="newMessageScreen" class="new-message-screen">

            <div class="nm-header">
                <button class="nm-back-btn" onclick="closeNewMessage()">
                    <i class="bi bi-arrow-left"></i>
                </button>
                <h4>New Message</h4>
            </div>

            <div class="nm-body">

                <!-- Quick Actions -->
                <div class="nm-quick-actions">

                    <a href="#" class="nm-action-item">
                        <div class="nm-action-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <span>New Group</span>
                    </a>

                    <a href="#" class="nm-action-item">
                        <div class="nm-action-icon">
                            <i class="bi bi-lock-fill"></i>
                        </div>
                        <span>New Secret Chat</span>
                    </a>

                    <a href="#" class="nm-action-item">
                        <div class="nm-action-icon">
                            <i class="bi bi-chat-square-fill"></i>
                        </div>
                        <span>New Channel</span>
                    </a>

                </div>

                <!-- Section Title -->
                <p class="nm-section-title">Sorted by last seen time</p>

                <!-- Contact List -->
                <div class="nm-contact-list">
                    @forelse($contactList as $contact)
                        <a href="{{ route('user.chat.index', ['admin_id' => $contact->id]) }}" class="nm-contact-item">

                            <div class="nm-contact-avatar">
                                <img src="{{ $contact->chat_avatar }}"
                                    onerror="this.src='{{ asset('images/pos/Rectangle 2.png') }}'"
                                    alt="{{ $contact->name }}">

                                {{-- @if ($contact->unread_count > 0)
                                    <span class="nm-contact-badge">
                                        {{ $contact->unread_count }}
                                    </span>
                                @endif --}}
                            </div>

                            <span class="nm-contact-name">
                                {{ $contact->name }}
                            </span>
                        </a>
                    @empty
                        <div class="nm-empty-text">No contacts available</div>
                    @endforelse
                </div>

            </div>
        </div>

    @endsection


    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            const tabs = document.querySelectorAll('.tab');
            const tabContents = document.querySelectorAll('.tab-content');
            const pagination = document.getElementById('paginationContainer');
            const desktopPager = document.getElementById('desktopNotificationPager');
            const resultCounts = document.querySelectorAll('[data-result-count]');

            tabs.forEach(tab => {

                tab.addEventListener('click', function() {

                    // REMOVE ACTIVE
                    tabs.forEach(item => item.classList.remove('active'));

                    // HIDE ALL CONTENTS
                    tabContents.forEach(content => {
                        content.style.display = 'none';
                    });

                    // ACTIVATE CURRENT TAB
                    this.classList.add('active');

                    const target = this.getAttribute('data-tab');

                    // SHOW CONTENT
                    document.getElementById(target).style.display = 'block';

                    resultCounts.forEach(count => {
                        count.style.display = count.dataset.resultCount === target ? 'inline' : 'none';
                    });

                    // ✅ ✅ CONTROL PAGINATION VISIBILITY
                    if (target === 'orderNotification') {
                        pagination.style.display = 'block';
                        if (desktopPager) desktopPager.style.display = '';
                    } else {
                        pagination.style.display = 'none';
                        if (desktopPager) desktopPager.style.display = 'none';
                    }

                });

            });

            function getCsrfToken() {
                return document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
            }

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
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                const contacts = document.querySelectorAll(".contact");

                contacts.forEach(contact => {
                    contact.addEventListener("click", function() {

                        // Get data from clicked contact
                        let name = this.dataset.name;
                        let avatar = this.dataset.avatar;
                        let phone = this.dataset.phone;
                        let email = this.dataset.email;

                        // Update RIGHT PANEL
                        document.getElementById("profileName").innerText = name;
                        document.getElementById("profileImage").src = avatar;
                        document.getElementById("profilePhone").innerText = phone;
                        document.getElementById("profileEmail").innerText = email;

                    });
                });

            });
        </script>
        <script>
            const searchInput = document.getElementById('searchInput');
            const searchSuggestions = document.getElementById('searchSuggestions');
            const dateInput = document.getElementById('dateInput');
            const notificationCards = document.querySelectorAll('.notification-card');

            // Open notification detail modal
            function openNotificationDetail(element) {
                const row = element.closest('.table-row, .notification-card');
                const title = element.dataset.title;
                const message = element.dataset.message;
                const notificationId = element.dataset.id;
                // Extracting new data attributes (make sure these are in your HTML)
                const type = element.dataset.type || 'order';
                const email = element.dataset.email || '';
                const company = element.dataset.company || '';

                // Get the date from the element
                const metaElement = element.querySelector('.notification-meta');
                const dateText = metaElement ? metaElement.textContent.trim() : new Date().toLocaleDateString();

                // 3. Populate the NEW Modern UI IDs
                document.getElementById('notificationCompanyName').textContent = company;
                document.getElementById('notificationUserEmail').textContent = email;
                document.getElementById('notificationType').textContent = type;
                document.getElementById('notificationDateDisplay').textContent = dateText;
                document.getElementById('notificationTitleText').textContent = title;
                document.getElementById('notificationMessageBody').textContent = message;
                // Open modal
                const modal = new bootstrap.Modal(document.getElementById('notificationModal'));
                modal.show();

                // Mark as read if not already read

                if (row.classList.contains('unread') || row.classList.contains('selected')) {

                    fetch(`/pos-system/notifications/${notificationId}/read`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                                    '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            }
                        })

                        .then(() => {

                            // ✅ Remove desktop highlight
                            row.classList.remove('selected');

                            // ✅ Remove mobile highlight
                            row.classList.remove('unread');

                            // ✅ Remove badge
                            const badge = row.querySelector('.notification-badge');
                            if (badge) badge.remove();

                            // ✅ Remove bold (desktop)
                            const strong = row.querySelector('strong');
                            if (strong) strong.classList.remove('fw-bold');


                        }).catch(err => console.error('Error marking as read:', err));
                }
            }

            // Get all notifications for autocomplete
            const allNotifications = Array.from(notificationCards).map(card => ({
                title: card.dataset.title,
                message: card.dataset.message,
                element: card
            }));

            // Live search with suggestions
            if (searchInput && searchSuggestions) {
                searchInput.addEventListener('input', function() {
                const searchTerm = this.value.trim().toLowerCase();

                if (searchTerm.length === 0) {
                    searchSuggestions.classList.remove('active');
                    showAllNotifications();
                    return;
                }

                // Filter notifications
                const filtered = allNotifications.filter(notif =>
                    notif.title.toLowerCase().includes(searchTerm) ||
                    notif.message.toLowerCase().includes(searchTerm)
                );

                if (filtered.length === 0) {
                    searchSuggestions.innerHTML =
                        '<div class="suggestion-item" style="color: #999;">No results found</div>';
                    searchSuggestions.classList.add('active');
                    hideAllNotifications();
                    return;
                }

                // Show suggestions
                searchSuggestions.innerHTML = filtered.map((notif, index) => `
                <div class="suggestion-item" onclick="selectSuggestion('${index}')">
                    <strong>${escapeHtml(notif.title)}</strong>
                    <br>
                    <span style="font-size: 12px;">${escapeHtml(notif.message.substring(0, 50))}${notif.message.length > 50 ? '...' : ''}</span>
                </div>
            `).join('');

                searchSuggestions.classList.add('active');

                // Show matching notifications
                notificationCards.forEach(card => {
                    const isMatch = filtered.some(f => f.element === card);
                    card.style.display = isMatch ? 'flex' : 'none';
                });
            });

                // Hide suggestions when clicking outside
                document.addEventListener('click', function(e) {
                    if (e.target !== searchInput && e.target !== searchSuggestions) {
                        searchSuggestions.classList.remove('active');
                    }
                });
                // 3. FULL DATABASE SEARCH (When user hits ENTER)
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        const searchTerm = this.value.trim();
                        const currentUrl = new URL(window.location.href);

                        if (searchTerm) {
                            currentUrl.searchParams.set('search', searchTerm);
                        } else {
                            currentUrl.searchParams.delete('search');
                        }

                        // CRITICAL: Remove 'page' so search starts from Page 1 of the results
                        currentUrl.searchParams.delete('page');
                        window.location.href = currentUrl.toString();
                    }
                });
            }

            // Date filter
            // Date filter with Clear support
            if (dateInput) {
                dateInput.addEventListener('change', function() {
                    const currentUrl = new URL(window.location.href);

                if (this.value) {
                    // If a date is selected, add it to the URL
                    currentUrl.searchParams.set('date', this.value);
                } else {
                    // If the date is cleared, remove it from the URL
                    currentUrl.searchParams.delete('date');
                }

                // Maintain the current tab
                currentUrl.searchParams.set('tab', '{{ $tab }}');

                    window.location.href = currentUrl.toString();
                });
            }

            function showAllNotifications() {
                notificationCards.forEach(card => card.style.display = 'flex');
            }

            // Hide all notifications
            function hideAllNotifications() {
                notificationCards.forEach(card => {
                    card.style.display = 'none';
                });
            }

            // Select suggestion
            // HELPERS
            function selectSuggestion(index) {
                const searchTerm = searchInput.value.toLowerCase();
                const filtered = currentPageData.filter(notif =>
                    notif.title.toLowerCase().includes(searchTerm) ||
                    notif.message.toLowerCase().includes(searchTerm)
                );
                if (filtered[index]) {
                    filtered[index].element.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    filtered[index].element.style.background = '#fffacd';
                    setTimeout(() => {
                        filtered[index].element.style.background = '';
                    }, 1500);
                }
                searchSuggestions.classList.remove('active');
            }
            // Escape HTML
            function escapeHtml(text) {
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, m => map[m]);
            }

            // Handle unread filter
            // ✅ Handle unread toggle (ONLY MOBILE NOW)
            function filterUnreadMobile() {

                const checkbox = document.getElementById('mobileUnreadFilter');
                let url = new URL(window.location.href);

                if (checkbox.checked) {
                    url.searchParams.set('unread', 'true');
                } else {
                    url.searchParams.delete('unread');
                }

                // ✅ KEEP CURRENT PAGE + TAB
                url.searchParams.set('tab', 'inbox');

                window.location.href = url.toString();
            }


            // ✅ Restore toggle state on page load
            document.addEventListener('DOMContentLoaded', function() {

                const params = new URLSearchParams(window.location.search);
                const isUnread = params.get('unread') === 'true';

                const mobileUnreadFilter = document.getElementById('mobileUnreadFilter');
                if (mobileUnreadFilter) {
                    mobileUnreadFilter.checked = isUnread;
                }

            });
        </script>
        <script>
            function openAllContact() {
                const screen = document.getElementById('allContactScreen');
                if (screen) {
                    screen.classList.add('active');
                }
            }

            function closeAllContact() {
                const screen = document.getElementById('allContactScreen');
                if (screen) {
                    screen.classList.remove('active');
                }
            }

            function openNewMessage() {
                const screen = document.getElementById('newMessageScreen');
                if (screen) {
                    screen.classList.add('active');
                }
            }

            function closeNewMessage() {
                const screen = document.getElementById('newMessageScreen');
                if (screen) {
                    screen.classList.remove('active');
                }
            }
        </script>
    @endpush
