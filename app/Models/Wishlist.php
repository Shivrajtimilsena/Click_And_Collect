<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $table = 'wishlists';
    protected $primaryKey = 'wishlist_id';
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
        return $this->hasMany(WishlistProduct::class, 'wishlist_id', 'wishlist_id');
    }
}
