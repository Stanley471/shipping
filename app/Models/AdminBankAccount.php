<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminBankAccount extends Model
{
    protected $fillable = [
        'user_id',
        'bank_name',
        'account_number',
        'account_name',
        'display_name',
        'vendor_info',
        'vendor_notes',
        'rate',
        'min_limit',
        'max_limit',
        'completion_rate',
        'avg_response_time',
        'total_sales',
        'rating',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'rate' => 'decimal:2',
        'rating' => 'decimal:1',
        'min_limit' => 'integer',
        'max_limit' => 'integer',
        'completion_rate' => 'integer',
        'avg_response_time' => 'integer',
        'total_sales' => 'integer',
    ];

    // Relationship to vendor user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Get display name (fallback to account name)
    public function getDisplayNameAttribute($value): string
    {
        return $value ?: $this->account_name;
    }

    // Scope helpers
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Get ordered list
    public static function getActiveOrdered()
    {
        return static::active()->orderBy('sort_order')->get();
    }

    // Get random active account for P2P
    public static function getRandomActive(): ?self
    {
        return static::active()->inRandomOrder()->first();
    }

    // Check if user is the vendor owner
    public function isOwnedBy($userId): bool
    {
        return $this->user_id === $userId;
    }
}
