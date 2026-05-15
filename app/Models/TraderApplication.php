<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TraderApplication extends Model
{
    protected $table = 'trader_application';

    protected $primaryKey = 'application_id';

    protected $fillable = [
        'shop_name',
        'email',
        'location',
        'speciality',
        'description',
        'password',
        'status',
        'admin_notes',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $attributes = [
        'status' => 'PENDING',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'user_id');
    }
}
