@extends('ManagementSystemViews.AdminViews.Layouts.app')

@section('title', 'Admin Chat')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    .chat-page {
        display: flex;
        height: calc(100vh - 20px);
        background: #f3f4f6;
        border-radius: 14px;
        overflow: hidden;
    }

    .conversation-pane {
        width: 320px;
        background: #f6f7f9;
        border-right: 1px solid #e5e7eb;
        display: flex;
        flex-direction: column;
        padding: 16px 12px;
    }

    .search-wrap { position: relative; margin-bottom: 10px; }
    .search-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 14px; }

    .search-input {
        width: 100%;
        height: 42px;
        border: none;
        border-radius: 999px;
        padding: 0 14px 0 36px;
        background: #eceef2;
        color: #374151;
        outline: none;
    }

    .contact-list { flex: 1; overflow: auto; padding-right: 4px; }

    .contact-item {
        display: flex;
        gap: 10px;
        text-decoration: none;
        padding: 12px 10px;
        border-radius: 12px;
        color: #1f2937;
        transition: all .18s ease;
    }

    .contact-item:hover, .contact-item.active {
        background: #ffffff;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
    }

    .contact-avatar-wrap { position: relative; flex-shrink: 0; }

    .contact-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .07);
    }

    .contact-badge {
        position: absolute;
        right: -2px;
        top: -4px;
        min-width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #ef4444;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 4px;
    }

    .contact-text { min-width: 0; flex: 1; }
    .contact-name-row { display: flex; justify-content: space-between; gap: 8px; margin-bottom: 2px; }
    .contact-name { font-weight: 700; color: #2f3747; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .contact-time { color: #9ca3af; font-size: 12px; flex-shrink: 0; }
    .contact-last { color: #9ca3af; font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .message-pane { flex: 1; display: flex; flex-direction: column; background: #f9fafb; }
    .message-header { height: 74px; display: flex; align-items: center; padding: 0 24px; background: #f3f4f6; border-bottom: 1px solid #e5e7eb; }
    .peer-name { font-size: 32px; font-weight: 700; color: #1f2937; line-height: 1; }

    .message-stream {
        flex: 1;
        overflow: auto;
        padding: 22px 28px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        background: #f9fafb;
    }

    .msg-row { display: flex; gap: 10px; align-items: flex-end; max-width: 82%; }
    .msg-row.mine { align-self: flex-end; flex-direction: row-reverse; }
    .msg-row.other { align-self: flex-start; }

    .msg-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        object-fit: cover;
        border: 1px solid #e5e7eb;
        flex-shrink: 0;
    }

    .msg-bubble {
        border-radius: 16px;
        padding: 12px 16px 8px;
        line-height: 1.35;
        font-size: 16px;
        min-width: 80px;
    }

    .msg-row.other .msg-bubble { background: #ffffff; color: #2e3440; border: 1px solid #edf0f4; }
    .msg-row.mine .msg-bubble { color: #fff; background: linear-gradient(135deg, #5c9dff 0%, #4f86f7 100%); box-shadow: 0 8px 20px rgba(79, 134, 247, .26); }

    .msg-image {
        width: 240px;
        max-width: 100%;
        border-radius: 12px;
        display: block;
        margin-bottom: 6px;
    }

    .msg-audio {
        width: 260px;
        max-width: 100%;
        display: block;
        margin-bottom: 6px;
    }

    .msg-icon-text { font-size: 30px; line-height: 1; }
    .msg-time { display: block; margin-top: 4px; font-size: 11px; opacity: .82; }

    .composer-wrap {
        background: #f3f4f6;
        border-top: 1px solid #e5e7eb;
        padding: 12px 20px 10px;
        position: relative;
    }

    .composer {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #fff;
        border-radius: 999px;
        padding: 8px 10px 8px 16px;
    }

    .composer-input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 16px;
        background: transparent;
        color: #2f3747;
    }

    .icon-btn {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        color: #8b95a7;
        font-size: 22px;
        transition: all .2s;
    }

    .icon-btn:hover { background: #edf2f7; color: #4b5563; }
    .icon-btn.listening { background: #fee2e2; color: #dc2626; transform: scale(1.08); }

    .send-btn {
        border: none;
        border-radius: 999px;
        height: 36px;
        min-width: 80px;
        padding: 0 16px;
        background: linear-gradient(135deg, #5c9dff 0%, #4f86f7 100%);
        color: #fff;
        font-weight: 700;
    }

    .composer-hint { margin-top: 6px; font-size: 12px; color: #6b7280; min-height: 18px; }

    .emoji-panel {
        position: absolute;
        right: 100px;
        bottom: 58px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, .13);
        padding: 8px;
        display: none;
        gap: 4px;
        z-index: 12;
    }

    .emoji-panel.show { display: flex; }

    .emoji-item {
        width: 34px;
        height: 34px;
        border: none;
        border-radius: 8px;
        background: #fff;
        font-size: 20px;
        line-height: 1;
    }

    .emoji-item:hover { background: #f3f4f6; }

    .back-link { margin-top: 10px; font-size: 13px; color: #4b5563; text-decoration: none; }

    @media (max-width: 991px) {
        .chat-page { height: auto; min-height: calc(100vh - 20px); flex-direction: column; border-radius: 10px; }
        .conversation-pane { width: 100%; max-height: 42vh; border-right: 0; border-bottom: 1px solid #e5e7eb; }
        .peer-name { font-size: 24px; }
        .message-stream { padding: 16px; }
        .msg-row { max-width: 95%; }
        .emoji-panel { right: 76px; bottom: 64px; }
    }
</style>
@endpush

@section('content')
<div class="chat-page">
    <aside class="conversation-pane">
        <div class="search-wrap">
            <i class="bi bi-search"></i>
            <input id="contactSearch" type="text" class="search-input" placeholder="Search...">
        </div>

        <div class="contact-list" id="contactList">
            @forelse($contacts as $contact)
                <a class="contact-item {{ (int)$activeContactId === (int)$contact->id ? 'active' : '' }}"
                    data-name="{{ strtolower($contact->name) }}"
                    data-last="{{ strtolower($contact->last_message ?: '') }}"
                    href="{{ route('admin.chat.index', ['user_id' => $contact->id]) }}">
                    <div class="contact-avatar-wrap">
                        <img src="{{ $contact->chat_avatar ?? asset('images/pos/Rectangle 2.png') }}" class="contact-avatar" alt="{{ $contact->name }}">
                        @if((int)($contact->unread_count ?? 0) > 0)
                            <div class="contact-badge">{{ (int)$contact->unread_count }}</div>
                        @endif
                    </div>
                    <div class="contact-text">
                        <div class="contact-name-row">
                            <div class="contact-name">{{ $contact->name }}</div>
                            <div class="contact-time">{{ $contact->last_message_at ? \Carbon\Carbon::parse($contact->last_message_at)->format('gA') : '' }}</div>
                        </div>
                        <div class="contact-last">{{ $contact->last_message ?: 'No message yet' }}</div>
                    </div>
                </a>
            @empty
                <div class="text-muted">No chat yet.</div>
            @endforelse
        </div>

        <a href="{{ route('admin.notifications.index') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Back Notifications
        </a>
    </aside>

    <section class="message-pane">
        <header class="message-header">
            <div class="peer-name">{{ $activeContact->name ?? 'Admin Chat' }}</div>
        </header>

        <div class="message-stream" id="chatBody">
            @forelse($messages as $msg)
                @php
                    $isMine = (int)$msg->sender_id === (int)$currentUser->id;
                    $type = $msg->message_type ?? 'text';
                    $attachmentUrl = $msg->attachment_path ? ('/storage/' . ltrim($msg->attachment_path, '/')) : null;
                    $text = (string) ($msg->message ?? '');
                @endphp
                <div class="msg-row {{ $isMine ? 'mine' : 'other' }}">
                    <img src="{{ $isMine ? ($currentUser->chat_avatar ?? asset('images/pos/Rectangle 2.png')) : ($activeContact->chat_avatar ?? asset('images/pos/Rectangle 2.png')) }}" class="msg-avatar" alt="Avatar">
                    <div class="msg-bubble">
                        @if($type === 'image' && $attachmentUrl)
                            <img src="{{ $attachmentUrl }}" alt="Shared image" class="msg-image">
                            @if($text !== '' && $text !== '[Image]')
                                <div>{{ $text }}</div>
                            @endif
                        @elseif($type === 'voice' && $attachmentUrl)
                            <audio controls class="msg-audio">
                                <source src="{{ $attachmentUrl }}" type="{{ $msg->attachment_mime ?? 'audio/webm' }}">
                            </audio>
                            @if($text !== '' && $text !== '[Voice message]')
                                <div>{{ $text }}</div>
                            @endif
                        @elseif($type === 'icon')
                            <div class="msg-icon-text">{{ $text }}</div>
                        @else
                            <div>{{ $text }}</div>
                        @endif
                        <small class="msg-time">{{ optional($msg->created_at)->format('g:i A') }}</small>
                    </div>
                </div>
            @empty
                <div class="text-muted" id="emptyChatText">Select a user conversation to start chat.</div>
            @endforelse
        </div>

        @if($activeContactId)
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

                <form class="composer" id="adminChatForm" action="{{ route('admin.chat.send') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $activeContactId }}">
                    <input type="file" id="imageInput" accept="image/*" class="d-none">
                    <input type="text" id="chatMessageInput" name="message" class="composer-input" placeholder="Type a message...">

                    <button type="button" id="attachButton" class="icon-btn" title="Send image">
                        <i class="bi bi-image"></i>
                    </button>
                    <button type="button" id="emojiButton" class="icon-btn" title="Send icon">
                        <i class="bi bi-emoji-smile"></i>
                    </button>
                    <button type="button" id="voiceButton" class="icon-btn" title="Click to record voice">
                        <i class="bi bi-mic"></i>
                    </button>
                    <button type="submit" class="send-btn">Send</button>
                </form>
                <div class="composer-hint" id="composerHint">Click mic to start recording, click again to send.</div>
            </div>
        @endif
    </section>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const chatBody = document.getElementById('chatBody');
        const form = document.getElementById('adminChatForm');
        const input = document.getElementById('chatMessageInput');
        const contactSearch = document.getElementById('contactSearch');
        const contactList = document.getElementById('contactList');
        const attachButton = document.getElementById('attachButton');
        const imageInput = document.getElementById('imageInput');
        const emojiButton = document.getElementById('emojiButton');
        const emojiPanel = document.getElementById('emojiPanel');
        const voiceButton = document.getElementById('voiceButton');
        const composerHint = document.getElementById('composerHint');

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const activeContactId = Number(@json((int) $activeContactId));
        const currentUserId = Number(@json((int) $currentUser->id));
        const myAvatar = @json($currentUser->chat_avatar ?? asset('images/pos/Rectangle 2.png'));
        const peerAvatar = @json($activeContact->chat_avatar ?? asset('images/pos/Rectangle 2.png'));
        const sendUrl = @json(route('admin.chat.send'));
        const messagesUrl = @json(route('admin.chat.messages'));

        if (contactSearch && contactList) {
            contactSearch.addEventListener('input', function () {
                const keyword = this.value.trim().toLowerCase();
                const items = contactList.querySelectorAll('.contact-item');
                items.forEach(function (item) {
                    const name = item.dataset.name || '';
                    const last = item.dataset.last || '';
                    item.style.display = name.includes(keyword) || last.includes(keyword) ? '' : 'none';
                });
            });
        }

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
                mediaStream.getTracks().forEach(function (track) {
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
                return `<img src="${attachmentUrl}" class="msg-image" alt="Shared image">${caption}`;
            }

            if (type === 'voice' && attachmentUrl) {
                const caption = (text && text !== '[Voice message]') ? `<div>${text}</div>` : '';
                return `<audio controls class="msg-audio"><source src="${attachmentUrl}" type="${attachmentMime || 'audio/webm'}"></audio>${caption}`;
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
            row.innerHTML = `
                <img src="${escapeHtml(isMine ? myAvatar : peerAvatar)}" class="msg-avatar" alt="Avatar">
                <div class="msg-bubble">
                    ${renderMessageBody(message)}
                    <small class="msg-time">${escapeHtml(message.sent_at || '')}</small>
                </div>
            `;

            chatBody.appendChild(row);
            if (messageId > 0) {
                renderedMessageIds.add(messageId);
            }
            removeEmptyState();
            scrollToBottom();
        }

        async function pollMessages() {
            try {
                const url = `${messagesUrl}?user_id=${activeContactId}&after_id=${lastId}`;
                const response = await fetch(url, { headers: { 'Accept': 'application/json' } });
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
                console.error('Admin chat poll failed', error);
            }
        }

        async function sendPayload({ message = '', icon = '', imageFile = null, voiceFile = null } = {}) {
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
                console.error('Admin chat send failed', error);
                setHint(error?.message || 'Failed to send. Please try again.');
            } finally {
                isSending = false;
                input.disabled = false;
                input.focus();
            }
        }

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            sendPayload({
                message: input.value,
                imageFile: selectedImageFile,
                voiceFile: selectedVoiceFile,
            });
        });

        if (attachButton && imageInput) {
            attachButton.addEventListener('click', function () {
                imageInput.click();
            });

            imageInput.addEventListener('change', function () {
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
            emojiButton.addEventListener('click', function () {
                emojiPanel.classList.toggle('show');
            });

            emojiPanel.addEventListener('click', function (event) {
                const target = event.target.closest('[data-icon]');
                if (!target) {
                    return;
                }

                const icon = target.getAttribute('data-icon') || '';
                emojiPanel.classList.remove('show');
                sendPayload({ icon });
            });

            document.addEventListener('click', function (event) {
                if (!emojiPanel.contains(event.target) && event.target !== emojiButton && !emojiButton.contains(event.target)) {
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
                mediaStream = await navigator.mediaDevices.getUserMedia({ audio: true });
                recorderMimeType = '';
                const preferredTypes = [
                    'audio/webm;codecs=opus',
                    'audio/webm',
                    'audio/ogg;codecs=opus',
                    'audio/mp4'
                ];

                let recorderOptions = undefined;
                for (const mimeType of preferredTypes) {
                    if (window.MediaRecorder.isTypeSupported && window.MediaRecorder.isTypeSupported(mimeType)) {
                        recorderOptions = { mimeType };
                        recorderMimeType = mimeType;
                        break;
                    }
                }

                mediaRecorder = recorderOptions ? new MediaRecorder(mediaStream, recorderOptions) : new MediaRecorder(mediaStream);
                recordedChunks = [];
                shouldSendRecordedVoice = true;
                recordStartedAt = Date.now();

                mediaRecorder.ondataavailable = function (event) {
                    if (event.data && event.data.size > 0) {
                        recordedChunks.push(event.data);
                    }
                };

                mediaRecorder.onstop = async function () {
                    if (recordTimer) {
                        clearInterval(recordTimer);
                        recordTimer = null;
                    }

                    const durationSec = Math.max(0, Math.round((Date.now() - recordStartedAt) / 1000));

                    const mimeType = mediaRecorder.mimeType || recorderMimeType || 'audio/webm';
                    const blob = new Blob(recordedChunks, { type: mimeType });

                    if (mediaStream) {
                        mediaStream.getTracks().forEach(function (track) { track.stop(); });
                    }

                    if (!shouldSendRecordedVoice || blob.size === 0) {
                        setHint('Voice message canceled.');
                        selectedVoiceFile = null;
                        cleanupRecorderResources();
                        return;
                    }

                    const extension = mimeType.includes('ogg') ? 'ogg' : (mimeType.includes('mp4') ? 'm4a' : 'webm');
                    selectedVoiceFile = new File([blob], `voice-${Date.now()}.${extension}`, { type: mimeType });
                    selectedImageFile = null;

                    if (durationSec < 1) {
                        setHint('Voice too short. Hold a bit longer.');
                        cleanupRecorderResources();
                        return;
                    }

                    setHint('Voice message recorded. Sending...');
                    await sendPayload({ message: input.value, voiceFile: selectedVoiceFile });
                    cleanupRecorderResources();
                };

                mediaRecorder.start();
                isRecording = true;
                voiceButton.classList.add('listening');
                setHint('Recording... 0:00');
                recordTimer = setInterval(function () {
                    const sec = Math.max(0, Math.floor((Date.now() - recordStartedAt) / 1000));
                    const mm = Math.floor(sec / 60);
                    const ss = String(sec % 60).padStart(2, '0');
                    setHint(`Recording... ${mm}:${ss} (click mic again to send)`);
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
        }

        if (voiceButton) {
            voiceButton.addEventListener('click', function (event) {
                event.preventDefault();
                if (isRecording) {
                    stopVoiceRecording(true);
                    return;
                }
                startVoiceRecording();
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && isRecording) {
                    stopVoiceRecording(false);
                }
            });
        }

        const sendBtn = form.querySelector('.send-btn');
        if (sendBtn) {
            sendBtn.addEventListener('click', function () {
                if (isRecording) {
                    stopVoiceRecording(true);
                }
            });
        }

        window.addEventListener('beforeunload', cleanupRecorderResources);
        document.addEventListener('visibilitychange', function () {
            if (document.hidden && isRecording) {
                stopVoiceRecording(false);
            }
        });

        scrollToBottom();
        setInterval(pollMessages, 2500);
    })();
</script>
@endpush
