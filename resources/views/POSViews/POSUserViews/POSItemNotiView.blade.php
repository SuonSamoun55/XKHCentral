<!DOCTYPE html>
<html>

<head>
    <title>Notifications</title>

    <link rel="stylesheet" href="{{ asset('css/ManagementSystem/aside.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/POSsystem/notification.css') }}" />
</head>

<body>

    <div class="app-shell" id="appShell">

        {{-- Sidebar --}}
        @include('ManagementSystemViews.UserViews.Layouts.aside')

        {{-- Content --}}
        <div class="page-wrap">
            <div class="header">
                <div class="notification-header">
                    <h2>Notification</h2>
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
                    </div>

                    <label class="unread-toggle">
                        <span>Unreads</span>
                        <input type="checkbox" id="unreadFilter" onchange="filterUnread()">
                    </label>
                </div>
            </div>

            {{-- Notification List --}}
            <div class="notification-list">
                @forelse($notifications as $notification)
                    <div class="notification-card {{ !$notification->is_read ? 'unread' : '' }}"
                        data-title="{{ $notification->title }}" data-message="{{ $notification->message }}"
                        data-id="{{ $notification->id }}" data-type="{{ $notification->type }}" style="cursor: pointer;"
                        onclick="openNotificationDetail(this)">

                        <div class="notification-content">
                            <div class="avatar">
                                <img src="{{ asset('images/pos/Rectangle 2.png') }}" alt="Admin"
                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            </div>

                            <div class="notification-text">

                                {{-- Show ADMIN badge only for admin-sent notifications --}}
                                @if($notification->type === 'admin_message')
                                    <span class="badge-admin">ADMIN</span>
                                @endif

                                <div class="notification-title">
                                    {{ $notification->title }}
                                </div>

                                <div class="notification-meta">
                                    {{ $notification->created_at->format('D d/m/Y') }}
                                    <span
                                        style="margin: 0 8px;">{{ $notification->created_at->format('h:i A') }}</span>
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
                            <div class="notification-badge">1</div>
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
        </div>

        {{-- Notification Detail Modal --}}
        <div id="notificationModal" class="modal fade" tabindex="-1" aria-labelledby="notificationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="notificationTitle"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="notificationMessage"></p>
                        <small class="text-muted" id="notificationDate"></small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

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

                // Get the date from the element
                const metaElement = element.querySelector('.notification-meta');
                const dateText = metaElement ? metaElement.textContent.trim() : new Date().toLocaleDateString();

                // Populate modal
                document.getElementById('notificationTitle').textContent = title;
                document.getElementById('notificationMessage').textContent = message;
                document.getElementById('notificationDate').textContent = 'Date: ' + dateText;

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

</body>

</html>
