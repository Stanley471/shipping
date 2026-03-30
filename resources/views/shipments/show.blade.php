<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Shipment Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Shipment Info Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-sm text-gray-500">Tracking ID</p>
                        <p class="text-2xl font-mono font-bold">{{ $shipment->tracking_id }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Type</p>
                        <p class="text-lg capitalize">{{ str_replace('_', ' ', $shipment->shipment_type) }}</p>
                    </div>
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

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Shipped At</p>
                        <p class="font-medium">{{ $shipment->shipped_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">ETA</p>
                        <p class="font-medium">{{ $shipment->eta?->format('M d, Y H:i') ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Receiver Email</p>
                        <p class="font-medium">{{ $shipment->receiver_email ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Current Status & Progress -->
            @php
                $latestUpdate = $shipment->trackingUpdates->first();
            @endphp

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold text-lg">Current Status</h3>
                    <span class="text-sm text-gray-500">{{ $latestUpdate?->occurred_at?->format('M d, Y H:i') ?? '' }}</span>
                </div>
                
                <p class="text-xl capitalize mb-4">{{ str_replace('_', ' ', $latestUpdate?->status ?? 'No updates yet') }}</p>
                
                @if($latestUpdate)
                    <div class="mb-2">
                        <div class="flex justify-between text-sm mb-1">
                            <span>Progress</span>
                            <span>{{ $latestUpdate->progress }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $latestUpdate->progress }}%"></div>
                        </div>
                    </div>
                    @if($latestUpdate->location)
                        <p class="text-sm text-gray-600 mt-2"><strong>Location:</strong> {{ $latestUpdate->location }}</p>
                    @endif
                    @if($latestUpdate->note)
                        <p class="text-sm text-gray-600 mt-1"><strong>Note:</strong> {{ $latestUpdate->note }}</p>
                    @endif
                @endif
            </div>

            <!-- Add Tracking Update Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-4">Add Tracking Update</h3>
                <form method="POST" action="{{ route('tracking-updates.store', $shipment) }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_transit" {{ old('status') == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                <option value="out_for_delivery" {{ old('status') == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                <option value="delivered" {{ old('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Progress (%)</label>
                            <input type="number" name="progress" value="{{ old('progress', $latestUpdate?->progress ?? 0) }}" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('progress')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Location</label>
                        <input type="text" name="location" value="{{ old('location') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @error('location')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Note</label>
                        <textarea name="note" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('note') }}</textarea>
                        @error('note')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Occurred At</label>
                        <input type="datetime-local" name="occurred_at" value="{{ old('occurred_at') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        @error('occurred_at')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Add Update
                        </button>
                    </div>
                </form>
            </div>

            <!-- Timeline -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-4">Timeline</h3>
                
                @if($shipment->trackingUpdates->count())
                    <div class="relative border-l-2 border-gray-200 ml-3 space-y-6">
                        @foreach($shipment->trackingUpdates as $update)
                            <div class="ml-6">
                                <span class="absolute -left-2 w-4 h-4 bg-blue-600 rounded-full border-2 border-white"></span>
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
                    <p class="text-gray-500">No updates yet.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>