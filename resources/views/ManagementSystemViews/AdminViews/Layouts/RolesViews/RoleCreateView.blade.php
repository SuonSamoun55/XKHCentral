@extends('Layout.Management.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/Management/role_form.css') }}">
@endpush

@section('title', 'Create Role')

@php
    // Icon per permission key, matched by name — a fallback (list icon) covers
    // any page not in this map (new pages added later, or ad-hoc test entries).
    $pageIcons = [
        'admin_chat' => '<path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/>',
        'chat_support' => '<path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/>',
        'chat' => '<path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/>',
        'companies' => '<path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 9h1M14 9h1M9 13h1M14 13h1M9 17h1M14 17h1"/>',
        'dashboard' => '<rect x="3" y="3" width="7" height="7" rx="1.3"/><rect x="14" y="3" width="7" height="7" rx="1.3"/><rect x="3" y="14" width="7" height="7" rx="1.3"/><rect x="14" y="14" width="7" height="7" rx="1.3"/>',
        'discounts' => '<line x1="19" y1="5" x2="5" y2="19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/>',
        'notifications' => '<path d="M18 8a6 6 0 00-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>',
        'user_notifications' => '<path d="M18 8a6 6 0 00-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>',
        'orders' => '<path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>',
        'page_management' => '<path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>',
        'pos' => '<rect x="2" y="4" width="20" height="14" rx="2"/><path d="M6 20h12"/><path d="M9 12h6"/>',
        'roles' => '<path d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z"/>',
        'store_management' => '<path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>',
        'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        'cart' => '<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>',
        'checkout' => '<rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/>',
        'favorites' => '<path d="M20.8 4.6a5.5 5.5 0 00-7.8 0L12 5.6l-1-1a5.5 5.5 0 00-7.8 7.8l1 1L12 21l7.8-7.8 1-1a5.5 5.5 0 000-7.8z"/>',
        'home' => '<path d="M3 12l9-9 9 9"/><path d="M5 10v10h14V10"/><path d="M9 21v-6h6v6"/>',
        'order_history' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/>',
        'profile' => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="10" r="3"/><path d="M6 19.5a6.5 6.5 0 0112 0"/>',
        'storefront' => '<path d="M3 9l1-5h16l1 5"/><path d="M4 9v10a1 1 0 001 1h14a1 1 0 001-1V9"/><path d="M9 21v-6h6v6"/>',
        '_default' => '<path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>',
    ];
@endphp

