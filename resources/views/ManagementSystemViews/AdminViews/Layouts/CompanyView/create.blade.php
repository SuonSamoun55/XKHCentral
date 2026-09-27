@extends('Layout.Management.app')

@section('title', 'Create Company')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/Management/Company/edit_company.css') }}">
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
            <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="page-grid">
                    <div class="logo-panel">
                        <div class="logo-avatar-wrap">
                            <div class="logo-box">
                                <img id="logoPreview" alt="Logo Preview" style="display:none;">
                            </div>

                            <button type="button" class="logo-edit-btn" id="openLogoPicker" aria-label="Add logo">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                        </div>

                        <div class="logo-word">Company Logo</div>

                        <input type="file" name="logo" id="logoInput" class="file-picker"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">

                        <div class="logo-hint">JPG, PNG or WEBP<br>max 2MB</div>

                        <div class="logo-avatar-wrap mt-3">
                            <div class="logo-box">
                                <img id="faviconPreview" alt="Favicon Preview" style="display:none;">
                            </div>

                            <button type="button" class="logo-edit-btn" id="openFaviconPicker" aria-label="Add favicon">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                        </div>

                        <div class="logo-word">Company Favicon</div>

                        <input type="file" name="favicon" id="faviconInput" class="file-picker"
                            accept=".jpg,.jpeg,.png,.webp,.ico,image/jpeg,image/png,image/webp,image/x-icon">

                        <div class="logo-hint">PNG, ICO, JPG or WEBP<br>max 512KB</div>
                    </div>

                    {{-- ============ FORM PANEL ============ --}}
                    <div class="form-panel">
                        <div class="form-title">Create Company</div>

                        <div class="form-section">
                            <div class="section-label">Company Info</div>

                            <div class="field-row single">
                                <div class="field">
                                    <label>Company Name</label>
                                    <input type="text" name="name" value="{{ old('name') }}" required>
                                </div>
                            </div>

                            <div class="field-row">
                                <div class="field">
                                    <label>Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}">
                                </div>
                                <div class="field">
                                    <label>Contact</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+855">
                                </div>
                            </div>

                            <div class="field-row single">
                                <div class="field">
                                    <label>Address</label>
                                    <textarea name="address">{{ old('address') }}</textarea>
                                </div>
                            </div>

                            <div class="field-row">
                                <div class="field">
                                    <label>Display Name</label>
                                    <input type="text" name="display_name" value="{{ old('display_name') }}">
                                </div>
                                <div class="field">
                                    <label>Tax Number</label>
                                    <input type="text" name="tax_number" value="{{ old('tax_number') }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="section-label">Business Central Connection</div>

                            <div class="field-row">
                                <div class="field">
                                    <label>Client ID</label>
                                    <input type="text" name="client_id" value="{{ old('client_id') }}" required>
                                </div>
                                <div class="field">
                                    <label>BC Company ID</label>
                                    <input type="text" name="company_bc_id" value="{{ old('company_bc_id') }}"
                                        required>
                                </div>
                            </div>

                            <div class="field-row single">
                                <div class="field">
                                    <label>Tenant ID</label>
                                    <input type="text" name="tenant_id" value="{{ old('tenant_id') }}" required>
                                </div>
                            </div>

                            <div class="field-row single">
                                <div class="field">
                                    <label>Client Secret</label>
                                    <input type="text" name="client_secret" value="{{ old('client_secret') }}"
                                        required>
                                </div>
                            </div>

                            <div class="field-row single">
                                <div class="field">
                                    <label>Environment</label>
                                    <input type="text" name="environment" value="{{ old('environment') }}">
                                </div>
                            </div>

                            <div class="field-row single">
                                <div class="field">
                                    <label>Base URL</label>
                                    <input type="text" name="base_url" value="{{ old('base_url') }}">
                                </div>
                            </div>

                            <div class="field-row single">
                                <div class="field">
                                    <label>Token URL</label>
                                    <input type="text" name="token_url" value="{{ old('token_url') }}">
                                </div>
                            </div>
                        </div>

                        <div class="actions">
                            <a href="{{ route('companies.index') }}" class="btn btn-discard">Discard</a>
                            <button type="submit" class="btn btn-save">Create</button>
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

        if (openFaviconPicker && faviconInput) {
            openFaviconPicker.addEventListener('click', function() {
                faviconInput.click();
            });
        }

        if (faviconInput) {
            faviconInput.addEventListener('change', function() {
                const file = this.files[0] || null;
                if (!file || !faviconPreview) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    faviconPreview.src = e.target.result;
                    faviconPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
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
