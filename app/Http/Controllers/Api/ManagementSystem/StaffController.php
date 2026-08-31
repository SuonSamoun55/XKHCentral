<?php

namespace App\Http\Controllers\Api\ManagementSystem;

use App\Http\Controllers\Controller;
use App\Models\ManagementSystem\Company;
use App\Models\ManagementSystem\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    private function scopedStaffQuery(Request $request)
    {
        $query = User::where('bc_customer_no', 'like', 'STAFF-%');

        $companyId = $request->user()->company_id ?? session('selected_company_id');

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $staff = $this->scopedStaffQuery($request)->orderBy('name')->get();
        $actingCompanyId = $request->user()->company_id;
        $roles = Role::when($actingCompanyId, fn($q) => $q->where('company_id', $actingCompanyId))
            ->orderBy('name')
            ->get();
        $companies = Company::orderBy('name')->get();

        return view(
            'ManagementSystemViews.AdminViews.Layouts.StaffViews.StaffList',
            compact('staff', 'roles', 'companies')
        );
    }
    private function resolveCompanyId(Request $request): ?int
    {
        $actingUser = $request->user();

        return $actingUser->company_id
            ? $actingUser->company_id
            : ($request->input('company_id') ?: null);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
            'role' => ['required', 'string', 'max:50', 'exists:roles,name'],
            'company_id' => ['nullable', 'exists:companies,id'],
        ]);

        $staffCompanyId = $this->resolveCompanyId($request);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'role_id' => Role::where('name', $request->role)->where('company_id', $staffCompanyId)->value('id'),
            'company_id' => $staffCompanyId,
            'bc_customer_no' => 'STAFF-' . strtoupper(Str::random(10)),
            'status' => true,
            'linked_at' => now(),
        ]);

        return redirect()->route('staff.index')->with('success', 'Staff account created successfully.');
    }

    public function update(Request $request, $id)
    {
        $staff = $this->scopedStaffQuery($request)->findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $staff->id],
            'role' => ['required', 'string', 'max:50', 'exists:roles,name'],
            'company_id' => ['nullable', 'exists:companies,id'],
        ]);

        $staffCompanyId = $this->resolveCompanyId($request);

        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'role_id' => Role::where('name', $request->role)->where('company_id', $staffCompanyId)->value('id'),
            'company_id' => $staffCompanyId,
        ]);

        return redirect()->route('staff.index')->with('success', 'Staff account updated successfully.');
    }
    public function updatePassword(Request $request, $id)
    {
        $staff = $this->scopedStaffQuery($request)->findOrFail($id);
        $request->validate([
            'password' => ['required', 'min:6', 'confirmed'],
        ]);
        $staff->update([
            'password' => Hash::make($request->password),
        ]);
        return redirect()->route('staff.index')
            ->with('success', 'Password updated for ' . $staff->name . '.')
            ->with('new_password', $request->password)
            ->with('password_updated_for', $staff->name);
    }
    public function destroy(Request $request, $id)
    {
        $staff = $this->scopedStaffQuery($request)->findOrFail($id);

        if ((int) $staff->id === (int) $request->user()->id) {
            return redirect()->route('staff.index')->with('error', 'You cannot delete your own account.');
        }
        $staff->delete();
        return redirect()->route('staff.index')->with('success', 'Staff account deleted successfully.');
    }
}
