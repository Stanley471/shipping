<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <h2 class="text-2xl font-bold text-center mb-6">Track Your Shipment</h2>

            <form method="POST" action="{{ route('tracking.search') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Tracking ID</label>
                    <input type="text" name="tracking_id" value="{{ old('tracking_id') }}" placeholder="TRK89EE9EE8E" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm uppercase" required>
                    @error('tracking_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 w-full">
                        Track Shipment
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>