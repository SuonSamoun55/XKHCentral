@extends('ManagementSystemViews.AdminViews.Layouts.app')

@section('title', 'Admin Notifications')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
        /* Alert Container Styles */
        .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }

        .custom-alert {
            background: #ffffff !important;
            color: #334155 !important;
            border: none !important;
            border-radius: 12px !important;
            padding: 16px 24px !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 280px;
            max-width: 400px;
            width: fit-content;
            animation: slideIn 0.3s ease-out forwards;
            pointer-events: auto;
        }

        .custom-alert.alert-success {
            border-left: 4px solid #10b981 !important;
        }

        .custom-alert.alert-danger {
            border-left: 4px solid #ef4444 !important;
        }

        .custom-alert.fade-out {
            animation: fadeOut 0.3s ease-out forwards;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(100%); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes fadeOut {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(100%); }
        }

        /* Page Layout Styles */
        .page-wrap {
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .notification-wrapper {
            display: flex;
            flex-direction: column;
            height: 100%;
            background: #efefef;
        }

        /* Sticky Header Section */
        .notification-page-header {
            background: white;
            padding: 20px 30px;
            border-bottom: 1px solid #eee;
            flex-shrink: 0;
        }

        .notification-page-header .page-title {
            font-size: 28px;
            font-weight: 600;
            color: #19bcc5;
            margin: 0;
        }

        /* Filter Form - Sticky */
        .filter-form {
            background: white;
            padding: 15px 30px 20px 30px;
            border-bottom: 1px solid #eee;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        /* Search Box Improvements */
        .search-box-noti {
            position: relative;
            width: 100%;
            max-width: 400px;
        }

        .search-box-noti i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 14px;
            pointer-events: none;
        }

        .search-box-noti input {
            width: 100%;
            height: auto;
            border: 2px solid #e0e0e0;
            background: #fafafa;
            border-radius: 8px;
            outline: none;
            padding: 10px 10px 10px 40px;
            font-size: 14px;
            color: #333;
            transition: all 0.3s;
        }

        .search-box-noti input:focus {
            border-color: #0066cc;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
        }

        /* Date Filter Improvements */
        .top-right-tools {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .date-filter-box {
            width: 160px;
            position: relative;
        }

        .date-filter-box label {
            display: block;
            font-size: 12px;
            color: #666;
            margin-bottom: 4px;
            line-height: 1;
            font-weight: 500;
        }

        .date-filter-box input {
            width: 100%;
            height: auto;
            border: 2px solid #0ec3d7;
            background: #f8fefe;
            border-radius: 6px;
            outline: none;
            padding: 8px 12px;
            font-size: 13px;
            color: #334155;
            transition: all 0.3s;
        }

        .date-filter-box input:focus {
            border-color: #0099aa;
            box-shadow: 0 0 0 3px rgba(14, 195, 215, 0.1);
        }

        /* Tab Row Styling */
        .tab-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 0;
            flex-wrap: wrap;
        }

        .tabs {
            display: flex;
            align-items: center;
            gap: 25px;
            flex-wrap: wrap;
        }

        .tab-link {
            color: #666;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            position: relative;
            padding-bottom: 10px;
            border-bottom: 3px solid transparent;
            font-weight: 500;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .tab-link:hover {
            color: #333;
        }

        .tab-link.active {
            color: #000;
            font-weight: 600;
            border-bottom-color: #000;
        }

        .tab-badge {
            min-width: 18px;
            height: 20px;
            border-radius: 999px;
            padding: 0 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            background: #0066cc;
            color: #fff;
            line-height: 1;
            font-weight: 600;
        }

        /* Right Actions */
        .right-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-send-message {
            height: auto;
            border: none;
            border-radius: 8px;
            background: #19bcc5;
            color: #fff;
            padding: 8px 16px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            cursor: pointer;
            box-shadow: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-send-message:hover {
            background: #1498a3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(25, 188, 197, 0.2);
        }

        .btn-send-message i {
            font-size: 14px;
        }

        /* Utility Bar */
        .utility-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            gap: 12px;
            flex-wrap: wrap;
            padding: 0 15px;
        }

        .selected-box {
            min-width: 100px;
            height: auto;
            padding: 6px 12px;
            background: #e4e8ef;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #67707b;
            font-weight: 500;
        }

        .selected-box span {
            color: #1f2937;
            font-weight: 700;
        }

        .utility-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .utility-btn {
            height: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 13px;
            background: #f0f0f0;
            color: #374151;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
        }

        .utility-btn:hover {
            background: #e0e0e0;
            border-color: #999;
        }

        .utility-btn.delete-btn {
            background: #fff5f5;
            color: #b42318;
            border-color: #fca5a5;
        }

        .utility-btn.delete-btn:hover {
            background: #fee2e2;
            border-color: #f87171;
        }

        /* Scrollable Notification List */
        .notification-list {
            width: 100%;
            display: flex;
            flex-direction: column;
            background: transparent;
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }
    </style>
<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    background: #ececec;
    font-family: Arial, Helvetica, sans-serif;
    color: #1f2937;
}
.notification-wrapper {
    min-height: calc(100vh - 44px);
    background: #efefef;
    padding: 10px 4px;
    border-radius: 4px;
}

.notification-page-header {

position: sticky;
top: 0;

z-index: 20;
border-radius: 20px 20px 0 0;
}

.page-title {
    margin: 0;
    font-size: 22px;
    font-weight: 700;
    color: #2aaab5;
    letter-spacing: -.2px;
}

.filter-form {
    margin-bottom: 10px;
    position: sticky;
    top: 60px;
    z-index: 10;
}

.top-filter-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 18px;
}

