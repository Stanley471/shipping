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

                <form method="POST" action="{{ route('coins.buy') }}" enctype="multipart/form-data" class="p-6 space-y-6">
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
                                            {{ $account->account_number }}
                                            <button type="button" onclick="copyToClipboard('{{ $account->account_number }}')" 
                                                class="ml-2 text-indigo-600 hover:text-indigo-500 text-xs">Copy</button>
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

                    {{-- Proof Upload --}}
                    <div>
                        <label for="proof_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Proof (Screenshot)</label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Upload screenshot of successful transfer. Max 5MB.</p>
                        <input type="file" id="proof_image" name="proof_image" accept="image/*" 
                            class="block w-full text-sm text-gray-900 dark:text-gray-300
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100
                                dark:file:bg-indigo-900 dark:file:text-indigo-300" 
                            required />
                        @error('proof_image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Instructions --}}
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2">How it works:</h4>
                        <ol class="text-sm text-blue-800 dark:text-blue-200 space-y-1 list-decimal list-inside">
                            <li>Enter the amount of coins you want to buy</li>
                            <li>Select a bank account and send the exact amount</li>
                            <li>Take a screenshot of the successful transfer</li>
                            <li>Upload the screenshot and submit</li>
                            <li>Admin will verify and credit your account</li>
                        </ol>
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
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Account number copied: ' + text);
            });
        }

        // Update Naira display when amount changes
        document.getElementById('amount').addEventListener('input', function(e) {
            const amount = parseInt(e.target.value) || 0;
            document.getElementById('naira-display').textContent = '₦' + amount.toLocaleString();
        });
    </script>
@endsection
