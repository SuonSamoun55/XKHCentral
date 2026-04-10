<?php

namespace App\Http\Controllers\Api\ManagementSystemController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\MagamentSystemModel\Company;
use App\Models\MagamentSystemModel\CompanyConnection;

class CompanyController extends Controller
{
    public function index()
    {
        $selectedCompanyId = session('selected_company_id');
        $company = null;

        if ($selectedCompanyId) {
            $company = Company::with('companyConnection')->find($selectedCompanyId);
        }

        if (!$company) {
            $company = Company::with('companyConnection')->first();
        }

        return view(
            'ManagementSystemViews.AdminViews.Layouts.CompanyView.index',
            compact('company')
        );
    }

    public function create()
    {
        return redirect()->route('companies.index');
    }

    public function store(Request $request)
    {
        if (Company::exists()) {
            return redirect()->route('companies.index')
                ->with('error', 'Only one company is allowed.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'display_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
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
            'sales_orders_endpoint' => ['nullable', 'string'],
            'sales_order_lines_endpoint' => ['nullable', 'string'],
            'sales_orders_by_number_endpoint' => ['nullable', 'string'],
            'sales_order_pdf_endpoint' => ['nullable', 'string'],
        ]);

        $logoPath = null;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('company_logos', 'public');
        }

        $company = Company::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'logo' => $logoPath,
            'tax_number' => $validated['tax_number'] ?? null,
            'is_active' => true,
        ]);

        CompanyConnection::create([
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
            'sales_orders_endpoint' => $validated['sales_orders_endpoint'] ?? null,
            'sales_order_lines_endpoint' => $validated['sales_order_lines_endpoint'] ?? null,
            'sales_orders_by_number_endpoint' => $validated['sales_orders_by_number_endpoint'] ?? null,
            'sales_order_pdf_endpoint' => $validated['sales_order_pdf_endpoint'] ?? null,
            'is_default' => true,
            'status' => true,
        ]);

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
            'sales_orders_endpoint' => ['nullable', 'string'],
            'sales_order_lines_endpoint' => ['nullable', 'string'],
            'sales_orders_by_number_endpoint' => ['nullable', 'string'],
            'sales_order_pdf_endpoint' => ['nullable', 'string'],
            'status' => ['nullable'],
        ]);

        $logoPath = $company->logo;

        if ($request->hasFile('logo')) {
            if (!empty($company->logo) && Storage::disk('public')->exists($company->logo)) {
                Storage::disk('public')->delete($company->logo);
            }

            $logoPath = $request->file('logo')->store('company_logos', 'public');
        }

        $company->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'logo' => $logoPath,
            'tax_number' => $validated['tax_number'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        $connectionData = [
            'tenant_id' => $validated['tenant_id'],
            'client_id' => $validated['client_id'],
            'company_bc_id' => $validated['company_bc_id'],
            'environment' => $validated['environment'] ?? null,
            'base_url' => $validated['base_url'] ?? null,
            'token_url' => $validated['token_url'] ?? null,
            'api_scope' => $validated['api_scope'] ?? null,
            'customers_endpoint' => $validated['customers_endpoint'] ?? null,
            'items_endpoint' => $validated['items_endpoint'] ?? null,
            'sales_orders_endpoint' => $validated['sales_orders_endpoint'] ?? null,
            'sales_order_lines_endpoint' => $validated['sales_order_lines_endpoint'] ?? null,
            'sales_orders_by_number_endpoint' => $validated['sales_orders_by_number_endpoint'] ?? null,
            'sales_order_pdf_endpoint' => $validated['sales_order_pdf_endpoint'] ?? null,
            'status' => $request->has('status'),
            'is_default' => true,
        ];

        if (!empty($validated['client_secret'])) {
            $connectionData['client_secret'] = $validated['client_secret'];
        }

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
            'sales_orders_endpoint' => ['required', 'string'],
            'sales_order_lines_endpoint' => ['required', 'string'],
            'sales_orders_by_number_endpoint' => ['required', 'string'],
            'sales_order_pdf_endpoint' => ['required', 'string'],
            'status' => ['nullable'],
        ]);

        $connectionData = [
            'base_url' => trim($validated['base_url']),
            'token_url' => trim($validated['token_url']),
            'api_scope' => trim($validated['api_scope']),
            'customers_endpoint' => trim($validated['customers_endpoint']),
            'items_endpoint' => trim($validated['items_endpoint']),
            'sales_orders_endpoint' => trim($validated['sales_orders_endpoint']),
            'sales_order_lines_endpoint' => trim($validated['sales_order_lines_endpoint']),
            'sales_orders_by_number_endpoint' => trim($validated['sales_orders_by_number_endpoint']),
            'sales_order_pdf_endpoint' => trim($validated['sales_order_pdf_endpoint']),
            'status' => $request->has('status'),
            'is_default' => true,
        ];

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
}
