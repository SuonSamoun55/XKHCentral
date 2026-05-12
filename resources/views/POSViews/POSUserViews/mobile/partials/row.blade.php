<div class="chat-row {{ !$notification->is_read ? 'unread' : '' }}">
   <div class="chat-avatar">
    <img 
        src="{{ asset('images/pos/Rectangle 2.png') }}"
        alt="Avatar"
        onerror="this.src='{{ asset('images/pos/Rectangle 2.png') }}'">
</div>

    <div class="chat-content">
        <div class="chat-top">
            <strong>{{ $notification->sender_name ?? 'System' }}</strong>
            <span>{{ $notification->created_at->format('h:i a') }}</span>
        </div>

        <div class="chat-message">
            {{ \Illuminate\Support\Str::limit($notification->message, 40) }}
        </div>
    </div>

    @if(!$notification->is_read)
        <span class="chat-badge">
            {{ $notification->unread_count ?? 1 }}
        </span>
    @endif
</div>