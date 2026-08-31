<?php

namespace App\Http\Controllers\Api\ManagementSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Permission;
use Database\Seeders\RoleAndPermissionSeeder;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('display_name')->get()->groupBy('group');

        return view('ManagementSystemViews.AdminViews.Layouts.PermissionsViews.PermissionView', compact('permissions'));
    }

    private function availablePageKeys(): array
    {
        $existingNames = Permission::pluck('name')->all();

        return array_diff_key(RoleAndPermissionSeeder::$pages, array_flip($existingNames));
    }

    public function create()
    {
        $availableKeys = $this->availablePageKeys();

        return view('ManagementSystemViews.AdminViews.Layouts.PermissionsViews.PermissionCreateView', compact('availableKeys'));
    }

    public function store(Request $request)
    {
        $availableKeys = $this->availablePageKeys();

        $request->validate([
            'name' => ['required', Rule::in(array_keys($availableKeys))],
            'display_name' => 'nullable|string|max:255',
        ]);

        $canonical = $availableKeys[$request->name];
        Permission::create([
            'name' => $request->name,
            'display_name' => $request->display_name ?: $canonical['label'],
            'group' => $canonical['group'],
            'urls' => $canonical['urls'] ?? null,
        ]);
        return redirect()->route('permissions.index')->with('success', 'Page added successfully.');
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('ManagementSystemViews.AdminViews.Layouts.PermissionsViews.PermissionEditView', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'display_name' => 'nullable|string|max:255',
            'group' => 'required|in:admin,customer',
        ]);
        $permission->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'group' => $request->group,
        ]);

        return redirect()->route('permissions.index')->with('success', 'Page updated successfully.');
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('permissions.index')->with('success', 'Page deleted successfully.');
    }
}
