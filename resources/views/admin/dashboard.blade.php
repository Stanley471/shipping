<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Total Users</p>
                    <p class="text-3xl font-bold">{{ $totalUsers }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Total Shipments</p>
                    <p class="text-3xl font-bold">{{ $totalShipments }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-4">Recent Shipments</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left">Tracking ID</th>
                            <th class="px-4 py-2 text-left">User</th>
                            <th class="px-4 py-2 text-left">Receiver</th>
                            <th class="px-4 py-2 text-left">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentShipments as $shipment)
                        <tr>
                            <td class="px-4 py-2 font-mono">{{ $shipment->tracking_id }}</td>
                            <td class="px-4 py-2">{{ $shipment->user->name }}</td>
                            <td class="px-4 py-2">{{ $shipment->receiver_name }}</td>
                            <td class="px-4 py-2">{{ $shipment->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>