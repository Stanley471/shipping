@extends('layouts.app')

@section('title', 'Profile')
@section('page-title', 'Profile')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    
    <!-- Update Profile Information -->
    <div class="dashboard-card rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Profile Information</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Update your account's profile information and email address.</p>
        
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('patch')
            
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                    class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-emerald-500 focus:ring-emerald-500" required>
                @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                    class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-emerald-500 focus:ring-emerald-500" required>
                @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div class="flex items-center gap-4 pt-2">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl font-medium transition-colors">
                    Save Changes
                </button>
                @if(session('status') === 'profile-updated')
                    <span class="text-sm text-emerald-600 dark:text-emerald-400">Saved successfully!</span>
                @endif
            </div>
        </form>
    </div>

    <!-- Update Password -->
    <div class="dashboard-card rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Update Password</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Ensure your account is using a long, random password to stay secure.</p>
        
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            @method('put')
            
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Current Password</label>
                <input type="password" name="current_password" 
                    class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-emerald-500 focus:ring-emerald-500" required>
                @error('current_password')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">New Password</label>
                <input type="password" name="password" 
                    class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-emerald-500 focus:ring-emerald-500" required>
                @error('password')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Confirm Password</label>
                <input type="password" name="password_confirmation" 
                    class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-emerald-500 focus:ring-emerald-500" required>
            </div>
            
            <div class="flex items-center gap-4 pt-2">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl font-medium transition-colors">
                    Update Password
                </button>
                @if(session('status') === 'password-updated')
                    <span class="text-sm text-emerald-600 dark:text-emerald-400">Password updated!</span>
                @endif
            </div>
        </form>
    </div>

    <!-- Delete Account -->
    <div class="dashboard-card rounded-2xl border border-red-200 dark:border-red-800/50 p-6 bg-red-50/50 dark:bg-red-900/10">
        <h3 class="text-lg font-bold text-red-600 dark:text-red-400 mb-4">Delete Account</h3>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
        
        <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
            @csrf
            @method('delete')
            
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Password</label>
                <input type="password" name="password" 
                    class="form-input w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-red-500 focus:ring-red-500" required placeholder="Enter your password to confirm">
                @error('password', 'userDeletion')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-xl font-medium transition-colors">
                Delete Account
            </button>
        </form>
    </div>
</div>
@endsection