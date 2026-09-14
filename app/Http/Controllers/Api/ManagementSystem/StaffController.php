<?php

namespace App\Http\Controllers\Api\ManagementSystem;

use App\Http\Controllers\Controller;
use App\Models\ManagementSystem\Company;
use App\Models\ManagementSystem\User;
use App\Models\POS\NumberSeries;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    private function scopedStaffQuery(Request $request)
    {
        $query = User::where('bc_customer_no', 'like', 'STAFF-%');

        // Cross-company roles see/manage staff in every company; everyone
        // else stays locked to their own.
        if ($request->user()->canManageStaffAcrossCompanies()) {
            return $query;
        }

        $companyId = $request->user()->company_id ?? session('selected_company_id');

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $staff = $this->scopedStaffQuery($request)->with('company')->orderBy('name')->get();
        $actingUser = $request->user();
        $crossCompany = $actingUser->canManageStaffAcrossCompanies();

        // Cross-company staff managers need roles from every company (since
        // they can assign staff into any of them); everyone else only ever
        // sees roles belonging to their own company.
        $roles = Role::when(!$crossCompany && $actingUser->company_id, fn($q) => $q->where('company_id', $actingUser->company_id))
            ->orderBy('name')
            ->get();
        $companies = Company::orderBy('name')->get();

        return view(
            'ManagementSystemViews.AdminViews.Layouts.StaffViews.StaffList',
            compact('staff', 'roles', 'companies', 'crossCompany')
        );
    }
    private function resolveCompanyId(Request $request): ?int
    {
        $actingUser = $request->user();

        // Cross-company managers pick the target company explicitly (an empty
        // selection means "no company" / cross-tenant, same as a true global
        // user) instead of being pinned to their own company account.
        if ($actingUser->canManageStaffAcrossCompanies()) {
            return $request->input('company_id') ?: null;
        }

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
            'staff_no' => $this->generateStaffNo($staffCompanyId),
            'status' => true,
            'linked_at' => now(),
        ]);

        return redirect()->route('staff.index')->with('success', 'Staff account created successfully.');
    }

    /**
     * Best-effort staff number from the "STAFF" number series (Management >
     * Number Series). Left null if no such series is configured yet, rather
     * than blocking staff account creation.
     */
    private function generateStaffNo(?int $companyId): ?string
    {
        if (!$companyId) {
            return null;
        }

        try {
            return DB::transaction(fn () => NumberSeries::issue($companyId, 'STAFF'));
        } catch (\Throwable $e) {
            Log::warning('Could not assign staff number', [
                'company_id' => $companyId,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
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
