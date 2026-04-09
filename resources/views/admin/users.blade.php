@extends('layouts.app')

@section('title', 'Manage Users')
@section('page-title', 'Manage Users')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800">
            <h3 class="font-bold text-slate-900 dark:text-white text-lg">All Users</h3>
            <span class="text-sm text-slate-600 dark:text-slate-400">{{ $users->total() }} total users</span>
        </div>
        
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
            <thead class="bg-slate-100 dark:bg-slate-700">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Vendor</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="px-6 py-4 text-slate-900 dark:text-white font-medium">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-slate-700 dark:text-slate-300">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize
                            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-400' : 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300' }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->is_active)
                            <span class="inline-flex items-center gap-1.5 text-green-600 dark:text-green-400 font-medium text-sm">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-red-600 dark:text-red-400 font-medium text-sm">
                                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                Suspended
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($user->is_vendor)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-400">
                                ✓ Vendor
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-400">
                                User
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 space-y-2">
                        @if($user->id !== auth()->id())
                            <div class="flex gap-3">
                                <form method="POST" action="{{ route('admin.users.toggle', $user) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm font-medium {{ $user->is_active ? 'text-red-600 dark:text-red-400 hover:text-red-800' : 'text-green-600 dark:text-green-400 hover:text-green-800' }} transition-colors">
                                        {{ $user->is_active ? 'Suspend' : 'Activate' }}
                                    </button>
                                </form>
                                <span class="text-slate-300">|</span>
                                <form method="POST" action="{{ route('admin.users.toggle-vendor', $user) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm font-medium {{ $user->is_vendor ? 'text-amber-600 dark:text-amber-400 hover:text-amber-800' : 'text-emerald-600 dark:text-emerald-400 hover:text-emerald-800' }} transition-colors">
                                        {{ $user->is_vendor ? 'Remove Vendor' : 'Make Vendor' }}
                                    </button>
                                </form>
                            </div>
                        @else
                            <span class="text-slate-400 text-sm">You</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">No users found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection