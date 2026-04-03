@extends('layouts.app')

@section('title', 'All Shipments')
@section('page-title', 'All Shipments')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800">
            <h3 class="font-bold text-slate-900 dark:text-white text-lg">All Shipments</h3>
            <span class="text-sm text-slate-600 dark:text-slate-400">{{ $shipments->total() }} total shipments</span>
        </div>
        
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
            <thead class="bg-slate-100 dark:bg-slate-700">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Tracking ID</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">User</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Receiver</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Shipped</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($shipments as $shipment)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="px-6 py-4 font-mono text-sm font-medium text-slate-900 dark:text-white">{{ $shipment->tracking_id }}</td>
                    <td class="px-6 py-4 text-sm text-slate-800 dark:text-slate-200">{{ $shipment->user->name }}</td>
                    <td class="px-6 py-4 text-sm text-slate-800 dark:text-slate-200">{{ $shipment->receiver_name }}</td>
                    <td class="px-6 py-4">
                        <span class="capitalize text-sm text-slate-700 dark:text-slate-300">{{ str_replace('_', ' ', $shipment->shipment_type) }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">{{ $shipment->shipped_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">No shipments found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800">
            {{ $shipments->links() }}
        </div>
    </div>
</div>
@endsection