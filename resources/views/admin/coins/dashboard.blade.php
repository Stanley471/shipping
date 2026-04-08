@extends('layouts.app')

@section('title', 'Coin System Dashboard')
@section('page-title', 'Coin System Dashboard')

@section('content')
<div class="max-w-7xl mx-auto">
            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- Total in Circulation --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Coins in Circulation</p>
                        <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">
                            {{ number_format($stats['total_coins_in_circulation']) }}
                        </p>
                    </div>
                </div>

                {{-- Total Earned --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Lifetime Earned</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400">
                            {{ number_format($stats['total_earned']) }}
                        </p>
                    </div>
                </div>

                {{-- Total Spent --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Lifetime Spent</p>
                        <p class="text-3xl font-bold text-red-600 dark:text-red-400">
                            {{ number_format($stats['total_spent']) }}
                        </p>
                    </div>
                </div>

                {{-- Pending Purchases --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Pending Purchases</p>
                        <div class="flex items-center">
                            <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">
                                {{ $stats['pending_purchases'] }}
                            </p>
                            @if($stats['pending_purchases'] > 0)
                            <a href="{{ route('admin.coins.pending') }}" class="ml-3 text-sm text-indigo-600 hover:text-indigo-900">View &rarr;</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Quick Actions</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('admin.coins.pending') }}" 
                       class="flex items-center p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/30">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="ml-3 font-medium text-indigo-900 dark:text-indigo-100">Approve Purchases</span>
                    </a>

                    <a href="{{ route('admin.coins.adjustment') }}" 
                       class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="ml-3 font-medium text-purple-900 dark:text-purple-100">Manual Adjustment</span>
                    </a>

                    <a href="{{ route('admin.coins.transactions') }}" 
                       class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span class="ml-3 font-medium text-blue-900 dark:text-blue-100">Transaction Logs</span>
                    </a>

                    <a href="{{ route('admin.coins.services') }}" 
                       class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="ml-3 font-medium text-green-900 dark:text-green-100">Service Pricing</span>
                    </a>
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Today's Activity</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Transactions Today</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total_transactions_today'] }}</p>
                        </div>
                        <a href="{{ route('admin.coins.transactions') }}" class="text-indigo-600 hover:text-indigo-900">
                            View All &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection
