@extends('ManagementSystemViews.AdminViews.Layouts.app')

@section('title', 'Notification Detail')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/ManagementSystemViews/AdminViews/Layouts/Notifications/NotificationsViews.css') }}">
@endpush

@section('content')
@php
    $user = $notification->user;
    $sender = $notification->sender;
    $isUserContact = ($notification->type === 'user_contact');
    $contactUser = $isUserContact ? ($sender ?: $user) : ($user ?: $sender);

    $avatarSrc = asset('images/pos/Rectangle 2.png');
    if ($contactUser && !empty($contactUser->profile_image_display)) {
        $avatarSrc = $contactUser->profile_image_display;
    } elseif (!empty($notification->sender_profile_image)) {
        $avatarSrc = $notification->sender_profile_image;
    }

    $displayName = optional($contactUser)->name
        ?? ($notification->sender_name ?: optional($sender)->name)
        ?? 'System';

    $recipientName = optional($user)->name ?? optional($sender)->name ?? 'N/A';
    $recipientEmail = optional($user)->email ?? optional($sender)->email;
    $typeLabel = str($notification->type ?? 'notification')->replace('_', ' ')->title();
    $cleanTitle = trim((string) preg_replace('/\s*\(Sent to \d+ users\)\s*/i', '', (string) ($notification->title ?? '')));
    $messageDate = optional($notification->updated_at)->format('D d/m/Y h:i A');
    $rawMessage = (string) ($notification->message ?? '');
    $sentToNames = $recipientName;
    $sentToHtml = e($recipientName);
    $messageTextForScan = html_entity_decode(
        strip_tags(preg_replace('/<br\s*\/?>/i', "\n", $rawMessage)),
        ENT_QUOTES | ENT_HTML5,
        'UTF-8'
    );

    if (preg_match('/Recipients:\s*(.+)/i', $messageTextForScan, $matches)) {
        $extractedRecipients = trim(preg_replace('/\s+/', ' ', (string) $matches[1]));
        if ($extractedRecipients !== '') {
            $sentToNames = $extractedRecipients;
            $sentToHtml = e($extractedRecipients);
        }
    }

    if (preg_match('/Recipients:\s*(.+?)(?:<\/p>|<\/div>|<br\s*\/?>|\r?\n|$)/is', $rawMessage, $htmlMatches)) {
        $extractedRecipientsHtml = trim((string) $htmlMatches[1]);
        if ($extractedRecipientsHtml !== '') {
            $sentToHtml = $extractedRecipientsHtml;
        }
    }

    $cleanMessage = preg_replace('/<p[^>]*>\s*Recipients:\s*.*?<\/p>/is', '', $rawMessage);
    $cleanMessage = preg_replace('/<div[^>]*>\s*Recipients:\s*.*?<\/div>/is', '', $cleanMessage);
    $cleanMessage = preg_replace('/<span[^>]*>\s*Recipients:\s*.*?<\/span>/is', '', $cleanMessage);
    $cleanMessage = preg_replace('/Recipients:\s*.*?(?:<br\s*\/?>|\r?\n|$)/is', '', $cleanMessage);
    if ($sentToNames !== $recipientName) {
        $cleanMessage = str_ireplace('Recipients: ' . $sentToNames, '', $cleanMessage);
    }
    $cleanMessage = trim((string) $cleanMessage);
@endphp

