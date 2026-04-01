<?php

namespace App\Models\POSModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class OrderHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'total_amount',
        'status',
        'ordered_at',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_history_id');
    }
}