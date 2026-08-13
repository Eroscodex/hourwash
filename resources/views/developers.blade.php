<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Our Developers | Hour Wash Laundry</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-[#F2F2F7] dark:bg-[#000000] text-slate-900 dark:text-[#F5F5F7] font-['Inter'] antialiased min-h-screen py-10 px-4 sm:px-6">
    <div class="w-full max-w-3xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex items-center justify-between">
            <a href="{{ route('welcome') }}" class="btn-ios-secondary text-xs">Back to Home</a>
            <button id="theme-toggle" class="p-2 px-3 rounded-xl bg-white dark:bg-white/10 text-slate-900 dark:text-[#F5F5F7] border border-black/10 dark:border-white/10 hover:scale-105 transition-all text-xs font-semibold flex items-center gap-1.5 shadow-sm cursor-pointer" title="Toggle Light/Dark Theme">
                <span class="dark:hidden flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Light</span>
                </span>
                <span class="hidden dark:flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <span>Dark</span>
                </span>
            </button>
        </div>

        <!-- Content Card -->
        <div class="bg-white dark:bg-[#1C1C1E] border border-black/10 dark:border-white/15 rounded-2xl p-6 sm:p-8 space-y-8 shadow-xl">
            <div class="text-center space-y-2 border-b border-black/10 dark:border-white/10 pb-5">
                <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-16 h-16 rounded-full mx-auto bg-white p-1 border border-black/10">
                <h1 class="text-2xl font-bold font-['Outfit'] text-slate-900 dark:text-white">Meet Our Developers</h1>
                <p class="text-xs text-slate-500">The Innovators behind the HourWash Platform</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Developer 1: Karl Nicko L. Alondra -->
                <div class="bg-[#F2F2F7] dark:bg-[#2C2C2E] border border-black/5 dark:border-white/5 rounded-2xl p-6 flex flex-col items-center text-center space-y-4 shadow-sm hover:scale-[1.02] transition-transform duration-300">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-[#007AFF] to-sky-400 text-white flex items-center justify-center font-bold text-xl shadow-md">
                        K
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-base font-bold font-['Outfit'] text-slate-900 dark:text-white">Karl Nicko L. Alondra</h3>
                        <span class="inline-block text-[10px] bg-[#007AFF]/15 text-[#007AFF] dark:text-[#0A84FF] font-bold px-2.5 py-0.5 rounded-full">Lead Developer</span>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Responsible for designing the system architecture, database design, backend logic, and overall system functionality of HourWash.
                    </p>
                </div>

                <!-- Developer 2: Lezil O. Orgasa -->
                <div class="bg-[#F2F2F7] dark:bg-[#2C2C2E] border border-black/5 dark:border-white/5 rounded-2xl p-6 flex flex-col items-center text-center space-y-4 shadow-sm hover:scale-[1.02] transition-transform duration-300">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-teal-500 to-emerald-400 text-white flex items-center justify-center font-bold text-xl shadow-md">
                        L
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-base font-bold font-['Outfit'] text-slate-900 dark:text-white">Lezil O. Orgasa</h3>
                        <span class="inline-block text-[10px] bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 font-bold px-2.5 py-0.5 rounded-full">Frontend Designer</span>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Specializes in modern user interfaces, designing responsive layouts, visual elements, and smooth interactions.
                    </p>
                </div>

                <!-- Developer 3: Shayne Marie R. Formento -->
                <div class="bg-[#F2F2F7] dark:bg-[#2C2C2E] border border-black/5 dark:border-white/5 rounded-2xl p-6 flex flex-col items-center text-center space-y-4 shadow-sm hover:scale-[1.02] transition-transform duration-300">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-purple-600 to-pink-500 text-white flex items-center justify-center font-bold text-xl shadow-md">
                        S
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-base font-bold font-['Outfit'] text-slate-900 dark:text-white">Shayne Marie R. Formento</h3>
                        <span class="inline-block text-[10px] bg-purple-500/15 text-purple-600 dark:text-purple-400 font-bold px-2.5 py-0.5 rounded-full">System Analyst</span>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Focuses on core system logic analysis, requirement definitions, quality assurance, and functional validation.
                    </p>
                </div>

                <!-- Developer 4: Alexa P. Cas -->
                <div class="bg-[#F2F2F7] dark:bg-[#2C2C2E] border border-black/5 dark:border-white/5 rounded-2xl p-6 flex flex-col items-center text-center space-y-4 shadow-sm hover:scale-[1.02] transition-transform duration-300">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-amber-500 to-orange-400 text-white flex items-center justify-center font-bold text-xl shadow-md">
                        A
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-base font-bold font-['Outfit'] text-slate-900 dark:text-white">Alexa P. Cas</h3>
                        <span class="inline-block text-[10px] bg-amber-500/15 text-amber-600 dark:text-amber-400 font-bold px-2.5 py-0.5 rounded-full">Database & Docs</span>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Manages database optimization, data validation pipelines, system logs management, and technical documentation.
                    </p>
                </div>
            </div>

            <div class="border-t border-black/10 dark:border-white/10 pt-6 text-center text-xs text-slate-500 dark:text-slate-400">
                HourWash Development Team
            </div>
        </div>
    </div>

    <script>
        document.getElementById('theme-toggle').addEventListener('click', function() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        });
    </script>
</body>
</html>
