<?php

namespace App\Models\MagamentSystemModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'tax_number',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

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
        return $this->hasMany(\App\Models\POSModel\Order::class);
    }
}
