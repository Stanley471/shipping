<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoinTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'balance_after',
        'description',
        'reference',
        'metadata',
        'processed_by',
    ];

    protected $casts = [
        'amount' => 'integer',
        'balance_after' => 'integer',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Type constants
    const TYPE_DEPOSIT = 'deposit';
    const TYPE_SPEND = 'spend';
    const TYPE_REFUND = 'refund';
    const TYPE_ADMIN_ADD = 'admin_add';
    const TYPE_ADMIN_DEDUCT = 'admin_deduct';

    // Scope helpers
    public function scopeDeposits($query)
    {
        return $query->where('type', self::TYPE_DEPOSIT);
    }

    public function scopeSpending($query)
    {
        return $query->where('type', self::TYPE_SPEND);
    }

    public function scopeCredits($query)
    {
        return $query->whereIn('type', [self::TYPE_DEPOSIT, self::TYPE_REFUND, self::TYPE_ADMIN_ADD]);
    }

    public function scopeDebits($query)
    {
        return $query->whereIn('type', [self::TYPE_SPEND, self::TYPE_ADMIN_DEDUCT]);
    }
}
