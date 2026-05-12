<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionSlot extends Model
{
    use HasFactory;

    protected $table = 'collection_slot';
    protected $primaryKey = 'collection_slot_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'shop_id',
        'slot_date',
        'slot_day',
        'slot_label',
        'start_time',
        'end_time',
        'capacity',
        'total_order',
        'is_active',
    ];

    protected $casts = [
        'slot_date' => 'date',
        'capacity' => 'integer',
        'total_order' => 'integer',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'shop_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'collection_slot_id', 'collection_slot_id');
    }

    public function isFull(): bool
    {
        return $this->total_order >= $this->capacity;
    }
}
