<?php

namespace App\Models\MagamentSystemModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAction extends Model
{
    use HasFactory;

    protected $table = 'order_actions';

    protected $fillable = [
        'order_id',
        'user_id',
        'action_by',
        'action_type',
        'status',
        'note',
    ];

    public function order()
    {
        return $this->belongsTo(\App\Models\POSModel\Order::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\MagamentSystemModel\User::class, 'user_id');
    }

    public function actionBy()
    {
        return $this->belongsTo(\App\Models\MagamentSystemModel\User::class, 'action_by');
    }
}