.search-box-noti {
    position: relative;
    width: 215px;
}

.search-box-noti i {
    position: absolute;
    left: 10px;
    top: 9px;
    color: #a5adb8;
    font-size: 12px;
}

.search-box-noti input {
    width: 100%;
    height: 24px;
    border: 1px solid #dadde2;
    background: #f3f3f3;
    border-radius: 5px;
    outline: none;
    padding: 0 10px 0 26px;
    font-size: 11px;
    color: #5b6470;
}

.top-right-tools {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.date-filter-box {
    width: 160px;
    position: relative;
}

.date-filter-box label {
    display: block;
    font-size: 8px;
    color: #8a8f98;
    margin-bottom: 2px;
    line-height: 1;
}

.date-filter-box input {
    width: 100%;
    height: 24px;
    border: 1.5px solid #0ec3d7;
    background: #f8fefe;
    border-radius: 2px;
    outline: none;
    padding: 0 8px;
    font-size: 9px;
    color: #334155;
}

.tab-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px;
}

.tabs {
    display: flex;
    align-items: center;
    gap: 24px;
    flex-wrap: wrap;
}

.tab-link {
    color: #4b5563;
    font-size: 13px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    position: relative;
    padding-bottom: 8px;
}

.tab-link.active {
    color: #2c3138;
    font-weight: 500;
}

.tab-link.active::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    height: 1.5px;
    background: #67707b;
}

.tab-badge {
    min-width: 12px;
    height: 12px;
    border-radius: 999px;
    padding: 0 4px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 8px;
    background: #4cd5df;
    color: #fff;
    line-height: 1;
}

.right-actions {
    display: flex;
    align-items: center;
    gap: 14px;
}

.btn-send-message {
    height: 24px;
    border: none;
    border-radius: 12px;
    background: #11c8d7;
    color: #fff;
    padding: 0 10px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 10px;
    cursor: pointer;
    box-shadow: none;
}

.btn-send-message i {
    font-size: 11px;
}

.utility-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
    gap: 12px;
    flex-wrap: wrap;
}

.selected-box {
    min-width: 90px;
    height: 24px;
    padding: 0 10px;
    background: #e4e8ef;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 10px;
    color: #67707b;
}

.selected-box span {
    color: #1f2937;
    font-weight: 700;
}

.utility-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.utility-btn {
    height: 24px;
    border: none;
    border-radius: 12px;
    padding: 0 10px;
    font-size: 10px;
    background: #dce2e8;
    color: #374151;
    cursor: pointer;
}

.utility-btn.delete-btn {
    background: #f2dcdc;
    color: #b42318;
}

.notification-list {
    width: 100%;
    display: flex;
    flex-direction: column;
    background: transparent;
}

