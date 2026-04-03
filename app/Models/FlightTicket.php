<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlightTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ticket_number',
        'booking_reference',
        'passenger_name',
        'flight_number',
        'airline',
        'origin',
        'destination',
        'flight_date',
        'departure_time',
        'arrival_time',
        'seat',
        'gate',
        'class',
        'price',
        'template',
        'pdf_path',
        'download_count',
        'last_downloaded_at',
    ];

    protected $casts = [
        'flight_date' => 'date',
        'last_downloaded_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getRouteKeyName(): string
    {
        return 'booking_reference';
    }

    public function getDurationAttribute(): string
    {
        $departure = strtotime($this->departure_time);
        $arrival = strtotime($this->arrival_time);
        $diff = $arrival - $departure;
        
        if ($diff < 0) {
            $diff += 24 * 60 * 60; // Add 24 hours if arrival is next day
        }
        
        $hours = floor($diff / 3600);
        $minutes = floor(($diff % 3600) / 60);
        
        return "{$hours}h {$minutes}m";
    }

    public function getStatusAttribute(): string
    {
        $flightDateTime = strtotime($this->flight_date . ' ' . $this->departure_time);
        $now = time();
        
        if ($flightDateTime < $now) {
            return 'completed';
        } elseif ($flightDateTime - $now < 24 * 60 * 60) {
            return 'upcoming';
        } else {
            return 'scheduled';
        }
    }
}