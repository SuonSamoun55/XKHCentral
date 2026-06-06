<?php

namespace App\Models\POS;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'company_id',
        'order_no',
        'user_id',
        'customer_no',
        'currency_code',
        'currency_factor',
        'subtotal',
        'discount_amount',
        'total_amount',
        'location_code',
        'amount_paid',
        'status',
        'sync_status',
        'bc_order_id',
        'bc_document_no',
        'checked_out_at',
    ];

    protected $casts = [
        'checked_out_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\ManagementSystem\User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(\App\Models\POS\OrderItem::class, 'order_id');
    }

    public function actions()
    {
        return $this->hasMany(\App\Models\ManagementSystem\OrderAction::class, 'order_id');
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\ManagementSystem\Company::class, 'company_id');
    }
}
