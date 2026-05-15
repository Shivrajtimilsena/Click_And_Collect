<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'user';

    protected $primaryKey = 'user_id';

    public $incrementing = true;

    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'phone_no',
        'role',
        'status',
        'address',
        'city',
        'postal_code',
        'avatar_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'user_id', 'user_id');
    }

    public function getCustomerRecord()
    {
        if (! $this->customer) {
            Customer::create([
                'user_id' => $this->user_id,
                'loyalty_points' => 0,
                'is_active' => 'Y',
            ]);
            $this->load('customer');
        }

        return $this->customer;
    }

    public function trader()
    {
        return $this->hasOne(Trader::class, 'user_id', 'user_id');
    }

    public function isTrader(): bool
    {
        return $this->role === 'TRADER';
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'user_id', 'user_id');
    }
}
