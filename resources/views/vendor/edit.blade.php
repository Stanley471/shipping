@extends('layouts.app')

@section('title', 'Edit Vendor Profile')
@section('page-title', 'Edit Profile')

@section('content')
<div class="max-w-3xl mx-auto">
    
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Edit Vendor Profile</h1>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Update your P2P vendor information</p>
    </div>

    {{-- Form --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
        <form method="POST" action="{{ route('vendor.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Display Name --}}
            <div>
                <label for="display_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Display Name <span class="text-red-500">*</span>
                </label>
                <input type="text" id="display_name" name="display_name" 
                    value="{{ old('display_name', $vendor->display_name) }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500"
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
                        value="{{ old('bank_name', $vendor->bank_name) }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500"
                        required>
                </div>
                <div>
                    <label for="account_number" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Account Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="account_number" name="account_number" 
                        value="{{ old('account_number', $vendor->account_number) }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500"
                        required>
                </div>
            </div>

            <div>
                <label for="account_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Account Name <span class="text-red-500">*</span>
                </label>
                <input type="text" id="account_name" name="account_name" 
                    value="{{ old('account_name', $vendor->account_name) }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500"
                    required>
            </div>

            {{-- Rate & Limits --}}
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label for="rate" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Rate (₦ per coin) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="rate" name="rate" step="0.01" min="0.5" max="10"
                        value="{{ old('rate', $vendor->rate) }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500"
                        required>
                </div>
                <div>
                    <label for="min_limit" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Min Order (coins) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="min_limit" name="min_limit" min="100" max="10000"
                        value="{{ old('min_limit', $vendor->min_limit) }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500"
                        required>
                </div>
                <div>
                    <label for="max_limit" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Max Order (coins) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="max_limit" name="max_limit" min="1000" max="500000"
                        value="{{ old('max_limit', $vendor->max_limit) }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500"
                        required>
                </div>
            </div>

            <div>
                <label for="avg_response_time" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Avg Response Time (minutes)
                </label>
                <input type="number" id="avg_response_time" name="avg_response_time" min="1" max="120"
                    value="{{ old('avg_response_time', $vendor->avg_response_time) }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                <p class="text-xs text-slate-500 mt-1">Estimated time to process orders</p>
            </div>

            {{-- Vendor Info --}}
            <div>
                <label for="vendor_info" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Vendor Info / Bio
                </label>
                <textarea id="vendor_info" name="vendor_info" rows="3"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">{{ old('vendor_info', $vendor->vendor_info) }}</textarea>
                <p class="text-xs text-slate-500 mt-1">Brief description shown on your card (max 500 chars)</p>
            </div>

            {{-- Vendor Notes --}}
            <div>
                <label for="vendor_notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Payment Instructions / Notes
                </label>
                <textarea id="vendor_notes" name="vendor_notes" rows="4"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">{{ old('vendor_notes', $vendor->vendor_notes) }}</textarea>
                <p class="text-xs text-slate-500 mt-1">Instructions shown to buyers (e.g., "Send screenshot via WhatsApp", max 1000 chars)</p>
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-colors">
                    Save Changes
                </button>
                <a href="{{ route('vendor.dashboard') }}" class="px-6 py-3 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-xl">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
