<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'is_vendor',
        'is_active',
        'referral_code',
        'referred_by',
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
            'is_vendor' => 'boolean',
            'is_active' => 'boolean',
            'total_referrals' => 'integer',
            'total_referral_earnings' => 'decimal:2',
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

    // Check if user is a vendor (admins are automatically vendors)
    public function isVendor(): bool
    {
        return $this->is_vendor || $this->isAdmin();
    }

    // Get vendor bank account
    public function vendorBankAccount(): HasOne
    {
        return $this->hasOne(AdminBankAccount::class);
    }

    // Referral relationships
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function referralCoins(): HasOne
    {
        return $this->hasOne(ReferralCoin::class);
    }

    public function referralTransactions(): HasMany
    {
        return $this->hasMany(ReferralTransaction::class);
    }

    // Get or create referral coin balance
    public function getReferralCoinBalance(): float
    {
        if (!$this->referralCoins) {
            $this->referralCoins()->create([
                'balance' => 0,
                'total_earned' => 0,
                'total_converted' => 0,
                'total_withdrawn' => 0,
            ]);
            $this->load('referralCoins');
        }
        return (float) $this->referralCoins->balance;
    }

    // Generate unique referral code
    public static function generateReferralCode(): string
    {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 8));
        } while (self::where('referral_code', $code)->exists());
        
        return $code;
    }

    // Get user's referral link
    public function getReferralLink(): string
    {
        return url('/register?ref=' . $this->referral_code);
    }
}
