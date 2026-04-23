@extends('ManagementSystemViews.AdminViews.Layouts.app')
<link rel="stylesheet" href="{{ asset('css/ManagementSystem/notification/notification.css') }}">
@section('title', 'Notification Detail')

@push('styles')
<style>

</style>
@endpush

@section('content')
<div class="app-shell" id="appShell">

    

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
                $avatarSrc = ($user && !empty($user->profile_image_display))
                    ? $user->profile_image_display
                    : asset('images/pos/Rectangle 2.png');
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
@endsection
