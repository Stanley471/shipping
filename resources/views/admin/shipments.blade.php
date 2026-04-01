@extends('layouts.app')

@section('title', 'All Shipments')
@section('page-title', 'All Shipments')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <div class="dashboard-card rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="p-5 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
            <h3 class="font-bold text-slate-900 dark:text-white">All Shipments</h3>
            <span class="text-sm text-slate-500 dark:text-slate-400">{{ $shipments->total() }} total shipments</span>
        </div>
        
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
            <thead class="table-header">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Tracking ID</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">User</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Receiver</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Type</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Shipped</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($shipments as $shipment)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                    <td class="px-6 py-4 font-mono text-sm text-slate-900 dark:text-white">{{ $shipment->tracking_id }}</td>
                    <td class="px-6 py-4 text-slate-700 dark:text-slate-300">{{ $shipment->user->name }}</td>
                    <td class="px-6 py-4 text-slate-700 dark:text-slate-300">{{ $shipment->receiver_name }}</td>
                    <td class="px-6 py-4">
                        <span class="capitalize text-slate-600 dark:text-slate-400">{{ str_replace('_', ' ', $shipment->shipment_type) }}</span>
                    </td>
                    <td class="px-6 py-4 text-slate-500 dark:text-slate-400">{{ $shipment->shipped_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">No shipments found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
            {{ $shipments->links() }}
        </div>
    </div>
</div>
@endsection