@extends('Layout.Management.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/Management/Role/role_form.css') }}">
@endpush

@section('title', 'Create Role')

@php
    $pageIcons = config('role_page_icons');
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

                    @if (auth()->user()?->canManageStaffAcrossCompanies())
                        <div class="field">
                            <label class="cross-company-check">
                                <input type="checkbox" name="is_cross_company" id="isCrossCompany" value="1" {{ old('is_cross_company') ? 'checked' : '' }}>
                                Cross-Company Access
                            </label>
                            <div class="field-hint">Staff with this role can manage and assign staff roles in every company, not just one.</div>
                        </div>
                    @endif
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
<script src="{{ asset('js/admin/role-permission-picker.js') }}"></script>
@endpush
