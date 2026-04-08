<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'description',
        'is_free',
        'coin_cost',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'coin_cost' => 'integer',
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    // Boot to create default services
    protected static function boot()
    {
        parent::boot();
    }

    // Get service by slug
    public static function getBySlug(string $slug): ?self
    {
        return static::where('slug', $slug)->first();
    }

    // Get cost (0 if free)
    public function getCost(): int
    {
        return $this->is_free ? 0 : $this->coin_cost;
    }

    // Check if user can afford
    public function canAfford(User $user): bool
    {
        $balance = $user->coins?->balance ?? 0;
        return $balance >= $this->getCost();
    }

    // Scope helpers
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePaid($query)
    {
        return $query->where('is_free', false);
    }

    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }
}
