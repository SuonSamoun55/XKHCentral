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
        'vat_percent',
        'tax_amount',
        'discount_amount',
        'discount_start_date',
        'discount_end_date',
        'inventory',
        'blocked',
        'is_visible',
        'category_visible',
        'item_category_code',
        'base_unit_of_measure_code',
        'price_includes_tax',
        'image_url',
        'default_location_code',
        'type',
    ];

    protected $casts = [
        'blocked' => 'boolean',
        'is_visible' => 'boolean',
        'category_visible' => 'boolean',
        'price_includes_tax' => 'boolean',
        'unit_price' => 'decimal:2',
        'vat_percent' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_start_date' => 'datetime',
        'discount_end_date' => 'datetime',
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
