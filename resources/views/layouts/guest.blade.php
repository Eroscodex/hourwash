<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Hour Wash Laundry Shop') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('hourwash.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('hourwash.ico') }}">

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

<body class="bg-[#F2F2F7] dark:bg-[#000000] text-slate-900 dark:text-[#F5F5F7] font-['Inter'] antialiased min-h-screen flex items-center justify-center p-4 sm:p-6 selection:bg-[#007AFF] selection:text-white">

    <div class="w-full max-w-md space-y-6">

        <!-- Top Header & Theme Switcher -->
        <div class="flex items-center justify-between">
            <a href="{{ route('welcome') }}" class="text-xs font-semibold text-[#007AFF] dark:text-[#0A84FF] hover:underline flex items-center gap-1.5">
                ← Back to Shop Home
            </a>

            <!-- Mode Switcher Pill -->
            <button id="theme-toggle-btn" onclick="toggleTheme()" class="px-3 py-1.5 rounded-full bg-slate-200 dark:bg-[#2C2C2E] text-slate-700 dark:text-slate-200 text-xs font-semibold hover:bg-slate-300 dark:hover:bg-slate-700 transition border border-black/10 dark:border-white/10 shadow-sm flex items-center gap-2">
                <span id="theme-text" class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    Dark
                </span>
            </button>
        </div>

        <!-- Logo -->
        <div class="flex flex-col items-center text-center mb-6">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-16 h-16 rounded-full object-cover shadow-xl group-hover:scale-105 transition-transform bg-white p-1 border-2 border-[#007AFF]/30">
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