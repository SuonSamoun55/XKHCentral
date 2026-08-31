@extends('Layout.Management.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/Management/permission_form.css') }}">
@endpush

@section('title', 'Add Page')

@section('content')
<div class="page-form-page">

    <h1>Add Page</h1>

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
        <section class="card empty-card">
            Every known page already exists in the list — there's nothing left to add.
            A new key only appears here once a developer wires up a new
            <code>permission:&lt;key&gt;</code> route and adds it to
            <code>RoleAndPermissionSeeder::$pages</code>.
            <div>
                <a href="{{ route('permissions.index') }}" class="btn">Back to Page List</a>
            </div>
        </section>
    @else
        <section class="card form-card">
            <form action="{{ route('permissions.store') }}" method="POST">
                @csrf

                <div class="field">
                    <label for="pageKeySelect">Page Key <span class="required">*</span></label>
                    <select name="name" id="pageKeySelect" class="select-control" required>
                        <option value="" {{ old('name') ? '' : 'selected' }} disabled hidden>Select a page…</option>
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
                    <p class="hint">Only real, already-wired-up pages are listed — picking one is what actually lets a role be granted access to it.</p>
                </div>

                <div class="field">
                    <label>URL(s) this key gates</label>
                    <div class="url-preview is-empty" id="pageKeyUrls">Select a page above to see what it protects.</div>
                </div>

                <div class="field">
                    <label for="pageKeyLabel">Page Label <span class="optional-tag">Optional</span></label>
                    <input type="text" name="display_name" id="pageKeyLabel" class="text-control" placeholder="e.g. Chat View" value="{{ old('display_name') }}">
                    <p class="hint">Auto-filled from the selected page — change it if you want a different display name.</p>
                </div>

                <div class="form-footer">
                    <a href="{{ route('permissions.index') }}" class="btn">Cancel</a>
                    <button type="submit" class="btn btn-primary">Add Page</button>
                </div>
            </form>
        </section>
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
            chip.className = 'url-chip';
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
