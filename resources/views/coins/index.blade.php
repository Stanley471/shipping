@extends('layouts.app')

@section('title', 'My Coins')
@section('page-title', 'My Coins')

@section('content')
<div class="max-w-7xl mx-auto">
            {{-- Balance Card --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Current Balance</p>
                            <p class="text-4xl font-bold text-indigo-600 dark:text-indigo-400">
                                {{ number_format($balance) }} <span class="text-lg">coins</span>
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">1 coin = ₦1 Naira</p>
                        </div>
                        <a href="{{ route('coins.buy') }}" 
                           class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Buy Coins
                        </a>
                    </div>
                </div>
            </div>

        

            {{-- Pending Purchases --}}
            @if($purchases->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Pending Purchases</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($purchases as $purchase)
                        <div class="flex items-center justify-between p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ number_format($purchase->amount_coins) }} coins
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    ₦{{ number_format($purchase->amount_naira) }} • {{ $purchase->bank_name }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    Submitted {{ $purchase->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                Pending
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Transaction History --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Transaction History</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Balance</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($transactions as $tx)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $tx->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($tx->type)
                                        @case('deposit')
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Deposit</span>
                                            @break
                                        @case('spend')
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Spend</span>
                                            @break
                                        @case('refund')
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Refund</span>
                                            @break
                                        @case('admin_add')
                                            <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">Credit</span>
                                            @break
                                        @case('admin_deduct')
                                            <span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">Deduction</span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $tx->description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium {{ $tx->amount > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $tx->amount > 0 ? '+' : '' }}{{ number_format($tx->amount) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-100">
                                    {{ number_format($tx->balance_after) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No transactions yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($transactions->hasPages())
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $transactions->links() }}
                </div>
                @endif
            </div>
</div>
@endsection
