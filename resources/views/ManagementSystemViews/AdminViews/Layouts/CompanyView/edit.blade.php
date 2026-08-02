@extends('Layout.Management.app')

@section('title', 'Edit Company')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/Management/edit_company.css') }}">
@endpush

@section('content')
<div class="company-setup-page">

    {{-- ============ ALERTS ============ --}}
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

        @if ($errors->any())
            <div class="custom-alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>Please fix the errors below.</span>
            </div>
        @endif
    </div>

    <div class="containers">
        <div class="company-setup-card">
            <form action="{{ route('companies.update', $company->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="header">
                    <div class="page-eyebrow">
                        <i class="bi bi-building"></i>
                        <span>Business Central</span>
                    </div>
                    <h1 class="page-title">Edit Company</h1>
                </div>

                <div class="company-setup-grid">

                    {{-- ============ LOGO PANEL ============ --}}
                    <div class="logo-panel">
                        <div class="logo-box">
                            @if (!empty($company->logo))
                                <img id="logoPreview" src="{{ asset('storage/' . $company->logo) }}" alt="Logo Preview">
                                <div class="logo-placeholder" id="logoPlaceholder" style="display:none;">
                                    <i class="bi bi-camera"></i>
                                    <span>Upload logo</span>
                                </div>
                            @else
                                <img id="logoPreview" alt="Logo Preview" style="display:none;">
                                <div class="logo-placeholder" id="logoPlaceholder">
                                    <i class="bi bi-camera"></i>
                                    <span>Upload logo</span>
                                </div>
                            @endif

                            <button type="button" class="logo-edit-btn" id="openLogoPicker" aria-label="Change logo">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                        </div>

                        <label class="file-picker">
                            <input
                                type="file"
                                name="logo"
                                id="logoInput"
                                class="custom-file"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            >
                        </label>

                        <div class="logo-note">
                            <strong><i class="bi bi-info-circle"></i> Logo requirements</strong>
                            <ul>
                                <li>Maximum file size: 2MB</li>
                                <li>Format: JPG, PNG, or WEBP</li>
                            </ul>
                        </div>
                    </div>

                    {{-- ============ COMPANY INFO FORM ============ --}}
                    <div class="info-column">
                        <div class="scrollable-info-panel">

                            <div class="form-section">
                                <div class="form-section-title">Company Info</div>
                                <div class="form-grid">
                                    <div class="form-col-span-2">
                                        <div class="field-label">Company Name</div>
                                        <input type="text" name="name" class="custom-input" value="{{ old('name', $company->name) }}" required>
                                    </div>

                                    <div>
                                        <div class="field-label">Email</div>
                                        <input type="email" name="email" class="custom-input" value="{{ old('email', $company->email) }}">
                                    </div>

                                    <div>
                                        <div class="field-label">Contact</div>
                                        <input type="text" name="phone" class="custom-input" value="{{ old('phone', $company->phone) }}" placeholder="+855">
                                    </div>

                                    <div class="form-col-span-2">
                                        <div class="field-label">Address</div>
                                        <textarea name="address" class="custom-textarea">{{ old('address', $company->address) }}</textarea>
                                    </div>

                                    <div class="form-col-span-2">
                                        <div class="field-label">Display Name</div>
                                        <input type="text" name="display_name" class="custom-input" value="{{ old('display_name', $company->display_name) }}">
                                    </div>

                                    <div class="form-col-span-2">
                                        <div class="field-label">Tax Number</div>
                                        <input type="text" name="tax_number" class="custom-input" value="{{ old('tax_number', $company->tax_number) }}">
                                    </div>

                                    <div class="form-col-span-2">
                                        <label class="toggle-row">
                                            <span class="toggle-row-title">Company Active</span>
                                            <span class="toggle-switch">
                                                <input type="checkbox" name="is_active" {{ old('is_active', $company->is_active) ? 'checked' : '' }}>
                                                <span class="toggle-slider"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <div class="form-section-title">Connection</div>
                                <div class="form-grid">
                                    <div>
                                        <div class="field-label">Client ID</div>
                                        <input type="text" name="client_id" class="custom-input" value="{{ old('client_id', $company->companyConnection->client_id ?? '') }}" required>
                                    </div>

                                    <div>
                                        <div class="field-label">BC Company ID</div>
                                        <input type="text" name="company_bc_id" class="custom-input" value="{{ old('company_bc_id', $company->companyConnection->company_bc_id ?? '') }}" required>
                                    </div>

                                    <div class="form-col-span-2">
                                        <div class="field-label">Tenant ID</div>
                                        <input type="text" name="tenant_id" class="custom-input" value="{{ old('tenant_id', $company->companyConnection->tenant_id ?? '') }}" required>
                                    </div>

                                    <div class="form-col-span-2">
                                        <div class="field-label">Client Secret</div>
                                        <input type="text" name="client_secret" class="custom-input" value="" placeholder="Leave blank to keep current secret">
                                    </div>

                                    <div>
                                        <div class="field-label">Environment</div>
                                        <input type="text" name="environment" class="custom-input" value="{{ old('environment', $company->companyConnection->environment ?? '') }}">
                                    </div>

                                    <div>
                                        <div class="field-label">Connection Status</div>
                                        <label class="toggle-row toggle-row--inline">
                                            <span class="toggle-switch">
                                                <input type="checkbox" name="status" {{ old('status', $company->companyConnection->status ?? false) ? 'checked' : '' }}>
                                                <span class="toggle-slider"></span>
                                            </span>
                                            <span class="toggle-row-title">Active</span>
                                        </label>
                                    </div>

                                    <div class="form-col-span-2">
                                        <div class="field-label">Base URL</div>
                                        <input type="text" name="base_url" class="custom-input" value="{{ old('base_url', $company->companyConnection->base_url ?? '') }}">
                                    </div>

                                    <div class="form-col-span-2">
                                        <div class="field-label">Token URL</div>
                                        <input type="text" name="token_url" class="custom-input" value="{{ old('token_url', $company->companyConnection->token_url ?? '') }}">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="btn-submit-wrap">
                            <a href="{{ route('companies.index') }}" class="btn-ghost">Back</a>
                            <a href="{{ route('companies.api.setup', $company->id) }}" class="btn-outline">
                                <i class="bi bi-plug"></i> API Setup
                            </a>
                            <button type="submit" class="btn-primary">
                                <i class="bi bi-check2"></i> Save changes
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const openLogoPicker = document.getElementById('openLogoPicker');
    const logoInput = document.getElementById('logoInput');
    const logoPreview = document.getElementById('logoPreview');
    const logoPlaceholder = document.getElementById('logoPlaceholder');

    function updateLogoPreview(file) {
        if (!file || !logoPreview || !logoPlaceholder) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            logoPreview.src = e.target.result;
            logoPreview.style.display = 'block';
            logoPlaceholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    if (openLogoPicker && logoInput) {
        openLogoPicker.addEventListener('click', function () {
            logoInput.click();
        });
    }

    if (logoInput) {
        logoInput.addEventListener('change', function () {
            const file = this.files[0] || null;
            if (!file) return;

            updateLogoPreview(file);
        });
    }

    // Auto-close alerts
    document.addEventListener('DOMContentLoaded', function () {
        const alerts = document.querySelectorAll('.custom-alert');

        alerts.forEach(function (alert) {
            setTimeout(function () {
                alert.style.animation = 'fadeOut 0.4s ease-in forwards';

                alert.addEventListener('animationend', function () {
                    alert.remove();
                });
            }, 4000);
        });
    });
</script>
@endpush