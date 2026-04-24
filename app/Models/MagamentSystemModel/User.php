<?php

namespace App\Models\MagamentSystemModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\BcCustomer;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'profile_image',
        'profile_image_url',
        'password',
        'role',
        'role_id',
        'bc_customer_no',
        'company_id',
        'status',
        'linked_at',
        'last_seen_at',
        'avatar',
        'dob',
        'location',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'profile_image_display',
        'is_online',
        'offline_duration',
    ];

    protected $casts = [
        'status' => 'boolean',
        'linked_at' => 'datetime',
        'last_seen_at' => 'datetime',
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

    public function getProfileImageDisplayAttribute(): string
    {
        if (!empty($this->profile_image) && Storage::disk('public')->exists($this->profile_image)) {
            return asset('storage/' . $this->profile_image);
        }

        if (!empty($this->profile_image_url)) {
            return $this->profile_image_url;
        }

        $defaultPath = public_path('images/default-user.png');

        if (file_exists($defaultPath)) {
            return asset('images/default-user.png');
        }

        return asset('images/pos/Rectangle 2.png');
    }

    public function getIsOnlineAttribute(): bool
    {
        if (!$this->last_seen_at) {
            return false;
        }

        return $this->last_seen_at->gte(now()->subMinutes(5));
    }

    public function getOfflineDurationAttribute(): string
    {
        if (!$this->last_seen_at) {
            return 'Never online';
        }

        return $this->last_seen_at->diffForHumans();
    }

    public function scopeOnline($query)
    {
        return $query->whereNotNull('last_seen_at')
            ->where('last_seen_at', '>=', now()->subMinutes(5));
    }

    public function scopeOffline($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('last_seen_at')
              ->orWhere('last_seen_at', '<', now()->subMinutes(5));
        });
    }

    public function scopeOfflineLongTime($query, $days = 7)
    {
        return $query->where(function ($q) use ($days) {
            $q->whereNull('last_seen_at')
              ->orWhere('last_seen_at', '<', now()->subDays($days));
        });
    }
}