.notification-item-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.notification-item {
    min-height: 48px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    padding: 8px 6px 8px 2px;
    border-bottom: 1px solid #dadde2;
    background: transparent;
    transition: .2s;
    cursor: pointer;
}

.notification-item:hover {
    background: #e8edf7;
}

.notification-item.selected-row {
    background: #dfe5f2;
    border-left: 3px solid #4750ff;
    padding-left: 0;
}

.notification-main-left {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
    flex: 1;
}

.notification-checkbox {
    width: 14px;
    height: 14px;
    cursor: pointer;
    margin-left: 4px;
}

.avatar-box {
    position: relative;
    width: 34px;
    height: 34px;
    min-width: 34px;
    border-radius: 50%;
    overflow: hidden;
    background: #d9e6f2;
}

.avatar-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.online-dot {
    position: absolute;
    width: 8px;
    height: 8px;
    background: #22c55e;
    border: 2px solid #fff;
    border-radius: 50%;
    left: 0;
    bottom: 2px;
}

.notification-content {
    min-width: 0;
    flex: 1;
}

.notification-name-row {
    display: flex;
    align-items: center;
    gap: 8px;
}

.notification-name {
    font-size: 11px;
    font-weight: 700;
    color: #1f2937;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.notification-message {
    margin-top: 2px;
    font-size: 9px;
    color: #6b7280;
    line-height: 1.25;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
}

.notification-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 6px;
    min-width: 90px;
    padding-right: 6px;
}

.notification-counter {
    min-width: 22px;
    height: 22px;
    border-radius: 9999px;
    background: #ef4444;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
    padding: 0 6px;
}

.notification-time {
    font-size: 9px;
    color: #9aa3b2;
    white-space: nowrap;
}

.empty-box {
    text-align: center;
    color: #7b8794;
    padding: 30px 0;
    font-size: 13px;
}

.pagination-wrap {
    margin-top: 14px;
}

.alert {
    border-radius: 8px;
    font-size: 12px;
    margin-bottom: 12px;
}

.custom-send-modal .modal-dialog {
    max-width: 760px;
}

.send-modal-content {
    border: none;
    border-radius: 14px;
    overflow: hidden;
    background: #ffffff;
    box-shadow: 0 20px 50px rgba(15, 23, 42, 0.18);
}

.send-modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    padding: 18px 20px 16px;
    border-bottom: 1px solid #eceff3;
}

.send-to-row {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    width: 100%;
}

.send-label {
    font-size: 13px;
    color: #6b7280;
    line-height: 38px;
    min-width: 18px;
}

.send-recipient-area {
    flex: 1;
    min-width: 0;
}

.recipient-top {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 10px;
}

.recipient-type-select {
    height: 38px;
    min-width: 170px;
    border: 1px solid #dbe2ea;
    border-radius: 10px;
    padding: 0 12px;
    font-size: 13px;
    color: #334155;
    background: #fff;
    outline: none;
}

.selected-user-preview {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    min-height: 38px;
    padding: 6px 14px;
    background: #f3f5f8;
    border: 1px solid #e5e7eb;
    border-radius: 999px;
}

.user-avatar-mini {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #dcdfe5;
    color: #111827;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    font-weight: 700;
}

.user-meta-mini {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
}

.user-name-mini {
    font-size: 13px;
    font-weight: 700;
    color: #111827;
}

.user-email-mini {
    font-size: 11px;
    color: #6b7280;
}

.recipient-select-wrap {
    position: relative;
}

.searchable-select-wrap {
    position: relative;
}

.customer-search-input {
    width: 100%;
    height: 44px;
    border: 1px solid #dbe2ea;
    border-radius: 14px;
    padding: 0 16px;
    font-size: 14px;
    color: #334155;
    background: #fff;
    outline: none;
}

.customer-search-input:focus {
    border-color: #13c6d6;
    box-shadow: 0 0 0 3px rgba(19, 198, 214, 0.12);
}

.customer-search-dropdown {
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid #dbe2ea;
    border-radius: 14px;
    box-shadow: 0 14px 32px rgba(15, 23, 42, 0.12);
    max-height: 220px;
    overflow-y: auto;
    z-index: 50;
    display: none;
    padding: 6px;
}

