@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'User Chat')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/views/POSViews/POSUserViews/Notifications/chat.css') }}">
@endpush

@section('content')

{{-- LIGHTBOX OVERLAY (global, outside chat-page) --}}
<div class="lightbox-overlay" id="lightboxOverlay">
    <button class="lightbox-nav lightbox-prev" id="lightboxPrev" type="button" aria-label="Previous image">&lt;</button>
    <button class="lightbox-close" id="lightboxClose">&#x2715;</button>
    <img class="lightbox-img" id="lightboxImg" src="" alt="Image preview">
    <button class="lightbox-nav lightbox-next" id="lightboxNext" type="button" aria-label="Next image">&gt;</button>
    <div class="lightbox-count" id="lightboxCount">1 / 1</div>
</div>

<div class="chat-page {{ $activeContactId ? 'has-active-chat' : 'no-active-chat' }}">
    @php
        $activeContactIsOnline = $activeContact ? (bool) ($activeContact->is_online ?? false) : false;
        $activeContactStatusText = $activeContact
            ? ($activeContactIsOnline ? 'Online' : ((string) ($activeContact->offline_duration ?? 'Offline')))
            : '';
    @endphp

    <aside class="conversation-pane">
        <div class="inbox-header">
            <div class="inbox-left">

                <a href="{{ route('user.notifications') }}" class="inbox-back">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <span class="inbox-title">Inbox</span>

                <a href="{{ route('user.chat.index', ['admin_id' => $activeContactId ?: optional($contacts->first())->id]) }}"
                    @if (($orderNotificationCount ?? 0) > 0)
                        <span class="inbox-action-badge">{{ $orderNotificationCount }}</span>
                    @endif
                </a>

                <button class="inbox-action">
                    <i class="bi bi-pencil"></i>
                </button>
            </div>

        </div>

        <div class="search-wrap">
            <i class="bi bi-search"></i>
            <input id="contactSearch" type="text" class="search-input" placeholder="Search...">
        </div>

        <div class="contact-list" id="contactList">
            @forelse($contacts as $contact)
                <a class="contact-item {{ (int) $activeContactId === (int) $contact->id ? 'active' : '' }}"
                    data-name="{{ strtolower($contact->name) }}"
                    data-last="{{ strtolower($contact->last_message ?: '') }}"
                    href="{{ route('user.chat.index', ['admin_id' => $contact->id]) }}">
                    <div class="contact-avatar-wrap">
                        <img src="{{ $contact->chat_avatar ?? asset('images/pos/Rectangle 2.png') }}"
                            class="contact-avatar" alt="{{ $contact->name }}">
                        @if ((int) ($contact->unread_count ?? 0) > 0)
                            <div class="contact-badge">{{ (int) $contact->unread_count }}</div>
                        @endif
                    </div>
                    <div class="contact-text">
                        <div class="contact-name-row">
                            <div class="contact-name">{{ $contact->name }}</div>
                            <div class="contact-time">
                                {{ $contact->last_message_at ? \Carbon\Carbon::parse($contact->last_message_at)->format('gA') : '' }}
                            </div>
                        </div>
                        <div class="contact-last">{{ $contact->last_message ?: 'No message yet' }}</div>
                    </div>
                </a>
            @empty
                <div class="text-muted">No admin contact yet.</div>
            @endforelse
        </div>


    </aside>

    <section class="message-pane">
        <header class="mobile-chat-header">
            <div class="header-left">
                <a href="{{ route('user.chat.index') }}" class="back-btn" title="Back to inbox">
                    <i class="bi bi-arrow-left"></i>
                </a>

                <img src="{{ $activeContact->chat_avatar ?? asset('images/pos/Rectangle 2.png') }}"
                    class="header-avatar" alt="Avatar">

                <div class="header-meta">
                    <div class="header-name">
                        Admin
                    </div>
                    <div class="header-status">
                        <span class="status-dot"></span> Online
                    </div>
                </div>
            </div>

            <div class="header-right">
                <button type="button" class="header-icon" id="toggleInfoPaneMobile" title="View contact info & shared images" @if(!$activeContact) hidden @endif>
                    <i class="bi bi-info-circle"></i>
                </button>
            </div>
        </header>

        {{-- Desktop / tablet header --}}
        <header class="message-header">
            @if($activeContact)
                <div class="contact-avatar-wrap">
                    <img src="{{ $activeContact->chat_avatar ?? asset('images/pos/Rectangle 2.png') }}"
                        class="contact-avatar" alt="{{ $activeContact->name }}">
                </div>
                <div class="header-info">
                    <div class="peer-name">{{ $activeContact->name }}</div>
                </div>
            @else
                <div class="header-info">
                    <div class="peer-name">Admin</div>
                </div>
            @endif

            {{-- Toggle right info panel --}}
            <button class="icon-btn header-toggle-btn" id="toggleInfoPane" title="Show / hide info panel" @if(!$activeContact) hidden @endif>
                <i class="bi bi-layout-sidebar-reverse"></i>
            </button>
        </header>

        <div class="message-stream" id="chatBody">
            @forelse($messages as $msg)
                @php
                    $isMine = (int) $msg->sender_id === (int) $currentUser->id;
                    $type = $msg->message_type ?? 'text';
                    $attachmentUrl = $msg->attachment_path ? '/storage/' . ltrim($msg->attachment_path, '/') : null;
                    $text = (string) ($msg->message ?? '');
                    $myAvatarSrc = $currentUser->profile_image_display ?? asset('images/pos/Rectangle 2.png');
                    $peerAvatarSrc = $activeContact->chat_avatar ?? asset('images/pos/Rectangle 2.png');
                @endphp
                <div class="msg-row {{ $isMine ? 'mine' : 'other' }}">
                    <img src="{{ $isMine ? $myAvatarSrc : $peerAvatarSrc }}" class="msg-avatar" alt="Avatar">

                    @if ($type === 'order')
                        @php
                            $order = json_decode($text ?: '{}', true) ?: [];
                            $orderStatus = $order['status'] ?? 'pending';
                            $orderStatusLabel = $order['status_label'] ?? ucfirst($orderStatus);
                            $orderInvoice = $order['invoice_number'] ?? '—';
                        @endphp
                        <button type="button" class="order-msg-card status-{{ $orderStatus }}"
                            data-order='@json($order)'>
                            <i class="bi bi-box-seam order-msg-icon"></i>
                            <div class="order-msg-text">
                                <div class="order-msg-title">Order {{ $orderInvoice }}</div>
                                <div class="order-msg-sub">{{ $orderStatusLabel }} · Tap to view</div>
                            </div>
                            <i class="bi bi-chevron-right order-msg-chevron"></i>
                        </button>

                    @elseif ($type === 'voice')
                        <div class="msg-bubble msg-voice">
                            @if ($attachmentUrl)
                                <audio controls class="msg-audio-row">
                                    <source src="{{ $attachmentUrl }}" type="{{ $msg->attachment_mime ?? 'audio/webm' }}">
                                </audio>
                                @if ($text !== '' && $text !== '[Voice message]')
                                    <div>{{ $text }}</div>
                                @endif
                            @endif
                            <small class="msg-time">
                                {{ optional($msg->created_at)->format('g:i A') }}
                                <i class="bi bi-check2-all msg-check"></i>
                            </small>
                        </div>

                    @else
                        <div class="msg-bubble">
                            @if ($type === 'image' && $attachmentUrl)
                                <img src="{{ $attachmentUrl }}" alt="Shared image" class="msg-image js-lightbox-trigger">
                                @if ($text !== '' && $text !== '[Image]')
                                    <div>{{ $text }}</div>
                                @endif
                            @elseif($type === 'icon')
                                <div class="msg-icon-text">{{ $text }}</div>
                            @else
                                <div>{{ $text }}</div>
                            @endif
                            <small class="msg-time">
                                {{ optional($msg->created_at)->format('g:i A') }}
                                <i class="bi bi-check2-all msg-check"></i>
                            </small>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-muted" id="emptyChatText">Start chatting with admin.</div>
            @endforelse
        </div>

        <div class="order-detail-overlay" id="orderDetailOverlay">
            <div class="order-detail-header">
                <button type="button" class="order-detail-back" id="orderDetailBack">
                    <i class="bi bi-arrow-left"></i>
                </button>
                <div class="order-detail-title">Order Detail</div>
            </div>

            <div class="order-detail-banner" id="orderDetailBanner">
                <i class="bi bi-box-seam order-detail-banner-icon"></i>
                <div>
                    <div class="order-detail-banner-title" id="orderDetailBannerTitle">Order is pending</div>
                    <div class="order-detail-banner-sub" id="orderDetailBannerSub">We will notify you by inbox</div>
                </div>
            </div>

            <div class="order-detail-list" id="orderDetailList">
                {{-- rows injected by JS --}}
            </div>
        </div>

        @if ($activeContactId)
            <div class="composer-wrap">
                <div class="emoji-panel" id="emojiPanel">
                    <button type="button" class="emoji-item" data-icon="😀">😀</button>
                    <button type="button" class="emoji-item" data-icon="😂">😂</button>
                    <button type="button" class="emoji-item" data-icon="😍">😍</button>
                    <button type="button" class="emoji-item" data-icon="👍">👍</button>
                    <button type="button" class="emoji-item" data-icon="🙏">🙏</button>
                    <button type="button" class="emoji-item" data-icon="🔥">🔥</button>
                    <button type="button" class="emoji-item" data-icon="🎉">🎉</button>
                    <button type="button" class="emoji-item" data-icon="😢">😢</button>
                </div>

                <form class="composer" id="userChatForm" action="{{ route('user.chat.send') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $activeContactId }}">
                    <input type="file" id="imageInput" accept="image/*" class="d-none">
                    <input type="text" id="chatMessageInput" name="message" class="composer-input"
                        placeholder="Type a message...">

                    <button type="button" id="attachButton" class="icon-btn" title="Send image">
                        <i class="bi bi-paperclip"></i>
                    </button>
                    <button type="button" id="emojiButton" class="icon-btn" title="Send icon">
                        <i class="bi bi-emoji-smile"></i>
                    </button>
                    <button type="button" id="voiceButton" class="icon-btn" title="Click to record voice">
                        <i class="bi bi-mic"></i>
                    </button>
                    <button type="button" class="voice-cancel-btn" id="voiceCancelButton" title="Cancel recording">
                        Cancel
                    </button>
                    <button type="submit" class="send-btn">
                        <span>Send</span>
                        <i class="bi bi-send-fill"></i>
                    </button>
                </form>
                <div class="composer-hint" id="composerHint">Click mic to start recording, click again to send. Press Esc to cancel.</div>
            </div>
        @endif
    </section>

    {{-- RIGHT PANE — Contact info, toggled by header button --}}
    @if ($activeContact)
        <aside class="contact-info-pane pane-hidden" id="contactInfoPane">

            <button type="button" class="info-pane-close" id="closeInfoPane" title="Close">
                <i class="bi bi-x-lg"></i>
            </button>

            <div class="rp-top">
                <img src="{{ $activeContact->chat_avatar ?? asset('images/pos/Rectangle 2.png') }}"
                    alt="{{ $activeContact->name }}">
                <h4>{{ $activeContact->name }}</h4>
                <div class="rp-status {{ $activeContactIsOnline ? 'is-online' : 'is-offline' }}">
                    {{ $activeContactStatusText }}
                </div>
            </div>

            <div class="rp-section">
                <h5>Info</h5>
                @if ($activeContact->phone ?? null)
                    <div class="rp-info-row">
                        <i class="bi bi-telephone"></i>
                        <span>{{ $activeContact->phone }}</span>
                    </div>
                @endif
                @if ($activeContact->email ?? null)
                    <div class="rp-info-row">
                        <i class="bi bi-envelope"></i>
                        <span>{{ $activeContact->email }}</span>
                    </div>
                @endif
                @if (!($activeContact->phone ?? null) && !($activeContact->email ?? null))
                    <div class="rp-info-row"><span>No extra contact info.</span></div>
                @endif
            </div>

            {{-- Shared media — images sent in this conversation --}}
            @php
                $sentImages = $messages->where('message_type', 'image')->where('attachment_path', '!=', null);
            @endphp
            @if ($sentImages->count())
                <div class="rp-section">
                    <h5>Shared media</h5>
                    <div class="media-grid-scroll">
                        @foreach ($sentImages as $imgMsg)
                            @php $imgUrl = '/storage/' . ltrim($imgMsg->attachment_path, '/'); @endphp
                            <button type="button"
                                class="media-thumb-link js-lightbox-trigger"
                                data-full-src="{{ $imgUrl }}"
                                title="{{ optional($imgMsg->created_at)->format('M d, Y g:i A') }}">
                                <img class="media-thumb" src="{{ $imgUrl }}" alt="Shared image">
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

        </aside>
    @endif

