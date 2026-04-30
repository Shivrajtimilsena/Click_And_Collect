<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'loyalty_points',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class, 'customer_id', 'customer_id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'customer_id', 'customer_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'customer_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'customer_id', 'customer_id');
    }

    public function getOrCreateCart()
    {
        return Cart::firstOrCreate(
            ['customer_id' => $this->customer_id],
            ['customer_id' => $this->customer_id]
        );
    }
}
