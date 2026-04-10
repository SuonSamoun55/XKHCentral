@extends('ManagementSystemViews.AdminViews.Layouts.app')

@section('title', 'Company API Setup')

@push('styles')
<style>
    .api-setup-wrap{
        padding:10px;
        width:100%;
    }
    .api-card{
        background:#f8fafc;
        border:1px solid #e5e7eb;
        border-radius:16px;
        padding:20px;
    }
    .api-title{
        position: sticky;
        top: 0;
        padding:  20px 10px;
        margin-top: -20px;
        background: #f8fafc;
        font-size:22px;
        font-weight:700;
        color:#0f172a;
        margin-bottom:4px;
    }
    .api-subtitle{
        position: sticky;
        top: 68px;
        background: #f8fafc;
    
        color:#6b7280;
        margin-bottom:18px;
        font-size:13px;
    }
    .api-grid{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:14px;
    }
    .api-grid .full{
        grid-column:1 / -1;
    }
    .field-label{
        font-size:11px;
        font-weight:700;
        color:#4b5563;
        margin-bottom:6px;
        text-transform:uppercase;
        letter-spacing:.05em;
    }
    .field-input{
        width:100%;
        border:1px solid #d1d5db;
        border-radius:10px;
        padding:10px 12px;
        font-size:13px;
        background:#fff;
    }
    .field-help{
        font-size:11px;
        color:#6b7280;
        margin-top:4px;
    }
    .action-row{
        margin-top:18px;
        display:flex;
        gap:10px;
        flex-wrap:wrap;
    }
    .btn-main{
        border:none;
        border-radius:10px;
        padding:10px 16px;
        background:#06b6d4;
        color:#fff;
        font-weight:600;
    }
    .btn-light{
        border:1px solid #d1d5db;
        border-radius:10px;
        padding:10px 16px;
        background:#fff;
        color:#334155;
        text-decoration:none;
        font-weight:600;
    }
    .example-box{
        margin-top:18px;
        border-radius:12px;
        border:1px dashed #93c5fd;
        background:#eff6ff;
        padding:12px 14px;
        font-size:12px;
        color:#1e3a8a;
    }
    .example-box code{
        color:#1d4ed8;
    }
    .custom-alert {
        position: fixed; /* Or absolute, depending on your container */
        top: 20px;
        right: 20px;
        min-width: 300px;
        padding: 16px 24px;
        background-color: #ffffff;
        color: #334155; /* Dark text color */
        border-radius: 12px;
        /* The thick green bar on the left */
        border-left: 6px solid #10b981; 
        /* Soft shadow for the floating effect */
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 9999;
        font-family: sans-serif;
        animation: slideIn 0.4s ease-out;
    }

    /* Change color if it's a danger alert */
    .alert-danger {
        border-left-color: #ef4444;
    }

    .alert-text {
        font-size: 15px;
        font-weight: 500;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes fadeOut {
        from { opacity: 1; transform: translateX(0); }
        to { opacity: 0; transform: translateX(20px); }
    }
    @media (max-width: 900px){
        .api-grid{
            grid-template-columns:1fr;
        }
    }
</style>
@endpush

@section('content')
@php
    $defaultSalesOrderByNumberEndpoint = "salesOrders?\$filter=number eq '{documentNo}'&\$top=1";
@endphp
<div class="api-setup-wrap">
    <div class="api-card">
        <div class="api-title">Company API Setup</div>
        <div class="api-subtitle">Configure API endpoints per company. No code change needed when company/API changes.</div>

     @if(session('success'))
    <div class="custom-alert alert-success">
        <span class="alert-text">{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="custom-alert alert-danger">
        <span class="alert-text">{{ session('error') }}</span>
    </div>
@endif
        <form method="POST" action="{{ route('companies.api.setup.update', $company->id) }}">
            @csrf
            @method('PUT')

            <div class="api-grid">
                <div class="full">
                    <div class="field-label">Base URL</div>
                    <input type="text" class="field-input" name="base_url" value="{{ old('base_url', $company->companyConnection->base_url ?? '') }}" required>
                    <div class="field-help">Example: https://api.businesscentral.dynamics.com/v2.0/SandboxKH/api/v2.0</div>
                </div>

                <div>
                    <div class="field-label">Token URL</div>
                    <input type="text" class="field-input" name="token_url" value="{{ old('token_url', $company->companyConnection->token_url ?? '') }}" required>
                </div>

                <div>
                    <div class="field-label">API Scope</div>
                    <input type="text" class="field-input" name="api_scope" value="{{ old('api_scope', $company->companyConnection->api_scope ?? 'https://api.businesscentral.dynamics.com/.default') }}" required>
                </div>

                <div class="full">
                    <div class="field-label">Users/Customers List Endpoint</div>
                    <input type="text" class="field-input" name="customers_endpoint" value="{{ old('customers_endpoint', $company->companyConnection->customers_endpoint ?? 'customers?$select=id,number,displayName,email,phoneNumber') }}" required>
                    <div class="field-help">Used by user sync list.</div>
                </div>

                <div class="full">
                    <div class="field-label">Items List Endpoint</div>
                    <input type="text" class="field-input" name="items_endpoint" value="{{ old('items_endpoint', $company->companyConnection->items_endpoint ?? 'items?$filter=blocked eq false') }}" required>
                </div>

                <div>
                    <div class="field-label">Sales Order Create Endpoint</div>
                    <input type="text" class="field-input" name="sales_orders_endpoint" value="{{ old('sales_orders_endpoint', $company->companyConnection->sales_orders_endpoint ?? 'salesOrders') }}" required>
                </div>

                <div>
                    <div class="field-label">Sales Order Line Create Endpoint</div>
                    <input type="text" class="field-input" name="sales_order_lines_endpoint" value="{{ old('sales_order_lines_endpoint', $company->companyConnection->sales_order_lines_endpoint ?? 'salesOrders({salesOrderId})/salesOrderLines') }}" required>
                    <div class="field-help">Placeholder: <code>{salesOrderId}</code></div>
                </div>

                <div class="full">
                    <div class="field-label">Sales Order Search by Number Endpoint</div>
                    <input type="text" class="field-input" name="sales_orders_by_number_endpoint" value="{{ old('sales_orders_by_number_endpoint', $company->companyConnection->sales_orders_by_number_endpoint ?? $defaultSalesOrderByNumberEndpoint) }}" required>
                    <div class="field-help">Placeholder: <code>{documentNo}</code></div>
                </div>

                <div class="full">
                    <div class="field-label">PDF Print Endpoint</div>
                    <input type="text" class="field-input" name="sales_order_pdf_endpoint" value="{{ old('sales_order_pdf_endpoint', $company->companyConnection->sales_order_pdf_endpoint ?? 'salesOrders({salesOrderId})/pdfDocument/pdfDocumentContent') }}" required>
                    <div class="field-help">Placeholder: <code>{salesOrderId}</code></div>
                </div>

                <div class="full">
                    <label style="display:flex;align-items:center;gap:8px;">
                        <input type="checkbox" name="status" {{ old('status', $company->companyConnection->status ?? true) ? 'checked' : '' }}>
                        Connection Active
                    </label>
                </div>
            </div>

            <div class="action-row">
                <a href="{{ route('companies.index') }}" class="btn-light">Back to Company</a>
                <a href="{{ route('companies.edit', $company->id) }}" class="btn-light">Edit Company</a>
                <button type="submit" class="btn-main">Save API Setup</button>
            </div>
        </form>

        <div class="example-box">
            <strong>Placeholders you can use:</strong>
            <br><code>{salesOrderId}</code> for line/PDF endpoint, <code>{documentNo}</code> for order search, <code>{companyId}</code> if needed.
        </div>
    </div>
</div>

@endsection
<script>
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