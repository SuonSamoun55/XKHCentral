<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BcCustomer extends Model
{
    use HasFactory;

    protected $table = 'bc_customers';

    protected $fillable = [
        'company_id',
        'bc_id',
        'bc_customer_no',
        'local_customer_no',
        'name',
        'display_name',
        'email',
        'phone',
        'phone_number',
        'mobile_phone_no',
        'address',
        'city',
        'payment_terms_code',
        'customer_price_group',
        'location_code',
        'ship_to_code',
        'blocked',
        'balance',
        'balance_due',
        'credit_limit',
        'profile_image_url',
        'connect_status',
        'last_synced_at',
    ];

    protected $casts = [
        'last_synced_at' => 'datetime',
        'balance' => 'decimal:2',
        'balance_due' => 'decimal:2',
        'credit_limit' => 'decimal:2',
    ];

    public function user()
    {
        return $this->hasOne(
            \App\Models\ManagementSystem\User::class,
            'bc_customer_no',
            'bc_customer_no'
        );
    }

    public function company()
    {
        return $this->belongsTo(
            \App\Models\ManagementSystem\Company::class,
            'company_id'
        );
    }
}
