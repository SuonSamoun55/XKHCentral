<?php

namespace App\Models\POSModel;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'item_id',
        'item_no',
        'item_name',
        'qty',
        'unit_price',
        'line_total',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
public function item()
{
    return $this->belongsTo(\App\Models\POSModel\Item::class, 'item_id');
}
}
