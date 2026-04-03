@extends('layouts.app')

@section('title', 'Ticket ' . $flightTicket->booking_reference)
@section('page-title', 'Ticket Details')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <!-- Breadcrumb -->
    <div class="mb-6">
        <a href="{{ route('flights.index') }}" class="text-emerald-600 dark:text-emerald-400 text-sm hover:underline">← Back to My Tickets</a>
    </div>

    <!-- Ticket Card -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mb-8">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-white">Boarding Pass</h1>
                    <p class="text-emerald-100 text-sm">{{ $flightTicket->airline }}</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-white">{{ $flightTicket->booking_reference }}</p>
                    <p class="text-emerald-100 text-xs">Booking Reference</p>
                </div>
            </div>
        </div>

        <div class="p-6 md:p-8">
            <!-- Route Display -->
            <div class="flex items-center justify-center gap-8 mb-8">
                <div class="text-center">
                    <p class="text-4xl font-bold text-slate-900 dark:text-white">{{ $flightTicket->origin }}</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ date('g:i A', strtotime($flightTicket->departure_time)) }}</p>
                </div>
                
                <div class="flex flex-col items-center">
                    <svg class="w-8 h-8 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                    </svg>
                    <p class="text-xs text-slate-400 mt-1">{{ $flightTicket->duration }}</p>
                </div>
                
                <div class="text-center">
                    <p class="text-4xl font-bold text-slate-900 dark:text-white">{{ $flightTicket->destination }}</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ date('g:i A', strtotime($flightTicket->arrival_time)) }}</p>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-4 text-center border border-slate-200 dark:border-slate-700">
                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Passenger</p>
                    <p class="font-bold text-slate-900 dark:text-white text-lg">{{ $flightTicket->passenger_name }}</p>
                </div>
                
                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-4 text-center border border-slate-200 dark:border-slate-700">
                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Flight</p>
                    <p class="font-bold text-slate-900 dark:text-white text-lg">{{ $flightTicket->flight_number }}</p>
                </div>
                
                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-4 text-center border border-slate-200 dark:border-slate-700">
                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Date</p>
                    <p class="font-bold text-slate-900 dark:text-white text-lg">{{ $flightTicket->flight_date->format('M d') }}</p>
                </div>
                
                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-4 text-center border border-slate-200 dark:border-slate-700">
                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Class</p>
                    <p class="font-bold text-slate-900 dark:text-white text-lg">{{ $flightTicket->class }}</p>
                </div>
            </div>

            <!-- Seat & Gate Info -->
            <div class="grid grid-cols-3 gap-4 mb-8">
                <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-6 text-center border border-emerald-200 dark:border-emerald-800">
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 uppercase tracking-wider mb-2">Seat</p>
                    <p class="text-3xl font-bold text-emerald-700 dark:text-emerald-400">{{ $flightTicket->seat }}</p>
                </div>
                
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-6 text-center border border-blue-200 dark:border-blue-800">
                    <p class="text-xs text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-2">Gate</p>
                    <p class="text-3xl font-bold text-blue-700 dark:text-blue-400">{{ $flightTicket->gate }}</p>
                </div>
                
                <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-6 text-center border border-purple-200 dark:border-purple-800">
                    <p class="text-xs text-purple-600 dark:text-purple-400 uppercase tracking-wider mb-2">Boarding</p>
                    <p class="text-3xl font-bold text-purple-700 dark:text-purple-400">
                        {{ date('g:i', strtotime($flightTicket->departure_time) - 3600) }}
                    </p>
                </div>
            </div>

            <!-- Barcode Section -->
            <div class="border-t border-slate-200 dark:border-slate-700 pt-6">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex-1">
                        <div class="h-16 bg-slate-900 dark:bg-white rounded-lg" style="background: repeating-linear-gradient(90deg, currentColor 0px, currentColor 2px, transparent 2px, transparent 4px, currentColor 4px, currentColor 6px, transparent 6px, transparent 9px);"></div>
                        <p class="text-center text-sm font-mono text-slate-500 mt-2">{{ $flightTicket->ticket_number }}</p>
                    </div>
                    
                    <div class="w-24 h-24 bg-slate-200 dark:bg-slate-700 rounded-lg flex items-center justify-center">
                        <svg class="w-12 h-12 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm2 2V5h1v1H5zM3 13a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1v-3zm2 2v-1h1v1H5zM13 3a1 1 0 00-1 1v3a1 1 0 001 1h3a1 1 0 001-1V4a1 1 0 00-1-1h-3zm1 2v1h1V5h-1z" clip-rule="evenodd"/>
                            <path d="M11 4a1 1 0 10-2 0v1a1 1 0 002 0V4zM10 7a1 1 0 011 1v1h2a1 1 0 110 2h-3a1 1 0 01-1-1V8a1 1 0 011-1zM16 9a1 1 0 100 2 1 1 0 000-2zM9 13a1 1 0 011-1h1a1 1 0 110 2v2a1 1 0 11-2 0v-3zM16 13a1 1 0 10-2 0v3a1 1 0 102 0v-3z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Disclaimer -->
            <div class="mt-6 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-sm text-amber-800 dark:text-amber-300">
                        <strong>For Entertainment Only:</strong> This is a novelty ticket generator. This boarding pass is NOT valid for actual air travel.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('flights.download', $flightTicket) }}" class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 rounded-xl font-bold transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Download PDF
        </a>
        
        <form action="{{ route('flights.destroy', $flightTicket) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ticket?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 px-8 py-4 rounded-xl font-bold transition-colors border border-red-200 dark:border-red-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Delete Ticket
            </button>
        </form>
    </div>
</div>
@endsection