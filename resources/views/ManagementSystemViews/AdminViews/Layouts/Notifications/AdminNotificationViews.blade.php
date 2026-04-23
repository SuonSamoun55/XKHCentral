@extends('ManagementSystemViews.AdminViews.Layouts.app')
<link rel="stylesheet" href="{{ asset('css/POSsystem/POSAdmin/notification/admin_notification.css') }}">
@section('title', 'Admin Notifications')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
        /* Alert Container Styles */
      
</style>
@endpush

@section('content')
<div class="app-shell" id="appShell">

    

    <div class="page-wrap">
        <div class="notification-wrapper">

            <div class="notification-page-header">
                <h2 class="page-title">Notification</h2>
            </div>

            <!-- Alert Container -->
            <div class="alert-container" id="alertContainer"></div>

            @if(session('success'))
                
            @endif

            @if(session('error'))
                
            @endif

            @if ($errors->any())
                
            @endif

            <form method="GET" action="{{ route('admin.notifications.index') }}" class="filter-form">
                <div class="top-filter-row">
                    <div class="search-box-noti">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." />
                    </div>

                    <div class="top-right-tools">
                        <div class="date-filter-box">
                            <label for="date">Date</label>
                            <input type="date" name="date" id="date" value="{{ request('date') }}" onchange="this.form.submit()">
                        </div>
                    </div>
                </div>

                <div class="tab-row">
                    <div class="tabs">
                        <a href="{{ route('admin.notifications.index', array_merge(request()->except('page', 'tab'), ['tab' => 'order_notification'])) }}"
                           class="tab-link {{ ($tab ?? 'order_notification') === 'order_notification' ? 'active' : '' }}">
                            Order Notification
                            <span class="tab-badge">{{ $orderCount ?? 0 }}</span>
                        </a>

                        <a href="{{ route('admin.notifications.index', array_merge(request()->except('page', 'tab'), ['tab' => 'user_contact'])) }}"
                           class="tab-link {{ ($tab ?? '') === 'user_contact' ? 'active' : '' }}">
                            User Contact
                            <span class="tab-badge">{{ $userContactCount ?? 0 }}</span>
                        </a>

                        <a href="{{ route('admin.notifications.index', array_merge(request()->except('page', 'tab'), ['tab' => 'out_of_stock'])) }}"
                           class="tab-link {{ ($tab ?? '') === 'out_of_stock' ? 'active' : '' }}">
                            Out of Stock Item
                            <span class="tab-badge">{{ $outOfStockCount ?? 0 }}</span>
                        </a>

                        <a href="{{ route('admin.notifications.index', array_merge(request()->except('page', 'tab'), ['tab' => 'global_message'])) }}"
                           class="tab-link {{ ($tab ?? '') === 'global_message' ? 'active' : '' }}">
                            Global Message
                            <span class="tab-badge">{{ $globalMessageCount ?? 0 }}</span>
                        </a>
                    </div>

                    <div class="right-actions">
                        <a href="{{ route('admin.chat.index') }}" class="btn-send-message" style="text-decoration:none;">
                            <i class="bi bi-telegram"></i>
                            <span>open chat</span>
                        </a>
                        <button type="button" class="btn-send-message" data-bs-toggle="modal" data-bs-target="#sendModal">
                            <i class="bi bi-chat-dots"></i>
                            <span>send message</span>
                        </button>
                    </div>
                </div>
            </form>

            <div class="utility-bar">
                <div class="selected-box">
                    Selected <span id="selectedCount">0</span>
                </div>

                <div class="utility-actions">
                    <form action="{{ route('admin.notifications.read.all') }}" method="POST">
                        @csrf
                        <button type="submit" class="utility-btn">Mark all read</button>
                    </form>

                    <button type="submit" form="deleteForm" class="utility-btn delete-btn">Delete</button>
                </div>
            </div>

            <form action="{{ route('admin.notifications.delete.selected') }}" method="POST" id="deleteForm">
                @csrf
                @method('DELETE')

                <div class="notification-list" id="notificationList">
                    @forelse($notifications as $notification)
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
                            $messagePreview = trim(strip_tags($notification->message ?? ''));
                        @endphp

                        <a href="{{ route('admin.notifications.show', $notification->id) }}" class="notification-item-link">
                            <div class="notification-item {{ !$notification->is_read ? 'selected-row' : '' }}">
                                <div class="notification-main-left">
                                    <input type="checkbox"
                                           class="notification-checkbox"
                                           name="notification_ids[]"
                                           value="{{ $notification->id }}"
                                           onclick="event.preventDefault(); event.stopPropagation(); this.checked = !this.checked; updateSelectedCount();">

                                    <div class="avatar-box">
                                        <img
                                            src="{{ $avatarSrc }}"
                                            alt="avatar"
                                            onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}'">
                                        <span class="online-dot"></span>
                                    </div>

                                    <div class="notification-content">
                                        <div class="notification-name-row">
                                            <div class="notification-name">
                                                {{ $displayName }}
                                            </div>
                                            @if(optional($contactUser)->id)
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-outline-info"
                                                    style="padding:2px 8px; font-size:11px;"
                                                    onclick="event.preventDefault(); event.stopPropagation(); window.location.href='{{ route('admin.chat.index', ['user_id' => $contactUser->id]) }}';"
                                                >
                                                    Chat
                                                </button>
                                            @endif
                                        </div>

                                        <div class="notification-message">
                                            {{ $messagePreview !== '' ? $messagePreview : 'Enter your message description here...' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="notification-right">
                                    @if((int) ($notification->unread_count ?? 0) > 0 && !$notification->is_read)
                                        <div class="notification-counter">{{ (int) $notification->unread_count }}</div>
                                    @endif
                                    <div class="notification-time">
                                        {{ optional($notification->updated_at)->format('H:i') }}
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
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>

<!-- SEND MESSAGE MODAL -->
<div class="modal fade custom-send-modal" id="sendModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content send-modal-content">

            <form action="{{ route('admin.notifications.store') }}" method="POST" id="sendNotificationForm">
                @csrf

                <div class="send-modal-header">
                    <div class="send-to-row">
                        <span class="send-label">to</span>

                        <div class="send-recipient-area">
                            <div class="recipient-top">
                                <select name="send_type" id="send_type" class="recipient-type-select" onchange="toggleRecipientMode()">
                                    <option value="all" {{ old('send_type') === 'all' ? 'selected' : '' }}>All Customers</option>
                                    <option value="specific" {{ old('send_type') === 'specific' ? 'selected' : '' }}>Specific Customer</option>
                                    <option value="multiple" {{ old('send_type') === 'multiple' ? 'selected' : '' }}>Multiple Customers</option>
                                </select>

                                <div id="selected_user_preview" class="selected-user-preview" style="display:none;">
                                    <span class="user-avatar-mini" id="selected_user_initial">U</span>
                                    <div class="user-meta-mini">
                                        <span class="user-name-mini" id="selected_user_name">Customer Name</span>
                                        <span class="user-email-mini" id="selected_user_email">customer@email.com</span>
                                    </div>
                                </div>
                            </div>

                            <div class="recipient-select-wrap" id="user_select_box" style="display:none;">
                                <div class="searchable-select-wrap">
                                    <input type="text" id="singleUserSearch" class="customer-search-input" placeholder="Search customer...">
                                    <div id="singleUserDropdown" class="customer-search-dropdown"></div>
                                </div>
                                <input type="hidden" name="user_id" id="user_id" value="{{ old('user_id') }}">
                            </div>

                            <div class="recipient-select-wrap" id="multi_user_select_box" style="display:none;">
                                <div class="multi-chip-box" id="selectedUsersChips"></div>

                                <div class="searchable-select-wrap">
                                    <input type="text" id="multiUserSearch" class="customer-search-input" placeholder="Search customers...">
                                    <div id="multiUserDropdown" class="customer-search-dropdown"></div>
                                </div>

                                <div id="selectedUserIdsContainer"></div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn-close send-close-btn" data-bs-dismiss="modal"></button>
                </div>

                <div class="send-modal-body">
                    <div class="send-subject-row">
                        <input
                            type="text"
                            name="title"
                            class="send-subject-input"
                            placeholder="Subject"
                            value="{{ old('title') }}"
                            maxlength="255">
                        <span class="subject-counter" id="subjectCounter">0</span>
                    </div>

                    <input type="hidden" name="type" value="{{ old('type', 'admin_message') }}">

                    <div class="send-editor-wrap">
                        <div
                            id="message_editor"
                            class="send-message-editor"
                            contenteditable="true"
                            data-placeholder="Write your message...">{!! old('message') !!}</div>

                        <textarea
                            name="message"
                            id="message"
                            class="send-message-textarea d-none"
                            rows="10">{{ old('message') }}</textarea>

                        <div class="editor-toolbar">
                            <button type="button" class="toolbar-btn" onclick="formatEditor('bold')" title="Bold"><b>B</b></button>
                            <button type="button" class="toolbar-btn" onclick="formatEditor('italic')" title="Italic"><i>I</i></button>
                            <button type="button" class="toolbar-btn" onclick="insertBulletList()" title="Bullet List">•</button>
                            <button type="button" class="toolbar-btn" onclick="insertNumberList()" title="Number List">≡</button>
                        </div>

                        <div class="send-message-preview-wrap">
                            <div class="send-message-preview-label">Send Message Preview</div>
                            <div id="sendMessagePreview" class="send-message-preview-text">Write your message...</div>
                        </div>
                    </div>
                </div>

                <div class="send-modal-footer">
                    <div class="footer-left-tools">
                        <button type="button" class="footer-icon-btn" title="Clear message" onclick="clearComposer()">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>

                    <div class="footer-right-tools">
                        <button type="submit" class="send-now-btn">
                            <span>send now</span>
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>





<style>

</style>
@endsection

@push('scripts')
<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showAlert('{{ session('success') }}', 'success');
                    });
                </script>
