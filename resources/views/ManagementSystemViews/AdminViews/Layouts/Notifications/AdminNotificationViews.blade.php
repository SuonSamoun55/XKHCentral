@extends('ManagementSystemViews.AdminViews.Layouts.app')
<link rel="stylesheet" href="{{ asset('css/POSsystem/POSAdmin/notification/admin_notification.css') }}">
@section('title', 'Admin Notifications')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    body.admin-notifications-page,
    body.admin-notifications-page .main-wrapper,
    body.admin-notifications-page .content-area {
        height: 100vh;
        overflow: hidden;
    }

    /* ============================================================
       SEND MESSAGE MODAL — FULL REPLACEMENT
       ============================================================ */

    /* ── OVERLAY ── */
    #sendModal .modal-dialog {
        max-width: 660px;
    }

    #sendModal .modal-content {
        border: none;
        border-radius: 20px;
        overflow: visible;
        background: #fff;
        box-shadow: 0 20px 70px rgba(15, 23, 42, 0.20);
        font-family: 'DM Sans', Arial, sans-serif;
    }

    /* ── HEADER ── */
    #sendModal .send-modal-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        padding: 16px 20px 14px;
        border-bottom: 1px solid #f0f2f5;
        border-radius: 20px 20px 0 0;
        background: #fff;
    }

    #sendModal .send-to-row {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        flex: 1;
        min-width: 0;
    }

    #sendModal .send-label {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        height: 34px;
        padding: 0 10px;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        background: #f8fafc;
        border: 1px solid #e4e7ec;
        border-radius: 999px;
        flex-shrink: 0;
    }
    #sendModal .send-label i {
        color: #18c4d4;
        font-size: 13px;
    }

    #sendModal .send-recipient-area {
        flex: 1;
        min-width: 0;
    }

    /* ── RECIPIENT TOP ROW: select + inline search ── */
    #sendModal .recipient-top {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    /* Type select */
    #sendModal .recipient-type-select {
        height: 34px;
        min-width: 155px;
        border: 1px solid #e4e7ec;
        border-radius: 9px;
        padding: 0 28px 0 10px;
        font-size: 13px;
        color: #374151;
        background: #fff;
        font-family: inherit;
        outline: none;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 9px center;
        transition: border-color 0.15s, box-shadow 0.15s;
    }
    #sendModal .recipient-type-select:focus {
        border-color: #18c4d4;
        box-shadow: 0 0 0 3px rgba(24,196,212,0.12);
    }

    /* ── INLINE CUSTOMER SEARCH (sits right next to the select) ── */
    #sendModal .inline-customer-search-wrap {
        position: relative;
        flex: 1;
        min-width: 180px;
        max-width: 300px;
        display: none; /* shown via JS */
    }
    #sendModal .inline-customer-search-wrap.visible {
        display: block;
    }
    #sendModal .inline-customer-input {
        width: 100%;
        height: 34px;
        border: 1px solid #e4e7ec;
        border-radius: 9px;
        padding: 0 12px 0 32px;
        font-size: 13px;
        color: #374151;
        font-family: inherit;
        outline: none;
        background: #f8fffe;
        transition: border-color 0.15s, box-shadow 0.15s;
    }
    #sendModal .inline-customer-input:focus {
        border-color: #18c4d4;
        box-shadow: 0 0 0 3px rgba(24,196,212,0.12);
        background: #fff;
    }
    #sendModal .inline-search-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 12px;
        pointer-events: none;
    }
    #sendModal .inline-customer-dropdown {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid #e4e7ec;
        border-radius: 12px;
        box-shadow: 0 12px 32px rgba(15,23,42,0.12);
        max-height: 210px;
        overflow-y: auto;
        z-index: 1060;
        display: none;
        padding: 6px;
    }
    #sendModal .inline-customer-dropdown.open {
        display: block;
    }

    /* Dropdown items */
    #sendModal .cust-dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        border: none;
        background: transparent;
        text-align: left;
        padding: 8px 10px;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.1s;
        font-family: inherit;
    }
    #sendModal .cust-dropdown-item:hover { background: #e0f8fa; }

    #sendModal .cust-dd-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        overflow: hidden;
        background: #e6e9ef;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 11px;
        font-weight: 700;
        color: #fff;
    }
    #sendModal .cust-dd-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    #sendModal .cust-dd-meta { display: flex; flex-direction: column; min-width: 0; }
    #sendModal .cust-dd-name  { font-size: 13px; font-weight: 600; color: #111827; line-height: 1.2; }
    #sendModal .cust-dd-email { font-size: 11px; color: #6b7280; margin-top: 1px; }
    #sendModal .cust-dd-empty { padding: 10px 12px; font-size: 12px; color: #94a3b8; }

    /* ── CHIPS (selected customers) ── */
    #sendModal .chip-box {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 8px;
        min-height: 4px;
    }
    #sendModal .cust-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 3px 7px 3px 3px;
        background: #e0f8fa;
        border: 1px solid #b2edf2;
        border-radius: 999px;
    }
    #sendModal .chip-avatar {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        overflow: hidden;
        background: #18c4d4;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }
    #sendModal .chip-avatar img { width: 100%; height: 100%; object-fit: cover; }
    #sendModal .chip-name  { font-size: 11px; font-weight: 600; color: #0d6b75; }
    #sendModal .chip-remove {
        border: none;
        background: transparent;
        color: #4da8b5;
        font-size: 15px;
        line-height: 1;
        cursor: pointer;
        padding: 0;
        display: flex;
        align-items: center;
    }
    #sendModal .chip-remove:hover { color: #0d6b75; }

    /* ── HEADER ICON BUTTONS ── */
    #sendModal .send-header-actions {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
        padding-top: 1px;
    }
    #sendModal .header-icon-btn {
        width: 30px;
        height: 30px;
        border: 1px solid #e4e7ec;
        border-radius: 9px;
        background: #f8fafc;
        color: #9aa3b2;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 13px;
        transition: background 0.12s, color 0.12s, border-color 0.12s;
    }
    #sendModal .header-icon-btn:hover {
        background: #edf2f7;
        color: #555d6b;
        border-color: #d1d5db;
    }
    #sendModal .header-icon-btn.close-btn:hover {
        background: #fef2f2;
        border-color: #fca5a5;
        color: #ef4444;
    }

    /* ── SUBJECT ROW ── */
    #sendModal .send-subject-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 20px;
        min-height: 52px;
        border-bottom: 1px solid #f0f2f5;
    }
    #sendModal .subject-row-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #eefbfc;
        color: #18c4d4;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }
    #sendModal .send-subject-input {
        flex: 1;
        border: none;
        outline: none;
        font-family: inherit;
        font-size: 16px;
        font-weight: 600;
        color: #111827;
        background: transparent;
        padding: 14px 0;
    }
    #sendModal .send-subject-input::placeholder { color: #d1d5db; font-weight: 400; }
    #sendModal .subject-counter {
        font-size: 11px;
        color: #c4cad4;
        background: #f5f7fb;
        padding: 3px 8px;
        border-radius: 6px;
        min-width: 28px;
        text-align: center;
    }

    /* ── EDITOR AREA ── */
    #sendModal .send-modal-body { padding: 0; }
    #sendModal .send-editor-wrap { position: relative; }
    #sendModal .editor-mode-badge {
        position: absolute;
        top: 14px;
        right: 18px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        background: rgba(238, 251, 252, 0.96);
        color: #0f766e;
        font-size: 11px;
        font-weight: 600;
        border: 1px solid #c7f2f5;
        z-index: 5;
        pointer-events: none;
    }
    #sendModal .editor-mode-badge i {
        color: #18c4d4;
        font-size: 12px;
    }

    #sendModal .send-message-editor {
        width: 100%;
        min-height: 220px;
        border: none;
        outline: none;
        padding: 16px 20px 90px;
        font-family: inherit;
        font-size: 14px;
        line-height: 1.7;
        color: #1f2937;
        background: #fff;
        white-space: normal;
        overflow-wrap: break-word;
        overflow-y: auto;
    }
    #sendModal .send-message-editor:empty::before {
        content: attr(data-placeholder);
        color: #d1d5db;
        pointer-events: none;
    }
    #sendModal .send-message-editor ul,
    #sendModal .send-message-editor ol { padding-left: 22px; margin-bottom: 0; }

    #sendModal .send-message-textarea {
        width: 100%;
        min-height: 220px;
        border: none;
        outline: none;
        resize: none;
        padding: 16px 20px 90px;
        font-family: inherit;
        font-size: 14px;
        line-height: 1.7;
        color: #1f2937;
        background: #fff;
    }

    /* ── FLOATING TOOLBAR ── */
    #sendModal .editor-toolbar {
        position: absolute;
        left: 20px;
        bottom: 13px;
        display: inline-flex;
        align-items: center;
        gap: 2px;
        padding: 5px 8px;
        background: rgba(255,255,255,0.98);
        border: 1px solid #e4e7ec;
        border-radius: 10px;
        box-shadow: 0 4px 18px rgba(15,23,42,0.09);
        z-index: 10;
    }
    #sendModal .toolbar-type-pill {
        height: 28px;
        padding: 0 10px;
        border-radius: 7px;
        border: none;
        background: transparent;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 500;
        color: #374151;
        cursor: pointer;
        transition: background 0.12s;
    }
    #sendModal .toolbar-type-pill:hover { background: #f0f4f8; }
    #sendModal .toolbar-divider {
        width: 1px;
        height: 14px;
        background: #e4e7ec;
        margin: 0 4px;
        flex-shrink: 0;
    }
    #sendModal .toolbar-btn {
        width: 28px;
        height: 28px;
        border: none;
        border-radius: 7px;
        background: transparent;
        color: #6b7280;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: background 0.12s, color 0.12s;
    }
    #sendModal .toolbar-btn:hover { background: #f0f4f8; color: #334155; }
    /* Active state — highlighted when format is applied */
    #sendModal .toolbar-btn.is-active {
        background: #e0f8fa;
        color: #0d8f9e;
        box-shadow: inset 0 0 0 1px #b2edf2;
    }

    /* ── LINK INSERT OVERLAY ── */
    #sendModal .link-insert-overlay {
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,0.72);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 200;
    }
    #sendModal .link-insert-overlay.show { display: flex; }
    #sendModal .link-insert-box {
        background: #fff;
        border: 1px solid #e4e7ec;
        border-radius: 14px;
        box-shadow: 0 14px 44px rgba(15,23,42,0.14);
        padding: 20px;
        width: 320px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    #sendModal .link-insert-title { font-size: 14px; font-weight: 700; color: #111827; margin: 0; }
    #sendModal .link-insert-field { display: flex; flex-direction: column; gap: 4px; }
    #sendModal .link-insert-field label {
        font-size: 11px;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    #sendModal .link-insert-field input {
        height: 36px;
        border: 1px solid #dbe2ea;
        border-radius: 9px;
        padding: 0 12px;
        font-family: inherit;
        font-size: 13px;
        color: #334155;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
    }
    #sendModal .link-insert-field input:focus {
        border-color: #18c4d4;
        box-shadow: 0 0 0 3px rgba(24,196,212,0.12);
    }
    #sendModal .link-insert-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 4px; }
    #sendModal .link-cancel-btn {
        height: 32px;
        padding: 0 14px;
        border: 1px solid #e4e7ec;
        border-radius: 8px;
        background: #fff;
        color: #6b7280;
        font-family: inherit;
        font-size: 12px;
        cursor: pointer;
    }
    #sendModal .link-cancel-btn:hover { background: #f8fafc; }
    #sendModal .link-confirm-btn {
        height: 32px;
        padding: 0 16px;
        border: none;
        border-radius: 8px;
        background: #18c4d4;
        color: #fff;
        font-family: inherit;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.14s;
    }
    #sendModal .link-confirm-btn:hover { background: #0fa8b8; }

    /* ── EMOJI PICKER ── */
    #sendModal .emoji-picker {
        position: absolute;
        right: 88px;
        bottom: 58px;
        width: 316px;
        max-width: calc(100vw - 44px);
        padding: 18px 16px 14px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.18);
        display: none;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 10px;
        z-index: 120;
    }
    #sendModal .emoji-picker.show { display: grid; }
    #sendModal .emoji-picker::after {
        content: '';
        position: absolute;
        right: 52px;
        bottom: -9px;
        width: 18px;
        height: 18px;
        background: #fff;
        border-right: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
        transform: rotate(45deg);
    }
    #sendModal .emoji-option {
        width: 46px;
        height: 46px;
        border: none;
        border-radius: 14px;
        background: transparent;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 27px;
        line-height: 1;
        cursor: pointer;
        transition: background 0.14s, transform 0.14s;
    }
    #sendModal .emoji-option:hover {
        background: #f5f7fb;
        transform: translateY(-1px);
    }

    /* ── FOOTER ── */
    #sendModal .send-modal-footer {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 10px 16px 12px;
        border-top: 1px solid #f0f2f5;
        background: #f8f9fc;
        border-radius: 0 0 20px 20px;
    }
    #sendModal .footer-left-tools,
    #sendModal .footer-right-tools { display: flex; align-items: center; gap: 4px; }
    #sendModal .footer-icon-btn {
        width: 34px;
        height: 34px;
        border: none;
        background: transparent;
        color: #b0b9c8;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.14s, color 0.14s;
    }
    #sendModal .footer-icon-btn:hover { background: #edf2f7; color: #64748b; }
    #sendModal .footer-icon-btn.danger-btn:hover { background: #fef2f2; color: #ef4444; }
    #sendModal .send-now-btn {
        height: 40px;
        border: none;
        border-radius: 12px;
        background: #18c4d4;
        color: #fff;
        padding: 0 20px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-transform: lowercase;
        box-shadow: 0 5px 16px rgba(24,196,212,0.30);
        transition: background 0.14s, transform 0.1s, box-shadow 0.14s;
    }
    #sendModal .send-now-btn:hover {
        background: #0fa8b8;
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(24,196,212,0.36);
    }

    /* ── MINIMIZED STATE ── */
    #sendModal.modal.is-minimized {
        pointer-events: none;
    }
    .send-minimized-tab {
        position: fixed;
        bottom: 0;
        right: 28px;
        background: #fff;
        border: 1px solid #e4e7ec;
        border-bottom: none;
        border-radius: 12px 12px 0 0;
        padding: 10px 18px;
        box-shadow: 0 -4px 20px rgba(15,23,42,0.10);
        display: none;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        z-index: 1055;
        transition: background 0.14s;
    }
    .send-minimized-tab:hover { background: #f0fffe; }
    .send-minimized-tab.visible { display: flex; }
    .minimized-dot { width: 8px; height: 8px; border-radius: 50%; background: #18c4d4; }
    .minimized-label { font-size: 13px; font-weight: 600; color: #374151; }
    .minimized-restore-btn {
        width: 22px; height: 22px; border-radius: 6px;
        border: none; background: #f0f2f5; color: #9ca3af;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; font-size: 11px;
    }

    /* ── FULLSCREEN STATE ── */
    #sendModal.modal.is-fullscreen .modal-dialog {
        max-width: 100vw;
        width: 100vw;
        margin: 0;
        height: 100vh;
    }
    #sendModal.modal.is-fullscreen .modal-dialog .modal-content {
        border-radius: 0;
        height: 100vh;
        display: flex;
        flex-direction: column;
    }
    #sendModal.modal.is-fullscreen .send-modal-body { flex: 1; }
    #sendModal.modal.is-fullscreen .send-message-editor { min-height: 350px; }
    #sendModal.modal.is-fullscreen .send-modal-footer { border-radius: 0; }

    /* ── RESPONSIVE ── */
    @media (max-width: 767px) {
        #sendModal .modal-dialog { max-width: calc(100% - 20px); margin: 10px auto; }
        #sendModal .recipient-top { flex-direction: column; align-items: flex-start; }
        #sendModal .inline-customer-search-wrap { max-width: 100%; min-width: 0; width: 100%; }
        #sendModal .send-modal-header,
        #sendModal .send-subject-row { padding-left: 14px; padding-right: 14px; }
        #sendModal .send-message-editor { padding-left: 14px; padding-right: 14px; }
        #sendModal .editor-toolbar { left: 14px; }
    }
</style>
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
                            <span class="tab-badge" data-tab-badge="order_notification">{{ $orderCount ?? 0 }}</span>
                        </a>
                        <a href="{{ route('admin.notifications.index', array_merge(request()->except('page', 'tab'), ['tab' => 'user_contact'])) }}"
                           class="tab-link {{ ($tab ?? '') === 'user_contact' ? 'active' : '' }}">
                            User Contact
                            <span class="tab-badge" data-tab-badge="user_contact">{{ $userContactCount ?? 0 }}</span>
                        </a>
                        <a href="{{ route('admin.notifications.index', array_merge(request()->except('page', 'tab'), ['tab' => 'out_of_stock'])) }}"
                           class="tab-link {{ ($tab ?? '') === 'out_of_stock' ? 'active' : '' }}">
                            Out of Stock Item
                            <span class="tab-badge" data-tab-badge="out_of_stock">{{ $outOfStockCount ?? 0 }}</span>
                        </a>
                        <a href="{{ route('admin.notifications.index', array_merge(request()->except('page', 'tab'), ['tab' => 'global_message'])) }}"
                           class="tab-link {{ ($tab ?? '') === 'global_message' ? 'active' : '' }}">
                            Global Message
                            <span class="tab-badge" data-tab-badge="global_message">{{ $globalMessageCount ?? 0 }}</span>
                        </a>
                    </div>

                    <div class="right-actions">
                        <a href="{{ route('admin.chat.index') }}" class="btn-send-message" style="text-decoration:none;">
                            <i class="bi bi-chat"></i>
                            <span>Open Chat</span>
                        </a>
                        <button type="button" class="btn-send-message" data-bs-toggle="modal" data-bs-target="#sendModal">
                            <i class="bi bi-chat-dots"></i>
                            <span>send message</span>
                        </button>
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
                <div class="utility-actions">
                    <form action="{{ route('admin.notifications.read.all') }}" method="POST" id="markAllReadForm">
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
                            $messagePreview = trim(strip_tags($notification->message ?? ''));
                        @endphp
                        <a class="notification-item-link"
                             data-id="{{ $notification->id }}"
                             data-href="{{ route('admin.notifications.show', $notification->id) }}"
                             href="{{ route('admin.notifications.show', $notification->id) }}">
                            <div class="notification-item {{ !$notification->is_read ? 'selected-row' : '' }}">
                                <div class="notification-main-left">
                                    <input type="checkbox"
                                           class="notification-checkbox"
                                           name="notification_ids[]"
                                           value="{{ $notification->id }}"
                                           onclick="event.stopPropagation();">
                                    <div class="avatar-box">
                                        <img src="{{ $avatarSrc }}" alt="avatar"
                                             onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}'">
                                        <span class="online-dot"></span>
                                    </div>
                                    <div class="notification-content">
                                        <div class="notification-name-row">
                                            <div class="notification-name">{{ $displayName }}</div>
                                            @if(optional($contactUser)->id)
                                                <button type="button" class="btn btn-sm btn-outline-info"
                                                    style="padding:2px 8px;font-size:11px;"
                                                    onclick="event.preventDefault();event.stopPropagation();window.location.href='{{ route('admin.chat.index', ['user_id' => $contactUser->id]) }}';">
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
                                    @if((int)($notification->unread_count ?? 0) > 0 && !$notification->is_read)
                                        <div class="notification-counter">{{ (int)$notification->unread_count }}</div>
                                    @endif
                                    <div class="notification-time">{{ optional($notification->updated_at)->format('H:i') }}</div>
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

