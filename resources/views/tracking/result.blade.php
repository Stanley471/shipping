<x-guest-layout>
    <div class="min-h-screen bg-gray-100 py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="text-center mb-8">
                <a href="{{ route('tracking.index') }}" class="text-blue-600 hover:underline">&larr; Track another shipment</a>
            </div>

            @php
                $latestUpdate = $shipment->trackingUpdates->first();
            @endphp

            <!-- Shipment Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-center mb-6">
                    <p class="text-sm text-gray-500">Tracking ID</p>
                    <p class="text-3xl font-mono font-bold">{{ $shipment->tracking_id }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-500">Shipping From</p>
                        <p class="font-medium">{{ $shipment->sender_name }}</p>
                        <p class="text-sm text-gray-600">{{ $shipment->pickup_location }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Destination</p>
                        <p class="font-medium">{{ $shipment->receiver_name }}</p>
                        <p class="text-sm text-gray-600">{{ $shipment->delivery_address }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-sm text-gray-500">Shipped At</p>
                        <p class="font-medium">{{ $shipment->shipped_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">ETA</p>
                        <p class="font-medium">{{ $shipment->eta?->format('M d, Y') ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Type</p>
                        <p class="font-medium capitalize">{{ str_replace('_', ' ', $shipment->shipment_type) }}</p>
                    </div>
                </div>
            </div>

            <!-- Current Status & Progress -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-4 text-center">Current Status</h3>
                
                @if($latestUpdate)
                    <p class="text-2xl text-center capitalize mb-4">{{ str_replace('_', ' ', $latestUpdate->status) }}</p>
                    
                    <div class="mb-4">
                        <div class="flex justify-between text-sm mb-1">
                            <span>Progress</span>
                            <span>{{ $latestUpdate->progress }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="bg-green-600 h-4 rounded-full transition-all duration-500" style="width: {{ $latestUpdate->progress }}%"></div>
                        </div>
                    </div>

                    <div class="text-center space-y-1">
                        @if($latestUpdate->location)
                            <p class="text-gray-600"><strong>Current Location:</strong> {{ $latestUpdate->location }}</p>
                        @endif
                        @if($latestUpdate->note)
                            <p class="text-gray-600"><strong>Note:</strong> {{ $latestUpdate->note }}</p>
                        @endif
                        <p class="text-sm text-gray-400">Updated {{ $latestUpdate->occurred_at->format('M d, Y H:i') }}</p>
                    </div>
                @else
                    <p class="text-center text-gray-500">No status updates available yet.</p>
                @endif
            </div>

            <!-- Timeline -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-4">Delivery Timeline</h3>
                
                @if($shipment->trackingUpdates->count())
                    <div class="relative border-l-2 border-gray-200 ml-3 space-y-8">
                        @foreach($shipment->trackingUpdates as $update)
                            <div class="relative ml-6">
                                <span class="absolute -left-[31px] top-1 w-4 h-4 bg-blue-600 rounded-full border-2 border-white"></span>
                                <p class="text-sm text-gray-500">{{ $update->occurred_at->format('M d, Y H:i') }}</p>
                                <p class="font-semibold capitalize">{{ str_replace('_', ' ', $update->status) }} — {{ $update->progress }}%</p>
                                @if($update->location)
                                    <p class="text-sm text-gray-600"><strong>Location:</strong> {{ $update->location }}</p>
                                @endif
                                @if($update->note)
                                    <p class="text-sm text-gray-600"><strong>Note:</strong> {{ $update->note }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center">No timeline updates yet.</p>
                @endif
            </div>

        </div>
    </div>
</x-guest-layout>