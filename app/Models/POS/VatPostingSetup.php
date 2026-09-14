<?php

namespace App\Models\POS;

use Illuminate\Database\Eloquent\Model;
use App\Models\ManagementSystem\Company;

class VatPostingSetup extends Model
{
    protected $table = 'vat_posting_setups';

    protected $fillable = [
        'company_id',
        'bc_id',
        'vat_bus_posting_group',
        'vat_prod_posting_group',
        'description',
        'blocked',
        'vat_identifier',
        'vat_pct',
        'vat_calculation_type',
        'unrealized_vat_type',
        'adjust_for_payment_discount',
        'sales_vat_account',
        'sales_vat_unreal_account',
        'purchase_vat_account',
        'purch_vat_unreal_account',
        'reverse_chrg_vat_acc',
        'reverse_chrg_vat_unreal_acc',
        'vat_clause_code',
        'eu_service',
        'certificate_of_supply_required',
        'tax_category',
    ];

    protected $casts = [
        'blocked' => 'boolean',
        'adjust_for_payment_discount' => 'boolean',
        'eu_service' => 'boolean',
        'certificate_of_supply_required' => 'boolean',
        'vat_pct' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