<!-- ============================================================
     MINIMIZED TAB (restored when modal is minimized)
     ============================================================ -->
<div class="send-minimized-tab" id="sendMinimizedTab" onclick="restoreSendModal()">
    <div class="minimized-dot"></div>
    <span class="minimized-label">New Message</span>
    <button class="minimized-restore-btn" title="Restore">
        <i class="bi bi-pip"></i>
    </button>
</div>

<!-- ============================================================
     SEND MESSAGE MODAL
     ============================================================ -->
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

                            <!-- Top row: type select + inline search side by side -->
                            <div class="recipient-top">

                                <!-- Only: All Customers / Select Customers -->
                                <select name="send_type" id="send_type"
                                        class="recipient-type-select"
                                        onchange="toggleRecipientMode()">
                                    <option value="all"      {{ old('send_type','all') === 'all'      ? 'selected' : '' }}>All Customers</option>
                                    <option value="multiple" {{ old('send_type')       === 'multiple' ? 'selected' : '' }}>Select Customers</option>
                                </select>

                                <!-- Inline search input — appears right next to the select -->
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

                            <!-- Chips: selected customers appear below the search row -->
                            <div class="chip-box" id="selectedChipsBox"></div>

                            <!-- Hidden inputs for selected user IDs -->
                            <div id="selectedUserIdsContainer"></div>

                        </div>
                    </div>

                    <!-- Header controls: fullscreen / close -->
                    <div class="send-header-actions">
                        <button type="button" class="header-icon-btn" id="fullscreenModalBtn" title="Full screen">
                            <i class="bi bi-arrows-angle-expand"></i>
                        </button>
                        <button type="button" class="header-icon-btn close-btn" title="Close" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- ── SUBJECT ROW ── -->
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

                <!-- ── EDITOR BODY ── -->
                <div class="send-modal-body">
                    <div class="send-editor-wrap">
                        <div class="editor-mode-badge">
                            <i class="bi bi-pencil-square"></i>
                            <span>Message</span>
                        </div>

                        <!-- Rich-text editor -->
                        <div id="message_editor"
                             class="send-message-editor"
                             contenteditable="true"
                             data-placeholder="Write your message...">{!! old('message') !!}</div>

                        <!-- Hidden textarea synced for form submit -->
                        <textarea name="message" id="message" class="send-message-textarea d-none">{{ old('message') }}</textarea>

                        <!-- Floating toolbar -->
                        <div class="editor-toolbar">
                            <button type="button" class="toolbar-type-pill">
                                Text <i class="bi bi-chevron-down" style="font-size:10px;"></i>
                            </button>
                            <span class="toolbar-divider"></span>

                            <!-- Bold -->
                            <button type="button" class="toolbar-btn" id="btnBold" title="Bold" onclick="execFmt('bold','btnBold')">
                                <i class="bi bi-type-bold"></i>
                            </button>
                            <!-- Italic -->
                            <button type="button" class="toolbar-btn" id="btnItalic" title="Italic" onclick="execFmt('italic','btnItalic')">
                                <i class="bi bi-type-italic"></i>
                            </button>
                            <!-- Underline -->
                            <button type="button" class="toolbar-btn" id="btnUnderline" title="Underline" onclick="execFmt('underline','btnUnderline')">
                                <i class="bi bi-type-underline"></i>
                            </button>
                            <!-- Strikethrough -->
                            <button type="button" class="toolbar-btn" id="btnStrike" title="Strikethrough" onclick="execFmt('strikeThrough','btnStrike')">
                                <i class="bi bi-type-strikethrough"></i>
                            </button>

                            <span class="toolbar-divider"></span>

                            <!-- Link -->
                            <button type="button" class="toolbar-btn" id="btnLink" title="Insert link" onclick="openLinkOverlay()">
                                <i class="bi bi-link-45deg"></i>
                            </button>
                        </div>

                        <!-- Link insert overlay -->
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
                </div><!-- /.send-modal-body -->

                <!-- ── FOOTER ── -->
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
                            {{-- <i class="bi bi-pencil-square"></i> --}}
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
let currentTab               = @json($tab ?? 'order_notification');

