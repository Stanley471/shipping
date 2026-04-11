<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'referred_user_id',
        'type',
        'amount',
        'balance_after',
        'description',
        'coin_purchase_id',
        'coin_transaction_id',
        'status',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'json',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function referredUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    public function coinPurchase(): BelongsTo
    {
        return $this->belongsTo(CoinPurchase::class);
    }

    public function coinTransaction(): BelongsTo
    {
        return $this->belongsTo(CoinTransaction::class);
    }

    /**
     * Scope for earnings (positive amounts)
     */
    public function scopeEarnings($query)
    {
        return $query->where('amount', '>', 0);
    }

    /**
     * Scope for withdrawals/conversions (negative amounts)
     */
    public function scopeOutgoing($query)
    {
        return $query->where('amount', '<', 0);
    }

    /**
     * Get transaction type label
     */
    public function typeLabel(): string
    {
        return match($this->type) {
            'signup_bonus' => 'Signup Bonus',
            'purchase_commission' => 'Purchase Commission',
            'converted_to_coins' => 'Converted to Coins',
            'withdrawal' => 'Withdrawal',
            'admin_adjustment' => 'Admin Adjustment',
            default => $this->type,
        };
    }

    /**
     * Get status badge color
     */
    public function statusColor(): string
    {
        return match($this->status) {
            'completed' => 'green',
            'pending' => 'yellow',
            'rejected' => 'red',
            default => 'gray',
        };
    }
}
