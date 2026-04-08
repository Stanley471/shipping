@extends('layouts.app')

@section('title', 'Manual Coin Adjustment')
@section('page-title', 'Manual Coin Adjustment')

@section('content')
<div class="max-w-2xl mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Adjust User Balance</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Manually add or deduct coins from a user's account. This action will be logged.
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.coins.adjustment') }}" class="p-6 space-y-6">
                    @csrf

                    {{-- User Email --}}
                    <div>
                        <label for="user_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">User Email</label>
                        <input id="user_email" name="user_email" type="email" 
                            class="block w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300" 
                            value="{{ old('user_email') }}" 
                            placeholder="user@example.com"
                            required />
                        <p class="mt-1 text-xs text-gray-500">Enter the exact email of the user</p>
                        @error('user_email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Type --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Adjustment Type</label>
                        <div class="mt-2 space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="type" value="add" checked 
                                    class="text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">Add Coins (Credit)</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="type" value="deduct" 
                                    class="text-red-600 border-gray-300 focus:ring-red-500">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">Deduct Coins (Debit)</span>
                            </label>
                        </div>
                        @error('type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Amount --}}
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
                        <input id="amount" name="amount" type="number" min="1" max="100000" 
                            class="block w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300" 
                            value="{{ old('amount') }}" 
                            required />
                        @error('amount')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Reason --}}
                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reason</label>
                        <textarea id="reason" name="reason" rows="3" 
                            class="block w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                            required>{{ old('reason') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">This will be visible to the user in their transaction history</p>
                        @error('reason')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Warning --}}
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Warning</h3>
                                <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                                    This action cannot be undone. Please verify the user email and amount before submitting.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Submit Adjustment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
