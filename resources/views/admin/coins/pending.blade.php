@extends('layouts.app')

@section('title', 'Pending Coin Purchases')
@section('page-title', 'Pending Coin Purchases')

@section('content')
<div class="max-w-7xl mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Pending Payments ({{ $purchases->total() }})
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Submitted</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Bank Details</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Verify By</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($purchases as $purchase)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $purchase->created_at->diffForHumans() }}
                                    <div class="text-xs text-gray-500">{{ $purchase->created_at->format('M d, Y H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $purchase->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $purchase->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ number_format($purchase->amount_coins) }} coins</div>
                                    <div class="text-sm text-gray-500">₦{{ number_format($purchase->amount_naira) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <div>{{ $purchase->bank_name }}</div>
                                    <div class="text-gray-500">{{ $purchase->account_number }}</div>
                                    <div class="text-gray-500 text-xs">{{ $purchase->account_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-xs text-blue-700 dark:text-blue-300">
                                        <span class="font-medium">Check remark for:</span><br>
                                        <code class="font-mono bg-blue-100 dark:bg-slate-700 px-1 py-0.5 rounded">{{ $purchase->user->email }}</code>
                                    </p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <form method="POST" action="{{ route('admin.coins.purchases.approve', $purchase) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                onclick="return confirm('Approve and credit {{ number_format($purchase->amount_coins) }} coins to {{ $purchase->user->name }}?')"
                                                class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 bg-green-100 dark:bg-green-900/30 px-3 py-1 rounded">
                                                Approve
                                            </button>
                                        </form>
                                        <button type="button" 
                                            onclick="document.getElementById('reject-modal-{{ $purchase->id }}').classList.remove('hidden')"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 bg-red-100 dark:bg-red-900/30 px-3 py-1 rounded">
                                            Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    No pending purchases.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($purchases->hasPages())
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $purchases->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Reject Modals --}}
    @foreach($purchases as $purchase)
    <div id="reject-modal-{{ $purchase->id }}" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Reject Purchase</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                User: {{ $purchase->user->name }}<br>
                Amount: {{ number_format($purchase->amount_coins) }} coins
            </p>
            <form method="POST" action="{{ route('admin.coins.purchases.reject', $purchase) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason for rejection</label>
                    <textarea name="reason" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700" required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                        onclick="document.getElementById('reject-modal-{{ $purchase->id }}').classList.add('hidden')"
                        class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                        Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endforeach
@endsection
