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
        $companies = Company::with('connection')->latest()->get();

        return view('ManagementSystemViews.AdminViews.Layouts.CompanyView.index', compact('companies'));
    }

    public function create()
    {
        return view('ManagementSystemViews.AdminViews.Layouts.CompanyView.create');
    }

public function store(Request $request)
{
    $validated = $request->validate([
        // Company
        'name'            => ['required', 'string', 'max:255'],
        'display_name'    => ['nullable', 'string', 'max:255'],
        'phone'           => ['nullable', 'string', 'max:50'],
        'email'           => ['nullable', 'email', 'max:255'],
        'address'         => ['nullable', 'string'],
        'logo'            => ['nullable', 'string', 'max:255'],
        'tax_number'      => ['nullable', 'string', 'max:100'],

        // Business Central
        'tenant_id'       => ['required', 'string'],
        'client_id'       => ['required', 'string'],
        'client_secret'   => ['required', 'string'],
        'company_bc_id'   => ['required', 'string'],
        'environment'     => ['nullable', 'string'],
        'base_url'        => ['nullable', 'string'],
        'token_url'       => ['nullable', 'string'],

        // checkbox (no boolean validation here)
        'is_default'      => ['nullable'],
        'status'          => ['nullable'],
        'is_active'       => ['nullable'],
    ]);

    // ✅ FIX checkbox values
    $isDefault = $request->has('is_default') ? 1 : 0;
    $status    = $request->has('status') ? 1 : 0;
    $isActive  = $request->has('is_active') ? 1 : 0;

    // ✅ If set default → reset others
    if ($isDefault) {
        \App\Models\MagamentSystemModel\CompanyConnection::query()
            ->update(['is_default' => 0]);
    }

    // ✅ Create company
    $company = \App\Models\MagamentSystemModel\Company::create([
        'name'         => $validated['name'],
        'display_name' => $validated['display_name'] ?? null,
        'phone'        => $validated['phone'] ?? null,
        'email'        => $validated['email'] ?? null,
        'address'      => $validated['address'] ?? null,
        'logo'         => $validated['logo'] ?? null,
        'tax_number'   => $validated['tax_number'] ?? null,
        'is_active'    => $isActive,
    ]);

    // ✅ Create connection
    \App\Models\MagamentSystemModel\CompanyConnection::create([
        'company_id'     => $company->id,
        'tenant_id'      => $validated['tenant_id'],
        'client_id'      => $validated['client_id'],
        'client_secret'  => $validated['client_secret'], // auto encrypted in model
        'company_bc_id'  => $validated['company_bc_id'],
        'environment'    => $validated['environment'] ?? null,
        'base_url'       => $validated['base_url'] ?? null,
        'token_url'      => $validated['token_url'] ?? null,
        'is_default'     => $isDefault,
        'status'         => $status,
    ]);

    return redirect()
        ->route('companies.index')
        ->with('success', 'Company created successfully.');
}
}
