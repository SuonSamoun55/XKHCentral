<?php

namespace App\Http\Controllers\Api\ManagementSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use App\Models\ManagementSystem\Company;
use App\Models\ManagementSystem\CompanyConnection;
use App\Models\ManagementSystem\User;
class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::with('companyConnection')
            ->withCount(['users as users_count' => function ($query) {
                $query->where('bc_customer_no', 'not like', 'STAFF-%')
                    ->where('status', true);
            }])
            ->latest()
            ->get();

        $selectedCompanyId = session('selected_company_id');

        return view(
            'ManagementSystemViews.AdminViews.Layouts.CompanyView.index',
            compact('companies', 'selectedCompanyId')
        );
    }
    public function create()
    {
        return view('ManagementSystemViews.AdminViews.Layouts.CompanyView.create');
    }

    public function select($id)
    {
        $company = Company::findOrFail($id);

        session(['selected_company_id' => $company->id]);

        /** @var \App\Models\ManagementSystem\User $user */
        $user = auth()->user();
        $user->update(['last_company_id' => $company->id]);

        return redirect()->route('pos.index')
            ->with('success', 'Now viewing ' . ($company->display_name ?? $company->name) . '.');
    }
    public function clearSelection()
    {
        session()->forget('selected_company_id');

        return redirect()->route('companies.index')
            ->with('success', 'Viewing all companies.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'display_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'favicon' => ['nullable', 'mimes:jpg,jpeg,png,webp,ico', 'max:512'],
            'tax_number' => ['nullable', 'string', 'max:100'],

            'tenant_id' => ['required', 'string'],
            'client_id' => ['required', 'string'],
            'client_secret' => ['required', 'string'],
            'company_bc_id' => ['required', 'string'],
            'environment' => ['nullable', 'string'],
            'base_url' => ['nullable', 'string'],
            'token_url' => ['nullable', 'string'],
            'api_scope' => ['nullable', 'string'],
            'customers_endpoint' => ['nullable', 'string'],
            'items_endpoint' => ['nullable', 'string'],
            'item_variants_endpoint' => ['nullable', 'string'],
            'sales_orders_endpoint' => ['nullable', 'string'],
            'sales_order_lines_endpoint' => ['nullable', 'string'],
            'sales_orders_by_number_endpoint' => ['nullable', 'string'],
            'posted_sales_invoice_endpoint' => ['nullable', 'string'],
            'posted_sales_invoice_lines_endpoint' => ['nullable', 'string'],
        ]);

        $logoPath = null;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('company_logos', 'public');
        }

        $faviconPath = null;

        if ($request->hasFile('favicon')) {
            $faviconPath = $request->file('favicon')->store('company_favicons', 'public');
        }

        $company = Company::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'logo' => $logoPath,
            'favicon' => $faviconPath,
            'tax_number' => $validated['tax_number'] ?? null,
            'is_active' => true,
        ]);

        $connectionData = [
            'company_id' => $company->id,
            'tenant_id' => $validated['tenant_id'],
            'client_id' => $validated['client_id'],
            'client_secret' => $validated['client_secret'],
            'company_bc_id' => $validated['company_bc_id'],
            'environment' => $validated['environment'] ?? null,
            'base_url' => $validated['base_url'] ?? null,
            'token_url' => $validated['token_url'] ?? null,
            'api_scope' => $validated['api_scope'] ?? null,
            'customers_endpoint' => $validated['customers_endpoint'] ?? null,
            'items_endpoint' => $validated['items_endpoint'] ?? null,
            'item_variants_endpoint' => $validated['item_variants_endpoint'] ?? null,
            'sales_orders_endpoint' => $validated['sales_orders_endpoint'] ?? null,
            'sales_order_lines_endpoint' => $validated['sales_order_lines_endpoint'] ?? null,
            'sales_orders_by_number_endpoint' => $validated['sales_orders_by_number_endpoint'] ?? null,
            'posted_sales_invoice_endpoint' => $validated['posted_sales_invoice_endpoint'] ?? null,
            'posted_sales_invoice_lines_endpoint' => $validated['posted_sales_invoice_lines_endpoint'] ?? null,
            'is_default' => true,
            'status' => true,
        ];

        CompanyConnection::create($this->filterConnectionDataByExistingColumns($connectionData));

        session(['selected_company_id' => $company->id]);

        return redirect()->route('companies.index')
            ->with('success', 'Company created successfully.');
    }

    public function edit($id)
    {
        $company = Company::with('companyConnection')->findOrFail($id);

        return view(
            'ManagementSystemViews.AdminViews.Layouts.CompanyView.edit',
            compact('company')
        );
    }

    public function update(Request $request, $id)
    {
        $company = Company::with('companyConnection')->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'display_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'favicon' => ['nullable', 'mimes:jpg,jpeg,png,webp,ico', 'max:512'],
            'remove_favicon' => ['nullable'],
            'tax_number' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable'],

            'tenant_id' => ['required', 'string'],
            'client_id' => ['required', 'string'],
            'client_secret' => ['nullable', 'string'],
            'company_bc_id' => ['required', 'string'],
            'environment' => ['nullable', 'string'],
            'base_url' => ['nullable', 'string'],
            'token_url' => ['nullable', 'string'],
            'api_scope' => ['nullable', 'string'],
            'customers_endpoint' => ['nullable', 'string'],
            'items_endpoint' => ['nullable', 'string'],
            'item_variants_endpoint' => ['nullable', 'string'],
            'sales_orders_endpoint' => ['nullable', 'string'],
            'sales_order_lines_endpoint' => ['nullable', 'string'],
            'sales_orders_by_number_endpoint' => ['nullable', 'string'],
            'posted_sales_invoice_endpoint' => ['nullable', 'string'],
            'posted_sales_invoice_lines_endpoint' => ['nullable', 'string'],
            'status' => ['nullable'],
        ]);

        $logoPath = $company->logo;

        if ($request->hasFile('logo')) {
            if (!empty($company->logo) && Storage::disk('public')->exists($company->logo)) {
                Storage::disk('public')->delete($company->logo);
            }

            $logoPath = $request->file('logo')->store('company_logos', 'public');
        }

        $faviconPath = $company->favicon;

        if ($request->hasFile('favicon')) {
            if (!empty($company->favicon) && Storage::disk('public')->exists($company->favicon)) {
                Storage::disk('public')->delete($company->favicon);
            }

            $faviconPath = $request->file('favicon')->store('company_favicons', 'public');
        } elseif ($request->boolean('remove_favicon')) {
            if (!empty($company->favicon) && Storage::disk('public')->exists($company->favicon)) {
                Storage::disk('public')->delete($company->favicon);
            }

            $faviconPath = null;
        }

        $company->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'logo' => $logoPath,
            'favicon' => $faviconPath,
            'tax_number' => $validated['tax_number'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        $connectionData = [
            'tenant_id' => $validated['tenant_id'],
            'client_id' => $validated['client_id'],
            'company_bc_id' => $validated['company_bc_id'],
            'status' => $request->has('status'),
            'is_default' => true,
        ];

        foreach (['environment', 'base_url', 'token_url'] as $connectionField) {
            if ($request->has($connectionField)) {
                $connectionData[$connectionField] = $validated[$connectionField] ?? null;
            }
        }

        foreach ([
            'api_scope',
            'customers_endpoint',
            'items_endpoint',
            'item_variants_endpoint',
            'sales_orders_endpoint',
            'sales_order_lines_endpoint',
            'sales_orders_by_number_endpoint',
            'posted_sales_invoice_endpoint',
            'posted_sales_invoice_lines_endpoint',
        ] as $endpointField) {
            if ($request->has($endpointField)) {
                $connectionData[$endpointField] = $validated[$endpointField] ?? null;
            }
        }

        if (!empty($validated['client_secret'])) {
            $connectionData['client_secret'] = $validated['client_secret'];
        }

        $connectionData = $this->filterConnectionDataByExistingColumns($connectionData);

        if ($company->companyConnection) {
            $company->companyConnection->update($connectionData);
        } else {
            $connectionData['company_id'] = $company->id;
            CompanyConnection::create($connectionData);
        }

        session(['selected_company_id' => $company->id]);

        return redirect()->route('companies.index')
            ->with('success', 'Company updated successfully.');
    }

    public function apiSetup($id)
    {
        $company = Company::with('companyConnection')->findOrFail($id);

        return view(
            'ManagementSystemViews.AdminViews.Layouts.CompanyView.api_setup',
            compact('company')
        );
    }

    public function updateApiSetup(Request $request, $id)
    {
        $company = Company::with('companyConnection')->findOrFail($id);

        $validated = $request->validate([
            'base_url' => ['required', 'string'],
            'token_url' => ['required', 'string'],
            'api_scope' => ['required', 'string'],
            'customers_endpoint' => ['required', 'string'],
            'items_endpoint' => ['required', 'string'],
            'item_variants_endpoint' => ['nullable', 'string'],
            'sales_orders_endpoint' => ['required', 'string'],
            'sales_order_lines_endpoint' => ['required', 'string'],
            'sales_orders_by_number_endpoint' => ['required', 'string'],
            'posted_sales_invoice_endpoint' => ['nullable', 'string'],
            'posted_sales_invoice_lines_endpoint' => ['nullable', 'string'],
            'status' => ['nullable'],
        ]);

        [$baseUrl, $customersEndpoint] = $this->normalizeBusinessCentralApiInput(
            trim($validated['base_url']),
            trim($validated['customers_endpoint'])
        );

        $connectionData = [
            'base_url' => $baseUrl,
            'token_url' => trim($validated['token_url']),
            'api_scope' => trim($validated['api_scope']),
            'customers_endpoint' => $customersEndpoint,
            'items_endpoint' => trim($validated['items_endpoint']),
            'item_variants_endpoint' => trim($validated['item_variants_endpoint'] ?? ''),
            'sales_orders_endpoint' => trim($validated['sales_orders_endpoint']),
            'sales_order_lines_endpoint' => trim($validated['sales_order_lines_endpoint']),
            'sales_orders_by_number_endpoint' => trim($validated['sales_orders_by_number_endpoint']),
            'posted_sales_invoice_endpoint' => trim($validated['posted_sales_invoice_endpoint'] ?? ''),
            'posted_sales_invoice_lines_endpoint' => trim($validated['posted_sales_invoice_lines_endpoint'] ?? ''),
            'status' => $request->has('status'),
            'is_default' => true,
        ];

        $connectionData = $this->filterConnectionDataByExistingColumns($connectionData);

        if ($company->companyConnection) {
            $company->companyConnection->update($connectionData);
        } else {
            return redirect()
                ->route('companies.edit', $company->id)
                ->with('error', 'Please complete basic BC credentials first in Edit Company, then configure API Setup.');
        }

        session(['selected_company_id' => $company->id]);

        return redirect()
            ->route('companies.api.setup', $company->id)
            ->with('success', 'Company API setup updated successfully.');
    }

    public function destroy($id)
    {
        $company = Company::with('companyConnection')->findOrFail($id);

        if (!empty($company->logo) && Storage::disk('public')->exists($company->logo)) {
            Storage::disk('public')->delete($company->logo);
        }

        if (!empty($company->favicon) && Storage::disk('public')->exists($company->favicon)) {
            Storage::disk('public')->delete($company->favicon);
        }

        if ($company->companyConnection) {
            $company->companyConnection->delete();
        }

        if (session('selected_company_id') == $company->id) {
            session()->forget('selected_company_id');
        }

        $company->delete();

        return redirect()->route('companies.index')
            ->with('success', 'Company deleted successfully.');
    }

    private function filterConnectionDataByExistingColumns(array $data): array
    {
        static $columns = null;

        if ($columns === null) {
            $columns = array_flip(Schema::getColumnListing('company_connections'));
        }

        return array_intersect_key($data, $columns);
    }

    private function normalizeBusinessCentralApiInput(string $baseUrl, string $customersEndpoint): array
    {
        $fullCustomerPattern = '#^(https?://.+?/api/[^/]+/[^/]+/v[0-9.]+)/companies\(([^)]+)\)/(Customers)(?:\?.*)?$#i';

        foreach (['baseUrl' => $baseUrl, 'customersEndpoint' => $customersEndpoint] as $field => $value) {
            if (!preg_match($fullCustomerPattern, $value, $matches)) {
                continue;
            }

            $baseUrl = $matches[1];
            $customersEndpoint = $matches[3];
        }

        return [rtrim($baseUrl, '/'), ltrim($customersEndpoint, '/')];
    }
}