.customer-search-dropdown.show {
    display: block;
}

.customer-dropdown-item {
    width: 100%;
    border: none;
    background: transparent;
    display: flex;
    align-items: center;
    gap: 12px;
    text-align: left;
    padding: 10px 12px;
    border-radius: 12px;
    cursor: pointer;
}

.customer-dropdown-item:hover {
    background: #f3f8fb;
}

.customer-dropdown-avatar-wrap,
.selected-customer-avatar-wrap {
    width: 34px;
    height: 34px;
    min-width: 34px;
    border-radius: 50%;
    overflow: hidden;
    background: #e6e9ef;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.customer-dropdown-photo,
.selected-customer-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.customer-dropdown-meta {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.customer-dropdown-name {
    font-size: 13px;
    font-weight: 700;
    color: #111827;
    line-height: 1.2;
}

.customer-dropdown-email {
    font-size: 11px;
    color: #6b7280;
    line-height: 1.2;
    margin-top: 3px;
}

.customer-dropdown-empty {
    padding: 12px 14px;
    font-size: 12px;
    color: #94a3b8;
}

.multi-chip-box {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 12px;
    min-height: 22px;
}

.chip-placeholder {
    font-size: 12px;
    color: #94a3b8;
}

.selected-customer-chip {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 6px 10px 6px 6px;
    background: #f3f5f8;
    border: 1px solid #e5e7eb;
    border-radius: 999px;
}

.selected-customer-text {
    display: flex;
    flex-direction: column;
    line-height: 1.1;
}

.selected-customer-name {
    font-size: 12px;
    font-weight: 700;
    color: #111827;
}

.selected-customer-email {
    font-size: 10px;
    color: #6b7280;
}

.selected-customer-remove {
    border: none;
    background: transparent;
    color: #6b7280;
    font-size: 18px;
    line-height: 1;
    cursor: pointer;
    padding: 0 0 0 2px;
}

.send-close-btn {
    margin-top: 2px;
}

.send-modal-body {
    padding: 0;
}

.send-subject-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0 20px;
    min-height: 52px;
    border-bottom: 1px solid #eceff3;
}

.send-subject-input {
    flex: 1;
    border: none;
    outline: none;
    font-size: 18px;
    color: #111827;
    background: transparent;
    padding: 12px 0;
}

.send-subject-input::placeholder {
    color: #9ca3af;
}

.subject-counter {
    font-size: 12px;
    color: #b0b7c3;
    min-width: 24px;
    text-align: right;
}

.send-editor-wrap {
    position: relative;
    padding: 0;
}

.send-message-editor {
    width: 100%;
    min-height: 360px;
    border: none;
    outline: none;
    padding: 20px 20px 66px;
    font-size: 14px;
    line-height: 1.7;
    color: #1f2937;
    background: #fff;
    white-space: normal;
    overflow-wrap: break-word;
}

.send-message-editor:empty:before {
    content: attr(data-placeholder);
    color: #9ca3af;
}

.send-message-editor ul,
.send-message-editor ol {
    padding-left: 22px;
    margin-bottom: 0;
}

.send-message-editor b,
.send-message-editor strong {
    font-weight: 700;
}

.send-message-editor i,
.send-message-editor em {
    font-style: italic;
}

.send-message-textarea {
    width: 100%;
    min-height: 280px;
    border: none;
    outline: none;
    resize: vertical;
    padding: 18px 20px 56px;
    font-size: 14px;
    line-height: 1.7;
    color: #1f2937;
    background: #fff;
}

.send-message-preview-wrap {
    margin: 8px 14px 0;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #f8fafc;
    padding: 10px 12px;
}

.send-message-preview-label {
    font-size: 11px;
    color: #64748b;
    margin-bottom: 4px;
    font-weight: 600;
}

.send-message-preview-text {
    font-size: 13px;
    color: #1e293b;
    white-space: pre-wrap;
    word-break: break-word;
    min-height: 20px;
}

.editor-toolbar {
    position: absolute;
    left: 20px;
    bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.toolbar-btn {
    width: 42px;
    height: 42px;
    border: 1px solid #d7dee8;
    border-radius: 12px;
    background: #fff;
    color: #475569;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 22px;
    font-weight: 700;
}

.toolbar-btn:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}

.send-modal-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 14px 18px;
    border-top: 1px solid #eceff3;
    background: #f7f8fa;
}

