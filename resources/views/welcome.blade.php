@extends('layouts.public')

@section('title', config('app.name', 'Shipping Tracker'))

@section('content')
    <style>
        /* Initial Load Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes countUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Animation Classes */
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
        }

        .animate-fade-in-scale {
            animation: fadeInScale 0.6s ease-out forwards;
            opacity: 0;
        }

        .animate-slide-left {
            animation: slideInLeft 0.8s ease-out forwards;
            opacity: 0;
        }

        .animate-slide-right {
            animation: slideInRight 0.8s ease-out forwards;
            opacity: 0;
        }

        /* Stagger delays */
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }
        .delay-600 { animation-delay: 0.6s; }

        /* Scroll Animation Classes */
        .scroll-animate {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .scroll-animate.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .scroll-animate-left {
            opacity: 0;
            transform: translateX(-60px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .scroll-animate-left.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .scroll-animate-right {
            opacity: 0;
            transform: translateX(60px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .scroll-animate-right.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .scroll-animate-scale {
            opacity: 0;
            transform: scale(0.9);
            transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .scroll-animate-scale.visible {
            opacity: 1;
            transform: scale(1);
        }

        /* Stagger children */
        .stagger-children > *:nth-child(1) { transition-delay: 0s; }
        .stagger-children > *:nth-child(2) { transition-delay: 0.1s; }
        .stagger-children > *:nth-child(3) { transition-delay: 0.2s; }
        .stagger-children > *:nth-child(4) { transition-delay: 0.3s; }
        .stagger-children > *:nth-child(5) { transition-delay: 0.4s; }
        .stagger-children > *:nth-child(6) { transition-delay: 0.5s; }
    </style>

    <!-- Hero Section with Background Image -->
    <section class="relative min-h-[600px] lg:min-h-[700px] flex items-center overflow-hidden">
        <!-- Background Image with Parallax -->
        <div class="absolute inset-0 z-0" id="hero-bg">
            <img 
                src="https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?w=1920&h=1080&fit=crop" 
                alt="Cargo ship at port"
                class="w-full h-full object-cover scale-110 transition-transform duration-[2s] ease-out"
                id="hero-image"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-gray-900/90 via-gray-900/70 to-gray-900/40"></div>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 pt-40 lg:pt-32">
            <div class="max-w-2xl">
                <div class="animate-fade-in-up inline-flex items-center gap-2 px-4 py-2 bg-emerald-500/20 backdrop-blur-sm rounded-full text-emerald-300 text-sm font-medium mb-6">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    Now serving 120+ countries worldwide
                </div>
                
                <h1 class="animate-fade-in-up delay-100 text-4xl md:text-5xl lg:text-6xl font-bold text-white tracking-tight leading-tight">
                    Track Your Shipments<br>
                    <span class="text-emerald-400">Anywhere, Anytime</span>
                </h1>
                
                <p class="animate-fade-in-up delay-200 mt-6 text-lg md:text-xl text-gray-300 max-w-xl">
                    Simple, fast, and reliable courier tracking for businesses and customers. Create shipments, share tracking links, and monitor deliveries in real-time.
                </p>
                
                <div class="animate-fade-in-up delay-300 mt-10 flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('register') }}" class="group bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300 shadow-lg shadow-emerald-600/25 text-center hover:scale-105 hover:shadow-xl">
                        Start Shipping Free
                        <svg class="inline-block w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <a href="{{ route('tracking.index') }}" class="bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300 text-center hover:scale-105">
                        Track a Shipment
                    </a>
                </div>

                <!-- Trust Indicators -->
                <div class="animate-fade-in-up delay-400 mt-12 flex flex-wrap items-center gap-8 text-gray-400">
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
        <div class="animate-fade-in-up delay-500 absolute bottom-8 left-1/2 -translate-x-1/2 hidden lg:block">
            <div class="w-6 h-10 border-2 border-white/30 rounded-full flex justify-center pt-2">
                <div class="w-1 h-2 bg-white/60 rounded-full animate-bounce"></div>
            </div>
        </div>
    </section>

    <!-- Stats Bar -->
    <section class="bg-emerald-600 py-8 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white stagger-children">
                <div class="scroll-animate">
                    <div class="text-3xl md:text-4xl font-bold">50K+</div>
                    <div class="text-emerald-100 text-sm mt-1">Active Shipments</div>
                </div>
                <div class="scroll-animate">
                    <div class="text-3xl md:text-4xl font-bold">120+</div>
                    <div class="text-emerald-100 text-sm mt-1">Countries Served</div>
                </div>
                <div class="scroll-animate">
                    <div class="text-3xl md:text-4xl font-bold">99%</div>
                    <div class="text-emerald-100 text-sm mt-1">On-Time Delivery</div>
                </div>
                <div class="scroll-animate">
                    <div class="text-3xl md:text-4xl font-bold">5K+</div>
                    <div class="text-emerald-100 text-sm mt-1">Business Partners</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Grid -->
    <section id="features" class="py-20 bg-gray-50 dark:bg-gray-800/50 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 scroll-animate-scale">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Everything You Need</h2>
                <p class="mt-4 text-gray-600 dark:text-gray-400">Powerful features for modern logistics</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 stagger-children">
                <div class="scroll-animate bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center mb-4 transform hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Real-Time Tracking</h3>
                    <p class="text-gray-600 dark:text-gray-400">Monitor shipments with live status updates and progress tracking from pickup to delivery.</p>
                </div>

                <div class="scroll-animate bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center mb-4 transform hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Secure & Reliable</h3>
                    <p class="text-gray-600 dark:text-gray-400">Unique tracking IDs, role-based access, and encrypted data keep your shipments safe.</p>
                </div>

                <div class="scroll-animate bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center mb-4 transform hover:scale-110 transition-transform">
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
    <section id="how-it-works" class="py-20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 scroll-animate-scale">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">How It Works</h2>
                <p class="mt-4 text-gray-600 dark:text-gray-400">Get started in three simple steps</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="scroll-animate-left text-center group">
                    <div class="w-16 h-16 bg-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold shadow-lg shadow-emerald-600/30 group-hover:scale-110 transition-transform duration-300">
                        1
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Create Shipment</h3>
                    <p class="text-gray-600 dark:text-gray-400">Enter sender and receiver details. We generate a unique tracking ID automatically.</p>
                </div>

                <div class="scroll-animate text-center group">
                    <div class="w-16 h-16 bg-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold shadow-lg shadow-emerald-600/30 group-hover:scale-110 transition-transform duration-300">
                        2
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Share Tracking</h3>
                    <p class="text-gray-600 dark:text-gray-400">Send the tracking ID or link to your customer. No account required to track.</p>
                </div>

                <div class="scroll-animate-right text-center group">
                    <div class="w-16 h-16 bg-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold shadow-lg shadow-emerald-600/30 group-hover:scale-110 transition-transform duration-300">
                        3
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Update Status</h3>
                    <p class="text-gray-600 dark:text-gray-400">Add progress updates as the shipment moves. Customers see real-time changes.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-emerald-600 overflow-hidden relative">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h2 class="scroll-animate-scale text-3xl md:text-4xl font-bold text-white mb-4">Ready to Start Tracking?</h2>
            <p class="scroll-animate delay-200 text-emerald-100 text-lg mb-8">Join thousands of businesses shipping with confidence.</p>
            <div class="scroll-animate delay-300 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="group bg-white text-emerald-600 px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-100 transition-all duration-300 hover:scale-105 hover:shadow-xl">
                    Create Free Account
                    <svg class="inline-block w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <a href="{{ route('tracking.index') }}" class="bg-emerald-700 text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-emerald-800 transition-all duration-300 border border-emerald-500 hover:scale-105">
                    Track a Shipment
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="scroll-animate flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center gap-2 mb-4 md:mb-0">
                    <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-white">ShipTrack</span>
                </div>
                <div class="flex gap-6 text-sm">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-white transition-colors">Contact</a>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-800 text-center text-sm scroll-animate delay-200">
                © {{ date('Y') }} ShipTrack. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Scroll Animation Script -->
    <script>
        // Hero image zoom effect on load
        window.addEventListener('load', function() {
            const heroImage = document.getElementById('hero-image');
            if (heroImage) {
                heroImage.style.transform = 'scale(1)';
            }
        });

        // Intersection Observer for scroll animations
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observe all scroll-animated elements
        document.querySelectorAll('.scroll-animate, .scroll-animate-left, .scroll-animate-right, .scroll-animate-scale').forEach((el) => {
            observer.observe(el);
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
@endsection