let selectedMultiUsers       = [];
let searchTimer              = null;
let savedRange               = null;
let latestNotificationId     = {{ (int)($notifications->max('id') ?? 0) }};

/* ── Bootstrap modal instance ── */
let bsModal = null;
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('sendModal');
    bsModal = new bootstrap.Modal(modalEl);
});

/* ────────────────────────────────────────────────
   FULLSCREEN / CLOSE
   ──────────────────────────────────────────────── */

function restoreSendModal() {
    const modalEl = document.getElementById('sendModal');
    modalEl.classList.remove('is-minimized');
    document.getElementById('sendMinimizedTab').classList.remove('visible');
    // Re-show via Bootstrap
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
    ed.focus();

    const sel = window.getSelection();
    if (savedRange) {
        sel.removeAllRanges();
        sel.addRange(savedRange);
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

// Clean up state when modal is fully hidden
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

/* ── Render dropdown ── */
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

/* ── Add / remove customer chips ── */
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
        // Chip
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

        // Hidden input
        const inp  = document.createElement('input');
        inp.type   = 'hidden';
        inp.name   = 'user_ids[]';
        inp.value  = user.id;
        cont.appendChild(inp);
    });
}

/* ── Search input event ── */
document.getElementById('customerSearchInput').addEventListener('input', function () {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => renderCustomerDropdown(this.value), 280);
});
document.getElementById('customerSearchInput').addEventListener('focus', function () {
    renderCustomerDropdown(this.value);
});

