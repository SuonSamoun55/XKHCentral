<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BcCustomer extends Model
{
    use HasFactory;

    protected $table = 'bc_customers';

    protected $fillable = [
        'bc_customer_no',
        'name',
        'email',
        'phone',
        'address',
        'connect_status',
        'last_synced_at',
    ];

    protected $casts = [
        'last_synced_at' => 'datetime',
    ];

    public function user()
    {
        return $this->hasOne(
            \App\Models\MagamentSystemModel\User::class,
            'bc_customer_no',
            'bc_customer_no'
        );
    }
}
