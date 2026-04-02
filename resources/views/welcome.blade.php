@extends('layouts.public')

@section('title', config('app.name', 'Shipping Tracker'))

@section('content')
    <style>
        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
        }

        .hero-bg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.7) 50%, rgba(16, 185, 129, 0.1) 100%);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        /* Typography */
        .hero-title {
            font-size: clamp(3rem, 8vw, 6rem);
            font-weight: 800;
            line-height: 1;
            letter-spacing: -0.02em;
        }

        .hero-subtitle {
            font-size: clamp(1rem, 2vw, 1.25rem);
            line-height: 1.6;
            opacity: 0.9;
        }

        /* Floating Elements */
        .floating-badge {
            animation: float 6s ease-in-out infinite;
        }

        .floating-badge-delayed {
            animation: float 6s ease-in-out infinite;
            animation-delay: -3s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        /* Scroll Indicator */
        .scroll-indicator {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2;
        }

        .scroll-mouse {
            width: 26px;
            height: 40px;
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-radius: 13px;
            position: relative;
        }

        .scroll-dot {
            width: 4px;
            height: 4px;
            background: #10b981;
            border-radius: 50%;
            position: absolute;
            top: 8px;
            left: 50%;
            transform: translateX(-50%);
            animation: scrollDot 2s ease-in-out infinite;
        }

        @keyframes scrollDot {
            0%, 100% { top: 8px; opacity: 1; }
            50% { top: 20px; opacity: 0; }
        }

        /* Glow Effect */
        .glow-emerald {
            box-shadow: 0 0 60px rgba(16, 185, 129, 0.3);
        }

        /* Stats Counter Animation */
        .stat-number {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Feature Cards */
        .feature-card {
            background: rgba(30, 41, 59, 0.5);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: rgba(16, 185, 129, 0.5);
            box-shadow: 0 20px 40px rgba(16, 185, 129, 0.1);
        }

        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #ffffff 0%, #10b981 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Light Trail Animation */
        .light-trail {
            position: absolute;
            width: 2px;
            background: linear-gradient(to bottom, transparent, #10b981, transparent);
            opacity: 0.3;
            animation: trailMove 8s linear infinite;
        }

        @keyframes trailMove {
            0% { transform: translateY(-100%); opacity: 0; }
            50% { opacity: 0.5; }
            100% { transform: translateY(100vh); opacity: 0; }
        }
    </style>

    <!-- Hero Section -->
    <section class="hero-section">
        <!-- Animated Light Trails -->
        <div class="light-trail" style="left: 20%; height: 150px; animation-delay: 0s;"></div>
        <div class="light-trail" style="left: 40%; height: 200px; animation-delay: 2s;"></div>
        <div class="light-trail" style="left: 60%; height: 120px; animation-delay: 4s;"></div>
        <div class="light-trail" style="left: 80%; height: 180px; animation-delay: 6s;"></div>

        <!-- Background Image -->
        <div class="hero-bg">
            <img src="https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?w=1920&q=80" 
                 alt="Highway at night with light trails" 
                 class="scale-105">
        </div>

        <!-- Overlay -->
        <div class="hero-overlay"></div>

        <!-- Content -->
        <div class="hero-content w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                
                <!-- Left: Text Content -->
                <div class="space-y-8">
                    <!-- Badge -->
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-sm font-medium">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                        Now Serving 120+ Countries
                    </div>

                    <!-- Title -->
                    <h1 class="hero-title text-white">
                        Logistics<br>
                        <span class="gradient-text">Accelerated</span>
                    </h1>

                    <!-- Subtitle -->
                    <p class="hero-subtitle text-slate-300 max-w-lg">
                        Streamline your supply chain with real-time tracking, automated workflows, 
                        and intelligent logistics solutions that keep your business moving forward.
                    </p>

                    <!-- Tracking Input -->
                    <div class="max-w-md">
                        <form action="{{ route('tracking.search') }}" method="POST" class="relative">
                            @csrf
                            <input type="text" name="tracking_id" 
                                placeholder="Enter your tracking number..."
                                class="w-full px-6 py-4 pr-36 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all"
                                required>
                            <button type="submit" 
                                class="absolute right-2 top-1/2 -translate-y-1/2 bg-emerald-600 hover:bg-emerald-500 text-white px-6 py-2.5 rounded-xl font-semibold transition-all hover:scale-105">
                                Track
                            </button>
                        </form>
                        @error('tracking_id')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('register') }}" 
                           class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white px-8 py-4 rounded-2xl font-bold text-lg transition-all hover:scale-105 glow-emerald">
                            Get Started
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                        <a href="#features" 
                           class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 backdrop-blur-md text-white px-8 py-4 rounded-2xl font-bold text-lg transition-all border border-white/20">
                            Learn More
                        </a>
                    </div>

                    <!-- Trust Badges -->
                    <div class="flex items-center gap-6 pt-4">
                        <div class="flex -space-x-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center text-white text-xs font-bold border-2 border-slate-900">5K+</div>
                            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-bold border-2 border-slate-900">4.9</div>
                            <div class="w-10 h-10 rounded-full bg-purple-500 flex items-center justify-center text-white text-xs font-bold border-2 border-slate-900">99%</div>
                        </div>
                        <p class="text-slate-400 text-sm">Trusted by industry leaders worldwide</p>
                    </div>
                </div>

                <!-- Right: Floating Stats Cards -->
                <div class="hidden lg:block relative h-[500px]">
                    <!-- Card 1: Active Shipments -->
                    <div class="floating-badge absolute top-10 right-0 bg-slate-800/90 backdrop-blur-xl rounded-2xl p-6 border border-emerald-500/30 shadow-2xl shadow-emerald-500/10">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-xl bg-emerald-500/20 flex items-center justify-center">
                                <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-3xl font-bold text-white">50K+</p>
                                <p class="text-slate-400 text-sm">Active Shipments</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: On Time Delivery -->
                    <div class="floating-badge-delayed absolute top-40 left-10 bg-slate-800/90 backdrop-blur-xl rounded-2xl p-6 border border-emerald-500/30 shadow-2xl shadow-emerald-500/10">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-xl bg-emerald-500/20 flex items-center justify-center">
                                <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-3xl font-bold text-white">99%</p>
                                <p class="text-slate-400 text-sm">On-Time Delivery</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Countries -->
                    <div class="floating-badge absolute bottom-20 right-10 bg-slate-800/90 backdrop-blur-xl rounded-2xl p-6 border border-emerald-500/30 shadow-2xl shadow-emerald-500/10">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-xl bg-emerald-500/20 flex items-center justify-center">
                                <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-3xl font-bold text-white">120+</p>
                                <p class="text-slate-400 text-sm">Countries Served</p>
                            </div>
                        </div>
                    </div>

                    <!-- Decorative Circle -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 rounded-full border border-emerald-500/20 animate-pulse"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 rounded-full border border-emerald-500/10"></div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="scroll-indicator">
            <div class="scroll-mouse">
                <div class="scroll-dot"></div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-slate-950 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-emerald-500/5 to-transparent"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <p class="stat-number">50K+</p>
                    <p class="text-slate-400 mt-2">Active Shipments</p>
                </div>
                <div class="text-center">
                    <p class="stat-number">120+</p>
                    <p class="text-slate-400 mt-2">Countries Served</p>
                </div>
                <div class="text-center">
                    <p class="stat-number">99%</p>
                    <p class="text-slate-400 mt-2">On-Time Rate</p>
                </div>
                <div class="text-center">
                    <p class="stat-number">24/7</p>
                    <p class="text-slate-400 mt-2">Customer Support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-emerald-400 font-semibold uppercase tracking-wider text-sm">Features</span>
                <h2 class="text-4xl md:text-5xl font-bold text-white mt-4 mb-6">Everything You Need</h2>
                <p class="text-slate-400 max-w-2xl mx-auto text-lg">
                    Powerful tools and features designed to streamline your logistics operations
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card rounded-3xl p-8">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-500/20 flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Real-Time Tracking</h3>
                    <p class="text-slate-400">Monitor your shipments 24/7 with live updates and precise location tracking from pickup to delivery.</p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card rounded-3xl p-8">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-500/20 flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Secure & Reliable</h3>
                    <p class="text-slate-400">Enterprise-grade security with encrypted data, unique tracking IDs, and role-based access control.</p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card rounded-3xl p-8">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-500/20 flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Mobile Optimized</h3>
                    <p class="text-slate-400">Track shipments on any device. Fully responsive design optimized for desktop, tablet, and mobile.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-24 bg-slate-950 relative">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-emerald-500/10 via-transparent to-transparent"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <span class="text-emerald-400 font-semibold uppercase tracking-wider text-sm">How It Works</span>
                <h2 class="text-4xl md:text-5xl font-bold text-white mt-4 mb-6">Three Simple Steps</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="text-center group">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white text-3xl font-bold mx-auto mb-6 shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform">
                        1
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Create Shipment</h3>
                    <p class="text-slate-400">Enter sender and receiver details. We automatically generate a unique tracking ID.</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center group">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white text-3xl font-bold mx-auto mb-6 shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform">
                        2
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Share Tracking</h3>
                    <p class="text-slate-400">Send the tracking ID to your customer. No account required to track shipments.</p>
                </div>

                <!-- Step 3 -->
                <div class="text-center group">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white text-3xl font-bold mx-auto mb-6 shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform">
                        3
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Update Status</h3>
                    <p class="text-slate-400">Add progress updates as the shipment moves. Customers see real-time changes.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-gradient-to-br from-emerald-600 to-emerald-700 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Ready to Accelerate?</h2>
            <p class="text-emerald-100 text-xl mb-10">Join thousands of businesses shipping with confidence.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 bg-white text-emerald-600 px-10 py-5 rounded-2xl font-bold text-lg hover:bg-emerald-50 transition-all hover:scale-105 shadow-xl">
                    Create Free Account
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <a href="{{ route('tracking.index') }}" class="inline-flex items-center justify-center gap-2 bg-emerald-700 text-white px-10 py-5 rounded-2xl font-bold text-lg hover:bg-emerald-800 transition-all border border-emerald-500">
                    Track a Shipment
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-950 border-t border-slate-800 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-white">Cargo Shippings</span>
                </div>
                <div class="flex gap-8 text-slate-400">
                    <a href="#" class="hover:text-emerald-400 transition-colors">Privacy</a>
                    <a href="#" class="hover:text-emerald-400 transition-colors">Terms</a>
                    <a href="#" class="hover:text-emerald-400 transition-colors">Contact</a>
                </div>
                <p class="text-slate-500 text-sm">© {{ date('Y') }} Cargo Shippings. All rights reserved.</p>
            </div>
        </div>
    </footer>
@endsection