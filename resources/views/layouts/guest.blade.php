<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="font-sans text-gray-900 antialiased">
    
    <!-- Mobile Navigation -->
    <nav class="fixed w-full z-40 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 lg:hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="/" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900 dark:text-white">Cargo Shipping</span>
                </a>
                <div class="flex items-center gap-2">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-emerald-600">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Split Screen Layout -->
    <div class="min-h-screen flex">
        
        <!-- Left Side - Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center px-4 sm:px-8 lg:px-16 py-12 pt-20 lg:pt-12 bg-gray-50 dark:bg-gray-900">
            
            <!-- Desktop Logo -->
            <div class="hidden lg:block mb-8 self-start">
                <a href="/" class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-gray-900 dark:text-white">Cargo Shipping</span>
                </a>
            </div>

            <!-- Form Container -->
            <div class="w-full max-w-md">
                {{ $slot }}
            </div>

            <!-- Footer Links -->
            <div class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400">
                <a href="/" class="hover:text-emerald-600 transition-colors">← Back to home</a>
            </div>
        </div>

        <!-- Right Side - Logistics Image (Desktop Only) -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-gray-100 dark:bg-gray-800 overflow-hidden">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0">
                <img 
                    src="https://images.unsplash.com/photo-1578575437130-527eed3abbec?w=1200&h=800&fit=crop" 
                    alt="Cargo ship and logistics"
                    class="w-full h-full object-cover"
                >
                <div class="absolute inset-0 bg-gradient-to-r from-gray-900/60 to-gray-900/30"></div>
            </div>

            <!-- Content Overlay -->
            <div class="relative z-10 flex flex-col justify-center h-full px-12 text-white">
                <div class="max-w-md">
                    <h2 class="text-4xl font-bold mb-4">Global Logistics Solutions</h2>
                    <p class="text-lg text-gray-200 mb-8">Track your shipments across air, sea, and land with real-time updates and complete transparency.</p>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-6 mb-8">
                        <div>
                            <div class="text-3xl font-bold text-emerald-400">50K+</div>
                            <div class="text-sm text-gray-300">Shipments</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-emerald-400">120+</div>
                            <div class="text-sm text-gray-300">Countries</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-emerald-400">99%</div>
                            <div class="text-sm text-gray-300">On Time</div>
                        </div>
                    </div>

                    <!-- Trust Badges -->
                    <div class="flex items-center gap-4">
                        <div class="flex -space-x-2">
                            <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center text-xs font-bold">JD</div>
                            <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-xs font-bold">MK</div>
                            <div class="w-8 h-8 rounded-full bg-purple-500 flex items-center justify-center text-xs font-bold">+5k</div>
                        </div>
                        <span class="text-sm text-gray-300">Trusted by 5,000+ businesses</span>
                    </div>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-gray-900/50 to-transparent"></div>
        </div>
    </div>

</body>
</html>