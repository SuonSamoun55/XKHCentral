<?php

namespace App\Models\MagamentSystemModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyConnection extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'tenant_id',
        'client_id',
        'client_secret',
        'company_bc_id',
        'environment',
        'base_url',
        'token_url',
        'is_default',
        'status',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'status' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function setClientSecretAttribute($value): void
    {
        $this->attributes['client_secret'] = filled($value)
            ? encrypt(trim($value))
            : null;
    }

    public function getClientSecretAttribute($value): ?string
    {
        return filled($value) ? decrypt($value) : null;
    }
}
