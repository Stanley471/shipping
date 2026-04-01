@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
@php
    $recentShipments = Auth::user()->shipments()->latest()->take(5)->get();
    $totalShipments = Auth::user()->shipments()->count();
    $activeShipments = Auth::user()->shipments()->whereHas('trackingUpdates', function($q) {
        $q->where('status', '!=', 'delivered')->where('status', '!=', 'cancelled');
    })->count();
    $deliveredShipments = Auth::user()->shipments()->whereHas('trackingUpdates', function($q) {
        $q->where('status', 'delivered');
    })->count();
@endphp

<div class="max-w-7xl mx-auto">
    
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">
            Hi, {{ Auth::user()->name }}
        </h1>
        <p class="text-slate-500 dark:text-slate-400 mt-1">
            Here's what's happening with your account today.
        </p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="dashboard-card rounded-2xl p-5 border border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Total Shipments</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $totalShipments }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="dashboard-card rounded-2xl p-5 border border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Active</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $activeShipments }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="dashboard-card rounded-2xl p-5 border border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Delivered</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $deliveredShipments }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>

        <a href="{{ route('shipments.create') }}" class="dashboard-card rounded-2xl p-5 border border-emerald-200 dark:border-emerald-800 bg-emerald-600 dark:bg-emerald-700 hover:bg-emerald-700 dark:hover:bg-emerald-600 transition-colors flex items-center justify-center gap-2 group">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span class="text-white font-semibold">New Shipment</span>
        </a>
    </div>

    <!-- Main Content Grid -->
    <div class="grid lg:grid-cols-3 gap-6">
        
        <!-- Recent Shipments -->
        <div class="lg:col-span-2 dashboard-card rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="p-5 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                <h3 class="font-bold text-slate-900 dark:text-white">Recent Shipments</h3>
                <a href="{{ route('shipments.index') }}" class="text-emerald-600 dark:text-emerald-400 text-sm font-medium hover:underline">View All</a>
            </div>

            @if($recentShipments->count())
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($recentShipments as $shipment)
                        @php
                            $latest = $shipment->trackingUpdates->first();
                            $status = $latest?->status ?? 'pending';
                        @endphp
                        <a href="{{ route('shipments.show', $shipment) }}" class="flex items-center justify-between p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-mono font-semibold text-slate-900 dark:text-white">{{ $shipment->tracking_id }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">To: {{ $shipment->receiver_name }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium capitalize
                                    {{ $status === 'delivered' ? 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400' : 
                                       ($status === 'cancelled' ? 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400' : 
                                       'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-400') }}">
                                    {{ str_replace('_', ' ', $status) }}
                                </span>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">{{ $latest?->progress ?? 0 }}%</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h4 class="text-lg font-medium text-slate-900 dark:text-white mb-2">No shipments yet</h4>
                    <p class="text-slate-500 dark:text-slate-400 mb-4">Create your first shipment to get started.</p>
                    <a href="{{ route('shipments.create') }}" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl font-medium hover:bg-emerald-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create Shipment
                    </a>
                </div>
            @endif
        </div>

        <!-- Quick Actions & Help -->
        <div class="space-y-6">
            <!-- Track Shipment -->
            <div class="dashboard-card rounded-2xl p-5 border border-slate-200 dark:border-slate-700">
                <h3 class="font-bold text-slate-900 dark:text-white mb-2">Track a Shipment</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Have a tracking ID? Check the status instantly.</p>
                <a href="{{ route('tracking.index') }}" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-medium hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Track Now
                </a>
            </div>

            <!-- Quick Links -->
            <div class="dashboard-card rounded-2xl p-5 border border-slate-200 dark:border-slate-700">
                <h3 class="font-bold text-slate-900 dark:text-white mb-4">Quick Links</h3>
                <div class="space-y-2">
                    <a href="{{ route('shipments.create') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Create Shipment</span>
                    </a>
                    <a href="{{ route('shipments.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">View All Shipments</span>
                    </a>
                    <a href="{{ route('tracking.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Public Tracking</span>
                    </a>
                </div>
            </div>

            <!-- Support -->
            <div class="dashboard-card rounded-2xl p-5 border border-slate-200 dark:border-slate-700 bg-gradient-to-br from-emerald-500 to-emerald-600">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-white">Need Help?</h3>
                </div>
                <p class="text-emerald-100 text-sm mb-4">Contact our support team for assistance.</p>
                <button class="w-full py-2.5 rounded-xl bg-white text-emerald-600 font-medium hover:bg-emerald-50 transition-colors">
                    Contact Support
                </button>
            </div>
        </div>
    </div>
</div>
@endsection