<div class="notification-detail-page">
    <div class="image-viewer" id="detailImageViewer">
        <button type="button" class="image-viewer-close" id="detailImageViewerClose" aria-label="Close image viewer">&times;</button>
        <div class="image-viewer-stage">
            <button type="button" class="image-viewer-btn" id="detailImagePrev" aria-label="Previous image">&lt;</button>
            <img src="" alt="Full preview" class="image-viewer-img" id="detailImageViewerImg">
            <button type="button" class="image-viewer-btn" id="detailImageNext" aria-label="Next image">&gt;</button>
        </div>
        <div class="image-viewer-count" id="detailImageViewerCount">1 / 1</div>
    </div>

    <div class="page-wrap">
        <div class="detail-wrapper">
            <div class="detail-header">
                <div>
                    <h2 class="page-title">Notification Detail</h2>
                </div>
            </div>

            <div class="detail-card">
                <div class="top-user-box">
                    <div class="avatar-box large">
                        <img src="{{ $avatarSrc }}"
                             alt="{{ $displayName }}"
                             onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}'">
                    </div>

                    <div class="user-info-box">
                        <h3>{{ $displayName }}</h3>
                        {{-- <p>{{ $typeLabel }}</p> --}}
                        {{-- @if($recipientEmail)
                            <span class="profile-email">{{ $recipientEmail }}</span>
                        @endif --}}
                        <p class="sent-to-line">
                            <strong>Sent to:</strong> {!! $sentToHtml !!}
                        </p>
                        {{-- @if($messageDate)
                            <p>{{ $messageDate }}</p>
                        @endif --}}

                        <span class="status-pill {{ $notification->is_read ? 'read' : 'unread' }}">
                            {{ $notification->is_read ? 'Read' : 'Unread' }}
                        </span>
                    </div>
                </div>

                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Type</label>
                        <div class="plain-value">{{ $notification->type ?: 'N/A' }}</div>
                    </div>

                    <div class="detail-item">
                        <label>Date</label>
                        <div class="plain-value">{{ $messageDate ?: 'N/A' }}</div>
                    </div>

                    <div class="detail-item full">
                        <label>Message</label>
                        <div class="content-card">
                            <h4 class="content-title">{{ $cleanTitle !== '' ? $cleanTitle : 'Notification Message' }}</h4>
                            <div class="content-divider"></div>
                            <div class="rendered-message">
                                @if(filled($cleanMessage))
                                    {!! $cleanMessage !!}
                                @else
                                    <p>No message content available.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bottom-actions">
                    <a href="{{ route('admin.notifications.index') }}" class="back-btn secondary">Back to List</a>

                    <form action="{{ route('admin.notifications.destroy', $notification->id) }}"
                          method="POST"
                          class="js-detail-delete-form">
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

@push('scripts')
<script>
(function () {
    const messageRoot = document.querySelector('.notification-detail-page .rendered-message');
    const viewer = document.getElementById('detailImageViewer');
    const viewerImg = document.getElementById('detailImageViewerImg');
    const viewerCount = document.getElementById('detailImageViewerCount');
    const closeBtn = document.getElementById('detailImageViewerClose');
    const prevBtn = document.getElementById('detailImagePrev');
    const nextBtn = document.getElementById('detailImageNext');

    if (!messageRoot || !viewer || !viewerImg) return;

    const images = Array.from(messageRoot.querySelectorAll('img'));
    if (!images.length) return;

    let activeIndex = 0;

    function renderViewer() {
        const current = images[activeIndex];
        if (!current) return;

        viewerImg.src = current.currentSrc || current.src;
        viewerImg.alt = current.alt || 'Full preview';
        if (viewerCount) {
            viewerCount.textContent = `${activeIndex + 1} / ${images.length}`;
        }
        if (prevBtn) prevBtn.style.visibility = images.length > 1 ? 'visible' : 'hidden';
        if (nextBtn) nextBtn.style.visibility = images.length > 1 ? 'visible' : 'hidden';
    }

    function openViewer(index) {
        activeIndex = index;
        renderViewer();
        viewer.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeViewer() {
        viewer.classList.remove('show');
        document.body.style.overflow = '';
    }

    function stepViewer(direction) {
        if (images.length < 2) return;
        activeIndex = (activeIndex + direction + images.length) % images.length;
        renderViewer();
    }

    images.forEach(function (img, index) {
        img.addEventListener('click', function () {
            openViewer(index);
        });
    });

    closeBtn?.addEventListener('click', closeViewer);
    prevBtn?.addEventListener('click', function () { stepViewer(-1); });
    nextBtn?.addEventListener('click', function () { stepViewer(1); });

    viewer.addEventListener('click', function (event) {
        if (event.target === viewer) closeViewer();
    });

    document.addEventListener('keydown', function (event) {
        if (!viewer.classList.contains('show')) return;
        if (event.key === 'Escape') closeViewer();
        if (event.key === 'ArrowLeft') stepViewer(-1);
        if (event.key === 'ArrowRight') stepViewer(1);
    });
})();
</script>
@endpush
