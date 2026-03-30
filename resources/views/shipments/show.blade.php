<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Shipment Details
            </h2>
            <span class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $shipment->tracking_id }}</span>
        </div>
    </x-slot>

    @php
        $latestUpdate = $shipment->trackingUpdates->first();
        $progress = $latestUpdate?->progress ?? 0;
        $status = $latestUpdate?->status ?? 'pending';
    @endphp

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-400 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <!-- Left Column: Summary & Details -->
                <div class="lg:col-span-7 space-y-6">
                    
                    <!-- Summary Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-5">
                            <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                            </svg>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 relative z-10">
                            <div>
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-3
                                    {{ $status === 'delivered' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                       ($status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 
                                       'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200') }}">
                                    <span class="w-2 h-2 rounded-full bg-current animate-pulse"></span>
                                    {{ str_replace('_', ' ', $status) }}
                                </span>
                                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white tracking-tight font-mono">
                                    {{ $shipment->tracking_id }}
                                </h1>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ str_replace('_', ' ', $shipment->shipment_type) }} • Est. Delivery: {{ $shipment->eta?->format('M d, Y') ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('shipments.index') }}" class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg font-medium text-sm hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                    Back
                                </a>
                            </div>
                        </div>

                        <!-- Progress Visualization -->
                        <div class="mt-8 space-y-2">
                            <div class="flex justify-between items-end">
                                <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Journey Progress</span>
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $progress }}%</span>
                            </div>
                            <div class="h-3 w-full bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500 
                                    {{ $progress >= 100 ? 'bg-green-500' : 'bg-emerald-500' }}" 
                                    style="width: {{ $progress }}%;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Origin & Destination Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Sender -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700 flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">Origin</p>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $shipment->sender_name }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed mt-1">
                                    {{ $shipment->pickup_location }}
                                </p>
                            </div>
                        </div>

                        <!-- Receiver -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700 flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">Destination</p>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $shipment->receiver_name }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed mt-1">
                                    {{ $shipment->delivery_address }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Info Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-md border border-gray-100 dark:border-gray-700 text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Shipped</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $shipment->shipped_at->format('M d, Y') }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-md border border-gray-100 dark:border-gray-700 text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">ETA</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $shipment->eta?->format('M d, Y') ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-md border border-gray-100 dark:border-gray-700 text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Type</p>
                            <p class="font-semibold text-gray-900 dark:text-white capitalize">{{ str_replace('_', ' ', $shipment->shipment_type) }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-md border border-gray-100 dark:border-gray-700 text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Updates</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $shipment->trackingUpdates->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Timeline & Add Update -->
                <div class="lg:col-span-5 space-y-6">
                    
                    <!-- Add Update Form -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Add Tracking Update</h3>
                        <form method="POST" action="{{ route('tracking-updates.store', $shipment) }}" class="space-y-4">
                            @csrf

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 uppercase mb-1">Status</label>
                                    <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                                        <option value="pending">Pending</option>
                                        <option value="in_transit">In Transit</option>
                                        <option value="out_for_delivery">Out for Delivery</option>
                                        <option value="delivered">Delivered</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                    @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 uppercase mb-1">Progress (%)</label>
                                    <input type="number" name="progress" value="{{ old('progress', $latestUpdate?->progress ?? 0) }}" min="0" max="100" 
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                                    @error('progress')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 uppercase mb-1">Location</label>
                                <input type="text" name="location" value="{{ old('location') }}" placeholder="e.g., Brussels Hub" 
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                @error('location')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 uppercase mb-1">Note</label>
                                <textarea name="note" rows="2" placeholder="Optional details..." 
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('note') }}</textarea>
                                @error('note')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 uppercase mb-1">Occurred At</label>
                                <input type="datetime-local" name="occurred_at" value="{{ old('occurred_at') }}" 
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                                @error('occurred_at')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-lg transition-colors text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                Add Update
                            </button>
                        </form>
                    </div>

                    <!-- Timeline -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Activity Timeline</h3>
                        
                        @if($shipment->trackingUpdates->count())
                            <div class="relative space-y-8">
                                <!-- Vertical Line -->
                                <div class="absolute left-[11px] top-2 bottom-2 w-0.5 bg-gray-200 dark:bg-gray-700"></div>

                                @foreach($shipment->trackingUpdates as $update)
                                    <div class="relative flex gap-4">
                                        <div class="relative z-10 flex-shrink-0">
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center border-2 
                                                {{ $loop->first ? 'bg-emerald-500 border-emerald-500 text-white' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600' }}">
                                                @if($loop->first)
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                @else
                                                    <div class="w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-600"></div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-1 pb-2">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <h4 class="font-bold text-gray-900 dark:text-white text-sm capitalize">
                                                    {{ str_replace('_', ' ', $update->status) }}
                                                </h4>
                                                @if($loop->first)
                                                    <span class="text-[10px] font-bold bg-emerald-100 dark:bg-emerald-900 text-emerald-800 dark:text-emerald-200 px-2 py-0.5 rounded">Current</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $update->location ?? 'Location not specified' }}</p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 font-medium">{{ $update->occurred_at->format('M d, Y • H:i') }}</p>
                                            
                                            @if($update->note)
                                                <div class="mt-2 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-xs text-gray-600 dark:text-gray-300">
                                                    {{ $update->note }}
                                                </div>
                                            @endif

                                            <div class="mt-2 flex items-center gap-2">
                                                <div class="flex-1 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                                    <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $update->progress }}%"></div>
                                                </div>
                                                <span class="text-xs font-medium text-gray-600 dark:text-gray-400">{{ $update->progress }}%</span>
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
                                <p>No updates yet.</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 w-full bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 md:hidden z-50">
        <div class="flex justify-around items-center py-2">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center p-2 text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="text-[10px] mt-1">Home</span>
            </a>
            <a href="{{ route('shipments.index') }}" class="flex flex-col items-center p-2 text-emerald-600 dark:text-emerald-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span class="text-[10px] mt-1">Shipments</span>
            </a>
            <a href="{{ route('shipments.create') }}" class="flex flex-col items-center p-2 text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="text-[10px] mt-1">New</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="flex flex-col items-center p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400">
                @csrf
                <button type="submit" class="flex flex-col items-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="text-[10px] mt-1">Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <div class="h-16 md:hidden"></div>
</x-app-layout>