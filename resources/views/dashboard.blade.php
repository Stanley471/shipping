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
        <!-- Total Shipments - Blue -->
        <div class="rounded-2xl p-5 border border-blue-200 dark:border-blue-800 bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 shadow-lg shadow-blue-500/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-100 font-medium">Total Shipments</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $totalShipments }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active - Amber -->
        <div class="rounded-2xl p-5 border border-amber-200 dark:border-amber-800 bg-gradient-to-br from-amber-500 to-amber-600 dark:from-amber-600 dark:to-amber-700 shadow-lg shadow-amber-500/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-amber-100 font-medium">Active</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $activeShipments }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Delivered - Green -->
        <div class="rounded-2xl p-5 border border-green-200 dark:border-green-800 bg-gradient-to-br from-green-500 to-green-600 dark:from-green-600 dark:to-green-700 shadow-lg shadow-green-500/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-100 font-medium">Delivered</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $deliveredShipments }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- New Shipment Button -->
        <a href="{{ route('shipments.create') }}" class="rounded-2xl p-5 border border-emerald-200 dark:border-emerald-800 bg-gradient-to-br from-emerald-500 to-emerald-600 dark:from-emerald-600 dark:to-emerald-700 shadow-lg shadow-emerald-500/20 hover:shadow-xl hover:scale-[1.02] transition-all flex flex-col items-center justify-center gap-2 group">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-sm group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <span class="text-white font-semibold text-sm">New Shipment</span>
        </a>
    </div>

    <!-- Main Content Grid -->
    <div class="grid lg:grid-cols-3 gap-6">
        
        <!-- Recent Shipments -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
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
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm">
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
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm">
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
            <div class="rounded-2xl p-5 border border-emerald-500 bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-sm">
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

<!-- Telegram Channel Popup -->
<div id="telegramPopup" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeTelegramPopup()"></div>
    
    <!-- Modal Panel -->
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200 dark:border-slate-700">
                <!-- Header with Telegram Color -->
                <div class="bg-gradient-to-r from-sky-500 to-sky-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                            </svg>
                            <h3 class="text-lg font-bold text-white" id="modal-title">Join Our Telegram!</h3>
                        </div>
                        <button onclick="closeTelegramPopup()" class="text-white/80 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Body -->
                <div class="px-6 py-6">
                    <div class="text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-sky-100 dark:bg-sky-900/30 mb-4">
                            <svg class="h-8 w-8 text-sky-600 dark:text-sky-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                            </svg>
                        </div>
                        
                        <h4 class="text-xl font-bold text-slate-900 dark:text-white mb-2">
                            Stay Updated!
                        </h4>
                        <p class="text-slate-500 dark:text-slate-400 mb-6">
                            Join our Telegram channel for exclusive updates, tips, and announcements about Ctools.
                        </p>
                        
                        <!-- Join Button -->
                        <a href="{{ config('app.telegram_channel_url') }}" target="_blank" onclick="joinTelegram()" 
                           class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-sky-500 px-6 py-3.5 text-white font-semibold shadow-lg shadow-sky-500/30 hover:bg-sky-600 hover:shadow-sky-600/40 transition-all">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                            </svg>
                            Join Telegram Channel
                        </a>
                        
                        <!-- Dismiss Button -->
                        <button onclick="dismissTelegramPopup()" class="mt-4 text-sm text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                            Maybe later
                        </button>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="bg-slate-50 dark:bg-slate-700/50 px-6 py-4">
                    <p class="text-xs text-center text-slate-500 dark:text-slate-400">
                        Get real-time notifications and connect with our community
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Check if popup should be shown (every 7 days)
    document.addEventListener('DOMContentLoaded', function() {
        const SEVEN_DAYS = 7 * 24 * 60 * 60 * 1000; // 7 days in milliseconds
        const lastDismissed = localStorage.getItem('telegramPopupDismissedAt');
        const now = Date.now();
        
        // Show if never dismissed OR if 7 days have passed since last dismissal
        if (!lastDismissed || (now - parseInt(lastDismissed)) > SEVEN_DAYS) {
            // Show popup after a short delay
            setTimeout(function() {
                document.getElementById('telegramPopup').classList.remove('hidden');
            }, 1000);
        }
    });
    
    function closeTelegramPopup() {
        document.getElementById('telegramPopup').classList.add('hidden');
    }
    
    function dismissTelegramPopup() {
        // Store timestamp of dismissal
        localStorage.setItem('telegramPopupDismissedAt', Date.now().toString());
        closeTelegramPopup();
    }
    
    function joinTelegram() {
        // Mark as joined so it doesn't show again (or use a much longer period)
        localStorage.setItem('telegramPopupDismissedAt', Date.now().toString());
        // Let the link open naturally
    }
</script>
@endsection