.footer-left-tools,
.footer-right-tools {
    display: flex;
    align-items: center;
    gap: 8px;
}

.footer-icon-btn {
    width: 38px;
    height: 38px;
    border: none;
    background: transparent;
    color: #94a3b8;
    border-radius: 10px;
    cursor: pointer;
}

.footer-icon-btn:hover {
    background: #eef2f7;
    color: #64748b;
}

.send-now-btn {
    height: 52px;
    border: none;
    border-radius: 999px;
    background: #18c4d4;
    color: #fff;
    padding: 0 24px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 8px 18px rgba(24, 196, 212, 0.25);
}

.send-now-btn:hover {
    background: #11b7c7;
}

.live-alert-toast {
    position: fixed;
    top: 18px;
    right: 18px;
    background: #11c8d7;
    color: #fff;
    padding: 12px 16px;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0,0,0,.15);
    z-index: 9999;
    opacity: 0;
    transform: translateY(-10px);
    transition: all .25s ease;
    font-size: 13px;
    font-weight: 600;
}

.live-alert-toast.show {
    opacity: 1;
    transform: translateY(0);
}

@media (max-width: 991px) {
    .top-filter-row,
    .tab-row,
    .utility-bar {
        flex-direction: column;
        align-items: flex-start;
    }

    .right-actions {
        width: 100%;
        justify-content: flex-start;
        flex-wrap: wrap;
    }

    .tabs {
        gap: 18px;
    }

    .search-box-noti,
    .date-filter-box {
        width: 100%;
    }

    .notification-item {
        align-items: flex-start;
        flex-direction: column;
    }

    .notification-right {
        width: 100%;
        align-items: flex-start;
        padding-right: 0;
        min-width: auto;
    }

    .custom-send-modal .modal-dialog {
        max-width: calc(100% - 20px);
    }

    .send-modal-header,
    .send-subject-row,
    .send-message-editor {
        padding-left: 14px;
        padding-right: 14px;
    }

    .editor-toolbar {
        left: 14px;
    }

    .recipient-top {
        flex-direction: column;
        align-items: flex-start;
    }

    .selected-user-preview {
        width: 100%;
        border-radius: 14px;
    }

    .send-to-row {
        align-items: flex-start;
    }
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
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    background: #ececec;
    font-family: Arial, Helvetica, sans-serif;
    color: #1f2937;
}
.notification-wrapper {
    min-height: calc(100vh - 44px);
    background: #efefef;
    padding: 10px 4px;
    border-radius: 4px;
}

.notification-page-header {

position: sticky;
top: 0;

z-index: 20;
border-radius: 20px 20px 0 0;
}

.page-title {
    margin: 0;
    font-size: 22px;
    font-weight: 700;
    color: #2aaab5;
    letter-spacing: -.2px;
}

.filter-form {
    margin-bottom: 10px;
    position: sticky;
    top: 60px;
    z-index: 10;
}

.top-filter-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 18px;
}

.search-box-noti {
    position: relative;
    width: 215px;
}

.search-box-noti i {
    position: absolute;
    left: 10px;
    top: 9px;
    color: #a5adb8;
    font-size: 12px;
}

.search-box-noti input {
    width: 100%;
    height: 24px;
    border: 1px solid #dadde2;
    background: #f3f3f3;
    border-radius: 5px;
    outline: none;
    padding: 0 10px 0 26px;
    font-size: 11px;
    color: #5b6470;
}

.top-right-tools {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.date-filter-box {
    width: 160px;
    position: relative;
}

.date-filter-box label {
    display: block;
    font-size: 8px;
    color: #8a8f98;
    margin-bottom: 2px;
    line-height: 1;
}

.date-filter-box input {
    width: 100%;
    height: 24px;
    border: 1.5px solid #0ec3d7;
    background: #f8fefe;
    border-radius: 2px;
    outline: none;
    padding: 0 8px;
    font-size: 9px;
    color: #334155;
}

.tab-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px;
}

