<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Shipping Tracker'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        (function() {
            const isDark = localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    <style>
        /* Light mode: Soft off-white background */
        html:not(.dark) body {
            background-color: #f8fafc;
        }
        
        /* Dark mode: Rich dark blue background */
        html.dark body {
            background-color: #0f172a;
        }
        
        /* Sidebar background */
        html:not(.dark) .sidebar {
            background-color: #1e293b;
        }
        
        html.dark .sidebar {
            background-color: #0f172a;
        }
        
        /* Main content area */
        html:not(.dark) .main-content {
            background-color: #f1f5f9;
        }
        
        html.dark .main-content {
            background-color: #1e293b;
        }
        
        /* Card styling */
        html:not(.dark) .dashboard-card {
            background-color: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        html.dark .dashboard-card {
            background-color: #27272a;
        }
        
        /* Nav item active state */
        html:not(.dark) .nav-item-active {
            background-color: #334155;
            color: #ffffff;
        }
        
        html.dark .nav-item-active {
            background-color: #334155;
            color: #ffffff;
        }
        
        /* Nav item hover */
        html:not(.dark) .nav-item:hover {
            background-color: #334155;
            color: #ffffff;
        }
        
        html.dark .nav-item:hover {
            background-color: #1e293b;
            color: #ffffff;
        }
        
        /* Scrollbar styling for sidebar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 2px;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Desktop Sidebar (Permanent) -->
        <aside class="sidebar hidden lg:flex flex-col w-64 h-full border-r border-slate-700/50">
            <!-- Logo -->
            <div class="p-4 border-b border-slate-700/50">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-lg font-bold text-white block leading-tight">ShipTrack</span>
                        <span class="text-xs text-slate-400">Shipping Made Easy</span>
                    </div>
                </a>
            </div>
            
            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'nav-item-active' : 'text-slate-300' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>
                
                <a href="{{ route('shipments.create') }}" class="nav-item {{ request()->routeIs('shipments.create') ? 'nav-item-active' : 'text-slate-300' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create Shipment
                </a>
                
                <a href="{{ route('shipments.index') }}" class="nav-item {{ request()->routeIs('shipments.index') ? 'nav-item-active' : 'text-slate-300' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Shipments
                </a>
                
                @if(Auth::user()->isAdmin())
                <div class="pt-4 mt-4 border-t border-slate-700/50">
                    <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Admin</p>
                    <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'nav-item-active' : 'text-slate-300' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Users
                    </a>
                    <a href="{{ route('admin.shipments.index') }}" class="nav-item {{ request()->routeIs('admin.shipments.*') ? 'nav-item-active' : 'text-slate-300' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        All Shipments
                    </a>
                </div>
                @endif
            </nav>
            
            <!-- User Profile at Bottom -->
            <div class="p-4 border-t border-slate-700/50">
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="flex items-center gap-3 w-full p-2 rounded-lg hover:bg-slate-800 transition-colors">
                        <div class="w-9 h-9 rounded-full bg-emerald-600 flex items-center justify-center">
                            <span class="text-sm font-semibold text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 text-left overflow-hidden">
                            <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div x-show="open" x-transition class="absolute bottom-full left-0 right-0 mb-2 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 py-1 z-50">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Log out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile Sidebar Overlay -->
        <div id="mobile-sidebar-overlay" class="fixed inset-0 z-40 lg:hidden hidden">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleMobileSidebar()"></div>
            <div id="mobile-sidebar" class="absolute left-0 top-0 h-full w-72 sidebar transform -translate-x-full transition-transform duration-300">
                <!-- Mobile Sidebar Content -->
                <div class="flex flex-col h-full">
                    <!-- Logo -->
                    <div class="p-4 border-b border-slate-700/50 flex items-center justify-between">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <span class="text-lg font-bold text-white">ShipTrack</span>
                        </a>
                        <button onclick="toggleMobileSidebar()" class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Navigation -->
                    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'nav-item-active' : 'text-slate-300' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Dashboard
                        </a>
                        
                        <a href="{{ route('shipments.create') }}" class="nav-item {{ request()->routeIs('shipments.create') ? 'nav-item-active' : 'text-slate-300' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Create Shipment
                        </a>
                        
                        <a href="{{ route('shipments.index') }}" class="nav-item {{ request()->routeIs('shipments.index') ? 'nav-item-active' : 'text-slate-300' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            Shipments
                        </a>
                        
                        @if(Auth::user()->isAdmin())
                        <div class="pt-4 mt-4 border-t border-slate-700/50">
                            <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Admin</p>
                            <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'nav-item-active' : 'text-slate-300' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                Users
                            </a>
                            <a href="{{ route('admin.shipments.index') }}" class="nav-item {{ request()->routeIs('admin.shipments.*') ? 'nav-item-active' : 'text-slate-300' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                                All Shipments
                            </a>
                        </div>
                        @endif
                        
                        <div class="pt-4 mt-4 border-t border-slate-700/50">
                            <a href="{{ route('tracking.index') }}" class="nav-item text-slate-300 flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Public Tracking
                            </a>
                        </div>
                    </nav>
                    
                    <!-- User Profile -->
                    <div class="p-4 border-t border-slate-700/50">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-800 transition-colors">
                            <div class="w-9 h-9 rounded-full bg-emerald-600 flex items-center justify-center">
                                <span class="text-sm font-semibold text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-slate-400 truncate">View Profile</p>
                            </div>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="mt-2">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 w-full px-3 py-2 rounded-lg text-slate-300 hover:text-red-400 hover:bg-red-500/10 transition-colors text-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Log out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Top Header -->
            <header class="flex items-center justify-between px-4 lg:px-6 py-3 border-b border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900">
                <div class="flex items-center gap-4">
                    <!-- Mobile Menu Button -->
                    <button onclick="toggleMobileSidebar()" class="lg:hidden p-2 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    
                    <!-- Page Title -->
                    <h1 class="text-lg font-semibold text-slate-900 dark:text-white">
                        @yield('page-title', 'Dashboard')
                    </h1>
                </div>
                
                <div class="flex items-center gap-3">
                    <!-- Dark Mode Toggle -->
                    <button id="theme-toggle" onclick="toggleDarkMode()" class="p-2 rounded-lg text-slate-500 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                        <svg id="sun-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <svg id="moon-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: block;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>
                    
                    <!-- New Shipment Button -->
                    <a href="{{ route('shipments.create') }}" class="hidden sm:flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        New Shipment
                    </a>
                </div>
            </header>
            
            <!-- Main Content -->
            <main class="main-content flex-1 overflow-y-auto p-4 lg:p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 flex items-center gap-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 flex items-center gap-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function updateThemeIcons() {
            const isDark = document.documentElement.classList.contains('dark');
            const sunIcon = document.getElementById('sun-icon');
            const moonIcon = document.getElementById('moon-icon');
            
            if (sunIcon && moonIcon) {
                sunIcon.style.display = isDark ? 'block' : 'none';
                moonIcon.style.display = isDark ? 'none' : 'block';
            }
        }

        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
            updateThemeIcons();
        }

        document.addEventListener('DOMContentLoaded', updateThemeIcons);

        function toggleMobileSidebar() {
            const overlay = document.getElementById('mobile-sidebar-overlay');
            const sidebar = document.getElementById('mobile-sidebar');
            
            if (overlay.classList.contains('hidden')) {
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                setTimeout(() => {
                    sidebar.classList.remove('-translate-x-full');
                }, 10);
            } else {
                sidebar.classList.add('-translate-x-full');
                setTimeout(() => {
                    overlay.classList.add('hidden');
                    document.body.style.overflow = '';
                }, 300);
            }
        }

        // Close sidebar on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const overlay = document.getElementById('mobile-sidebar-overlay');
                if (!overlay.classList.contains('hidden')) {
                    toggleMobileSidebar();
                }
            }
        });
    </script>
</body>
</html>