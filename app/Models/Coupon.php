<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupon';
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
        // Check if coupon is active
        if ($this->is_active !== 'Y') {
            return false;
        }
        
        // Check if within valid date range
        $now = \Carbon\Carbon::now()->toDateString();
        if ($this->start_date && $this->start_date > $now) {
            return false; // Not started yet
        }
        if ($this->end_date && $this->end_date < $now) {
            return false; // Already expired
        }
        
        return true;
    }
}
