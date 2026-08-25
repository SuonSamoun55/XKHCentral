@extends('Layout.POSAdmin.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/Management/page_form.css') }}">
@endpush

@section('title', 'Add Tax Group')

@section('content')
<div class="page-form-page">

    <a href="{{ route('tax-groups.index') }}" class="back-link">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M19 12H5"/><path d="M11 18l-6-6 6-6"/></svg>
        Tax Groups
    </a>

    <div class="page-head">
        <h1>Add Tax Group</h1>
        <p>Match a Business Central tax group code to the percentage it actually means.</p>
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

    @if ($availableCodes->isEmpty())
        <div class="form-card">
            <div class="empty-card">
                No new tax group codes to add yet — every code your synced items are using already has a
                tax group configured, or no items have synced a tax group code from Business Central yet.
                <div>
                    <a href="{{ route('tax-groups.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    @else
        <div class="form-card">
            <form action="{{ route('tax-groups.store') }}" method="POST">
                @csrf

                <div class="field">
                    <label for="taxCode">Code</label>
                    <select name="code" id="taxCode" required>
                        <option value="" {{ old('code') ? '' : 'selected' }} disabled>Select a code…</option>
                        @foreach ($availableCodes as $code)
                            <option value="{{ $code }}" {{ old('code') === $code ? 'selected' : '' }}>{{ $code }}</option>
                        @endforeach
                    </select>
                    <div class="field-hint">Only codes your items actually carry from Business Central are listed — each one appears once, even if many items share it.</div>
                </div>

                <div class="field">
                    <label for="taxDisplayName">Display Name</label>
                    <input type="text" name="display_name" id="taxDisplayName" placeholder="e.g. GST 15%" value="{{ old('display_name') }}">
                    <div class="field-hint">Optional — a friendlier label, shown alongside the code.</div>
                </div>

                <div class="field">
                    <label for="taxPercent">Percent</label>
                    <input type="text" name="percent" id="taxPercent" placeholder="e.g. 15" value="{{ old('percent') }}" required inputmode="decimal">
                    <div class="field-hint">The real tax rate for this code, 0–100. Applied immediately to every item already tagged with this code.</div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M20 6L9 17l-5-5"/></svg>
                        Save
                    </button>
                    <a href="{{ route('tax-groups.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </form>
        </div>
    @endif

</div>
@endsection
