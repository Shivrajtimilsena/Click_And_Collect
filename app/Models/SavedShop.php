<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedShop extends Model
{
    use HasFactory;

    protected $table = 'customer_saved_shop';

    protected $primaryKey = 'saved_shop_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'customer_id',
        'shop_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'shop_id');
    }
}
