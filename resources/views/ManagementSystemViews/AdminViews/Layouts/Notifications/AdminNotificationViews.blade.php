@extends('Layout.POSAdmin.app')
<link rel="stylesheet" href="{{ asset('/css/views/POSViews/POSAdminViews/AdminNotification/admin_notification.css') }}">
@section('title', 'Admin Notifications')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush
@section('content')
<div class="app-shell" id="appShell">
    <div class="page-wrap">
        <div class="notification-wrapper">

            <div class="notification-page-header">
                <h2 class="page-title">Notification</h2>
            </div>

            <div class="alert-container" id="alertContainer"></div>

            <form method="GET" action="{{ route('admin.notifications.index') }}" class="filter-form" id="notificationFilterForm">

                <div class="top-filter-row">
                    <div class="search-and-filter-row">
                        <div class="search-box-noti">
                            <i class="bi bi-search"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." />
                        </div>

                        <button type="button" class="mobile-date-toggle-btn" id="mobileDateToggleNotif" aria-label="Filter by date">
                            <img src="{{ asset('images/AdminPOS/calendar (3).png') }}" alt="" class="mobile-toggle-icon">
                        </button>

                        <button type="button" class="mobile-tabs-toggle-btn" id="mobileTabsToggleNotif" aria-label="Show/hide category tabs">
                            <img src="{{ asset('images/AdminPOS/filter (1).png') }}" alt="" class="mobile-toggle-icon">
                        </button>
                    </div>
                    <div class="top-right-tools">
                        <a href="{{ route('admin.chat.index') }}" class="btn-send-message btn-inbox" style="text-decoration:none;">
                            <img src="{{ asset('images/AdminPOS/chatting.png') }}" alt="" class="btn-send-message-icon">
                            <span>Chat</span>
                        </a>
                        <button type="button" class="btn-send-message btn-send" data-bs-toggle="modal" data-bs-target="#sendModal">
                            <img src="{{ asset('images/AdminPOS/send message.png') }}" alt="" class="btn-send-message-icon">
                            <span>Send Message</span>
                        </button>
                    </div>
                </div>

                <!-- Row 2: date filter. Always visible on desktop; on mobile it's kept
                     off-screen and opened directly by the calendar icon (see
                     bindMobileDateToggle) instead of a toggled dropdown row. -->
                <div class="date-row" id="notifDateRow">
                    <div class="date-filter-box" onclick="document.getElementById('date').showPicker && document.getElementById('date').showPicker()">
                        <input type="date" name="date" id="date" value="{{ request('date') }}" onchange="this.form.submit()">
                        <i class="bi bi-calendar3 date-icon"></i>
                    </div>
                </div>

                <!-- Row 3: tabs (icons are images now) -->
                <div class="tab-row" id="notifTabRow">
                    <div class="tabs">
                        @php
                            $activeTab = $tab ?? 'order_notification';
                        @endphp
                        <a href="{{ route('admin.notifications.index', array_merge(request()->except('page', 'tab'), ['tab' => 'order_notification'])) }}"
                           class="tab-link {{ $activeTab === 'order_notification' ? 'active' : '' }}">
                            <span class="tab-icon-wrap">
                                <img src="{{ asset($activeTab === 'order_notification' ? '/images/management/cart.png' : '/images/management/cart icon.png') }}" alt="Order Notification">
                                <span class="tab-badge" data-tab-badge="order_notification">{{ $orderCount ?? 0 }}</span>
                            </span>
                            <span class="tab-label">Order Notification</span>
                        </a>
                        <a href="{{ route('admin.notifications.index', array_merge(request()->except('page', 'tab'), ['tab' => 'user_contact'])) }}"
                           class="tab-link {{ $activeTab === 'user_contact' ? 'active' : '' }}">
                            <span class="tab-icon-wrap">
                                <img src="{{ asset($activeTab === 'user_contact' ? '/images/management/user contact.png' : '/images/management/user contact inactive.png') }}" alt="User Contact">
                                <span class="tab-badge" data-tab-badge="user_contact">{{ $userContactCount ?? 0 }}</span>
                            </span>
                            <span class="tab-label">User Contact</span>
                        </a>
                        <a href="{{ route('admin.notifications.index', array_merge(request()->except('page', 'tab'), ['tab' => 'out_of_stock'])) }}"
                           class="tab-link {{ $activeTab === 'out_of_stock' ? 'active' : '' }}">
                            <span class="tab-icon-wrap">
                                <img src="{{ asset($activeTab === 'out_of_stock' ? '/images/management/out of stock alert.png' : '/images/management/out of stock.png') }}" alt="Out of Stock Item">
                                <span class="tab-badge" data-tab-badge="out_of_stock">{{ $outOfStockCount ?? 0 }}</span>
                            </span>
                            <span class="tab-label">Out of Stock Item</span>
                        </a>
                        <a href="{{ route('admin.notifications.index', array_merge(request()->except('page', 'tab'), ['tab' => 'global_message'])) }}"
                           class="tab-link {{ $activeTab === 'global_message' ? 'active' : '' }}">
                            <span class="tab-icon-wrap">
                                <img src="{{ asset($activeTab === 'global_message' ? '/images/management/global message (2).png' : '/images/management/global message.png') }}" alt="Global Message">
                                <span class="tab-badge" data-tab-badge="global_message">{{ $globalMessageCount ?? 0 }}</span>
                            </span>
                            <span class="tab-label">Global Message</span>
                        </a>
                    </div>
                </div>
            </form>

            <div class="utility-bar">
                <div class="utility-selection">
                    <label class="select-all-box" for="selectAllNotifications">
                        <input type="checkbox" id="selectAllNotifications">
                        <span>Select All</span>
                    </label>
                    <div class="selected-box">
                        Selected <span id="selectedCount">0</span>
                    </div>
                </div>
                <form action="{{ route('admin.notifications.read.all') }}" method="POST" id="markAllReadForm">
                    @csrf
                </form>

                <div class="utility-actions">
                    <button type="submit" form="markAllReadForm" class="utility-btn utility-btn-toggle">Mark all read</button>
                    <button type="submit" form="deleteForm" class="utility-btn delete-btn utility-btn-toggle">Delete</button>
                </div>
            </div>

            <form action="{{ route('admin.notifications.delete.selected') }}" method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="notification-list" id="notificationList">
                    @forelse($notifications as $notification)
                        @php
                            $user        = $notification->user;
                            $sender      = $notification->sender;
                            $isUserContact = ($notification->type === 'user_contact');
                            $contactUser = $isUserContact ? ($sender ?: $user) : ($user ?: $sender);
                            $avatarSrc   = asset('images/pos/Rectangle 2.png');
                            if ($contactUser && !empty($contactUser->profile_image_display)) {
                                $avatarSrc = $contactUser->profile_image_display;
                            } elseif (!empty($notification->sender_profile_image)) {
                                $avatarSrc = $notification->sender_profile_image;
                            }
                            $displayName   = optional($contactUser)->name ?? ($notification->sender_name ?: optional($sender)->name) ?? 'System';

                            $messagePreview = trim(preg_replace(
                                '/\s+/',
                                ' ',
                                html_entity_decode(strip_tags($notification->message ?? ''), ENT_QUOTES, 'UTF-8')
                            ));
                            // Escape first, then wrap order numbers in a highlight span — safe
                            // because the regex only ever inserts our own trusted markup
                            // around text that's already been through e().
                            $messagePreviewHtml = preg_replace(
                                '/\b(ORD-[A-Za-z0-9]+)\b/',
                                '<span class="order-id-highlight">$1</span>',
                                e($messagePreview !== '' ? $messagePreview : 'Enter your message description here...')
                            );
                            $rowUrl = ($isUserContact && optional($contactUser)->id)
                                ? route('admin.chat.index', ['user_id' => $contactUser->id])
                                : route('admin.notifications.show', $notification->id);
                        @endphp
                        <a class="notification-item-link"
                             data-id="{{ $notification->id }}"
                             data-href="{{ $rowUrl }}"
                             href="{{ $rowUrl }}">
                            <div class="notification-item {{ !$notification->is_read ? 'selected-row' : '' }}">
                                <div class="notification-main-left">
                                    <input type="checkbox"
                                           class="notification-checkbox"
                                           name="notification_ids[]"
                                           value="{{ $notification->id }}"
                                           onclick="event.stopPropagation();">
                                    @if($isUserContact)
                                        <button type="button" class="star-btn" title="Star" onclick="event.preventDefault();event.stopPropagation();">
                                            <i class="bi bi-star"></i>
                                        </button>
                                    @endif
                                    <div class="avatar-box">
                                        <img src="{{ $avatarSrc }}" alt="avatar"
                                             onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}'">
                                        <span class="online-dot"></span>
                                    </div>
                                    <div class="notification-content">
                                        <div class="notification-name-row">
                                            <div class="notification-name">{{ $displayName }}</div>
                                            @if($isUserContact && optional($contactUser)->id)
                                                <button type="button" class="start-chat-btn"
                                                    onclick="event.preventDefault();event.stopPropagation();window.location.href='{{ route('admin.chat.index', ['user_id' => $contactUser->id]) }}';">
                                                    <i class="bi bi-chat-dots-fill"></i>
                                                    <span>Start Chat</span>
                                                </button>
                                            @endif
                                        </div>
                                        <div class="notification-message">
                                            {!! $messagePreviewHtml !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="notification-right">
                                    @if((int)($notification->unread_count ?? 0) > 0 && !$notification->is_read)
                                        <div class="notification-counter"></div>
                                    @endif
                                    <div class="notification-time">{{ optional($notification->updated_at)->format('H:i') }}</div>
                                    <button type="button" class="row-hover-delete-btn" title="Delete"
                                            data-delete-url="{{ route('admin.notifications.destroy', $notification->id) }}"
                                            onclick="event.preventDefault();event.stopPropagation();deleteNotificationRow(this);">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                    {{-- Mobile-only: a single "more actions" menu instead of an
                                         always-visible time+delete pair (there's no hover on touch). --}}
                                    <div class="row-menu-wrap">
                                        <button type="button" class="row-menu-btn" title="More actions"
                                                onclick="event.preventDefault();event.stopPropagation();toggleRowMenu(this);">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <div class="row-menu-dropdown">
                                            <button type="button" class="row-menu-item"
                                                    data-mark-read-url="{{ route('admin.notifications.read', $notification->id) }}"
                                                    onclick="event.preventDefault();event.stopPropagation();markRowAsRead(this);">
                                                <i class="bi bi-envelope-open"></i> Mark as read
                                            </button>
                                            <button type="button" class="row-menu-item row-menu-item-danger"
                                                    data-delete-url="{{ route('admin.notifications.destroy', $notification->id) }}"
                                                    onclick="event.preventDefault();event.stopPropagation();deleteNotificationRow(this);">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="empty-box">No notifications found.</div>
                    @endforelse
                </div>
            </form>

            <div class="pagination-wrap">
                <form method="GET" action="{{ route('admin.notifications.index') }}" class="per-page-form">
                    @foreach(request()->except('page', 'per_page') as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $nestedValue)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $nestedValue }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <label for="per_page" class="per-page-label">Show</label>
                    <select name="per_page" id="per_page" class="per-page-select" onchange="this.form.submit()">
                        <option value="10" {{ (int)($perPage ?? 10) === 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ (int)($perPage ?? 10) === 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ (int)($perPage ?? 10) === 50 ? 'selected' : '' }}>50</option>
                    </select>
                    <span class="per-page-unit">notifications</span>
                </form>
                <div class="pagination-controls">
                    @php
                        $previousPageUrl = $notifications->onFirstPage() ? null : $notifications->previousPageUrl();
                        $nextPageUrl     = $notifications->hasMorePages() ? $notifications->nextPageUrl() : null;
                    @endphp
                    @if($previousPageUrl)
                        <a href="{{ $previousPageUrl }}" class="pager-btn">Previous</a>
                    @else
                        <span class="pager-btn disabled">Previous</span>
                    @endif
                    <div class="pager-current">Page {{ $notifications->currentPage() }} of {{ $notifications->lastPage() }}</div>
                    @if($nextPageUrl)
                        <a href="{{ $nextPageUrl }}" class="pager-btn">Next</a>
                    @else
                        <span class="pager-btn disabled">Next</span>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<div class="send-minimized-tab" id="sendMinimizedTab" onclick="restoreSendModal()">
    <div class="minimized-dot"></div>
    <span class="minimized-label">New Message</span>
    <button class="minimized-restore-btn" title="Restore">
        <i class="bi bi-pip"></i>
    </button>
</div>
<div class="modal fade" id="sendModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content send-modal-content">

            <form action="{{ route('admin.notifications.store') }}" method="POST" id="sendNotificationForm">
                @csrf

                <!-- ── HEADER ── -->
                <div class="send-modal-header">
                    <div class="send-to-row">
                        <span class="send-label" id="recipientModeBadge">
                            <i class="bi bi-people-fill" id="recipientModeBadgeIcon"></i>
                            <span id="recipientModeBadgeText">All users</span>
                        </span>
                        <div class="send-recipient-area">

                            <div class="recipient-top">
                                <select name="send_type" id="send_type"
                                        class="recipient-type-select"
                                        onchange="toggleRecipientMode()">
                                    <option value="all"      {{ old('send_type','all') === 'all'      ? 'selected' : '' }}>All Customers</option>
                                    <option value="multiple" {{ old('send_type')       === 'multiple' ? 'selected' : '' }}>Select Customers</option>
                                </select>

                                <div class="inline-customer-search-wrap" id="inlineSearchWrap">
                                    <i class="bi bi-search inline-search-icon"></i>
                                    <input type="text"
                                           id="customerSearchInput"
                                           class="inline-customer-input"
                                           placeholder="Search and add customer..."
                                           autocomplete="off">
                                    <div id="customerDropdown" class="inline-customer-dropdown"></div>
                                </div>
                            </div>

                            <div class="chip-box" id="selectedChipsBox"></div>
                            <div id="selectedUserIdsContainer"></div>

                        </div>
                    </div>

                    <div class="send-header-actions">
                        <button type="button" class="header-icon-btn" id="fullscreenModalBtn" title="Full screen">
                            <i class="bi bi-arrows-angle-expand"></i>
                        </button>
                        <button type="button" class="header-icon-btn close-btn" title="Close" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>

                <div class="send-subject-row">
                    <span class="subject-row-icon"><i class="bi bi-envelope-paper"></i></span>
                    <input type="text"
                           name="title"
                           class="send-subject-input"
                           id="subjectInput"
                           placeholder="Write title..."
                           value="{{ old('title') }}"
                           maxlength="200">
                    <span class="subject-counter" id="subjectCounter">0</span>
                </div>

                <input type="hidden" name="type" value="{{ old('type', 'admin_message') }}">

                <div class="send-modal-body">
                    <div class="send-editor-wrap">
                        <div class="editor-mode-badge">
                            <i class="bi bi-pencil-square"></i>
                            <span>Message</span>
                        </div>

                        <div id="message_editor"
                             class="send-message-editor"
                             contenteditable="true"
                             data-placeholder="Write your message...">{!! old('message') !!}</div>

                        <textarea name="message" id="message" class="send-message-textarea d-none">{{ old('message') }}</textarea>

                        <div class="editor-toolbar">
                            <button type="button" class="toolbar-type-pill">
                                Text <i class="bi bi-chevron-down" style="font-size:10px;"></i>
                            </button>
                            <span class="toolbar-divider"></span>

                            <button type="button" class="toolbar-btn" id="btnBold" title="Bold" onclick="execFmt('bold','btnBold')">
                                <i class="bi bi-type-bold"></i>
                            </button>
                            <button type="button" class="toolbar-btn" id="btnItalic" title="Italic" onclick="execFmt('italic','btnItalic')">
                                <i class="bi bi-type-italic"></i>
                            </button>
                            <button type="button" class="toolbar-btn" id="btnUnderline" title="Underline" onclick="execFmt('underline','btnUnderline')">
                                <i class="bi bi-type-underline"></i>
                            </button>
                            <button type="button" class="toolbar-btn" id="btnStrike" title="Strikethrough" onclick="execFmt('strikeThrough','btnStrike')">
                                <i class="bi bi-type-strikethrough"></i>
                            </button>

                            <span class="toolbar-divider"></span>

                            <button type="button" class="toolbar-btn" id="btnLink" title="Insert link" onclick="openLinkOverlay()">
                                <i class="bi bi-link-45deg"></i>
                            </button>
                        </div>

                        <div class="link-insert-overlay" id="linkInsertOverlay">
                            <div class="link-insert-box">
                                <p class="link-insert-title">Insert Link</p>
                                <div class="link-insert-field">
                                    <label>Display text</label>
                                    <input type="text" id="linkDisplayText" placeholder="Link label (optional)">
                                </div>
                                <div class="link-insert-field">
                                    <label>URL</label>
                                    <input type="url" id="linkUrlInput" placeholder="https://example.com">
                                </div>
                                <div class="link-insert-actions">
                                    <button type="button" class="link-cancel-btn" onclick="closeLinkOverlay()">Cancel</button>
                                    <button type="button" class="link-confirm-btn" onclick="confirmInsertLink()">Insert</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="send-modal-footer">
                    <div class="emoji-picker" id="sendEmojiPicker">
                        <button type="button" class="emoji-option" data-emoji="😀" aria-label="Grinning face">😀</button>
                        <button type="button" class="emoji-option" data-emoji="😂" aria-label="Face with tears of joy">😂</button>
                        <button type="button" class="emoji-option" data-emoji="😍" aria-label="Smiling face with heart eyes">😍</button>
                        <button type="button" class="emoji-option" data-emoji="👍" aria-label="Thumbs up">👍</button>
                        <button type="button" class="emoji-option" data-emoji="🙏" aria-label="Folded hands">🙏</button>
                        <button type="button" class="emoji-option" data-emoji="🔥" aria-label="Fire">🔥</button>
                        <button type="button" class="emoji-option" data-emoji="🎉" aria-label="Party popper">🎉</button>
                        <button type="button" class="emoji-option" data-emoji="🥺" aria-label="Pleading face">🥺</button>
                        <button type="button" class="emoji-option" data-emoji="❤️" aria-label="Red heart">❤️</button>
                        <button type="button" class="emoji-option" data-emoji="👏" aria-label="Clapping hands">👏</button>
                        <button type="button" class="emoji-option" data-emoji="😎" aria-label="Smiling face with sunglasses">😎</button>
                        <button type="button" class="emoji-option" data-emoji="🤔" aria-label="Thinking face">🤔</button>
                    </div>
                    <div class="footer-left-tools">
                        <button type="button" class="footer-icon-btn danger-btn" title="Clear" onclick="clearComposer()">
                            <i class="bi bi-trash"></i>
                        </button>
                        <button type="button" class="footer-icon-btn" title="More options">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                    </div>
                    <div class="footer-right-tools">
                        <button type="button" class="footer-icon-btn" title="Write message" onclick="focusMessageEditor()">
                        </button>
                        <button type="button" class="footer-icon-btn" id="emojiToggleBtn" title="Emoji" onclick="toggleEmojiPicker(event)">
                            <i class="bi bi-emoji-smile"></i>
                        </button>
                        <button type="button" class="footer-icon-btn" title="Insert link" onclick="openLinkOverlay()">
                            <i class="bi bi-link-45deg"></i>
                        </button>
                        <button type="submit" class="send-now-btn">
                            <span>send now</span>
                            <i class="bi bi-send"></i>
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<div class="modal fade confirm-action-modal" id="confirmActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content confirm-action-content">
            <div class="modal-body confirm-action-body">
                <h5 class="confirm-action-title">Are you sure?</h5>
                <p class="confirm-action-message" id="confirmActionMessage"></p>
            </div>
            <div class="modal-footer confirm-action-footer">
                <button type="button" class="confirm-action-delete-btn" id="confirmActionConfirmBtn">Delete</button>
                <button type="button" class="confirm-action-cancel-btn" data-bs-dismiss="modal">Cancel Request</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() { showAlert('{{ session('success') }}', 'success'); });
</script>
@endif
@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() { showAlert('{{ session('error') }}', 'danger'); });
</script>
@endif
@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() { showAlert('Please fix the errors below', 'danger'); });
</script>
@endif