.tabs {
    display: flex;
    align-items: center;
    gap: 24px;
    flex-wrap: wrap;
}

.tab-link {
    color: #4b5563;
    font-size: 13px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    position: relative;
    padding-bottom: 8px;
}

.tab-link.active {
    color: #2c3138;
    font-weight: 500;
}

.tab-link.active::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    height: 1.5px;
    background: #67707b;
}

.tab-badge {
    min-width: 12px;
    height: 12px;
    border-radius: 999px;
    padding: 0 4px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 8px;
    background: #4cd5df;
    color: #fff;
    line-height: 1;
}

.right-actions {
    display: flex;
    align-items: center;
    gap: 14px;
}

.btn-send-message {
    height: 24px;
    border: none;
    border-radius: 12px;
    background: #11c8d7;
    color: #fff;
    padding: 0 10px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 10px;
    cursor: pointer;
    box-shadow: none;
}

.btn-send-message i {
    font-size: 11px;
}

.utility-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
    gap: 12px;
    flex-wrap: wrap;
}

.selected-box {
    min-width: 90px;
    height: 24px;
    padding: 0 10px;
    background: #e4e8ef;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 10px;
    color: #67707b;
}

.selected-box span {
    color: #1f2937;
    font-weight: 700;
}

.utility-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.utility-btn {
    height: 24px;
    border: none;
    border-radius: 12px;
    padding: 0 10px;
    font-size: 10px;
    background: #dce2e8;
    color: #374151;
    cursor: pointer;
}

.utility-btn.delete-btn {
    background: #f2dcdc;
    color: #b42318;
}

.notification-list {
    width: 100%;
    display: flex;
    flex-direction: column;
    background: transparent;
}

.notification-item-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.notification-item {
    min-height: 48px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    padding: 8px 6px 8px 2px;
    border-bottom: 1px solid #dadde2;
    background: transparent;
    transition: .2s;
    cursor: pointer;
}

.notification-item:hover {
    background: #e8edf7;
}

.notification-item.selected-row {
    background: #dfe5f2;
    border-left: 3px solid #4750ff;
    padding-left: 0;
}

.notification-main-left {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
    flex: 1;
}

.notification-checkbox {
    width: 14px;
    height: 14px;
    cursor: pointer;
    margin-left: 4px;
}

.avatar-box {
    position: relative;
    width: 34px;
    height: 34px;
    min-width: 34px;
    border-radius: 50%;
    overflow: hidden;
    background: #d9e6f2;
}

.avatar-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.online-dot {
    position: absolute;
    width: 8px;
    height: 8px;
    background: #22c55e;
    border: 2px solid #fff;
    border-radius: 50%;
    left: 0;
    bottom: 2px;
}

.notification-content {
    min-width: 0;
    flex: 1;
}

.notification-name-row {
    display: flex;
    align-items: center;
    gap: 8px;
}

.notification-name {
    font-size: 11px;
    font-weight: 700;
    color: #1f2937;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.notification-message {
    margin-top: 2px;
    font-size: 9px;
    color: #6b7280;
    line-height: 1.25;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
}

.notification-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 6px;
    min-width: 90px;
    padding-right: 6px;
}

.notification-counter {
    min-width: 22px;
    height: 22px;
    border-radius: 9999px;
    background: #ef4444;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
    padding: 0 6px;
}

.notification-time {
    font-size: 9px;
    color: #9aa3b2;
    white-space: nowrap;
}

.empty-box {
    text-align: center;
    color: #7b8794;
    padding: 30px 0;
    font-size: 13px;
}

.pagination-wrap {
    margin-top: 14px;
}

.alert {
    border-radius: 8px;
    font-size: 12px;
    margin-bottom: 12px;
}

.custom-send-modal .modal-dialog {
    max-width: 760px;
}

.send-modal-content {
    border: none;
    border-radius: 14px;
    overflow: hidden;
    background: #ffffff;
    box-shadow: 0 20px 50px rgba(15, 23, 42, 0.18);
}

.send-modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    padding: 18px 20px 16px;
    border-bottom: 1px solid #eceff3;
}

.send-to-row {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    width: 100%;
}

