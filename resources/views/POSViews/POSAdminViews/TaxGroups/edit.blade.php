@extends('Layout.POSAdmin.app')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/css/views/Management/tax_group_form.css') }}">
@endpush

@section('title', 'Edit Tax Group')

@section('content')
<div class="tax-group-page">



    <h1>Edit Tax Group</h1>

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

    <section class="card form-card">
        <form action="{{ route('tax-groups.update', $taxGroup->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="taxCode">Tax Code <span class="required">*</span></label>
                <input type="text" name="code" id="taxCode" class="text-control" value="{{ old('code', $taxGroup->code) }}" required>
                <p class="hint">Must match the tax group code Business Central sends on the item exactly (case-sensitive).</p>
            </div>

            <div class="field">
                <label for="taxDisplayName">Display Name <span class="optional-tag">Optional</span></label>
                <input type="text" name="display_name" id="taxDisplayName" class="text-control" placeholder="e.g. GST 15%" value="{{ old('display_name', $taxGroup->display_name) }}">
                <p class="hint">A customer-friendly label shown with the code.</p>
            </div>

            <div class="field">
                <label for="taxPercent">Tax Rate <span class="required">*</span></label>
                <div class="rate-row">
                    <input type="text" name="percent" id="taxPercent" value="{{ old('percent', rtrim(rtrim(number_format($taxGroup->percent, 2), '0'), '.')) }}" required inputmode="decimal">
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
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </section>

</div>
@endsection
