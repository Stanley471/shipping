@extends('layouts.app')

@section('title', 'Shipment ' . $shipment->tracking_id)
@section('page-title', 'Shipment Details')

@section('content')
@php
    $latestUpdate = $shipment->trackingUpdates->first();
    $progress = $latestUpdate?->progress ?? 0;
    $status = $latestUpdate?->status ?? 'pending';
@endphp

<div class="max-w-7xl mx-auto">
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left Column: Summary & Details -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Summary Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 relative overflow-hidden shadow-sm">
                <div class="absolute top-0 right-0 p-4 opacity-5">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                        <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                    </svg>
                </div>

                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 relative z-10">
                    <div>
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-3
                            {{ $status === 'delivered' ? 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400' : 
                               ($status === 'cancelled' ? 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400' : 
                               'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-400') }}">
                            <span class="w-2 h-2 rounded-full bg-current animate-pulse"></span>
                            {{ str_replace('_', ' ', $status) }}
                        </span>
                        <h1 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white tracking-tight font-mono">
                            {{ $shipment->tracking_id }}
                        </h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                            {{ str_replace('_', ' ', $shipment->shipment_type) }} • Est. Delivery: {{ $shipment->eta?->format('M d, Y') ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('shipments.index') }}" class="bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 px-4 py-2 rounded-lg font-medium text-sm hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                            Back
                        </a>
                        <a href="{{ route('shipments.edit', $shipment) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors">
                            Edit
                        </a>
                        <a href="{{ route('shipments.pdf', $shipment) }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            PDF
                        </a>
                    </div>
                </div>

                <!-- Progress Visualization -->
                <div class="mt-8 space-y-2">
                    <div class="flex justify-between items-end">
                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Journey Progress</span>
                        <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ $progress }}%</span>
                    </div>
                    <div class="h-3 w-full bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
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
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 flex items-start gap-4 shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-600 dark:text-slate-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Origin</p>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $shipment->sender_name }}</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mt-1">
                            {{ $shipment->pickup_location }}
                        </p>
                    </div>
                </div>

                <!-- Receiver -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 flex items-start gap-4 shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Destination</p>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $shipment->receiver_name }}</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mt-1">
                            {{ $shipment->delivery_address }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Info Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 text-center shadow-sm">
                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Shipped</p>
                    <p class="font-semibold text-slate-900 dark:text-white">{{ $shipment->shipped_at->format('M d, Y') }}</p>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 text-center shadow-sm">
                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">ETA</p>
                    <p class="font-semibold text-slate-900 dark:text-white">{{ $shipment->eta?->format('M d, Y') ?? 'N/A' }}</p>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 text-center shadow-sm">
                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Type</p>
                    <p class="font-semibold text-slate-900 dark:text-white capitalize">{{ str_replace('_', ' ', $shipment->shipment_type) }}</p>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 text-center shadow-sm">
                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Updates</p>
                    <p class="font-semibold text-slate-900 dark:text-white">{{ $shipment->trackingUpdates->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Right Column: Timeline & Add Update -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Add Update Form -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Add Tracking Update</h3>
                <form method="POST" action="{{ route('tracking-updates.store', $shipment) }}" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 uppercase mb-1">Status</label>
                            <select name="status" class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white text-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                                <option value="pending">Pending</option>
                                <option value="in_transit">In Transit</option>
                                <option value="out_for_delivery">Out for Delivery</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 uppercase mb-1">Progress (%)</label>
                            <input type="number" name="progress" value="{{ old('progress', $latestUpdate?->progress ?? 0) }}" min="0" max="100" 
                                class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white text-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                            @error('progress')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 uppercase mb-1">Location</label>
                        <input type="text" name="location" value="{{ old('location') }}" placeholder="e.g., Brussels Hub" 
                            class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        @error('location')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 uppercase mb-1">Note</label>
                        <textarea name="note" rows="2" placeholder="Optional details..." 
                            class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white text-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('note') }}</textarea>
                        @error('note')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 uppercase mb-1">Occurred At</label>
                        <input type="datetime-local" name="occurred_at" value="{{ old('occurred_at') }}" 
                            class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white text-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                        @error('occurred_at')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Email Notification Checkbox -->
                    <div class="pt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="send_email" value="1" {{ old('send_email') ? 'checked' : '' }} 
                                class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="text-xs text-slate-600 dark:text-slate-400">Send email notification to customer</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition-colors text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800">
                        Add Update
                    </button>
                </form>
            </div>

            <!-- Timeline -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6">Activity Timeline</h3>
                
                @if($shipment->trackingUpdates->count())
                    <div class="relative space-y-8">
                        <!-- Vertical Line -->
                        <div class="absolute left-[11px] top-2 bottom-2 w-0.5 bg-slate-200 dark:bg-slate-700"></div>

                        @foreach($shipment->trackingUpdates as $update)
                            <div class="relative flex gap-4">
                                <div class="relative z-10 flex-shrink-0">
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center border-2 
                                        {{ $loop->first ? 'bg-emerald-500 border-emerald-500 text-white' : 'bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-600' }}">
                                        @if($loop->first)
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        @else
                                            <div class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-600"></div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1 pb-2">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="font-bold text-slate-900 dark:text-white text-sm capitalize">
                                            {{ str_replace('_', ' ', $update->status) }}
                                        </h4>
                                        @if($loop->first)
                                            <span class="text-[10px] font-bold bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400 px-2 py-0.5 rounded">Current</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">{{ $update->location ?? 'Location not specified' }}</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">{{ $update->occurred_at->format('M d, Y • H:i') }}</p>
                                    
                                    @if($update->note)
                                        <div class="mt-2 p-3 bg-slate-50 dark:bg-slate-700/50 rounded-lg text-xs text-slate-600 dark:text-slate-300">
                                            {{ $update->note }}
                                        </div>
                                    @endif

                                    <div class="mt-2 flex items-center gap-2">
                                        <div class="flex-1 h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                            <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $update->progress }}%"></div>
                                        </div>
                                        <span class="text-xs font-medium text-slate-600 dark:text-slate-400">{{ $update->progress }}%</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-slate-500 dark:text-slate-400">
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
@endsection