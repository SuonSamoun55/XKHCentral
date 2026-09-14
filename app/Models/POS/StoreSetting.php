<?php

namespace App\Models\POS;

use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    protected $fillable = [
        'company_id',
        'selling_location_code',
        'selling_location_name',
    ];

    public static function forCompany(?int $companyId): self
    {
        return static::firstOrNew(['company_id' => $companyId]);
    }
}
