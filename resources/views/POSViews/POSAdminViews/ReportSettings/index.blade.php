@extends('Layout.POSAdmin.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/Management/TaxGroup/TaxList.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/views/POSViews/POSAdminViews/ReportSettings/index.css') }}">
@endpush

@section('title', 'Report Settings')

@section('content')
<div class="pagelist-page">

    <div class="alert-container">
        @if (session('success'))
            <div class="custom-alert alert-success">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="custom-alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <h1>Report Settings</h1>

        <form method="POST" action="{{ route('report-settings.update') }}" class="rs-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="rs-card">
                <div class="rs-card-title"><i class="bi bi-building"></i> Company Info Shown</div>
                <p class="rs-card-subtitle">What appears in the report header and the Bill To panel.</p>

                <div class="rs-logo-upload">
                    <div class="rs-logo-preview">
                        @if (!empty($settings->logo))
                            <img src="{{ asset('storage/' . $settings->logo) }}" alt="Report logo" id="rsLogoPreview">
                        @else
                            <span class="rs-logo-placeholder" id="rsLogoPlaceholder">No logo</span>
                            <img src="" alt="Report logo" id="rsLogoPreview" style="display:none;">
                        @endif
                    </div>
                    <div>
                        <label for="rsLogoInput" class="rs-logo-btn"><i class="bi bi-upload"></i> Upload / Change Logo</label>
                        <input type="file" name="logo" id="rsLogoInput" accept="image/png,image/jpeg,image/webp" style="display:none;">
                        <div class="field-help">
                            JPG, PNG or WEBP, max 2MB. This logo is used on the report/receipt only — it does
                            not change your sidebar/header logo. Turning on "Company Logo" below shows it on the report.
                        </div>
                    </div>
                </div>

                <div class="rs-toggle-grid">
                    <label class="rs-toggle">
                        <span class="rs-toggle-label"><i class="bi bi-image"></i> Company Logo</span>
                        <input type="checkbox" name="show_logo" value="1" {{ $settings->show_logo ? 'checked' : '' }}>
                        <span class="rs-switch"></span>
                    </label>
                    <label class="rs-toggle">
                        <span class="rs-toggle-label"><i class="bi bi-fonts"></i> Company Name</span>
                        <input type="checkbox" name="show_company_name" value="1" {{ $settings->show_company_name ? 'checked' : '' }}>
                        <span class="rs-switch"></span>
                    </label>
                    <label class="rs-toggle">
                        <span class="rs-toggle-label"><i class="bi bi-geo-alt"></i> Address</span>
                        <input type="checkbox" name="show_address" value="1" {{ $settings->show_address ? 'checked' : '' }}>
                        <span class="rs-switch"></span>
                    </label>
                    <label class="rs-toggle">
                        <span class="rs-toggle-label"><i class="bi bi-telephone"></i> Phone</span>
                        <input type="checkbox" name="show_phone" value="1" {{ $settings->show_phone ? 'checked' : '' }}>
                        <span class="rs-switch"></span>
                    </label>
                    <label class="rs-toggle">
                        <span class="rs-toggle-label"><i class="bi bi-envelope"></i> Email</span>
                        <input type="checkbox" name="show_email" value="1" {{ $settings->show_email ? 'checked' : '' }}>
                        <span class="rs-switch"></span>
                    </label>
                    <label class="rs-toggle">
                        <span class="rs-toggle-label"><i class="bi bi-receipt"></i> Tax Number</span>
                        <input type="checkbox" name="show_tax_number" value="1" {{ $settings->show_tax_number ? 'checked' : '' }}>
                        <span class="rs-switch"></span>
                    </label>
                </div>
            </div>

            <div class="rs-card">
                <div class="rs-card-title"><i class="bi bi-table"></i> Item Table Columns</div>
                <p class="rs-card-subtitle">Extra columns shown alongside each line item.</p>
                <div class="rs-toggle-grid">
                    <label class="rs-toggle">
                        <span class="rs-toggle-label"><i class="bi bi-percent"></i> Discount column</span>
                        <input type="checkbox" name="show_discount_column" value="1" {{ $settings->show_discount_column ? 'checked' : '' }}>
                        <span class="rs-switch"></span>
                    </label>
                    <label class="rs-toggle">
                        <span class="rs-toggle-label"><i class="bi bi-cash-coin"></i> VAT column</span>
                        <input type="checkbox" name="show_vat_column" value="1" {{ $settings->show_vat_column ? 'checked' : '' }}>
                        <span class="rs-switch"></span>
                    </label>
                    <label class="rs-toggle">
                        <span class="rs-toggle-label"><i class="bi bi-rulers"></i> Unit of measure column</span>
                        <input type="checkbox" name="show_unit_column" value="1" {{ $settings->show_unit_column ? 'checked' : '' }}>
                        <span class="rs-switch"></span>
                    </label>
                    <label class="rs-toggle">
                        <span class="rs-toggle-label"><i class="bi bi-card-image"></i> Item image</span>
                        <input type="checkbox" name="show_item_image" value="1" {{ $settings->show_item_image ? 'checked' : '' }}>
                        <span class="rs-switch"></span>
                    </label>
                </div>
            </div>

            <div class="rs-card">
                <div class="rs-card-title"><i class="bi bi-arrows-angle-expand"></i> Spacing</div>
                <p class="rs-card-subtitle">How tightly the report's rows and sections are packed together.</p>
                <div class="rs-segmented">
                    <input type="radio" name="spacing" id="spacingCompact" value="compact" {{ $settings->spacing === 'compact' ? 'checked' : '' }}>
                    <label for="spacingCompact">Compact</label>
                    <input type="radio" name="spacing" id="spacingNormal" value="normal" {{ $settings->spacing === 'normal' ? 'checked' : '' }}>
                    <label for="spacingNormal">Normal</label>
                    <input type="radio" name="spacing" id="spacingSpacious" value="spacious" {{ $settings->spacing === 'spacious' ? 'checked' : '' }}>
                    <label for="spacingSpacious">Spacious</label>
                </div>
            </div>

            <div class="rs-card">
                <div class="rs-card-title"><i class="bi bi-pen"></i> Signature Area</div>
                <label class="rs-toggle">
                    <span class="rs-toggle-label"><i class="bi bi-vector-pen"></i> Show signature lines at the bottom of the report</span>
                    <input type="checkbox" id="showSignatureToggle" name="show_signature" value="1" {{ $settings->show_signature ? 'checked' : '' }}>
                    <span class="rs-switch"></span>
                </label>

                <div id="signatureLabelsBox" class="rs-signature-labels" style="{{ $settings->show_signature ? '' : 'display:none;' }}">
                    @php $labels = $settings->signature_labels ?: ['Customer Signature', 'Authorized By']; @endphp
                    @foreach ($labels as $label)
                        <input type="text" name="signature_labels[]" class="field-input" value="{{ $label }}" placeholder="e.g. Customer Signature">
                    @endforeach
                    <input type="text" name="signature_labels[]" class="field-input" placeholder="Add another signature line...">
                </div>
            </div>

            <div class="rs-card">
                <div class="rs-card-title"><i class="bi bi-chat-square-text"></i> Footer Note</div>
                <textarea name="footer_note" class="field-input" rows="2" placeholder="Thank you for your order...">{{ $settings->footer_note }}</textarea>
                <div class="field-help">Leave blank to use the default "Thank you for your order" message.</div>
            </div>

            <div class="action-row">
                <button type="submit" class="btn-main"><i class="bi bi-check-lg"></i> Save Report Settings</button>
            </div>
        </form>

</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alerts = document.querySelectorAll('.pagelist-page .custom-alert');
            alerts.forEach(function (alert) {
                setTimeout(function () {
                    alert.style.animation = 'pageListFadeOut 0.5s ease-in forwards';
                    alert.addEventListener('animationend', function () { alert.remove(); });
                }, 4000);
            });

            const toggle = document.getElementById('showSignatureToggle');
            const box = document.getElementById('signatureLabelsBox');
            if (toggle && box) {
                toggle.addEventListener('change', function () {
                    box.style.display = toggle.checked ? '' : 'none';
                });
            }

            const logoInput = document.getElementById('rsLogoInput');
            const logoPreview = document.getElementById('rsLogoPreview');
            const logoPlaceholder = document.getElementById('rsLogoPlaceholder');
            if (logoInput && logoPreview) {
                logoInput.addEventListener('change', function () {
                    const file = logoInput.files && logoInput.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        logoPreview.src = e.target.result;
                        logoPreview.style.display = 'block';
                        if (logoPlaceholder) logoPlaceholder.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    </script>
@endpush
