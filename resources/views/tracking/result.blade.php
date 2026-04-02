@extends('layouts.tracking')

@section('title', $shipment->tracking_id . ' | Tracking Result')

@section('content')
    @php
        $latestUpdate = $shipment->trackingUpdates->first();
        $progress = $latestUpdate?->progress ?? 0;
        $status = $latestUpdate?->status ?? 'pending';
    @endphp

    <!-- Main Content -->
    <main>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            


            <!-- Status Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 md:p-8 mb-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Tracking ID</p>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-mono">{{ $shipment->tracking_id }}</h1>
                    </div>
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold uppercase
                        {{ $status === 'delivered' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                           ($status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 
                           'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200') }}">
                        <span class="w-2 h-2 rounded-full bg-current animate-pulse"></span>
                        {{ str_replace('_', ' ', $status) }}
                    </span>
                </div>

                <!-- Progress Bar -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Shipment Progress</span>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $progress }}%</span>
                    </div>
                    <div class="h-4 w-full bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500 
                            {{ $progress >= 100 ? 'bg-green-500' : ($progress >= 50 ? 'bg-emerald-500' : 'bg-yellow-500') }}" 
                            style="width: {{ $progress }}%;">
                        </div>
                    </div>
                </div>

                <!-- Quick Info Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Shipped</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $shipment->shipped_at->format('M d, Y') }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">ETA</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $shipment->eta?->format('M d, Y') ?? 'N/A' }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Type</p>
                        <p class="font-semibold text-gray-900 dark:text-white capitalize">{{ str_replace('_', ' ', $shipment->shipment_type) }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Updates</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $shipment->trackingUpdates->count() }}</p>
                    </div>
                </div>

                <!-- Shipment Details Section -->
                @if($shipment->courier || $shipment->quantity || !is_null($shipment->is_fragile))
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Shipment Details</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @if($shipment->courier)
                        <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800">
                            <p class="text-xs text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-1">Courier</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $shipment->courier }}</p>
                        </div>
                        @endif
                        @if($shipment->quantity)
                        <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl border border-purple-100 dark:border-purple-800">
                            <p class="text-xs text-purple-600 dark:text-purple-400 uppercase tracking-wider mb-1">Quantity</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $shipment->quantity }} {{ $shipment->quantity == 1 ? 'item' : 'items' }}</p>
                        </div>
                        @endif
                        @if(!is_null($shipment->is_fragile))
                        <div class="text-center p-4 {{ $shipment->is_fragile ? 'bg-red-50 dark:bg-red-900/20 border-red-100 dark:border-red-800' : 'bg-green-50 dark:bg-green-900/20 border-green-100 dark:border-green-800' }} rounded-xl border">
                            <p class="text-xs {{ $shipment->is_fragile ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }} uppercase tracking-wider mb-1">Fragile</p>
                            <p class="font-semibold text-gray-900 dark:text-white flex items-center justify-center gap-2">
                                @if($shipment->is_fragile)
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    Yes
                                @else
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    No
                                @endif
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Route Info -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 01-.553-.894L15 7m0 13V7"/>
                        </svg>
                        Route Information
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">From</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $shipment->sender_name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $shipment->pickup_location }}</p>
                            </div>
                        </div>

                        <div class="flex justify-center">
                            <svg class="w-5 h-5 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">To</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $shipment->receiver_name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $shipment->delivery_address }}</p>
                            </div>
                        </div>
                    </div>

                    @if($latestUpdate && $latestUpdate->location)
                        <div class="mt-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-100 dark:border-emerald-800">
                            <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium uppercase tracking-wider mb-1">Current Location</p>
                            <p class="text-gray-900 dark:text-white font-semibold">{{ $latestUpdate->location }}</p>
                        </div>
                    @endif
                </div>

                <!-- Timeline -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Activity Timeline
                    </h2>

                    @if($shipment->trackingUpdates->count())
                        <div class="relative space-y-6 max-h-96 overflow-y-auto pr-2">
                            <div class="absolute left-[15px] top-2 bottom-2 w-0.5 bg-gray-200 dark:bg-gray-700"></div>

                            @foreach($shipment->trackingUpdates as $update)
                                <div class="relative flex gap-4">
                                    <div class="relative z-10 flex-shrink-0">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center
                                            {{ $loop->first ? 'bg-emerald-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                                            @if($loop->first)
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            @else
                                                <div class="w-2 h-2 rounded-full bg-current"></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-1 pb-2">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h4 class="font-semibold text-gray-900 dark:text-white text-sm capitalize">
                                                {{ str_replace('_', ' ', $update->status) }}
                                            </h4>
                                            @if($loop->first)
                                                <span class="text-[10px] font-bold bg-emerald-100 dark:bg-emerald-900 text-emerald-800 dark:text-emerald-200 px-2 py-0.5 rounded">Latest</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $update->occurred_at->format('M d, Y • H:i') }}</p>
                                        
                                        @if($update->note)
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-2 bg-gray-50 dark:bg-gray-700/50 p-2 rounded">{{ $update->note }}</p>
                                        @endif

                                        <div class="mt-2 flex items-center gap-2">
                                            <div class="flex-1 h-1 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                                <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $update->progress }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $update->progress }}%</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>No updates yet</p>
                        </div>
                    @endif
                </div>
            </div>


        </div>
    </main>
@endsection