@extends('layouts.app')

@section('title', 'Referral Settings')
@section('page-title', 'Referral Settings')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 md:p-8">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">Referral Program Settings</h1>
        
        <form action="{{ route('admin.referrals.settings') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Signup Bonus -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Signup Bonus Amount (coins)
                    </label>
                    <input type="number" name="signup_bonus_amount" value="{{ $settings->signup_bonus_amount }}" 
                        min="0" max="10000" required
                        class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                    <p class="text-xs text-slate-500 mt-1">Coins given to referrer when someone signs up</p>
                </div>

                <!-- Commission Percent -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Purchase Commission (%)
                    </label>
                    <input type="number" name="purchase_commission_percent" value="{{ $settings->purchase_commission_percent }}" 
                        min="0" max="100" step="0.01" required
                        class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                    <p class="text-xs text-slate-500 mt-1">Percentage of referred user's coin purchases</p>
                </div>

                <!-- Min Withdrawal -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Minimum Withdrawal (coins)
                    </label>
                    <input type="number" name="min_withdrawal_amount" value="{{ $settings->min_withdrawal_amount }}" 
                        min="100" max="100000" required
                        class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                    <p class="text-xs text-slate-500 mt-1">Minimum coins required for withdrawal</p>
                </div>

                <!-- Conversion Rate -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Conversion Rate
                    </label>
                    <input type="number" name="conversion_rate" value="{{ $settings->conversion_rate }}" 
                        min="0.01" max="10" step="0.01" required
                        class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                    <p class="text-xs text-slate-500 mt-1">1 referral coin = X normal coins</p>
                </div>
            </div>

            <!-- Active Status -->
            <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                <input type="checkbox" name="is_active" value="1" {{ $settings->is_active ? 'checked' : '' }}
                    class="w-5 h-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <div>
                    <label class="font-medium text-slate-900 dark:text-white">Enable Referral System</label>
                    <p class="text-sm text-slate-500">Turn on/off the entire referral program</p>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-colors">
                    Save Settings
                </button>
            </div>
        </form>
    </div>

    <!-- Quick Stats -->
    <div class="grid md:grid-cols-4 gap-4 mt-6">
        <a href="{{ route('admin.referrals.statistics') }}" class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5 hover:shadow-md transition-shadow">
            <p class="text-sm text-slate-500">Statistics</p>
            <p class="text-lg font-semibold text-emerald-600">View Stats →</p>
        </a>
        <a href="{{ route('admin.referrals.withdrawals') }}" class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5 hover:shadow-md transition-shadow">
            <p class="text-sm text-slate-500">Withdrawals</p>
            <p class="text-lg font-semibold text-amber-600">Manage →</p>
        </a>
        <a href="{{ route('admin.referrals.transactions') }}" class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5 hover:shadow-md transition-shadow">
            <p class="text-sm text-slate-500">Transactions</p>
            <p class="text-lg font-semibold text-blue-600">View All →</p>
        </a>
    </div>
</div>
@endsection
