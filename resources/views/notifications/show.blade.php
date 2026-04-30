<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Notification Detail</title>
    <link rel="stylesheet" href="{{ asset('css/ManagementSystem/aside.css') }}" />
    <style>
        .notification-detail-card { 
            max-width: 720px;
            margin: 2rem auto;
            padding: 1.5rem;
            border: 1px solid #dde1e7;
            border-radius: 14px;
            background: #fff;
        }
        .notification-detail-card h2 { margin-bottom: 0.75rem; }
        .notification-detail-card p { margin: 0.5rem 0; }
        .notification-detail-card .meta { color:#6b7280; font-size:0.88rem; }
        .btn-back { display:inline-block; margin-top:1rem; color:#007bff; text-decoration:none; }
        .message-body {
            line-height: 1.6;
            word-break: break-word;
        }
        .message-body a {
            color: #2563eb;
            text-decoration: underline;
        }
    </style>
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
