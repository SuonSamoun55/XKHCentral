@extends('Layout.POSAdmin.app')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/css/views/Management/tax_group_form.css') }}">
@endpush

@section('title', 'Add Tax Group')

@section('content')
<div class="tax-group-page">



    <h1>Add Tax Group</h1>

    @if ($errors->any())
        <div class="error-banner">
            <i class="bi bi-exclamation-circle"></i>
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
        <section class="card empty-card">
            No new tax group codes to add yet — every code your synced items are using already has a
            tax group configured, or no items have synced a tax group code from Business Central yet.
            <div>
                <a href="{{ route('tax-groups.index') }}" class="btn">Back</a>
            </div>
        </section>
    @else
        <section class="card form-card">
            <form action="{{ route('tax-groups.store') }}" method="POST">
                @csrf

                <div class="field">
                    <label for="taxCode">Tax Code <span class="required">*</span></label>
                    <select name="code" id="taxCode" class="select-control" required>
                        <option value="" {{ old('code') ? '' : 'selected' }} disabled hidden>Select a Business Central code</option>
                        @foreach ($availableCodes as $code)
                            <option value="{{ $code }}" {{ old('code') === $code ? 'selected' : '' }}>{{ $code }}</option>
                        @endforeach
                    </select>
                    <p class="hint">Only codes found on synced items are available.</p>
                </div>

                <div class="field">
                    <label for="taxDisplayName">Display Name <span class="optional-tag">Optional</span></label>
                    <input type="text" name="display_name" id="taxDisplayName" class="text-control" placeholder="e.g. GST 15%" value="{{ old('display_name') }}">
                    <p class="hint">A customer-friendly label shown with the code.</p>
                </div>

                <div class="field">
                    <label for="taxPercent">Tax Rate <span class="required">*</span></label>
                    <div class="rate-row">
                        <input type="text" name="percent" id="taxPercent" placeholder="15" value="{{ old('percent') }}" required inputmode="decimal">
                        <div class="rate-suffix">%</div>
                    </div>
                    <p class="hint">Enter a value from 0 to 100. This rate applies to all items using the selected code.</p>
                </div>

                <div class="banner">
                    <i class="bi bi-info-circle"></i>
                    Changes apply immediately to synced items using this tax code.
                </div>

                <div class="form-footer">
                    <a href="{{ route('tax-groups.index') }}" class="btn">Cancel</a>
                    <button type="submit" class="btn btn-primary">Add Tax Group</button>
                </div>
            </form>
        </section>
    @endif

</div>
@endsection
