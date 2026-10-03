<?php

namespace App\Models\ManagementSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'phone',
        'email',
        'address',
        'logo',
        'favicon',
        'tax_number',
        'is_active',
        'is_test',
        'cloned_from_id',
        'staff_email_tag',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_test' => 'boolean',
    ];

    public function clonedFrom(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'cloned_from_id');
    }

    public function companyConnection(): HasOne
    {
        return $this->hasOne(CompanyConnection::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function bcCustomers(): HasMany
    {
        return $this->hasMany(\App\Models\BcCustomer::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(\App\Models\POS\Order::class);
    }
}
