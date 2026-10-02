<?php

namespace App\Models\POS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'company_id',
        'item_id',
        'item_variant_id',
        'item_no',
        'item_name',
        'variant_description',
        'image_path',
        'qty',
        'unit_price',
        'discount_percent',
        'discount_amount',
        'tax_amount',
        'line_total',
        'location_code',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function item()
    {
        return $this->belongsTo(\App\Models\POS\Item::class, 'item_id');
    }

    public function itemVariant()
    {
        return $this->belongsTo(\App\Models\POS\ItemVariant::class, 'item_variant_id');
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\ManagementSystem\Company::class, 'company_id');
    }

    /**
     * Removes the saved picture copies of these orders' lines. Call it before
     * deleting the orders (the lines themselves go with the order).
     */
    public static function deleteSnapshotImages(array $orderIds): void
    {
        $paths = static::whereIn('order_id', $orderIds)
            ->where('image_path', 'like', '/storage/order-items/%')
            ->pluck('image_path')
            ->map(fn ($path) => substr($path, strlen('/storage/')))
            ->all();

        Storage::disk('public')->delete($paths);
    }

    /**
     * Copies the picture a customer sees for this product right now (the
     * variant's, else the product's) into "order-items/". It is saved on the
     * order line as image_path, and every order page shows that copy, so
     * replacing or deleting the product picture later doesn't change orders
     * already placed. Returns the copy's "/storage/..." path, or null when
     * the product has no picture (the order then keeps showing none).
     */
    public static function snapshotImage(?Item $item, ?ItemVariant $variant = null): ?string
    {
        $source = $variant?->image_url ?: ($item?->custom_image_url ?: $item?->image_url);

        if (!$source) {
            return null;
        }

        if (str_starts_with($source, 'http://') || str_starts_with($source, 'https://')) {
            return $source;
        }

        $disk = Storage::disk('public');
        $relative = preg_replace('#^/?storage/#', '', ltrim($source, '/'));

        if (!$disk->exists($relative)) {
            return null;
        }

        $extension = pathinfo($relative, PATHINFO_EXTENSION) ?: 'jpg';
        $copy = 'order-items/' . Str::random(40) . '.' . $extension;
        $disk->copy($relative, $copy);

        return Storage::url($copy);
    }
}
