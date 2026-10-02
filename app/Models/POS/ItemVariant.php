<?php

namespace App\Models\POS;

use Illuminate\Database\Eloquent\Model;

class ItemVariant extends Model
{
    protected $fillable = [
        'item_id',
        'bc_id',
        'item_number',
        'code',
        'description',
        'description2',
        'blocked',
        'sales_blocked',
        'purchasing_blocked',
        'is_visible',
        'price',
        'image_url',
    ];

    protected $casts = [
        'blocked' => 'boolean',
        'sales_blocked' => 'boolean',
        'purchasing_blocked' => 'boolean',
        'is_visible' => 'boolean',
        'price' => 'decimal:2',
    ];

    /** True when this variant has its own price instead of the product's. */
    public function hasOwnPrice(): bool
    {
        return $this->price !== null;
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