<script>
document.body.classList.add('admin-notifications-page');

/* ── Alert system ── */
function getAlertContainer() { return document.getElementById('alertContainer'); }
function showAlert(message, type = 'success') {
    const alertContainer = getAlertContainer();
    if (!alertContainer) return;
    const el = document.createElement('div');
    el.className = `custom-alert alert-${type}`;
    el.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'}"></i><span>${message}</span>`;
    alertContainer.appendChild(el);
    setTimeout(() => { el.classList.add('fade-out'); setTimeout(() => el.remove(), 300); }, 4000);
}

/* ── Shared refs ── */
function getCheckboxes() { return Array.from(document.querySelectorAll('.notification-checkbox')); }
function getSelectedCountEl() { return document.getElementById('selectedCount'); }
function getSelectAllEl() { return document.getElementById('selectAllNotifications'); }
function getNotificationWrapper() { return document.querySelector('.notification-wrapper'); }
const defaultAvatar          = @json(asset('images/default-avatar.png'));
const customerSearchUrl      = @json(route('admin.notifications.ajax.search.customers'));
const latestNotificationUrl  = @json(route('admin.notifications.ajax.latest'));
const notificationDestroyUrlTemplate = @json(route('admin.notifications.destroy', ['id' => '__ID__']));
const notificationReadUrlTemplate = @json(route('admin.notifications.read', ['id' => '__ID__']));
let currentTab               = @json($tab ?? 'order_notification');

