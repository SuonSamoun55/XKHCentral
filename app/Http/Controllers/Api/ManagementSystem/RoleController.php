<?php

namespace App\Http\Controllers\Api\ManagementSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Role;
use App\Models\Permission;

class RoleController extends Controller
{
    private function requireSelectedCompanyId(): int
    {
        $companyId = session('selected_company_id');

        if (!$companyId) {
            abort(redirect()->route('companies.index')
                ->with('error', 'Select a company first to manage its roles.'));
        }

        return $companyId;
    }

    public function index()
    {
        $companyId = $this->requireSelectedCompanyId();

        $roles = Role::with('permissions')
            ->where('company_id', $companyId)
            ->latest()
            ->get();

        return view('ManagementSystemViews.AdminViews.Layouts.RolesViews.RoleView', compact('roles'));
    }

    public function create()
    {
        $this->requireSelectedCompanyId();
        $permissions = Permission::orderBy('display_name')->get()->groupBy('group');
        return view('ManagementSystemViews.AdminViews.Layouts.RolesViews.RoleCreateView', compact('permissions'));
    }

    public function store(Request $request)
    {
        $companyId = $this->requireSelectedCompanyId();
        $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('roles')->where(fn ($q) => $q->where('company_id', $companyId)),
            ],
            'display_name' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        $role = Role::create([
            'company_id' => $companyId,
            'name' => $request->name,
            'display_name' => $request->display_name,
        ]);

        $role->permissions()->sync($request->permissions ?? []);
        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit($id)
    {
        $companyId = $this->requireSelectedCompanyId();

        $role = Role::with('permissions')->where('company_id', $companyId)->findOrFail($id);
        $permissions = Permission::orderBy('display_name')->get()->groupBy('group');

        return view('ManagementSystemViews.AdminViews.Layouts.RolesViews.Roleedit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $companyId = $this->requireSelectedCompanyId();
        $role = Role::where('company_id', $companyId)->findOrFail($id);

        $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('roles')->where(fn ($q) => $q->where('company_id', $companyId))->ignore($role->id),
            ],
            'display_name' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        $role->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
        ]);
        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy($id)
    {
        $companyId = $this->requireSelectedCompanyId();
        $role = Role::where('company_id', $companyId)->findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
