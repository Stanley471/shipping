@extends('layouts.app')

@section('title', 'My Shipments')
@section('page-title', 'Shipments')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <!-- Coin Balance Bar -->
    @php
        $createCost = app(\App\Services\CoinService::class)->getServiceCost('create_shipment');
        $userBalance = auth()->user()->getCoinBalance();
    @endphp
    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center text-amber-600 dark:text-amber-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Your Coin Balance</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($userBalance) }} coins</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if($userBalance < $createCost)
                <span class="text-sm text-amber-600 dark:text-amber-400 font-medium">
                    Need {{ $createCost }} coins per shipment
                </span>
            @endif
            <a href="{{ route('coins.buy') }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buy Coins
            </a>
        </div>
    </div>

    @if($shipments->count())
        <!-- Desktop Table -->
        <div class="hidden md:block bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-100 dark:bg-slate-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Tracking ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Receiver</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Shipped</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                    @foreach($shipments as $shipment)
                        @php
                            $latestUpdate = $shipment->trackingUpdates->sortByDesc('occurred_at')->first();
                            $status = $latestUpdate?->status ?? 'pending';
                        @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono font-bold text-slate-900 dark:text-white">{{ $shipment->tracking_id }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-800 dark:text-slate-200">
                                {{ $shipment->receiver_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="capitalize text-slate-600 dark:text-slate-400">{{ str_replace('_', ' ', $shipment->shipment_type) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize
                                    {{ $status === 'delivered' ? 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400' : 
                                       ($status === 'cancelled' ? 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400' : 
                                       'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-400') }}">
                                    {{ str_replace('_', ' ', $status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 w-16 h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $latestUpdate?->progress ?? 0 }}%"></div>
                                    </div>
                                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ $latestUpdate?->progress ?? 0 }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-400">
                                {{ $shipment->shipped_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('shipments.edit', $shipment) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium text-sm">
                                        Edit
                                    </a>
                                    <a href="{{ route('shipments.show', $shipment) }}" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 font-medium text-sm">
                                        View
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800">
                {{ $shipments->links() }}
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-4">
            @foreach($shipments as $shipment)
                @php
                    $latestUpdate = $shipment->trackingUpdates->sortByDesc('occurred_at')->first();
                    $status = $latestUpdate?->status ?? 'pending';
                @endphp
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-mono font-bold text-slate-900 dark:text-white">{{ $shipment->tracking_id }}</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">To: {{ $shipment->receiver_name }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium capitalize
                            {{ $status === 'delivered' ? 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400' : 
                               ($status === 'cancelled' ? 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400' : 
                               'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-400') }}">
                            {{ str_replace('_', ' ', $status) }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="flex-1 h-2 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $latestUpdate?->progress ?? 0 }}%"></div>
                        </div>
                        <span class="text-xs text-slate-500 dark:text-slate-400">{{ $latestUpdate?->progress ?? 0 }}%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-slate-400 dark:text-slate-500">{{ $shipment->shipped_at->format('M d, Y') }}</span>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('shipments.edit', $shipment) }}" class="text-blue-600 dark:text-blue-400 font-medium text-sm">Edit</a>
                            <a href="{{ route('shipments.show', $shipment) }}" class="text-emerald-600 dark:text-emerald-400 font-medium text-sm">View →</a>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="mt-4">
                {{ $shipments->links() }}
            </div>
        </div>

    @else
        <!-- Empty State -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-12 text-center shadow-sm">
            <div class="w-20 h-20 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No shipments yet</h3>
            <p class="text-slate-500 dark:text-slate-400 mb-6 max-w-md mx-auto">Create your first shipment to start tracking deliveries and sharing tracking links with your customers.</p>
            <a href="{{ route('shipments.create') }}" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-emerald-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create Your First Shipment
            </a>
        </div>
    @endif
</div>
@endsection