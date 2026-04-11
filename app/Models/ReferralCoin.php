<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReferralCoin extends Model
{
    protected $fillable = [
        'user_id',
        'balance',
        'total_earned',
        'total_converted',
        'total_withdrawn',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'total_converted' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(ReferralTransaction::class, 'user_id', 'user_id');
    }

    /**
     * Get or create referral coin record for user
     */
    public static function forUser(User $user): self
    {
        return self::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0, 'total_earned' => 0, 'total_converted' => 0, 'total_withdrawn' => 0]
        );
    }

    /**
     * Check if user can withdraw
     */
    public function canWithdraw(): bool
    {
        $minAmount = ReferralSetting::getSettings()->min_withdrawal_amount;
        return $this->balance >= $minAmount;
    }

    /**
     * Get minimum withdrawal amount
     */
    public function minWithdrawal(): int
    {
        return ReferralSetting::getSettings()->min_withdrawal_amount;
    }
}
