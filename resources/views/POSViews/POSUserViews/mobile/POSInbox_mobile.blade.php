@extends('ManagementSystemViews.UserViews.Layouts.app')
@include('ManagementSystemViews.UserViews.Layouts.footer')

@section('title', 'Inbox')

@section('content')
    <div class="inbox-page">

        {{-- Header --}}
        <div class="inbox-header">
            <a href="{{ route('user.notifications') }}" class="back-btn">
          
                <i class="bi bi-arrow-left"></i>
            </a>
            <h4>Inbox</h4>
        </div>

    <div class="inbox-scroll">

        {{-- Recent --}}
       <div class="recent-section">
    <p class="section-title">Recent</p>

    <div class="recent-list">
        @forelse($contacts as $contact)
            <a href="{{ route('user.chat.index', ['admin_id' => $contact->id]) }}"
               class="recent-item">

                <div class="recent-avatar-wrap">
                    <img src="{{ $contact->chat_avatar ?? asset('images/pos/Rectangle 2.png') }}"
                         onerror="this.src='{{ asset('images/pos/Rectangle 2.png') }}'"
                         alt="{{ $contact->name }}">

                    @if((int)($contact->unread_count ?? 0) > 0)
                        <span class="recent-badge">
                            {{ (int)$contact->unread_count }}
                        </span>
                    @endif
                </div>

                <span class="recent-name">
                    {{ \Illuminate\Support\Str::limit($contact->name, 8) }}
                </span>
            </a>
        @empty
            <div class="empty-text">No recent messages</div>
        @endforelse
    </div>
</div>


        @php
            $today = $notifications->filter(fn($n) => $n->created_at->isToday());

            $yesterday = $notifications->filter(fn($n) => $n->created_at->isYesterday());

            // All notifications BEFORE yesterday
            $otherDays = $notifications
                ->filter(fn($n) => $n->created_at->isBefore(now()->subDay()))
                ->groupBy(fn($n) => $n->created_at->format('Y-m-d'));
        @endphp


        {{-- TODAY --}}
        <div class="message-section">
            <p class="section-title">Today</p>

            {{-- Preview (2 only) --}}
            <div id="today-preview">
                @foreach ($today->take(2) as $notification)
                    <div class="chat-row {{ !$notification->is_read ? 'unread' : '' }}">
                        <div class="chat-avatar">
                            <img src="{{ $notification->sender_profile_image ?? asset('images/pos/Rectangle 2.png') }}"
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

                        @if (!$notification->is_read)
                            <span class="chat-badge">{{ $notification->unread_count ?? 1 }}</span>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Full list --}}
            <div id="today-full" style="display:none;">
                @foreach ($today as $notification)
                    <div class="chat-row {{ !$notification->is_read ? 'unread' : '' }}">
                        <div class="chat-avatar">
                            <img src="{{ $notification->sender_profile_image ?? asset('images/pos/Rectangle 2.png') }}"
                                onerror="this.src='{{ asset('images/pos/Rectangle 2.png') }}'">
                        </div>
                        <div class="chat-content">
                            <div class="chat-top">
                                <strong>{{ $notification->sender_name ?? 'System' }}</strong>
                                <span>{{ $notification->created_at->format('h:i a') }}</span>
                            </div>
                            <div class="chat-message">
                                {{ Str::limit($notification->message, 40) }}
                            </div>
                        </div>
                        @if (!$notification->is_read)
                            <span class="chat-badge">{{ $notification->unread_count ?? 1 }}</span>
                        @endif
                    </div>
                @endforeach
            </div>

            @if ($today->count() > 2)
                <div id="toggle-today" class="see-more" onclick="toggleToday()">See more</div>
            @endif
        </div>

        {{-- YESTERDAY --}}
        <div class="message-section">
            <p class="section-title">Yesterday</p>

            @if ($yesterday->isNotEmpty())
                @foreach ($yesterday as $notification)
