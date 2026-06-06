<?php

namespace App\Models\POS;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations';

    protected $fillable = [
        'code',
        'name',
        'is_active',
    ];
}
