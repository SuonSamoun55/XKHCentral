@extends('Layout.Management.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/Management/page_form.css') }}">
@endpush

@section('title', 'Add Page')

@section('content')
<div class="page-form-page">

    <a href="{{ route('permissions.index') }}" class="back-link">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M19 12H5"/><path d="M11 18l-6-6 6-6"/></svg>
        Page List
    </a>

    <div class="page-head">
        <h1>Add Page</h1>
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

    @if (empty($availableKeys))
        <div class="form-card">
            <div class="empty-card">
                Every known page already exists in the list — there's nothing left to add.
                A new key only appears here once a developer wires up a new
                <code>permission:&lt;key&gt;</code> route and adds it to
                <code>RoleAndPermissionSeeder::$pages</code>.
                <div>
                    <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Back to Page List</a>
                </div>
            </div>
        </div>
    @else
        <div class="form-card">
            <form action="{{ route('permissions.store') }}" method="POST">
                @csrf

                <div class="field">
                    <label for="pageKeySelect">Page Key</label>
                    <select name="name" id="pageKeySelect" required>
                        <option value="" {{ old('name') ? '' : 'selected' }} disabled>Select a page…</option>
                        @foreach ($availableKeys as $key => $meta)
                            <option value="{{ $key }}"
                                data-label="{{ $meta['label'] }}"
                                data-urls="{{ $meta['urls'] ?? '' }}"
                                data-group="{{ $meta['group'] === 'admin' ? 'Admin Side' : 'User Side' }}"
                                {{ old('name') === $key ? 'selected' : '' }}>
                                {{ $key }} — {{ $meta['label'] }} ({{ $meta['group'] === 'admin' ? 'Admin Side' : 'User Side' }})
                            </option>
                        @endforeach
                    </select>
                    <div class="field-hint">Only real, already-wired-up pages are listed — picking one is what actually lets a role be granted access to it.</div>
                </div>

                <div class="field">
                    <label>URL(s) this key gates</label>
                    <div class="url-preview is-empty" id="pageKeyUrls">Select a page above to see what it protects.</div>
                </div>

                <div class="field">
                    <label for="pageKeyLabel">Page Label</label>
                    <input type="text" name="display_name" id="pageKeyLabel" placeholder="e.g. Chat View" value="{{ old('display_name') }}">
                    <div class="field-hint">Auto-filled from the selected page — change it if you want a different display name.</div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M20 6L9 17l-5-5"/></svg>
                        Save
                    </button>
                    <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </form>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('pageKeySelect');
    const urlsBox = document.getElementById('pageKeyUrls');
    const labelInput = document.getElementById('pageKeyLabel');

    if (!select) return;

    function renderUrls(urlsRaw) {
        const urls = (urlsRaw || '').split(',').map(u => u.trim()).filter(Boolean);

        if (urls.length === 0) {
            urlsBox.classList.add('is-empty');
            urlsBox.textContent = 'Select a page above to see what it protects.';
            return;
        }

        urlsBox.classList.remove('is-empty');
        urlsBox.innerHTML = urls.map(function (u) {
            const chip = document.createElement('span');
            chip.className = 'url-chip mono';
            chip.textContent = u;
            return chip.outerHTML;
        }).join('');
    }

    select.addEventListener('change', function () {
        const opt = select.options[select.selectedIndex];
        renderUrls(opt.dataset.urls);

        if (!labelInput.value && opt.dataset.label) {
            labelInput.value = opt.dataset.label;
        }
    });

    // Restore state on a validation-error reload (old('name') selected server-side)
    if (select.value) {
        renderUrls(select.options[select.selectedIndex].dataset.urls);
    }
});
</script>
@endpush
