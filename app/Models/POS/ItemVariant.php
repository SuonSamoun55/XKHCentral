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
        'image_url',
    ];

    protected $casts = [
        'blocked' => 'boolean',
        'sales_blocked' => 'boolean',
        'purchasing_blocked' => 'boolean',
        'is_visible' => 'boolean',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}