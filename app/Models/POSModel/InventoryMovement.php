<?php

namespace App\Models\POSModel;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    protected $fillable = [
        'company_id',
        'item_id',
        'order_id',
        'actor_user_id',
        'buyer_user_id',
        'source',
        'quantity_change',
        'old_inventory',
        'new_inventory',
        'happened_at',
        'reference_no',
        'note',
    ];

    protected $casts = [
        'happened_at' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function actor()
    {
        return $this->belongsTo(\App\Models\MagamentSystemModel\User::class, 'actor_user_id');
    }

    public function buyer()
    {
        return $this->belongsTo(\App\Models\MagamentSystemModel\User::class, 'buyer_user_id');
    }
}

