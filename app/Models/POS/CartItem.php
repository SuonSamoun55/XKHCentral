<?php
namespace App\Models\POS;
use Illuminate\Database\Eloquent\Model;
class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'item_id',
        'item_variant_id',
        'item_no',
        'item_name',
        'qty',
        'unit_price',
        'line_total',
    ];
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
    public function itemVariant()
    {
        return $this->belongsTo(ItemVariant::class, 'item_variant_id');
    }
}