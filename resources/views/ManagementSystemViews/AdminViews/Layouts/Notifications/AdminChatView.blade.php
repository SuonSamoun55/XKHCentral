@extends('ManagementSystemViews.AdminViews.Layouts.app')
<link rel="stylesheet" href="{{ asset('css/POSsystem/POSAdmin/notification/admin_chat_view.css') }}">
@section('title', 'Admin Chat')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush

@section('content')

{{-- ══════════════════════════════════════════════════════════
     LIGHTBOX OVERLAY (global, outside chat-page)
══════════════════════════════════════════════════════════ --}}
<div class="lightbox-overlay" id="lightboxOverlay">
    <button class="lightbox-nav lightbox-prev" id="lightboxPrev" type="button" aria-label="Previous image">&lt;</button>
    <button class="lightbox-close" id="lightboxClose">&#x2715;</button>
    <img class="lightbox-img" id="lightboxImg" src="" alt="Image preview">
    <button class="lightbox-nav lightbox-next" id="lightboxNext" type="button" aria-label="Next image">&gt;</button>
    <div class="lightbox-count" id="lightboxCount">1 / 1</div>
</div>

<div class="chat-page" id="chatPage">
    @php
        $activeContactIsOnline = $activeContact ? (bool) ($activeContact->is_online ?? false) : false;
        $activeContactStatusText = $activeContact
            ? ($activeContactIsOnline ? 'Online' : ((string) ($activeContact->offline_duration ?? 'Offline')))
            : '';
    @endphp

    {{-- ══════════════════════════════════════════════════════
         LEFT PANE — Conversation list
    ══════════════════════════════════════════════════════ --}}
    <aside class="conversation-pane">

        <div class="pane-header">
            <div class="pane-title-row">
                <a href="{{ route('admin.notifications.index') }}" class="pane-back-btn" aria-label="Back to notifications">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h3>Inbox</h3>
                @php $totalUnread = collect($contacts)->sum(fn($item) => (int) ($item->unread_count ?? 0)); @endphp
                <span class="pane-count">{{ $totalUnread }}</span>
                <button class="pane-compose-btn" type="button" aria-label="New message">
                    <i class="bi bi-pencil"></i>
                </button>
            </div>
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input id="contactSearch"
                       type="text"
                       class="search-input"
                       placeholder="Search">
            </div>
        </div>

        <div class="contact-list" id="contactList">
            <div id="contactListItems">
                @forelse($contacts as $contact)
                    @php
                        $contactIsOnline = (bool) ($contact->is_online ?? false);
                    @endphp
                    <a class="contact-item {{ (int)$activeContactId === (int)$contact->id ? 'active' : '' }}"
                       data-name="{{ strtolower($contact->name) }}"
                       data-last="{{ strtolower($contact->last_message ?: '') }}"
                       data-user-id="{{ (int) $contact->id }}"
                       href="{{ route('admin.chat.index', ['user_id' => $contact->id]) }}">

                        <div class="contact-avatar-wrap">
                            <img src="{{ $contact->chat_avatar ?? asset('images/pos/Rectangle 2.png') }}"
                                 class="contact-avatar"
                                 alt="{{ $contact->name }}">
                            <span class="contact-presence-dot {{ $contactIsOnline ? 'is-online' : 'is-offline' }}"></span>

                        </div>

                        <div class="contact-text">
                            <div class="contact-name-row">
                                <div class="contact-name">{{ $contact->name }}</div>
                                <div class="contact-time">
                                    {{ $contact->last_message_at
                                        ? \Carbon\Carbon::parse($contact->last_message_at)->format('g:i A')
                                        : '' }}
                                </div>
                            </div>
                            <div class="contact-last">{{ $contact->last_message ?: 'No message yet' }}</div>
                        </div>
                        @if((int)($contact->unread_count ?? 0) > 0)
                            <div class="contact-badge">{{ (int)$contact->unread_count }}</div>
                        @endif

                    </a>
                @empty
                    <div class="text-muted px-3 py-2" style="font-size:13px;color:#9ca3af">No chats yet.</div>
                @endforelse
            </div>
        </div>

    </aside>

    {{-- ══════════════════════════════════════════════════════
         MIDDLE PANE — Message thread
    ══════════════════════════════════════════════════════ --}}
    <section class="message-pane">

        {{-- Header --}}
        <header class="message-header">
            <div class="message-header-main" id="messageHeaderMain">
            @if($activeContact)
                <img class="header-avatar"
                     src="{{ $activeContact->chat_avatar ?? asset('images/pos/Rectangle 2.png') }}"
                     alt="{{ $activeContact->name }}">
                <div class="header-info">
                    <div class="peer-name">{{ $activeContact->name }}</div>
                    <div class="peer-status {{ $activeContactIsOnline ? 'is-online' : 'is-offline' }}" id="peerStatus">
                        {{ $activeContactStatusText }}
                    </div>
                </div>
            @else
                <div class="header-info">
                    <div class="peer-name">Admin Chat</div>
                </div>
            @endif
            </div>

            <div class="message-header-actions">
                <button class="icon-btn header-action-btn" type="button" title="Search">
                    <i class="bi bi-search"></i>
                </button>
                <button class="icon-btn header-toggle-btn" id="toggleInfoPane" title="Show / hide info panel" @if(!$activeContact) hidden @endif>
                    <i class="bi bi-info-circle"></i>
                </button>
            </div>
        </header>

        {{-- Message stream --}}
        <div class="message-stream" id="chatBody">
            @php $previousMsgDate = null; @endphp
            @forelse($messages as $msg)
                @php
                    $isMine       = (int)$msg->sender_id === (int)$currentUser->id;
                    $type         = $msg->message_type ?? 'text';
                    $attachUrl    = $msg->attachment_path
                                        ? ('/storage/' . ltrim($msg->attachment_path, '/'))
                                        : null;
                    $text         = (string)($msg->message ?? '');
                    $myAvatar     = $currentUser->chat_avatar  ?? asset('images/pos/Rectangle 2.png');
                    $peerAvatar   = $activeContact->chat_avatar ?? asset('images/pos/Rectangle 2.png');
                    $msgDate      = optional($msg->created_at)->format('Y-m-d');
                @endphp
                @if($msgDate && $msgDate !== $previousMsgDate)
                    <div class="chat-date-divider" data-date="{{ $msgDate }}">
                        <span>
                            {{ \Carbon\Carbon::parse($msgDate)->isToday()
                                ? 'Today'
                                : (\Carbon\Carbon::parse($msgDate)->isYesterday()
                                    ? 'Yesterday'
                                    : \Carbon\Carbon::parse($msgDate)->format('M d, Y')) }}
                        </span>
                    </div>
                    @php $previousMsgDate = $msgDate; @endphp
                @endif

                <div class="msg-row {{ $isMine ? 'mine' : 'other' }}">

                    <img src="{{ $isMine ? $myAvatar : $peerAvatar }}"
                         class="msg-avatar"
                         alt="Avatar">

                    <div class="msg-bubble">

                        @if($type === 'image' && $attachUrl)
                            {{-- Image message --}}
                            <img src="{{ $attachUrl }}"
                                 alt="Shared image"
                                 class="msg-image js-lightbox-trigger">
                            @if($text !== '' && $text !== '[Image]')
                                <div>{{ $text }}</div>
                            @endif

                        @elseif($type === 'voice' && $attachUrl)
                            {{-- Voice message --}}
                            <div class="msg-audio-row" data-src="{{ $attachUrl }}" data-mime="{{ $msg->attachment_mime ?? 'audio/webm' }}">
                                <button class="play-btn js-play-btn" type="button">
                                    <i class="bi bi-play-fill"></i>
                                </button>
                                <div class="voice-progress-wrap">
                                    <input type="range" class="voice-progress js-voice-progress" value="0" min="0" max="100" step="0.1">
                                </div>
                                <span class="wave-dur js-wave-dur">{{ $msg->voice_duration ?? '0:00' }}</span>
                                <select class="speed-select js-speed-select" title="Playback speed">
                                    <option value="1">1×</option>
                                    <option value="1.5">1.5×</option>
                                    <option value="2">2×</option>
                                </select>
                                <audio class="js-audio-el" preload="metadata">
                                    <source src="{{ $attachUrl }}"
                                            type="{{ $msg->attachment_mime ?? 'audio/webm' }}">
                                </audio>
                            </div>
                            @if($text !== '' && $text !== '[Voice message]')
                                <div>{{ $text }}</div>
                            @endif

                        @elseif($type === 'icon')
                            {{-- Emoji / icon message --}}
                            <div class="msg-icon-text">{{ $text }}</div>

                        @else
                            {{-- Plain text --}}
                            <div>{{ $text }}</div>
                        @endif

                        <small class="msg-time">
                            {{ optional($msg->created_at)->format('g:i A') }}
                            @if($isMine)
                                <span class="msg-tick">✓✓</span>
                            @endif
                        </small>

                    </div>
                </div>

            @empty
                <div class="text-muted" id="emptyChatText"
                     style="font-size:13px;color:#9ca3af;align-self:center;margin-top:40px">
                    Select a conversation to start chatting.
                </div>
            @endforelse
        </div>

        {{-- Sending animation --}}
        <div class="sending-indicator" id="sendingIndicator">
            <span>Sending</span>
            <div class="typing-dot"></div>
            <div class="typing-dot"></div>
            <div class="typing-dot"></div>
        </div>

        {{-- Image preview bar (shown after choosing an image) --}}
        <div class="img-preview-bar" id="imgPreviewBar">
            <img id="imgThumb" src="" alt="">
            <span id="imgFileName"></span>
            <button class="img-preview-remove" id="removeImgBtn" type="button">&#x2715;</button>
        </div>

        {{-- ── Composer ── --}}
        <div id="composerContainer">
        @if($activeContactId)
        <div class="composer-wrap">

            {{-- Emoji picker panel --}}
            <div class="emoji-panel" id="emojiPanel">
                @foreach(['😀','😂','😍','👍','🙏','🔥','🎉','😢','❤️','👏','😎','🤔'] as $em)
                    <button type="button" class="emoji-item" data-icon="{{ $em }}">{{ $em }}</button>
                @endforeach
            </div>

            <form class="composer"
                  id="adminChatForm"
                  action="{{ route('admin.chat.send') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="receiver_id" value="{{ $activeContactId }}">
                <input type="file"   id="imageInput"    accept="image/*" style="display:none">

                {{-- Attach image --}}
                <button type="button" class="icon-btn" id="attachButton" title="Send image">
                    <i class="bi bi-paperclip"></i>
                </button>

                <input type="text"
                       id="chatMessageInput"
                       name="message"
                       class="composer-input"
                       placeholder="Write a message"
                       autocomplete="off">

                {{-- Emoji picker toggle --}}
                <button type="button" class="icon-btn" id="emojiButton" title="Emoji">
                    <i class="bi bi-hand-thumbs-up"></i>
                </button>

                {{-- Voice record --}}
                <button type="button" class="icon-btn" id="voiceButton" title="Voice message">
                    <i class="bi bi-mic"></i>
                </button>

                <button type="button" class="voice-cancel-btn" id="voiceCancelButton" title="Cancel recording">
                    Cancel
                </button>

                {{-- Send --}}
                <button type="submit" class="send-btn" aria-label="Send message">
                    <i class="bi bi-send-fill"></i>
                </button>
            </form>

            <div class="composer-hint" id="composerHint">
                Click mic to record, click again to send. Press Esc to cancel.
            </div>

        </div>
        @endif
        </div>

    </section>

    {{-- ══════════════════════════════════════════════════════
         RIGHT PANE — Contact info
    ══════════════════════════════════════════════════════ --}}
    <div id="contactInfoPaneContainer">
    @if($activeContact)
    <aside class="contact-info-pane" id="contactInfoPane">

        <div class="rp-top">
            <img src="{{ $activeContact->chat_avatar ?? asset('images/pos/Rectangle 2.png') }}"
                 alt="{{ $activeContact->name }}">
            <h4>{{ $activeContact->name }}</h4>
            <div class="rp-status {{ $activeContactIsOnline ? 'is-online' : 'is-offline' }}" id="peerInfoStatus">
                {{ $activeContactStatusText }}
            </div>
        </div>

        <div class="rp-section">
            <h5>Info</h5>
            @if($activeContact->phone ?? null)
            <div class="rp-info-row">
                <i class="bi bi-telephone"></i>
                <span>{{ $activeContact->phone }}</span>
            </div>
            @endif
            @if($activeContact->email ?? null)
            <div class="rp-info-row">
                <i class="bi bi-envelope"></i>
                <span>{{ $activeContact->email }}</span>
            </div>
            @endif
            @if($activeContact->location ?? null)
            <div class="rp-info-row">
                <i class="bi bi-geo-alt"></i>
                <span>{{ $activeContact->location }}</span>
            </div>
            @endif
        </div>

        {{-- Shared media — images sent in this conversation --}}
        @php
            $sentImages = $messages->where('message_type', 'image')->where('attachment_path', '!=', null);
        @endphp
        @if($sentImages->count())
        <div class="rp-section">
            <h5>Shared media</h5>
            <div class="media-grid-scroll">
                @foreach($sentImages as $imgMsg)
                    @php
                        $imgUrl  = '/storage/' . ltrim($imgMsg->attachment_path, '/');
                        $imgDate = optional($imgMsg->created_at)->format('Y-m-d');
                        $imgLink = route('admin.chat.index', ['user_id' => $activeContact->id, 'date' => $imgDate]);
                    @endphp
                    <button type="button"
                            class="media-thumb-link js-lightbox-trigger"
                            data-full-src="{{ $imgUrl }}"
                            title="{{ optional($imgMsg->created_at)->format('M d, Y g:i A') }}">
                        <img class="media-thumb"
                             src="{{ $imgUrl }}"
                             alt="Shared image">
                    </button>
                @endforeach
            </div>
        </div>
        @elseif(!empty($sharedMedia) && count($sharedMedia))
        {{-- Fallback: controller-provided $sharedMedia --}}
        <div class="rp-section">
            <h5>Shared media</h5>
            <div class="media-grid-scroll">
                @foreach($sharedMedia as $media)
                    @php
                        $imgUrl  = '/storage/' . ltrim($media->attachment_path, '/');
                        $imgDate = optional($media->created_at)->format('Y-m-d');
                        $imgLink = route('admin.chat.index', ['user_id' => $activeContact->id, 'date' => $imgDate]);
                    @endphp
                    <button type="button"
                            class="media-thumb-link js-lightbox-trigger"
                            data-full-src="{{ $imgUrl }}"
                            title="{{ optional($media->created_at)->format('M d, Y g:i A') }}">
                        <img class="media-thumb"
                             src="{{ $imgUrl }}"
                             alt="Media">
                    </button>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Tags --}}
        @if(!empty($contactTags) && count($contactTags))
        <div class="rp-section">
            <h5>Tags</h5>
            <div class="tags-row">
                @foreach($contactTags as $tag)
                    <span class="tag">{{ $tag }}</span>
                @endforeach
            </div>
        </div>
        @endif

    </aside>
    @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';
    let form, input, attachButton, imageInput, emojiButton, emojiPanel, voiceButton, voiceCancelButton, composerHint, removeImgBtn;
    let imgPreviewBar, imgThumb, imgFileName, contactInfoPane, peerStatus, peerInfoStatus;

    const chatBody               = document.getElementById('chatBody');
    const contactSearch          = document.getElementById('contactSearch');
    const contactList            = document.getElementById('contactList');
    const contactListItems       = document.getElementById('contactListItems');
    const composerContainer      = document.getElementById('composerContainer');
    const infoPaneContainer      = document.getElementById('contactInfoPaneContainer');
    const messageHeaderMain      = document.getElementById('messageHeaderMain');
    const sendingIndicator       = document.getElementById('sendingIndicator');
    const lightboxOverlay        = document.getElementById('lightboxOverlay');
    const lightboxImg            = document.getElementById('lightboxImg');
    const lightboxClose          = document.getElementById('lightboxClose');
    const lightboxPrev           = document.getElementById('lightboxPrev');
    const lightboxNext           = document.getElementById('lightboxNext');
    const lightboxCount          = document.getElementById('lightboxCount');
    const toggleInfoPane         = document.getElementById('toggleInfoPane');

    const csrfToken              = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const currentUserId          = Number(@json((int) $currentUser->id));
    const myAvatar               = @json($currentUser->chat_avatar ?? asset('images/pos/Rectangle 2.png'));
    const chatIndexUrl           = @json(route('admin.chat.index'));
    const sendUrl                = @json(route('admin.chat.send'));
    const messagesUrl            = @json(route('admin.chat.messages'));

    let activeContactId          = Number(@json((int) $activeContactId));
    let peerAvatar               = @json($activeContact->chat_avatar ?? asset('images/pos/Rectangle 2.png'));
    let isSending                = false;
    let isLoadingConversation    = false;
    let lastId                   = Number(@json((int) ($messages->max('id') ?? 0)));
    let selectedImageFile        = null;
    let selectedVoiceFile        = null;
    let isRecording              = false;
    let mediaRecorder            = null;
    let recorderMimeType         = '';
    let recordedChunks           = [];
    let mediaStream              = null;
    let recordStartedAt          = 0;
    let recordTimer              = null;
    let shouldSend               = true;
    let lbZoomed                 = false;
    let lightboxImages           = [];
    let lightboxIndex            = 0;
    let lastRenderedDate         = @json(optional($messages->last()?->created_at)->format('Y-m-d'));
    let currentAudioEl           = null;
    let currentPlayBtn           = null;
    let contactsState            = [];

    const renderedIds = new Set(@json($messages->pluck('id')->map(fn($id) => (int)$id)->values()));

    function refreshDomRefs() {
        form              = document.getElementById('adminChatForm');
        input             = document.getElementById('chatMessageInput');
        attachButton      = document.getElementById('attachButton');
        imageInput        = document.getElementById('imageInput');
        emojiButton       = document.getElementById('emojiButton');
        emojiPanel        = document.getElementById('emojiPanel');
        voiceButton       = document.getElementById('voiceButton');
        voiceCancelButton = document.getElementById('voiceCancelButton');
        composerHint      = document.getElementById('composerHint');
        removeImgBtn      = document.getElementById('removeImgBtn');
        imgPreviewBar     = document.getElementById('imgPreviewBar');
        imgThumb          = document.getElementById('imgThumb');
        imgFileName       = document.getElementById('imgFileName');
        contactInfoPane   = document.getElementById('contactInfoPane');
        peerStatus        = document.getElementById('peerStatus');
        peerInfoStatus    = document.getElementById('peerInfoStatus');
    }

    function escapeHtml(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function setHint(text) {
        if (composerHint) composerHint.textContent = text || '';
    }

    function showSending(visible) {
        sendingIndicator?.classList.toggle('show', visible);
    }

    function updatePeerPresence(presence) {
        if (!presence) return;
        const isOnline = Boolean(presence.is_online);
        const statusText = String(presence.status_text || (isOnline ? 'Online' : 'Offline'));
        [peerStatus, peerInfoStatus].forEach(function (node) {
            if (!node) return;
            node.textContent = statusText;
            node.classList.toggle('is-online', isOnline);
            node.classList.toggle('is-offline', !isOnline);
        });
    }

    function scrollBottom() {
        if (chatBody) chatBody.scrollTop = chatBody.scrollHeight;
    }

    function removeEmptyState() {
        document.getElementById('emptyChatText')?.remove();
    }

    function nowFormatted() {
        const d = new Date();
        let h = d.getHours();
        const m = d.getMinutes();
        const ap = h >= 12 ? 'PM' : 'AM';
        h = h % 12 || 12;
        return `${h}:${String(m).padStart(2, '0')} ${ap}`;
    }

    function toDateKey(value) {
        if (!value) return '';
        const date = new Date(value);
        if (Number.isNaN(date.getTime())) return '';
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    function formatDateLabel(dateKey) {
        if (!dateKey) return '';
        const date = new Date(`${dateKey}T00:00:00`);
        if (Number.isNaN(date.getTime())) return dateKey;
        const today = new Date();
        const todayKey = toDateKey(today);
        const yesterday = new Date();
        yesterday.setDate(yesterday.getDate() - 1);
        const yesterdayKey = toDateKey(yesterday);
        if (dateKey === todayKey) return 'Today';
        if (dateKey === yesterdayKey) return 'Yesterday';
        return date.toLocaleDateString(undefined, { month: 'short', day: '2-digit', year: 'numeric' });
    }

    function appendDateDivider(dateKey) {
        if (!chatBody || !dateKey || dateKey === lastRenderedDate) return;
        const divider = document.createElement('div');
        divider.className = 'chat-date-divider';
        divider.dataset.date = dateKey;
        divider.innerHTML = `<span>${escapeHtml(formatDateLabel(dateKey))}</span>`;
        chatBody.appendChild(divider);
        lastRenderedDate = dateKey;
    }

    function formatDur(sec) {
        const m = Math.floor(sec / 60);
        const s = String(Math.floor(sec % 60)).padStart(2, '0');
        return `${m}:${s}`;
    }

    function syncLightboxControls() {
        const total = lightboxImages.length || 1;
        if (lightboxCount) lightboxCount.textContent = `${lightboxIndex + 1} / ${total}`;
        const showNav = total > 1;
        lightboxPrev?.classList.toggle('show', showNav);
        lightboxNext?.classList.toggle('show', showNav);
    }

    function openLightbox(src, images = [], index = 0) {
        if (!lightboxOverlay || !lightboxImg) return;
        lightboxImages = Array.isArray(images) && images.length ? images : [src];
        lightboxIndex = Math.max(0, Math.min(index, lightboxImages.length - 1));
        lightboxImg.src = lightboxImages[lightboxIndex] || src;
        lbZoomed = false;
        lightboxImg.classList.remove('zoomed');
        syncLightboxControls();
        lightboxOverlay.classList.add('show');
    }

    function stepLightbox(direction) {
        if (lightboxImages.length < 2 || !lightboxImg) return;
        lightboxIndex = (lightboxIndex + direction + lightboxImages.length) % lightboxImages.length;
        lightboxImg.src = lightboxImages[lightboxIndex];
        lbZoomed = false;
        lightboxImg.classList.remove('zoomed');
        syncLightboxControls();
    }

    function attachLightbox(scope) {
        if (!scope) return;
        const triggers = Array.from(scope.querySelectorAll('.js-lightbox-trigger'));
        if (!triggers.length) return;
        const imageSources = triggers.map(function (trigger) {
            return trigger.dataset.fullSrc || trigger.getAttribute('src') || trigger.querySelector('img')?.getAttribute('src') || '';
        }).filter(Boolean);
        triggers.forEach(function (trigger, index) {
            trigger.addEventListener('click', function (e) {
                e.preventDefault();
                const src = trigger.dataset.fullSrc || trigger.getAttribute('src') || trigger.querySelector('img')?.getAttribute('src') || '';
                if (!src) return;
                openLightbox(src, imageSources, index);
            });
        });
    }

    function stopCurrentAudio() {
        if (currentAudioEl && !currentAudioEl.paused) {
            currentAudioEl.pause();
            if (currentPlayBtn) currentPlayBtn.innerHTML = '<i class="bi bi-play-fill"></i>';
        }
        currentAudioEl = null;
        currentPlayBtn = null;
    }

    function attachPlayButtons(scope) {
        if (!scope) return;
        scope.querySelectorAll('.msg-audio-row').forEach(function (row) {
            const btn = row.querySelector('.js-play-btn');
            const audio = row.querySelector('.js-audio-el');
            const progress = row.querySelector('.js-voice-progress');
            const durEl = row.querySelector('.js-wave-dur');
            const speedSel = row.querySelector('.js-speed-select');
            if (!audio || !btn || row.dataset.bound === '1') return;
            row.dataset.bound = '1';

            audio.addEventListener('timeupdate', function () {
                if (!audio.duration) return;
                const pct = (audio.currentTime / audio.duration) * 100;
                if (progress) progress.value = pct;
                if (durEl) durEl.textContent = formatDur(audio.currentTime) + ' / ' + formatDur(audio.duration);
            });

            audio.addEventListener('loadedmetadata', function () {
                if (durEl && audio.duration) durEl.textContent = formatDur(audio.duration);
            });

            progress?.addEventListener('input', function () {
                if (!audio.duration) return;
                audio.currentTime = (progress.value / 100) * audio.duration;
            });

            speedSel?.addEventListener('change', function () {
                audio.playbackRate = parseFloat(speedSel.value);
            });

            btn.addEventListener('click', function () {
                if (!audio.paused) {
                    audio.pause();
                    btn.innerHTML = '<i class="bi bi-play-fill"></i>';
                    currentAudioEl = null;
                    currentPlayBtn = null;
                } else {
                    stopCurrentAudio();
                    audio.playbackRate = parseFloat(speedSel?.value || 1);
                    audio.play();
                    btn.innerHTML = '<i class="bi bi-pause-fill"></i>';
                    currentAudioEl = audio;
                    currentPlayBtn = btn;
                }
            });

            audio.addEventListener('ended', function () {
                btn.innerHTML = '<i class="bi bi-play-fill"></i>';
                if (progress) progress.value = 0;
                if (durEl && audio.duration) durEl.textContent = formatDur(audio.duration);
                if (currentAudioEl === audio) {
                    currentAudioEl = null;
                    currentPlayBtn = null;
                }
            });
        });
    }

    function buildBubbleInner(msg) {
        const type = msg.message_type || 'text';
        const text = escapeHtml(msg.message || '');
        const attachmentUrl = msg.attachment_url ? escapeHtml(msg.attachment_url) : '';
        const mime = escapeHtml(msg.attachment_mime || 'audio/webm');
        const dur = escapeHtml(msg.voice_duration || '0:00');

        if (type === 'image' && attachmentUrl) {
            const caption = (text && text !== '[Image]') ? `<div>${text}</div>` : '';
            return `<img src="${attachmentUrl}" alt="Shared image" class="msg-image js-lightbox-trigger">${caption}`;
        }
        if (type === 'voice' && attachmentUrl) {
            const caption = (text && text !== '[Voice message]') ? `<div>${text}</div>` : '';
            return `<div class="msg-audio-row" data-src="${attachmentUrl}" data-mime="${mime}">
                    <button type="button" class="play-btn js-play-btn"><i class="bi bi-play-fill"></i></button>
                    <div class="voice-progress-wrap">
                        <input type="range" class="voice-progress js-voice-progress" value="0" min="0" max="100" step="0.1">
                    </div>
                    <span class="wave-dur js-wave-dur">${dur}</span>
                    <select class="speed-select js-speed-select" title="Playback speed">
                        <option value="1">1×</option>
                        <option value="1.5">1.5×</option>
                        <option value="2">2×</option>
                    </select>
                    <audio class="js-audio-el" preload="metadata">
                        <source src="${attachmentUrl}" type="${mime}">
                    </audio>
                </div>${caption}`;
        }
        if (type === 'icon') return `<div class="msg-icon-text">${text}</div>`;
        return `<div>${text}</div>`;
    }

    function appendMessageRow(msg) {
        const msgId = Number(msg?.id || 0);
        if (msgId > 0 && renderedIds.has(msgId)) return;
        appendDateDivider(toDateKey(msg?.created_at));
        const isMine = Boolean(msg.is_mine ?? (Number(msg.sender_id) === currentUserId));
        const avatar = isMine ? escapeHtml(myAvatar) : escapeHtml(peerAvatar);
        const tick = isMine ? ' <span class="msg-tick">✓✓</span>' : '';
        const time = escapeHtml(msg.sent_at || nowFormatted());

        const row = document.createElement('div');
        row.className = `msg-row ${isMine ? 'mine' : 'other'}`;
        row.innerHTML = `<img src="${avatar}" class="msg-avatar" alt="Avatar">
            <div class="msg-bubble">
                ${buildBubbleInner(msg)}
                <small class="msg-time">${time}${tick}</small>
            </div>`;
        chatBody.appendChild(row);
        attachLightbox(row);
        attachPlayButtons(row);
        if (msgId > 0) renderedIds.add(msgId);
        removeEmptyState();
        scrollBottom();
    }

    function resetMessageState() {
        renderedIds.clear();
        lastRenderedDate = '';
        lastId = 0;
        stopCurrentAudio();
        if (chatBody) chatBody.innerHTML = '';
    }

    function renderMessages(messages) {
        resetMessageState();
        if (!Array.isArray(messages) || !messages.length) {
            chatBody.innerHTML = '<div class="text-muted" id="emptyChatText" style="font-size:13px;color:#9ca3af;align-self:center;margin-top:40px">Select a conversation to start chatting.</div>';
            return;
        }
        messages.forEach(appendMessageRow);
        lastId = Number(messages[messages.length - 1]?.id || 0);
    }

    function renderContacts(contacts) {
        contactsState = Array.isArray(contacts) ? contacts : [];
        if (!contactListItems) return;
        if (!contactsState.length) {
            contactListItems.innerHTML = '<div class="text-muted px-3 py-2" style="font-size:13px;color:#9ca3af">No chats yet.</div>';
            return;
        }
        contactListItems.innerHTML = `${contactsState.map(function (contact) {
            const isOnline = Boolean(contact.is_online);
            const badge = Number(contact.unread_count || 0) > 0 ? `<div class="contact-badge">${Number(contact.unread_count)}</div>` : '';
            const active = Number(contact.id) === Number(activeContactId) ? ' active' : '';
            return `<a class="contact-item${active}"
                    data-name="${escapeHtml(String(contact.name || '').toLowerCase())}"
                    data-last="${escapeHtml(String(contact.last_message || '').toLowerCase())}"
                    data-user-id="${Number(contact.id)}"
                    href="${chatIndexUrl}?user_id=${Number(contact.id)}">
                    <div class="contact-avatar-wrap">
                        <img src="${escapeHtml(contact.chat_avatar || '')}" class="contact-avatar" alt="${escapeHtml(contact.name || '')}">
                        <span class="contact-presence-dot ${isOnline ? 'is-online' : 'is-offline'}"></span>
                    </div>
                    <div class="contact-text">
                        <div class="contact-name-row">
                            <div class="contact-name">${escapeHtml(contact.name || '')}</div>
                            <div class="contact-time">${escapeHtml(contact.last_message_time || '')}</div>
                        </div>
                        <div class="contact-last">${escapeHtml(contact.last_message || 'No message yet')}</div>
                    </div>
                    ${badge}
                </a>`;
        }).join('')}`;
        applyContactSearchFilter();
    }

    function renderHeader(contact) {
        if (!messageHeaderMain) return;
        if (contact) {
            messageHeaderMain.innerHTML = `<img class="header-avatar" src="${escapeHtml(contact.chat_avatar || '')}" alt="${escapeHtml(contact.name || '')}">
                <div class="header-info">
                    <div class="peer-name">${escapeHtml(contact.name || '')}</div>
                    <div class="peer-status ${contact.is_online ? 'is-online' : 'is-offline'}" id="peerStatus">${escapeHtml(contact.status_text || (contact.is_online ? 'Online' : 'Offline'))}</div>
                </div>`;
            toggleInfoPane?.removeAttribute('hidden');
        } else {
            messageHeaderMain.innerHTML = `<div class="header-info"><div class="peer-name">Admin Chat</div></div>`;
            toggleInfoPane?.setAttribute('hidden', 'hidden');
        }
        refreshDomRefs();
    }

    function composerMarkup(contactId) {
        if (!contactId) return '';
        return `<div class="composer-wrap">
            <div class="emoji-panel" id="emojiPanel">
                ${['😀','😂','😍','👍','🙏','🔥','🎉','😢','❤️','👏','😎','🤔'].map(em => `<button type="button" class="emoji-item" data-icon="${em}">${em}</button>`).join('')}
            </div>
            <form class="composer" id="adminChatForm" action="${sendUrl}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="${escapeHtml(csrfToken)}">
                <input type="hidden" name="receiver_id" value="${Number(contactId)}">
                <input type="file" id="imageInput" accept="image/*" style="display:none">
                <button type="button" class="icon-btn" id="attachButton" title="Send image"><i class="bi bi-paperclip"></i></button>
                <input type="text" id="chatMessageInput" name="message" class="composer-input" placeholder="Write a message" autocomplete="off">
                <button type="button" class="icon-btn" id="emojiButton" title="Emoji"><i class="bi bi-hand-thumbs-up"></i></button>
                <button type="button" class="icon-btn" id="voiceButton" title="Voice message"><i class="bi bi-mic"></i></button>
                <button type="button" class="voice-cancel-btn" id="voiceCancelButton" title="Cancel recording">Cancel</button>
                <button type="submit" class="send-btn" aria-label="Send message"><i class="bi bi-send-fill"></i></button>
            </form>
            <div class="composer-hint" id="composerHint">Click mic to record, click again to send. Press Esc to cancel.</div>
        </div>`;
    }

    function renderComposer(contactId) {
        if (!composerContainer) return;
        composerContainer.innerHTML = composerMarkup(contactId);
        refreshDomRefs();
        selectedImageFile = null;
        selectedVoiceFile = null;
        bindComposerEvents();
    }

    function renderInfoPane(contact, sharedMedia) {
        if (!infoPaneContainer) return;
        if (!contact) {
            infoPaneContainer.innerHTML = '';
            refreshDomRefs();
            return;
        }

        const infoRows = [
            contact.phone ? `<div class="rp-info-row"><i class="bi bi-telephone"></i><span>${escapeHtml(contact.phone)}</span></div>` : '',
            contact.email ? `<div class="rp-info-row"><i class="bi bi-envelope"></i><span>${escapeHtml(contact.email)}</span></div>` : '',
            contact.location ? `<div class="rp-info-row"><i class="bi bi-geo-alt"></i><span>${escapeHtml(contact.location)}</span></div>` : '',
        ].join('');

        const mediaHtml = Array.isArray(sharedMedia) && sharedMedia.length
            ? `<div class="rp-section">
                <h5>Shared media</h5>
                <div class="media-grid-scroll">
                    ${sharedMedia.map(media => `<button type="button" class="media-thumb-link js-lightbox-trigger" data-full-src="${escapeHtml(media.url || '')}" title="${escapeHtml(media.title || '')}">
                            <img class="media-thumb" src="${escapeHtml(media.url || '')}" alt="Shared image">
                        </button>`).join('')}
                </div>
            </div>`
            : '';

        infoPaneContainer.innerHTML = `<aside class="contact-info-pane" id="contactInfoPane">
            <div class="rp-top">
                <img src="${escapeHtml(contact.chat_avatar || '')}" alt="${escapeHtml(contact.name || '')}">
                <h4>${escapeHtml(contact.name || '')}</h4>
                <div class="rp-status ${contact.is_online ? 'is-online' : 'is-offline'}" id="peerInfoStatus">${escapeHtml(contact.status_text || '')}</div>
            </div>
            <div class="rp-section">
                <h5>Info</h5>
                ${infoRows || '<div class="rp-info-row"><span>No extra contact info.</span></div>'}
            </div>
            ${mediaHtml}
        </aside>`;
        refreshDomRefs();
        attachLightbox(infoPaneContainer);
    }

    function clearImagePreview() {
        selectedImageFile = null;
        imgPreviewBar?.classList.remove('show');
        if (imgThumb) imgThumb.src = '';
        if (imgFileName) imgFileName.textContent = '';
        if (imageInput) imageInput.value = '';
    }

    function cleanupRecorder() {
        clearInterval(recordTimer);
        recordTimer = null;
        if (mediaRecorder && mediaRecorder.state !== 'inactive') {
            try { mediaRecorder.stop(); } catch (_) {}
        }
        if (mediaStream) {
            mediaStream.getTracks().forEach(function (t) { try { t.stop(); } catch (_) {} });
        }
        mediaRecorder = null;
        mediaStream = null;
        isRecording = false;
        voiceButton?.classList.remove('recording');
        voiceButton?.querySelector('i')?.setAttribute('class', 'bi bi-mic');
        voiceCancelButton?.classList.remove('show');
    }

    async function startVoiceRecording() {
        if (!navigator.mediaDevices || !window.MediaRecorder) {
            setHint('Voice recording is not supported in this browser.');
            return;
        }
        try {
            cleanupRecorder();
            mediaStream = await navigator.mediaDevices.getUserMedia({ audio: true });
            const preferred = ['audio/webm;codecs=opus', 'audio/webm', 'audio/ogg;codecs=opus', 'audio/mp4'];
            let recOpts;
            for (const mt of preferred) {
                if (window.MediaRecorder.isTypeSupported?.(mt)) {
                    recOpts = { mimeType: mt };
                    recorderMimeType = mt;
                    break;
                }
            }
            mediaRecorder = recOpts ? new MediaRecorder(mediaStream, recOpts) : new MediaRecorder(mediaStream);
            recordedChunks = [];
            shouldSend = true;
            recordStartedAt = Date.now();

            mediaRecorder.ondataavailable = function (e) {
                if (e.data?.size > 0) recordedChunks.push(e.data);
            };

            mediaRecorder.onstop = async function () {
                clearInterval(recordTimer);
                const durSec = Math.max(0, Math.round((Date.now() - recordStartedAt) / 1000));
                const mime = mediaRecorder.mimeType || recorderMimeType || 'audio/webm';
                const blob = new Blob(recordedChunks, { type: mime });
                if (mediaStream) mediaStream.getTracks().forEach(function (t) { t.stop(); });
                if (!shouldSend || blob.size === 0) {
                    setHint('Voice message canceled.');
                    cleanupRecorder();
                    return;
                }
                if (durSec < 1) {
                    setHint('Too short — hold a bit longer.');
                    cleanupRecorder();
                    return;
                }
                const ext = mime.includes('ogg') ? 'ogg' : (mime.includes('mp4') ? 'm4a' : 'webm');
                selectedVoiceFile = new File([blob], `voice-${Date.now()}.${ext}`, { type: mime });
                selectedImageFile = null;
                setHint('Voice recorded — sending...');
                await sendPayload({ message: input?.value || '', voiceFile: selectedVoiceFile });
                cleanupRecorder();
            };

            mediaRecorder.start();
            isRecording = true;
            voiceButton?.classList.add('recording');
            voiceButton?.querySelector('i')?.setAttribute('class', 'bi bi-mic-fill');
            voiceCancelButton?.classList.add('show');
            setHint('Recording... 0:00  (click mic again to send, Esc to cancel)');
            recordTimer = setInterval(function () {
                const sec = Math.max(0, Math.floor((Date.now() - recordStartedAt) / 1000));
                const mm = Math.floor(sec / 60);
                const ss = String(sec % 60).padStart(2, '0');
                setHint(`Recording... ${mm}:${ss}  (click mic again to send, Esc to cancel)`);
            }, 250);
        } catch (err) {
            const name = String(err?.name || '');
            const msgs = {
                NotAllowedError: 'Microphone blocked. Allow mic access and try again.',
                PermissionDeniedError: 'Microphone blocked. Allow mic access and try again.',
                NotFoundError: 'No microphone found.',
                NotReadableError: 'Microphone busy in another app. Close it and try again.',
                NotSupportedError: 'This browser does not support voice recording.',
            };
            setHint(msgs[name] || `Voice error: ${name || 'unknown'}`);
            cleanupRecorder();
        }
    }

    function stopVoiceRecording(send) {
        if (!mediaRecorder || mediaRecorder.state === 'inactive') return;
        shouldSend = !!send;
        mediaRecorder.stop();
        isRecording = false;
        voiceButton?.classList.remove('recording');
        voiceButton?.querySelector('i')?.setAttribute('class', 'bi bi-mic');
    }

    async function sendPayload({ message = '', icon = '', imageFile = null, voiceFile = null } = {}) {
        if (isSending || !activeContactId) return;
        const text = String(message || '').trim();
        const iconText = String(icon || '').trim();
        if (!text && !iconText && !imageFile && !voiceFile) {
            setHint('Please type a message or select media.');
            return;
        }

        isSending = true;
        if (input) input.disabled = true;
        showSending(true);

        try {
            const fd = new FormData();
            fd.append('receiver_id', String(activeContactId));
            fd.append('message', text);
            if (iconText) fd.append('icon', iconText);
            if (imageFile) fd.append('image', imageFile);
            if (voiceFile) fd.append('voice', voiceFile);

            const res = await fetch(sendUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: fd,
            });

            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                const msg = Object.values(err?.errors || {})[0]?.[0] || err?.message || 'Send failed.';
                throw new Error(msg);
            }

            const payload = await res.json();
            if (payload?.message) {
                appendMessageRow(payload.message);
                lastId = Math.max(lastId, Number(payload.message.id || 0));
            }

            const active = contactsState.find(c => Number(c.id) === Number(activeContactId));
            if (active) {
                active.last_message = payload?.message?.message_type === 'image'
                    ? '[Image]'
                    : (payload?.message?.message_type === 'voice'
                        ? '[Voice message]'
                        : String(payload?.message?.message || ''));
                active.last_message_time = payload?.message?.sent_at || nowFormatted();
            }
            renderContacts(contactsState);

            if (input) input.value = '';
            selectedImageFile = null;
            selectedVoiceFile = null;
            clearImagePreview();
            setHint('');
        } catch (err) {
            console.error('Send failed', err);
            setHint(err?.message || 'Failed to send. Try again.');
        } finally {
            isSending = false;
            showSending(false);
            if (input) {
                input.disabled = false;
                input.focus();
            }
        }
    }

    function bindComposerEvents() {
        if (!form || form.dataset.bound === '1') return;
        form.dataset.bound = '1';

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            sendPayload({ message: input?.value, imageFile: selectedImageFile, voiceFile: selectedVoiceFile });
        });

        input?.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendPayload({ message: input.value, imageFile: selectedImageFile, voiceFile: selectedVoiceFile });
            }
        });

        attachButton?.addEventListener('click', function () { imageInput?.click(); });

        imageInput?.addEventListener('change', function () {
            const file = imageInput.files?.[0];
            if (!file) return;
            selectedImageFile = file;
            selectedVoiceFile = null;
            if (imgThumb) imgThumb.src = URL.createObjectURL(file);
            if (imgFileName) imgFileName.textContent = file.name;
            imgPreviewBar?.classList.add('show');
            setHint('Image ready — press Send or Enter.');
        });

        removeImgBtn?.addEventListener('click', function () {
            clearImagePreview();
            setHint('');
        });

        emojiButton?.addEventListener('click', function (e) {
            e.stopPropagation();
            emojiPanel?.classList.toggle('show');
        });

        emojiPanel?.addEventListener('click', function (e) {
            const btn = e.target.closest('[data-icon]');
            if (!btn) return;
            const icon = btn.getAttribute('data-icon') || '';
            emojiPanel.classList.remove('show');
            sendPayload({ icon });
        });

        voiceButton?.addEventListener('click', function (e) {
            e.preventDefault();
            isRecording ? stopVoiceRecording(true) : startVoiceRecording();
        });

        voiceCancelButton?.addEventListener('click', function (e) {
            e.preventDefault();
            if (isRecording) stopVoiceRecording(false);
        });

        form.querySelector('.send-btn')?.addEventListener('click', function () {
            if (isRecording) stopVoiceRecording(true);
        });
    }

    function applyContactSearchFilter() {
        const kw = (contactSearch?.value || '').trim().toLowerCase();
        contactList?.querySelectorAll('.contact-item').forEach(function (item) {
            const match = (item.dataset.name || '').includes(kw) || (item.dataset.last || '').includes(kw);
            item.style.display = match ? '' : 'none';
        });
    }

    async function loadConversation(userId, pushHistory = true) {
        if (!userId || isLoadingConversation) return;
        isLoadingConversation = true;
        cleanupRecorder();
        showSending(true);

        try {
            const res = await fetch(`${chatIndexUrl}?user_id=${encodeURIComponent(userId)}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (!res.ok) throw new Error('Failed to load chat.');
            const payload = await res.json();

            activeContactId = Number(payload.active_contact_id || 0);
            peerAvatar = payload.active_contact?.chat_avatar || myAvatar;

            renderContacts(payload.contacts || []);
            renderHeader(payload.active_contact || null);
            renderMessages(payload.messages || []);
            renderComposer(activeContactId);
            renderInfoPane(payload.active_contact || null, payload.shared_media || []);
            refreshDomRefs();
            bindComposerEvents();
            updatePeerPresence(payload.active_contact || null);
            clearImagePreview();
            setHint('');
            scrollBottom();

            if (pushHistory) {
                const url = new URL(chatIndexUrl, window.location.origin);
                if (activeContactId) url.searchParams.set('user_id', String(activeContactId));
                window.history.pushState({ user_id: activeContactId }, '', url.toString());
            }
        } catch (err) {
            console.error('Conversation load failed', err);
            setHint(err?.message || 'Failed to load conversation.');
        } finally {
            isLoadingConversation = false;
            showSending(false);
        }
    }

    async function pollMessages() {
        if (!activeContactId || isLoadingConversation) return;
        try {
            const res = await fetch(`${messagesUrl}?user_id=${activeContactId}&after_id=${lastId}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (!res.ok) return;
            const payload = await res.json();
            const incoming = Array.isArray(payload.messages) ? payload.messages : [];
            updatePeerPresence(payload.contact_presence || null);
            incoming.forEach(appendMessageRow);
            if (incoming.length) {
                lastId = Number(payload.last_id || lastId);
                const active = contactsState.find(c => Number(c.id) === Number(activeContactId));
                const latest = incoming[incoming.length - 1];
                if (active && latest) {
                    active.last_message = latest.message_type === 'image'
                        ? '[Image]'
                        : (latest.message_type === 'voice'
                            ? '[Voice message]'
                            : String(latest.message || ''));
                    active.last_message_time = latest.sent_at || active.last_message_time;
                }
                renderContacts(contactsState);
            }
        } catch (err) {
            console.error('Chat poll failed', err);
        }
    }

    lightboxClose?.addEventListener('click', function () {
        lightboxOverlay.classList.remove('show');
    });
    lightboxPrev?.addEventListener('click', function (e) {
        e.stopPropagation();
        stepLightbox(-1);
    });
    lightboxNext?.addEventListener('click', function (e) {
        e.stopPropagation();
        stepLightbox(1);
    });
    lightboxOverlay?.addEventListener('click', function (e) {
        if (e.target === lightboxOverlay) lightboxOverlay.classList.remove('show');
    });
    lightboxImg?.addEventListener('click', function () {
        lbZoomed = !lbZoomed;
        lightboxImg.classList.toggle('zoomed', lbZoomed);
    });

    document.addEventListener('keydown', function (e) {
        if (lightboxOverlay?.classList.contains('show')) {
            if (e.key === 'Escape') lightboxOverlay.classList.remove('show');
            if (e.key === 'ArrowLeft') stepLightbox(-1);
            if (e.key === 'ArrowRight') stepLightbox(1);
        }
        if (e.key === 'Escape' && isRecording) stopVoiceRecording(false);
    });

    document.addEventListener('click', function (e) {
        if (emojiPanel && !emojiPanel.contains(e.target) && !emojiButton?.contains(e.target)) {
            emojiPanel.classList.remove('show');
        }
    });

    contactSearch?.addEventListener('input', applyContactSearchFilter);

    contactList?.addEventListener('click', function (e) {
        const item = e.target.closest('.contact-item[data-user-id]');
        if (!item) return;
        e.preventDefault();
        const userId = Number(item.dataset.userId || 0);
        if (!userId || userId === activeContactId) return;
        loadConversation(userId, true);
    });

    toggleInfoPane?.addEventListener('click', function () {
        if (!contactInfoPane) return;
        const hidden = contactInfoPane.classList.toggle('pane-hidden');
        toggleInfoPane.querySelector('i').className = hidden ? 'bi bi-info-circle' : 'bi bi-info-circle-fill';
    });

    window.addEventListener('popstate', function () {
        const userId = Number(new URL(window.location.href).searchParams.get('user_id') || 0);
        if (userId && userId !== activeContactId) loadConversation(userId, false);
    });

    window.addEventListener('beforeunload', cleanupRecorder);
    document.addEventListener('visibilitychange', function () {
        if (document.hidden && isRecording) stopVoiceRecording(false);
    });

    refreshDomRefs();
    bindComposerEvents();
    attachLightbox(chatBody);
    attachLightbox(infoPaneContainer);
    attachPlayButtons(chatBody);
    applyContactSearchFilter();
    scrollBottom();
    setInterval(pollMessages, 2500);

})();
</script>
@endpush