.send-label {
    font-size: 13px;
    color: #6b7280;
    line-height: 38px;
    min-width: 18px;
}

.send-recipient-area {
    flex: 1;
    min-width: 0;
}

.recipient-top {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 10px;
}

.recipient-type-select {
    height: 38px;
    min-width: 170px;
    border: 1px solid #dbe2ea;
    border-radius: 10px;
    padding: 0 12px;
    font-size: 13px;
    color: #334155;
    background: #fff;
    outline: none;
}

.selected-user-preview {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    min-height: 38px;
    padding: 6px 14px;
    background: #f3f5f8;
    border: 1px solid #e5e7eb;
    border-radius: 999px;
}

.user-avatar-mini {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #dcdfe5;
    color: #111827;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    font-weight: 700;
}

.user-meta-mini {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
}

.user-name-mini {
    font-size: 13px;
    font-weight: 700;
    color: #111827;
}

.user-email-mini {
    font-size: 11px;
    color: #6b7280;
}

.recipient-select-wrap {
    position: relative;
}

.searchable-select-wrap {
    position: relative;
}

.customer-search-input {
    width: 100%;
    height: 44px;
    border: 1px solid #dbe2ea;
    border-radius: 14px;
    padding: 0 16px;
    font-size: 14px;
    color: #334155;
    background: #fff;
    outline: none;
}

.customer-search-input:focus {
    border-color: #13c6d6;
    box-shadow: 0 0 0 3px rgba(19, 198, 214, 0.12);
}

.customer-search-dropdown {
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid #dbe2ea;
    border-radius: 14px;
    box-shadow: 0 14px 32px rgba(15, 23, 42, 0.12);
    max-height: 220px;
    overflow-y: auto;
    z-index: 50;
    display: none;
    padding: 6px;
}

.customer-search-dropdown.show {
    display: block;
}

.customer-dropdown-item {
    width: 100%;
    border: none;
    background: transparent;
    display: flex;
    align-items: center;
    gap: 12px;
    text-align: left;
    padding: 10px 12px;
    border-radius: 12px;
    cursor: pointer;
}

.customer-dropdown-item:hover {
    background: #f3f8fb;
}

.customer-dropdown-avatar-wrap,
.selected-customer-avatar-wrap {
    width: 34px;
    height: 34px;
    min-width: 34px;
    border-radius: 50%;
    overflow: hidden;
    background: #e6e9ef;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.customer-dropdown-photo,
.selected-customer-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.customer-dropdown-meta {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.customer-dropdown-name {
    font-size: 13px;
    font-weight: 700;
    color: #111827;
    line-height: 1.2;
}

.customer-dropdown-email {
    font-size: 11px;
    color: #6b7280;
    line-height: 1.2;
    margin-top: 3px;
}

.customer-dropdown-empty {
    padding: 12px 14px;
    font-size: 12px;
    color: #94a3b8;
}

.multi-chip-box {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 12px;
    min-height: 22px;
}

.chip-placeholder {
    font-size: 12px;
    color: #94a3b8;
}

.selected-customer-chip {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 6px 10px 6px 6px;
    background: #f3f5f8;
    border: 1px solid #e5e7eb;
    border-radius: 999px;
}

.selected-customer-text {
    display: flex;
    flex-direction: column;
    line-height: 1.1;
}

.selected-customer-name {
    font-size: 12px;
    font-weight: 700;
    color: #111827;
}

.selected-customer-email {
    font-size: 10px;
    color: #6b7280;
}

.selected-customer-remove {
    border: none;
    background: transparent;
    color: #6b7280;
    font-size: 18px;
    line-height: 1;
    cursor: pointer;
    padding: 0 0 0 2px;
}

.send-close-btn {
    margin-top: 2px;
}

.send-modal-body {
    padding: 0;
}

.send-subject-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0 20px;
    min-height: 52px;
    border-bottom: 1px solid #eceff3;
}

.send-subject-input {
    flex: 1;
    border: none;
    outline: none;
    font-size: 18px;
    color: #111827;
    background: transparent;
    padding: 12px 0;
}

.send-subject-input::placeholder {
    color: #9ca3af;
}

.subject-counter {
    font-size: 12px;
    color: #b0b7c3;
    min-width: 24px;
    text-align: right;
}

.send-editor-wrap {
    position: relative;
    padding: 0;
}

.send-message-editor {
    width: 100%;
    min-height: 360px;
    border: none;
    outline: none;
    padding: 20px 20px 66px;
    font-size: 14px;
    line-height: 1.7;
    color: #1f2937;
    background: #fff;
    white-space: normal;
    overflow-wrap: break-word;
}

.send-message-editor:empty:before {
    content: attr(data-placeholder);
    color: #9ca3af;
}

.send-message-editor ul,
.send-message-editor ol {
    padding-left: 22px;
    margin-bottom: 0;
}

.send-message-editor b,
.send-message-editor strong {
    font-weight: 700;
}

.send-message-editor i,
.send-message-editor em {
    font-style: italic;
}

.send-message-textarea {
    width: 100%;
    min-height: 280px;
    border: none;
    outline: none;
    resize: vertical;
    padding: 18px 20px 56px;
    font-size: 14px;
    line-height: 1.7;
    color: #1f2937;
    background: #fff;
}

.send-message-preview-wrap {
    margin: 8px 14px 0;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #f8fafc;
    padding: 10px 12px;
}

.send-message-preview-label {
    font-size: 11px;
    color: #64748b;
    margin-bottom: 4px;
    font-weight: 600;
}

.send-message-preview-text {
    font-size: 13px;
    color: #1e293b;
    white-space: pre-wrap;
    word-break: break-word;
    min-height: 20px;
}

.editor-toolbar {
    position: absolute;
    left: 20px;
    bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.toolbar-btn {
    width: 42px;
    height: 42px;
    border: 1px solid #d7dee8;
    border-radius: 12px;
    background: #fff;
    color: #475569;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 22px;
    font-weight: 700;
}

.toolbar-btn:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}

.send-modal-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 14px 18px;
    border-top: 1px solid #eceff3;
    background: #f7f8fa;
}

