<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';
    protected $primaryKey = 'cart_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'customer_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function products()
    {
        return $this->hasMany(CartProduct::class, 'cart_id', 'cart_id');
    }

    public function getTotalAttribute(): float
    {
        return $this->products()->get()->sum(fn($item) => $item->product->discounted_price * $item->quantity);
    }
}
