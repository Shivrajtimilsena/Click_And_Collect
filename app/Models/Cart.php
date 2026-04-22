<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function products()
    {
        return $this->hasMany(CartProduct::class);
    }

    public function getTotalAttribute(): float
    {
        return $this->products()->get()->sum(fn($item) => $item->product->discounted_price * $item->quantity);
    }
}
