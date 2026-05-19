<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraderWithdrawal extends Model
{
    use HasFactory;

    protected $table = 'trader_withdrawal';
    protected $primaryKey = 'withdrawal_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'trader_id',
        'amount',
        'paypal_email',
        'status',
        'paypal_batch_id',
        'admin_notes',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'float',
        'processed_at' => 'datetime',
    ];

    public function trader()
    {
        return $this->belongsTo(Trader::class, 'trader_id', 'trader_id');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by', 'user_id');
    }
}
