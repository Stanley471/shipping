<?php

namespace App\Services;

use App\Models\Shipment;

class TrackingIdGenerator
{
    public static function generate(): string
    {
        $prefix = 'TRK';
        $length = 10;
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $maxAttempts = 100;

        for ($i = 0; $i < $maxAttempts; $i++) {
            $randomPart = '';
            
            for ($j = 0; $j < $length; $j++) {
                $randomPart .= $characters[random_int(0, strlen($characters) - 1)];
            }

            $trackingId = $prefix . $randomPart;

            // Check if this ID already exists
            $exists = Shipment::withTrashed()->where('tracking_id', $trackingId)->exists();

            if (! $exists) {
                return $trackingId;
            }
        }

        throw new \Exception('Unable to generate unique tracking ID after ' . $maxAttempts . ' attempts.');
    }
}