</div>
@endsection

@push('scripts')
    <script>
        (function() {
            const chatBody = document.getElementById('chatBody');
            const form = document.getElementById('userChatForm');
            const input = document.getElementById('chatMessageInput');
            const contactSearch = document.getElementById('contactSearch');
            const contactList = document.getElementById('contactList');
            const attachButton = document.getElementById('attachButton');
            const imageInput = document.getElementById('imageInput');
            const emojiButton = document.getElementById('emojiButton');
            const emojiPanel = document.getElementById('emojiPanel');
            const voiceButton = document.getElementById('voiceButton');
            const voiceCancelButton = document.getElementById('voiceCancelButton');
            const composerHint = document.getElementById('composerHint');
            const toggleInfoPane = document.getElementById('toggleInfoPane');
            const toggleInfoPaneMobile = document.getElementById('toggleInfoPaneMobile');
            const closeInfoPane = document.getElementById('closeInfoPane');
            const contactInfoPane = document.getElementById('contactInfoPane');

            const lightboxOverlay = document.getElementById('lightboxOverlay');
            const lightboxImg = document.getElementById('lightboxImg');
            const lightboxClose = document.getElementById('lightboxClose');
            const lightboxPrev = document.getElementById('lightboxPrev');
            const lightboxNext = document.getElementById('lightboxNext');
            const lightboxCount = document.getElementById('lightboxCount');

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            const activeContactId = Number(@json((int) $activeContactId));
            const currentUserId = Number(@json((int) $currentUser->id));
            const myAvatar = @json($currentUser->profile_image_display ?? asset('images/pos/Rectangle 2.png'));
            const peerAvatar = @json($activeContact->chat_avatar ?? asset('images/pos/Rectangle 2.png'));
            const sendUrl = @json(route('user.chat.send'));
            const messagesUrl = @json(route('user.chat.messages'));

            let lbZoomed = false;
            let lightboxImages = [];
            let lightboxIndex = 0;

            if (contactSearch && contactList) {
                contactSearch.addEventListener('input', function() {
                    const keyword = this.value.trim().toLowerCase();
                    const items = contactList.querySelectorAll('.contact-item');
                    items.forEach(function(item) {
                        const name = item.dataset.name || '';
                        const last = item.dataset.last || '';
                        item.style.display = name.includes(keyword) || last.includes(keyword) ? '' :
                            'none';
                    });
                });
            }

            // ===== Info panel toggle (desktop/tablet) =====
            toggleInfoPane?.addEventListener('click', function() {
                if (!contactInfoPane) return;
                const hidden = contactInfoPane.classList.toggle('pane-hidden');
                const icon = toggleInfoPane.querySelector('i');
                if (icon) {
                    icon.className = hidden ? 'bi bi-layout-sidebar-inset-reverse' : 'bi bi-layout-sidebar-reverse';
                }
            });

            // ===== Info panel toggle (mobile — slides in as full overlay) =====
            toggleInfoPaneMobile?.addEventListener('click', function() {
                contactInfoPane?.classList.add('mobile-open');
            });

            closeInfoPane?.addEventListener('click', function() {
                contactInfoPane?.classList.remove('mobile-open');
            });

            // ===== Lightbox (click-to-view images) =====
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
                const imageSources = triggers.map(function(trigger) {
                    return trigger.dataset.fullSrc || trigger.getAttribute('src') || trigger.querySelector('img')?.getAttribute('src') || '';
                }).filter(Boolean);
                triggers.forEach(function(trigger, index) {
                    if (trigger.dataset.lbBound === '1') return;
                    trigger.dataset.lbBound = '1';
                    trigger.addEventListener('click', function(e) {
                        e.preventDefault();
                        const src = trigger.dataset.fullSrc || trigger.getAttribute('src') || trigger.querySelector('img')?.getAttribute('src') || '';
                        if (!src) return;
                        const freshTriggers = Array.from(scope.querySelectorAll('.js-lightbox-trigger'));
                        const freshSources = freshTriggers.map(t => t.dataset.fullSrc || t.getAttribute('src') || t.querySelector('img')?.getAttribute('src') || '').filter(Boolean);
                        const freshIndex = freshTriggers.indexOf(trigger);
                        openLightbox(src, freshSources.length ? freshSources : imageSources, freshIndex >= 0 ? freshIndex : index);
                    });
                });
            }

            lightboxClose?.addEventListener('click', function() {
                lightboxOverlay.classList.remove('show');
            });
            lightboxPrev?.addEventListener('click', function(e) {
                e.stopPropagation();
                stepLightbox(-1);
            });
            lightboxNext?.addEventListener('click', function(e) {
                e.stopPropagation();
                stepLightbox(1);
            });
            lightboxOverlay?.addEventListener('click', function(e) {
                if (e.target === lightboxOverlay) lightboxOverlay.classList.remove('show');
            });
            lightboxImg?.addEventListener('click', function() {
                lbZoomed = !lbZoomed;
                lightboxImg.classList.toggle('zoomed', lbZoomed);
            });
            document.addEventListener('keydown', function(e) {
                if (lightboxOverlay?.classList.contains('show')) {
                    if (e.key === 'Escape') lightboxOverlay.classList.remove('show');
                    if (e.key === 'ArrowLeft') stepLightbox(-1);
                    if (e.key === 'ArrowRight') stepLightbox(1);
                }
                if (e.key === 'Escape' && contactInfoPane?.classList.contains('mobile-open')) {
                    contactInfoPane.classList.remove('mobile-open');
                }
            });

            attachLightbox(chatBody);
            attachLightbox(contactInfoPane);

            if (!chatBody || !form || !input || !activeContactId) {
                return;
            }

            let isSending = false;
            let lastId = Number(@json((int) ($messages->max('id') ?? 0)));
            const renderedMessageIds = new Set(@json($messages->pluck('id')->map(fn($id) => (int) $id)->values()));
            let selectedImageFile = null;
            let selectedVoiceFile = null;
            let isRecording = false;
            let mediaRecorder = null;
            let recorderMimeType = '';
            let recordedChunks = [];
            let mediaStream = null;
            let recordStartedAt = 0;
            let recordTimer = null;
            let shouldSendRecordedVoice = true;

            function cleanupRecorderResources() {
                if (recordTimer) {
                    clearInterval(recordTimer);
                    recordTimer = null;
                }

                if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                    try {
                        mediaRecorder.stop();
                    } catch (error) {
                        console.warn('Recorder stop warning', error);
                    }
                }

                if (mediaStream) {
                    mediaStream.getTracks().forEach(function(track) {
                        try {
                            track.stop();
                        } catch (error) {
                            console.warn('Track stop warning', error);
                        }
                    });
                }

                mediaRecorder = null;
                mediaStream = null;
                isRecording = false;
                voiceButton?.classList.remove('listening');
                voiceCancelButton?.classList.remove('show');
            }

            function setHint(text) {
                if (composerHint) {
                    composerHint.textContent = text || '';
                }
            }

            function escapeHtml(value) {
                return String(value ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function scrollToBottom() {
                chatBody.scrollTop = chatBody.scrollHeight;
            }

            function removeEmptyState() {
                const empty = document.getElementById('emptyChatText');
                if (empty) {
                    empty.remove();
                }
            }

            function renderMessageBody(message) {
                const type = message.message_type || 'text';
                const text = escapeHtml(message.message || '');
                const attachmentUrl = message.attachment_url ? escapeHtml(message.attachment_url) : '';
                const attachmentMime = escapeHtml(message.attachment_mime || '');

                if (type === 'image' && attachmentUrl) {
                    const caption = (text && text !== '[Image]') ? `<div>${text}</div>` : '';
                    return `<img src="${attachmentUrl}" class="msg-image js-lightbox-trigger" alt="Shared image">${caption}`;
                }

                if (type === 'voice' && attachmentUrl) {
                    const caption = (text && text !== '[Voice message]') ? `<div>${text}</div>` : '';
                    return `<audio controls class="msg-audio-row"><source src="${attachmentUrl}" type="${attachmentMime || 'audio/webm'}"></audio>${caption}`;
                }

                if (type === 'icon') {
                    return `<div class="msg-icon-text">${text}</div>`;
                }

                return `<div>${text}</div>`;
            }

            function appendMessageRow(message) {
                const messageId = Number(message?.id || 0);
                if (messageId > 0 && renderedMessageIds.has(messageId)) {
                    return;
                }

                const isMine = Boolean(message.is_mine ?? (Number(message.sender_id) === currentUserId));
                const row = document.createElement('div');
                row.className = `msg-row ${isMine ? 'mine' : 'other'}`;

                const type = message.message_type || 'text';

                if (type === 'order') {
                    let order = {};
                    try {
                        order = JSON.parse(message.message || '{}');
                    } catch (e) {
                        order = {};
                    }
                    const status = order.status || 'pending';
                    const statusLabel = order.status_label || (status.charAt(0).toUpperCase() + status.slice(1));
                    const invoice = escapeHtml(order.invoice_number || '—');

                    row.innerHTML = `
                    <img src="${escapeHtml(isMine ? myAvatar : peerAvatar)}" class="msg-avatar" alt="Avatar">
                    <button type="button" class="order-msg-card status-${escapeHtml(status)}" data-order='${escapeHtml(JSON.stringify(order))}'>
                        <i class="bi bi-box-seam order-msg-icon"></i>
                        <div class="order-msg-text">
                            <div class="order-msg-title">Order ${invoice}</div>
                            <div class="order-msg-sub">${escapeHtml(statusLabel)} · Tap to view</div>
                        </div>
                        <i class="bi bi-chevron-right order-msg-chevron"></i>
                    </button>
                `;
                } else if (type === 'voice') {
                    row.innerHTML = `
                    <img src="${escapeHtml(isMine ? myAvatar : peerAvatar)}" class="msg-avatar" alt="Avatar">
                    <div class="msg-bubble msg-voice">
                        ${renderMessageBody(message)}
                        <small class="msg-time">${escapeHtml(message.sent_at || '')}<i class="bi bi-check2-all msg-check"></i></small>
                    </div>
                `;
                } else {
                    row.innerHTML = `
                    <img src="${escapeHtml(isMine ? myAvatar : peerAvatar)}" class="msg-avatar" alt="Avatar">
                    <div class="msg-bubble">
                        ${renderMessageBody(message)}
                        <small class="msg-time">${escapeHtml(message.sent_at || '')}<i class="bi bi-check2-all msg-check"></i></small>
                    </div>
                `;
                }

                chatBody.appendChild(row);
                if (messageId > 0) {
                    renderedMessageIds.add(messageId);
                }
                removeEmptyState();
                attachLightbox(row);
                scrollToBottom();
            }

            async function pollMessages() {
                try {
                    const url = `${messagesUrl}?admin_id=${activeContactId}&after_id=${lastId}`;
                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    if (!response.ok) {
                        return;
                    }

                    const payload = await response.json();
                    const incoming = Array.isArray(payload.messages) ? payload.messages : [];
                    if (incoming.length > 0) {
                        incoming.forEach(appendMessageRow);
                        lastId = Number(payload.last_id || lastId);
                    }
                } catch (error) {
                    console.error('User chat poll failed', error);
                }
            }

            async function sendPayload({
                message = '',
                icon = '',
                imageFile = null,
                voiceFile = null
            } = {}) {
                if (isSending) {
                    return;
                }

                const text = String(message || '').trim();
                const iconText = String(icon || '').trim();

                if (!text && !iconText && !imageFile && !voiceFile) {
                    setHint('Please type a message or select media.');
                    return;
                }

                isSending = true;
                input.disabled = true;

                try {
                    const formData = new FormData();
                    formData.append('receiver_id', String(activeContactId));
                    formData.append('message', text);

                    if (iconText) {
                        formData.append('icon', iconText);
                    }

                    if (imageFile) {
                        formData.append('image', imageFile);
                    }

                    if (voiceFile) {
                        formData.append('voice', voiceFile);
                    }

                    const response = await fetch(sendUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    if (!response.ok) {
                        const errorPayload = await response.json().catch(() => ({}));
                        const firstValidationError = Object.values(errorPayload?.errors || {})[0]?.[0];
                        const message = firstValidationError || errorPayload?.message || 'Send failed';
                        throw new Error(message);
                    }

                    const payload = await response.json();
                    if (payload?.message) {
                        appendMessageRow(payload.message);
                        lastId = Math.max(lastId, Number(payload.message.id || 0));
                    }

                    input.value = '';
                    selectedImageFile = null;
                    selectedVoiceFile = null;
                    setHint('');
                } catch (error) {
                    console.error('User chat send failed', error);
                    setHint(error?.message || 'Failed to send. Please try again.');
                } finally {
                    isSending = false;
                    input.disabled = false;
                    input.focus();
                }
            }

            form.addEventListener('submit', function(event) {
                event.preventDefault();
                sendPayload({
                    message: input.value,
                    imageFile: selectedImageFile,
                    voiceFile: selectedVoiceFile,
                });
            });

            if (attachButton && imageInput) {
                attachButton.addEventListener('click', function() {
                    imageInput.click();
                });

                imageInput.addEventListener('change', function() {
                    const file = imageInput.files && imageInput.files[0] ? imageInput.files[0] : null;
                    if (!file) {
                        return;
                    }

                    selectedImageFile = file;
                    selectedVoiceFile = null;
                    setHint(`Image ready: ${file.name}`);
                    sendPayload({
                        message: input.value,
                        imageFile: selectedImageFile,
                    });
                    imageInput.value = '';
                });
            }

            if (emojiButton && emojiPanel) {
                emojiButton.addEventListener('click', function() {
                    emojiPanel.classList.toggle('show');
                });

                emojiPanel.addEventListener('click', function(event) {
                    const target = event.target.closest('[data-icon]');
                    if (!target) {
                        return;
                    }

                    const icon = target.getAttribute('data-icon') || '';
                    emojiPanel.classList.remove('show');
                    sendPayload({
                        icon
                    });
                });

                document.addEventListener('click', function(event) {
                    if (!emojiPanel.contains(event.target) && event.target !== emojiButton && !emojiButton
                        .contains(event.target)) {
                        emojiPanel.classList.remove('show');
                    }
                });
            }

            async function startVoiceRecording() {
                if (!navigator.mediaDevices || !window.MediaRecorder) {
                    setHint('Voice recording is not supported in this browser.');
                    return;
                }

                try {
                    cleanupRecorderResources();
                    mediaStream = await navigator.mediaDevices.getUserMedia({
                        audio: true
                    });
                    recorderMimeType = '';
                    const preferredTypes = [
                        'audio/webm;codecs=opus',
                        'audio/webm',
                        'audio/ogg;codecs=opus',
                        'audio/mp4'
                    ];

                    let recorderOptions = undefined;
                    for (const mimeType of preferredTypes) {
                        if (window.MediaRecorder.isTypeSupported && window.MediaRecorder.isTypeSupported(
                            mimeType)) {
                            recorderOptions = {
                                mimeType
                            };
                            recorderMimeType = mimeType;
                            break;
                        }
                    }

                    mediaRecorder = recorderOptions ? new MediaRecorder(mediaStream, recorderOptions) :
                        new MediaRecorder(mediaStream);
                    recordedChunks = [];
                    shouldSendRecordedVoice = true;
                    recordStartedAt = Date.now();

                    mediaRecorder.ondataavailable = function(event) {
                        if (event.data && event.data.size > 0) {
                            recordedChunks.push(event.data);
                        }
                    };

                    mediaRecorder.onstop = async function() {
                        if (recordTimer) {
                            clearInterval(recordTimer);
                            recordTimer = null;
                        }

                        const durationSec = Math.max(0, Math.round((Date.now() - recordStartedAt) / 1000));

                        const mimeType = mediaRecorder.mimeType || recorderMimeType || 'audio/webm';
                        const blob = new Blob(recordedChunks, {
                            type: mimeType
                        });

                        if (mediaStream) {
                            mediaStream.getTracks().forEach(function(track) {
                                track.stop();
                            });
                        }

                        if (!shouldSendRecordedVoice || blob.size === 0) {
                            setHint('Voice message canceled.');
                            selectedVoiceFile = null;
                            cleanupRecorderResources();
                            return;
                        }

                        const extension = mimeType.includes('ogg') ? 'ogg' : (mimeType.includes('mp4') ?
                            'm4a' : 'webm');
                        selectedVoiceFile = new File([blob], `voice-${Date.now()}.${extension}`, {
                            type: mimeType
                        });
                        selectedImageFile = null;

                        if (durationSec < 1) {
                            setHint('Voice too short. Hold a bit longer.');
                            cleanupRecorderResources();
                            return;
                        }

                        setHint('Voice message recorded. Sending...');
                        await sendPayload({
                            message: input.value,
                            voiceFile: selectedVoiceFile
                        });
                        cleanupRecorderResources();
                    };

                    mediaRecorder.start();
                    isRecording = true;
                    voiceButton.classList.add('listening');
                    voiceCancelButton?.classList.add('show');
                    setHint('Recording... 0:00 (click mic again to send, Cancel or Esc to cancel)');
                    recordTimer = setInterval(function() {
                        const sec = Math.max(0, Math.floor((Date.now() - recordStartedAt) / 1000));
                        const mm = Math.floor(sec / 60);
                        const ss = String(sec % 60).padStart(2, '0');
                        setHint(`Recording... ${mm}:${ss} (click mic again to send, Cancel or Esc to cancel)`);
                    }, 250);
                } catch (error) {
                    console.error('Voice recording start failed', error);
                    const name = String(error?.name || '');
                    if (name === 'NotAllowedError' || name === 'PermissionDeniedError') {
                        setHint('Microphone blocked. Please allow microphone and try again.');
                    } else if (name === 'NotFoundError') {
                        setHint('No microphone device found.');
                    } else if (name === 'NotReadableError') {
                        setHint('Microphone is busy in another app. Close that app and try again.');
                    } else if (name === 'NotSupportedError') {
                        setHint('This browser does not support voice recording format.');
                    } else {
                        setHint(`Voice record error: ${name || 'unknown error'}`);
                    }
                    cleanupRecorderResources();
                }
            }

            function stopVoiceRecording(sendAfterStop = true) {
                if (!mediaRecorder || mediaRecorder.state === 'inactive') {
                    return;
                }

                shouldSendRecordedVoice = !!sendAfterStop;
                mediaRecorder.stop();
                isRecording = false;
                voiceButton.classList.remove('listening');
                voiceCancelButton?.classList.remove('show');
            }

            if (voiceButton) {
                voiceButton.addEventListener('click', function(event) {
                    event.preventDefault();
                    if (isRecording) {
                        stopVoiceRecording(true);
                        return;
                    }
                    startVoiceRecording();
                });

                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape' && isRecording) {
                        stopVoiceRecording(false);
                    }
                });
            }

            voiceCancelButton?.addEventListener('click', function(event) {
                event.preventDefault();
                if (isRecording) {
                    stopVoiceRecording(false);
                }
            });

            const sendBtn = form.querySelector('.send-btn');
            if (sendBtn) {
                sendBtn.addEventListener('click', function() {
                    if (isRecording) {
                        stopVoiceRecording(true);
                    }
                });
            }

            window.addEventListener('beforeunload', cleanupRecorderResources);
            document.addEventListener('visibilitychange', function() {
                if (document.hidden && isRecording) {
                    stopVoiceRecording(false);
                }
            });

            // ===== Order detail overlay =====
            const orderDetailOverlay = document.getElementById('orderDetailOverlay');
            const orderDetailBack = document.getElementById('orderDetailBack');
            const orderDetailBanner = document.getElementById('orderDetailBanner');
            const orderDetailBannerTitle = document.getElementById('orderDetailBannerTitle');
            const orderDetailBannerSub = document.getElementById('orderDetailBannerSub');
            const orderDetailList = document.getElementById('orderDetailList');

            const ORDER_STATUS_COPY = {
                pending: {
                    title: 'Order is pending',
                    sub: 'We will notify you by inbox'
                },
                completed: {
                    title: 'Order is completed',
                    sub: 'Thank you for your purchase'
                },
                cancelled: {
                    title: 'Order was cancelled',
                    sub: 'Contact support if this is unexpected'
                },
            };

            function openOrderDetail(order) {
                if (!orderDetailOverlay) {
                    return;
                }

                const status = order.status || 'pending';
                const copy = ORDER_STATUS_COPY[status] || ORDER_STATUS_COPY.pending;

                orderDetailBanner.className = `order-detail-banner status-${status}`;
                orderDetailBannerTitle.textContent = order.status_label ? order.status_label : copy.title;
                orderDetailBannerSub.textContent = order.status_note || copy.sub;

                const rows = [
                    ['Invoice number', order.invoice_number],
                    ['Order date', order.order_date],
                    ['Item', order.item_name],
                    ['Quantity', order.quantity],
                    ['Total amount', order.total],
                ].filter(([, value]) => value !== undefined && value !== null && value !== '');

                orderDetailList.innerHTML = rows.map(([label, value]) => `
                    <div class="order-detail-row">
                        <span class="label">${escapeHtml(label)}</span>
                        <span class="value">${escapeHtml(String(value))}</span>
                    </div>
                `).join('');

                orderDetailOverlay.classList.add('open');
            }

            function closeOrderDetail() {
                orderDetailOverlay?.classList.remove('open');
            }

            if (chatBody) {
                chatBody.addEventListener('click', function(event) {
                    const card = event.target.closest('.order-msg-card');
                    if (!card) {
                        return;
                    }
                    let order = {};
                    try {
                        order = JSON.parse(card.dataset.order || '{}');
                    } catch (e) {
                        order = {};
                    }
                    openOrderDetail(order);
                });
            }

            orderDetailBack?.addEventListener('click', closeOrderDetail);

            scrollToBottom();
            setInterval(pollMessages, 2500);
        })();
    </script>
@endpush