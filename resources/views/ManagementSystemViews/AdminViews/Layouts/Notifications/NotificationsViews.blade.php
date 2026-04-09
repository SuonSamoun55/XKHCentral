<!DOCTYPE html>
<html>
<head>
    <title>Notification Detail</title>

    <link rel="stylesheet" href="{{ asset('css/ManagementSystem/aside.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="app-shell" id="appShell">

    @include('ManagementSystemViews.AdminViews.Layouts.aside')

    <div class="page-wrap">
        <div class="detail-wrapper">
            <div class="detail-header">
                <div>
                    <h2 class="page-title">Notification Detail</h2>
                    <p class="page-subtitle">View full notification information</p>
                </div>

                <a href="{{ route('admin.notifications.index') }}" class="back-btn">Back</a>
            </div>

            @php
                use Illuminate\Support\Str;

                $user = $notification->user;
                $avatarSrc = asset('images/default-avatar.png');

                if ($user) {
                    if (!empty($user->profile_image)) {
                        $avatarSrc = asset('storage/' . ltrim($user->profile_image, '/'));
                    } elseif (!empty($user->profile_image_url)) {
                        $avatarSrc = $user->profile_image_url;
                    } elseif (!empty($user->bc_customer_no)) {
                        $avatarSrc = route('users.bc.image', $user->bc_customer_no);
                    }
                }
            @endphp

            <div class="detail-card">
                <div class="top-user-box">
                    <div class="avatar-box large">
                        <img
                            src="{{ $avatarSrc }}"
                            alt="avatar"
                            onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}'">
                    </div>

                    <div class="user-info-box">
                        <h3>{{ optional($notification->user)->name ?? 'Unknown User' }}</h3>
                        <p>{{ optional($notification->user)->email ?? 'No email' }}</p>
                        <span class="status-pill {{ !$notification->is_read ? 'unread' : 'read' }}">
                            {{ !$notification->is_read ? 'Unread' : 'Read' }}
                        </span>
                    </div>
                </div>

                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Type</label>
                        <div class="plain-value">{{ $notification->type ?? '-' }}</div>
                    </div>

                    <div class="detail-item">
                        <label>Date</label>
                        <div class="plain-value">{{ optional($notification->created_at)->format('D d/m/Y h:i A') }}</div>
                    </div>

                    <div class="detail-item full">
                        <label>Title</label>
                        <div class="rendered-title">
                            {!! !empty($notification->title) ? Str::markdown($notification->title) : '<p>-</p>' !!}
                        </div>
                    </div>

                    <div class="detail-item full">
                        <label>Message</label>
                        <div class="message-box rendered-message">
                            {!! !empty($notification->message) ? Str::markdown($notification->message) : '<p>-</p>' !!}
                        </div>
                    </div>
                </div>

                <div class="bottom-actions">
                    <a href="{{ route('admin.notifications.index') }}" class="back-btn secondary">Back to List</a>

                    <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Delete this notification?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    background: #ececec;
    font-family: Arial, Helvetica, sans-serif;
    color: #1f2937;
}

.page-wrap {
    padding: 24px;
}

.detail-wrapper {
    max-width: 980px;
}

.detail-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
    gap: 12px;
}

.page-title {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
    color: #2aaab5;
}

.page-subtitle {
    margin: 4px 0 0;
    color: #6b7280;
    font-size: 13px;
}

.detail-card {
    background: #fff;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 8px 30px rgba(15, 23, 42, 0.08);
}

.top-user-box {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
    padding-bottom: 18px;
    border-bottom: 1px solid #e5e7eb;
}

.avatar-box.large {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    overflow: hidden;
    background: #d9e6f2;
    flex-shrink: 0;
}

.avatar-box.large img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.user-info-box h3 {
    margin: 0 0 4px;
    font-size: 20px;
    font-weight: 700;
}

.user-info-box p {
    margin: 0 0 10px;
    color: #64748b;
    font-size: 13px;
}

.status-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 28px;
    padding: 0 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
}

.status-pill.unread {
    background: #e0f7fa;
    color: #0f7f8c;
}

.status-pill.read {
    background: #eef2f7;
    color: #64748b;
}

.detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}

.detail-item label {
    display: block;
    margin-bottom: 8px;
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
}

.plain-value {
    font-size: 14px;
    color: #111827;
}

.detail-item.full {
    grid-column: 1 / -1;
}

.message-box {
    min-height: 140px;
    padding: 14px;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    background: #f8fafc;
}

.rendered-title,
.rendered-message {
    line-height: 1.7;
    color: #111827;
    font-size: 14px;
}

.rendered-title p,
.rendered-message p {
    margin: 0 0 10px;
}

.rendered-title p:last-child,
.rendered-message p:last-child {
    margin-bottom: 0;
}

.rendered-title strong,
.rendered-message strong {
    font-weight: 700;
    color: #111827;
}

.rendered-title em,
.rendered-message em {
    font-style: italic;
}

.rendered-title ul,
.rendered-title ol,
.rendered-message ul,
.rendered-message ol {
    padding-left: 22px;
    margin: 8px 0;
}

.rendered-title li,
.rendered-message li {
    margin-bottom: 4px;
}

.rendered-title h1,
.rendered-title h2,
.rendered-title h3,
.rendered-title h4,
.rendered-title h5,
.rendered-title h6,
.rendered-message h1,
.rendered-message h2,
.rendered-message h3,
.rendered-message h4,
.rendered-message h5,
.rendered-message h6 {
    margin: 0 0 10px;
    font-weight: 700;
    color: #111827;
}

.rendered-title blockquote,
.rendered-message blockquote {
    margin: 10px 0;
    padding: 10px 14px;
    border-left: 4px solid #cbd5e1;
    background: #f1f5f9;
    color: #334155;
    border-radius: 8px;
}

.rendered-title code,
.rendered-message code {
    background: #eef2f7;
    padding: 2px 6px;
    border-radius: 6px;
    font-size: 13px;
}

.bottom-actions {
    margin-top: 24px;
    display: flex;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}

.back-btn,
.delete-btn {
    height: 40px;
    padding: 0 18px;
    border-radius: 10px;
    border: none;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
}

.back-btn {
    background: #14b8c4;
    color: #fff;
}

.back-btn.secondary {
    background: #e5e7eb;
    color: #111827;
}

.delete-btn {
    background: #dc2626;
    color: #fff;
}

@media (max-width: 768px) {
    .detail-grid {
        grid-template-columns: 1fr;
    }

    .detail-header,
    .top-user-box,
    .bottom-actions {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
