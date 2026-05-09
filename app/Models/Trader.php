<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trader extends Model
{
    use HasFactory;

    protected $table = 'traders';

    protected $primaryKey = 'trader_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'shop_type',
        'logo_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function shops()
    {
        return $this->hasMany(Shop::class, 'trader_id', 'trader_id');
    }
}
