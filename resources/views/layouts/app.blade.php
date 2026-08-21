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

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
        if (localStorage.getItem('sidebar_collapsed') === 'true') {
            document.documentElement.classList.add('sidebar-collapsed');
        }
    </script>
    <style>
        /* Collapsed Sidebar Styles - Desktop Only (min-width: 768px) */
        @media (min-width: 768px) {
            html.sidebar-collapsed #sidebar {
                width: 4.5rem !important;
            }
            html.sidebar-collapsed #main-wrapper {
                padding-left: 4.5rem !important;
            }
            html.sidebar-collapsed .sidebar-text,
            html.sidebar-collapsed .sidebar-section-header {
                display: none !important;
            }
            html.sidebar-collapsed #sidebar-toggle-icon {
                transform: rotate(180deg);
            }
            html.sidebar-collapsed #sidebar-header-box {
                padding-left: 0 !important;
                padding-right: 0 !important;
                justify-content: center !important;
            }
            html.sidebar-collapsed #sidebar-header-box a {
                justify-content: center !important;
                width: 100% !important;
            }
            html.sidebar-collapsed #sidebar-user-box {
                padding-left: 0 !important;
                padding-right: 0 !important;
                justify-content: center !important;
            }
            html.sidebar-collapsed #sidebar-user-box > div {
                justify-content: center !important;
                width: 100% !important;
            }
            html.sidebar-collapsed .sidebar-nav-item {
                justify-content: center !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
                width: 100% !important;
            }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 dark:bg-[#09090B] text-slate-900 dark:text-zinc-100 font-['Inter'] antialiased selection:bg-blue-600 selection:text-white min-h-screen">

    <div class="flex min-h-screen relative overflow-x-hidden">

        <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 dark:bg-black/80 z-40 hidden md:hidden transition-opacity"></div>

        <aside id="sidebar" class="fixed top-0 bottom-0 left-0 h-screen w-64 bg-white dark:bg-[#141417] border-r border-slate-200 dark:dark:border-zinc-700 z-50 transform -translate-x-full md:translate-x-0 transition-[width,transform] duration-300 flex flex-col justify-between shadow-sm">

            <div class="flex flex-col flex-1 min-h-0 overflow-y-auto">
                <div id="sidebar-header-box" class="p-5 border-b border-slate-200 dark:dark:border-zinc-700 flex items-center justify-between flex-shrink-0 transition-all">
                    <a href="{{ route('welcome') }}" class="flex items-center gap-3 group w-full justify-start transition-all">
                        <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-11 h-11 rounded-full object-cover shadow-sm group-transition-transform bg-white p-0.5 border border-slate-200 dark:dark:border-zinc-700 shrink-0">
                        <div id="sidebar-logo-text" class="sidebar-text">
                            <h1 class="text-lg font-bold tracking-wide text-slate-900 dark:text-white">
                                HOUR WASH
                            </h1>
                            <p class="text-[8.5px] text-blue-600 dark:text-blue-400 tracking-wider uppercase font-bold whitespace-nowrap leading-none mt-0.5">LAUNDRY MANAGEMENT SYSTEM</p>
                        </div>
                    </a>
                    <button id="close-sidebar" class="md:hidden text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <nav class="p-4 space-y-1.5 text-sm font-medium">
                    @auth
                        @if(auth()->user()->isOwner())
                            <div class="sidebar-section-header px-3 py-2 text-[11px] font-extrabold text-slate-900 dark:text-slate-200 uppercase tracking-wider">Navigation</div>

                            <a href="{{ route('admin.dashboard') }}" title="Overall Reports & Dashboard" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:dark:bg-zinc-800' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                <div class="sidebar-text">
                                    <span class="block leading-snug text-[11.5px] font-bold whitespace-nowrap">Overall Reports & Dashboard</span>
                                    <p class="text-[10px] opacity-75 font-normal block leading-tight mt-0.5 whitespace-nowrap">System overview & metrics</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.laundry.index') }}" title="Manage Laundry Orders" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.laundry.*') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:dark:bg-zinc-800' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                <div class="sidebar-text">
                                    <span class="block leading-snug text-[11.5px] font-bold whitespace-nowrap">Manage Laundry Orders</span>
                                    <p class="text-[10px] opacity-75 font-normal block leading-tight mt-0.5 whitespace-nowrap">Queue & cashier processing</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.machines.index') }}" title="Machine Monitor" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.machines.*') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:dark:bg-zinc-800' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <div class="sidebar-text">
                                    <span class="block leading-snug text-[11.5px] font-bold whitespace-nowrap">Manage Machines</span>
                                    <p class="text-[10px] opacity-75 font-normal block leading-tight mt-0.5 whitespace-nowrap">Machine Status & Fleet Monitor</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.services.index') }}" title="Services & Pricing" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.services.*') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:dark:bg-zinc-800' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                <div class="sidebar-text">
                                    <span class="block leading-snug text-[11.5px] font-bold whitespace-nowrap">Services & Pricing</span>
                                    <p class="text-[10px] opacity-75 font-normal block leading-tight mt-0.5 whitespace-nowrap">Service rates & load options</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.users.index') }}" title="Manage Users" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:dark:bg-zinc-800' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <div class="sidebar-text">
                                    <span class="block leading-snug text-[11.5px] font-bold whitespace-nowrap">Manage Users</span>
                                    <p class="text-[10px] opacity-75 font-normal block leading-tight mt-0.5 whitespace-nowrap">Accounts & role permissions</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.sms.index') }}" title="Live SMS Outbox" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.sms.*') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:dark:bg-zinc-800' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                <div class="sidebar-text">
                                    <span class="block leading-snug text-[11.5px] font-bold whitespace-nowrap">Live SMS Outbox</span>
                                    <p class="text-[10px] opacity-75 font-normal block leading-tight mt-0.5 whitespace-nowrap">Phone notification logs</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.emails.index') }}" title="Live Email Outbox" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.emails.*') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:dark:bg-zinc-800' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <div class="sidebar-text">
                                    <span class="block leading-snug text-[11.5px] font-bold whitespace-nowrap">Live Email Outbox</span>
                                    <p class="text-[10px] opacity-75 font-normal block leading-tight mt-0.5 whitespace-nowrap">HTML email receipt logs</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.reviews.index') }}" title="Customer Reviews Outbox" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.reviews.*') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:dark:bg-zinc-800' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                <div class="sidebar-text">
                                    <span class="block leading-snug text-[11.5px] font-bold whitespace-nowrap">Customer Reviews Outbox</span>
                                    <p class="text-[10px] opacity-75 font-normal block leading-tight mt-0.5 whitespace-nowrap">Ratings & feedback logs</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.qr_scan_logs.index') }}" title="QR Scan Logs Outbox" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.qr_scan_logs.*') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:dark:bg-zinc-800' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                <div class="sidebar-text">
                                    <span class="block leading-snug text-[11.5px] font-bold whitespace-nowrap">QR Scan Logs Outbox</span>
                                    <p class="text-[10px] opacity-75 font-normal block leading-tight mt-0.5 whitespace-nowrap">Audit log of all QR scans</p>
                                </div>
                            </a>
                        @elseif(auth()->user()->isRider())
                            <div class="sidebar-section-header px-3 py-2 text-[11px] font-extrabold text-slate-900 dark:text-slate-200 uppercase tracking-wider">NAVIGATION</div>

                            <a href="{{ route('rider.dashboard') }}" title="Rider Logistics Dashboard" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-all {{ request()->routeIs('rider.dashboard') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:dark:bg-zinc-800' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <div class="sidebar-text">
                                    <span class="block leading-snug">Rider Logistics Dashboard</span>
                                    <p class="text-[10.5px] opacity-75 font-normal block leading-tight mt-0.5">Pickup & delivery dispatches</p>
                                </div>
                            </a>
                        @elseif(auth()->user()->isStaff())
                            <div class="sidebar-section-header px-3 py-2 text-[11px] font-extrabold text-slate-900 dark:text-slate-200 uppercase tracking-wider">Navigation</div>

                            <a href="{{ route('staff.dashboard') }}" title="Workstation Dashboard" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-all {{ request()->routeIs('staff.dashboard') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:dark:bg-zinc-800' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                <div class="sidebar-text">
                                    <span class="block leading-snug">Workstation Dashboard</span>
                                    <p class="text-[10.5px] opacity-75 font-normal block leading-tight mt-0.5">Active laundry processing</p>
                                </div>
                            </a>

                            <a href="{{ route('staff.laundry.index') }}" title="Manage Laundry Orders" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-all {{ request()->routeIs('staff.laundry.*') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:dark:bg-zinc-800' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                <div class="sidebar-text">
                                    <span class="block leading-snug">Manage Laundry Orders</span>
                                    <p class="text-[10.5px] opacity-75 font-normal block leading-tight mt-0.5">Queue & cashier processing</p>
                                </div>
                            </a>

                            <a href="{{ route('staff.machines.index') }}" title="Machine Monitor" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-all {{ request()->routeIs('staff.machines.*') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:dark:bg-zinc-800' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <div class="sidebar-text">
                                    <span class="block leading-snug">Machine Monitor</span>
                                    <p class="text-[10.5px] opacity-75 font-normal block leading-tight mt-0.5">Machine Status & Fleet Monitor</p>
                                </div>
                            </a>

                            <a href="{{ route('laundry.create') }}" title="New Walk-in Order" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-all {{ request()->routeIs('laundry.create') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:dark:bg-zinc-800' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <div class="sidebar-text">
                                    <span class="block leading-snug">New Walk-in Order</span>
                                    <p class="text-[10.5px] opacity-75 font-normal block leading-tight mt-0.5">Book customer wash</p>
                                </div>
                            </a>

                            <a href="{{ route('staff.qr_scan_logs.index') }}" title="QR Scan Logs Outbox" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-all {{ request()->routeIs('staff.qr_scan_logs.*') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:dark:bg-zinc-800' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                <div class="sidebar-text">
                                    <span class="block leading-snug">QR Scan Logs Outbox</span>
                                    <p class="text-[10.5px] opacity-75 font-normal block leading-tight mt-0.5">Audit log of all QR scans</p>
                                </div>
                            </a>
                        @else
                            <div class="sidebar-section-header px-3 py-2 text-[11px] font-extrabold text-slate-900 dark:text-slate-200 uppercase tracking-wider">NAVIGATION</div>

                            <a href="{{ route('dashboard') }}" title="Customer Dashboard" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-all {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:dark:bg-zinc-800' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                <div class="sidebar-text">
                                    <span class="block leading-snug">Customer Dashboard</span>
                                    <p class="text-[10.5px] opacity-75 font-normal block leading-tight mt-0.5">Live status & quick actions</p>
                                </div>
                            </a>

                            <a href="{{ route('laundry.create') }}" title="Book New Order" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-all {{ request()->routeIs('laundry.create') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:dark:bg-zinc-800' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <div class="sidebar-text">
                                    <span class="block leading-snug">Book New Order</span>
                                    <p class="text-[10.5px] opacity-75 font-normal block leading-tight mt-0.5">New wash booking request</p>
                                </div>
                            </a>

                            <a href="{{ route('my.orders') }}" title="My Order History" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-all {{ request()->routeIs('my.orders') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:dark:bg-zinc-800' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <div class="sidebar-text">
                                    <span class="block leading-snug">My Order History</span>
                                    <p class="text-[10.5px] opacity-75 font-normal block leading-tight mt-0.5">Track bookings & receipts</p>
                                </div>
                            </a>

                            <a href="{{ route('frequent_card.index') }}" title="Frequent User Card" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-all {{ request()->routeIs('frequent_card.*') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:dark:bg-zinc-800' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 000 4v3a2 2 0 01-2 2H5a2 2 0 01-2-2v-3a2 2 0 000-4V7a2 2 0 012-2z"/></svg>
                                <div class="sidebar-text">
                                    <span class="block leading-snug">Frequent User Card</span>
                                    <p class="text-[10.5px] opacity-75 font-normal block leading-tight mt-0.5">12-stamp loyalty rewards</p>
                                </div>
                            </a>
                        @endif

                        <div class="pt-4 border-t border-slate-200 dark:dark:border-zinc-700 my-2"></div>
                        <a href="{{ route('welcome') }}" title="Home Dashboard" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:dark:bg-zinc-800 font-medium transition-all">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                            <div class="sidebar-text">
                                <span class="block leading-snug">Home Dashboard</span>
                                <p class="text-[10.5px] opacity-75 font-normal block leading-tight mt-0.5">Public landing page</p>
                            </div>
                        </a>
                        @php
                            $userRole = auth()->user()?->role;
                            $profileRoute = match($userRole) {
                                'admin', 'owner' => route('admin.profile.edit'),
                                'staff' => route('staff.profile.edit'),
                                default => route('customer.profile.edit'),
                            };
                        @endphp
                        <a href="{{ $profileRoute }}" title="Account Settings" class="sidebar-nav-item flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:dark:bg-zinc-800 font-medium transition-all">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <div class="sidebar-text">
                                <span class="block leading-snug">Account Settings</span>
                                <p class="text-[10.5px] opacity-75 font-normal block leading-tight mt-0.5">Profile & security settings</p>
                            </div>
                        </a>
                    @endauth
                </nav>
            </div>

            @auth
            <div id="sidebar-user-box" class="p-4 border-t border-slate-200 dark:dark:border-zinc-700 bg-slate-50 dark:bg-white/5 transition-all">
                <div class="flex items-center justify-start gap-3 w-full transition-all">
                    <div class="w-10 h-10 rounded-lg bg-blue-600 dark:bg-blue-600 text-white flex items-center justify-center font-bold shadow-sm shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="sidebar-text flex-1 overflow-hidden">
                        <h4 class="text-sm font-bold truncate text-slate-900 dark:text-white">{{ auth()->user()->name }}</h4>
                        <p class="text-[11px] text-blue-600 dark:text-blue-400 capitalize flex items-center gap-1 font-extrabold">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600 dark:bg-blue-600 inline-block animate-pulse"></span>
                            {{ auth()->user()->role }}
                        </p>
                    </div>
                </div>
            </div>
            @endauth
        </aside>

        <div id="main-wrapper" class="flex-1 flex flex-col min-w-0 md:pl-64 transition-[padding] duration-300">

            <header class="bg-white dark:bg-[#141417] border-b border-slate-200 dark:dark:border-zinc-700 px-4 md:px-8 py-3.5 flex items-center justify-between sticky top-0 z-30 shadow-sm backdrop-blur-sm">
                <div class="flex items-center gap-3">
                    <button id="open-sidebar" class="md:hidden p-2 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:dark:bg-zinc-800 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <button id="toggle-collapse-sidebar" class="hidden md:flex p-2 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:dark:bg-zinc-800 focus:outline-none transition-all cursor-pointer" title="Toggle Sidebar Collapse">
                        <svg id="sidebar-toggle-icon" class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                        </svg>
                    </button>
                    <form action="{{ route('global.search') }}" method="GET" class="hidden sm:flex items-center relative w-64 md:w-80">
                        <button type="submit" aria-label="Submit search" class="absolute left-3 text-slate-400 dark:text-zinc-400 hover:text-blue-600 focus:outline-none z-10 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search orders, machines, users..." class="w-full !pl-9 pr-4 py-2 bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-700 rounded-lg text-xs text-slate-900 dark:text-zinc-100 placeholder-slate-400 dark:placeholder-zinc-500 focus:outline-none focus:border-blue-600 transition" style="padding-left: 2.25rem !important;" required>
                    </form>
                </div>

                <div class="flex items-center gap-2 sm:gap-4">
                    <button id="theme-toggle" class="p-2 px-3 rounded-lg bg-slate-100 dark:dark:bg-zinc-800 text-slate-900 dark:text-zinc-100 border border-slate-200 dark:dark:border-zinc-700 transition-all text-xs font-semibold flex items-center gap-1.5 shadow-sm" title="Toggle Light/Dark Theme">
                        <span class="dark:hidden flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            Light
                        </span>
                        <span class="hidden dark:flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                            Dark
                        </span>
                    </button>

                    <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-100 dark:dark:bg-zinc-800 border border-slate-200 dark:dark:border-zinc-700 text-xs text-slate-800 dark:text-slate-200 font-semibold">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ now()->format('M d, Y') }}</span>
                    </div>

                    @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 rounded-lg text-slate-700 dark:text-white hover:bg-slate-100 dark:hover:bg-zinc-800 transition" title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                    @endauth
                </div>
            </header>

            <main class="flex-1 p-4 md:p-8 max-w-7xl w-full mx-auto">
                <x-popup-alert />
                {{ $slot }}
            </main>

            <footer class="border-t border-slate-200 dark:dark:border-zinc-700 py-4 px-6 md:px-8 bg-white dark:bg-[#141417] w-full">
                <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-slate-600 dark:text-slate-400">
                    <div>© {{ date('Y') }} Hour Wash Laundry Management System</div>
                    <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-2">
                        <a href="{{ route('about') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">About Us</a>
                        <span class="text-slate-300 dark:text-slate-700">•</span>
                        <a href="{{ route('developers') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Developers</a>
                        <span class="text-slate-300 dark:text-slate-700">•</span>
                        <a href="{{ route('privacy') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Privacy Policy</a>
                        <span class="text-slate-300 dark:text-slate-700">•</span>
                        <a href="{{ route('terms') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Terms & Conditions</a>
                        <span class="text-slate-300 dark:text-slate-700">•</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-300">Magallanes St., Orosite, Legazpi City</span>
                    </div>
                </div>
            </footer>

        </div>
    </div>

    <button id="chat-toggle" class="fixed bottom-6 right-6 w-14 h-14 rounded-lg bg-blue-600 dark:bg-blue-600 text-white chat-bubble-glow flex items-center justify-center active:scale-[0.98] transition-transform z-50 group" aria-label="Toggle AI Assistant Chat">
        <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-md bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-md h-3.5 w-3.5 bg-emerald-500 border-2 border-slate-900"></span>
        </span>
        <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-8 h-8 rounded-full object-cover group-hover:rotate-12 transition-transform bg-white p-0.5 border border-white/20 shadow-sm">
    </button>

    <div id="chat-window" class="fixed bottom-24 right-6 w-80 sm:w-96 bg-white dark:bg-[#141417] border border-slate-200 dark:dark:border-zinc-600 rounded-2xl shadow-xl z-50 hidden flex-col overflow-hidden backdrop-blur-sm">
        <div class="p-4 bg-blue-600 dark:bg-blue-600 text-white flex items-center justify-between">
            <div class="flex items-center gap-2 font-bold text-sm">
                <span class="w-2.5 h-2.5 rounded-full bg-white animate-pulse"></span>
                Hour Wash
            </div>
            <button id="chat-close" class="text-white/80 hover:text-white text-lg">✕</button>
        </div>

        <div id="chat-box" class="p-4 h-80 overflow-y-auto space-y-3 text-xs bg-slate-50 dark:bg-[#09090B]">
            <div class="flex justify-start">
                <div class="bg-white dark:bg-[#18181B] text-slate-900 dark:text-zinc-100 px-4 py-3 rounded-2xl rounded-tl-sm max-w-[85%] border border-slate-200 dark:dark:border-zinc-700 shadow-xs">
                    Hi! How can I help you today, po? 😊
                </div>
            </div>

            <!-- Quick Suggestion Pills (CATC Style) -->
            <div id="quick-suggestions-container" class="flex flex-col items-end space-y-2 pt-2">
                <button onclick="sendQuickMessage('What requirements are needed?')" class="text-xs bg-white dark:bg-[#18181B] text-slate-800 dark:text-slate-200 hover:bg-slate-100 border border-slate-300 dark:border-zinc-700 px-4 py-2 rounded-full transition shadow-xs">
                    What requirements are needed?
                </button>
                <button onclick="sendQuickMessage('Services & rates offered?')" class="text-xs bg-white dark:bg-[#18181B] text-slate-800 dark:text-slate-200 hover:bg-slate-100 border border-slate-300 dark:border-zinc-700 px-4 py-2 rounded-full transition shadow-xs">
                    Services & rates offered?
                </button>
                <button onclick="sendQuickMessage('Store location & hours?')" class="text-xs bg-white dark:bg-[#18181B] text-slate-800 dark:text-slate-200 hover:bg-slate-100 border border-slate-300 dark:border-zinc-700 px-4 py-2 rounded-full transition shadow-xs">
                    Store location & hours?
                </button>
            </div>
        </div>

        <div class="p-3 border-t border-slate-200 dark:dark:border-zinc-700 bg-white dark:bg-[#141417] flex flex-col gap-1.5">
            <div class="flex items-center gap-2">
                <input id="message" type="text" placeholder="Hello! I have a question :)" class="flex-1 bg-slate-100 dark:bg-[#18181B] border border-slate-300 dark:dark:border-zinc-700 rounded-full px-4 py-2.5 text-xs focus:outline-none focus:border-blue-600" onkeydown="if(event.key==='Enter')sendMessage()">
                <button onclick="sendMessage()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold w-9 h-9 rounded-full text-sm flex items-center justify-center transition shadow-xs shrink-0">
                    ↑
                </button>
            </div>
            <p class="text-[9.5px] text-center text-slate-400 dark:text-slate-500 font-medium">
                This is an AI generated response for Hour Wash Laundry.
            </p>
        </div>
    </div>

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

        const collapseBtn = document.getElementById('toggle-collapse-sidebar');
        if (collapseBtn) {
            collapseBtn.addEventListener('click', function() {
                const isCollapsed = document.documentElement.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebar_collapsed', isCollapsed ? 'true' : 'false');
            });
        }

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

        const chatToggle = document.getElementById('chat-toggle');
        const chatWindow = document.getElementById('chat-window');
        const chatClose = document.getElementById('chat-close');

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

    function sendQuickMessage(text) {
        const input = document.getElementById('message');
        if (input) {
            input.value = text;
            sendMessage();
        }
    }

    function sendMessage() {
        const input = document.getElementById('message');
        const message = input.value.trim();
        if (!message) return;

        const chatBox = document.getElementById('chat-box');

        // Hide quick suggestions container once user sends a message
        const suggContainer = document.getElementById('quick-suggestions-container');
        if (suggContainer) suggContainer.remove();

        chatBox.innerHTML += `
            <div class="flex justify-end">
                <div class="bg-white dark:bg-[#18181B] text-slate-900 dark:text-zinc-100 font-medium px-4 py-2.5 rounded-2xl rounded-tr-sm max-w-[85%] border border-slate-300 dark:border-zinc-700 shadow-xs">
                    ${message}
                </div>
            </div>
        `;
        input.value = "";
        chatBox.scrollTop = chatBox.scrollHeight;

        const typingId = 'typing-' + Date.now();
        chatBox.innerHTML += `
            <div class="flex justify-start" id="${typingId}">
                <div class="bg-white dark:bg-[#18181B] text-slate-500 px-3.5 py-2.5 rounded-2xl rounded-tl-sm border border-slate-200 dark:dark:border-zinc-700 shadow-xs">
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

            let formattedReply = data.reply || '';
            formattedReply = formattedReply.replace(/\[([^\]]+)\]\((https?:\/\/[^\s\)]+)\)/g, '<a href="$2" target="_blank" class="text-blue-600 dark:text-blue-400 font-bold underline hover:opacity-80 transition break-all">$1</a>');
            formattedReply = formattedReply.replace(/(?<!href=")(https?:\/\/[^\s<]+)/g, '<a href="$1" target="_blank" class="text-blue-600 dark:text-blue-400 font-bold underline hover:opacity-80 transition break-all">$1</a>');
            formattedReply = formattedReply.replace(/\n/g, '<br>');

            chatBox.innerHTML += `
                <div class="flex justify-start">
                    <div class="bg-white dark:bg-[#18181B] text-slate-900 dark:text-zinc-100 px-3.5 py-2.5 rounded-2xl rounded-tl-sm max-w-[85%] border border-slate-200 dark:dark:border-zinc-700 shadow-xs break-all [word-break:break-word] overflow-hidden">
                        <strong class="text-blue-600 dark:text-blue-400 block mb-0.5">Hour Wash Assistant</strong>
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
                    <div class="bg-rose-500/10 text-rose-500 border border-rose-500/20 px-3 py-2 rounded-lg">
                        Could not reach assistant. Please try again.
                    </div>
                </div>
            `;
        });
    }
    </script>
</body>

</html>
