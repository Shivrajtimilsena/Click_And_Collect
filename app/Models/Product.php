<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'product_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'shop_id',
        'product_category_id',
        'product_name',
        'description',
        'price',
        'stock',
        'amount',
        'max_order',
        'min_order',
        'add_date',
        'update_date',
        'product_status',
        'image_url',
    ];

    protected $casts = [
        'price' => 'float',
        'stock' => 'integer',
        'amount' => 'integer',
        'max_order' => 'integer',
        'min_order' => 'integer',
        'add_date' => 'date',
        'update_date' => 'date',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'shop_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id', 'product_category_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'product_id', 'product_id');
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class, 'product_id', 'product_id');
    }

    public function getDiscountedPriceAttribute(): float
    {
        return $this->price * (1 - ($this->discount->discount_percentage ?? 0) / 100);
    }

    public function getAverageRatingAttribute(): float
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getReviewCountAttribute(): int
    {
        return $this->reviews()->count();
    }
}
