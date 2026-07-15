<?php

namespace App\Models\ManagementSystem;

use App\Models\BcCustomer;
use App\Models\ManagementSystem\ChatMessage;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

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

    public function sentMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'receiver_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE HELPERS
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->roleRelation?->name === 'admin'
            || $this->role === 'admin';
    }

    public function hasPermission(string $permission): bool
    {
        return $this->roleRelation
            ? $this->roleRelation->permissions->contains('name', $permission)
            : false;
    }

    /*
    |--------------------------------------------------------------------------
    | ATTRIBUTES
    |--------------------------------------------------------------------------
    */

    public function getProfileImageDisplayAttribute(): string
    {
        if ($this->profile_image && Storage::disk('public')->exists($this->profile_image)) {
            return asset('storage/' . $this->profile_image);
        }

        return $this->profile_image_url
            ?? (file_exists(public_path('images/default-user.png'))
                ? asset('images/default-user.png')
                : asset('images/pos/Rectangle 2.png'));
    }

    public function getIsOnlineAttribute(): bool
    {
        return $this->last_seen_at
            ? $this->last_seen_at->gte(now()->subMinutes(5))
            : false;
    }

    public function getOfflineDurationAttribute(): string
    {
        return $this->last_seen_at
            ? $this->last_seen_at->diffForHumans()
            : 'Never online';
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeOnline($query)
    {
        return $query->where('last_seen_at', '>=', now()->subMinutes(5));
    }

    public function scopeOffline($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('last_seen_at')
              ->orWhere('last_seen_at', '<', now()->subMinutes(5));
        });
    }

    public function scopeOfflineLongTime($query, int $days = 7)
    {
        return $query->where(function ($q) use ($days) {
            $q->whereNull('last_seen_at')
              ->orWhere('last_seen_at', '<', now()->subDays($days));
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ALIAS
    |--------------------------------------------------------------------------
    */

    public function messages(): HasMany
    {
        return $this->sentMessages();
    }
}