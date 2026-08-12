<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Hour Wash Laundry System') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    <!-- Google Fonts & Theme Pre-init script -->
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

<body class="bg-[#F2F2F7] dark:bg-[#000000] text-slate-900 dark:text-[#F5F5F7] font-['Inter'] antialiased selection:bg-[#007AFF] selection:text-white min-h-screen">

    <div class="flex min-h-screen relative overflow-x-hidden">

        <!-- Mobile Drawer Overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 dark:bg-black/80 z-40 hidden md:hidden transition-opacity"></div>

        <!-- Sidebar Navigation -->
        <aside id="sidebar" class="fixed top-0 bottom-0 left-0 h-screen w-64 bg-white dark:bg-[#1C1C1E] border-r border-black/10 dark:border-white/10 z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 flex flex-col justify-between shadow-xl">
            
            <div class="flex flex-col flex-1 min-h-0 overflow-y-auto">
                <!-- Brand Header -->
                <div class="p-5 border-b border-black/10 dark:border-white/10 flex items-center justify-between flex-shrink-0">
                    <a href="{{ route('welcome') }}" class="flex items-center gap-3 group">
                        <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-11 h-11 rounded-full object-cover shadow-md group-hover:scale-105 transition-transform bg-white p-0.5 border border-black/10 dark:border-white/10">
                        <div>
                            <h1 class="text-lg font-bold font-['Outfit'] tracking-wide text-slate-900 dark:text-white">
                                HOUR WASH
                            </h1>
                            <p class="text-[10px] text-[#007AFF] dark:text-[#0A84FF] tracking-widest uppercase font-semibold">LAUNDRY MANAGEMENT SYSTEM</p>
                        </div>
                    </a>
                    <button id="close-sidebar" class="md:hidden text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-1.5 text-sm font-medium">
                    @auth
                        @if(auth()->user()->isOwner())
                            <!-- Owner / Admin Links -->
                            <div class="px-3 py-2 text-[11px] font-extrabold text-slate-900 dark:text-slate-200 uppercase tracking-wider">Management Workstation</div>
                            
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#007AFF] text-white font-bold shadow-md' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/10' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                <span>Overview Dashboard</span>
                            </a>
                            
                            <a href="{{ route('admin.laundry.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.laundry.*') ? 'bg-[#007AFF] text-white font-bold shadow-md' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/10' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                <span>Store Orders Queue</span>
                            </a>
                            
                            <a href="{{ route('admin.machines.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.machines.*') ? 'bg-[#007AFF] text-white font-bold shadow-md' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/10' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                                <span>Machine Fleet Monitor</span>
                            </a>
                            
                            <a href="{{ route('admin.services.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.services.*') ? 'bg-[#007AFF] text-white font-bold shadow-md' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/10' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                <span>Services & Pricing</span>
                            </a>
                            
                            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.users.*') ? 'bg-[#007AFF] text-white font-bold shadow-md' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/10' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <span>Staff & Customers</span>
                            </a>
                            
                            <a href="{{ route('admin.analytics') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.analytics') ? 'bg-[#007AFF] text-white font-bold shadow-md' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/10' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                <span>Analytics & Reports</span>
                            </a>

                            <a href="{{ route('admin.inventory.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.inventory.*') ? 'bg-[#007AFF] text-white font-bold shadow-md' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/10' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                <span>Store Inventory</span>
                            </a>
                        @elseif(auth()->user()->isStaff())
                            <!-- Staff Links -->
                            <div class="px-3 py-2 text-[11px] font-extrabold text-slate-900 dark:text-slate-200 uppercase tracking-wider">Staff Terminal</div>
                            
                            <a href="{{ route('staff.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('staff.dashboard') ? 'bg-[#007AFF] text-white font-bold shadow-md' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/10' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                <span>Workstation Dashboard</span>
                            </a>
                            
                            <a href="{{ route('admin.laundry.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/10">
                                <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <span>Manage Laundry Orders</span>
                            </a>
                        @else
                            <!-- Customer Links -->
                            <div class="px-3 py-2 text-[11px] font-extrabold text-slate-900 dark:text-slate-200 uppercase tracking-wider">Customer Hub</div>
                            
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-[#007AFF] text-white font-bold shadow-md' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/10' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                <span>Customer Dashboard</span>
                            </a>
                            
                            <a href="{{ route('laundry.create') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('laundry.create') ? 'bg-[#007AFF] text-white font-bold shadow-md' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/10' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Book New Order</span>
                            </a>
                            
                            <a href="{{ route('my.orders') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('my.orders') ? 'bg-[#007AFF] text-white font-bold shadow-md' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/10' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>My Order History</span>
                            </a>
                        @endif

                        <div class="pt-4 border-t border-black/10 dark:border-white/10 my-2"></div>
                        <a href="{{ route('welcome') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/10 font-medium transition-all">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                            <span>Public Storefront</span>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/10 font-medium transition-all">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>Account Settings</span>
                        </a>
                    @endauth
                </nav>
            </div>

            <!-- Profile Footer Badge in Sidebar -->
            @auth
            <div class="p-4 border-t border-black/10 dark:border-white/10 bg-slate-50 dark:bg-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#007AFF] dark:bg-[#0A84FF] text-white flex items-center justify-center font-bold shadow-md">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 overflow-hidden">
                        <h4 class="text-sm font-bold truncate text-slate-900 dark:text-white">{{ auth()->user()->name }}</h4>
                        <p class="text-[11px] text-[#007AFF] dark:text-[#0A84FF] capitalize flex items-center gap-1 font-extrabold">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#007AFF] dark:bg-[#0A84FF] inline-block animate-pulse"></span>
                            {{ auth()->user()->role }}
                        </p>
                    </div>
                </div>
            </div>
            @endauth
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 md:pl-64">
            
            <!-- Top Header Navbar -->
            <header class="bg-white dark:bg-[#1C1C1E] border-b border-black/10 dark:border-white/10 px-4 md:px-8 py-3.5 flex items-center justify-between sticky top-0 z-30 shadow-sm backdrop-blur-xl">
                <div class="flex items-center gap-3">
                    <button id="open-sidebar" class="md:hidden p-2 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/10 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <!-- Search Bar (Desktop & Mobile) -->
                    <form action="{{ route('global.search') }}" method="GET" class="hidden sm:flex items-center relative w-64 md:w-80">
                        <button type="submit" aria-label="Submit search" class="absolute left-3.5 text-slate-500 dark:text-slate-400 hover:text-[#007AFF] focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search orders, machines, users..." class="w-full pl-10 pr-4 py-2 bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/15 rounded-xl text-xs text-slate-900 dark:text-[#F5F5F7] placeholder-slate-500 focus:outline-none focus:border-[#007AFF] transition" required>
                    </form>
                </div>

                <div class="flex items-center gap-2 sm:gap-4">
                    <!-- Light / Dark Theme Switcher Button -->
                    <button id="theme-toggle" class="p-2 px-3 rounded-xl bg-slate-100 dark:bg-white/10 text-slate-900 dark:text-[#F5F5F7] border border-black/10 dark:border-white/10 hover:scale-105 transition-all text-xs font-semibold flex items-center gap-1.5 shadow-sm" title="Toggle Light/Dark Theme">
                        <span class="dark:hidden flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            Light
                        </span>
                        <span class="hidden dark:flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                            Dark
                        </span>
                    </button>

                    <!-- Live Date Display -->
                    <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-white/10 border border-black/10 dark:border-white/10 text-xs text-slate-800 dark:text-slate-200 font-semibold">
                        <svg class="w-4 h-4 text-[#007AFF] dark:text-[#0A84FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ now()->format('M d, Y') }}</span>
                    </div>

                    <!-- Logout Button -->
                    @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 rounded-xl text-rose-500 hover:bg-rose-500/10 transition" title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                    @endauth
                </div>
            </header>

            <!-- Page Main Content -->
            <main class="flex-1 p-4 md:p-8 max-w-7xl w-full mx-auto">
                <x-popup-alert />
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="border-t border-black/10 dark:border-white/10 py-4 px-6 text-center text-xs text-slate-600 dark:text-slate-400 flex flex-col sm:flex-row justify-between items-center gap-2 bg-white dark:bg-[#1C1C1E]">
                <div>© {{ date('Y') }} Hour Wash Laundry Management System</div>
                <div class="flex items-center gap-4 text-slate-600 dark:text-slate-400">
                    <span class="text-[#007AFF] dark:text-[#0A84FF] font-semibold">Magallanes St., Orosite, Legazpi City</span>
                </div>
            </footer>

        </div>
    </div>

    <!-- Floating Assistant Button & Drawer -->
    <button id="chat-toggle" class="fixed bottom-6 right-6 w-14 h-14 rounded-2xl bg-[#007AFF] dark:bg-[#0A84FF] text-white chat-bubble-glow flex items-center justify-center hover:scale-110 active:scale-95 transition-transform z-50 group" aria-label="Toggle AI Assistant Chat">
        <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-500 border-2 border-slate-900"></span>
        </span>
        <svg class="w-7 h-7 stroke-[2.5] group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
    </button>

    <div id="chat-window" class="fixed bottom-24 right-6 w-80 sm:w-96 bg-white dark:bg-[#1C1C1E] border border-black/10 dark:border-white/15 rounded-2xl shadow-2xl z-50 hidden flex-col overflow-hidden backdrop-blur-xl">
        <div class="p-4 bg-[#007AFF] dark:bg-[#0A84FF] text-white flex items-center justify-between">
            <div class="flex items-center gap-2 font-bold text-sm">
                <span class="w-2.5 h-2.5 rounded-full bg-white animate-pulse"></span>
                HourWash Virtual Assistant
            </div>
            <button id="chat-close" class="text-white/80 hover:text-white text-lg">✕</button>
        </div>

        <div id="chat-box" class="p-4 h-72 overflow-y-auto space-y-3 text-xs bg-slate-50 dark:bg-[#000000]">
            <div class="flex justify-start">
                <div class="bg-white dark:bg-[#2C2C2E] text-slate-900 dark:text-[#F5F5F7] px-3.5 py-2.5 rounded-2xl rounded-bl-none max-w-[85%] border border-black/10 dark:border-white/10 shadow-sm">
                    Hello! How can I assist you with your laundry orders today? 🧺
                </div>
            </div>
        </div>

        <div class="p-3 border-t border-black/10 dark:border-white/10 bg-white dark:bg-[#1C1C1E] flex gap-2">
            <input id="message" type="text" placeholder="Ask about order status, services..." class="flex-1 bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-[#007AFF]">
            <button onclick="sendMessage()" class="bg-[#007AFF] dark:bg-[#0A84FF] hover:bg-[#0062CC] text-white font-bold px-4 py-2 rounded-xl text-xs transition shadow-sm">
                Send
            </button>
        </div>
    </div>

    <!-- Layout Scripts -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const openBtn = document.getElementById('open-sidebar');
        const closeBtn = document.getElementById('close-sidebar');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        if(openBtn) openBtn.addEventListener('click', toggleSidebar);
        if(closeBtn) closeBtn.addEventListener('click', toggleSidebar);
        if(overlay) overlay.addEventListener('click', toggleSidebar);

        // Auto close sidebar on mobile when any link is clicked
        if (sidebar) {
            const navLinks = sidebar.querySelectorAll('a');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        sidebar.classList.add('-translate-x-full');
                        if (overlay) overlay.classList.add('hidden');
                    }
                });
            });
        }

        // Light / Dark Theme Switcher Logic
        const themeToggle = document.getElementById('theme-toggle');
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

        // Chatbot Toggle
        const chatToggle = document.getElementById('chat-toggle');
        const chatWindow = document.getElementById('chat-window');
        const chatClose = document.getElementById('chat-close');

        if(chatToggle && chatWindow) {
            chatToggle.addEventListener('click', function() {
                chatWindow.classList.toggle('hidden');
                chatWindow.classList.toggle('flex');
            });
        }
        if(chatClose && chatWindow) {
            chatClose.addEventListener('click', function() {
                chatWindow.classList.add('hidden');
                chatWindow.classList.remove('flex');
            });
        }
    });

    function sendMessage() {
        const input = document.getElementById('message');
        const message = input.value.trim();
        if (!message) return;

        const chatBox = document.getElementById('chat-box');
        chatBox.innerHTML += `
            <div class="flex justify-end">
                <div class="bg-[#007AFF] dark:bg-[#0A84FF] text-white font-medium px-3.5 py-2.5 rounded-2xl rounded-br-none max-w-[85%] shadow-sm">
                    ${message}
                </div>
            </div>
        `;
        input.value = "";
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
            chatBox.innerHTML += `
                <div class="flex justify-start">
                    <div class="bg-white dark:bg-[#2C2C2E] text-slate-900 dark:text-[#F5F5F7] px-3.5 py-2.5 rounded-2xl rounded-bl-none max-w-[85%] border border-black/10 dark:border-white/10 shadow-sm">
                        <strong class="text-[#007AFF] dark:text-[#0A84FF] block mb-0.5">HourWash Assistant</strong>
                        ${data.reply}
                    </div>
                </div>
            `;
            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(err => {
            chatBox.innerHTML += `
                <div class="flex justify-start">
                    <div class="bg-rose-500/10 text-rose-500 border border-rose-500/20 px-3 py-2 rounded-xl">
                        Could not reach assistant. Please try again.
                    </div>
                </div>
            `;
        });
    }
    </script>
</body>
</html>