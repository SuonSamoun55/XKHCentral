@extends('Layout.Management.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/Management/permission_form.css') }}">
@endpush

@section('title', 'Edit Page')

@php
    $urls = collect(explode(',', $permission->urls ?? ''))
        ->map(fn ($u) => trim($u))
        ->filter();
@endphp

@section('content')
<div class="page-form-page">

    <h1>Edit Page</h1>

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

    <section class="card form-card">
        <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="pageKeyInput">Page Key <span class="required">*</span></label>
                <input type="text" name="name" id="pageKeyInput" class="text-control" value="{{ old('name', $permission->name) }}" required>
                <p class="hint">Internal key used in code — this must match a real <code>permission:&lt;key&gt;</code> route.</p>
            </div>

            <div class="field">
                <label>URL(s) this key gates</label>
                <div class="url-preview {{ $urls->isEmpty() ? 'is-empty' : '' }}">
                    @if ($urls->isEmpty())
                        No URLs recorded for this page.
                    @else
                        @foreach ($urls as $url)
                            <span class="url-chip">{{ $url }}</span>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="field">
                <label for="pageKeyLabel">Page Label <span class="optional-tag">Optional</span></label>
                <input type="text" name="display_name" id="pageKeyLabel" class="text-control" value="{{ old('display_name', $permission->display_name) }}">
                <p class="hint">Shown in the Page List and anywhere this page appears.</p>
            </div>

            <div class="field">
                <label>Side</label>
                <div class="side-toggle">
                    <input type="radio" name="group" id="groupAdmin" value="admin" {{ old('group', $permission->group) === 'admin' ? 'checked' : '' }}>
                    <label for="groupAdmin">Admin Side</label>

                    <input type="radio" name="group" id="groupCustomer" value="customer" {{ old('group', $permission->group) === 'customer' ? 'checked' : '' }}>
                    <label for="groupCustomer">User Side</label>
                </div>
            </div>

            <div class="form-footer">
                <a href="{{ route('permissions.index') }}" class="btn">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </section>

</div>
@endsection
