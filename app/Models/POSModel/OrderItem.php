<?php

namespace App\Models\POSModel;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'company_id',
        'item_id',
        'item_no',
        'item_name',
        'qty',
        'unit_price',
        'line_total',
        'location_code',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function item()
    {
        return $this->belongsTo(\App\Models\POSModel\Item::class, 'item_id');
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\MagamentSystemModel\Company::class, 'company_id');
    }
}
