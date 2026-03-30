<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Shipment
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('shipments.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Sender Name</label>
                        <input type="text" name="sender_name" value="{{ old('sender_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        @error('sender_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Receiver Name</label>
                        <input type="text" name="receiver_name" value="{{ old('receiver_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        @error('receiver_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Receiver Email (optional)</label>
                        <input type="email" name="receiver_email" value="{{ old('receiver_email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @error('receiver_email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Shipping From</label>
                        <textarea name="pickup_location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('pickup_location') }}</textarea>
                        @error('pickup_location')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Destination</label>
                        <textarea name="delivery_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('delivery_address') }}</textarea>
                        @error('delivery_address')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Shipping Started At</label>
                        <input type="datetime-local" name="shipped_at" value="{{ old('shipped_at') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        @error('shipped_at')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Estimated Delivery Date</label>
                        <input type="datetime-local" name="eta" value="{{ old('eta') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @error('eta')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Shipment Type</label>
                        <select name="shipment_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            <option value="" disabled {{ old('shipment_type') ? '' : 'selected' }}>Select shipment type</option>
                            <option value="air_freight" {{ old('shipment_type') == 'air_freight' ? 'selected' : '' }}>Air Freight</option>
                            <option value="sea_freight" {{ old('shipment_type') == 'sea_freight' ? 'selected' : '' }}>Sea Freight</option>
                            <option value="road_freight" {{ old('shipment_type') == 'road_freight' ? 'selected' : '' }}>Road Freight</option>
                            <option value="express_delivery" {{ old('shipment_type') == 'express_delivery' ? 'selected' : '' }}>Express Delivery</option>
                        </select>
                        @error('shipment_type')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <hr class="my-6">

                    <h3 class="font-semibold text-lg mb-4">Initial Tracking Update</h3>

                    <div class="mb-4">
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

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Current Location</label>
                        <input type="text" name="location" value="{{ old('location') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @error('location')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Note</label>
                        <textarea name="note" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('note') }}</textarea>
                        @error('note')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Progress (%)</label>
                        <input type="number" name="progress" value="{{ old('progress', 0) }}" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        @error('progress')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Update Occurred At</label>
                        <input type="datetime-local" name="occurred_at" value="{{ old('occurred_at') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        @error('occurred_at')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Create Shipment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>