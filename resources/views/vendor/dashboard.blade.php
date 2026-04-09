@extends('layouts.app')

@section('title', 'Vendor Dashboard')
@section('page-title', 'Vendor Dashboard')

@section('content')
<div class="max-w-6xl mx-auto">
    
    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">
                Vendor Dashboard
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">
                Manage your P2P sales and profile
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('vendor.orders') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 rounded-xl font-medium text-sm">
                All Orders
            </a>
            <a href="{{ route('vendor.edit') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-medium text-sm">
                Edit Profile
            </a>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700">
            <p class="text-sm text-slate-500 dark:text-slate-400">Total Sales</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['total_sales']) }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700">
            <p class="text-sm text-slate-500 dark:text-slate-400">Pending Orders</p>
            <p class="text-2xl font-bold text-amber-600">{{ $stats['pending_orders'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700">
            <p class="text-sm text-slate-500 dark:text-slate-400">Total Earnings</p>
            <p class="text-2xl font-bold text-emerald-600">₦{{ number_format($stats['total_earnings']) }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700">
            <p class="text-sm text-slate-500 dark:text-slate-400">Rating</p>
            <p class="text-2xl font-bold text-amber-500">★ {{ $stats['rating'] > 0 ? $stats['rating'] : 'N/A' }}</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        
        {{-- Profile Card --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Your Profile</h2>
            
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                    <span class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ substr($vendor->display_name, 0, 1) }}</span>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 dark:text-white">{{ $vendor->display_name }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $vendor->bank_name }}</p>
                </div>
            </div>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500 dark:text-slate-400">Rate</span>
                    <span class="font-medium text-slate-900 dark:text-white">₦{{ number_format($vendor->rate, 2) }}/coin</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 dark:text-slate-400">Min Order</span>
                    <span class="font-medium text-slate-900 dark:text-white">{{ number_format($vendor->min_limit) }} coins</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 dark:text-slate-400">Max Order</span>
                    <span class="font-medium text-slate-900 dark:text-white">{{ number_format($vendor->max_limit) }} coins</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 dark:text-slate-400">Response Time</span>
                    <span class="font-medium text-slate-900 dark:text-white">~{{ $vendor->avg_response_time }} mins</span>
                </div>
            </div>

            @if($vendor->vendor_info)
            <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                <p class="text-sm text-slate-600 dark:text-slate-300">{{ $vendor->vendor_info }}</p>
            </div>
            @endif
        </div>

        {{-- Recent Orders --}}
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Recent Orders</h2>
            
            @if($recentOrders->count() > 0)
            <div class="space-y-4">
                @foreach($recentOrders as $order)
                <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                    <div>
                        <p class="font-semibold text-slate-900 dark:text-white">{{ number_format($order->amount_coins) }} Coins</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $order->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-slate-900 dark:text-white">₦{{ number_format($order->amount_naira) }}</p>
                        @switch($order->status)
                            @case('pending')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">
                                    Pending
                                </span>
                                @break
                            @case('approved')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    Completed
                                </span>
                                @break
                            @case('rejected')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                    Rejected
                                </span>
                                @break
                        @endswitch
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <p class="text-slate-500 dark:text-slate-400">No orders yet</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