<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showAlert('{{ session('error') }}', 'danger');
                    });
                </script>
<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showAlert('Please fix the errors below', 'danger');
                    });
                </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Alert functionality
    const alertContainer = document.getElementById('alertContainer');

    function showAlert(message, type = 'success') {
        const alertEl = document.createElement('div');
        alertEl.className = `custom-alert alert-${type}`;
        const iconClass = type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill';
        alertEl.innerHTML = `
            <i class="bi bi-${iconClass}"></i>
            <span>${message}</span>
        `;

        alertContainer.appendChild(alertEl);

        // Auto-close after 4 seconds
        setTimeout(() => {
            alertEl.classList.add('fade-out');
            setTimeout(() => alertEl.remove(), 300);
        }, 4000);
    }

    const checkboxes = document.querySelectorAll('.notification-checkbox');
    const selectedCount = document.getElementById('selectedCount');
    const defaultAvatar = @json(asset('images/default-avatar.png'));
    const customerSearchUrl = @json(route('admin.notifications.ajax.search.customers'));
    const latestNotificationUrl = @json(route('admin.notifications.ajax.latest'));

    let selectedMultiUsers = [];
    let singleSearchTimer = null;
    let multiSearchTimer = null;
    let latestNotificationId = {{ (int) ($notifications->max('id') ?? 0) }};
    let latestNotificationSeenAt = @json(optional($notifications->max('updated_at'))->toDateTimeString());

    function updateSelectedCount() {
        const checked = document.querySelectorAll('.notification-checkbox:checked');
        selectedCount.textContent = checked.length;
    }

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value ?? '';
        return div.innerHTML;
    }

    function getInitial(name) {
        return (name || 'U').trim().charAt(0).toUpperCase();
    }

    function toggleRecipientMode() {
        const sendType = document.getElementById('send_type').value;
        const singleBox = document.getElementById('user_select_box');
        const multiBox = document.getElementById('multi_user_select_box');
        const preview = document.getElementById('selected_user_preview');

        if (sendType === 'specific') {
            singleBox.style.display = 'block';
            multiBox.style.display = 'none';
            preview.style.display = 'none';
            return;
        }

        if (sendType === 'multiple') {
            singleBox.style.display = 'none';
            multiBox.style.display = 'block';
            preview.style.display = 'none';
            renderMultiSelectedUsers();
            return;
        }

        singleBox.style.display = 'none';
        multiBox.style.display = 'none';
        preview.style.display = 'none';
    }

    async function fetchCustomers(keyword = '') {
        const url = `${customerSearchUrl}?q=${encodeURIComponent(keyword)}`;

        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                return [];
            }

            const result = await response.json();
            return result.data || [];
        } catch (error) {
            return [];
        }
    }

    function updateSelectedUserPreview(user) {
        const preview = document.getElementById('selected_user_preview');

        if (!user) {
            preview.style.display = 'none';
            return;
        }

        document.getElementById('selected_user_name').textContent = user.name || 'User';
        document.getElementById('selected_user_email').textContent = user.email || '';
        document.getElementById('selected_user_initial').textContent = getInitial(user.name);
        preview.style.display = 'inline-flex';
    }

    async function renderSingleDropdown(keyword = '') {
        const dropdown = document.getElementById('singleUserDropdown');
        dropdown.innerHTML = '<div class="customer-dropdown-empty">Searching...</div>';
        dropdown.classList.add('show');

        const users = await fetchCustomers(keyword);
        dropdown.innerHTML = '';

        if (!users.length) {
            dropdown.innerHTML = '<div class="customer-dropdown-empty">No customer found</div>';
            dropdown.classList.add('show');
            return;
        }

        users.forEach(user => {
            const item = document.createElement('button');
            item.type = 'button';
            item.className = 'customer-dropdown-item';
            item.innerHTML = `
                <span class="customer-dropdown-avatar-wrap">
                    <img src="${user.avatar}" class="customer-dropdown-photo" onerror="this.onerror=null;this.src='${defaultAvatar}'">
                </span>
                <span class="customer-dropdown-meta">
                    <span class="customer-dropdown-name">${escapeHtml(user.name)}</span>
                    <span class="customer-dropdown-email">${escapeHtml(user.email)} - ${escapeHtml(user.customer_no)}</span>
                </span>
            `;
            item.addEventListener('click', function () {
                document.getElementById('user_id').value = user.id;
                document.getElementById('singleUserSearch').value = `${user.name} - ${user.email} - ${user.customer_no}`;
                dropdown.classList.remove('show');
                updateSelectedUserPreview(user);
            });
            dropdown.appendChild(item);
        });

        dropdown.classList.add('show');
    }

    async function renderMultiDropdown(keyword = '') {
        const dropdown = document.getElementById('multiUserDropdown');
        dropdown.innerHTML = '<div class="customer-dropdown-empty">Searching...</div>';
        dropdown.classList.add('show');

        let users = await fetchCustomers(keyword);

        users = users.filter(user => {
            return !selectedMultiUsers.some(selected => String(selected.id) === String(user.id));
        });

        dropdown.innerHTML = '';

        if (!users.length) {
            dropdown.innerHTML = '<div class="customer-dropdown-empty">No customer found</div>';
            dropdown.classList.add('show');
            return;
        }

        users.forEach(user => {
            const item = document.createElement('button');
            item.type = 'button';
            item.className = 'customer-dropdown-item';
            item.innerHTML = `
                <span class="customer-dropdown-avatar-wrap">
                    <img src="${user.avatar}" class="customer-dropdown-photo" onerror="this.onerror=null;this.src='${defaultAvatar}'">
                </span>
                <span class="customer-dropdown-meta">
                    <span class="customer-dropdown-name">${escapeHtml(user.name)}</span>
                    <span class="customer-dropdown-email">${escapeHtml(user.email)} - ${escapeHtml(user.customer_no)}</span>
                </span>
            `;
            item.addEventListener('click', function () {
                addMultiUser(user);
                document.getElementById('multiUserSearch').value = '';
                dropdown.classList.remove('show');
            });
            dropdown.appendChild(item);
        });

        dropdown.classList.add('show');
    }

    function addMultiUser(user) {
        const exists = selectedMultiUsers.some(item => String(item.id) === String(user.id));
        if (exists) return;

        selectedMultiUsers.push(user);
        renderMultiSelectedUsers();
    }

    function removeMultiUser(userId) {
        selectedMultiUsers = selectedMultiUsers.filter(item => String(item.id) !== String(userId));
        renderMultiSelectedUsers();
    }

    function renderMultiSelectedUsers() {
        const chipBox = document.getElementById('selectedUsersChips');
        const idsContainer = document.getElementById('selectedUserIdsContainer');

        chipBox.innerHTML = '';
        idsContainer.innerHTML = '';

        if (!selectedMultiUsers.length) {
            chipBox.innerHTML = '<div class="chip-placeholder">Selected customers will show here</div>';
            return;
        }

        selectedMultiUsers.forEach(user => {
            const chip = document.createElement('div');
            chip.className = 'selected-customer-chip';
            chip.innerHTML = `
                <span class="selected-customer-avatar-wrap">
                    <img src="${user.avatar}" class="selected-customer-photo" onerror="this.onerror=null;this.src='${defaultAvatar}'">
                </span>
                <span class="selected-customer-text">
                    <span class="selected-customer-name">${escapeHtml(user.name)}</span>
                    <span class="selected-customer-email">${escapeHtml(user.email)}</span>
                </span>
                <button type="button" class="selected-customer-remove">&times;</button>
            `;

            chip.querySelector('.selected-customer-remove').addEventListener('click', function () {
                removeMultiUser(user.id);
            });

            chipBox.appendChild(chip);

            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'user_ids[]';
            hidden.value = user.id;
            idsContainer.appendChild(hidden);
        });
    }

    function focusEditor() {
        const editor = document.getElementById('message_editor');
        if (editor) editor.focus();
    }

    function formatEditor(command) {
        focusEditor();
        document.execCommand(command, false, null);
        syncMessageToTextarea();
    }

    function insertBulletList() {
        focusEditor();
        document.execCommand('insertUnorderedList', false, null);
        syncMessageToTextarea();
    }

    function insertNumberList() {
        focusEditor();
        document.execCommand('insertOrderedList', false, null);
        syncMessageToTextarea();
    }

    function syncMessageToTextarea() {
        const editor = document.getElementById('message_editor');
        const textarea = document.getElementById('message');
        const preview = document.getElementById('sendMessagePreview');

        if (!editor || !textarea) return;
        textarea.value = editor.innerHTML.trim();

        if (preview) {
            const plainText = (editor.textContent || '').trim();
            preview.textContent = plainText !== '' ? plainText : 'Write your message...';
        }
    }

    function clearComposer() {
        const subjectInput = document.querySelector('.send-subject-input');
        const editor = document.getElementById('message_editor');
        const textarea = document.getElementById('message');
        const preview = document.getElementById('sendMessagePreview');

        subjectInput.value = '';
        document.getElementById('subjectCounter').textContent = '0';
        editor.innerHTML = '';
        textarea.value = '';
        if (preview) {
            preview.textContent = 'Write your message...';
        }

        document.getElementById('singleUserSearch').value = '';
        document.getElementById('multiUserSearch').value = '';
        document.getElementById('user_id').value = '';
        document.getElementById('selected_user_preview').style.display = 'none';

        selectedMultiUsers = [];
        renderMultiSelectedUsers();

        document.getElementById('singleUserDropdown').classList.remove('show');
        document.getElementById('multiUserDropdown').classList.remove('show');
    }

    function appendNewNotificationRows(items) {
        if (!items || !items.length) return;

        const list = document.getElementById('notificationList');
        if (!list) return;

        const emptyBox = list.querySelector('.empty-box');
        if (emptyBox) {
            emptyBox.remove();
        }

        items.slice().reverse().forEach(item => {
            const existingCheckbox = list.querySelector(`.notification-checkbox[value="${item.id}"]`);
            if (existingCheckbox) {
                const existingRow = existingCheckbox.closest('.notification-item-link');
                if (existingRow) {
                    existingRow.remove();
                }
            }

            const row = document.createElement('a');
            row.href = item.show_url;
            row.className = 'notification-item-link';
            row.innerHTML = `
                <div class="notification-item ${item.is_read ? '' : 'selected-row'}">
                    <div class="notification-main-left">
                        <input type="checkbox"
                               class="notification-checkbox"
                               name="notification_ids[]"
                               value="${item.id}"
                               onclick="event.preventDefault(); event.stopPropagation(); this.checked = !this.checked; updateSelectedCount();">

                        <div class="avatar-box">
                            <img src="${item.avatar}" alt="avatar" onerror="this.onerror=null;this.src='${defaultAvatar}'">
                            <span class="online-dot"></span>
                        </div>

                        <div class="notification-content">
                            <div class="notification-name-row">
                                <div class="notification-name">${escapeHtml(item.user_name || 'Unknown User')}</div>
                            </div>
                            <div class="notification-message">${escapeHtml(item.message || '')}</div>
                        </div>
                    </div>

                    <div class="notification-right">
                        ${(Number(item.unread_count || 0) > 0 && !item.is_read) ? `<div class="notification-counter">${Number(item.unread_count)}</div>` : ''}
                        <div class="notification-time">${escapeHtml(item.time || '')}</div>
                    </div>
                </div>
            `;
            list.prepend(row);
        });

        document.querySelectorAll('.notification-checkbox').forEach((checkbox) => {
            checkbox.removeEventListener('change', updateSelectedCount);
            checkbox.addEventListener('change', updateSelectedCount);
        });
    }

    function showNotificationToast(message) {
        const toast = document.createElement('div');
        toast.className = 'live-alert-toast';
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 50);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    async function checkLatestNotifications() {
        try {
            const url = `${latestNotificationUrl}?last_id=${latestNotificationId}&last_seen_at=${encodeURIComponent(latestNotificationSeenAt || '')}`;
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) return;

            const result = await response.json();

            if (result.data && result.data.length > 0) {
                appendNewNotificationRows(result.data);
                latestNotificationId = result.last_id || latestNotificationId;
                latestNotificationSeenAt = result.last_seen_at || latestNotificationSeenAt;

                const first = result.data[0];
                showNotificationToast(`New notification: ${first.title || first.type || 'New alert'}`);
            }
        } catch (error) {
            console.error(error);
        }
    }

    document.addEventListener('click', function (e) {
        const singleWrap = document.querySelector('#user_select_box .searchable-select-wrap');
        const multiWrap = document.querySelector('#multi_user_select_box .searchable-select-wrap');

        if (singleWrap && !singleWrap.contains(e.target)) {
            document.getElementById('singleUserDropdown').classList.remove('show');
        }

        if (multiWrap && !multiWrap.contains(e.target)) {
            document.getElementById('multiUserDropdown').classList.remove('show');
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const subjectInput = document.querySelector('.send-subject-input');
        const subjectCounter = document.getElementById('subjectCounter');
        const form = document.getElementById('sendNotificationForm');
        const editor = document.getElementById('message_editor');
        const singleUserSearch = document.getElementById('singleUserSearch');
        const multiUserSearch = document.getElementById('multiUserSearch');

        function updateSubjectCounter() {
            subjectCounter.textContent = subjectInput.value.length;
        }

        subjectInput.addEventListener('input', updateSubjectCounter);
        updateSubjectCounter();

        editor.addEventListener('input', syncMessageToTextarea);
        editor.addEventListener('keyup', syncMessageToTextarea);
        editor.addEventListener('paste', function () {
            setTimeout(syncMessageToTextarea, 50);
        });

        singleUserSearch.addEventListener('focus', function () {
            renderSingleDropdown(singleUserSearch.value);
        });

        singleUserSearch.addEventListener('input', function () {
            clearTimeout(singleSearchTimer);
            singleSearchTimer = setTimeout(() => {
                renderSingleDropdown(singleUserSearch.value);
            }, 300);
        });

        multiUserSearch.addEventListener('focus', function () {
            renderMultiDropdown(multiUserSearch.value);
        });

        multiUserSearch.addEventListener('input', function () {
            clearTimeout(multiSearchTimer);
            multiSearchTimer = setTimeout(() => {
                renderMultiDropdown(multiUserSearch.value);
            }, 300);
        });

        form.addEventListener('submit', function () {
            syncMessageToTextarea();
        });

        toggleRecipientMode();
        renderMultiSelectedUsers();
        syncMessageToTextarea();
        updateSelectedCount();

        @if(old('send_type'))
            const modal = new bootstrap.Modal(document.getElementById('sendModal'));
            modal.show();
        @endif

        setInterval(checkLatestNotifications, 10000);
    });
</script>
@endpush
