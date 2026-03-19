<?php

namespace App\Models\MagamentSystemModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function connection(): HasOne
    {
        return $this->hasOne(CompanyConnection::class);
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\POSModel\Order::class);
    }
}
