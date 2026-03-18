<?php

namespace App\Models\MagamentSystemModel;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'order_id',
        'item_id',
        'type',
        'title',
        'message',
        'is_read',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(\App\Models\POSModel\Order::class);
    }

    public function item()
    {
        return $this->belongsTo(\App\Models\POSModel\Item::class);
    }
}
