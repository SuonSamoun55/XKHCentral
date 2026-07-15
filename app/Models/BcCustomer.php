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
        'name',
        'display_name',
        'email',
        'phone',
        'phone_number',
        'address',
        'profile_image_url',
        'connect_status',
        'last_synced_at',
    ];

    protected $casts = [
        'last_synced_at' => 'datetime',
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
