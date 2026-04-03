<x-mail::message>
# Shipment Update

Hello {{ $shipment->receiver_name }},

Your shipment **{{ $shipment->tracking_id }}** has been updated.

**Status:** {{ ucfirst(str_replace('_', ' ', $update->status)) }}

**Progress:** {{ $update->progress }}%

@if($update->location)
**Location:** {{ $update->location }}
@endif

@if($update->note)
**Note:** {{ $update->note }}
@endif

**Updated:** {{ $update->occurred_at->format('F d, Y \a\t h:i A') }}

<x-mail::button :url="$trackingUrl">
View Full Details
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>