let selectedMultiUsers       = [];
let searchTimer              = null;
let savedRange               = null;
let latestNotificationId     = {{ (int)($notifications->max('id') ?? 0) }};

/* ── Bootstrap modal instance ── */
let bsModal = null;
let bsConfirmModal = null;
let pendingConfirmCallback = null;

document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('sendModal');
    bsModal = new bootstrap.Modal(modalEl);

    const confirmModalEl = document.getElementById('confirmActionModal');
    if (confirmModalEl) {
        bsConfirmModal = new bootstrap.Modal(confirmModalEl);
    }

    document.getElementById('confirmActionConfirmBtn')?.addEventListener('click', function () {
        bsConfirmModal?.hide();
        const callback = pendingConfirmCallback;
        pendingConfirmCallback = null;
        callback?.();
    });
});
function showConfirmModal(message, onConfirm) {
    const messageEl = document.getElementById('confirmActionMessage');
    if (messageEl) messageEl.textContent = message;
    pendingConfirmCallback = onConfirm;
    bsConfirmModal?.show();
}
function restoreSendModal() {
    const modalEl = document.getElementById('sendModal');
    modalEl.classList.remove('is-minimized');
    document.getElementById('sendMinimizedTab').classList.remove('visible');
    bsModal.show();
}
function focusMessageEditor() {
    document.getElementById('message_editor')?.focus();
}
function rememberEditorSelection() {
    const sel = window.getSelection();
    const editor = document.getElementById('message_editor');
    if (!sel || !editor || sel.rangeCount === 0) return;
    const range = sel.getRangeAt(0);
    if (editor.contains(range.commonAncestorContainer)) {
        savedRange = range.cloneRange();
    }
}
function toggleEmojiPicker(event) {
    event?.stopPropagation();
    rememberEditorSelection();
    document.getElementById('sendEmojiPicker')?.classList.toggle('show');
}
function closeEmojiPicker() {
    document.getElementById('sendEmojiPicker')?.classList.remove('show');
}

