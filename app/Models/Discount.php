<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $table = 'discount';
    protected $primaryKey = 'discount_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'product_id',
        'discount_percentage',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'discount_percentage' => 'float',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function isActive(): bool
    {
        return now()->between($this->start_date, $this->end_date);
    }
}
