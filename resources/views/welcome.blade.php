@extends('layouts.public')

@section('title', config('app.name', 'Shipping Tracker'))

@section('content')
    <!-- Hero Section with Background Image -->
    <section class="relative min-h-[600px] lg:min-h-[700px] flex items-center">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img 
                src="https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?w=1920&h=1080&fit=crop" 
                alt="Cargo ship at port"
                class="w-full h-full object-cover"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-gray-900/90 via-gray-900/70 to-gray-900/40"></div>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 pt-40 lg:pt-32">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500/20 backdrop-blur-sm rounded-full text-emerald-300 text-sm font-medium mb-6">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    Now serving 120+ countries worldwide
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white tracking-tight leading-tight">
                    Track Your Shipments<br>
                    <span class="text-emerald-400">Anywhere, Anytime</span>
                </h1>
                
                <p class="mt-6 text-lg md:text-xl text-gray-300 max-w-xl">
                    Simple, fast, and reliable courier tracking for businesses and customers. Create shipments, share tracking links, and monitor deliveries in real-time.
                </p>
                
                <div class="mt-10 flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('register') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 rounded-xl font-bold text-lg transition-colors shadow-lg shadow-emerald-600/25 text-center">
                        Start Shipping Free
                    </a>
                    <a href="{{ route('tracking.index') }}" class="bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white px-8 py-4 rounded-xl font-bold text-lg transition-colors text-center">
                        Track a Shipment
                    </a>
                </div>

                <!-- Trust Indicators -->
                <div class="mt-12 flex flex-wrap items-center gap-8 text-gray-400">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm">Real-time Tracking</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm">Secure & Reliable</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm">24/7 Support</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 hidden lg:block">
            <div class="w-6 h-10 border-2 border-white/30 rounded-full flex justify-center pt-2">
                <div class="w-1 h-2 bg-white/60 rounded-full animate-bounce"></div>
            </div>
        </div>
    </section>

    <!-- Stats Bar -->
    <section class="bg-emerald-600 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
                <div>
                    <div class="text-3xl md:text-4xl font-bold">50K+</div>
                    <div class="text-emerald-100 text-sm mt-1">Active Shipments</div>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-bold">120+</div>
                    <div class="text-emerald-100 text-sm mt-1">Countries Served</div>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-bold">99%</div>
                    <div class="text-emerald-100 text-sm mt-1">On-Time Delivery</div>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-bold">5K+</div>
                    <div class="text-emerald-100 text-sm mt-1">Business Partners</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Grid -->
    <section id="features" class="py-20 bg-gray-50 dark:bg-gray-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Everything You Need</h2>
                <p class="mt-4 text-gray-600 dark:text-gray-400">Powerful features for modern logistics</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg border border-gray-100 dark:border-gray-700">
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Real-Time Tracking</h3>
                    <p class="text-gray-600 dark:text-gray-400">Monitor shipments with live status updates and progress tracking from pickup to delivery.</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg border border-gray-100 dark:border-gray-700">
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Secure & Reliable</h3>
                    <p class="text-gray-600 dark:text-gray-400">Unique tracking IDs, role-based access, and encrypted data keep your shipments safe.</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg border border-gray-100 dark:border-gray-700">
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Mobile Friendly</h3>
                    <p class="text-gray-600 dark:text-gray-400">Track shipments on any device. Optimized for desktop, tablet, and mobile experiences.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section id="how-it-works" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">How It Works</h2>
                <p class="mt-4 text-gray-600 dark:text-gray-400">Get started in three simple steps</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold">1</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Create Shipment</h3>
                    <p class="text-gray-600 dark:text-gray-400">Enter sender and receiver details. We generate a unique tracking ID automatically.</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold">2</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Share Tracking</h3>
                    <p class="text-gray-600 dark:text-gray-400">Send the tracking ID or link to your customer. No account required to track.</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold">3</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Update Status</h3>
                    <p class="text-gray-600 dark:text-gray-400">Add progress updates as the shipment moves. Customers see real-time changes.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-emerald-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Ready to Start Tracking?</h2>
            <p class="text-emerald-100 text-lg mb-8">Join thousands of businesses shipping with confidence.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-white text-emerald-600 px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-100 transition-colors">
                    Create Free Account
                </a>
                <a href="{{ route('tracking.index') }}" class="bg-emerald-700 text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-emerald-800 transition-colors border border-emerald-500">
                    Track a Shipment
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center gap-2 mb-4 md:mb-0">
                    <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-white">Cargo Shipping</span>
                </div>
                <div class="flex gap-6 text-sm">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-white transition-colors">Contact</a>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-800 text-center text-sm">
                © {{ date('Y') }} Cargo Shipping. All rights reserved.
            </div>
        </div>
    </footer>
@endsection