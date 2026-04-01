@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="dashboard-card rounded-2xl p-6 border border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Total Users</p>
                    <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $totalUsers }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="dashboard-card rounded-2xl p-6 border border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Total Shipments</p>
                    <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $totalShipments }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="dashboard-card rounded-2xl p-6 border border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Quick Links</p>
                    <div class="flex gap-3 mt-2">
                        <a href="{{ route('admin.users.index') }}" class="text-sm text-emerald-600 dark:text-emerald-400 hover:underline">Users</a>
                        <a href="{{ route('admin.shipments.index') }}" class="text-sm text-emerald-600 dark:text-emerald-400 hover:underline">Shipments</a>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Shipments -->
    <div class="dashboard-card rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="p-5 border-b border-slate-200 dark:border-slate-700">
            <h3 class="font-bold text-slate-900 dark:text-white">Recent Shipments</h3>
        </div>
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
            <thead class="table-header">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Tracking ID</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">User</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Receiver</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($recentShipments as $shipment)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                    <td class="px-6 py-4 font-mono text-sm text-slate-900 dark:text-white">{{ $shipment->tracking_id }}</td>
                    <td class="px-6 py-4 text-slate-700 dark:text-slate-300">{{ $shipment->user->name }}</td>
                    <td class="px-6 py-4 text-slate-700 dark:text-slate-300">{{ $shipment->receiver_name }}</td>
                    <td class="px-6 py-4 text-slate-500 dark:text-slate-400">{{ $shipment->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">No shipments yet</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection