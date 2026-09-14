<?php

namespace App\Models\POS;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'company_id', 'status'];

    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }
}
