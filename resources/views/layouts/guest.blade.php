<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Hour Wash Laundry Shop') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

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
            <a href="{{ route('welcome') }}" class="btn-ios-secondary text-xs">Back</a>
            <button id="theme-toggle-btn" onclick="toggleTheme()" class="p-2 px-3 rounded-xl bg-slate-100 dark:bg-white/10 text-slate-900 dark:text-[#F5F5F7] border border-black/10 dark:border-white/10 hover:scale-105 transition-all text-xs font-semibold flex items-center gap-1.5 shadow-sm cursor-pointer" title="Toggle Light/Dark Theme">
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
        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
    </script>

</body>
</html>