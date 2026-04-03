@extends('layouts.app')

@section('title', 'Search Flights')
@section('page-title', 'Flight Ticket Generator')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">
            Generate Flight Ticket
        </h1>
        <p class="text-slate-500 dark:text-slate-400 mt-1">
            Search real flights and create a realistic boarding pass for entertainment purposes.
        </p>
    </div>

    <!-- Search Form -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 md:p-8">
        <form action="{{ route('flights.results') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid md:grid-cols-2 gap-4">
                <!-- Origin -->
                <div class="relative">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">From (Origin)</label>
                    <div class="relative">
                        <input type="text" id="origin" name="origin" placeholder="JFK - New York" 
                            class="w-full px-4 py-3 pl-10 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent uppercase"
                            required maxlength="3" autocomplete="off">
                        <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </div>
                    <div id="origin-suggestions" class="absolute z-50 w-full mt-1 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl shadow-lg hidden max-h-60 overflow-y-auto"></div>
                </div>

                <!-- Destination -->
                <div class="relative">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">To (Destination)</label>
                    <div class="relative">
                        <input type="text" id="destination" name="destination" placeholder="LHR - London" 
                            class="w-full px-4 py-3 pl-10 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent uppercase"
                            required maxlength="3" autocomplete="off">
                        <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                    </div>
                    <div id="destination-suggestions" class="absolute z-50 w-full mt-1 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl shadow-lg hidden max-h-60 overflow-y-auto"></div>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <!-- Date -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Departure Date</label>
                    <input type="date" name="date" 
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        required min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d', strtotime('+1 day')) }}">
                </div>

                <!-- Passengers -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Passengers</label>
                    <select name="passengers" class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'Passenger' : 'Passengers' }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            @if($errors->any())
                <div class="p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-xl transition-all hover:scale-[1.02] flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Search Flights
            </button>
        </form>
    </div>

    <!-- Quick Links -->
    <div class="mt-8 grid md:grid-cols-2 gap-4">
        <a href="{{ route('flights.index') }}" class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5 hover:border-emerald-500 dark:hover:border-emerald-400 transition-colors group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 dark:text-white">My Tickets</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">View your generated tickets</p>
                </div>
            </div>
        </a>

        <a href="{{ route('flights.search') }}" class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5 hover:border-emerald-500 dark:hover:border-emerald-400 transition-colors group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 dark:text-white">New Ticket</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Generate another ticket</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Popular Routes -->
    <div class="mt-12">
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Popular Routes</h3>
        <div class="grid md:grid-cols-3 gap-4">
            <button onclick="setRoute('JFK', 'LHR')" class="p-4 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-emerald-500 dark:hover:border-emerald-400 transition-colors text-left">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-bold text-slate-900 dark:text-white">JFK → LHR</span>
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">New York to London</p>
            </button>

            <button onclick="setRoute('LAX', 'DXB')" class="p-4 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-emerald-500 dark:hover:border-emerald-400 transition-colors text-left">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-bold text-slate-900 dark:text-white">LAX → DXB</span>
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Los Angeles to Dubai</p>
            </button>

            <button onclick="setRoute('SIN', 'HND')" class="p-4 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-emerald-500 dark:hover:border-emerald-400 transition-colors text-left">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-bold text-slate-900 dark:text-white">SIN → HND</span>
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Singapore to Tokyo</p>
            </button>
        </div>
    </div>
</div>

<script>
// Airport autocomplete
const airports = [
    { code: 'JFK', name: 'John F. Kennedy International', city: 'New York' },
    { code: 'LAX', name: 'Los Angeles International', city: 'Los Angeles' },
    { code: 'LHR', name: 'Heathrow Airport', city: 'London' },
    { code: 'CDG', name: 'Charles de Gaulle Airport', city: 'Paris' },
    { code: 'DXB', name: 'Dubai International', city: 'Dubai' },
    { code: 'HND', name: 'Haneda Airport', city: 'Tokyo' },
    { code: 'SIN', name: 'Changi Airport', city: 'Singapore' },
    { code: 'SYD', name: 'Sydney Kingsford Smith', city: 'Sydney' },
    { code: 'AMS', name: 'Amsterdam Schiphol', city: 'Amsterdam' },
    { code: 'FRA', name: 'Frankfurt Airport', city: 'Frankfurt' },
];

function setupAutocomplete(inputId, suggestionsId) {
    const input = document.getElementById(inputId);
    const suggestions = document.getElementById(suggestionsId);
    
    input.addEventListener('input', function() {
        const value = this.value.toUpperCase();
        if (value.length < 1) {
            suggestions.classList.add('hidden');
            return;
        }
        
        const matches = airports.filter(a => 
            a.code.includes(value) || 
            a.city.toLowerCase().includes(value.toLowerCase()) ||
            a.name.toLowerCase().includes(value.toLowerCase())
        ).slice(0, 5);
        
        if (matches.length > 0) {
            suggestions.innerHTML = matches.map(a => `
                <div class="p-3 hover:bg-slate-100 dark:hover:bg-slate-600 cursor-pointer border-b border-slate-100 dark:border-slate-600 last:border-0" 
                     onclick="selectAirport('${inputId}', '${suggestionsId}', '${a.code}', '${a.city}')">
                    <div class="font-semibold text-slate-900 dark:text-white">${a.code}</div>
                    <div class="text-sm text-slate-500 dark:text-slate-400">${a.name}, ${a.city}</div>
                </div>
            `).join('');
            suggestions.classList.remove('hidden');
        } else {
            suggestions.classList.add('hidden');
        }
    });
    
    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !suggestions.contains(e.target)) {
            suggestions.classList.add('hidden');
        }
    });
}

function selectAirport(inputId, suggestionsId, code, city) {
    document.getElementById(inputId).value = code;
    document.getElementById(suggestionsId).classList.add('hidden');
}

function setRoute(from, to) {
    document.getElementById('origin').value = from;
    document.getElementById('destination').value = to;
    document.querySelector('input[name="date"]').focus();
}

setupAutocomplete('origin', 'origin-suggestions');
setupAutocomplete('destination', 'destination-suggestions');
</script>
@endsection