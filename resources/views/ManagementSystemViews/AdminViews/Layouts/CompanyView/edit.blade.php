@extends('ManagementSystemViews.AdminViews.Layouts.app')

@section('title', 'Edit Company')

@push('styles')
<style>
    .company-setup-page{
        width: 100%;
    }
    .content-area{
width: 100%;
    }
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
         width: 100%;
         min-width: 100%;
    }

    .company-setup-grid{
        display:grid;
        grid-template-columns:260px 1fr;
        width: 100%;
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
        display:block;
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
    .custom-textarea,
    .custom-file{
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
        gap:10px;
        flex-wrap:wrap;
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

    .btn-back-company{
        min-width:180px;
        border:1px solid #cbd5e1;
        border-radius:8px;
        background:#fff;
        color:#334155;
        font-size:14px;
        font-weight:600;
        padding:12px 20px;
        text-align:center;
        text-decoration:none;
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
    <div class="page-title">Company &gt; Edit</div>
    <div class="page-subtitle">Business Central</div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="company-setup-card">
        <form action="{{ route('companies.update', $company->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="company-setup-grid">
                <div class="logo-panel">
                    <div class="logo-box">
                        @if(!empty($company->logo))
                            <img id="logoPreview" src="{{ asset('storage/' . $company->logo) }}" alt="Logo Preview">
                            <div class="logo-placeholder" id="logoPlaceholder" style="display:none;">
                                <div><i class="bi bi-camera" style="font-size:20px;"></i></div>
                                <div>UPLOAD LOGO</div>
                            </div>
                        @else
                            <img id="logoPreview" alt="Logo Preview" style="display:none;">
                            <div class="logo-placeholder" id="logoPlaceholder">
                                <div><i class="bi bi-camera" style="font-size:20px;"></i></div>
                                <div>UPLOAD LOGO</div>
                            </div>
                        @endif

                        <button type="button" class="logo-edit-btn" id="openLogoPicker">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                    </div>

                    <input
                        type="file"
                        name="logo"
                        id="logoInput"
                        class="custom-file"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                    >

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
                            <input type="text" name="name" class="custom-input" value="{{ old('name', $company->name) }}" required>
                        </div>

                        <div>
                            <div class="field-label">Client ID</div>
                            <input type="text" name="client_id" class="custom-input" value="{{ old('client_id', $company->companyConnection->client_id ?? '') }}" required>
                        </div>

                        <div>
                            <div class="field-label">BC Company ID</div>
                            <input type="text" name="company_bc_id" class="custom-input" value="{{ old('company_bc_id', $company->companyConnection->company_bc_id ?? '') }}" required>
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
                            <div class="field-label">Tenant ID</div>
                            <input type="text" name="tenant_id" class="custom-input" value="{{ old('tenant_id', $company->companyConnection->tenant_id ?? '') }}" required>
                        </div>

                        <div class="form-col-span-2">
                            <div class="field-label">Client Secret</div>
                            <input type="text" name="client_secret" class="custom-input" value="">
                        </div>

                        <div class="form-col-span-2">
                            <div class="field-label">Environment</div>
                            <input type="text" name="environment" class="custom-input" value="{{ old('environment', $company->companyConnection->environment ?? '') }}">
                        </div>

                        <div class="form-col-span-2">
                            <div class="field-label">Base URL</div>
                            <input type="text" name="base_url" class="custom-input" value="{{ old('base_url', $company->companyConnection->base_url ?? '') }}">
                        </div>

                        <div class="form-col-span-2">
                            <div class="field-label">Token URL</div>
                            <input type="text" name="token_url" class="custom-input" value="{{ old('token_url', $company->companyConnection->token_url ?? '') }}">
                        </div>

                        <div class="form-col-span-2">
                            <label class="checkbox-row">
                                <input type="checkbox" name="is_active" {{ old('is_active', $company->is_active) ? 'checked' : '' }}>
                                <span>Company Active</span>
                            </label>
                        </div>

                        <div class="form-col-span-2">
                            <label class="checkbox-row">
                                <input type="checkbox" name="status" {{ old('status', $company->companyConnection->status ?? false) ? 'checked' : '' }}>
                                <span>Connection Active</span>
                            </label>
                        </div>
                    </div>

                    <div class="btn-submit-wrap">
                        <button type="submit" class="btn-submit-company">Update</button>
                        <a href="{{ route('companies.index') }}" class="btn-back-company">Back</a>
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
</script>
@endpush
