@extends('Layout.Management.app')

@section('title', 'Company API Setup')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/Management/Company/company_api.css') }}">
@endpush

@section('content')
    @php
        $defaultBaseUrl = '';
        $defaultTokenUrl = '';
        $defaultApiScope = 'https://api.businesscentral.dynamics.com/.default';
        $defaultCustomersEndpoint = 'Customers';
        $defaultItemsEndpoint = 'items';
        $defaultItemVariantsEndpoint = 'itemVariants';
        $defaultSalesOrdersEndpoint = 'salesOrders';
        $defaultSalesOrderLinesEndpoint = 'salesOrderLines';
        $defaultSalesOrderByNumberEndpoint = "salesOrders?\$filter=number eq '{documentNo}'&\$top=1";
        $defaultPostedSalesInvoiceEndpoint = 'postedSalesInvoices';
        $defaultPostedSalesInvoiceLinesEndpoint = 'postedSalesInvoiceLines';
    @endphp
    <div class="api-setup-wrap">
        <div class="api-card">
            <div class="api-title">Company API Setup</div>
            <div class="api-subtitle">Configure API endpoints per company. No code change needed when company/API changes.
            </div>

            @if (session('success'))
                <div class="custom-alert alert-success">
                    <span class="alert-text">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="custom-alert alert-danger">
                    <span class="alert-text">{{ session('error') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="custom-alert alert-danger">
                    <span class="alert-text">
                        <strong>Please fix the following:</strong>
                        <ul style="margin:6px 0 0 18px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </span>
                </div>
            @endif
            <form method="POST" action="{{ route('companies.api.setup.update', $company->id) }}" id="apiSetupForm">
                @csrf
                @method('PUT')

                <div class="api-grid">
                    <div class="full section-title">Connection</div>

                    <div class="full">
                        <div class="field-label">Base URL</div>
                        <input type="text" class="field-input" name="base_url"
                            placeholder="https://api.businesscentral.dynamics.com/v2.0/SandboxKH/api/XKH/LaravelAPI/v1.0"
                            data-default="{{ $defaultBaseUrl }}"
                            value="{{ old('base_url', $company->companyConnection->base_url ?? '') }}" required>
                    </div>

                    <div>
                        <div class="field-label">Token URL</div>
                        <input type="text" class="field-input" name="token_url"
                            placeholder="https://login.microsoftonline.com/{tenant-id}/oauth2/v2.0/token"
                            data-default="{{ $defaultTokenUrl }}"
                            value="{{ old('token_url', $company->companyConnection->token_url ?? '') }}" required>
                    </div>

                    <div>
                        <div class="field-label">API Scope</div>
                        <input type="text" class="field-input" name="api_scope"
                            placeholder="{{ $defaultApiScope }}"
                            data-default="{{ $defaultApiScope }}"
                            value="{{ old('api_scope', $company->companyConnection->api_scope ?? $defaultApiScope) }}"
                            required>
                    </div>

                    <div class="full">
                        <label class="connection-toggle">
                            <input type="checkbox" name="status" id="statusCheckbox"
                                {{ old('status', $company->companyConnection->status ?? true) ? 'checked' : '' }}>
                            Connection Active
                        </label>
                    </div>

                    <div class="full section-title">Product &amp; Customer Endpoints</div>

                    <div>
                        <div class="field-label">Users/Customers List Endpoint</div>
                        <input type="text" class="field-input" name="customers_endpoint"
                            placeholder="{{ $defaultCustomersEndpoint }}"
                            data-default="{{ $defaultCustomersEndpoint }}"
                            value="{{ old('customers_endpoint', $company->companyConnection->customers_endpoint ?? $defaultCustomersEndpoint) }}"
                            required>
                    </div>

                    <div>
                        <div class="field-label">Items List Endpoint</div>
                        <input type="text" class="field-input" name="items_endpoint"
                            placeholder="{{ $defaultItemsEndpoint }}"
                            data-default="{{ $defaultItemsEndpoint }}"
                            value="{{ old('items_endpoint', $company->companyConnection->items_endpoint ?? $defaultItemsEndpoint) }}"
                            required>
                    </div>

                    <div class="full">
                        <div class="field-label">Item Variants List Endpoint</div>
                        <input type="text" class="field-input" name="item_variants_endpoint"
                            placeholder="{{ $defaultItemVariantsEndpoint }}"
                            data-default="{{ $defaultItemVariantsEndpoint }}"
                            value="{{ old('item_variants_endpoint', $company->companyConnection->item_variants_endpoint ?? $defaultItemVariantsEndpoint) }}">
                    </div>

                    <div class="full section-title">Sales Order Endpoints</div>

                    <div>
                        <div class="field-label">Sales Order Create Endpoint</div>
                        <input type="text" class="field-input" name="sales_orders_endpoint"
                            placeholder="{{ $defaultSalesOrdersEndpoint }}"
                            data-default="{{ $defaultSalesOrdersEndpoint }}"
                            value="{{ old('sales_orders_endpoint', $company->companyConnection->sales_orders_endpoint ?? $defaultSalesOrdersEndpoint) }}"
                            required>
                    </div>

                    <div>
                        <div class="field-label">Sales Order Line Create Endpoint</div>
                        <input type="text" class="field-input" name="sales_order_lines_endpoint"
                            placeholder="{{ $defaultSalesOrderLinesEndpoint }}"
                            data-default="{{ $defaultSalesOrderLinesEndpoint }}"
                            value="{{ old('sales_order_lines_endpoint', $company->companyConnection->sales_order_lines_endpoint ?? $defaultSalesOrderLinesEndpoint) }}"
                            required>
                    </div>

                    <div class="full">
                        <div class="field-label">Sales Order Search by Number Endpoint</div>
                        <input type="text" class="field-input" name="sales_orders_by_number_endpoint"
                            placeholder="{{ $defaultSalesOrderByNumberEndpoint }}"
                            data-default="{{ $defaultSalesOrderByNumberEndpoint }}"
                            value="{{ old('sales_orders_by_number_endpoint', $company->companyConnection->sales_orders_by_number_endpoint ?? $defaultSalesOrderByNumberEndpoint) }}"
                            required>
                    </div>

                    <div class="full section-title">Invoice Endpoints</div>

                    <div>
                        <div class="field-label">Posted Sales Invoice Lookup Endpoint</div>
                        <input type="text" class="field-input" name="posted_sales_invoice_endpoint"
                            placeholder="{{ $defaultPostedSalesInvoiceEndpoint }}"
                            data-default="{{ $defaultPostedSalesInvoiceEndpoint }}"
                            value="{{ old('posted_sales_invoice_endpoint', $company->companyConnection->posted_sales_invoice_endpoint ?? $defaultPostedSalesInvoiceEndpoint) }}">
                    </div>

                    <div>
                        <div class="field-label">Posted Sales Invoice Lines Endpoint</div>
                        <input type="text" class="field-input" name="posted_sales_invoice_lines_endpoint"
                            placeholder="{{ $defaultPostedSalesInvoiceLinesEndpoint }}"
                            data-default="{{ $defaultPostedSalesInvoiceLinesEndpoint }}"
                            value="{{ old('posted_sales_invoice_lines_endpoint', $company->companyConnection->posted_sales_invoice_lines_endpoint ?? $defaultPostedSalesInvoiceLinesEndpoint) }}">
                    </div>

                </div>

                <div class="action-row">
                    <a href="{{ route('companies.index') }}" class="btn-light">Back to Company</a>
                    <a href="{{ route('companies.edit', $company->id) }}" class="btn-light">Edit Company</a>
                    <button type="button" class="btn-light" id="resetDefaultsBtn">Reset to Default</button>
                    <button type="submit" class="btn-main">Save API Setup</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.custom-alert');

            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.animation = 'fadeOut 0.5s ease-in forwards';
                    alert.addEventListener('animationend', function() {
                        alert.remove();
                    });
                }, 4000);
            });

            const resetBtn = document.getElementById('resetDefaultsBtn');
            const form = document.getElementById('apiSetupForm');

            resetBtn?.addEventListener('click', function() {
                form.querySelectorAll('.field-input[data-default]').forEach(function(input) {
                    input.value = input.dataset.default;
                });

                const statusCheckbox = document.getElementById('statusCheckbox');
                if (statusCheckbox) statusCheckbox.checked = true;
            });
        });
    </script>
@endpush
