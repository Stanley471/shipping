@extends('layouts.app')

@section('title', 'Buy Coins')
@section('page-title', 'Buy Coins')

@section('content')
<div class="max-w-3xl mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Purchase Coins (P2P)</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Send payment to one of our admin accounts and upload proof. Coins will be credited after verification.
                    </p>
                </div>

                <form method="POST" action="{{ route('coins.buy') }}" class="p-6 space-y-6">
                    @csrf

                    {{-- Amount --}}
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount (Coins)</label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">1 coin = ₦1 Naira • Min: 100 • Max: 100,000</p>
                        <input id="amount" name="amount" type="number" min="100" max="100000" step="1" 
                            class="block w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300" 
                            value="{{ old('amount', 100) }}" 
                            required />
                        <p class="mt-1 text-sm text-indigo-600 dark:text-indigo-400 font-medium" id="naira-display">₦100</p>
                        @error('amount')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Bank Account Selection --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Bank Account to Pay</label>
                        <div class="mt-2 space-y-3">
                            @foreach($bankAccounts as $account)
                            <label class="relative flex cursor-pointer rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none hover:border-indigo-500">
                                <input type="radio" name="bank_account_id" value="{{ $account->id }}" 
                                    class="mt-1 mr-3" {{ $loop->first ? 'checked' : '' }} required>
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-medium text-gray-900 dark:text-gray-100">{{ $account->bank_name }}</span>
                                        <span class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                            <span class="font-bold text-gray-900 dark:text-gray-200 tracking-wide">{{ $account->account_number }}</span>
                                            <button type="button" onclick="copyToClipboard(this, '{{ $account->account_number }}')" 
                                                class="ml-2 inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900 hover:bg-indigo-200 dark:hover:bg-indigo-800 text-indigo-700 dark:text-indigo-300 rounded text-xs font-medium transition-colors">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                </svg>
                                                Copy
                                            </button>
                                        </span>
                                        <span class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $account->account_name }}</span>
                                    </span>
                                </span>
                            </label>
                            @endforeach
                        </div>
                        @error('bank_account_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Instructions --}}
                    <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-emerald-900 dark:text-emerald-100 mb-2">Payment Instructions</h4>
                        <ol class="text-sm text-emerald-800 dark:text-emerald-200 space-y-1 list-decimal list-inside">
                            <li>Enter the amount of coins you want to buy</li>
                            <li>Select a bank account and send the exact amount</li>
                            <li><strong>Important:</strong> Include your account email <code class="bg-white dark:bg-slate-700 px-1 py-0.5 rounded">{{ auth()->user()->email }}</code> in the transaction remark/reference</li>
                            <li>Click "I Have Made Payment"</li>
                        </ol>
                        <p class="text-xs text-emerald-700 dark:text-emerald-300 mt-2">
                            Adding your email in the remark helps us verify your payment quickly.
                        </p>
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            I Have Made Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(btn, text) {
            // Fallback copy method for broader compatibility
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-9999px';
            document.body.appendChild(textArea);
            textArea.select();
            let success = false;
            try {
                success = document.execCommand('copy');
            } catch (err) {}
            document.body.removeChild(textArea);

            // Also try modern API
            if (!success && navigator.clipboard) {
                navigator.clipboard.writeText(text);
            }

            // Visual feedback
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>Copied</span>';
            btn.className = 'ml-2 inline-flex items-center gap-1 px-2 py-0.5 bg-green-600 text-white rounded text-xs font-medium transition-colors';
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.className = 'ml-2 inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900 hover:bg-indigo-200 dark:hover:bg-indigo-800 text-indigo-700 dark:text-indigo-300 rounded text-xs font-medium transition-colors';
            }, 2000);
        }

        // Update Naira display when amount changes
        document.getElementById('amount').addEventListener('input', function(e) {
            const amount = parseInt(e.target.value) || 0;
            document.getElementById('naira-display').textContent = '₦' + amount.toLocaleString();
        });
    </script>
@endsection
