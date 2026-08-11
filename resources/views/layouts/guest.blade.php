<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Hour Wash Laundry Shop') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts & Pre-init Theme script -->
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

<body class="bg-[#F5F5F7] text-slate-900 dark:bg-[#000000] dark:text-[#F5F5F7] font-sans antialiased min-h-screen flex items-center justify-center px-4 py-8 transition-colors duration-300">

    <div class="relative w-full max-w-md">

        <!-- Theme Switcher Header -->
        <div class="flex justify-end mb-4">
            <button id="guest-theme-toggle" class="p-2 px-3 rounded-xl bg-slate-100 dark:bg-white/10 text-slate-900 dark:text-[#F5F5F7] border border-black/10 dark:border-white/10 hover:scale-105 transition-all text-xs font-semibold flex items-center gap-1.5 shadow-sm" title="Toggle Light/Dark Theme">
                <span class="dark:hidden flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Light
                </span>
                <span class="hidden dark:flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    Dark
                </span>
            </button>
        </div>

        <!-- Logo -->
        <div class="flex flex-col items-center text-center mb-6">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3 group">
                <div class="w-14 h-14 rounded-2xl bg-[#007AFF] dark:bg-[#0A84FF] text-white shadow-lg shadow-[#007AFF]/20 group-hover:scale-105 transition-transform flex items-center justify-center font-bold">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.6 15.11a2 2 0 01-1.183-1.845V7.4a2 2 0 011.183-1.845l2.4-1.2a6 6 0 013.86-.517l.318.158a6 6 0 003.86.517l2.387-.477a2 2 0 011.022.547l2.4 2.4a2 2 0 01.586 1.414v7.172a2 2 0 01-.586 1.414l-2.4 2.4z"/>
                    </svg>
                </div>
            </a>
            <h1 class="mt-3 text-2xl font-bold tracking-wider text-slate-900 dark:text-white font-['Outfit']">
                HOUR WASH
            </h1>
            <p class="text-xs text-[#007AFF] dark:text-[#0A84FF] tracking-widest uppercase mt-1 font-semibold">LAUNDRY MANAGEMENT SYSTEM</p>
        </div>

        <!-- Form Card Container -->
        <div class="app-card px-6 py-8 sm:px-8 shadow-2xl">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <p class="text-center text-slate-500 dark:text-slate-400 text-xs mt-6">
            © {{ date('Y') }} Hour Wash Laundry Shop • Legazpi City
        </p>

    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const themeToggle = document.getElementById('guest-theme-toggle');
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