@extends('ManagementSystemViews.AdminViews.Layouts.app')

@section('title', 'Company')

@push('styles')
    <style>
        .main-wrapper {
            display: flex;
            gap: 10px;
            height: 100%;

        }

        .content-area {
            width: 100%;
        }

        .company-page {
            width: 100%;
            margin: 0;
        }

        .company-card {

            margin-top: 10px !important;
            margin-right: 10px !important;


            background: #f8fafc;
            border-radius: 18px;
            padding: 20px;
            border: 1px solid #eef2f7;
            width: 100%;
        }

        .company-grid {
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 28px;
            align-items: start;
        }

        .logo-panel {
            background: #f1f5f9;
            border-radius: 14px;
            min-height: 520px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logo-box {
            width: 120px;
            height: 120px;
            border-radius: 12px;
            background: linear-gradient(180deg, #cbd5e1 0%, #94a3b8 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            margin-bottom: 14px;
            border: 3px solid #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .logo-placeholder {
            text-align: center;
            color: #0ea5b7;
            font-size: 11px;
            line-height: 1.5;
            font-weight: 700;
        }

        .logo-edit-btn {
            position: absolute;
            right: 8px;
            bottom: 8px;
            width: 28px;
            height: 28px;
            border: none;
            border-radius: 50%;
            background: #ffffff;
            color: #06b6d4;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .logo-note {
            width: 100%;
            background: #e0e7ff;
            border-radius: 10px;
            padding: 12px 14px;
            color: #38bdf8;
            font-size: 11px;
        }

        .logo-note strong {
            display: block;
            color: #334155;
            font-size: 11px;
            margin-bottom: 6px;
        }

        .logo-note ul {
            padding-left: 16px;
            margin: 0;
        }

        .form-section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 18px;
        }

        .form-section-title::before {
            content: "";
            width: 4px;
            height: 26px;
            border-radius: 99px;
            background: #06b6d4;
            display: block;
        }

        .field-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: #6b7280;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .custom-input,
        .custom-textarea,
        .custom-file {
            width: 100%;
            border: none;
            outline: none;
            background: #eceff3;
            border-radius: 8px;
            padding: 11px 14px;
            font-size: 14px;
            color: #111827;
        }

        .custom-textarea {
            min-height: 56px;
            resize: vertical;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px 14px;
        }

        .form-col-span-2 {
            grid-column: span 2;
        }

        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
            color: #4b5563;
            font-size: 14px;
        }

        .checkbox-row input {
            width: 16px;
            height: 16px;
            accent-color: #06b6d4;
        }

        .btn-submit-wrap {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-submit-company {
            min-width: 180px;
            border: none;
            border-radius: 8px;
            background: #11c5df;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            padding: 12px 20px;
            box-shadow: 0 4px 10px rgba(6, 182, 212, 0.25);
        }

        .btn-submit-company:hover {
            background: #0fb4cc;
        }

        .btn-back-company {
            min-width: 180px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #fff;
            color: #334155;
            font-size: 14px;
            font-weight: 600;
            padding: 12px 20px;
            text-align: center;
            text-decoration: none;
        }

        .company-overview-grid {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 18px;
            align-items: stretch;
        }

        .company-left-panel {
            background: #eef1f4;
            border-radius: 4px;
            min-height: 520px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 28px;
        }

        .company-logo-box {
            width: 90px;
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .company-logo-box img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 50%;
        }

        .company-default-logo {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: linear-gradient(180deg, #ffcf57 0%, #f59e0b 100%);
            color: #fff;
            font-size: 34px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .company-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px 16px;
        }

        .company-field.full-width {
            grid-column: span 2;
        }

        .company-value {
            background: #e9ecef;
            border-radius: 6px;
            padding: 8px 11px;
            font-size: 14px;
            color: #111827;
            display: flex;
            align-items: center;
            word-break: break-word;
        }

        .company-actions {
            margin-top: 18px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: flex-start;
        }

        .btn-company-outline-info,
        .btn-company-outline-danger {
            height: 32px;
            padding: 0 14px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            background: #fff;
            cursor: pointer;
        }

        .btn-company-outline-info {
            color: #11c5df;
            border: 1px solid #11c5df;
        }

        .btn-company-outline-info:hover {
            background: #ecfeff;
            color: #0ea5b7;
        }

        .btn-company-outline-danger {
            color: #ef4444;
            border: 1px solid #ef4444;
        }

        .btn-company-outline-danger:hover {
            background: #fef2f2;
            color: #dc2626;
        }
        /* Floating Top-Right Alert Styles */
.alert-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.custom-alert {
    background: #ffffff !important;
    color: #334155 !important;
    border: none !important;
    border-radius: 12px !important;
    padding: 16px 24px !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 280px;
    max-width: 400px;
    width: fit-content; /* Makes width fit content instead of full width */
    animation: slideIn 0.3s ease-out forwards;
}

.custom-alert.alert-success {
    border-left: 4px solid #10b981 !important;
}

.custom-alert.alert-danger {
    border-left: 4px solid #ef4444 !important;
}

@keyframes slideIn {
    from { opacity: 0; transform: translateX(100%); }
    to { opacity: 1; transform: translateX(0); }
}

@keyframes fadeOut {
    from { opacity: 1; transform: translateX(0); }
    to { opacity: 0; transform: translateX(100%); }
}
        @media (max-width: 992px) {

            .company-grid,
            .company-overview-grid {
                grid-template-columns: 1fr;
            }

            .logo-panel,
            .company-left-panel {
                min-height: auto;
            }

            .form-grid,
            .company-details-grid {
                grid-template-columns: 1fr;
            }

            .form-col-span-2,
            .company-field.full-width {
                grid-column: span 1;
            }
        }

        /* Floating Top-Right Alert Styles */
        .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .custom-alert {
            background: #ffffff !important;
            color: #334155 !important;
            border: none !important;
            border-radius: 12px !important;
            padding: 16px 24px !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 280px;
            max-width: 400px;
            width: fit-content;
            animation: slideIn 0.3s ease-out forwards;
        }

        .custom-alert.alert-success {
            border-left: 4px solid #10b981 !important;
        }

        .custom-alert.alert-danger {
            border-left: 4px solid #ef4444 !important;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(100%); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes fadeOut {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(100%); }
        }
    </style>
@endpush

@section('content')
    <div class="company-page">
        <!-- Alert Container -->
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

        @if (!$company)
            <div class="page-title">Company &gt; Set Up</div>
            <div class="page-subtitle">Business Central</div>
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
                                    <button type="submit" class="btn-submit-company">Create</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="company-card">
                    <div class="company-overview-grid">
                        <div class="company-left-panel">
                            <div class="company-logo-box">
                                @if (!empty($company->logo) && file_exists(public_path('storage/' . $company->logo)))
                                    <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo">
                                @else
                                    <div class="company-default-logo">
                                        {{ strtoupper(substr($company->name ?? 'C', 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <div class="form-section-title">Company Details</div>

                            <div class="company-details-grid">
                                <div class="company-field full-width">
                                    <div class="field-label">Company Name</div>
                                    <div class="company-value">{{ $company->name ?? '-' }}</div>
                                </div>

                                <div class="company-field">
                                    <div class="field-label">Client ID</div>
                                    <div class="company-value">{{ $company->companyConnection->client_id ?? '-' }}</div>
                                </div>

                                <div class="company-field">
                                    <div class="field-label">BC Company ID</div>
                                    <div class="company-value">{{ $company->companyConnection->company_bc_id ?? '-' }}
                                    </div>
                                </div>

                                <div class="company-field">
                                    <div class="field-label">Email</div>
                                    <div class="company-value">{{ $company->email ?? '-' }}</div>
                                </div>

                                <div class="company-field">
                                    <div class="field-label">Contact</div>
                                    <div class="company-value">{{ $company->phone ?? '-' }}</div>
                                </div>

                                <div class="company-field full-width">
                                    <div class="field-label">Address</div>
                                    <div class="company-value">{{ $company->address ?? '-' }}</div>
                                </div>

                                <div class="company-field full-width">
                                    <div class="field-label">Connection Status</div>
                                    <div class="company-value">
                                        {{ $company->companyConnection && $company->companyConnection->status ? 'Active' : 'Inactive' }}
                                    </div>
                                </div>

                                <div class="company-field full-width">
                                    <div class="field-label">Company Status</div>
                                    <div class="company-value">
                                        {{ $company->is_active ? 'Active' : 'Inactive' }}
                                    </div>
                                </div>
                            </div>

                            <div class="company-actions">
                                <a href="{{ route('companies.edit', $company->id) }}" class="btn-company-outline-info">
                                    <i class="bi bi-pencil-square"></i>
                                    Edit Company
                                </a>
                                 <a href="{{ route('companies.api.setup', $company->id) }}" class="btn-company-outline-info">
                            <i class="bi bi-sliders"></i>
                            API Setup
                        </a>

                                <!-- Delete Button triggers Modal -->
                                <button type="button" class="btn-company-outline-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteCompanyModal">
                                    <i class="bi bi-trash"></i>
                                    Delete Company
                                </button>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteCompanyModal" tabindex="-1"
                                    aria-labelledby="deleteCompanyModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteCompanyModalLabel">Are you sure?</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                This action is permanent and cannot be undone. Your company details will be
                                                deleted.
                                            </div>
                                            <div class="modal-footer">
                                                <form action="{{ route('companies.destroy', $company->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel Request</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    </div>
                </div>
            </div>
        </div>
    @endif
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
        document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.custom-alert');
    
    alerts.forEach(function(alert) {
        // Auto-close after 4 seconds
        setTimeout(function() {
            alert.style.animation = 'fadeOut 0.5s ease-in forwards';
            
            // Remove from DOM after animation finishes
            alert.addEventListener('animationend', function() {
                alert.remove();
            });
        }, 4000);
    });
});
    </script>
@endpush
