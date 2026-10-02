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
        'description',
        'unit_price',
        'tax_group_code',
        'tax_amount',
        'discount_amount',
        'discount_start_date',
        'discount_end_date',
        'inventory',
        'blocked',
        'is_visible',
        'allow_oversell',
        'category_visible',
        'item_category_code',
        'base_unit_of_measure_code',
        'price_includes_tax',
        'image_url',
        'custom_image_url',
        'default_location_code',
        'type',
        'number_series_id',
        'series_number',
    ];

    protected $casts = [
        'blocked' => 'boolean',
        'is_visible' => 'boolean',
        'allow_oversell' => 'boolean',
        'category_visible' => 'boolean',
        'price_includes_tax' => 'boolean',
        'unit_price' => 'decimal:2',
        'inventory' => 'decimal:2',
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

    public function locationInventories()
    {
        return $this->hasMany(ItemLocationInventory::class, 'item_id');
    }

    // Which company-wide "Item" number series this item is tagged under.
    // The issued number lives in series_number, separate from `number`
    // (Business Central's own item number, overwritten on every sync).
    public function numberSeries()
    {
        return $this->belongsTo(NumberSeries::class, 'number_series_id');
    }

    // BC-synced names are inconsistently cased (e.g. "PARIS Guest Chair,
    // black" — every word capitalized except the trailing color). ucwords()
    // capitalizes the first letter of each word without touching letters
    // that are already uppercase, so "PARIS" stays as-is while "black"
    // becomes "Black" — fixes the inconsistency without needing to touch
    // the stored value or every place display_name gets rendered.
    public function getDisplayNameAttribute($value)
    {
        return $value !== null ? ucwords($value) : $value;
    }

    // Stock shown to/used by customers: the single warehouse location the
    // store owner has picked as their selling location, falling back to the
    // aggregate BC inventory if no location has been selected yet.
    public function getSellableInventoryAttribute()
    {
        $setting = StoreSetting::forCompany($this->company_id);

        if (empty($setting->selling_location_code)) {
            return (float) $this->inventory;
        }

        $locations = $this->relationLoaded('locationInventories')
            ? $this->locationInventories
            : $this->locationInventories()->get();

        $match = $locations->firstWhere('location_code', $setting->selling_location_code);

        return $match ? (float) $match->inventory : 0.0;
    }

    /**
     * Whether a customer can actually buy this item: either it still has
     * stock, or the admin has switched "Oversell" on for it specifically
     * (Store Management > product row / "Open All" for out-of-stock items).
     * Anywhere customer-facing code counts, lists, or lets someone buy a
     * product should gate on this — not just is_visible — so a product
     * hidden by this rule doesn't get counted as "available" elsewhere.
     */
    public function isPurchasable(): bool
    {
        if ((float) ($this->sellable_inventory ?? 0) > 0) {
            return true;
        }

        return (bool) $this->allow_oversell;
    }

    /**
     * Most a customer may order of this item: null = no limit (Oversell is
     * on), otherwise the stock left at the selling location (never below 0).
     */
    public function maxOrderableQty(): ?float
    {
        if ($this->allow_oversell) {
            return null;
        }

        return max(0.0, (float) ($this->sellable_inventory ?? 0));
    }

    /** "5 PCS" style label for stock messages. */
    public function stockLabel(float $qty): string
    {
        $number = rtrim(rtrim(number_format($qty, 2, '.', ''), '0'), '.');

        return $number . ' ' . ($this->base_unit_of_measure_code ?: 'units');
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class, 'item_id');
    }

    /**
     * The real VAT rate for this item — resolved live from BC's synced VAT
     * Posting Setup by matching tax_group_code (the item's VAT Prod. Posting
     * Group, as sent by BC) against vat_prod_posting_group, rather than a
     * cached percent on the item itself. Re-syncing VAT Posting Setup takes
     * effect immediately everywhere this is read.
     */
    public function getResolvedVatPercentAttribute(): float
    {
        if (empty($this->tax_group_code)) {
            return 0.0;
        }

        return (float) (VatPostingSetup::where('company_id', $this->company_id)
            ->where('vat_prod_posting_group', $this->tax_group_code)
            ->where('blocked', false)
            ->value('vat_pct') ?? 0);
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

    public function variants()
    {
        return $this->hasMany(ItemVariant::class, 'item_id');
    }

    /**
     * The price one unit sells for: the variant's own price when it has one,
     * otherwise the product's unit_price. Every cart/checkout calculation goes
     * through here so a variant's price is used the same way everywhere.
     */
    public function unitPriceFor(?ItemVariant $variant = null): float
    {
        if ($variant && $variant->item_id === $this->id && $variant->hasOwnPrice()) {
            return (float) $variant->price;
        }

        return (float) ($this->unit_price ?? 0);
    }
}
