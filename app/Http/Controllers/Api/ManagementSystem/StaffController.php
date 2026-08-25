<?php

namespace App\Http\Controllers\Api\ManagementSystem;

use App\Http\Controllers\Controller;
use App\Models\ManagementSystem\Company;
use App\Models\ManagementSystem\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class StaffController extends Controller
{
    private function scopedStaffQuery(Request $request)
    {
        $query = User::where('bc_customer_no', 'like', 'STAFF-%');

        if ($request->user()->company_id) {
            $query->where('company_id', $request->user()->company_id);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $staff = $this->scopedStaffQuery($request)->orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
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

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'role_id' => Role::where('name', $request->role)->value('id'),
            'company_id' => $this->resolveCompanyId($request),
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

        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'role_id' => Role::where('name', $request->role)->value('id'),
            'company_id' => $this->resolveCompanyId($request),
        ]);

        return redirect()->route('staff.index')->with('success', 'Staff account updated successfully.');
    }

    /**
     * Resetting a staff member's password requires the ACTING admin to
     * re-enter their OWN current password first — same re-authentication
     * pattern as AdminProfileController::updatePassword() (the admin's own
     * self-service Change Password page), just applied here to authorize a
     * sensitive action on someone else's account instead of your own.
     */
    public function updatePassword(Request $request, $id)
    {
        $staff = $this->scopedStaffQuery($request)->findOrFail($id);

        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $actingUser = $request->user();

        if (!Hash::check($request->current_password, $actingUser->password)) {
            return back()->withErrors(['current_password' => 'Your current password is incorrect.']);
        }

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