function insertEmojiAtCursor(emoji) {
    const ed = document.getElementById('message_editor');
    if (!ed || !emoji) return;
    const rangeToRestore = savedRange;
    ed.focus();
    const sel = window.getSelection();
    if (rangeToRestore) {
        sel.removeAllRanges();
        sel.addRange(rangeToRestore);
    }
    const textNode = document.createTextNode(emoji + ' ');
    if (sel && sel.rangeCount > 0) {
        const range = sel.getRangeAt(0);
        range.deleteContents();
        range.insertNode(textNode);
        range.setStartAfter(textNode);
        range.collapse(true);
        sel.removeAllRanges();
        sel.addRange(range);
        savedRange = range.cloneRange();
    } else {
        ed.appendChild(textNode);
    }
    syncEditor();
    closeEmojiPicker();
}
document.getElementById('fullscreenModalBtn').addEventListener('click', function () {
    const modalEl = document.getElementById('sendModal');
    const icon    = this.querySelector('i');
    modalEl.classList.toggle('is-fullscreen');
    if (modalEl.classList.contains('is-fullscreen')) {
        icon.className = 'bi bi-arrows-angle-contract';
        this.title = 'Exit full screen';
    } else {
        icon.className = 'bi bi-arrows-angle-expand';
        this.title = 'Full screen';
    }
});

document.getElementById('sendModal').addEventListener('hidden.bs.modal', function () {
    this.classList.remove('is-fullscreen', 'is-minimized');
    document.getElementById('fullscreenModalBtn').querySelector('i').className = 'bi bi-arrows-angle-expand';
    document.getElementById('sendMinimizedTab').classList.remove('visible');
});

/* ────────────────────────────────────────────────
   RECIPIENT MODE TOGGLE
   ──────────────────────────────────────────────── */
