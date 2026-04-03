<x-mail::message>
# Your Shipment Has Been Created

Hello {{ $shipment->receiver_name }},

A new shipment has been created for you. Here are the details:

**Tracking ID:** {{ $shipment->tracking_id }}

**Shipment Type:** {{ str_replace('_', ' ', $shipment->shipment_type) }}

**From:** {{ $shipment->sender_name }}

**To:** {{ $shipment->receiver_name }}

**Estimated Delivery:** {{ $shipment->eta?->format('F d, Y') ?? 'To be determined' }}

<x-mail::button :url="$trackingUrl">
Track Your Shipment
</x-mail::button>

You can track your shipment anytime using the tracking ID above.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>