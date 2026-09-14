<?php

namespace App\Http\Controllers\Api\ManagementSystem;

use App\Http\Controllers\Controller;
use App\Models\Permission;

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
}
