@extends('layouts.public')

@section('title', 'Book Flight | ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-950 pt-20 pb-12">
    
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <a href="{{ route('flights.search') }}" class="text-blue-600 dark:text-blue-400 text-sm hover:underline">← Back to Search</a>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
            
            <!-- Flight Summary Header -->
            <div class="bg-blue-600 px-6 py-4">
                <h1 class="text-xl font-bold text-white">Generate Flight Ticket</h1>
                <p class="text-blue-100 text-sm">Enter passenger details to create your boarding pass</p>
            </div>

            <div class="p-6 md:p-8">
                <!-- Flight Info Card -->
                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-4 mb-6 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">✈️</div>
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $flight['airline'] ?? 'Unknown Airline' }}</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $flight['flight_number'] ?? 'Unknown' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">${{ number_format($flight['price'] ?? 0) }}</p>
                            <p class="text-xs text-slate-500">Total price</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-center gap-6 mt-4 pt-4 border-t border-slate-200 dark:border-slate-600">
                        <div class="text-center">
                            <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $flight['departure_time'] }}</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $flight['origin'] }}</p>
                        </div>
                        <div class="flex flex-col items-center">
                            <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $flight['arrival_time'] }}</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $flight['destination'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Passenger Form -->
                <form action="{{ route('flights.ticket') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Hidden Flight Data -->
                    <input type="hidden" name="flight_number" value="{{ $flight['flight_number'] }}">
                    <input type="hidden" name="airline" value="{{ $flight['airline'] }}">
                    <input type="hidden" name="origin" value="{{ $flight['origin'] }}">
                    <input type="hidden" name="destination" value="{{ $flight['destination'] }}">
                    <input type="hidden" name="departure_time" value="{{ $flight['departure_time'] }}">
                    <input type="hidden" name="arrival_time" value="{{ $flight['arrival_time'] }}">
                    <input type="hidden" name="date" value="{{ $flight['date'] }}">

                    <!-- Passenger Name -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Passenger Full Name *</label>
                        <input type="text" name="passenger_name" required 
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent uppercase"
                            placeholder="JOHN DOE">
                    </div>

                    <!-- Class Selection -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">Seat Class</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="seat_class" value="economy" checked class="sr-only peer">
                                <div class="p-4 border-2 border-slate-200 dark:border-slate-600 rounded-xl text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 transition-all hover:border-blue-300">
                                    <p class="font-semibold text-slate-900 dark:text-white">Economy</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Standard</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="seat_class" value="business" class="sr-only peer">
                                <div class="p-4 border-2 border-slate-200 dark:border-slate-600 rounded-xl text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 transition-all hover:border-blue-300">
                                    <p class="font-semibold text-slate-900 dark:text-white">Business</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Premium</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="seat_class" value="first" class="sr-only peer">
                                <div class="p-4 border-2 border-slate-200 dark:border-slate-600 rounded-xl text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 transition-all hover:border-blue-300">
                                    <p class="font-semibold text-slate-900 dark:text-white">First</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Luxury</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Disclaimer -->
                    <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-amber-800 dark:text-amber-300">For Entertainment Only</p>
                                <p class="text-xs text-amber-700 dark:text-amber-400 mt-1">This is a novelty/prank ticket generator. It creates realistic-looking boarding passes that are NOT valid for actual travel.</p>
                            </div>
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400 text-sm">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl transition-all hover:scale-[1.02] flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Generate & Download Ticket
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection