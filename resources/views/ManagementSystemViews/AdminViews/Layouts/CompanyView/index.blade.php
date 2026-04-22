@extends('ManagementSystemViews.AdminViews.Layouts.app')
<link rel="stylesheet" href="{{ asset('css/POSsystem/POSAdmin/company.css') }}">
@section('title', 'Company')

@push('styles')
    <style>
      
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
