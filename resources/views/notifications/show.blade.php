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
    </style>
</head>
<body>
    <div class="app-shell" id="appShell">
        @include('ManagementSystemViews.UserViews.Layouts.aside')

        <main class="content-scroll" style="padding:1.5rem;">
            <div class="notification-detail-card">
                <h2>{{ $notification->title }}</h2>
                <div class="meta">{{ optional($notification->created_at)->format('d M Y h:i A') }}</div>
                <p>{{ $notification->message }}</p>
                <a href="{{ route('user.notifications') }}" class="btn-back">← Back to notifications</a>
            </div>
        </main>
    </div>
</body>
</html>