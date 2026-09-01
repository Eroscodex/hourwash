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

<body class="bg-slate-50 dark:bg-[#09090B] text-slate-900 dark:text-zinc-100 font-sans antialiased min-h-screen flex items-center justify-center p-4 sm:p-6 selection:bg-blue-600 selection:text-white">
    <div id="top-loading-bar" class="fixed top-0 left-0 h-1 bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-400 z-[9999] transition-all duration-500 ease-out w-0 opacity-0 pointer-events-none shadow-sm"></div>

    <div class="w-full max-w-md space-y-6">

        
        <div class="flex items-center justify-between">
            <a href="{{ route('welcome') }}" class="btn-secondary text-xs">Back</a>
            <button id="theme-toggle-btn" onclick="toggleTheme()" class="p-2 px-3 rounded-md bg-slate-100 dark:bg-zinc-800 text-slate-900 dark:text-zinc-100 border border-slate-200 dark:border-zinc-700 hover:bg-slate-200 dark:hover:bg-zinc-700 transition text-xs font-semibold flex items-center gap-1.5 shadow-sm cursor-pointer" title="Toggle Light/Dark Theme">
                <span class="dark:hidden flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Light
                </span>
                <span class="hidden dark:flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    Dark
                </span>
            </button>
        </div>

        
        <div class="flex flex-col items-center text-center mb-6">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-14 h-14 rounded-lg object-cover shadow-sm bg-white p-1 border border-slate-200 dark:border-zinc-700">
            </a>
            <h1 class="mt-3 text-2xl font-black tracking-tight text-slate-900 dark:text-white uppercase">
                HOUR WASH
            </h1>
            <p class="text-xs text-blue-600 dark:text-blue-400 tracking-widest uppercase mt-1 font-semibold">LAUNDRY MANAGEMENT SYSTEM</p>
        </div>

        
        <div class="app-card px-6 py-7 sm:px-8">
            {{ $slot }}
        </div>

        
        <p class="text-center text-slate-500 dark:text-slate-400 text-xs mt-6">
            © {{ date('Y') }} Hour Wash Laundry Shop
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

        document.addEventListener('DOMContentLoaded', function () {
            const topBar = document.getElementById('top-loading-bar');
            
            document.querySelectorAll('form').forEach(function (form) {
                form.addEventListener('submit', function () {
                    if (form.checkValidity && !form.checkValidity()) return;
                    
                    const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
                    if (submitBtn && !submitBtn.dataset.noLoading) {
                        setTimeout(function() {
                            submitBtn.disabled = true;
                        }, 20);
                        
                        submitBtn.classList.add('opacity-80', 'cursor-not-allowed', 'pointer-events-none');
                        
                        if (!submitBtn.querySelector('.global-btn-spinner')) {
                            const spinner = document.createElement('span');
                            spinner.className = 'global-btn-spinner inline-flex items-center shrink-0 mr-2';
                            spinner.innerHTML = `<svg class="animate-spin h-3.5 w-3.5 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
                            submitBtn.insertBefore(spinner, submitBtn.firstChild);
                        }
                    }
                    
                    if (topBar) {
                        topBar.style.width = '75%';
                        topBar.style.opacity = '1';
                    }
                });
            });

            window.addEventListener('beforeunload', function () {
                if (topBar) {
                    topBar.style.width = '95%';
                    topBar.style.opacity = '1';
                }
            });
        });
    </script>
</body>
</html>