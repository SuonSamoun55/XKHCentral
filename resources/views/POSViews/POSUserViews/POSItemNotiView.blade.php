<!DOCTYPE html>
<html>
<head>
    <title>Notifications</title>

    <link rel="stylesheet" href="{{ asset('css/ManagementSystem/aside.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pos-user-notification.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="app-shell" id="appShell">

    @include('ManagementSystemViews.UserViews.Layouts.aside')

    <div class="page-wrap">
        <div class="notification-wrapper">

            <div class="notification-header">
                <h2 class="page-title">Notification</h2>

                <div class="header-actions">
                    <div class="selected-box">
                        Selected <span id="selectedCount">0</span>
                    </div>

                    <form action="{{ route('notifications.read.all') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-action btn-cancel">Mark all read</button>
                    </form>

                    <button type="submit" form="deleteForm" class="btn-action btn-delete">Delete</button>
                </div>
            </div>

            <form method="GET" action="{{ route('notifications.index') }}" class="filter-form">
                <div class="filter-row">
                    <div class="search-box-noti">
                        <span class="search-icon">⌕</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." />
                    </div>

                    <div class="date-filter-box">
                        <label for="date">Date</label>
                        <input type="date" name="date" id="date" value="{{ request('date') }}" onchange="this.form.submit()">
                    </div>
                </div>

                <div class="tab-row">
                    <div class="tabs">
                        <a href="{{ route('notifications.index', array_merge(request()->except('page', 'tab'), ['tab' => 'inbox'])) }}"
                           class="tab-link {{ ($tab ?? 'inbox') === 'inbox' ? 'active' : '' }}">
                            Inbox <span class="tab-badge">{{ $allCount ?? 0 }}</span>
                        </a>

                        <span class="tab-link disabled-tab">Spam</span>

                        <span class="tab-link disabled-tab">Archive <span class="tab-badge light">0</span></span>
                    </div>

                    <div class="unread-toggle-wrap">
                        <span>Unreads</span>
                        <a href="{{ route('notifications.index', array_merge(request()->except('page', 'tab'), ['tab' => ($tab ?? 'inbox') === 'unread' ? 'inbox' : 'unread'])) }}"
                           class="toggle-switch {{ ($tab ?? '') === 'unread' ? 'active' : '' }}">
                            <span class="toggle-circle"></span>
                        </a>
                    </div>
                </div>
            </form>

            <form action="{{ route('notifications.delete.selected') }}" method="POST" id="deleteForm">
                @csrf
                @method('DELETE')

                <div class="notification-list">
                    @forelse($notifications as $notification)
                        <div class="notification-item {{ !$notification->is_read ? 'active-row' : '' }}">
                            <div class="notification-left">
                                <input type="checkbox" class="notification-checkbox" name="notification_ids[]" value="{{ $notification->id }}">

                                <div class="avatar-box">
                                    <img src="{{ asset('images/default-avatar.png') }}" alt="avatar">
                                </div>

                                <div class="notification-content">
                                    <div class="notification-top">
                                        <div class="notification-title {{ !$notification->is_read ? 'bold' : '' }}">
                                            {{ $notification->title }}
                                        </div>

                                        @if(!$notification->is_read)
                                            <span class="unread-badge">1</span>
                                        @endif
                                    </div>

                                    @if(!empty($notification->message))
                                        <div class="notification-message">
                                            {{ $notification->message }}
                                        </div>
                                    @endif

                                    <div class="notification-meta">
                                        <span class="meta-date">{{ optional($notification->created_at)->format('D d/m/Y') }}</span>
                                        <span class="meta-time">{{ optional($notification->created_at)->format('g:ia') }}</span>
                                    </div>

                                    @if(!$notification->is_read)
                                        <div class="notification-actions-row">
                                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="mark-read-btn">Mark as read</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-box">
                            No notifications found.
                        </div>
                    @endforelse
                </div>
            </form>

            <div class="pagination-wrap">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    const checkboxes = document.querySelectorAll('.notification-checkbox');
    const selectedCount = document.getElementById('selectedCount');

    function updateSelectedCount() {
        const checked = document.querySelectorAll('.notification-checkbox:checked');
        selectedCount.textContent = checked.length;
    }

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
</script>

</body>
</html>
<style>
    * {
    box-sizing: border-box;
}

body {
    margin: 0;
    background: #f3f3f3;
    font-family: Arial, Helvetica, sans-serif;
    color: #222;
}

.page-wrap {
    padding: 26px 18px;
}

.notification-wrapper {
    background: #f7f7f7;
    min-height: calc(100vh - 52px);
    padding: 22px 26px;
    border-radius: 4px;
}

.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    margin-bottom: 26px;
}