/* ── Close dropdown on outside click ── */
document.addEventListener('click', function (e) {
    const wrap = document.getElementById('inlineSearchWrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('customerDropdown').classList.remove('open');
    }
});

/* ────────────────────────────────────────────────
   SUBJECT COUNTER
   ──────────────────────────────────────────────── */
const subjectInput = document.getElementById('subjectInput');
subjectInput.addEventListener('input', function () {
    document.getElementById('subjectCounter').textContent = this.value.length;
});
document.getElementById('subjectCounter').textContent = subjectInput.value.length;

/* ────────────────────────────────────────────────
   TEXT FORMATTING
   ──────────────────────────────────────────────── */
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

/* ────────────────────────────────────────────────
   EDITOR ↔ TEXTAREA SYNC
   ──────────────────────────────────────────────── */
function syncEditor() {
    const ta = document.getElementById('message');
    if (ta) ta.value = document.getElementById('message_editor').innerHTML.trim();
}
editor.addEventListener('input', syncEditor);
editor.addEventListener('keyup', syncEditor);
editor.addEventListener('paste', () => setTimeout(syncEditor, 50));

/* ────────────────────────────────────────────────
   LINK INSERTION
   ──────────────────────────────────────────────── */
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
    ed.focus();

    if (savedRange) {
        const sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(savedRange);
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

/* ────────────────────────────────────────────────
   CLEAR COMPOSER
   ──────────────────────────────────────────────── */
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

function bindDynamicNotificationUi() {
    bindCheckboxListeners();
    bindRowInteractions();
    updateSelectedCount();
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
                    <div class="avatar-box">
                        <img src="${item.avatar}" alt="avatar" onerror="this.onerror=null;this.src='${defaultAvatar}'">
                        <span class="online-dot"></span>
                    </div>
                    <div class="notification-content">
                        <div class="notification-name-row">
                            <div class="notification-name">${escHtmlStr(item.user_name||'Unknown')}</div>
                        </div>
                        <div class="notification-message">${escHtmlStr(item.message||'')}</div>
                    </div>
                </div>
                <div class="notification-right">
                    ${(Number(item.unread_count||0)>0 && !item.is_read) ? `<div class="notification-counter">${Number(item.unread_count)}</div>` : ''}
                    <div class="notification-time">${escHtmlStr(item.time||'')}</div>
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

    if (e.target?.id === 'date' || e.target?.id === 'per_page') {
        const form = e.target.closest('form');
        if (form) {
            e.preventDefault();
            const url = `${form.action}?${new URLSearchParams(new FormData(form)).toString()}`;
            loadNotificationPage(url).catch(err => showAlert(err.message, 'error'));
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
        if (!getCheckboxes().some(c => c.checked)) {
            showAlert('Please select at least one notification.', 'error');
            return;
        }
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
        return;
    }

    if (form.id === 'sendNotificationForm') {
        e.preventDefault();
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
            .catch(err => showAlert(err.message, 'error'));
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
