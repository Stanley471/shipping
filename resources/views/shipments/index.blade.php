<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Shipments
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4">
                <a href="{{ route('shipments.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    + Create New Shipment
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tracking ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Receiver</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progress</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shipped At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ETA</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($shipments as $shipment)
                            @php
                                $latestUpdate = $shipment->trackingUpdates->sortByDesc('occurred_at')->first();
                            @endphp
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-mono text-sm">{{ $shipment->tracking_id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm capitalize">{{ str_replace('_', ' ', $shipment->shipment_type) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $shipment->receiver_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm capitalize">{{ str_replace('_', ' ', $latestUpdate?->status ?? 'N/A') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $latestUpdate?->progress ?? 0 }}%</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $shipment->shipped_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $shipment->eta?->format('M d, Y') ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('shipments.show', $shipment) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No shipments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $shipments->links() }}
            </div>
        </div>
    </div>
</x-app-layout>