@include('POSViews.POSUserViews.mobile.partials.row', [
    'notification' => $notification
])                @endforeach
            @else
                <p class="empty-text">Yesterday no order</p>
            @endif
        </div>

        {{-- OTHER DAYS --}}
        @foreach ($otherDays as $date => $items)
            @php
                $dateTitle = \Carbon\Carbon::parse($date)->format('M d, Y');
            @endphp

            <div class="message-section">
                <p class="section-title">{{ $dateTitle }}</p>

                @foreach ($items as $notification)
                    <div class="chat-row {{ !$notification->is_read ? 'unread' : '' }}">
                        <div class="chat-avatar">
                            <img src="{{ $notification->sender_profile_image ?? asset('images/pos/Rectangle 2.png') }}"
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

                        @if (!$notification->is_read)
                            <span class="chat-badge">{{ $notification->unread_count ?? 1 }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
    </div>

    {{-- JS --}}
    <script>
        function toggleToday() {
            const preview = document.getElementById('today-preview');
            const full = document.getElementById('today-full');
            const btn = document.getElementById('toggle-today');

            if (full.style.display === 'block') {
                full.style.display = 'none';
                preview.style.display = 'block';
                btn.innerText = 'See more';
            } else {
                preview.style.display = 'none';
                full.style.display = 'block';
                btn.innerText = 'Show less';
            }
        }
    </script>

    {{-- Styles --}}
    <style>
        .sidebar,
        .sidebar-wrap {
            display: none;
        }
        .bi-arrow-left {
                    width: 38px;
        height: 38px;
        background: #e5e7eb;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0f172a;
        text-decoration: none;
    
        }
        /* Page layout */
.inbox-page {
    height: 100vh;
    display: flex;
    flex-direction: column;
    background: #fff;
}

/* Header stays fixed */
.inbox-header {
    flex-shrink: 0;
}

/* Scrollable content */
.inbox-scroll {
   
    flex: 1;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch; /* smooth iOS scroll */
}

/* Optional: hide scrollbar (mobile look) */
.inbox-scroll::-webkit-scrollbar {
    width: 0;
    background: transparent;
}

        .inbox-page {
            background: #fff;
            min-height: 100vh;
            width: 100%;
        }

        .inbox-header {
            display: flex;
            align-items: center;
            padding: 14px 16px;
            border-bottom: 1px solid #eee;
        }

        .inbox-header h4 {
            flex: 1;
            text-align: center;
            margin: 0
        }

        .back-btn {
            font-size: 18px;
            color: #000
        }

        .section-title {
            font-size: 13px;
            font-weight: 600;
            margin: 16px
        }

      .recent-list {
    display: flex;
    gap: 16px;
    padding: 0 16px;
    overflow-x: auto;
}

.recent-item {
    text-align: center;
    text-decoration: none;
    color: inherit;
    position: relative;
}

.recent-avatar-wrap {
    position: relative;
    width: 48px;
    height: 48px;
    margin: 0 auto 4px;
}

.recent-avatar-wrap img {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
}

.recent-badge {
    position: absolute;
    top: -2px;
    right: -2px;
    background: #06b6d4;
    color: #fff;
    font-size: 10px;
    min-width: 16px;
    height: 16px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.recent-name {
    font-size: 11px;
    display: block;
}
        .recent-item img {
            width: 48px;
            height: 48px;
            border-radius: 50%
        }

        .recent-item span {
            font-size: 11px;
            text-align: center;
            display: block
        }

        .chat-row {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border-bottom: 1px solid #f1f1f1
        }

        .chat-avatar {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 12px
        }

        .chat-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .chat-content {
            flex: 1
        }

        .chat-top {
            display: flex;
            justify-content: space-between;
            font-size: 14px
        }

        .chat-message {
            font-size: 12px;
            color: #6b7280
        }

        .chat-badge {
            background: #06b6d4;
            margin-top: -36px;
            color: #fff;
            font-size: 11px;
            padding: 3px 7px;
            border-radius: 10px
        }

        .see-more {
            text-align: center;
            color: #06b6d4;
            font-size: 13px;
            margin: 8px 0;
         
        }

        .empty-text {
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 12px
        }
    </style>
@endsection
