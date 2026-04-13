@extends('layouts.app')

@section('title', 'Transaction History')
@section('page-title', 'Referral Transactions')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Transaction History</h1>
        <p class="text-slate-500 dark:text-slate-400">View all your referral earnings and withdrawals</p>
    </div>

    <!-- Transactions List -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="p-5 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
            <h3 class="font-bold text-slate-900 dark:text-white">All Transactions</h3>
            <a href="{{ route('referrals.index') }}" class="text-emerald-600 hover:underline text-sm">← Back to Referrals</a>
        </div>

        @if($transactions->count() > 0)
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach($transactions as $tx)
                <div class="p-5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <!-- Icon -->
                            <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                                {{ $tx->amount > 0 ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }}">
                                @if($tx->amount > 0)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                    </svg>
                                @endif
                            </div>
                            
                            <!-- Details -->
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $tx->typeLabel() }}</p>
                                <p class="text-sm text-slate-500">{{ $tx->created_at->format('M d, Y \a\t h:i A') }}</p>
                                
                                @if($tx->type === 'withdrawal' && $tx->metadata)
                                    @php $bank = $tx->metadata['bank_details'] ?? null; @endphp
                                    @if($bank)
                                        <p class="text-xs text-slate-400 mt-1">
                                            {{ $bank['bank_name'] ?? '' }} - {{ $bank['account_number'] ?? '' }}
                                        </p>
                                    @endif
                                @endif
                                
                                <!-- Status Badge -->
                                @if($tx->status)
                                <div class="mt-2">
                                    @if($tx->status === 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span>
                                            PENDING - Awaiting admin approval
                                        </span>
                                    @elseif($tx->status === 'completed')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            APPROVED - Payment sent
                                        </span>
                                    @elseif($tx->status === 'rejected')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            REJECTED - Coins refunded
                                        </span>
                                    @endif
                                </div>
                                @endif
                                
                                <!-- Rejection Reason -->
                                @if($tx->status === 'rejected' && $tx->description)
                                    <div class="mt-3 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                        <p class="text-sm text-red-800 dark:text-red-300">
                                            <span class="font-semibold">Reason for rejection:</span><br>
                                            {{ $tx->description }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Amount -->
                        <span class="font-bold text-lg {{ $tx->amount > 0 ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ $tx->amount > 0 ? '+' : '' }}{{ number_format($tx->amount) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="p-5 border-t border-slate-200 dark:border-slate-700">
                {{ $transactions->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h4 class="text-lg font-medium text-slate-900 dark:text-white mb-2">No transactions yet</h4>
                <p class="text-slate-500 dark:text-slate-400">Start referring friends to earn coins!</p>
                <a href="{{ route('referrals.index') }}" class="inline-flex items-center gap-2 mt-4 text-emerald-600 hover:underline">
                    Go to Referrals
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
