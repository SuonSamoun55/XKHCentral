<?php

namespace App\Models\POS;

use Illuminate\Database\Eloquent\Model;

class ItemSetupStatus extends Model
{
    protected $fillable = [
        'item_id',
        'main_image_done',
        'variants_done',
    ];

    protected $casts = [
        'main_image_done' => 'boolean',
        'variants_done' => 'boolean',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}