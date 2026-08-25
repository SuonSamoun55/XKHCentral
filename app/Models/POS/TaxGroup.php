<?php

namespace App\Models\POS;

use Illuminate\Database\Eloquent\Model;
use App\Models\ManagementSystem\Company;

class TaxGroup extends Model
{
    protected $fillable = [
        'company_id',
        'code',
        'display_name',
        'percent',
    ];

    protected $casts = [
        'percent' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
