<?php

namespace App\Models\POSModel;

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
