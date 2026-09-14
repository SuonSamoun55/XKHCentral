<?php

namespace App\Models\POS;

use App\Models\ManagementSystem\Company;
use Illuminate\Database\Eloquent\Model;

class ReportSetting extends Model
{
    protected $fillable = [
        'company_id',
        'logo',
        'show_logo',
        'show_company_name',
        'show_address',
        'show_tax_number',
        'show_phone',
        'show_email',
        'show_discount_column',
        'show_vat_column',
        'show_unit_column',
        'show_item_image',
        'spacing',
        'show_signature',
        'signature_labels',
        'footer_note',
    ];

    protected $casts = [
        'show_logo' => 'boolean',
        'show_company_name' => 'boolean',
        'show_address' => 'boolean',
        'show_tax_number' => 'boolean',
        'show_phone' => 'boolean',
        'show_email' => 'boolean',
        'show_discount_column' => 'boolean',
        'show_vat_column' => 'boolean',
        'show_unit_column' => 'boolean',
        'show_item_image' => 'boolean',
        'show_signature' => 'boolean',
        'signature_labels' => 'array',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Sensible defaults for a company that hasn't saved report settings yet
     * — the report page/PDF still needs something to render. An unsaved
     * Eloquent instance does NOT pick up the migration's column defaults on
     * its own, so they're spelled out here too (matching create_report_settings_table)
     * — otherwise every toggle would silently read as off until first saved.
     */
    private static function defaultAttributes(): array
    {
        return [
            'show_logo' => true,
            'show_company_name' => true,
            'show_address' => true,
            'show_tax_number' => true,
            'show_phone' => true,
            'show_email' => true,
            'show_discount_column' => true,
            'show_vat_column' => true,
            'show_unit_column' => true,
            'show_item_image' => false,
            'spacing' => 'normal',
            'show_signature' => false,
            'signature_labels' => ['Customer Signature', 'Authorized By'],
        ];
    }

    public static function forCompany(?int $companyId): self
    {
        if (!$companyId) {
            return new self(static::defaultAttributes());
        }

        return static::firstOrNew(
            ['company_id' => $companyId],
            static::defaultAttributes()
        );
    }
}
