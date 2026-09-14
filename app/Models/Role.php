<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Permission;
use App\Models\ManagementSystem\Company;
use App\Models\ManagementSystem\User;

class Role extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'display_name',
        'is_cross_company',
    ];

    protected $casts = [
        'is_cross_company' => 'boolean',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
