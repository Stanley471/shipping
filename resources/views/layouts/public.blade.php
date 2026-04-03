<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        /* Transparent navbar on hero */
        .navbar-transparent {
            background: transparent;
            backdrop-filter: none;
        }
        
        /* Solid navbar on scroll - dark mode */
        .dark .navbar-solid {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Solid navbar on scroll - light mode */
        html:not(.dark) .navbar-solid {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        /* Mobile menu - dark mode */
        .dark .mobile-menu {
            background: rgba(15, 23, 42, 0.98);
            backdrop-filter: blur(20px);
        }
        
        /* Mobile menu - light mode */
        html:not(.dark) .mobile-menu {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
        }
    </style>
</head>
<body class="antialiased bg-white dark:bg-slate-950 text-slate-900 dark:text-white">
    
    <!-- Navigation -->
    <nav id="main-nav" class="fixed w-full z-50 transition-all duration-300 navbar-transparent">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-white">Cargo Shippings</span>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="/" class="text-white/90 hover:text-emerald-400 font-medium transition-colors">Home</a>
                    <a href="{{ route('tracking.index') }}" class="text-white/90 hover:text-emerald-400 font-medium transition-colors">Track</a>
                    <a href="/#features" class="text-white/90 hover:text-emerald-400 font-medium transition-colors">Features</a>
                </div>

                <!-- Desktop Right Side -->
                <div class="hidden md:flex items-center gap-4">
                    <!-- Dark Mode Toggle -->
                    <button id="theme-toggle" onclick="toggleDarkMode()" class="p-2.5 rounded-xl text-white/80 hover:text-emerald-400 hover:bg-white/10 transition-all" aria-label="Toggle dark mode">
                        <svg id="sun-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <svg id="moon-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>

                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="text-white/90 hover:text-white font-medium transition-colors">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-emerald-600 hover:bg-emerald-500 text-white px-6 py-2.5 rounded-xl font-semibold transition-all hover:scale-105 shadow-lg shadow-emerald-500/30">
                                Get Started
                            </a>
                        @endif
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <button onclick="toggleMobileMenu()" class="md:hidden p-2.5 rounded-xl text-white hover:bg-white/10 transition-colors">
                    <svg id="menu-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu" class="fixed inset-0 z-40 hidden md:hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleMobileMenu()"></div>
        <div class="absolute right-0 top-0 h-full w-80 max-w-full mobile-menu transform translate-x-full transition-transform duration-300" id="mobile-sidebar">
            <div class="flex flex-col h-full p-6">
                <div class="flex justify-between items-center mb-8">
                    <span class="text-xl font-bold text-slate-900 dark:text-white">Menu</span>
                    <button onclick="toggleMobileMenu()" class="p-2 rounded-lg text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <nav class="flex-1 space-y-2">
                    <a href="/" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-700 dark:text-white/80 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-emerald-600 dark:hover:text-emerald-400 font-medium transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Home
                    </a>
                    <a href="{{ route('tracking.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-700 dark:text-white/80 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-emerald-600 dark:hover:text-emerald-400 font-medium transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Track Shipment
                    </a>
                    <a href="/#features" onclick="toggleMobileMenu()" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-700 dark:text-white/80 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-emerald-600 dark:hover:text-emerald-400 font-medium transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                        Features
                    </a>
                </nav>

                <div class="space-y-3 pt-6 border-t border-slate-200 dark:border-white/10">
                    <a href="{{ route('login') }}" class="flex items-center justify-center w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-white/20 text-slate-700 dark:text-white font-medium hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="flex items-center justify-center w-full px-4 py-3 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-500 transition-colors">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <script>
        function updateThemeIcons() {
            const isDark = document.documentElement.classList.contains('dark');
            const sunIcon = document.getElementById('sun-icon');
            const moonIcon = document.getElementById('moon-icon');
            
            if (sunIcon && moonIcon) {
                sunIcon.classList.toggle('hidden', !isDark);
                moonIcon.classList.toggle('hidden', isDark);
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

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const sidebar = document.getElementById('mobile-sidebar');
            const menuIcon = document.getElementById('menu-icon');
            const closeIcon = document.getElementById('close-icon');
            
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                setTimeout(() => {
                    sidebar.classList.remove('translate-x-full');
                }, 10);
                menuIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
            } else {
                sidebar.classList.add('translate-x-full');
                setTimeout(() => {
                    menu.classList.add('hidden');
                    document.body.style.overflow = '';
                }, 300);
                menuIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            }
        }

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const nav = document.getElementById('main-nav');
            const isDark = document.documentElement.classList.contains('dark');
            
            if (window.scrollY > 100) {
                nav.classList.remove('navbar-transparent');
                nav.classList.add('navbar-solid');
                
                // Switch to solid navbar text colors
                if (!isDark) {
                    document.querySelectorAll('#main-nav a:not(.bg-emerald-600)').forEach(link => {
                        link.classList.remove('text-white/90', 'hover:text-emerald-400');
                        link.classList.add('text-slate-700', 'hover:text-emerald-600');
                    });
                    document.querySelector('#main-nav span.text-white')?.classList.remove('text-white');
                    document.querySelector('#main-nav span')?.classList.add('text-slate-900');
                    document.querySelector('#theme-toggle')?.classList.remove('text-white/80', 'hover:text-emerald-400');
                    document.querySelector('#theme-toggle')?.classList.add('text-slate-600', 'hover:text-emerald-600');
                    document.querySelector('[onclick="toggleMobileMenu()"]')?.classList.remove('text-white');
                    document.querySelector('[onclick="toggleMobileMenu()"]')?.classList.add('text-slate-700');
                }
            } else {
                nav.classList.add('navbar-transparent');
                nav.classList.remove('navbar-solid');
                
                // Switch back to transparent navbar text colors (white)
                if (!isDark) {
                    document.querySelectorAll('#main-nav a:not(.bg-emerald-600)').forEach(link => {
                        link.classList.remove('text-slate-700', 'hover:text-emerald-600');
                        link.classList.add('text-white/90', 'hover:text-emerald-400');
                    });
                    document.querySelector('#main-nav span')?.classList.remove('text-slate-900');
                    document.querySelector('#main-nav span')?.classList.add('text-white');
                    document.querySelector('#theme-toggle')?.classList.remove('text-slate-600', 'hover:text-emerald-600');
                    document.querySelector('#theme-toggle')?.classList.add('text-white/80', 'hover:text-emerald-400');
                    document.querySelector('[onclick="toggleMobileMenu()"]')?.classList.remove('text-slate-700');
                    document.querySelector('[onclick="toggleMobileMenu()"]')?.classList.add('text-white');
                }
            }
        });

        // Close mobile menu on escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const menu = document.getElementById('mobile-menu');
                if (!menu.classList.contains('hidden')) {
                    toggleMobileMenu();
                }
            }
        });
    </script>
</body>
</html>