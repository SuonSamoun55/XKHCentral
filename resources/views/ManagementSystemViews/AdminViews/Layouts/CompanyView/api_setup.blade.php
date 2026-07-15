@extends('ManagementSystemViews.AdminViews.Layouts.app')
<link rel="stylesheet" href="{{ asset('css/pos/admin/company_api.css') }}">

@section('title', 'Company API Setup')

@push('styles')
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
                    <div class="field-help">Use only the API base here: https://api.businesscentral.dynamics.com/v2.0/SandboxKH/api/samoun/sale/v1.0</div>
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
                    <input type="text" class="field-input" name="customers_endpoint" value="{{ old('customers_endpoint', $company->companyConnection->customers_endpoint ?? 'Customers') }}" required>
                    <div class="field-help">Use <code>Customers</code>. If you paste the full Customers URL by mistake, Laravel will convert it when saving.</div>
                </div>

                <div class="full">
                    <div class="field-label">Items List Endpoint</div>
                    <input type="text" class="field-input" name="items_endpoint" value="{{ old('items_endpoint', $company->companyConnection->items_endpoint ?? 'items') }}" required>
                </div>

                <div>
                    <div class="field-label">Sales Order Create Endpoint</div>
                    <input type="text" class="field-input" name="sales_orders_endpoint" value="{{ old('sales_orders_endpoint', $company->companyConnection->sales_orders_endpoint ?? 'salesOrders') }}" required>
                </div>

                <div>
                    <div class="field-label">Sales Order Line Create Endpoint</div>
                    <input type="text" class="field-input" name="sales_order_lines_endpoint" value="{{ old('sales_order_lines_endpoint', $company->companyConnection->sales_order_lines_endpoint ?? 'salesOrderLines') }}" required>
                    <div class="field-help">Use <code>salesOrderLines</code> when your custom API exposes lines as their own page. Use <code>salesOrders({salesOrderId})/salesOrderLines</code> only if the AL API exposes a nested line part.</div>
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
                    <div class="field-label">Posted Sales Invoice Lookup Endpoint</div>
                    <input type="text" class="field-input" name="posted_sales_invoice_endpoint" value="{{ old('posted_sales_invoice_endpoint', $company->companyConnection->posted_sales_invoice_endpoint ?? 'postedSalesInvoices') }}">
                </div>

                <div>
                    <div class="field-label">Posted Sales Invoice Lines Endpoint</div>
                    <input type="text" class="field-input" name="posted_sales_invoice_lines_endpoint" value="{{ old('posted_sales_invoice_lines_endpoint', $company->companyConnection->posted_sales_invoice_lines_endpoint ?? 'postedSalesInvoiceLines') }}">
                </div>

                <div>
                    <div class="field-label">Posted Sales Invoice PDF Endpoint</div>
                    <input type="text" class="field-input" name="posted_sales_invoice_pdf_endpoint" value="{{ old('posted_sales_invoice_pdf_endpoint', $company->companyConnection->posted_sales_invoice_pdf_endpoint ?? 'postedSalesInvoices({invoiceId})/pdfDocument/pdfDocumentContent') }}">
                    <div class="field-help">Placeholder: <code>{invoiceId}</code></div>
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
