<?php

namespace App\Http\Controllers\Api\ManagementSystemController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MagamentSystemModel\Company;
use App\Models\MagamentSystemModel\CompanyConnection;

class CompanyController extends Controller
{
    public function index()
    {
        $company = Company::with('companyConnection')->first();

        return view(
            'ManagementSystemViews.AdminViews.Layouts.CompanyView.index',
            compact('company')
        );
    }

    public function create()
    {
        if (Company::exists()) {
            return redirect()->route('companies.index')
                ->with('error', 'Company already exists.');
        }

        return view('ManagementSystemViews.AdminViews.Layouts.CompanyView.create');
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
            'logo' => ['nullable', 'string', 'max:255'],
            'tax_number' => ['nullable', 'string', 'max:100'],

            'tenant_id' => ['required', 'string'],
            'client_id' => ['required', 'string'],
            'client_secret' => ['required', 'string'],
            'company_bc_id' => ['required', 'string'],
            'environment' => ['nullable', 'string'],
            'base_url' => ['nullable', 'string'],
            'token_url' => ['nullable', 'string'],
        ]);

        $company = Company::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'logo' => $validated['logo'] ?? null,
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
            'is_default' => true,
            'status' => true,
        ]);

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
            'logo' => ['nullable', 'string', 'max:255'],
            'tax_number' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable'],

            'tenant_id' => ['required', 'string'],
            'client_id' => ['required', 'string'],
            'client_secret' => ['nullable', 'string'],
            'company_bc_id' => ['required', 'string'],
            'environment' => ['nullable', 'string'],
            'base_url' => ['nullable', 'string'],
            'token_url' => ['nullable', 'string'],
            'status' => ['nullable'],
        ]);

        $company->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'logo' => $validated['logo'] ?? null,
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

        return redirect()->route('companies.index')
            ->with('success', 'Company updated successfully.');
    }

    public function destroy($id)
    {
        $company = Company::with('companyConnection')->findOrFail($id);

        if ($company->companyConnection) {
            $company->companyConnection->delete();
        }

        $company->delete();

        return redirect()->route('companies.index')
            ->with('success', 'Company deleted successfully.');
    }
}