.footer-left-tools,
.footer-right-tools {
    display: flex;
    align-items: center;
    gap: 8px;
}

.footer-icon-btn {
    width: 38px;
    height: 38px;
    border: none;
    background: transparent;
    color: #94a3b8;
    border-radius: 10px;
    cursor: pointer;
}

.footer-icon-btn:hover {
    background: #eef2f7;
    color: #64748b;
}

.send-now-btn {
    height: 52px;
    border: none;
    border-radius: 999px;
    background: #18c4d4;
    color: #fff;
    padding: 0 24px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 8px 18px rgba(24, 196, 212, 0.25);
}

.send-now-btn:hover {
    background: #11b7c7;
}

.live-alert-toast {
    position: fixed;
    top: 18px;
    right: 18px;
    background: #11c8d7;
    color: #fff;
    padding: 12px 16px;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0,0,0,.15);
    z-index: 9999;
    opacity: 0;
    transform: translateY(-10px);
    transition: all .25s ease;
    font-size: 13px;
    font-weight: 600;
}

.live-alert-toast.show {
    opacity: 1;
    transform: translateY(0);
}

@media (max-width: 991px) {
    .top-filter-row,
    .tab-row,
    .utility-bar {
        flex-direction: column;
        align-items: flex-start;
    }

    .right-actions {
        width: 100%;
        justify-content: flex-start;
        flex-wrap: wrap;
    }

    .tabs {
        gap: 18px;
    }

    .search-box-noti,
    .date-filter-box {
        width: 100%;
    }

    .notification-item {
        align-items: flex-start;
        flex-direction: column;
    }

    .notification-right {
        width: 100%;
        align-items: flex-start;
        padding-right: 0;
        min-width: auto;
    }

    .custom-send-modal .modal-dialog {
        max-width: calc(100% - 20px);
    }

    .send-modal-header,
    .send-subject-row,
    .send-message-editor {
        padding-left: 14px;
        padding-right: 14px;
    }

    .editor-toolbar {
        left: 14px;
    }

    .recipient-top {
        flex-direction: column;
        align-items: flex-start;
    }

    .selected-user-preview {
        width: 100%;
        border-radius: 14px;
    }

    .send-to-row {
        align-items: flex-start;
    }
}
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
