<h2>Admin Notifications</h2>

@if(isset($notifications) && $notifications->count())
    @foreach($notifications as $notification)
        <div style="border:1px solid #ccc; margin-bottom:10px; padding:15px;">
            <h4>{{ $notification->title }}</h4>
            <p>{{ $notification->message }}</p>
            <p>Type: {{ $notification->type }}</p>
            <p>Status: {{ $notification->is_read ? 'Read' : 'Unread' }}</p>
            <p>Time: {{ $notification->created_at }}</p>
        </div>
    @endforeach
@else
    <p>No notifications found.</p>
@endif
