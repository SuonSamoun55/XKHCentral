@extends('Layout.POSAdmin.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/Management/page_form.css') }}">
@endpush

@section('title', 'Edit Tax Group')

@section('content')
<div class="page-form-page">

    <a href="{{ route('tax-groups.index') }}" class="back-link">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M19 12H5"/><path d="M11 18l-6-6 6-6"/></svg>
        Tax Groups
    </a>

    <div class="page-head">
        <h1>Edit Tax Group</h1>
        <p>Changing the percent here updates every item already tagged with this code right away.</p>
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

    <div class="form-card">
        <form action="{{ route('tax-groups.update', $taxGroup->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="taxCode">Code</label>
                <input type="text" name="code" id="taxCode" value="{{ old('code', $taxGroup->code) }}" required>
                <div class="field-hint">Must match the tax group code Business Central sends on the item exactly (case-sensitive).</div>
            </div>

            <div class="field">
                <label for="taxDisplayName">Display Name</label>
                <input type="text" name="display_name" id="taxDisplayName" value="{{ old('display_name', $taxGroup->display_name) }}">
                <div class="field-hint">Optional — a friendlier label, shown alongside the code.</div>
            </div>

            <div class="field">
                <label for="taxPercent">Percent</label>
                <input type="text" name="percent" id="taxPercent" value="{{ old('percent', rtrim(rtrim(number_format($taxGroup->percent, 2), '0'), '.')) }}" required inputmode="decimal">
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

</div>
@endsection
