<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'collection_slot_id',
        'total_price',
        'status',
        'notes',
    ];

    protected $casts = [
        'total_price' => 'float',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function collectionSlot()
    {
        return $this->belongsTo(CollectionSlot::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
