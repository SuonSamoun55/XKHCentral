<?php

namespace App\Models\POSModel;

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
        'status',
        'sync_status',
        'bc_document_no',
        'checked_out_at',
    ];

    protected $casts = [
        'checked_out_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(\App\Models\POSModel\OrderItem::class, 'order_id');
    }

    public function actions()
    {
        return $this->hasMany(\App\Models\MagamentSystemModel\OrderAction::class, 'order_id');
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\MagamentSystemModel\Company::class, 'company_id');
    }
}
