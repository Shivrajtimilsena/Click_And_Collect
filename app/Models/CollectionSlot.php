<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionSlot extends Model
{
    use HasFactory;

    protected $table = 'collection_slots';

    protected $fillable = [
        'shop_id',
        'start_time',
        'end_time',
        'max_orders',
        'current_orders',
        'is_available',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'max_orders' => 'integer',
        'current_orders' => 'integer',
        'is_available' => 'boolean',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function isFull(): bool
    {
        return $this->current_orders >= $this->max_orders;
    }
}
