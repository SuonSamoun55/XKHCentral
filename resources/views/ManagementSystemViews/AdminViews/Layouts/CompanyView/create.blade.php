@extends('Layout.Management.app')
<link rel="stylesheet" href="{{ asset('/css/views/Management/company.css') }}">
@section('title', 'Create Company')

@section('content')
    <div class="main-wrapper">
        <div class="content-areas">
            <div class="company-page">
                <div class="alert-container">
                    @if ($errors->any())
                        <div class="custom-alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <span>Please fix the errors below.</span>
                        </div>
                    @endif
                </div>

                <div class="container">
                    <div class="company-card">
                        <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="company-grid">
                                <div class="logo-panel">
                                    <div class="logo-box">
                                        <img id="logoPreview" alt="Logo Preview" style="display:none;">
                                        <div class="logo-placeholder" id="logoPlaceholder">
                                            <div><i class="bi bi-camera" style="font-size:20px;"></i></div>
                                            <div>UPLOAD LOGO</div>
                                        </div>

                                        <button type="button" class="logo-edit-btn" id="openLogoPicker">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                    </div>

                                    <input type="file" name="logo" id="logoInput" class="custom-file"
                                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">

                                    <div class="logo-note mt-3">
                                        <strong><i class="bi bi-info-circle me-1"></i>LOGO REQUIREMENT</strong>
                                        <ul>
                                            <li>Maximum file size: 2MB</li>
                                            <li>Format: JPG, PNG, or WEBP</li>
                                        </ul>
                                    </div>

                                    <div class="logo-box mt-3" style="width:64px;height:64px;">
                                        <img id="faviconPreview" alt="Favicon Preview" style="display:none;">
                                        <div class="logo-placeholder" id="faviconPlaceholder">
                                            <div><i class="bi bi-app" style="font-size:16px;"></i></div>
                                        </div>

                                        <button type="button" class="logo-edit-btn" id="openFaviconPicker">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                    </div>

                                    <input type="file" name="favicon" id="faviconInput" class="custom-file"
                                        accept=".jpg,.jpeg,.png,.webp,.ico,image/jpeg,image/png,image/webp,image/x-icon">

                                    <div class="logo-note mt-3">
                                        <strong><i class="bi bi-info-circle me-1"></i>BROWSER TAB ICON</strong>
                                        <ul>
                                            <li>Shown in the browser tab for this company</li>
                                            <li>Maximum file size: 512KB</li>
                                            <li>Format: PNG, ICO, JPG, or WEBP</li>
                                        </ul>
                                    </div>
                                </div>

                                <div>
                                    <div class="form-section-title">Company Info</div>

                                    <div class="form-grid">
                                        <div class="form-col-span-2">
                                            <div class="field-label">Company Name</div>
                                            <input type="text" name="name" class="custom-input"
                                                value="{{ old('name') }}" required>
                                        </div>

                                        <div>
                                            <div class="field-label">Client ID</div>
                                            <input type="text" name="client_id" class="custom-input"
                                                value="{{ old('client_id') }}" required>
                                        </div>

                                        <div>
                                            <div class="field-label">BC Company ID</div>
                                            <input type="text" name="company_bc_id" class="custom-input"
                                                value="{{ old('company_bc_id') }}" required>
                                        </div>

                                        <div>
                                            <div class="field-label">Email</div>
                                            <input type="email" name="email" class="custom-input"
                                                value="{{ old('email') }}">
                                        </div>

                                        <div>
                                            <div class="field-label">Contact</div>
                                            <input type="text" name="phone" class="custom-input"
                                                value="{{ old('phone') }}" placeholder="+855">
                                        </div>

                                        <div class="form-col-span-2">
                                            <div class="field-label">Address</div>
                                            <textarea name="address" class="custom-textarea">{{ old('address') }}</textarea>
                                        </div>

                                        <div class="form-col-span-2">
                                            <div class="field-label">Display Name</div>
                                            <input type="text" name="display_name" class="custom-input"
                                                value="{{ old('display_name') }}">
                                        </div>

                                        <div class="form-col-span-2">
                                            <div class="field-label">Tax Number</div>
                                            <input type="text" name="tax_number" class="custom-input"
                                                value="{{ old('tax_number') }}">
                                        </div>

                                        <div class="form-col-span-2">
                                            <div class="field-label">Tenant ID</div>
                                            <input type="text" name="tenant_id" class="custom-input"
                                                value="{{ old('tenant_id') }}" required>
                                        </div>

                                        <div class="form-col-span-2">
                                            <div class="field-label">Client Secret</div>
                                            <input type="text" name="client_secret" class="custom-input"
                                                value="{{ old('client_secret') }}" required>
                                        </div>

                                        <div class="form-col-span-2">
                                            <div class="field-label">Environment</div>
                                            <input type="text" name="environment" class="custom-input"
                                                value="{{ old('environment') }}">
                                        </div>

                                        <div class="form-col-span-2">
                                            <div class="field-label">Base URL</div>
                                            <input type="text" name="base_url" class="custom-input"
                                                value="{{ old('base_url') }}">
                                        </div>

                                        <div class="form-col-span-2">
                                            <div class="field-label">Token URL</div>
                                            <input type="text" name="token_url" class="custom-input"
                                                value="{{ old('token_url') }}">
                                        </div>

                                        <div class="form-col-span-2">
                                            <label class="checkbox-row">
                                                <input type="checkbox" checked disabled>
                                                <span>Company Active</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="btn-submit-wrap">
                                        <a href="{{ route('companies.index') }}" class="btn-back-company">Back</a>
                                        <button type="submit" class="btn-submit-company">Create</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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
            reader.onload = function(e) {
                logoPreview.src = e.target.result;
                logoPreview.style.display = 'block';
                logoPlaceholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }

        if (openLogoPicker && logoInput) {
            openLogoPicker.addEventListener('click', function() {
                logoInput.click();
            });
        }

        if (logoInput) {
            logoInput.addEventListener('change', function() {
                const file = this.files[0] || null;
                if (!file) return;

                updateLogoPreview(file);
            });
        }

        const openFaviconPicker = document.getElementById('openFaviconPicker');
        const faviconInput = document.getElementById('faviconInput');
        const faviconPreview = document.getElementById('faviconPreview');
        const faviconPlaceholder = document.getElementById('faviconPlaceholder');

        if (openFaviconPicker && faviconInput) {
            openFaviconPicker.addEventListener('click', function() {
                faviconInput.click();
            });
        }

        if (faviconInput) {
            faviconInput.addEventListener('change', function() {
                const file = this.files[0] || null;
                if (!file || !faviconPreview || !faviconPlaceholder) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    faviconPreview.src = e.target.result;
                    faviconPreview.style.display = 'block';
                    faviconPlaceholder.style.display = 'none';
                };
                reader.readAsDataURL(file);
            });
        }
    </script>
@endpush
