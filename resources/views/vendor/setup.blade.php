@extends('layouts.app')

@section('title', 'Become a Vendor')
@section('page-title', 'Become a Vendor')

@section('content')
<div class="max-w-3xl mx-auto">
    
    {{-- Header --}}
    <div class="mb-8 text-center">
        <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Become a P2P Vendor</h1>
        <p class="text-slate-500 dark:text-slate-400 mt-2">Start selling coins and earn money on our platform</p>
    </div>

    {{-- Benefits --}}
    <div class="grid md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white dark:bg-slate-800 rounded-xl p-5 border border-slate-200 dark:border-slate-700 text-center">
            <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="font-semibold text-slate-900 dark:text-white">Set Your Rate</h3>
            <p class="text-sm text-slate-500 mt-1">Choose your own price per coin</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl p-5 border border-slate-200 dark:border-slate-700 text-center">
            <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="font-semibold text-slate-900 dark:text-white">Verified Buyers</h3>
            <p class="text-sm text-slate-500 mt-1">Trade with verified platform users</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl p-5 border border-slate-200 dark:border-slate-700 text-center">
            <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h3 class="font-semibold text-slate-900 dark:text-white">Instant Payments</h3>
            <p class="text-sm text-slate-500 mt-1">Receive payments directly to your bank</p>
        </div>
    </div>

    {{-- Setup Form --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-6">Set Up Your Profile</h2>
        
        <form method="POST" action="{{ route('vendor.setup') }}" class="space-y-6">
            @csrf

            {{-- Display Name --}}
            <div>
                <label for="display_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Display Name <span class="text-red-500">*</span>
                </label>
                <input type="text" id="display_name" name="display_name" 
                    value="{{ old('display_name') }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500"
                    placeholder="Your business or personal name"
                    required>
                <p class="text-xs text-slate-500 mt-1">This is what buyers will see</p>
            </div>

            {{-- Bank Details --}}
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label for="bank_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Bank Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="bank_name" name="bank_name" 
                        value="{{ old('bank_name') }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500"
                        placeholder="e.g., First Bank of Nigeria"
                        required>
                </div>
                <div>
                    <label for="account_number" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Account Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="account_number" name="account_number" 
                        value="{{ old('account_number') }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500"
                        placeholder="10 digit account number"
                        required>
                </div>
            </div>

            <div>
                <label for="account_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Account Name <span class="text-red-500">*</span>
                </label>
                <input type="text" id="account_name" name="account_name" 
                    value="{{ old('account_name') }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500"
                    placeholder="Name on the bank account"
                    required>
            </div>

            {{-- Rate & Limits --}}
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label for="rate" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Rate (₦ per coin) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="rate" name="rate" step="0.01" min="0.5" max="10"
                        value="{{ old('rate', 1.00) }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500"
                        required>
                    <p class="text-xs text-slate-500 mt-1">Default: ₦1.00</p>
                </div>
                <div>
                    <label for="min_limit" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Min Order <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="min_limit" name="min_limit" min="100" max="10000"
                        value="{{ old('min_limit', 100) }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500"
                        required>
                </div>
                <div>
                    <label for="max_limit" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Max Order <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="max_limit" name="max_limit" min="1000" max="500000"
                        value="{{ old('max_limit', 100000) }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500"
                        required>
                </div>
            </div>

            {{-- Vendor Info --}}
            <div>
                <label for="vendor_info" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Vendor Info / Bio
                </label>
                <textarea id="vendor_info" name="vendor_info" rows="3"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500"
                    placeholder="Tell buyers about yourself...">{{ old('vendor_info') }}</textarea>
            </div>

            {{-- Vendor Notes --}}
            <div>
                <label for="vendor_notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Payment Instructions
                </label>
                <textarea id="vendor_notes" name="vendor_notes" rows="4"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500"
                    placeholder="e.g., 'Send screenshot via WhatsApp after payment'">{{ old('vendor_notes') }}</textarea>
            </div>

            {{-- Terms Checkbox --}}
            <div class="flex items-start gap-3">
                <input type="checkbox" id="terms" name="terms" required
                    class="mt-1 w-4 h-4 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500">
                <label for="terms" class="text-sm text-slate-600 dark:text-slate-400">
                    I agree to the vendor terms and conditions. I understand that I am responsible for completing transactions and the platform takes no responsibility for disputes.
                </label>
            </div>

            {{-- Submit --}}
            <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-colors">
                Create Vendor Account
            </button>
        </form>
    </div>
</div>
@endsection
