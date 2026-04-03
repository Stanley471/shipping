@extends('layouts.app')

@section('title', 'My Flight Tickets')
@section('page-title', 'My Tickets')

@section('content')
<div class="max-w-6xl mx-auto">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">My Flight Tickets</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">View and download your generated boarding passes</p>
        </div>
        <a href="{{ route('flights.search') }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Generate New Ticket
        </a>
    </div>

    @if($tickets->count() > 0)
        <!-- Stats -->
        <div class="grid md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5">
                <p class="text-sm text-slate-500 dark:text-slate-400">Total Tickets</p>
                <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $tickets->total() }}</p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5">
                <p class="text-sm text-slate-500 dark:text-slate-400">Upcoming</p>
                <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                    {{ $tickets->filter(fn($t) => $t->status === 'upcoming')->count() }}
                </p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5">
                <p class="text-sm text-slate-500 dark:text-slate-400">Total Downloads</p>
                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $tickets->sum('download_count') }}</p>
            </div>
        </div>

        <!-- Tickets List -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Flight</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Passenger</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach($tickets as $ticket)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center text-lg">
                                            ✈️
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-900 dark:text-white">{{ $ticket->origin }} → {{ $ticket->destination }}</p>
                                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $ticket->airline }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-slate-900 dark:text-white">{{ $ticket->passenger_name }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $ticket->booking_reference }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-emerald-600 dark:text-emerald-400">${{ number_format($ticket->price, 2) }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-slate-900 dark:text-white">{{ $ticket->flight_date->format('M d, Y') }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ date('g:i A', strtotime($ticket->departure_time)) }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if($ticket->status === 'completed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            Completed
                                        </span>
                                    @elseif($ticket->status === 'upcoming')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400">
                                            Upcoming
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-400">
                                            Scheduled
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('flights.show', $ticket) }}" class="p-2 text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors" title="View">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('flights.download', $ticket) }}" class="p-2 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="Download PDF">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('flights.destroy', $ticket) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($tickets->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-12 text-center">
            <div class="w-20 h-20 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No tickets yet</h3>
            <p class="text-slate-500 dark:text-slate-400 mb-6 max-w-md mx-auto">Generate your first flight ticket to create realistic boarding passes for entertainment purposes.</p>
            <a href="{{ route('flights.search') }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Generate First Ticket
            </a>
        </div>
    @endif
</div>
@endsection