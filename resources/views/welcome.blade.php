<!DOCTYPE html>
<html lang="en" class="dark scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hour Wash Laundry Shop | Self-Service & Drop-off System</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    <!-- Font & Network Optimizations -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//unpkg.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F5F5F7] text-slate-900 dark:bg-[#000000] dark:text-[#F5F5F7] font-sans antialiased selection:bg-[#007AFF] selection:text-white min-h-screen flex flex-col transition-colors duration-300">

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-50 bg-white/95 dark:bg-[#1C1C1E]/95 border-b border-black/10 dark:border-white/10 px-4 md:px-10 py-4 shadow-sm backdrop-blur-xl">
        <div class="max-w-7xl mx-auto flex items-center justify-between">

            <!-- Brand Logo -->
            <a href="{{ route('welcome') }}" class="flex items-center gap-3 group flex-shrink-0">
                <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-11 h-11 rounded-full object-cover shadow-md group-hover:scale-105 transition-transform bg-white p-0.5 border border-black/10 dark:border-white/10">
                <div>
                    <span class="text-xl font-bold font-['Outfit'] tracking-wide text-slate-900 dark:text-white block">
                        HOUR WASH
                    </span>
                    <span class="text-[10px] text-[#007AFF] dark:text-[#0A84FF] tracking-widest uppercase font-semibold block">LAUNDRY MANAGEMENT SYSTEM</span>
                </div>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="hidden xl:flex items-center gap-8 text-sm font-semibold text-slate-600 dark:text-slate-300">
                <a href="#home" class="hover:text-[#007AFF] dark:hover:text-[#0A84FF] transition-colors">Home</a>
                <a href="#services" class="hover:text-[#007AFF] dark:hover:text-[#0A84FF] transition-colors">Services & Pricing</a>
                <a href="#how-it-works" class="hover:text-[#007AFF] dark:hover:text-[#0A84FF] transition-colors">How It Works</a>
                <a href="#machines" class="hover:text-[#007AFF] dark:hover:text-[#0A84FF] transition-colors">Machines</a>
                <a href="#reviews-section" class="hover:text-[#007AFF] dark:hover:text-[#0A84FF] transition-colors">Customer Reviews</a>
                <a href="#track-section" class="hover:text-[#007AFF] dark:hover:text-[#0A84FF] transition-colors">QR Code Tracker</a>
            </nav>

            <!-- Action Buttons & Theme Switcher -->
            <div class="flex items-center gap-3 flex-shrink-0">
                <button id="welcome-theme-toggle" class="p-2 px-3 rounded-xl bg-slate-100 dark:bg-white/10 text-slate-900 dark:text-white border border-black/10 dark:border-white/10 hover:scale-105 transition-all text-xs font-semibold flex items-center gap-1.5 shadow-sm" title="Toggle Light/Dark Theme">
                    <span class="dark:hidden flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Light
                    </span>
                    <span class="hidden dark:flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        Dark
                    </span>
                </button>

                @auth
                    <a href="{{ route('dashboard') }}" class="btn-ios-primary hidden sm:inline-block">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-ios-secondary hidden sm:inline-block text-xs">
                        Log In
                    </a>
                    <a href="{{ route('register') }}" class="btn-ios-primary hidden sm:inline-block text-xs">
                        Register
                    </a>
                @endauth

                <!-- Mobile Hamburger Button -->
                <button id="welcome-mobile-toggle" class="xl:hidden p-2 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/10 focus:outline-none" aria-label="Toggle Mobile Navigation">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Navigation Drawer Overlay & Panel -->
    <div id="welcome-mobile-overlay" class="fixed inset-0 bg-black/60 dark:bg-black/80 z-50 hidden xl:hidden transition-opacity"></div>

    <div id="welcome-mobile-menu" class="fixed top-0 right-0 bottom-0 w-72 bg-white dark:bg-[#1C1C1E] border-l border-black/10 dark:border-white/10 z-50 transform translate-x-full xl:hidden transition-transform duration-300 flex flex-col justify-between p-6 shadow-2xl">
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b border-black/10 dark:border-white/10 pb-4">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-8 h-8 rounded-lg object-cover shadow-sm bg-white p-0.5 border border-black/10 dark:border-white/10">
                    <span class="font-bold text-slate-900 dark:text-white font-['Outfit']">Hour Wash Menu</span>
                </div>
                <button id="welcome-mobile-close" class="text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <nav class="flex flex-col space-y-3 font-medium text-sm">
                <a href="#home" class="mobile-nav-link text-[#007AFF] dark:text-[#0A84FF] font-bold py-2 border-b border-black/5 dark:border-white/5">Home</a>
                <a href="#services" class="mobile-nav-link text-slate-700 dark:text-slate-200 hover:text-[#007AFF] py-2 border-b border-black/5 dark:border-white/5">Services & Pricing</a>
                <a href="#how-it-works" class="mobile-nav-link text-slate-700 dark:text-slate-200 hover:text-[#007AFF] py-2 border-b border-black/5 dark:border-white/5">How It Works</a>
                <a href="#machines" class="mobile-nav-link text-slate-700 dark:text-slate-200 hover:text-[#007AFF] py-2 border-b border-black/5 dark:border-white/5">Machine Monitor</a>
                <a href="#track-section" class="mobile-nav-link text-slate-700 dark:text-slate-200 hover:text-[#007AFF] py-2">QR Order Tracker</a>
            </nav>

            <div class="pt-4 space-y-2 border-t border-black/10 dark:border-white/10">
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full btn-ios-primary text-center block">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="w-full btn-ios-secondary text-center block">
                        Log In
                    </a>
                    <a href="{{ route('register') }}" class="w-full btn-ios-primary text-center block">
                        Register Account
                    </a>
                @endauth
            </div>
        </div>

        <div class="text-[11px] text-slate-500 dark:text-slate-400 text-center border-t border-black/10 dark:border-white/10 pt-4">
            Hour Wash Laundry Shop • Legazpi City
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1 space-y-16 py-8 md:py-12 px-4 md:px-10 max-w-7xl mx-auto w-full">
        <x-popup-alert />

        <!-- Hero Banner Section -->
        <section id="home" class="relative rounded-[16px] overflow-hidden app-card p-6 md:p-14">
            <div class="grid lg:grid-cols-12 gap-8 lg:gap-10 items-center">
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#007AFF]/10 dark:bg-[#0A84FF]/20 border border-[#007AFF]/20 text-[#007AFF] dark:text-[#0A84FF] text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-[#007AFF] dark:bg-[#0A84FF] animate-pulse"></span>
                        Self-Service & Drop-off Laundry System
                    </div>
                    
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight font-['Outfit'] text-slate-900 dark:text-white leading-tight">
                        Professional Clean. <br>
                        <span class="text-[#007AFF] dark:text-[#0A84FF]">Fast & Real-Time Tracking </span>
                    </h1>

                    <p class="text-slate-600 dark:text-slate-300 text-sm md:text-base max-w-xl leading-relaxed">
                        Magallanes St., Orosite, Legazpi City. Experience 1-hour fast turnarounds, QR code order verification, live machine monitoring, and pickup & delivery services.
                    </p>

                    <!-- Feature Badges -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 pt-2">
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/5 dark:border-white/10">
                            <div class="w-10 h-10 rounded-lg bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center font-bold text-lg flex-shrink-0">
                                ⏱
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 dark:text-slate-100">1-Hour Cycle</h4>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">Express washing</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/5 dark:border-white/10">
                            <div class="w-10 h-10 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-lg flex-shrink-0">
                                🏷
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 dark:text-slate-100">QR Order Tag</h4>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">Instant verification</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/5 dark:border-white/10">
                            <div class="w-10 h-10 rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-lg flex-shrink-0">
                                🛵
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 dark:text-slate-100">Pickup & Delivery</h4>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">Doorstep service</p>
                            </div>
                        </div>
                    </div>

                    <!-- CTAs -->
                    <div class="flex flex-wrap items-center gap-3 sm:gap-4 pt-2">
                        <a href="{{ route('register') }}" class="w-full sm:w-auto btn-ios-primary text-center">
                            Book a Pickup Order
                        </a>
                        <a href="#services" class="w-full sm:w-auto btn-ios-secondary text-center">
                            Explore Services
                        </a>
                    </div>
                </div>

                <!-- Hero Graphic Container -->
                <div class="lg:col-span-5 relative flex justify-center">
                    <div class="w-full max-w-sm rounded-2xl bg-white/90 dark:bg-[#2C2C2E]/90 border border-black/5 dark:border-white/10 p-6 flex flex-col justify-between shadow-lg dark:shadow-xl space-y-6">
                        <div class="flex items-center justify-between border-b border-black/10 dark:border-white/10 pb-3">
                            <span class="text-xs font-bold text-[#007AFF] dark:text-[#0A84FF] uppercase tracking-widest">Status</span>
                            <span class="w-2.5 h-2.5 rounded-full bg-[#007AFF] dark:bg-[#0A84FF] animate-pulse"></span>
                        </div>
                        <div class="space-y-3">
                            <div class="text-2xl font-bold font-['Outfit'] text-slate-900 dark:text-white">HOUR WASH LAUNDRY SHOP</div>
                            <p class="text-xs text-slate-600 dark:text-slate-400">Store Hours: 7:00 AM – 6:00 PM Daily</p>
                            <div class="flex flex-wrap gap-2 pt-1">
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 text-[11px] font-bold">12 Washers Idle</span>
                                <span class="px-2.5 py-1 rounded-lg bg-sky-500/15 text-sky-700 dark:text-sky-300 text-[11px] font-bold">8 Dryers Ready</span>
                            </div>
                        </div>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400 border-t border-black/10 dark:border-white/10 pt-3 flex justify-between">
                            <span>Magallanes St., Orosite</span>
                            <span class="text-[#007AFF] dark:text-[#0A84FF] font-semibold">Legazpi City</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services & Pricing Section -->
        <section id="services" class="space-y-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white font-['Outfit']">Our Laundry Services & Rates</h2>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">Select from our wide range of professional washing, drying, and folding packages.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @forelse($services as $service)
                    <div class="app-card p-6 flex flex-col justify-between space-y-4 hover:border-[#007AFF]/40 transition">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $service->name }}</h3>
                                <span class="text-[#007AFF] dark:text-[#0A84FF] font-bold text-lg">₱{{ number_format($service->price, 2) }}</span>
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">{{ $service->description ?? 'Full wash, rinse, and dry cycle.' }}</p>
                        </div>
                        <div class="pt-3 border-t border-black/10 dark:border-white/10 flex items-center justify-between text-xs">
                            <span class="text-slate-500 dark:text-slate-400">Price Unit: <strong class="text-slate-800 dark:text-slate-200">Per {{ $service->price_unit }}</strong></span>
                            <span class="text-[#007AFF] dark:text-[#0A84FF] font-semibold">~{{ $service->estimated_minutes }} mins</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8 text-slate-500">Services catalog loading...</div>
                @endforelse
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="space-y-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white font-['Outfit']">How Hour Wash Works</h2>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">Simple 4-step process from drop-off to clean, folded clothes.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <!-- Step 1 -->
                <div class="app-card p-6 space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-[#007AFF]/10 text-[#007AFF] dark:text-[#0A84FF] border border-[#007AFF]/20 flex items-center justify-center font-bold text-lg font-['Outfit']">
                        1
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Book or Drop Off</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        Book online or visit our store kiosk in Orosite, Legazpi City to select your preferred laundry package.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="app-card p-6 space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20 flex items-center justify-center font-bold text-lg font-['Outfit']">
                        2
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">QR Tag Verification</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        Your laundry receives a unique QR code tag for instant identification and automated tracking throughout the cycle.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="app-card p-6 space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20 flex items-center justify-center font-bold text-lg font-['Outfit']">
                        3
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Washing & Drying</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        Our commercial washers and dryers clean and sanitize your load with premium detergents in under 1 hour.
                    </p>
                </div>

                <!-- Step 4 -->
                <div class="app-card p-6 space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 flex items-center justify-center font-bold text-lg font-['Outfit']">
                        4
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Pickup or Delivery</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        Get SMS/system notifications when ready for in-store pickup or rider delivery right to your doorstep.
                    </p>
                </div>
            </div>
        </section>

        <!-- Machine Status Section -->
        <section id="machines" class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white font-['Outfit']">Live Machine Monitor</h2>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">Real-time availability of washers and dryers at Hour Wash main store.</p>
                </div>
                <div class="flex items-center gap-2 text-xs text-[#007AFF] dark:text-[#0A84FF] font-semibold">
                    <span class="w-2 h-2 rounded-full bg-[#007AFF] dark:bg-[#0A84FF] animate-pulse"></span>
                    Live Status Updates
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse($machines as $machine)
                    <div class="app-card p-4 space-y-3 hover:border-[#007AFF]/40 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $machine->machine_name }}</span>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">{{ $machine->machine_code }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-600 dark:text-slate-300 capitalize">{{ str_replace('_', ' ', $machine->machine_type) }}</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                @if($machine->status === 'idle') bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30
                                @elseif(in_array($machine->status, ['washing', 'rinsing', 'drying'])) bg-[#007AFF]/15 text-[#007AFF] dark:text-[#0A84FF] border border-[#007AFF]/30
                                @elseif($machine->status === 'maintenance') bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30
                                @else bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 @endif">
                                {{ ucfirst($machine->status) }}
                            </span>
                        </div>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400 pt-1 border-t border-black/10 dark:border-white/10">
                            @if(in_array($machine->status, ['washing', 'rinsing', 'drying']))
                                <div class="flex items-center justify-between font-semibold text-slate-800 dark:text-slate-200">
                                    <span>⏱ {{ $machine->remaining_minutes ?? 30 }} mins remaining</span>
                                    @if($machine->currentOrder)
                                        <a href="{{ route('laundry.track', $machine->currentOrder->order_number) }}" class="text-[#007AFF] dark:text-[#0A84FF] font-mono text-[10px] font-bold hover:underline" title="View Order Status">
                                            #{{ $machine->currentOrder->order_number }}
                                        </a>
                                    @endif
                                </div>
                            @elseif($machine->status === 'maintenance')
                                <div class="text-amber-600 dark:text-amber-400 font-semibold flex items-center gap-1">
                                    ⚠ Under Maintenance
                                </div>
                            @elseif($machine->status === 'offline')
                                <div class="text-rose-600 dark:text-rose-400 font-semibold flex items-center gap-1">
                                    🚫 Out of Service
                                </div>
                            @else
                                <div class="text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1">
                                    ✓ Ready for next load
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-6 text-xs text-slate-500">Machine status loading...</div>
                @endforelse
            </div>
        </section>

        <!-- Simplified QR Code Feature Highlight -->
        <section class="app-card p-6 md:p-10 relative overflow-hidden bg-gradient-to-br from-[#007AFF]/5 via-transparent to-emerald-500/5 border border-[#007AFF]/20 space-y-6">
            <div class="grid lg:grid-cols-12 gap-8 items-center">
                
                <div class="lg:col-span-7 space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#007AFF]/15 text-[#007AFF] dark:text-[#0A84FF] text-xs font-bold uppercase tracking-wider">
                         QR Tagging
                    </div>
                    
                    <h2 class="text-2xl sm:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white leading-tight">
                        How Our QR Code Tagging Keeps Your Laundry 100% Safe
                    </h2>
                    
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                        No complex apps or accounts needed! Every laundry bag receives a unique QR Code tag when dropped off. Here is how simple it is:
                    </p>

                    <div class="space-y-3 pt-2">
                        <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/5 dark:border-white/10">
                            <div class="w-7 h-7 rounded-lg bg-[#007AFF] text-white flex items-center justify-center font-bold text-xs flex-shrink-0">1</div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 dark:text-white">Bag Tagged at Store</h4>
                                <p class="text-[11px] text-slate-600 dark:text-slate-400">Staff attaches a waterproof QR tag with your unique Order ID to your laundry bag.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/5 dark:border-white/10">
                            <div class="w-7 h-7 rounded-lg bg-sky-500 text-white flex items-center justify-center font-bold text-xs flex-shrink-0">2</div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 dark:text-white">Scan with Any Phone Camera</h4>
                                <p class="text-[11px] text-slate-600 dark:text-slate-400">Point your smartphone camera at the QR code tag — no login or password required!</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/5 dark:border-white/10">
                            <div class="w-7 h-7 rounded-lg bg-emerald-500 text-white flex items-center justify-center font-bold text-xs flex-shrink-0">3</div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 dark:text-white">See Live Cleaning Progress</h4>
                                <p class="text-[11px] text-slate-600 dark:text-slate-400">Instantly see whether your clothes are currently Washing, Rinsing, Drying, or Ready for Pickup.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sample QR Code Card Illustration -->
                <div class="lg:col-span-5 flex justify-center">
                    <div class="w-full max-w-xs app-card p-6 text-center space-y-4 shadow-xl border border-[#007AFF]/30 relative">
                        <div class="px-3 py-1 rounded-md bg-[#007AFF]/10 text-[#007AFF] dark:text-[#0A84FF] text-[10px] font-bold uppercase tracking-wider">
                            SAMPLE QR LAUNDRY TAG
                        </div>

                        <!-- Real Scannable QR Code Image -->
                        <div class="w-36 h-36 mx-auto bg-white p-2 rounded-2xl shadow-md border border-slate-200 flex items-center justify-center relative group">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=HW884210" 
                                 alt="Real Scannable QR Tag #HW884210" 
                                 class="w-full h-full rounded-xl">
                            <div class="absolute inset-0 bg-[#007AFF]/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                <span class="bg-[#007AFF] text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow">Scan with Camera!</span>
                            </div>
                        </div>

                        <div>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-widest block">ORDER CODE</span>
                            <h3 class="text-lg font-bold font-mono text-[#007AFF] dark:text-[#0A84FF]">#HW884210</h3>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- QR Track Section -->
        <section id="track-section" class="app-card p-6 md:p-8 space-y-6">
            <div class="max-w-xl mx-auto text-center space-y-2">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white font-['Outfit']">Track Order Status by QR Code</h2>
                <p class="text-xs text-slate-600 dark:text-slate-400">Enter your order QR token or scan with your device camera without logging in.</p>
            </div>

            <form onsubmit="event.preventDefault(); trackPublicOrder();" class="max-w-md mx-auto flex flex-col sm:flex-row gap-3">
                <input id="public-qr-input" type="text" placeholder="Enter QR Token e.g. cf3e0ce1... or HW884210..." class="flex-1" required>
                <button type="submit" class="btn-ios-primary text-center">
                    Check Status
                </button>
            </form>

            <div class="flex justify-center gap-3 pt-1">
                <button onclick="openCameraScanner()" class="btn-ios-secondary text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#007AFF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span> Open Camera Scanner</span>
                </button>
            </div>
        </section>

        <!-- Customer Ratings & Reviews Section -->
        <section id="reviews-section" class="space-y-6">
            <div class="text-center space-y-2 max-w-xl mx-auto">
                <span class="px-3 py-1 rounded-full bg-amber-500/15 text-amber-600 dark:text-amber-400 text-xs font-bold uppercase tracking-wider">
                     Customer Ratings & Reviews
                </span>
                <h2 class="text-2xl sm:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">
                    What Our Legazpi Customers Say
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                    Real feedback and ratings from customers at Magallanes St., Orosite, Legazpi City.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($feedbacks ?? [] as $fb)
                    <div class="app-card p-5 space-y-3 relative hover:border-amber-500/30 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-[#007AFF] text-white flex items-center justify-center font-bold text-xs">
                                    {{ strtoupper(substr($fb->user->name ?? 'C', 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900 dark:text-white">{{ $fb->user->name ?? 'Verified Customer' }}</h4>
                                    <span class="text-[10px] text-slate-500 dark:text-slate-400">{{ $fb->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="text-amber-500 text-xs font-bold">
                                {{ str_repeat('⭐', $fb->rating) }}
                            </div>
                        </div>

                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed italic">
                            "{{ $fb->comment }}"
                        </p>
                    </div>
                @empty
                    <div class="col-span-full text-center py-6 text-xs text-slate-500">No customer reviews published yet.</div>
                @endforelse
            </div>
        </section>

        <!-- Camera Scanner Modal -->
        <div id="camera-scanner-modal" class="fixed inset-0 bg-black/80 backdrop-blur-md z-50 hidden flex-col items-center justify-center p-4 animate-fade-in">
            <div class="app-card max-w-sm w-full p-6 space-y-4 text-center">
                <div class="flex items-center justify-between border-b border-black/10 dark:border-white/10 pb-3">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#007AFF] dark:text-[#0A84FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><circle cx="12" cy="13" r="3"/></svg>
                        Real-Time Camera QR Scanner
                    </h3>
                    <button onclick="closeCameraScanner()" class="text-slate-500 hover:text-slate-900 dark:hover:text-white font-bold text-base">✕</button>
                </div>
                
                <div id="qr-reader" class="w-full h-64 bg-black rounded-xl overflow-hidden relative flex items-center justify-center border border-black/10 dark:border-white/10 shadow-inner"></div>

                <p class="text-[11px] text-slate-500 dark:text-slate-400">Point your phone camera at any HourWash QR Code tag to decode instantly.</p>
                <button onclick="closeCameraScanner()" class="btn-ios-secondary text-xs w-full">Cancel</button>
            </div>
        </div>

        <style>
            #qr-reader video {
                width: 100% !important;
                height: 100% !important;
                object-fit: cover !important;
                border-radius: 12px;
            }
            #qr-reader__scan_region {
                background: transparent !important;
            }
        </style>

        <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
        <script>
        let html5QrCodeScanner = null;

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

        async function openCameraScanner() {
            const modal = document.getElementById('camera-scanner-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            if (!html5QrCodeScanner) {
                html5QrCodeScanner = new Html5Qrcode("qr-reader");
            }

            const qrCodeSuccessCallback = (decodedText) => {
                closeCameraScanner();
                if (decodedText) {
                    let cleaned = decodedText.trim();
                    if (cleaned.startsWith('http://') || cleaned.startsWith('https://')) {
                        window.location.href = cleaned;
                    } else {
                        window.location.href = '/laundry/track/' + encodeURIComponent(cleaned);
                    }
                }
            };

            const config = { fps: 15, qrbox: { width: 220, height: 220 } };

            try {
                const devices = await Html5Qrcode.getCameras();
                if (devices && devices.length > 0) {
                    // Select rear/back camera on mobile devices
                    const backCam = devices.find(d => 
                        d.label.toLowerCase().includes('back') || 
                        d.label.toLowerCase().includes('rear') || 
                        d.label.toLowerCase().includes('environment')
                    ) || devices[devices.length - 1];

                    await html5QrCodeScanner.start(backCam.id, config, qrCodeSuccessCallback);
                } else {
                    await html5QrCodeScanner.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);
                }
            } catch (err) {
                console.warn("Direct camera selection failed, falling back to facingMode constraint:", err);
                try {
                    await html5QrCodeScanner.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);
                } catch (fallbackErr) {
                    console.error("Camera scanner fallback error:", fallbackErr);
                    alert("Camera Access Required: Please allow camera permissions in your browser settings to scan QR tags.");
                    closeCameraScanner();
                }
            }
        }

        async function closeCameraScanner() {
            const modal = document.getElementById('camera-scanner-modal');
            if (html5QrCodeScanner && html5QrCodeScanner.isScanning) {
                try {
                    await html5QrCodeScanner.stop();
                } catch (e) {}
            }
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        </script>

    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-[#1C1C1E] border-t border-black/10 dark:border-white/10 py-6 px-4 md:px-10 text-center text-xs text-slate-600 dark:text-slate-400 flex flex-col sm:flex-row justify-between items-center max-w-7xl mx-auto w-full gap-4">
        <div>© {{ date('Y') }} Hour Wash Laundry Shop System. Legazpi City</div>
        <div class="flex items-center gap-4">
            <span>Magallanes St., Orosite, Legazpi City</span>
        </div>
    </footer>

    <!-- Mobile Drawer & Theme Toggle Script -->
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

        // Theme Toggle Button
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
    });
    </script>
</body>
</html>