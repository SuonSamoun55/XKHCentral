<?php

namespace App\Http\Controllers\Api\ManagementSystem;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Database\Seeders\RoleAndPermissionSeeder;

class PermissionController extends Controller
{
    /**
     * Read-only directory of the app's own permissions/route groups —
     * these come from Database\Seeders\RoleAndPermissionSeeder::$pages and
     * are tightly coupled to the permission:* route middleware, so they're
     * not something to create/edit/delete from here; they already exist.
     */
    public function index()
    {
        $permissions = Permission::orderBy('display_name')->get()->groupBy('group');

        return view('ManagementSystemViews.AdminViews.Layouts.PermissionsViews.PermissionView', compact('permissions'));
    }

    /**
     * Re-runs RoleAndPermissionSeeder so any page added to its $pages array
     * (in code) but never seeded on this environment gets created/updated
     * here without needing a fresh deploy or manual `artisan db:seed`.
     */
    public function sync()
    {
        (new RoleAndPermissionSeeder())->run();

        return redirect()->route('permissions.index')
            ->with('success', 'Pages synced — ' . count(RoleAndPermissionSeeder::$pages) . ' page(s) up to date.');
    }
}
