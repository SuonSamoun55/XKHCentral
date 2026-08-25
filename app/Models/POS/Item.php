<?php

namespace App\Models\POS;

use Illuminate\Database\Eloquent\Model;
use App\Models\ManagementSystem\Company;

class Item extends Model
{
    protected $table = 'items';

    protected $fillable = [
        'company_id',
        'bc_id',
        'number',
        'display_name',
        'unit_price',
        'tax_group_code',
        'tax_amount',
        'discount_amount',
        'discount_start_date',
        'discount_end_date',
        'inventory',
        'blocked',
        'is_visible',
        'category_visible',
        'item_category_code',
        'base_unit_of_measure_code',
        'price_includes_tax',
        'image_url',
        'default_location_code',
        'type',
    ];

    protected $casts = [
        'blocked' => 'boolean',
        'is_visible' => 'boolean',
        'category_visible' => 'boolean',
        'price_includes_tax' => 'boolean',
        'unit_price' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_start_date' => 'datetime',
        'discount_end_date' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class, 'item_id');
    }

    /**
     * The real VAT rate for this item — resolved live from Tax Groups by
     * its tax_group_code (the code Business Central actually sends), rather
     * than a cached percent on the item itself. Editing a tax group's rate
     * takes effect immediately everywhere this is read.
     */
    public function getResolvedVatPercentAttribute(): float
    {
        if (empty($this->tax_group_code)) {
            return 0.0;
        }

        return (float) (TaxGroup::where('company_id', $this->company_id)
            ->where('code', $this->tax_group_code)
            ->value('percent') ?? 0);
    }

    public function getActiveDiscountPercentAttribute(): float
    {
        $discount = max(0, (float) ($this->discount_amount ?? 0));
        if ($discount <= 0) {
            return 0.0;
    }

    $today = \Carbon\Carbon::today();
    $start = $this->discount_start_date ? \Carbon\Carbon::parse($this->discount_start_date)->startOfDay() : null;
    $end = $this->discount_end_date ? \Carbon\Carbon::parse($this->discount_end_date)->endOfDay() : null;

    if ($start && $today->lt($start)) {
        return 0.0;
    }
    if ($end && $today->gt($end)) {
        return 0.0;
    }

    return min(100, $discount);
}
}
