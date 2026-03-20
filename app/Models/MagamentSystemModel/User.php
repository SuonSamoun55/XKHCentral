<?php

namespace App\Models\MagamentSystemModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\BcCustomer;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'role_id',
        'bc_customer_no',
        'company_id',
        'status',
        'linked_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'status' => 'boolean',
        'linked_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function bcCustomer(): BelongsTo
    {
        return $this->belongsTo(BcCustomer::class, 'bc_customer_no', 'bc_customer_no');
    }

    public function roleRelation(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function hasPermission($permissionName): bool
    {
        if (!$this->roleRelation) {
            return false;
        }

        return $this->roleRelation->permissions->contains('name', $permissionName);
    }

    public function isAdmin(): bool
    {
        if ($this->roleRelation) {
            return $this->roleRelation->name === 'admin';
        }

        return $this->role === 'admin';
    }
}