function toggleRecipientMode() {
    const val        = document.getElementById('send_type').value;
    const searchWrap = document.getElementById('inlineSearchWrap');
    const chipsBox   = document.getElementById('selectedChipsBox');
    const badgeIcon  = document.getElementById('recipientModeBadgeIcon');
    const badgeText  = document.getElementById('recipientModeBadgeText');

    if (val === 'multiple') {
        searchWrap.classList.add('visible');
        chipsBox.style.display = selectedMultiUsers.length ? 'flex' : 'none';
        if (badgeIcon) badgeIcon.className = 'bi bi-person-check-fill';
        if (badgeText) badgeText.textContent = 'Selected users';
    } else {
        searchWrap.classList.remove('visible');
        document.getElementById('customerDropdown').classList.remove('open');
        chipsBox.style.display = 'none';
        if (badgeIcon) badgeIcon.className = 'bi bi-people-fill';
        if (badgeText) badgeText.textContent = 'All users';
    }
}

/* ────────────────────────────────────────────────
   CUSTOMER SEARCH API
   ──────────────────────────────────────────────── */
async function fetchCustomers(q = '') {
    try {
        const r = await fetch(`${customerSearchUrl}?q=${encodeURIComponent(q)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        if (!r.ok) return [];
        const data = await r.json();
        return data.data || [];
    } catch { return []; }
}

function escHtml(v) {
    const d = document.createElement('div');
    d.textContent = v ?? '';
    return d.innerHTML;
}
function getInitial(n) { return (n || 'U').trim()[0].toUpperCase(); }

async function renderCustomerDropdown(q) {
    const dd = document.getElementById('customerDropdown');
    dd.innerHTML = '<div class="cust-dd-empty">Searching…</div>';
    dd.classList.add('open');

    let users = await fetchCustomers(q);
    users = users.filter(u => !selectedMultiUsers.some(s => String(s.id) === String(u.id)));

    dd.innerHTML = '';
    if (!users.length) {
        dd.innerHTML = '<div class="cust-dd-empty">No customers found</div>';
        return;
    }
    users.forEach(user => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'cust-dropdown-item';
        btn.innerHTML = `
            <span class="cust-dd-avatar">
                <img src="${escHtml(user.avatar)}" onerror="this.onerror=null;this.src='${defaultAvatar}'">
            </span>
            <span class="cust-dd-meta">
                <span class="cust-dd-name">${escHtml(user.name)}</span>
                <span class="cust-dd-email">${escHtml(user.email)}</span>
            </span>`;
        btn.addEventListener('click', () => {
            addCustomer(user);
            document.getElementById('customerSearchInput').value = '';
            dd.classList.remove('open');
        });
        dd.appendChild(btn);
    });
    dd.classList.add('open');
}

function addCustomer(user) {
    if (selectedMultiUsers.some(u => String(u.id) === String(user.id))) return;
    selectedMultiUsers.push(user);
    renderChips();
}

function removeCustomer(userId) {
    selectedMultiUsers = selectedMultiUsers.filter(u => String(u.id) !== String(userId));
    renderChips();
}

function renderChips() {
    const box  = document.getElementById('selectedChipsBox');
    const cont = document.getElementById('selectedUserIdsContainer');
    box.innerHTML  = '';
    cont.innerHTML = '';

    if (!selectedMultiUsers.length) {
        box.style.display = 'none';
        return;
    }
    box.style.display = 'flex';

    selectedMultiUsers.forEach(user => {
        const chip = document.createElement('div');
        chip.className = 'cust-chip';
        chip.innerHTML = `
            <span class="chip-avatar">
                <img src="${escHtml(user.avatar)}" onerror="this.onerror=null;this.src='${defaultAvatar}'">
            </span>
            <span class="chip-name">${escHtml(user.name)}</span>
            <button type="button" class="chip-remove">&times;</button>`;
        chip.querySelector('.chip-remove').addEventListener('click', () => removeCustomer(user.id));
        box.appendChild(chip);

        const inp  = document.createElement('input');
        inp.type   = 'hidden';
        inp.name   = 'user_ids[]';
        inp.value  = user.id;
        cont.appendChild(inp);
    });
}

document.getElementById('customerSearchInput').addEventListener('input', function () {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => renderCustomerDropdown(this.value), 280);
});
document.getElementById('customerSearchInput').addEventListener('focus', function () {
    renderCustomerDropdown(this.value);
});

document.addEventListener('click', function (e) {
    const wrap = document.getElementById('inlineSearchWrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('customerDropdown').classList.remove('open');
    }
});
const subjectInput = document.getElementById('subjectInput');
subjectInput.addEventListener('input', function () {
    document.getElementById('subjectCounter').textContent = this.value.length;
});
document.getElementById('subjectCounter').textContent = subjectInput.value.length;
const fmtMap = {
    bold:          'btnBold',
    italic:        'btnItalic',
    underline:     'btnUnderline',
    strikeThrough: 'btnStrike',
};

function execFmt(cmd, btnId) {
    const editor = document.getElementById('message_editor');
    editor.focus();
    document.execCommand(cmd, false, null);
    syncEditor();
    updateToolbarState();
}

function updateToolbarState() {
    Object.entries(fmtMap).forEach(([cmd, id]) => {
        const btn = document.getElementById(id);
        if (!btn) return;
        btn.classList.toggle('is-active', document.queryCommandState(cmd));
    });
}

const editor = document.getElementById('message_editor');
editor.addEventListener('keyup',   updateToolbarState);
editor.addEventListener('mouseup', updateToolbarState);
editor.addEventListener('keyup',   rememberEditorSelection);
editor.addEventListener('mouseup', rememberEditorSelection);
editor.addEventListener('focus',   rememberEditorSelection);
function syncEditor() {
    const ta = document.getElementById('message');
    if (ta) ta.value = document.getElementById('message_editor').innerHTML.trim();
}
editor.addEventListener('input', syncEditor);
editor.addEventListener('keyup', syncEditor);
editor.addEventListener('paste', () => setTimeout(syncEditor, 50));
function openLinkOverlay() {
    closeEmojiPicker();
    const sel = window.getSelection();
    if (sel && sel.rangeCount > 0) {
        savedRange = sel.getRangeAt(0).cloneRange();
        const selText = sel.toString();
        if (selText) document.getElementById('linkDisplayText').value = selText;
    }
    document.getElementById('linkUrlInput').value = '';
    document.getElementById('linkInsertOverlay').classList.add('show');
    setTimeout(() => document.getElementById('linkUrlInput').focus(), 80);
}

function closeLinkOverlay() {
    document.getElementById('linkInsertOverlay').classList.remove('show');
    document.getElementById('linkDisplayText').value = '';
    document.getElementById('linkUrlInput').value = '';
    savedRange = null;
}
function confirmInsertLink() {
    const url  = document.getElementById('linkUrlInput').value.trim();
    const text = document.getElementById('linkDisplayText').value.trim();
    if (!url) { document.getElementById('linkUrlInput').focus(); return; }

    const ed = document.getElementById('message_editor');

    const rangeToRestore = savedRange;
    ed.focus();

    if (rangeToRestore) {
        const sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(rangeToRestore);
    }

    const anchor      = document.createElement('a');
    anchor.href       = /^https?:\/\//i.test(url) ? url : 'https://' + url;
    anchor.target     = '_blank';
    anchor.rel        = 'noopener noreferrer';
    anchor.style.color = '#18c4d4';
    anchor.textContent = text || url;

    const sel = window.getSelection();
    if (sel && sel.rangeCount > 0) {
        const range = sel.getRangeAt(0);
        range.deleteContents();
        range.insertNode(anchor);
        range.setStartAfter(anchor);
        range.setEndAfter(anchor);
        sel.removeAllRanges();
        sel.addRange(range);
    } else {
        ed.appendChild(anchor);
    }

    syncEditor();
    closeLinkOverlay();
}

document.getElementById('linkUrlInput').addEventListener('keydown', function (e) {
    if (e.key === 'Enter') confirmInsertLink();
    if (e.key === 'Escape') closeLinkOverlay();
});
function clearComposer() {
    document.getElementById('subjectInput').value       = '';
    document.getElementById('subjectCounter').textContent = '0';
    document.getElementById('message_editor').innerHTML  = '';
    document.getElementById('message').value             = '';
    document.getElementById('customerSearchInput').value = '';
    document.getElementById('send_type').value           = 'all';
    selectedMultiUsers = [];
    renderChips();
    toggleRecipientMode();
    document.getElementById('customerDropdown').classList.remove('open');
    closeEmojiPicker();
}

document.getElementById('sendEmojiPicker')?.addEventListener('click', function (e) {
    const btn = e.target.closest('.emoji-option');
    if (!btn) return;
    insertEmojiAtCursor(btn.getAttribute('data-emoji') || '');
});

document.addEventListener('click', function (e) {
    const picker = document.getElementById('sendEmojiPicker');
    const toggle = document.getElementById('emojiToggleBtn');
    if (!picker || !picker.classList.contains('show')) return;
    if (picker.contains(e.target) || toggle?.contains(e.target)) return;
    closeEmojiPicker();
});

/* ────────────────────────────────────────────────
   FORM SUBMIT — sync editor before post
   ──────────────────────────────────────────────── */
document.getElementById('sendNotificationForm').addEventListener('submit', syncEditor);

/* ────────────────────────────────────────────────
   CHECKBOX / SELECT ALL
   ──────────────────────────────────────────────── */
function updateSelectedCount() {
    const all     = getCheckboxes();
    const checked = all.filter(c => c.checked);
    const selectedCountEl = getSelectedCountEl();
    const selectAllEl = getSelectAllEl();
    if (selectedCountEl) selectedCountEl.textContent = checked.length;

    document.querySelector('.utility-actions')?.classList.toggle('has-selection', checked.length > 0);

    if (!selectAllEl) return;
    if (!all.length) { selectAllEl.checked = false; selectAllEl.indeterminate = false; return; }
    selectAllEl.checked       = checked.length === all.length;
    selectAllEl.indeterminate = checked.length > 0 && checked.length < all.length;
}

function bindCheckboxListeners(scope = document) {
    scope.querySelectorAll('.notification-checkbox').forEach(c => {
        c.removeEventListener('change', updateSelectedCount);
        c.addEventListener('change', updateSelectedCount);
    });
}

function bindRowInteractions() {}

function escHtmlStr(v) { const d=document.createElement('div'); d.textContent=v??''; return d.innerHTML; }

// Same rule as the server-rendered list: escape first, then wrap order
// numbers in a highlight span — safe since the regex only inserts our
// own trusted markup around text that's already been through escHtmlStr.
function highlightOrderId(v) {
    return escHtmlStr(v).replace(/\b(ORD-[A-Za-z0-9]+)\b/g, '<span class="order-id-highlight">$1</span>');
}

function setLatestNotificationCursor() {
    latestNotificationId = Math.max(
        0,
        ...Array.from(document.querySelectorAll('.notification-item-link[data-id]')).map(el => Number(el.dataset.id || 0))
    );
    const activeTabLink = document.querySelector('.tab-link.active');
    if (activeTabLink) {
        const activeUrl = new URL(activeTabLink.href, window.location.origin);
        currentTab = activeUrl.searchParams.get('tab') || 'order_notification';
    }
}

async function loadNotificationPage(url, pushState = true) {
    const response = await fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' }
    });
    if (!response.ok) throw new Error('Failed to load notifications.');

    const html = await response.text();
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');
    const incoming = doc.querySelector('.notification-wrapper');
    const current = getNotificationWrapper();

    if (!incoming || !current) throw new Error('Notification content not found.');

    current.innerHTML = incoming.innerHTML;
    bindDynamicNotificationUi();
    setLatestNotificationCursor();

    if (pushState) {
        window.history.pushState({ notificationUrl: url }, '', url);
    }
}

async function postNotificationAction(url, options = {}) {
    const response = await fetch(url, {
        method: options.method || 'POST',
        body: options.body,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            ...(options.headers || {}),
        },
    });

    const data = await response.json().catch(() => ({}));

    if (!response.ok || data.success === false) {
        throw new Error(data.message || 'Action failed.');
    }

    return data;
}

/* ────────────────────────────────────────────────
   PER-ROW DELETE (hover trash button)
   ──────────────────────────────────────────────── */
function deleteNotificationRow(btn) {
    const url = btn.dataset.deleteUrl;
    if (!url) return;

    showConfirmModal('This action is permanent and cannot be undone. This notification will be deleted.', function () {
        const token = document.querySelector('input[name="_token"]')?.value || '';
        btn.disabled = true;

        postNotificationAction(url, {
            method: 'POST',
            headers: {
                'X-HTTP-Method-Override': 'DELETE',
                'X-CSRF-TOKEN': token,
            },
        })
            .then(data => {
                showAlert(data.message || 'Notification deleted successfully.');
                return loadNotificationPage(window.location.href, false);
            })
            .catch(err => {
                btn.disabled = false;
                showAlert(err.message, 'error');
            });
    });
}

/* ────────────────────────────────────────────────
   PER-ROW "MORE ACTIONS" MENU (mobile) — tap the ⋮
   ──────────────────────────────────────────────── */
function toggleRowMenu(btn) {
    const dropdown = btn.closest('.row-menu-wrap')?.querySelector('.row-menu-dropdown');
    if (!dropdown) return;
    const isOpen = dropdown.classList.contains('show');
    document.querySelectorAll('.row-menu-dropdown.show').forEach(d => d.classList.remove('show'));
    if (!isOpen) dropdown.classList.add('show');
}

document.addEventListener('click', function (e) {
    if (!e.target.closest('.row-menu-wrap')) {
        document.querySelectorAll('.row-menu-dropdown.show').forEach(d => d.classList.remove('show'));
    }
});

function markRowAsRead(btn) {
    const url = btn.dataset.markReadUrl;
    if (!url) return;

    const token = document.querySelector('input[name="_token"]')?.value || '';
    btn.disabled = true;

    postNotificationAction(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token },
    })
        .then(data => {
            showAlert(data.message || 'Notification marked as read.');
            return loadNotificationPage(window.location.href, false);
        })
        .catch(err => {
            btn.disabled = false;
            showAlert(err.message, 'error');
        });
}

// This whole content block gets replaced wholesale on every tab switch /
// pagination / filter change (see loadNotificationPage), which would
// otherwise silently reset these panels closed each time. Persisting the
// open/closed state in localStorage means it survives that reset instead
// of forcing the user to re-open it after every click elsewhere on the page.
function bindPersistedToggle(toggleId, rowId, storageKey) {
    const toggleBtn = document.getElementById(toggleId);
    const row = document.getElementById(rowId);
    if (!toggleBtn || !row) return;

    if (localStorage.getItem(storageKey) === '1') {
        row.classList.add('show');
    }

    toggleBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        const isShown = row.classList.toggle('show');
        localStorage.setItem(storageKey, isShown ? '1' : '0');
    });
}
function bindMobileDateToggle() {
    const toggleBtn = document.getElementById('mobileDateToggleNotif');
    const dateInput = document.getElementById('date');
    if (!toggleBtn || !dateInput) return;

    toggleBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        if (dateInput.showPicker) {
            dateInput.showPicker();
        } else {
            dateInput.focus();
        }
    });
}

function bindMobileTabsToggle() {
    bindPersistedToggle('mobileTabsToggleNotif', 'notifTabRow', 'notifTabRowVisible');
}

function bindDynamicNotificationUi() {
    bindCheckboxListeners();
    bindRowInteractions();
    updateSelectedCount();
    bindMobileDateToggle();
    bindMobileTabsToggle();
}

/* ────────────────────────────────────────────────
   LIVE NOTIFICATION POLLING
   ──────────────────────────────────────────────── */
function appendNewRows(items) {
    if (!items?.length) return;
    const list = document.getElementById('notificationList');
    if (!list) return;
    list.querySelector('.empty-box')?.remove();

    items.slice().reverse().forEach(item => {
        list.querySelector(`.notification-checkbox[value="${item.id}"]`)
            ?.closest('.notification-item-link')?.remove();

        const row = document.createElement('a');
        row.className = 'notification-item-link';
        row.dataset.id = item.id;
        row.dataset.href = item.show_url;
        row.href = item.show_url;
        row.innerHTML = `
            <div class="notification-item ${item.is_read ? '' : 'selected-row'}">
                <div class="notification-main-left">
                    <input type="checkbox" class="notification-checkbox" name="notification_ids[]" value="${item.id}" onclick="event.stopPropagation();">
                    ${item.type === 'user_contact' ? `
                    <button type="button" class="star-btn" title="Star" onclick="event.preventDefault();event.stopPropagation();">
                        <i class="bi bi-star"></i>
                    </button>` : ''}
                    <div class="avatar-box">
                        <img src="${item.avatar}" alt="avatar" onerror="this.onerror=null;this.src='${defaultAvatar}'">
                        <span class="online-dot"></span>
                    </div>
                    <div class="notification-content">
                        <div class="notification-name-row">
                            <div class="notification-name">${escHtmlStr(item.user_name||'Unknown')}</div>
                            ${item.type === 'user_contact' && item.contact_user_id ? `
                            <button type="button" class="start-chat-btn"
                                onclick="event.preventDefault();event.stopPropagation();window.location.href='${item.chat_url || '#'}';">
                                <i class="bi bi-chat-dots-fill"></i>
                                <span>Start Chat</span>
                            </button>` : ''}
                        </div>
                        <div class="notification-message">${highlightOrderId(item.message||'')}</div>
                    </div>
                </div>
                <div class="notification-right">
                    ${(Number(item.unread_count||0)>0 && !item.is_read) ? `<div class="notification-counter"></div>` : ''}
                    <div class="notification-time">${escHtmlStr(item.time||'')}</div>
                    <button type="button" class="row-hover-delete-btn" title="Delete"
                            data-delete-url="${notificationDestroyUrlTemplate.replace('__ID__', item.id)}"
                            onclick="event.preventDefault();event.stopPropagation();deleteNotificationRow(this);">
                        <i class="bi bi-trash"></i>
                    </button>

                    <div class="row-menu-wrap">
                        <button type="button" class="row-menu-btn" title="More actions"
                                onclick="event.preventDefault();event.stopPropagation();toggleRowMenu(this);">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <div class="row-menu-dropdown">
                            <button type="button" class="row-menu-item"
                                    data-mark-read-url="${notificationReadUrlTemplate.replace('__ID__', item.id)}"
                                    onclick="event.preventDefault();event.stopPropagation();markRowAsRead(this);">
                                <i class="bi bi-envelope-open"></i> Mark as read
                            </button>
                            <button type="button" class="row-menu-item row-menu-item-danger"
                                    data-delete-url="${notificationDestroyUrlTemplate.replace('__ID__', item.id)}"
                                    onclick="event.preventDefault();event.stopPropagation();deleteNotificationRow(this);">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </div>
                    </div>

                    <div class="row-actions-inline">
                        <button type="button" class="row-action-btn" title="Save" onclick="event.preventDefault();event.stopPropagation();"><i class="bi bi-bookmark"></i></button>
                        <button type="button" class="row-action-btn" title="Mark as read" onclick="event.preventDefault();event.stopPropagation();"><i class="bi bi-envelope-open"></i></button>
                        <button type="button" class="row-action-btn" title="Snooze" onclick="event.preventDefault();event.stopPropagation();"><i class="bi bi-clock"></i></button>
                    </div>
                </div>
            </div>`;
        list.prepend(row);
    });

    bindCheckboxListeners(list);
    bindRowInteractions(list);
    setLatestNotificationCursor();
}

function updateTabBadges(counts) {
    if (!counts) return;
    Object.entries(counts).forEach(([key, count]) => {
        const badge = document.querySelector(`[data-tab-badge="${key}"]`);
        if (badge) badge.textContent = Number(count || 0);
    });
}

function showLiveToast(msg) {
    const t = document.createElement('div');
    t.className   = 'live-alert-toast';
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(() => t.classList.add('show'), 50);
    setTimeout(() => { t.classList.remove('show'); setTimeout(() => t.remove(), 300); }, 3000);
}

async function checkLatestNotifications() {
    try {
        const params = new URLSearchParams(window.location.search);
        params.set('last_id', latestNotificationId);
        params.set('tab', params.get('tab') || currentTab);
        const r = await fetch(`${latestNotificationUrl}?${params}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        if (!r.ok) return;
        const result = await r.json();
        updateTabBadges(result.counts);
        if (result.data?.length) {
            appendNewRows(result.data);
            latestNotificationId = result.last_id      || latestNotificationId;
            showLiveToast(`New notification: ${result.data[0].title || result.data[0].type || 'New alert'}`);
        }
    } catch (e) { console.error(e); }
}

/* ────────────────────────────────────────────────
   DOM READY INIT
   ──────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function () {
    toggleRecipientMode();
    renderChips();
    syncEditor();
    bindDynamicNotificationUi();
    setLatestNotificationCursor();

    const selectAllEl = getSelectAllEl();
    if (selectAllEl) {
        selectAllEl.addEventListener('change', function () {
            getCheckboxes().forEach(c => c.checked = this.checked);
            updateSelectedCount();
        });
    }

    @if(old('send_type'))
        bsModal?.show();
    @endif

    setInterval(checkLatestNotifications, 10000);
});

document.addEventListener('change', function (e) {
    if (e.target?.id === 'selectAllNotifications') {
        getCheckboxes().forEach(c => c.checked = e.target.checked);
        updateSelectedCount();
    }

    if (e.target?.classList?.contains('notification-checkbox')) {
        const item = e.target.closest('.notification-item');
        if (item) item.classList.toggle('row-checked', e.target.checked);
    }

    if (e.target?.id === 'date' || e.target?.id === 'per_page') {
        const form = e.target.closest('form');
        if (form) {
            e.preventDefault();
            const url = `${form.action}?${new URLSearchParams(new FormData(form)).toString()}`;
            loadNotificationPage(url).catch(err => showAlert(err.message, 'error'));
        }
    }
});

document.addEventListener('click', function (e) {
    const starBtn = e.target.closest('.star-btn');
    if (starBtn) {
        e.preventDefault();
        e.stopPropagation();
        starBtn.classList.toggle('is-active');
        const icon = starBtn.querySelector('i');
        if (icon) {
            icon.classList.toggle('bi-star');
            icon.classList.toggle('bi-star-fill');
        }
    }
});

document.addEventListener('submit', function (e) {
    const form = e.target;
    if (!(form instanceof HTMLFormElement)) return;

    if (form.id === 'notificationFilterForm' || form.classList.contains('per-page-form')) {
        e.preventDefault();
        const url = `${form.action}?${new URLSearchParams(new FormData(form)).toString()}`;
        loadNotificationPage(url).catch(err => showAlert(err.message, 'error'));
        return;
    }

    if (form.id === 'markAllReadForm') {
        e.preventDefault();
        postNotificationAction(form.action, {
            method: form.method || 'POST',
            body: new FormData(form),
        })
            .then(data => {
                showAlert(data.message || 'All notifications marked as read.');
                return loadNotificationPage(window.location.href, false);
            })
            .catch(err => showAlert(err.message, 'error'));
        return;
    }

    if (form.id === 'deleteForm') {
        e.preventDefault();
        const checkedCount = getCheckboxes().filter(c => c.checked).length;
        if (checkedCount === 0) {
            showAlert('Please select at least one notification.', 'error');
            return;
        }
        const plural = checkedCount > 1 ? 's' : '';
        showConfirmModal(`This action is permanent and cannot be undone. ${checkedCount} selected notification${plural} will be deleted.`, function () {
            postNotificationAction(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'X-HTTP-Method-Override': 'DELETE' },
            })
                .then(data => {
                    showAlert(data.message || 'Selected notifications deleted.');
                    return loadNotificationPage(window.location.href, false);
                })
                .catch(err => showAlert(err.message, 'error'));
        });
        return;
    }

    if (form.id === 'sendNotificationForm') {
        e.preventDefault();

        const sendBtn = form.querySelector('.send-now-btn');
        if (sendBtn?.disabled) return; // already sending — ignore repeat clicks/submits
        if (sendBtn) sendBtn.disabled = true;

        syncEditor();
        postNotificationAction(form.action, {
            method: form.method || 'POST',
            body: new FormData(form),
        })
            .then(data => {
                showAlert(data.message || 'Notification sent successfully.');
                clearComposer();
                bsModal?.hide();
                return loadNotificationPage(window.location.href, false);
            })
            .catch(err => showAlert(err.message, 'error'))
            .finally(() => { if (sendBtn) sendBtn.disabled = false; });
        return;
    }

    if (form.classList.contains('js-detail-delete-form')) {
        e.preventDefault();
        postNotificationAction(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-HTTP-Method-Override': 'DELETE' },
        })
            .then(data => {
                showAlert(data.message || 'Notification deleted successfully.');
                window.location.href = @json(route('admin.notifications.index'));
            })
            .catch(err => showAlert(err.message, 'error'));
    }
});

document.addEventListener('click', function (e) {
    const tabLink = e.target.closest('.tab-link');
    if (tabLink) {
        e.preventDefault();
        loadNotificationPage(tabLink.href).catch(err => showAlert(err.message, 'error'));
        return;
    }

    const pagerBtn = e.target.closest('.pager-btn[href]');
    if (pagerBtn) {
        e.preventDefault();
        loadNotificationPage(pagerBtn.href).catch(err => showAlert(err.message, 'error'));
        return;
    }
});

window.addEventListener('popstate', function () {
    loadNotificationPage(window.location.href, false).catch(err => showAlert(err.message, 'error'));
});

window.addEventListener('beforeunload', () => document.body.classList.remove('admin-notifications-page'));
</script>
@endpush
