<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'tracking_id',
        'sender_name',
        'receiver_name',
        'receiver_email',
        'pickup_location',
        'delivery_address',
        'shipped_at',
        'shipment_type',
        'eta',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'eta' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trackingUpdates(): HasMany
    {
        return $this->hasMany(TrackingUpdate::class);
    }

    public function latestUpdate(): HasMany
    {
        return $this->hasMany(TrackingUpdate::class)->latest('occurred_at');
    }
}