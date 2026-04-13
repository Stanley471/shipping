@extends('layouts.app')

@section('title', 'My Referrals')
@section('page-title', 'Referral Program')

@section('content')
<div class="max-w-6xl mx-auto">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">Referral Program</h1>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Invite friends and earn referral coins!</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Referral Balance -->
        <div class="rounded-2xl p-5 border border-amber-200 dark:border-amber-800 bg-gradient-to-br from-amber-500 to-amber-600 shadow-lg shadow-amber-500/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-amber-100 font-medium">Referral Balance</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ number_format($stats['referral_balance']) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Referrals -->
        <div class="rounded-2xl p-5 border border-blue-200 dark:border-blue-800 bg-gradient-to-br from-blue-500 to-blue-600 shadow-lg shadow-blue-500/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-100 font-medium">Total Referrals</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $stats['total_referrals'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Earned -->
        <div class="rounded-2xl p-5 border border-emerald-200 dark:border-emerald-800 bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-lg shadow-emerald-500/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-emerald-100 font-medium">Total Earned</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ number_format($stats['total_earned']) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Converted/Withdrawn -->
        <div class="rounded-2xl p-5 border border-purple-200 dark:border-purple-800 bg-gradient-to-br from-purple-500 to-purple-600 shadow-lg shadow-purple-500/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-purple-100 font-medium">Used/Withdrawn</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ number_format($stats['total_converted'] + $stats['total_withdrawn']) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        
        <!-- Left Column - Referral Link & Actions -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Referral Link Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    Your Referral Link
                </h3>
                
                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-4 mb-4">
                    <div class="flex items-center gap-3">
                        <input type="text" value="{{ $stats['referral_link'] }}" readonly 
                            class="flex-1 bg-transparent text-slate-700 dark:text-slate-300 font-mono text-sm outline-none"
                            id="referralLink">
                        <button onclick="copyReferralLink()" 
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors">
                            Copy
                        </button>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4 text-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-slate-900 dark:text-white">{{ $settings->signup_bonus_amount }} coins</p>
                            <p class="text-slate-500 dark:text-slate-400">Per successful signup</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-slate-900 dark:text-white">{{ $settings->purchase_commission_percent }}%</p>
                            <p class="text-slate-500 dark:text-slate-400">Of their purchases</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="grid md:grid-cols-2 gap-4">
                <!-- Convert to Coins -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
                    <h4 class="font-bold text-slate-900 dark:text-white mb-2">Convert to Coins</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                        Rate: 1 referral coin = {{ $settings->conversion_rate }} normal coin
                    </p>
                    <form action="{{ route('referrals.convert') }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="number" name="amount" min="1" max="{{ $stats['referral_balance'] }}" 
                            placeholder="Amount" required
                            class="flex-1 px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white text-sm">
                        <button type="submit" 
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors"
                            {{ $stats['referral_balance'] <= 0 ? 'disabled' : '' }}>
                            Convert
                        </button>
                    </form>
                </div>

                <!-- Withdraw -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
                    <h4 class="font-bold text-slate-900 dark:text-white mb-2">Withdraw Cash</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                        Min: {{ $settings->min_withdrawal_amount }} coins
                    </p>
                    <button onclick="document.getElementById('withdrawModal').classList.remove('hidden')"
                        class="w-full px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-medium transition-colors"
                        {{ !$stats['can_withdraw'] ? 'disabled' : '' }}>
                        {{ $stats['can_withdraw'] ? 'Request Withdrawal' : 'Insufficient Balance' }}
                    </button>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-slate-900 dark:text-white">Recent Transactions</h3>
                    <a href="{{ route('referrals.transactions') }}" class="text-sm text-emerald-600 hover:underline">View All</a>
                </div>

                @if($transactions->count() > 0)
                    <div class="space-y-3">
                        @foreach($transactions->take(5) as $tx)
                        <div class="p-3 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center
                                        {{ $tx->amount > 0 ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }}">
                                        @if($tx->amount > 0)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $tx->typeLabel() }}</p>
                                        <p class="text-xs text-slate-500">{{ $tx->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                <span class="font-semibold {{ $tx->amount > 0 ? 'text-emerald-600' : 'text-amber-600' }}">
                                    {{ $tx->amount > 0 ? '+' : '' }}{{ number_format($tx->amount) }}
                                </span>
                            </div>
                            
                            <!-- Status Badge -->
                            @if($tx->status)
                            <div class="mt-2 flex items-center gap-2">
                                @if($tx->status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        PENDING
                                    </span>
                                @elseif($tx->status === 'completed')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        APPROVED
                                    </span>
                                @elseif($tx->status === 'rejected')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        REJECTED
                                    </span>
                                @endif
                            </div>
                            @endif
                            
                            <!-- Rejection Reason -->
                            @if($tx->status === 'rejected' && $tx->description)
                                <p class="mt-2 text-xs text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 p-2 rounded">
                                    <span class="font-medium">Reason:</span> {{ $tx->description }}
                                </p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-500 dark:text-slate-400 text-center py-4">No transactions yet</p>
                @endif
            </div>
        </div>

        <!-- Right Column - Referrals List -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="font-bold text-slate-900 dark:text-white mb-4">Your Referrals</h3>
            
            @if($referrals->count() > 0)
                <div class="space-y-3">
                    @foreach($referrals as $referral)
                    <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-bold">
                            {{ substr($referral->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-slate-900 dark:text-white truncate">{{ $referral->name }}</p>
                            <p class="text-xs text-slate-500">Joined {{ $referral->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400">No referrals yet</p>
                    <p class="text-sm text-slate-400 mt-1">Share your link to start earning!</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Withdrawal Modal -->
<div id="withdrawModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl max-w-md w-full p-6">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Request Withdrawal</h3>
        
        <form action="{{ route('referrals.withdraw') }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Amount</label>
                <input type="number" name="amount" min="{{ $settings->min_withdrawal_amount }}" max="{{ $stats['referral_balance'] }}" 
                    value="{{ $stats['referral_balance'] }}" required
                    class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                <p class="text-xs text-slate-500 mt-1">Available: {{ number_format($stats['referral_balance']) }} coins</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Bank Name</label>
                <input type="text" name="bank_name" required
                    class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Account Number</label>
                <input type="text" name="account_number" required
                    class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Account Name</label>
                <input type="text" name="account_name" required
                    class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('withdrawModal').classList.add('hidden')"
                    class="flex-1 px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg font-medium">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-medium">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function copyReferralLink() {
    const link = document.getElementById('referralLink');
    link.select();
    document.execCommand('copy');
    
    // Show feedback
    const btn = event.target;
    const originalText = btn.textContent;
    btn.textContent = 'Copied!';
    setTimeout(() => {
        btn.textContent = originalText;
    }, 2000);
}
</script>
@endsection
