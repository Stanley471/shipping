@extends('layouts.app')

@section('title', 'Vendor Orders')
@section('page-title', 'Vendor Orders')

@section('content')
<div class="max-w-6xl mx-auto">
    
    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">My Orders</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Manage your P2P sales</p>
        </div>
        <a href="{{ route('vendor.dashboard') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 rounded-xl font-medium text-sm">
            Back to Dashboard
        </a>
    </div>

    {{-- Orders List --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
        @forelse($orders as $order)
        <div class="p-6 border-b border-slate-200 dark:border-slate-700 last:border-0">
            <div class="flex items-start justify-between">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                        <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ substr($order->user->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900 dark:text-white">{{ number_format($order->amount_coins) }} Coins</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Buyer: {{ $order->user->name }}</p>
                        <p class="text-xs text-slate-400 mt-1">{{ $order->created_at->format('M d, Y H:i') }}</p>
                        @if($order->proof_image)
                        <a href="{{ Storage::url($order->proof_image) }}" target="_blank" class="text-sm text-emerald-600 hover:text-emerald-700 mt-2 inline-block">
                            View Payment Proof →
                        </a>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-bold text-slate-900 dark:text-white">₦{{ number_format($order->amount_naira) }}</p>
                    @switch($order->status)
                        @case('pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">
                                Pending
                            </span>
                            <form method="POST" action="{{ route('vendor.confirm', $order) }}" class="mt-3">
                                @csrf
                                <button type="submit" onclick="return confirm('Confirm you received ₦{{ number_format($order->amount_naira) }} from {{ $order->user->name }}?')" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg">
                                    Confirm Payment
                                </button>
                            </form>
                            @break
                        @case('approved')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                Completed
                            </span>
                            @break
                        @case('rejected')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                Rejected
                            </span>
                            @break
                    @endswitch
                </div>
            </div>
            
            @if($order->admin_note)
            <div class="mt-4 p-3 bg-slate-50 dark:bg-slate-700/50 rounded-lg text-sm text-slate-600 dark:text-slate-300">
                <span class="font-medium">Note:</span> {{ $order->admin_note }}
            </div>
            @endif
        </div>
        @empty
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">No orders yet</h3>
            <p class="text-slate-500 dark:text-slate-400">Orders will appear here when buyers purchase from you</p>
        </div>
        @endforelse
    </div>

    @if($orders->hasPages())
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
