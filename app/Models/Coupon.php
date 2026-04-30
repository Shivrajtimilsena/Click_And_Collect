<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';
    protected $primaryKey = 'coupon_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'coupon_code',
        'amount',
        'discount_percent',
        'start_date',
        'end_date',
        'description',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'float',
        'discount_percent' => 'float',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function isValid(): bool
    {
        return $this->is_active && 
               ($this->used_count < $this->usage_limit) && 
               now()->lessThanOrEqualTo($this->expiry_date);
    }
}
