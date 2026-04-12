<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Shipment {{ $shipment->tracking_id }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.5; color: #333; padding: 30px; }
        .header { border-bottom: 3px solid #10b981; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { font-size: 24px; font-weight: bold; color: #10b981; margin-bottom: 10px; }
        .tracking-id { font-size: 18px; font-family: monospace; color: #666; }
        .section { margin-bottom: 25px; }
        .section-title { font-size: 14px; font-weight: bold; color: #10b981; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; padding-bottom: 5px; margin-bottom: 15px; }
        .label { font-size: 10px; color: #666; text-transform: uppercase; }
        .value { font-size: 12px; font-weight: bold; color: #111; }
        .status-badge { display: inline-block; padding: 5px 15px; border-radius: 20px; font-weight: bold; font-size: 11px; text-transform: uppercase; }
        .progress-bar { width: 100%; height: 20px; background: #e5e7eb; border-radius: 10px; overflow: hidden; margin: 10px 0; }
        .progress-fill { height: 100%; background: #10b981; border-radius: 10px; }
        .timeline-item { padding: 10px 0; border-bottom: 1px dashed #e5e7eb; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 10px; color: #666; text-align: center; }
    </style>
</head>
<body>
    @php
        $latestUpdate = $shipment->trackingUpdates->first();
        $status = $latestUpdate?->status ?? 'pending';
        $progress = $latestUpdate?->progress ?? 0;
    @endphp
    
    <div class="header">
        <div class="logo">Ctools</div>
        <div class="tracking-id">Tracking ID: {{ $shipment->tracking_id }}</div>
    </div>
    
    <div class="section">
        <div class="section-title">Current Status</div>
        <div style="margin-bottom: 15px;">
            <span class="status-badge" style="background: {{ $status === 'delivered' ? '#d1fae5' : ($status === 'cancelled' ? '#fee2e2' : '#dbeafe') }}; color: {{ $status === 'delivered' ? '#065f46' : ($status === 'cancelled' ? '#991b1b' : '#1e40af') }};">
                {{ str_replace('_', ' ', $status) }}
            </span>
        </div>
        <div class="progress-bar">
            <div class="progress-fill" style="width: {{ $progress }}%;"></div>
        </div>
        <div style="text-align: center; font-weight: bold; color: #10b981;">{{ $progress }}% Complete</div>
    </div>
    
    <div class="section">
        <div class="section-title">Shipment Details</div>
        <table style="width: 100%;" cellspacing="0" cellpadding="5">
            <tr>
                <td style="width: 50%;">
                    <div class="label">Shipment Type</div>
                    <div class="value">{{ str_replace('_', ' ', $shipment->shipment_type) }}</div>
                </td>
                <td style="width: 50%;">
                    <div class="label">Shipped Date</div>
                    <div class="value">{{ $shipment->shipped_at->format('M d, Y') }}</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="label">Estimated Delivery</div>
                    <div class="value">{{ $shipment->eta?->format('M d, Y') ?? 'N/A' }}</div>
                </td>
                <td>
                    <div class="label">Courier</div>
                    <div class="value">{{ $shipment->courier ?? 'N/A' }}</div>
                </td>
            </tr>
            @if($shipment->quantity)
            <tr>
                <td>
                    <div class="label">Quantity</div>
                    <div class="value">{{ $shipment->quantity }} {{ $shipment->quantity == 1 ? 'item' : 'items' }}</div>
                </td>
                <td>
                    <div class="label">Fragile</div>
                    <div class="value">{{ $shipment->is_fragile ? 'Yes' : 'No' }}</div>
                </td>
            </tr>
            @endif
        </table>
    </div>
    
    <div class="section">
        <div class="section-title">Sender Information</div>
        <div class="label">Name</div>
        <div class="value">{{ $shipment->sender_name }}</div>
        <div class="label" style="margin-top: 10px;">Address</div>
        <div class="value">{{ $shipment->pickup_location }}</div>
    </div>
    
    <div class="section">
        <div class="section-title">Receiver Information</div>
        <div class="label">Name</div>
        <div class="value">{{ $shipment->receiver_name }}</div>
        @if($shipment->receiver_email)
        <div class="label" style="margin-top: 10px;">Email</div>
        <div class="value">{{ $shipment->receiver_email }}</div>
        @endif
        <div class="label" style="margin-top: 10px;">Address</div>
        <div class="value">{{ $shipment->delivery_address }}</div>
    </div>
    
    @if($shipment->trackingUpdates->count())
    <div class="section">
        <div class="section-title">Tracking History</div>
        @foreach($shipment->trackingUpdates as $update)
        <div class="timeline-item">
            <div style="font-size: 10px; color: #666;">{{ $update->occurred_at->format('M d, Y - H:i') }}</div>
            <div style="font-weight: bold; color: #111;">{{ str_replace('_', ' ', $update->status) }} ({{ $update->progress }}%)</div>
            @if($update->location)
            <div style="font-size: 11px; color: #666;">Location: {{ $update->location }}</div>
            @endif
        </div>
        @endforeach
    </div>
    @endif
    
    <div class="footer">
        <p>Generated on {{ now()->format('F d, Y') }} | Ctools</p>
    </div>
</body>
</html>