<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Notification Detail</title>
    <link rel="stylesheet" href="{{ asset('css/management-system/aside.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/views/notifications/show.css') }}">
</head>
<body>
    <div class="app-shell" id="appShell">
        @include('ManagementSystemViews.UserViews.Layouts.aside')

        <main class="content-scroll" style="padding:1.5rem;">
            <div class="notification-detail-card">
                <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1rem;">
                    <div style="width: 60px; height: 60px; border-radius: 50%; overflow: hidden; flex-shrink: 0;">
                        <img src="{{ $notification->sender_profile_image_display ?? asset('images/pos/Rectangle 2.png') }}"
                             alt="Sender"
                             style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div style="flex: 1;">
                        <h2 style="margin: 0 0 0.5rem 0;">{{ $notification->title }}</h2>
                        <div class="meta">{{ optional($notification->created_at)->format('d M Y h:i A') }}</div>
                    </div>
                </div>
                <div style="border-top: 1px solid #e5e7eb; padding-top: 1rem;">
                    @php
                        $messageHtml = preg_replace(
                            '/(https?:\/\/[^\s<]+)/i',
                            '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>',
                            e($notification->message ?? '')
                        );
                    @endphp
                    <div class="message-body">{!! nl2br($messageHtml) !!}</div>
                </div>
                <a href="{{ route('user.notifications') }}" class="btn-back">← Back to notifications</a>
            </div>
        </main>
    </div>
</body>
</html>
