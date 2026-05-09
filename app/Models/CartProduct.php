<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartProduct extends Model
{
    use HasFactory;

    protected $table = 'cart_products';

    protected $primaryKey = 'cart_product_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price_at_add',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price_at_add' => 'float',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'cart_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function getSubtotalAttribute(): float
    {
        return $this->product->discounted_price * $this->quantity;
    }
}
