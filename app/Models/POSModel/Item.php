<?php

namespace App\Models\POSModel;

use Illuminate\Database\Eloquent\Model;
use App\Models\MagamentSystemModel\Company;

class Item extends Model
{
    protected $table = 'items';

    protected $fillable = [
        'company_id',
        'bc_id',
        'number',
        'display_name',
        'unit_price',
        'inventory',
        'blocked',
        'item_category_code',
        'base_unit_of_measure_code',
        'price_includes_tax',
        'image_url',
        'default_location_code',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
