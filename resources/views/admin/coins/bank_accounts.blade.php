@extends('layouts.app')

@section('title', 'Admin Bank Accounts')
@section('page-title', 'Admin Bank Accounts')

@section('content')
<div class="max-w-4xl mx-auto">
            {{-- Add New Account --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Add New Bank Account</h3>
                </div>
                <form method="POST" action="{{ route('admin.coins.bank_accounts') }}" class="p-6 space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="bank_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bank Name</label>
                            <input id="bank_name" name="bank_name" class="block w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300" required />
                        </div>
                        <div>
                            <label for="account_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account Number</label>
                            <input id="account_number" name="account_number" class="block w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300" required />
                        </div>
                        <div>
                            <label for="account_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account Name</label>
                            <input id="account_name" name="account_name" class="block w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300" required />
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Add Account</button>
                    </div>
                </form>
            </div>

            {{-- Existing Accounts --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Existing Accounts</h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($accounts as $account)
                    <div class="p-6">
                        <form method="POST" action="{{ route('admin.coins.bank_accounts') }}/{{ $account->id }}">
                            @csrf
                            @method('PATCH')
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bank</label>
                                    <input type="text" name="bank_name" value="{{ $account->bank_name }}" 
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account #</label>
                                    <input type="text" name="account_number" value="{{ $account->account_number }}" 
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                    <input type="text" name="account_name" value="{{ $account->account_name }}" 
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                </div>
                                <div class="flex items-center space-x-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="is_active" value="1" {{ $account->is_active ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-indigo-600">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                                    </label>
                                    <button type="submit" class="text-indigo-600 hover:text-indigo-900 text-sm">Save</button>
                                </div>
                            </div>
                        </form>
                        <form method="POST" action="{{ route('admin.coins.bank_accounts') }}/{{ $account->id }}" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this account?')" 
                                class="text-red-600 hover:text-red-900 text-xs">Delete</button>
                        </form>
                    </div>
                    @empty
                    <div class="p-6 text-center text-gray-500">
                        No bank accounts configured.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
