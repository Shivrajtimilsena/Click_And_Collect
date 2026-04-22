<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_percentage',
        'discount_fixed_amount',
        'usage_limit',
        'used_count',
        'expiry_date',
        'is_active',
    ];

    protected $casts = [
        'discount_percentage' => 'float',
        'discount_fixed_amount' => 'float',
        'expiry_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isValid(): bool
    {
        return $this->is_active && 
               ($this->used_count < $this->usage_limit) && 
               now()->lessThanOrEqualTo($this->expiry_date);
    }
}
