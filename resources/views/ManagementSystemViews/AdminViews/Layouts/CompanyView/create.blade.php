@extends('ManagementSystemViews.AdminViews.Layouts.app')

@section('title', 'Company Setup')

@push('styles')
<style>
    .company-setup-page .page-title{
        font-size:20px;
        font-weight:700;
        margin-bottom:2px;
        color:#111827;
    }

    .company-setup-page .page-subtitle{
        font-size:12px;
        color:#9ca3af;
        margin-bottom:18px;
    }

    .company-setup-card{
        background:#f8fafc;
        border-radius:18px;
        padding:20px;
        border:1px solid #eef2f7;
    }

    .company-setup-grid{
        display:grid;
        grid-template-columns: 260px 1fr;
        gap:28px;
        align-items:start;
    }

    .logo-panel{
        background:#f1f5f9;
        border-radius:14px;
        min-height:520px;
        padding:16px;
        display:flex;
        flex-direction:column;
        align-items:center;
    }

    .logo-box{
        width:120px;
        height:120px;
        border-radius:12px;
        background:linear-gradient(180deg, #cbd5e1 0%, #94a3b8 100%);
        display:flex;
        align-items:center;
        justify-content:center;
        position:relative;
        overflow:hidden;
        margin-bottom:14px;
        border:3px solid #ffffff;
        box-shadow:0 4px 12px rgba(0,0,0,0.08);
    }

    .logo-box img{
        width:100%;
        height:100%;
        object-fit:cover;
        display:none;
    }

    .logo-placeholder{
        text-align:center;
        color:#0ea5b7;
        font-size:11px;
        line-height:1.5;
        font-weight:700;
    }

    .logo-edit-btn{
        position:absolute;
        right:8px;
        bottom:8px;
        width:28px;
        height:28px;
        border:none;
        border-radius:50%;
        background:#ffffff;
        color:#06b6d4;
        box-shadow:0 2px 8px rgba(0,0,0,0.15);
        display:flex;
        align-items:center;
        justify-content:center;
        cursor:pointer;
    }

    .logo-note{
        width:100%;
        background:#e0e7ff;
        border-radius:10px;
        padding:12px 14px;
        color:#38bdf8;
        font-size:11px;
    }

    .logo-note strong{
        display:block;
        color:#334155;
        font-size:11px;
        margin-bottom:6px;
    }

    .logo-note ul{
        padding-left:16px;
        margin:0;
    }

    .form-section-title{
        display:flex;
        align-items:center;
        gap:10px;
        font-size:24px;
        font-weight:700;
        color:#111827;
        margin-bottom:18px;
    }

    .form-section-title::before{
        content:"";
        width:4px;
        height:26px;
        border-radius:99px;
        background:#06b6d4;
        display:block;
    }

    .field-label{
        font-size:10px;
        font-weight:700;
        letter-spacing:0.08em;
        color:#6b7280;
        margin-bottom:6px;
        text-transform:uppercase;
    }

    .custom-input,
    .custom-textarea{
        width:100%;
        border:none;
        outline:none;
        background:#eceff3;
        border-radius:8px;
        padding:11px 14px;
        font-size:14px;
        color:#111827;
    }

    .custom-textarea{
        min-height:56px;
        resize:vertical;
    }

    .form-grid{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:16px 14px;
    }

    .form-col-span-2{
        grid-column:span 2;
    }

    .checkbox-row{
        display:flex;
        align-items:center;
        gap:8px;
        margin-top:10px;
        color:#4b5563;
        font-size:14px;
    }

    .checkbox-row input{
        width:16px;
        height:16px;
        accent-color:#06b6d4;
    }

    .btn-submit-wrap{
        display:flex;
        justify-content:center;
        margin-top:20px;
    }

    .btn-submit-company{
        min-width:180px;
        border:none;
        border-radius:8px;
        background:#11c5df;
        color:#fff;
        font-size:14px;
        font-weight:600;
        padding:12px 20px;
        box-shadow:0 4px 10px rgba(6, 182, 212, 0.25);
    }

    .btn-submit-company:hover{
        background:#0fb4cc;
    }

    .hidden-logo-input{
        display:none;
    }

    @media (max-width: 992px){
        .company-setup-grid{
            grid-template-columns:1fr;
        }

        .logo-panel{
            min-height:auto;
        }

        .form-grid{
            grid-template-columns:1fr;
        }

        .form-col-span-2{
            grid-column:span 1;
        }
    }
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
