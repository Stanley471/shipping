@extends('layouts.app')

@section('title', 'P2P Marketplace - Buy Coins')
@section('page-title', 'P2P Marketplace')

@section('content')
<div class="max-w-6xl mx-auto">
    
    {{-- Header Section --}}
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">
            P2P Marketplace
        </h1>
        <p class="text-slate-500 dark:text-slate-400 mt-2">
            Buy coins directly from verified vendors with secure escrow protection.
        </p>
    </div>

    {{-- Active Order Alert --}}
    @if($hasActiveOrder)
    <div class="mb-6 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <div>
                <h3 class="font-semibold text-amber-800 dark:text-amber-200">Active Order In Progress</h3>
                <p class="text-amber-700 dark:text-amber-300 text-sm mt-1">
                    You already have an active order. Please complete or cancel it before placing a new one. 
                    <a href="{{ route('coins.orders') }}" class="underline font-medium">View My Orders</a>
                </p>
            </div>
        </div>
    </div>
    @endif

    {{-- Search & Filter Bar --}}
    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        <div class="flex-1 relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" 
                placeholder="Search vendors by name or username..." 
                class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                id="vendor-search">
        </div>
        <select class="px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
            <option>Price: Low to High</option>
            <option>Price: High to Low</option>
            <option>Rating: High to Low</option>
        </select>
    </div>

    {{-- Vendors Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($vendors as $vendor)
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm hover:shadow-md transition-shadow">
            {{-- Vendor Header --}}
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                        <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ substr($vendor->account_name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900 dark:text-white">{{ $vendor->account_name }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $vendor->bank_name }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xl font-bold text-slate-900 dark:text-white">₦1.00</p>
                    <p class="text-xs text-slate-500">per coin</p>
                </div>
            </div>

            {{-- Vendor Stats --}}
            <div class="flex items-center gap-4 mb-4 text-sm">
                <div class="flex items-center gap-1">
                    <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span class="text-slate-600 dark:text-slate-300">N/A</span>
                </div>
                <div class="flex items-center gap-1">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span class="text-slate-600 dark:text-slate-300">{{ rand(50, 500) }} sales</span>
                </div>
                <div class="flex items-center gap-1">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-slate-600 dark:text-slate-300">~20 mins</span>
                </div>
            </div>

            {{-- Available Balance --}}
            <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-4 mb-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-slate-600 dark:text-slate-400">Available</span>
                    <span class="font-semibold text-slate-900 dark:text-white">{{ number_format($vendor->max_limit ?? 100000) }} coins</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-slate-600 dark:text-slate-400">Minimum</span>
                    <span class="font-semibold text-slate-900 dark:text-white">{{ number_format($vendor->min_limit ?? 100) }} coins</span>
                </div>
            </div>

            {{-- Buy Button --}}
            <button onclick="openBuyModal({{ $vendor->id }}, '{{ $vendor->account_name }}', '{{ $vendor->bank_name }}', '{{ $vendor->account_number }}', {{ $vendor->min_limit ?? 100 }}, {{ $vendor->max_limit ?? 100000 }})" 
                class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-colors"
                {{ $hasActiveOrder ? 'disabled' : '' }}>
                Buy
            </button>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">No vendors available</h3>
            <p class="text-slate-500 dark:text-slate-400">Please check back later.</p>
        </div>
        @endforelse
    </div>
</div>

{{-- Buy Modal --}}
<div id="buy-modal" class="hidden fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeBuyModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
            
            {{-- Modal Header --}}
            <div class="p-6 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">Buy Coins</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Purchase from <span id="modal-vendor-name" class="font-medium"></span></p>
                </div>
                <button onclick="closeBuyModal()" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <form id="buy-form" method="POST" action="{{ route('coins.buy') }}" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                <input type="hidden" name="bank_account_id" id="modal-vendor-id">

                {{-- Vendor Info Card --}}
                <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                        <span class="font-bold text-emerald-600 dark:text-emerald-400" id="modal-vendor-initial"></span>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-slate-900 dark:text-white" id="modal-vendor-fullname"></p>
                        <div class="flex items-center gap-2 text-sm text-slate-500">
                            <span>₦1.00 per coin</span>
                            <span>•</span>
                            <span class="text-amber-500">★ N/A</span>
                        </div>
                    </div>
                </div>

                {{-- Quick Amount Buttons --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">Quick Select</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" onclick="setAmount(2000)" class="py-2 px-3 text-sm font-medium rounded-lg border border-slate-200 dark:border-slate-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:border-emerald-500 dark:text-white transition-colors">2,000</button>
                        <button type="button" onclick="setAmount(5000)" class="py-2 px-3 text-sm font-medium rounded-lg border border-slate-200 dark:border-slate-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:border-emerald-500 dark:text-white transition-colors">5,000</button>
                        <button type="button" onclick="setAmount(10000)" class="py-2 px-3 text-sm font-medium rounded-lg border border-slate-200 dark:border-slate-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:border-emerald-500 dark:text-white transition-colors">10,000</button>
                        <button type="button" onclick="setAmount(20000)" class="py-2 px-3 text-sm font-medium rounded-lg border border-slate-200 dark:border-slate-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:border-emerald-500 dark:text-white transition-colors">20,000</button>
                        <button type="button" onclick="setAmount(50000)" class="py-2 px-3 text-sm font-medium rounded-lg border border-slate-200 dark:border-slate-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:border-emerald-500 dark:text-white transition-colors">50,000</button>
                        <button type="button" onclick="setAmount(100000)" class="py-2 px-3 text-sm font-medium rounded-lg border border-slate-200 dark:border-slate-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:border-emerald-500 dark:text-white transition-colors">100,000</button>
                    </div>
                </div>

                {{-- Custom Amount Input --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Or enter amount</label>
                    <input type="number" name="amount" id="amount-input" min="100" max="100000" step="1"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="Minimum: 100 coins">
                    <p class="text-xs text-slate-500 mt-1">Min: <span id="modal-min">100</span> | Max: <span id="modal-max">100,000</span> coins</p>
                </div>

                {{-- Calculation Summary --}}
                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-600 dark:text-slate-400">Coins</span>
                        <span class="font-medium text-slate-900 dark:text-white" id="summary-coins">0</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-600 dark:text-slate-400">Rate</span>
                        <span class="font-medium text-slate-900 dark:text-white">₦1.00/coin</span>
                    </div>
                    <div class="border-t border-slate-200 dark:border-slate-600 pt-2 mt-2">
                        <div class="flex justify-between">
                            <span class="font-medium text-slate-900 dark:text-white">Total Amount</span>
                            <span class="text-xl font-bold text-emerald-600" id="summary-total">₦0</span>
                        </div>
                    </div>
                </div>

                {{-- Bank Details --}}
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4">
                    <h4 class="font-medium text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                        </svg>
                        Payment Details
                    </h4>
                    <div class="space-y-1 text-sm">
                        <p class="text-blue-800 dark:text-blue-200"><span class="font-medium">Bank:</span> <span id="modal-bank"></span></p>
                        <p class="text-blue-800 dark:text-blue-200"><span class="font-medium">Account Number:</span> <span id="modal-account"></span></p>
                        <p class="text-blue-800 dark:text-blue-200"><span class="font-medium">Account Name:</span> <span id="modal-account-name"></span></p>
                    </div>
                </div>

                {{-- Instructions --}}
                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4">
                    <h4 class="font-medium text-amber-900 dark:text-amber-100 mb-2">Payment Instructions</h4>
                    <ol class="text-sm text-amber-800 dark:text-amber-200 space-y-1 list-decimal list-inside">
                        <li>Transfer the exact amount to the vendor's account</li>
                        <li>Take a screenshot of the payment receipt</li>
                        <li>Upload the screenshot below</li>
                        <li>Click "I Have Made Payment"</li>
                    </ol>
                </div>

                {{-- Proof Upload --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Payment Proof (Screenshot)</label>
                    <input type="file" name="proof_image" accept="image/*" required
                        class="block w-full text-sm text-slate-900 dark:text-white
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-lg file:border-0
                            file:text-sm file:font-semibold
                            file:bg-emerald-50 file:text-emerald-700
                            hover:file:bg-emerald-100
                            dark:file:bg-emerald-900/30 dark:file:text-emerald-300">
                </div>

                {{-- Disclaimer --}}
                <div class="text-xs text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-700/50 p-3 rounded-lg">
                    <strong>Disclaimer:</strong> This is a peer-to-peer transaction. Ensure you are sending money to the correct vendor. The platform is not responsible for disputes between users and vendors.
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
                    <span>I Have Made Payment</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    let currentVendor = null;

    function openBuyModal(id, name, bank, account, min, max) {
        currentVendor = { id, name, bank, account, min, max };
        
        document.getElementById('modal-vendor-id').value = id;
        document.getElementById('modal-vendor-name').textContent = name;
        document.getElementById('modal-vendor-fullname').textContent = name;
        document.getElementById('modal-vendor-initial').textContent = name.charAt(0);
        document.getElementById('modal-bank').textContent = bank;
        document.getElementById('modal-account').textContent = account;
        document.getElementById('modal-account-name').textContent = name;
        document.getElementById('modal-min').textContent = min.toLocaleString();
        document.getElementById('modal-max').textContent = max.toLocaleString();
        
        document.getElementById('buy-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeBuyModal() {
        document.getElementById('buy-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function setAmount(amount) {
        document.getElementById('amount-input').value = amount;
        updateSummary();
    }

    function updateSummary() {
        const amount = parseInt(document.getElementById('amount-input').value) || 0;
        document.getElementById('summary-coins').textContent = amount.toLocaleString() + ' coins';
        document.getElementById('summary-total').textContent = '₦' + amount.toLocaleString();
    }

    document.getElementById('amount-input').addEventListener('input', updateSummary);

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeBuyModal();
    });
</script>
@endsection
