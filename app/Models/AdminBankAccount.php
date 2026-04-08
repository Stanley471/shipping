<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminBankAccount extends Model
{
    protected $fillable = [
        'bank_name',
        'account_number',
        'account_name',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Scope helpers
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
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
}
