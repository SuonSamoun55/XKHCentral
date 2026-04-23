@extends('ManagementSystemViews.AdminViews.Layouts.app')
<link rel="stylesheet" href="{{ asset('css/POSsystem/POSAdmin/create_company.css') }}">
@section('title', 'Company Setup')

@push('styles')
<style>
  
</style>
@endpush

@section('content')
<div class="company-setup-page">
    <div class="page-title">Company &gt; Set Up</div>
    <div class="page-subtitle">Business Central</div>

    <div class="company-setup-card">
        <form action="{{ route('companies.store') }}" method="POST" id="companySetupForm">
            @csrf

            <div class="company-setup-grid">
                {{-- Left --}}
                <div class="logo-panel">
                    <div class="logo-box">
                        <img id="logoPreview" alt="Logo Preview">
                        <div class="logo-placeholder" id="logoPlaceholder">
                            <div><i class="bi bi-camera" style="font-size:20px;"></i></div>
                            <div>UPLOAD LOGO</div>
                        </div>

                        <button type="button" class="logo-edit-btn" id="openLogoPrompt">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                    </div>

                    <input
                        type="text"
                        name="logo"
                        id="logoInput"
                        class="hidden-logo-input"
                        value="{{ old('logo') }}"
                    >

                    <div class="logo-note">
                        <strong><i class="bi bi-info-circle me-1"></i>LOGO REQUIREMENT</strong>
                        <ul>
                            <li>Maximum file size: 2MB</li>
                            <li>Format: JPG, PNG, or WEBP</li>
                        </ul>
                    </div>
                </div>

                {{-- Right --}}
                <div>
                    <div class="form-section-title">Company Info</div>

                    <div class="form-grid">
                        <div class="form-col-span-2">
                            <div class="field-label">Company Name</div>
                            <input type="text" name="name" class="custom-input" value="{{ old('name') }}" required>
                        </div>

                        <div>
                            <div class="field-label">Client ID</div>
                            <input type="text" name="client_id" class="custom-input" value="{{ old('client_id') }}" required>
                        </div>

                        <div>
                            <div class="field-label">BC Company ID</div>
                            <input type="text" name="company_bc_id" class="custom-input" value="{{ old('company_bc_id') }}" required>
                        </div>

                        <div>
                            <div class="field-label">Email</div>
                            <input type="email" name="email" class="custom-input" value="{{ old('email') }}">
                        </div>

                        <div>
                            <div class="field-label">Contact</div>
                            <input type="text" name="phone" class="custom-input" value="{{ old('phone') }}" placeholder="+855">
                        </div>

                        <div class="form-col-span-2">
                            <div class="field-label">Address</div>
                            <textarea name="address" class="custom-textarea">{{ old('address') }}</textarea>
                        </div>

                        <div class="form-col-span-2">
                            <div class="field-label">Base URL</div>
                            <input type="text" name="base_url" class="custom-input" value="{{ old('base_url') }}">
                        </div>

                        <div class="form-col-span-2">
                            <div class="field-label">Token URL</div>
                            <input type="text" name="token_url" class="custom-input" value="{{ old('token_url') }}">
                        </div>

                        {{-- Hidden / extra fields still needed by controller --}}
                        <input type="hidden" name="display_name" value="{{ old('display_name') }}">
                        <input type="hidden" name="tax_number" value="{{ old('tax_number') }}">
                        <input type="hidden" name="tenant_id" value="{{ old('tenant_id', 'default_tenant') }}">
                        <input type="hidden" name="client_secret" value="{{ old('client_secret', 'default_secret') }}">
                        <input type="hidden" name="environment" value="{{ old('environment') }}">

                        <div class="form-col-span-2">
                            <label class="checkbox-row">
                                <input type="checkbox" checked disabled>
                                <span>Company Active</span>
                            </label>
                        </div>
                    </div>

                    <div class="btn-submit-wrap">
                        <button type="submit" class="btn-submit-company">Create</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const openLogoPrompt = document.getElementById('openLogoPrompt');
    const logoInput = document.getElementById('logoInput');
    const logoPreview = document.getElementById('logoPreview');
    const logoPlaceholder = document.getElementById('logoPlaceholder');

    function updateLogoPreview(value) {
        if (value && (value.startsWith('http://') || value.startsWith('https://') || value.startsWith('data:image'))) {
            logoPreview.src = value;
            logoPreview.style.display = 'block';
            logoPlaceholder.style.display = 'none';
        } else {
            logoPreview.src = '';
            logoPreview.style.display = 'none';
            logoPlaceholder.style.display = 'block';
        }
    }

    openLogoPrompt.addEventListener('click', function () {
        const currentValue = logoInput.value || '';
        const logoUrl = prompt('Paste logo URL here:', currentValue);

        if (logoUrl !== null) {
            logoInput.value = logoUrl.trim();
            updateLogoPreview(logoInput.value);
        }
    });

    updateLogoPreview(logoInput.value);
</script>
@endpush
