@extends('ManagementSystemViews.UserViews.Layouts.app')
@section('title', 'Notifications')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/POSsystem/notification.css') }}" />
@endpush

@section('content')
    <div class="page-wrap">
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
                <h2>Notification</h2>
                <a href="{{ route('user.chat.index') }}" class="btn btn-sm btn-info text-white ms-2">Message Admin</a>
            </div>

            {{-- Search and Date --}}
            <div class="search-date-container">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search..."
                        value="{{ request('search') }}" autocomplete="off">
                    <div class="search-suggestions" id="searchSuggestions"></div>
                </div>

                <div class="date-filter-wrapper">
                    <label for="dateInput" class="floating-label">Date</label>
                    <input type="date" name="date" id="dateInput" value="{{ request('date') }}"
                        onchange="this.form.submit()">

                    <img src="{{ asset('images/pos/icon.png') }}" class="calendar-custom-img" alt="calendar">
                </div>
            </div>

            {{-- Tabs --}}
            <div class="tabs-section">
                <div class="tabs-list">
                    <a href="{{ route('user.notifications', ['tab' => 'inbox']) }}"
                        class="tab-item {{ $tab === 'inbox' ? 'active' : '' }}">
                        Inbox <span class="tab-badge">{{ $inboxCount }}</span>
                    </a>

                    <a href="{{ route('user.notifications', ['tab' => 'spam']) }}"
                        class="tab-item {{ $tab === 'spam' ? 'active' : '' }}">
                        Spam <span class="tab-badge">{{ $spamCount }}</span>
                    </a>

                    <a href="{{ route('user.notifications', ['tab' => 'archive']) }}"
                        class="tab-item {{ $tab === 'archive' ? 'active' : '' }}">
                        Archive <span class="tab-badge">{{ $archiveCount }}</span>
                    </a>

                    <a href="{{ route('user.notifications', ['tab' => 'global_message']) }}"
                        class="tab-item {{ $tab === 'global_message' ? 'active' : '' }}">
                        Global Message <span class="tab-badge">{{ $globalMessageCount }}</span>
                    </a>
                </div>

                <label class="unread-toggle">
                    <span>Unreads</span>
                    <input type="checkbox" id="unreadFilter" onchange="filterUnread()">
                </label>
            </div>
        </div>
        {{-- MOBILE TOP TABS --}}
        <div class="mobile-tabs">
       

<a href="{{ route('user.notifications.mobile_inbox') }}"
   class="mt-pill active">
    <i class="bi bi-inbox"></i>
    Inbox
</a>
       

            <button class="mt-icon"><i class="bi bi-pencil"></i></button>
            <button class="mt-icon"><i class="bi bi-archive"></i></button>

            <label class="mt-switch">
                <input type="checkbox">
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
            <span>Out of Stock Alert ({{ $spamCount }})</span>
        </div>

        {{-- Notification List --}}
        <div class="notification-list">
            @forelse($notifications as $notification)
                <div class="notification-card {{ !$notification->is_read ? 'unread' : '' }}"
                    data-title="{{ $notification->title }}" data-message="{{ $notification->message }}"
                    data-message="{{ $notification->message }}" data-id="{{ $notification->id }}"
                    data-type="{{ $notification->type }}" style="cursor: pointer;" onclick="openNotificationDetail(this)">

                    <div class="notification-content">
                        <div class="avatar">
                            <img src="{{ $notification->sender_profile_image_display ?? asset('images/pos/Rectangle 2.png') }}"
                                alt="Sender" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;"
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
                                <form action="{{ route('user.notifications.read', $notification->id) }}" method="POST"
                                    style="display: inline;" onclick="event.stopPropagation();">
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
    
        {{-- Pagination --}}
        <div class="pagination-container">
            @if ($notifications->hasPages())
                {{ $notifications->links('vendor.pagination.custom-pos') }}
            @endif
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

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const searchInput = document.getElementById('searchInput');
        const searchSuggestions = document.getElementById('searchSuggestions');
        const dateInput = document.getElementById('dateInput');
        const notificationCards = document.querySelectorAll('.notification-card');

        // Open notification detail modal
        function openNotificationDetail(element) {
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
            if (element.classList.contains('unread')) {
                fetch(`/pos-system/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                            '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    }
                }).then(() => {
                    element.classList.remove('unread');
                    const badge = element.querySelector('.notification-badge');
                    if (badge) badge.remove();
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

        // Date filter
        // Date filter with Clear support
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
        function filterUnread() {
            const checkbox = document.getElementById('unreadFilter');
            const currentUrl = new URL(window.location);

            if (checkbox.checked) {
                currentUrl.searchParams.set('unread', 'true');
            } else {
                currentUrl.searchParams.delete('unread');
            }

            window.location.href = currentUrl.toString();
        }

        // Check unread filter on page load
        window.addEventListener('load', function() {
            const params = new URLSearchParams(window.location.search);
            if (params.get('unread') === 'true') {
                document.getElementById('unreadFilter').checked = true;
            }
        });
    </script>
@endpush
