@extends('ManagementSystemViews.AdminViews.Layouts.app')
<link rel="stylesheet" href="{{ asset('css/POSsystem/POSAdmin/notification/admin_chat_view.css') }}">
@section('title', 'Admin Chat')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
   
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
