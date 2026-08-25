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


        <div class="company-card">
            <form action="{{ route('companies.update', $company->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="page-grid">
                    <div class="logo-panel">
                        <div class="logo-avatar-wrap">
                            <div class="logo-circle">
                                {{-- Add your logo image/markup here --}}
                                <img id="logoPreview"
                                    src="{{ !empty($company->logo) ? asset('storage/' . $company->logo) : '' }}"
                                    alt="Logo Preview" style="{{ empty($company->logo) ? 'display:none;' : '' }}">
                            </div>

                            <button type="button" class="logo-edit-btn" id="openLogoPicker" aria-label="Change logo">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                        </div>

                        <div class="logo-word">{{ $company->display_name ?? $company->name }}</div>

                        <input type="file" name="logo" id="logoInput" class="file-picker"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">

                        <div class="logo-hint">JPG, PNG or WEBP<br>max 2MB</div>

                        <div class="logo-avatar-wrap mt-3">
                            <div class="logo-circle" style="width:48px;height:48px;">
                                <img id="faviconPreview"
                                    src="{{ !empty($company->favicon) ? asset('storage/' . $company->favicon) : '' }}"
                                    alt="Favicon Preview" style="{{ empty($company->favicon) ? 'display:none;' : '' }}">
                            </div>

                            <button type="button" class="logo-edit-btn" id="openFaviconPicker" aria-label="Change favicon">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                        </div>

                        <div class="logo-word">Browser Tab Icon</div>

                        <input type="file" name="favicon" id="faviconInput" class="file-picker"
                            accept=".jpg,.jpeg,.png,.webp,.ico,image/jpeg,image/png,image/webp,image/x-icon">

                        <div class="logo-hint">PNG, ICO, JPG or WEBP<br>max 512KB</div>

                        @if (!empty($company->favicon))
                            <label class="logo-hint d-block mt-1" style="cursor:pointer;">
                                <input type="checkbox" name="remove_favicon" id="removeFaviconCheckbox" value="1">
                                Remove favicon
                            </label>
                        @endif
                    </div>

                    {{-- ============ FORM PANEL ============ --}}
                    <div class="form-panel">
                        <div class="form-title">Edit Company</div>

                        <div class="form-section">
                            <div class="section-label">Company Info</div>

                            <div class="field-row single">
                                <div class="field">
                                    <label>Company Name</label>
                                    <input type="text" name="name" value="{{ old('name', $company->name) }}" required>
                                </div>
                            </div>

                            <div class="field-row">
                                <div class="field">
                                    <label>Email</label>
                                    <input type="email" name="email" value="{{ old('email', $company->email) }}">
                                </div>
                                <div class="field">
                                    <label>Contact</label>
                                    <input type="text" name="phone" value="{{ old('phone', $company->phone) }}"
                                        placeholder="+855">
                                </div>
                            </div>

                            <div class="field-row single">
                                <div class="field">
                                    <label>Address</label>
                                    <textarea name="address">{{ old('address', $company->address) }}</textarea>
                                </div>
                            </div>

                            <div class="field-row">
                                <div class="field">
                                    <label>Display Name</label>
                                    <input type="text" name="display_name"
                                        value="{{ old('display_name', $company->display_name) }}">
                                </div>
                                <div class="field">
                                    <label>Tax Number</label>
                                    <input type="text" name="tax_number"
                                        value="{{ old('tax_number', $company->tax_number) }}">
                                </div>
                            </div>

                            <div class="field-row single">
                                <div class="field">
                                    <label>Company Status</label>
                                    <select name="is_active">
                                        <option value="1"
                                            {{ old('is_active', $company->is_active) ? 'selected' : '' }}>Active</option>
                                        <option value="0"
                                            {{ !old('is_active', $company->is_active) ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="section-label">Business Central Connection</div>

                            <div class="field-row">
                                <div class="field">
                                    <label>Client ID</label>
                                    <input type="text" name="client_id"
                                        value="{{ old('client_id', $company->companyConnection->client_id ?? '') }}"
                                        required>
                                </div>
                                <div class="field">
                                    <label>BC Company ID</label>
                                    <input type="text" name="company_bc_id"
                                        value="{{ old('company_bc_id', $company->companyConnection->company_bc_id ?? '') }}"
                                        required>
                                </div>
                            </div>

                            <div class="field-row single">
                                <div class="field">
                                    <label>Tenant ID</label>
                                    <input type="text" name="tenant_id"
                                        value="{{ old('tenant_id', $company->companyConnection->tenant_id ?? '') }}"
                                        required>
                                </div>
                            </div>

                            <div class="field-row single">
                                <div class="field">
                                    <label>Client Secret</label>
                                    <input type="text" name="client_secret" value=""
                                        placeholder="Leave blank to keep current secret">
                                </div>
                            </div>

                            <div class="field-row">
                                <div class="field">
                                    <label>Environment</label>
                                    <input type="text" name="environment"
                                        value="{{ old('environment', $company->companyConnection->environment ?? '') }}">
                                </div>
                                <div class="field">
                                    <label>Connection Status</label>
                                    <select name="status">
                                        <option value="1"
                                            {{ old('status', $company->companyConnection->status ?? false) ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="0"
                                            {{ !old('status', $company->companyConnection->status ?? false) ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="field-row single">
                                <div class="field">
                                    <label>Base URL</label>
                                    <input type="text" name="base_url"
                                        value="{{ old('base_url', $company->companyConnection->base_url ?? '') }}">
                                </div>
                            </div>

                            <div class="field-row single">
                                <div class="field">
                                    <label>Token URL</label>
                                    <input type="text" name="token_url"
                                        value="{{ old('token_url', $company->companyConnection->token_url ?? '') }}">
                                </div>
                            </div>
                        </div>

                        <div class="actions">
                            <a href="{{ route('companies.index') }}" class="btn btn-discard">Discard</a>
                            <button type="submit" class="btn btn-save">Save</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const openLogoPicker = document.getElementById('openLogoPicker');
        const logoInput = document.getElementById('logoInput');
        const logoPreview = document.getElementById('logoPreview');

        function updateLogoPreview(file) {
            if (!file || !logoPreview) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                logoPreview.src = e.target.result;
                logoPreview.style.display = 'block';
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
        const removeFaviconCheckbox = document.getElementById('removeFaviconCheckbox');

        if (openFaviconPicker && faviconInput) {
            openFaviconPicker.addEventListener('click', function() {
                faviconInput.click();
            });
        }

        if (faviconInput) {
            faviconInput.addEventListener('change', function() {
                const file = this.files[0] || null;
                if (!file || !faviconPreview) return;

                // Picking a new file overrides any pending removal.
                if (removeFaviconCheckbox) removeFaviconCheckbox.checked = false;

                const reader = new FileReader();
                reader.onload = function(e) {
                    faviconPreview.src = e.target.result;
                    faviconPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            });
        }

        if (removeFaviconCheckbox) {
            removeFaviconCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    if (faviconInput) faviconInput.value = '';
                    if (faviconPreview) faviconPreview.style.display = 'none';
                }
            });
        }

        // Auto-close alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.custom-alert');

            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.animation = 'fadeOut 0.4s ease-in forwards';

                    alert.addEventListener('animationend', function() {
                        alert.remove();
                    });
                }, 4000);
            });
        });
    </script>
@endpush
