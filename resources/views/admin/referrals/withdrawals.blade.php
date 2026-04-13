@extends('layouts.app')

@section('title', 'Withdrawal Requests')
@section('page-title', 'Pending Withdrawals')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5">
            <p class="text-sm text-slate-500 dark:text-slate-400">Pending Requests</p>
            <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $withdrawals->total() }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5">
            <p class="text-sm text-slate-500 dark:text-slate-400">Total Amount</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white">
                {{ number_format($withdrawals->sum(function($w) { return abs($w->amount); })) }} coins
            </p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5">
            <p class="text-sm text-slate-500 dark:text-slate-400">Min Withdrawal</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white">
                {{ number_format(\App\Models\ReferralSetting::getSettings()->min_withdrawal_amount) }} coins
            </p>
        </div>
    </div>

    <!-- Withdrawals Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="p-5 border-b border-slate-200 dark:border-slate-700">
            <h3 class="font-bold text-slate-900 dark:text-white">Pending Withdrawal Requests</h3>
        </div>

        @if($withdrawals->count())
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 dark:bg-slate-700/50">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">User</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Amount</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Bank Details</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Requested</th>
                            <th class="text-right px-5 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($withdrawals as $withdrawal)
                        @php
                            $bankDetails = $withdrawal->metadata['bank_details'] ?? [];
                        @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center">
                                        <span class="text-emerald-600 dark:text-emerald-400 font-semibold">
                                            {{ substr($withdrawal->user->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-white">{{ $withdrawal->user->name }}</p>
                                        <p class="text-sm text-slate-500">{{ $withdrawal->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-bold text-slate-900 dark:text-white">{{ number_format(abs($withdrawal->amount)) }} coins</p>
                                <p class="text-xs text-slate-500">Balance after: {{ number_format($withdrawal->balance_after) }}</p>
                            </td>
                            <td class="px-5 py-4">
                                @if(!empty($bankDetails))
                                    <div class="text-sm">
                                        <p class="font-medium text-slate-900 dark:text-white">{{ $bankDetails['bank_name'] ?? 'N/A' }}</p>
                                        <p class="text-slate-500">{{ $bankDetails['account_number'] ?? 'N/A' }}</p>
                                        <p class="text-slate-500">{{ $bankDetails['account_name'] ?? 'N/A' }}</p>
                                    </div>
                                @else
                                    <span class="text-sm text-slate-400">No bank details</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-sm text-slate-600 dark:text-slate-400">
                                    {{ $withdrawal->created_at->diffForHumans() }}
                                </p>
                                <p class="text-xs text-slate-400">
                                    {{ $withdrawal->created_at->format('M d, Y H:i') }}
                                </p>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Approve Button -->
                                    <form method="POST" action="{{ route('admin.referrals.approve', $withdrawal) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                            onclick="return confirm('Approve withdrawal of {{ number_format(abs($withdrawal->amount)) }} coins to {{ $withdrawal->user->name }}?')"
                                            class="px-3 py-1.5 bg-emerald-100 hover:bg-emerald-200 dark:bg-emerald-900/30 dark:hover:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400 rounded-lg text-sm font-medium transition-colors">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Approve
                                            </span>
                                        </button>
                                    </form>

                                    <!-- Reject Button -->
                                    <button type="button" 
                                        onclick="showRejectModal({{ $withdrawal->id }}, '{{ number_format(abs($withdrawal->amount)) }}', '{{ $withdrawal->user->name }}')"
                                        class="px-3 py-1.5 bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 rounded-lg text-sm font-medium transition-colors">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Reject
                                        </span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-slate-200 dark:border-slate-700">
                {{ $withdrawals->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h4 class="text-lg font-medium text-slate-900 dark:text-white mb-2">No pending withdrawals</h4>
                <p class="text-slate-500 dark:text-slate-400">All withdrawal requests have been processed.</p>
            </div>
        @endif
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="hideRejectModal()"></div>
    
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200 dark:border-slate-700">
                <div class="bg-red-50 dark:bg-red-900/20 px-6 py-4 border-b border-red-100 dark:border-red-800">
                    <h3 class="text-lg font-bold text-red-800 dark:text-red-200" id="modal-title">Reject Withdrawal</h3>
                </div>
                
                <form id="rejectForm" method="POST" action="">
                    @csrf
                    <div class="px-6 py-4">
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">
                            You are rejecting the withdrawal request from <strong id="rejectUserName"></strong> 
                            for <strong id="rejectAmount"></strong> coins. The coins will be refunded to the user's balance.
                        </p>
                        
                        <div>
                            <label for="reason" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Reason for Rejection <span class="text-red-500">*</span>
                            </label>
                            <textarea id="reason" name="reason" rows="3" required
                                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-500"
                                placeholder="e.g., Invalid bank details, Account verification required..."></textarea>
                        </div>
                    </div>
                    
                    <div class="bg-slate-50 dark:bg-slate-700/50 px-6 py-4 flex justify-end gap-3">
                        <button type="button" onclick="hideRejectModal()" 
                            class="px-4 py-2 bg-slate-200 dark:bg-slate-600 hover:bg-slate-300 dark:hover:bg-slate-500 text-slate-700 dark:text-slate-200 rounded-lg font-medium transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                            Confirm Rejection
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function showRejectModal(transactionId, amount, userName) {
        document.getElementById('rejectUserName').textContent = userName;
        document.getElementById('rejectAmount').textContent = amount;
        document.getElementById('rejectForm').action = `/admin/referrals/withdrawals/${transactionId}/reject`;
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function hideRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('reason').value = '';
    }
</script>
@endsection
