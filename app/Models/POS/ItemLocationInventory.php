<?php

namespace App\Models\POS;

use Illuminate\Database\Eloquent\Model;

class ItemLocationInventory extends Model
{
    protected $fillable = [
        'company_id',
        'item_id',
        'location_code',
        'location_name',
        'inventory',
    ];

    protected $casts = [
        'inventory' => 'decimal:2',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
