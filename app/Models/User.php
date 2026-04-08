<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }
    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    public function flightTickets(): HasMany
    {
        return $this->hasMany(FlightTicket::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function coins(): HasOne
    {
        return $this->hasOne(UserCoin::class);
    }

    public function coinTransactions(): HasMany
    {
        return $this->hasMany(CoinTransaction::class);
    }

    public function coinPurchases(): HasMany
    {
        return $this->hasMany(CoinPurchase::class);
    }

    // Helper to get or create coin balance
    public function getCoinBalance(): int
    {
        if (!$this->coins) {
            $this->coins()->create([
                'balance' => 0,
                'total_earned' => 0,
                'total_spent' => 0,
            ]);
            $this->load('coins');
        }
        return $this->coins->balance;
    }
}
