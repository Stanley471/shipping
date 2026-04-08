@extends('layouts.app')

@section('title', 'Coin Transaction Logs')
@section('page-title', 'Coin Transaction Logs')

@section('content')
<div class="max-w-7xl mx-auto">
            {{-- Filters --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                            <select name="type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                <option value="">All Types</option>
                                @foreach($types as $value => $label)
                                    <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">User</label>
                            <input type="text" name="user" value="{{ request('user') }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700"
                                placeholder="Email or name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">From Date</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">To Date</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        </div>
                        <div class="md:col-span-4 flex justify-end">
                            <a href="{{ route('admin.coins.transactions') }}" 
                               class="mr-3 px-4 py-2 text-sm text-gray-600 hover:text-gray-900">
                                Clear
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Transactions Table --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">User</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Description</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Amount</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Balance After</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Processed By</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($transactions as $tx)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $tx->created_at->format('M d, Y') }}
                                    <div class="text-xs text-gray-500">{{ $tx->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $tx->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $tx->user->email }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @switch($tx->type)
                                        @case('deposit')
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">P2P Purchase</span>
                                            @break
                                        @case('spend')
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Payment</span>
                                            @break
                                        @case('refund')
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Refund</span>
                                            @break
                                        @case('admin_add')
                                            <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">Admin +</span>
                                            @break
                                        @case('admin_deduct')
                                            <span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-800">Admin -</span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 max-w-xs truncate">
                                    {{ $tx->description }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium {{ $tx->amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $tx->amount > 0 ? '+' : '' }}{{ number_format($tx->amount) }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-100">
                                    {{ number_format($tx->balance_after) }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ $tx->processor?->name ?? 'System' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    No transactions found.
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
    </div>
@endsection