@section('content')
<div class="role-form-page">

    <a href="{{ route('roles.index') }}" class="back-link">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M19 12H5"/><path d="M11 18l-6-6 6-6"/></svg>
        Role List
    </a>

    <div class="page-head">
        <h1>Create Role</h1>
        <p>Define what this role is called and which pages it can reach.</p>
    </div>

    @if ($errors->any())
        <div class="error-banner">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><path d="M12 8v5"/><path d="M12 16h.01"/></svg>
            <div>
                Please fix the following:
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('roles.store') }}" method="POST" id="roleForm">
        @csrf

        <div class="role-stack">

            <!-- top row: basic info + live preview, side by side -->
            <div class="top-row">
                <div class="field-card">
                    <div class="field">
                        <label for="roleName">Role Name</label>
                        <input type="text" name="name" id="roleName" placeholder="e.g. support" value="{{ old('name') }}" required>
                        <div class="field-hint">Internal key used in code — lowercase, no spaces.</div>
                    </div>
                    <div class="field">
                        <label for="displayName">Display Name</label>
                        <input type="text" name="display_name" id="displayName" placeholder="e.g. Chat Support" value="{{ old('display_name') }}">
                        <div class="field-hint">Shown in the Role List and anywhere this role appears.</div>
                    </div>
                </div>

                <div class="preview-card">
                    <div class="preview-eyebrow">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
                        Live preview
                    </div>
                    <div class="preview-top-flex">
                        <div class="preview-role-head">
                            <div class="preview-badge" id="previewBadge">?</div>
                            <div class="preview-titles">
                                <div class="preview-name" id="previewName">Untitled role</div>
                                <div class="preview-key" id="previewKey">—</div>
                            </div>
                        </div>
                        <div class="preview-stat-row">
                            <div class="preview-stat">
                                <div class="preview-stat-num admin" id="previewAdminCount">0</div>
                                <div class="preview-stat-label">Admin pages</div>
                            </div>
                            <div class="preview-stat">
                                <div class="preview-stat-num customer" id="previewUserCount">0</div>
                                <div class="preview-stat-label">User pages</div>
                            </div>
                        </div>
                    </div>
                    <div class="preview-divider"></div>
                    <div class="preview-chips" id="previewChips">
                        <span class="preview-empty">No pages selected yet.</span>
                    </div>
                </div>
            </div>

            <!-- bottom row: permission picker, full width -->
            <div class="picker-row">

                <div class="perm-search">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
                    <input type="text" id="permSearch" placeholder="Filter pages by name…">
                </div>

                @foreach (['admin' => 'Admin Side', 'customer' => 'User Side'] as $groupKey => $groupLabel)
                    @php $groupPermissions = $permissions[$groupKey] ?? collect(); @endphp
                    <div class="perm-panel {{ $groupKey }}" data-group="{{ $groupKey }}">
                        <div class="perm-panel-head">
                            <div class="perm-panel-title">
                                <div class="perm-panel-icon">
                                    @if ($groupKey === 'admin')
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                                    @else
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <h3>{{ $groupLabel }}</h3>
                                    <div class="perm-panel-sub" data-count="{{ $groupKey }}">0 of {{ $groupPermissions->count() }} selected</div>
                                </div>
                            </div>
                            <button type="button" class="perm-select-all" data-select-all="{{ $groupKey }}">Select all</button>
                        </div>
                        <div class="perm-progress-track"><div class="perm-progress-fill" data-progress="{{ $groupKey }}"></div></div>
                        <div class="perm-grid" data-grid="{{ $groupKey }}">
                            @foreach ($groupPermissions as $permission)
                                @php
                                    $isChecked = in_array($permission->id, old('permissions', []));
                                @endphp
                                <label class="perm-card {{ $isChecked ? 'is-checked' : '' }}" data-label="{{ $permission->display_name ?? $permission->name }}" data-group="{{ $groupKey }}">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="perm-card-input" {{ $isChecked ? 'checked' : '' }} hidden>
                                    <div class="perm-card-top">
                                        <div class="perm-card-icon">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! $pageIcons[$permission->name] ?? $pageIcons['_default'] !!}</svg>
                                        </div>
                                        <div class="perm-card-check">
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3.2"><path d="M20 6L9 17l-5-5"/></svg>
                                        </div>
                                    </div>
                                    <div class="perm-card-label">{{ $permission->display_name ?? $permission->name }}</div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="action-bar">
                    <div class="action-bar-summary"><b id="totalCount">0</b> pages selected in total</div>
                    <div class="action-buttons">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Back</a>
                        <button type="submit" class="btn btn-primary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M20 6L9 17l-5-5"/></svg>
                            Save Role
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const allCards = Array.from(document.querySelectorAll('.role-form-page .perm-card'));
    const roleNameInput = document.getElementById('roleName');
    const displayNameInput = document.getElementById('displayName');

    function cardChecked(card) {
        return card.classList.contains('is-checked');
    }

    function setCardChecked(card, checked) {
        card.classList.toggle('is-checked', checked);
        card.querySelector('.perm-card-input').checked = checked;
    }

    function refreshAll() {
        let total = 0;
        ['admin', 'customer'].forEach(function (group) {
            const groupCards = allCards.filter(function (c) { return c.dataset.group === group; });
            const checked = groupCards.filter(cardChecked);
            total += checked.length;

            const countEl = document.querySelector('[data-count="' + group + '"]');
            if (countEl) countEl.textContent = checked.length + ' of ' + groupCards.length + ' selected';

            const progressEl = document.querySelector('[data-progress="' + group + '"]');
            if (progressEl) progressEl.style.width = (groupCards.length ? (checked.length / groupCards.length * 100) : 0) + '%';

            const btn = document.querySelector('[data-select-all="' + group + '"]');
            if (btn) {
                const visibleCards = groupCards.filter(function (c) { return !c.classList.contains('is-hidden'); });
                const allVisibleChecked = visibleCards.length > 0 && visibleCards.every(cardChecked);
                btn.textContent = allVisibleChecked ? 'Clear all' : 'Select all';
            }
        });
        document.getElementById('totalCount').textContent = total;
        updatePreview();
    }

    function updatePreview() {
        const displayName = displayNameInput.value.trim();
        const roleName = roleNameInput.value.trim();

        document.getElementById('previewName').textContent = displayName || 'Untitled role';
        document.getElementById('previewKey').textContent = roleName || '—';

        const badge = document.getElementById('previewBadge');
        badge.textContent = displayName ? displayName.charAt(0).toUpperCase() : '?';

        const checkedAdmin = allCards.filter(function (c) { return c.dataset.group === 'admin' && cardChecked(c); });
        const checkedUser = allCards.filter(function (c) { return c.dataset.group === 'customer' && cardChecked(c); });
        document.getElementById('previewAdminCount').textContent = checkedAdmin.length;
        document.getElementById('previewUserCount').textContent = checkedUser.length;

        const allChecked = checkedAdmin.concat(checkedUser);
        const chipsEl = document.getElementById('previewChips');
        if (allChecked.length === 0) {
            chipsEl.innerHTML = '<span class="preview-empty">No pages selected yet.</span>';
            return;
        }
        const maxShow = 8;
        const shown = allChecked.slice(0, maxShow);
        let html = shown.map(function (c) {
            return '<span class="preview-chip ' + c.dataset.group + '">' + c.dataset.label + '</span>';
        }).join('');
        if (allChecked.length > maxShow) {
            html += '<span class="preview-chip more">+' + (allChecked.length - maxShow) + ' more</span>';
        }
        chipsEl.innerHTML = html;
    }

    allCards.forEach(function (card) {
        card.addEventListener('click', function (e) {
            e.preventDefault();
            setCardChecked(card, !cardChecked(card));
            refreshAll();
        });
    });

    document.querySelectorAll('[data-select-all]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const group = btn.getAttribute('data-select-all');
            const visibleCards = allCards.filter(function (c) { return c.dataset.group === group && !c.classList.contains('is-hidden'); });
            const allChecked = visibleCards.length > 0 && visibleCards.every(cardChecked);
            visibleCards.forEach(function (c) { setCardChecked(c, !allChecked); });
            refreshAll();
        });
    });

    document.getElementById('permSearch').addEventListener('input', function (e) {
        const q = e.target.value.trim().toLowerCase();
        allCards.forEach(function (c) {
            const match = c.dataset.label.toLowerCase().includes(q);
            c.classList.toggle('is-hidden', q.length > 0 && !match);
        });
        refreshAll();
    });

    roleNameInput.addEventListener('input', updatePreview);
    displayNameInput.addEventListener('input', updatePreview);

    refreshAll();
});
</script>
@endpush