.page-title {
    margin: 0;
    font-size: 22px;
    font-weight: 600;
    color: #12bfd0;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.selected-box {
    min-width: 100px;
    height: 34px;
    padding: 0 12px;
    background: #edf0f5;
    border-radius: 4px;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #666;
}

.selected-box span {
    font-weight: 700;
    color: #24325c;
}

.btn-action {
    height: 34px;
    border: none;
    border-radius: 4px;
    padding: 0 14px;
    font-size: 12px;
    cursor: pointer;
}

.btn-cancel {
    background: #5d678a;
    color: #fff;
}

.btn-delete {
    background: #1f2d77;
    color: #fff;
}

.filter-form {
    margin-bottom: 24px;
}

.filter-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
    margin-bottom: 26px;
}

.search-box-noti {
    position: relative;
    width: 310px;
}

.search-box-noti input {
    width: 100%;
    height: 34px;
    border: 1px solid #d9dde6;
    border-radius: 7px;
    outline: none;
    background: #fff;
    padding: 0 12px 0 34px;
    font-size: 14px;
    color: #555;
}

.search-icon {
    position: absolute;
    top: 8px;
    left: 10px;
    color: #9aa3b2;
    font-size: 15px;
}

.date-filter-box {
    width: 180px;
}

.date-filter-box label {
    display: block;
    font-size: 10px;
    color: #666;
    margin-bottom: 4px;
    padding-left: 4px;
}

.date-filter-box input {
    width: 100%;
    height: 32px;
    border: 2px solid #12bfd0;
    border-radius: 2px;
    outline: none;
    padding: 0 8px;
    font-size: 11px;
    background: #fff;
}

.tab-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
}

.tabs {
    display: flex;
    align-items: center;
    gap: 70px;
}

.tab-link {
    color: #555;
    font-size: 15px;
    text-decoration: none;
    padding-bottom: 6px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.tab-link.active {
    color: #222;
    font-weight: 600;
    border-bottom: 2px solid #222;
}

.disabled-tab {
    cursor: default;
}

.tab-badge {
    min-width: 16px;
    height: 16px;
    border-radius: 20px;
    padding: 0 5px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 9px;
    background: #59d3dd;
    color: #fff;
}

.tab-badge.light {
    background: #dbe0f4;
    color: #666f96;
}

.unread-toggle-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #777;
    font-size: 14px;
}

.toggle-switch {
    width: 24px;
    height: 14px;
    background: #d9d9d9;
    border-radius: 20px;
    position: relative;
    display: inline-block;
    text-decoration: none;
}

.toggle-circle {
    width: 12px;
    height: 12px;
    background: #fff;
    border-radius: 50%;
    position: absolute;
    left: 1px;
    top: 1px;
    transition: 0.2s;
}

.toggle-switch.active {
    background: #11c2d1;
}

.toggle-switch.active .toggle-circle {
    left: 11px;
}

.notification-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.notification-item {
    padding: 14px 10px;
    border-radius: 3px;
}

.notification-item.active-row {
    background: #efefef;
}

.notification-left {
    display: flex;
    align-items: flex-start;
    gap: 14px;
}

.notification-checkbox {
    margin-top: 12px;
}

.avatar-box {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    overflow: hidden;
    background: #cde7f7;
    flex-shrink: 0;
}

.avatar-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.notification-content {
    flex: 1;
}

.notification-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 14px;
}

.notification-title {
    font-size: 15px;
    line-height: 1.6;
    color: #262626;
}

.notification-title.bold {
    font-weight: 700;
}

.notification-message {
    font-size: 15px;
    line-height: 1.6;
    color: #262626;
    margin-top: 2px;
}

.notification-meta {
    display: flex;
    align-items: center;
    gap: 18px;
    flex-wrap: wrap;
    margin-top: 6px;
    font-size: 10px;
}

.meta-date {
    color: #e15555;
    font-weight: 600;
}

.meta-time {
    color: #7f8ca3;
}

.unread-badge {
    min-width: 20px;
    height: 14px;
    border-radius: 12px;
    background: #df5050;
    color: #fff;
    font-size: 9px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 5px;
    margin-top: 4px;
}

.notification-actions-row {
    margin-top: 8px;
}

.mark-read-btn {
    border: none;
    background: transparent;
    color: #1f73ff;
    text-decoration: underline;
    font-size: 12px;
    padding: 0;
    cursor: pointer;
}

.empty-box {
    text-align: center;
    color: #888;
    padding: 30px 0;
}

.pagination-wrap {
    margin-top: 20px;
}

@media (max-width: 991px) {
    .notification-header,
    .filter-row,
    .tab-row {
        flex-direction: column;
        align-items: flex-start;
    }

    .tabs {
        gap: 24px;
        flex-wrap: wrap;
    }

    .search-box-noti,
    .date-filter-box {
        width: 100%;
    }
}
</style>
