<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';

    protected $primaryKey = 'order_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'customer_id',
        'shop_id',
        'collection_slot_id',
        'group_id',
        'order_amount',
        'discount_amount',
        'total_amount',
        'order_status',
        'payment_status',
        'rfid_uid',
        'rfid_assigned_at',
        'collected_at',
        'notes',
    ];

    protected $casts = [
        'order_amount' => 'float',
        'discount_amount' => 'float',
        'total_amount' => 'float',
        'rfid_assigned_at' => 'datetime',
        'collected_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    public function collectionSlot()
    {
        return $this->belongsTo(CollectionSlot::class, 'collection_slot_id', 'collection_slot_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'order_id');
    }

    /**
     * Get all shops involved in this order
     */
    public function shops()
    {
        return $this->items()
            ->with('product.shop')
            ->get()
            ->pluck('product.shop')
            ->unique('shop_id');
    }

    public function siblingOrders()
    {
        return $this->hasMany(Order::class, 'group_id', 'group_id')
            ->where('order_id', '!=', $this->order_id);
    }
}
