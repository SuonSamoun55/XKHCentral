@extends('ManagementSystemViews.AdminViews.Layouts.app')

@section('title', 'Notification Detail')

@push('styles')
<style>
.main-wrapper{
gap:10px;
}
.content-area{
    background-color: white;
    border-radius: 12px;
}
.notification-detail-page *,
.notification-detail-page *::before,
.notification-detail-page *::after {
    box-sizing: border-box;
}

.notification-detail-page {
    
    font-family: Arial, Helvetica, sans-serif;
    color: #1f2937;
    min-height: 100vh;
}

.notification-detail-page .page-wrap {
    /* background:white; */
    padding: 24px;
}

.notification-detail-page .detail-wrapper {
    width: 100%;
    
}

.notification-detail-page .detail-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
    gap: 12px;
}

.notification-detail-page .page-title {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
    color: #2aaab5;
}

.notification-detail-page .page-subtitle {
    margin: 4px 0 0;
    color: #6b7280;
    font-size: 13px;
}

.notification-detail-page .detail-card {
    /* background: #fff;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 8px 30px rgba(15, 23, 42, 0.08); */
    /* width:100%; */
}

.notification-detail-page .top-user-box {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
    padding-bottom: 18px;
    border-bottom: 1px solid #e5e7eb;
}

.notification-detail-page .avatar-box.large {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    overflow: hidden;
    background: #d9e6f2;
    flex-shrink: 0;
}

.notification-detail-page .avatar-box.large img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.notification-detail-page .user-info-box h3 {
    margin: 0 0 4px;
    font-size: 20px;
    font-weight: 700;
}

.notification-detail-page .user-info-box p {
    margin: 0 0 6px;
    color: #64748b;
    font-size: 13px;
}

.notification-detail-page .profile-email {
    display: inline-block;
    margin-bottom: 10px;
    color: #475569;
    font-size: 13px;
}

.notification-detail-page .sent-to-line {
    margin: 0 0 10px;
    color: #2563eb;
    font-size: 13px;
    line-height: 1.5;
    word-break: break-word;
}

.notification-detail-page .sent-to-line strong {
    color: #0f172a;
}

.notification-detail-page .status-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 28px;
    padding: 0 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
}

.notification-detail-page .status-pill.unread {
    background: #e0f7fa;
    color: #0f7f8c;
}

.notification-detail-page .status-pill.read {
    background: #eef2f7;
    color: #64748b;
}

.notification-detail-page .detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
    margin-bottom: 22px;
}

.notification-detail-page .detail-item label {
    display: block;
    margin-bottom: 8px;
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
}

.notification-detail-page .plain-value {
    font-size: 14px;
    color: #111827;
}

.notification-detail-page .detail-item.full {
    grid-column: 1 / -1;
}

.notification-detail-page .content-card {
    min-height: 180px;
    padding: 20px;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    background: #f8fafc;
}

.notification-detail-page .content-title {
    margin: 0 0 14px;
    font-size: 22px;
    font-weight: 700;
    color: #0f172a;
}

.notification-detail-page .content-divider {
    height: 1px;
    background: #e2e8f0;
    margin-bottom: 16px;
}

.notification-detail-page .rendered-message {
    line-height: 1.7;
    color: #111827;
    font-size: 14px;
}

.notification-detail-page .rendered-message p {
    margin: 0 0 10px;
}

.notification-detail-page .rendered-message p:last-child {
    margin-bottom: 0;
}

.notification-detail-page .rendered-message strong {
    font-weight: 700;
    color: #111827;
}

.notification-detail-page .rendered-message em {
    font-style: italic;
}

.notification-detail-page .rendered-message ul,
.notification-detail-page .rendered-message ol {
    padding-left: 22px;
    margin: 8px 0;
}

.notification-detail-page .rendered-message li {
    margin-bottom: 4px;
}

.notification-detail-page .rendered-message h1,
.notification-detail-page .rendered-message h2,
.notification-detail-page .rendered-message h3,
.notification-detail-page .rendered-message h4,
.notification-detail-page .rendered-message h5,
.notification-detail-page .rendered-message h6 {
    margin: 0 0 10px;
    font-weight: 700;
    color: #111827;
}

.notification-detail-page .rendered-message blockquote {
    margin: 10px 0;
    padding: 10px 14px;
    border-left: 4px solid #cbd5e1;
    background: #f1f5f9;
    color: #334155;
    border-radius: 8px;
}

.notification-detail-page .rendered-message code {
    background: #eef2f7;
    padding: 2px 6px;
    border-radius: 6px;
    font-size: 13px;
}

.notification-detail-page .rendered-message a {
    color: #2563eb;
    text-decoration: underline;
    word-break: break-word;
}

.notification-detail-page .rendered-message a:hover {
    color: #1d4ed8;
}

.notification-detail-page .rendered-message img {
    display: block;
    max-width: 100%;
    height: auto;
    margin: 12px 0;
    border-radius: 12px;
    cursor: zoom-in;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
}

.notification-detail-page .image-viewer {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.84);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 2000;
    padding: 32px 88px;
}

.notification-detail-page .image-viewer.show {
    display: flex;
}

.notification-detail-page .image-viewer-stage {
    position: relative;
    width: 100%;
    max-width: 1100px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notification-detail-page .image-viewer-img {
    max-width: 100%;
    max-height: calc(100vh - 120px);
    border-radius: 16px;
    box-shadow: 0 20px 44px rgba(0, 0, 0, 0.28);
    background: #fff;
}

.notification-detail-page .image-viewer-btn,
.notification-detail-page .image-viewer-close {
    border: none;
    cursor: pointer;
    transition: background 0.15s ease, transform 0.15s ease;
}

.notification-detail-page .image-viewer-btn {
    width: 48px;
    height: 48px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.16);
    color: #fff;
    font-size: 24px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.notification-detail-page .image-viewer-btn:hover,
.notification-detail-page .image-viewer-close:hover {
    background: rgba(255, 255, 255, 0.28);
    transform: translateY(-1px);
}

.notification-detail-page .image-viewer-close {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 42px;
    height: 42px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.18);
    color: #fff;
    font-size: 22px;
    z-index: 2;
}

.notification-detail-page .image-viewer-count {
    position: absolute;
    left: 50%;
    bottom: 20px;
    transform: translateX(-50%);
    min-width: 74px;
    padding: 8px 14px;
    border-radius: 999px;
    background: rgba(15, 23, 42, 0.56);
    color: #fff;
    text-align: center;
    font-size: 12px;
    font-weight: 600;
}

.notification-detail-page .bottom-actions {
    margin-top: 24px;
    display: flex;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}

.notification-detail-page .back-btn,
.notification-detail-page .delete-btn {
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

.notification-detail-page .back-btn {
    background: #14b8c4;
    color: #fff;
}

.notification-detail-page .back-btn.secondary {
    background: #e5e7eb;
    color: #111827;
}

.notification-detail-page .delete-btn {
    background: #dc2626;
    color: #fff;
}

@media (max-width: 768px) {
    .notification-detail-page .detail-grid {
        grid-template-columns: 1fr;
    }

    .notification-detail-page .detail-header,
    .notification-detail-page .top-user-box,
    .notification-detail-page .bottom-actions {
        flex-direction: column;
        align-items: flex-start;
    }

    .notification-detail-page .image-viewer {
        padding: 24px 16px;
    }

    .notification-detail-page .image-viewer-stage {
        gap: 12px;
    }

    .notification-detail-page .image-viewer-btn {
        width: 42px;
        height: 42px;
        font-size: 20px;
    }
}
</style>
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
