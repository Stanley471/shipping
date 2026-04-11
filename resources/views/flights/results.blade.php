@extends('layouts.app')

@section('title', 'Flight Results')
@section('page-title', 'Select Flight')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('flights.search') }}" class="text-emerald-600 dark:text-emerald-400 text-sm hover:underline mb-2 inline-block">← Back to Search</a>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
            {{ $origin['iata_code'] ?? $search['origin'] }} → {{ $destination['iata_code'] ?? $search['destination'] }}
        </h1>
        <p class="text-slate-500 dark:text-slate-400">
            {{ $origin['city'] ?? $search['origin'] }} to {{ $destination['city'] ?? $search['destination'] }} • 
            {{ date('D, M d', strtotime($search['date'])) }} • 
            {{ $search['passengers'] }} {{ $search['passengers'] == 1 ? 'Passenger' : 'Passengers' }}
        </p>
    </div>

    @if(count($flights) > 0)
        <div class="space-y-4">
            @foreach($flights as $index => $flight)
                @php
                    $depTime = date('H:i', strtotime($flight['departure']['scheduled']));
                    $arrTime = date('H:i', strtotime($flight['arrival']['scheduled']));
                    $duration = (strtotime($flight['arrival']['scheduled']) - strtotime($flight['departure']['scheduled'])) / 60;
                    $durationHours = floor($duration / 60);
                    $durationMins = $duration % 60;
                    $defaultPrice = rand(150, 2500);
                @endphp
                
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition-shadow" id="flight-card-{{ $index }}">
                    <form action="{{ route('flights.book') }}" method="GET" class="space-y-4">
                        <!-- Hidden flight data - Always United Airlines -->
                        <input type="hidden" name="flight[flight_number]" value="UA{{ preg_replace('/[^0-9]/', '', $flight['flight']['iata_number']) }}">
                        <input type="hidden" name="flight[airline]" value="United Airlines">
                        <input type="hidden" name="flight[origin]" value="{{ $flight['departure']['iata_code'] }}">
                        <input type="hidden" name="flight[destination]" value="{{ $flight['arrival']['iata_code'] }}">
                        <input type="hidden" name="flight[departure_time]" value="{{ $depTime }}">
                        <input type="hidden" name="flight[arrival_time]" value="{{ $arrTime }}">
                        <input type="hidden" name="flight[date]" value="{{ $search['date'] }}">
                        <input type="hidden" name="flight[terminal]" value="{{ $flight['departure']['terminal'] ?? 'TBD' }}">
                        <input type="hidden" name="flight[gate]" value="{{ $flight['departure']['gate'] ?? 'TBD' }}">

                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            
                            <!-- Airline Info - United Airlines -->
                            <div class="flex items-center gap-3 min-w-[180px]">
                                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900 dark:text-white">United Airlines</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">UA{{ preg_replace('/[^0-9]/', '', $flight['flight']['iata_number']) }}</p>
                                </div>
                            </div>

                            <!-- Flight Times -->
                            <div class="flex items-center gap-6 flex-1 justify-center">
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $depTime }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $flight['departure']['iata_code'] }}</p>
                                </div>
                                
                                <div class="flex flex-col items-center">
                                    <p class="text-xs text-slate-400">{{ $durationHours }}h {{ $durationMins }}m</p>
                                    <div class="w-24 h-px bg-slate-300 dark:bg-slate-600 my-1 relative">
                                        <div class="absolute -top-1.5 left-1/2 -translate-x-1/2 text-slate-400">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-xs text-slate-400">{{ $flight['aircraft']['iata_code'] ?? 'Boeing/Airbus' }}</p>
                                </div>
                                
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $arrTime }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $flight['arrival']['iata_code'] }}</p>
                                </div>
                            </div>

                            <!-- Editable Price -->
                            <div class="min-w-[160px]">
                                <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Price (USD)</label>
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-500 dark:text-slate-400 font-semibold">$</span>
                                    <input type="number" name="flight[price]" value="{{ $defaultPrice }}" min="1" max="99999"
                                        class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                        onchange="updatePriceDisplay({{ $index }}, this.value)">
                                </div>
                                <p class="text-xs text-slate-400 mt-1">per person</p>
                            </div>
                        </div>

                        <!-- Additional Info -->
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex flex-wrap items-center justify-between gap-4">
                            <div class="flex flex-wrap gap-4 text-sm text-slate-500 dark:text-slate-400">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    Terminal {{ $flight['departure']['terminal'] ?? 'TBD' }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Gate {{ $flight['departure']['gate'] ?? 'TBD' }}
                                </span>
                            </div>
                            
                            <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-colors">
                                Select Flight
                            </button>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-12 text-center">
            <div class="w-20 h-20 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No flights found</h3>
            <p class="text-slate-500 dark:text-slate-400 mb-6">Try different dates or airports</p>
            <a href="{{ route('flights.search') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-colors">
                Modify Search
            </a>
        </div>
    @endif
</div>

<script>
function updatePriceDisplay(index, value) {
    // Optional: Add visual feedback when price is edited
    const card = document.getElementById('flight-card-' + index);
    card.classList.add('ring-2', 'ring-emerald-500', 'ring-opacity-50');
    setTimeout(() => {
        card.classList.remove('ring-2', 'ring-emerald-500', 'ring-opacity-50');
    }, 300);
}
</script>
@endsection