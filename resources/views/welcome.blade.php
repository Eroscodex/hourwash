<!DOCTYPE html>
<html lang="en" class="dark scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hour Wash Laundry Management System</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-900 dark:bg-[#09090B] dark:text-zinc-100 font-sans antialiased selection:bg-blue-600 selection:text-white min-h-screen flex flex-col transition-colors duration-200">

    <!-- Storefront Main Navigation Header -->
    <header class="sticky top-0 z-50 bg-white/95 dark:bg-[#141417]/95 border-b border-slate-200 dark:border-zinc-800 px-4 md:px-8 py-3 shadow-sm backdrop-blur-sm">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">

            <a href="{{ route('welcome') }}" class="flex items-center gap-2.5 shrink-0">
                <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-9 h-9 sm:w-10 sm:h-10 rounded-md object-cover bg-white p-0.5 border border-slate-200 dark:border-zinc-700 shadow-sm shrink-0">
                <div>
                    <span class="text-base sm:text-lg font-bold tracking-tight text-slate-900 dark:text-white block leading-tight">
                        HOUR WASH
                    </span>
                    <span class="hidden sm:block text-[9px] sm:text-[10px] text-blue-600 dark:text-blue-400 tracking-wider uppercase font-semibold whitespace-nowrap">LAUNDRY MANAGEMENT SYSTEM</span>
                </div>
            </a>

            <nav class="hidden lg:flex items-center gap-5 text-xs font-semibold text-slate-600 dark:text-zinc-300 whitespace-nowrap">
                <a href="#home" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Home</a>
                <a href="#services" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Services & Rates</a>
                <a href="#how-it-works" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">How It Works</a>
                <a href="#track-section" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Track Order</a>
                <a href="#reviews-section" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Customer Reviews</a>
            </nav>

            <div class="flex items-center gap-2 shrink-0">
                <button id="welcome-theme-toggle" class="p-1.5 sm:px-2.5 rounded-md bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-zinc-200 border border-slate-200 dark:border-zinc-700 hover:bg-slate-200 dark:hover:bg-zinc-700 transition text-[11px] font-medium flex items-center gap-1.5 shadow-sm whitespace-nowrap" title="Toggle Light/Dark Theme">
                    <span class="dark:hidden flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span class="hidden sm:inline">Light</span>
                    </span>
                    <span class="hidden dark:flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <span class="hidden sm:inline">Dark</span>
                    </span>
                </button>

                <div class="hidden lg:flex items-center gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-primary text-[11px] py-1.5 px-3 whitespace-nowrap">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-secondary text-[11px] py-1.5 px-3 whitespace-nowrap">
                            Log In
                        </a>
                        <a href="{{ route('register') }}" class="btn-primary text-[11px] py-1.5 px-3 whitespace-nowrap">
                            Register Account
                        </a>
                    @endauth
                </div>

                <button id="welcome-mobile-toggle" class="lg:hidden p-1.5 rounded-md text-slate-700 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-800 focus:outline-none" aria-label="Toggle Mobile Navigation">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Slideover Navigation Drawer -->
    <div id="welcome-mobile-overlay" class="fixed inset-0 bg-slate-900/60 dark:bg-black/80 z-50 hidden lg:hidden transition-opacity"></div>

    <div id="welcome-mobile-menu" class="fixed top-0 right-0 bottom-0 w-72 bg-white dark:bg-[#141417] border-l border-slate-200 dark:border-zinc-800 z-50 transform translate-x-full lg:hidden transition-transform duration-200 flex flex-col justify-between p-6 shadow-xl">
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-zinc-800 pb-4">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-8 h-8 rounded-md object-cover bg-white p-0.5 border border-slate-200 dark:border-zinc-700">
                    <span class="font-bold text-slate-900 dark:text-white text-sm">Hour Wash Menu</span>
                </div>
                <button id="welcome-mobile-close" class="text-slate-500 dark:text-zinc-400 hover:text-slate-900 dark:hover:text-white p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <nav class="flex flex-col space-y-2 font-medium text-xs">
                <a href="#home" class="mobile-nav-link text-blue-600 dark:text-blue-400 font-bold py-2 border-b border-slate-100 dark:border-zinc-800">Home</a>
                <a href="#services" class="mobile-nav-link text-slate-700 dark:text-zinc-200 hover:text-blue-600 py-2 border-b border-slate-100 dark:border-zinc-800">Services & Rates</a>
                <a href="#how-it-works" class="mobile-nav-link text-slate-700 dark:text-zinc-200 hover:text-blue-600 py-2 border-b border-slate-100 dark:border-zinc-800">How It Works</a>
                <a href="#track-section" class="mobile-nav-link text-slate-700 dark:text-zinc-200 hover:text-blue-600 py-2 border-b border-slate-100 dark:border-zinc-800">Track Order</a>
                <a href="#reviews-section" class="mobile-nav-link text-slate-700 dark:text-zinc-200 hover:text-blue-600 py-2">Reviews</a>
            </nav>

            <div class="pt-4 space-y-2 border-t border-slate-200 dark:border-zinc-800">
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full btn-primary text-center block text-xs">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="w-full btn-secondary text-center block text-xs">
                        Log In
                    </a>
                    <a href="{{ route('register') }}" class="w-full btn-primary text-center block text-xs">
                        Register Account
                    </a>
                @endauth
            </div>
        </div>

        <div class="text-[11px] text-slate-500 dark:text-zinc-500 text-center border-t border-slate-200 dark:border-zinc-800 pt-4">
            Hour Wash Laundry Shop
        </div>
    </div>

    <!-- Main Content Body Container -->
    <main class="flex-1 space-y-12 py-8 md:py-12 px-4 md:px-8 max-w-7xl mx-auto w-full">
        <x-popup-alert />

        <!-- Storefront Hero Section -->
        <section id="home" class="relative rounded-lg overflow-hidden app-card p-6 md:p-12">
            <div class="grid lg:grid-cols-12 gap-8 lg:gap-10 items-center">
                <div class="lg:col-span-7 space-y-5">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/40 dark:text-blue-400 dark:border-blue-800/60 text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-blue-600 dark:bg-blue-400"></span>
                        Laundry Management System
                    </div>

                    <h1 class="text-2xl sm:text-4xl md:text-5xl font-bold tracking-tight text-slate-900 dark:text-white leading-tight">
                        Professional Clean. <br>
                        <span class="text-blue-600 dark:text-blue-400">Fast & Real-Time Tracking</span>
                    </h1>

                    <p class="text-slate-600 dark:text-zinc-400 text-xs sm:text-sm max-w-xl leading-relaxed">
                        Magallanes St., Orosite, Legazpi City. Experience 1-hour fast turnarounds, QR code order verification, live machine monitoring, and doorstep pickup & delivery services.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                        <div class="flex items-center gap-3 p-3 rounded-md bg-slate-50 dark:bg-zinc-800/60 border border-slate-200 dark:border-zinc-800">
                            <div class="w-9 h-9 rounded-md bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400 flex items-center justify-center font-extrabold text-xs shrink-0 border border-blue-200 dark:border-blue-800/60">
                                1H
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 dark:text-zinc-100">1-Hour Cycle</h4>
                                <p class="text-[11px] text-slate-500 dark:text-zinc-400">Express washing</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-md bg-slate-50 dark:bg-zinc-800/60 border border-slate-200 dark:border-zinc-800">
                            <div class="w-9 h-9 rounded-md bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 flex items-center justify-center font-extrabold text-xs shrink-0 border border-emerald-200 dark:border-emerald-800/60">
                                QR
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 dark:text-zinc-100">QR Order Tag</h4>
                                <p class="text-[11px] text-slate-500 dark:text-zinc-400">Instant verification</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-md bg-slate-50 dark:bg-zinc-800/60 border border-slate-200 dark:border-zinc-800">
                            <div class="w-9 h-9 rounded-md bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 flex items-center justify-center font-extrabold text-xs shrink-0 border border-amber-200 dark:border-amber-800/60">
                                VIP
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 dark:text-zinc-100">Pickup & Delivery</h4>
                                <p class="text-[11px] text-slate-500 dark:text-zinc-400">Doorstep service</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <a href="{{ route('register') }}" class="w-full sm:w-auto btn-primary text-center">
                            Book a Pickup Order
                        </a>
                        <a href="#services" class="w-full sm:w-auto btn-secondary text-center">
                            Explore Services
                        </a>
                    </div>
                </div>

                <!-- Store Operational Status Widget -->
                <div class="lg:col-span-5 relative flex justify-center">
                    <div class="w-full max-w-sm rounded-lg bg-slate-50 dark:bg-zinc-800/60 border border-slate-200 dark:border-zinc-800 p-5 flex flex-col justify-between shadow-sm space-y-5">
                        <div class="flex items-center justify-between border-b border-slate-200 dark:border-zinc-700/60 pb-3">
                            <span class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Live Store Status</span>
                            @if(($storeStatus ?? 'open') === 'open')
                                <span class="badge-status badge-green">Open Today</span>
                            @else
                                <span class="badge-status badge-red">Closed Today</span>
                            @endif
                        </div>
                        <div class="space-y-3">
                            <div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white">HOUR WASH LAUNDRY</h3>
                                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">Operating Hours: 7:00 AM – 6:00 PM Daily</p>
                            </div>
                            @php
                                $idleWashers = $machines->filter(function($m) {
                                    return in_array($m->machine_type, ['washer', 'washer_dryer']) && $m->status === 'idle';
                                })->count();

                                $readyDryers = $machines->filter(function($m) {
                                    return in_array($m->machine_type, ['dryer', 'washer_dryer']) && $m->status === 'idle';
                                })->count();
                            @endphp
                            <div class="flex flex-wrap gap-2 pt-1">
                                <span class="badge-status badge-green">
                                    {{ $idleWashers }} {{ Str::plural('Washer', $idleWashers) }} Idle
                                </span>
                                <span class="badge-status badge-blue">
                                    {{ $readyDryers }} {{ Str::plural('Dryer', $readyDryers) }} Ready
                                </span>
                            </div>
                        </div>
                        <div class="text-[11px] text-slate-500 dark:text-zinc-400 border-t border-slate-200 dark:border-zinc-700/60 pt-3 flex justify-between">
                            <span>Magallanes St., Orosite</span>
                            <span class="text-blue-600 dark:text-blue-400 font-semibold">Legazpi City, Albay</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services & Rates Catalog -->
        <section id="services" class="space-y-5">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">Our Laundry Services & Rates</h2>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-zinc-400 mt-1">Select from our wide range of professional washing, drying, and folding packages.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3.5">
                @forelse($services as $service)
                    <div class="app-card p-5 flex flex-col justify-between space-y-4 hover:border-blue-500/40 transition">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $service->name }}</h3>
                                <span class="text-blue-600 dark:text-blue-400 font-bold text-base">₱{{ number_format($service->price, 2) }}</span>
                            </div>
                            <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed">{{ $service->description ?? 'Full wash, rinse, and dry cycle.' }}</p>
                        </div>
                        <div class="pt-3 border-t border-slate-200 dark:border-zinc-800 flex items-center justify-between text-xs">
                            <span class="text-slate-500 dark:text-zinc-400">Price Unit: <strong class="text-slate-800 dark:text-zinc-200">Per {{ $service->price_unit }}</strong></span>
                            @php
                                $mins = $service->estimated_minutes;
                                $hrs = floor($mins / 60);
                                $remMins = $mins % 60;
                                $durationFormatted = $hrs > 0 ? ($remMins > 0 ? "~{$hrs} hrs {$remMins} mins" : "~{$hrs} hrs") : "~{$mins} mins";
                            @endphp
                            <span class="text-blue-600 dark:text-blue-400 font-semibold">{{ $durationFormatted }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8 text-xs text-slate-500">Services catalog loading...</div>
                @endforelse
            </div>
        </section>

        <!-- How It Works 4-Step Guide -->
        <section id="how-it-works" class="space-y-5">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">How Hour Wash Works</h2>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-zinc-400 mt-1">Simple 4-step process from booking to clean, folded clothes.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="app-card p-5 space-y-3">
                    <div class="w-8 h-8 rounded-md bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400 border border-blue-200 dark:border-blue-800/60 flex items-center justify-center font-bold text-xs">
                        1
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">Book or Drop Off</h3>
                    <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed">
                        Book online or visit our store in Orosite, Legazpi City to select your preferred laundry package.
                    </p>
                </div>

                <div class="app-card p-5 space-y-3">
                    <div class="w-8 h-8 rounded-md bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400 border border-blue-200 dark:border-blue-800/60 flex items-center justify-center font-bold text-xs">
                        2
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">QR Tag Verification</h3>
                    <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed">
                        Your laundry receives a unique QR code tag for instant identification and automated tracking throughout the cycle.
                    </p>
                </div>

                <div class="app-card p-5 space-y-3">
                    <div class="w-8 h-8 rounded-md bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400 border border-blue-200 dark:border-blue-800/60 flex items-center justify-center font-bold text-xs">
                        3
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">Washing & Drying</h3>
                    <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed">
                        Our commercial washers and dryers clean and sanitize your load with premium detergents in under 1 hour.
                    </p>
                </div>

                <div class="app-card p-5 space-y-3">
                    <div class="w-8 h-8 rounded-md bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/60 flex items-center justify-center font-bold text-xs">
                        4
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">Pickup or Delivery</h3>
                    <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed">
                        Get SMS and system notifications when ready for in-store pickup or rider delivery right to your doorstep.
                    </p>
                </div>
            </div>
        </section>

        <!-- Public Order Tracker Section -->
        <section id="track-section" class="app-card p-6 md:p-8 space-y-5">
            <div class="max-w-xl mx-auto text-center space-y-1.5">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">Track Order Status</h2>
                <p class="text-xs text-slate-600 dark:text-zinc-400">Enter Your Order Number: #********</p>
            </div>

            <form onsubmit="event.preventDefault(); trackPublicOrder();" class="max-w-md mx-auto flex flex-col sm:flex-row gap-2.5">
                <input id="public-qr-input" type="text" placeholder="Enter order number: #********" class="flex-1" required>
                <button type="submit" class="btn-primary text-center">
                    Check Status
                </button>
            </form>

        </section>

        <!-- Customer Ratings & Reviews Section -->
        <section id="reviews-section" class="space-y-5">
            <div class="text-center space-y-1.5 max-w-xl mx-auto">
                <span class="badge-status badge-orange">
                    Customer Ratings & Reviews
                </span>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">
                    What Our Legazpi Customers Say
                </h2>
                <p class="text-xs text-slate-600 dark:text-zinc-400">
                    Real feedback and ratings from verified customers at Magallanes St., Orosite, Legazpi City.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($feedbacks ?? [] as $fb)
                    <div class="app-card p-5 space-y-3 hover:border-blue-500/40 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs">
                                    {{ strtoupper(substr($fb->user->name ?? 'C', 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900 dark:text-white">{{ $fb->user->name ?? 'Verified Customer' }}</h4>
                                    <span class="text-[10px] text-slate-500 dark:text-zinc-400">{{ $fb->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="text-amber-400 text-xs font-bold">
                                {{ str_repeat('⭐', $fb->rating) }}
                            </div>
                        </div>

                        <p class="text-xs text-slate-600 dark:text-zinc-300 leading-relaxed italic">
                            "{{ $fb->comment }}"
                        </p>
                    </div>
                @empty
                    <div class="col-span-full text-center py-6 text-xs text-slate-500">No customer reviews published yet.</div>
                @endforelse
            </div>
        </section>

        <script>
            function trackPublicOrder() {
                const val = document.getElementById('public-qr-input').value.trim();
                if (val) {
                    if (val.startsWith('http://') || val.startsWith('https://')) {
                        window.location.href = val;
                    } else {
                        window.location.href = '/laundry/track/' + encodeURIComponent(val);
                    }
                }
            }

            function filterWelcomeReviews() {
                const input = document.getElementById('welcome-review-search');
                if (!input) return;
                const query = input.value.toLowerCase().trim();
                const cards = document.querySelectorAll('.welcome-review-card');

                cards.forEach(card => {
                    const text = card.textContent.toLowerCase();
                    if (!query || text.includes(query)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }
        </script>
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-[#141417] border-t border-slate-200 dark:border-zinc-800 py-5 px-4 md:px-8 text-xs text-slate-500 dark:text-zinc-400">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-3">
            <div>© {{ date('Y') }} Hour Wash Laundry Management System</div>
            <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-1">
                <a href="{{ route('about') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">About Us</a>
                <span>•</span>
                <a href="{{ route('developers') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Developers</a>
                <span>•</span>
                <a href="{{ route('privacy') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Privacy Policy</a>
                <span>•</span>
                <a href="{{ route('terms') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Terms & Conditions</a>
                <span>•</span>
                <a href="https://maps.app.goo.gl/3yJAPrj4HQTZJPRb9" target="_blank" rel="noopener noreferrer" class="font-medium text-slate-700 dark:text-zinc-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Magallanes St., Orosite, Legazpi City</a>
            </div>
        </div>
    </footer>

    <!-- AI Assistant Floating Widget -->
    <button id="welcome-chat-toggle" class="fixed bottom-6 right-6 w-13 h-13 rounded-lg bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-all shadow-lg z-50 p-2 group" aria-label="Toggle AI Assistant Chat">
        <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-md bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-md h-3.5 w-3.5 bg-emerald-500 border-2 border-slate-900"></span>
        </span>
        <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-8 h-8 rounded-full object-cover group-hover:rotate-12 transition-transform bg-white p-0.5 border border-white/20 shadow-sm">
    </button>

    <div id="welcome-chat-window" class="fixed bottom-20 right-6 w-80 sm:w-96 bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-800 rounded-lg shadow-xl z-50 hidden flex-col overflow-hidden">
        <div class="p-3.5 bg-blue-600 text-white flex items-center justify-between">
            <div class="flex items-center gap-2 font-semibold text-xs sm:text-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                HourWash Assistant
            </div>
            <button id="welcome-chat-close" class="text-white/80 hover:text-white text-base">✕</button>
        </div>

        <div id="welcome-chat-box" class="p-4 h-72 overflow-y-auto space-y-3 text-xs bg-slate-50 dark:bg-[#09090B]">
            <div class="flex justify-start">
                <div class="bg-white dark:bg-zinc-800 text-slate-900 dark:text-zinc-100 px-3.5 py-2.5 rounded-lg max-w-[85%] border border-slate-200 dark:border-zinc-700 shadow-sm">
                    Hello! Welcome to Hour Wash Laundry Shop. How can I assist you today with your laundry orders, machine availability, or shop rates?
                </div>
            </div>
        </div>

        <div class="p-3 border-t border-slate-200 dark:border-zinc-800 bg-white dark:bg-[#141417] flex gap-2">
            <input id="welcome-message" type="text" placeholder="Ask about order status, services..." class="flex-1 bg-slate-50 dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 rounded-md px-3 py-1.5 text-xs focus:outline-none focus:border-blue-600 text-slate-900 dark:text-zinc-100" onkeydown="if(event.key==='Enter')welcomeSendMessage()">
            <button onclick="welcomeSendMessage()" class="btn-primary py-1.5 px-3 text-xs">
                Send
            </button>
        </div>
    </div>

    <!-- Page Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('welcome-mobile-toggle');
            const closeBtn = document.getElementById('welcome-mobile-close');
            const overlay = document.getElementById('welcome-mobile-overlay');
            const menu = document.getElementById('welcome-mobile-menu');
            const links = document.querySelectorAll('.mobile-nav-link');

            function openMenu() {
                menu.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
            }

            function closeMenu() {
                menu.classList.add('translate-x-full');
                if (overlay) overlay.classList.add('hidden');
            }

            if (toggleBtn) toggleBtn.addEventListener('click', openMenu);
            if (closeBtn) closeBtn.addEventListener('click', closeMenu);
            if (overlay) overlay.addEventListener('click', closeMenu);

            links.forEach(link => {
                link.addEventListener('click', closeMenu);
            });

            // Theme Toggle
            const themeToggle = document.getElementById('welcome-theme-toggle');
            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    }
                });
            }

            // Welcome Chatbot Toggle
            const chatToggle = document.getElementById('welcome-chat-toggle');
            const chatWindow = document.getElementById('welcome-chat-window');
            const chatClose = document.getElementById('welcome-chat-close');

            if (chatToggle && chatWindow) {
                chatToggle.addEventListener('click', function() {
                    chatWindow.classList.toggle('hidden');
                    chatWindow.classList.toggle('flex');
                });
            }
            if (chatClose && chatWindow) {
                chatClose.addEventListener('click', function() {
                    chatWindow.classList.add('hidden');
                    chatWindow.classList.remove('flex');
                });
            }
        });

        function welcomeSendMessage() {
            const input = document.getElementById('welcome-message');
            const message = input.value.trim();
            if (!message) return;

            const chatBox = document.getElementById('welcome-chat-box');
            chatBox.innerHTML += `
                <div class="flex justify-end">
                    <div class="bg-blue-600 text-white font-medium px-3.5 py-2.5 rounded-lg max-w-[85%] shadow-sm">
                        ${message}
                    </div>
                </div>
            `;
            input.value = "";
            chatBox.scrollTop = chatBox.scrollHeight;

            const typingId = 'typing-' + Date.now();
            chatBox.innerHTML += `
                <div class="flex justify-start" id="${typingId}">
                    <div class="bg-white dark:bg-zinc-800 text-slate-500 px-3.5 py-2.5 rounded-lg border border-slate-200 dark:border-zinc-700 shadow-sm">
                        <span class="animate-pulse">Typing...</span>
                    </div>
                </div>
            `;
            chatBox.scrollTop = chatBox.scrollHeight;

            fetch('/chatbot', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ message: message })
            })
            .then(res => res.json())
            .then(data => {
                const typingEl = document.getElementById(typingId);
                if (typingEl) typingEl.remove();

                const formattedReply = (data.reply || '').replace(/\n/g, '<br>');

                chatBox.innerHTML += `
                    <div class="flex justify-start">
                        <div class="bg-white dark:bg-zinc-800 text-slate-900 dark:text-zinc-100 px-3.5 py-2.5 rounded-lg max-w-[85%] border border-slate-200 dark:border-zinc-700 shadow-sm break-words">
                            <strong class="text-blue-600 dark:text-blue-400 block mb-0.5 font-semibold">HourWash Assistant</strong>
                            ${formattedReply}
                        </div>
                    </div>
                `;
                chatBox.scrollTop = chatBox.scrollHeight;
            })
            .catch(err => {
                const typingEl = document.getElementById(typingId);
                if (typingEl) typingEl.remove();

                chatBox.innerHTML += `
                    <div class="flex justify-start">
                        <div class="bg-rose-50 text-rose-600 border border-rose-200 px-3 py-2 rounded-md">
                            Could not reach assistant. Please try again.
                        </div>
                    </div>
                `;
            });
        }
    </script>
</body>
</html>
