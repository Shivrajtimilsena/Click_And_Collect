<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
    use HasFactory;

    protected $table = 'shop';

    protected $primaryKey = 'shop_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'trader_id',
        'shop_name',
        'description',
        'shop_address',
        'shop_image',
        'is_active',
    ];

    public function getIsActiveAttribute($value)
    {
        return $value === 'Y';
    }

    public function setIsActiveAttribute($value)
    {
        $this->attributes['is_active'] = $value ? 'Y' : 'N';
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'shop_id', 'shop_id');
    }

    public function trader(): BelongsTo
    {
        return $this->belongsTo(Trader::class, 'trader_id', 'trader_id');
    }

}
