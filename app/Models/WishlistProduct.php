<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishlistProduct extends Model
{
    use HasFactory;

    protected $table = 'wishlist_products';

    protected $primaryKey = 'wishlist_product_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = true;

    const UPDATED_AT = null;

    protected $fillable = [
        'wishlist_id',
        'product_id',
        'added_at',
    ];

    protected $casts = [
        'added_at' => 'datetime',
    ];

    public function wishlist()
    {
        return $this->belongsTo(Wishlist::class, 'wishlist_id', 'wishlist_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
