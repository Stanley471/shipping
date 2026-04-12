@extends('layouts.app')

@section('title', 'Create Shipment')
@section('page-title', 'Create New Shipment')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <form method="POST" action="{{ route('shipments.store') }}" class="space-y-6">
        @csrf

        <!-- Coin Cost Notice -->
        @php
            $createCost = app(\App\Services\CoinService::class)->getServiceCost('create_shipment');
            $userBalance = auth()->user()->getCoinBalance();
        @endphp
        
        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-2xl p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-amber-900 dark:text-amber-100">
                        Cost: {{ $createCost }} coins
                    </p>
                    <p class="text-xs text-amber-700 dark:text-amber-300">
                        Your balance: {{ $userBalance }} coins
                    </p>
                </div>
            </div>
            @if($userBalance < $createCost)
                <a href="{{ route('coins.buy') }}" class="text-sm font-medium text-amber-700 dark:text-amber-300 hover:text-amber-800 dark:hover:text-amber-200 underline">
                    Buy Coins →
                </a>
            @endif
        </div>

        <!-- Shipment Details Section -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 md:p-8 shadow-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Shipment Details</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Shipment Type -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Shipment Type</label>
                    <select name="shipment_type" class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-emerald-500 focus:ring-emerald-500" required>
                        <option value="" disabled {{ old('shipment_type') ? '' : 'selected' }}>Select type</option>
                        <option value="air_freight" {{ old('shipment_type') == 'air_freight' ? 'selected' : '' }}>Air Freight</option>
                        <option value="sea_freight" {{ old('shipment_type') == 'sea_freight' ? 'selected' : '' }}>Sea Freight</option>
                        <option value="road_freight" {{ old('shipment_type') == 'road_freight' ? 'selected' : '' }}>Road Freight</option>
                        <option value="express_delivery" {{ old('shipment_type') == 'express_delivery' ? 'selected' : '' }}>Express Delivery</option>
                    </select>
                    @error('shipment_type')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Shipping Started At -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Shipping Started At</label>
                    <input type="datetime-local" name="shipped_at" value="{{ old('shipped_at') }}" 
                        class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-emerald-500 focus:ring-emerald-500" required>
                    @error('shipped_at')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- ETA -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Estimated Delivery (ETA)</label>
                    <input type="datetime-local" name="eta" value="{{ old('eta') }}" 
                        class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-emerald-500 focus:ring-emerald-500">
                    @error('eta')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Shipment Details Section -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 md:p-8 shadow-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Shipment Details</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Courier -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Courier</label>
                    <input type="text" name="courier" value="{{ old('courier') }}" placeholder="e.g., DHL, FedEx"
                        class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-emerald-500 focus:ring-emerald-500">
                    @error('courier')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Quantity</label>
                    <input type="number" name="quantity" value="{{ old('quantity') }}" min="1" placeholder="Number of items"
                        class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-emerald-500 focus:ring-emerald-500">
                    @error('quantity')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Fragile -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fragile?</label>
                    <select name="is_fragile" class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="0" {{ old('is_fragile') == '0' ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('is_fragile') == '1' ? 'selected' : '' }}>Yes</option>
                    </select>
                    @error('is_fragile')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Sender & Receiver Section -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 md:p-8 shadow-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Sender & Receiver</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Sender Name -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Sender Name</label>
                    <input type="text" name="sender_name" value="{{ old('sender_name') }}" 
                        class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-emerald-500 focus:ring-emerald-500" required>
                    @error('sender_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Receiver Name -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Receiver Name</label>
                    <input type="text" name="receiver_name" value="{{ old('receiver_name') }}" 
                        class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-emerald-500 focus:ring-emerald-500" required>
                    @error('receiver_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Receiver Email -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Receiver Email (Optional)</label>
                    <input type="email" name="receiver_email" value="{{ old('receiver_email') }}" 
                        class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-emerald-500 focus:ring-emerald-500">
                    @error('receiver_email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <!-- Shipping From -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Shipping From (Sender's Address)</label>
                    <textarea name="pickup_location" rows="3" 
                        class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-emerald-500 focus:ring-emerald-500" required>{{ old('pickup_location') }}</textarea>
                    @error('pickup_location')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Destination -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Destination (Receiver's Address)</label>
                    <textarea name="delivery_address" rows="3" 
                        class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-emerald-500 focus:ring-emerald-500" required>{{ old('delivery_address') }}</textarea>
                    @error('delivery_address')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Initial Tracking Update Section -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 md:p-8 shadow-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Initial Tracking Update</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Current Status</label>
                    <select name="status" class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-emerald-500 focus:ring-emerald-500" required>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_transit" {{ old('status') == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                        <option value="out_for_delivery" {{ old('status') == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                        <option value="delivered" {{ old('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Progress -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Progress (%)</label>
                    <input type="number" name="progress" value="{{ old('progress', 0) }}" min="0" max="100" 
                        class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-emerald-500 focus:ring-emerald-500" required>
                    @error('progress')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Location -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Current Location</label>
                    <input type="text" name="location" value="{{ old('location') }}" placeholder="e.g., Warehouse A" 
                        class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-emerald-500 focus:ring-emerald-500">
                    @error('location')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Occurred At -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Update Occurred At</label>
                    <input type="datetime-local" name="occurred_at" value="{{ old('occurred_at') }}" 
                        class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-emerald-500 focus:ring-emerald-500" required>
                    @error('occurred_at')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Note -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Note (Optional)</label>
                <textarea name="note" rows="2" placeholder="Additional details about this update..." 
                    class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-emerald-500 focus:ring-emerald-500">{{ old('note') }}</textarea>
                @error('note')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Email Notification Checkbox -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="send_email" value="1" {{ old('send_email') ? 'checked' : '' }} 
                    class="w-5 h-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <div>
                    <span class="text-sm font-medium text-slate-900 dark:text-white">Send email notifications</span>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Notify me and the receiver when this shipment is created</p>
                </div>
            </label>
        </div>

        <!-- Chat Widget Section -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 md:p-8 shadow-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center text-purple-600 dark:text-purple-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Customer Chat Support</h3>
            </div>

            <div class="space-y-4">
                <!-- Chat Provider -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Chat Provider</label>
                    <select name="chat_provider" id="chat_provider" class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-purple-500 focus:ring-purple-500">
                        <option value="">No chat support</option>
                        <option value="whatsapp" {{ old('chat_provider') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                        <option value="smartsupp" {{ old('chat_provider') == 'smartsupp' ? 'selected' : '' }}>SmartSupp</option>
                    </select>
                    @error('chat_provider')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Chat Widget Code -->
                <div id="chat_code_container" class="{{ old('chat_provider') ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Embed Code
                        <span class="text-xs text-slate-500 font-normal ml-1">(Paste your widget code here)</span>
                    </label>
                    <textarea name="chat_widget_code" id="chat_widget_code" rows="4" placeholder="<!-- Paste your WhatsApp or SmartSupp embed code here -->"
                        class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-purple-500 focus:ring-purple-500 font-mono text-xs">{{ old('chat_widget_code') }}</textarea>
                    @error('chat_widget_code')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    
                    <!-- Help Text -->
                    <div class="mt-3 p-3 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg">
                        <p class="text-xs text-purple-800 dark:text-purple-300 font-medium mb-1">How to get your embed code:</p>
                        <ul class="text-xs text-purple-700 dark:text-purple-400 space-y-1 ml-4 list-disc">
                            <li><strong>WhatsApp:</strong> Go to <a href="https://business.whatsapp.com/products/whatsapp-business-api" target="_blank" class="underline">WhatsApp Business API</a> or use a widget generator like <a href="https://elfsight.com/whatsapp-chat-widget/" target="_blank" class="underline">Elfsight</a></li>
                            <li><strong>SmartSupp:</strong> Sign up at <a href="https://www.smartsupp.com/" target="_blank" class="underline">smartsupp.com</a> → Install → Copy the embed code</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('chat_provider').addEventListener('change', function() {
                const container = document.getElementById('chat_code_container');
                if (this.value) {
                    container.classList.remove('hidden');
                } else {
                    container.classList.add('hidden');
                    document.getElementById('chat_widget_code').value = '';
                }
            });
        </script>

        <!-- Submit Buttons -->
        <div class="flex flex-col sm:flex-row gap-4">
            <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Create Shipment
            </button>
            <a href="{{ route('shipments.index') }}" class="flex-1 sm:flex-initial bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-bold py-4 px-8 rounded-xl transition-colors text-center">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection