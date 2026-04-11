<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralSetting extends Model
{
    protected $fillable = [
        'signup_bonus_amount',
        'purchase_commission_percent',
        'min_withdrawal_amount',
        'conversion_rate',
        'is_active',
    ];

    protected $casts = [
        'signup_bonus_amount' => 'integer',
        'purchase_commission_percent' => 'decimal:2',
        'min_withdrawal_amount' => 'integer',
        'conversion_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get default settings (singleton pattern)
     */
    public static function getSettings(): self
    {
        return self::firstOrCreate([], [
            'signup_bonus_amount' => 50,
            'purchase_commission_percent' => 10.00,
            'min_withdrawal_amount' => 1000,
            'conversion_rate' => 1.00,
            'is_active' => true,
        ]);
    }

    /**
     * Check if referral system is active
     */
    public static function isActive(): bool
    {
        return self::getSettings()->is_active;
    }
}
