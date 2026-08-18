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
<body class="bg-slate-50 dark:bg-[#09090B] text-slate-900 dark:text-zinc-100 font-['Inter'] antialiased min-h-screen py-10 px-4 sm:px-6">
    <div class="w-full max-w-3xl mx-auto space-y-6">
        
        
        <div class="flex items-center justify-between">
            <a href="{{ route('welcome') }}" class="btn-secondary text-xs">Back</a>
            <button id="theme-toggle" class="p-2 px-3 rounded-lg bg-white dark:dark:bg-zinc-800 text-slate-900 dark:text-zinc-100 border border-slate-200 dark:border-zinc-700  transition-all text-xs font-semibold flex items-center gap-1.5 shadow-sm cursor-pointer" title="Toggle Light/Dark Theme">
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

        
        <div class="bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-600 rounded-lg p-6 sm:p-8 space-y-8 shadow-sm">
            <div class="text-center space-y-2 border-b border-slate-200 dark:border-zinc-700 pb-5">
                <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-16 h-16 rounded-full mx-auto bg-white p-1 border border-slate-200">
                <h1 class="text-2xl font-bold  text-slate-900 dark:text-white">Meet Our Developers</h1>
                <p class="text-xs text-slate-500">The Innovators behind the HourWash Platform</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="bg-slate-50 dark:bg-[#18181B] border border-black/5 dark:border-white/5 rounded-lg p-6 flex flex-col items-center text-center space-y-4 shadow-sm hover:scale-[1.02] transition-transform duration-300">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-[#2563EB] to-sky-400 text-white flex items-center justify-center font-bold text-xl shadow-md">
                        K
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-base font-bold  text-slate-900 dark:text-white">Karl Nicko L. Alondra</h3>
                        <span class="inline-block text-[10px] bg-blue-600/15 text-blue-600 dark:text-blue-400 font-bold px-2.5 py-0.5 rounded-full">Lead Developer</span>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Responsible for designing the system architecture, database design, backend logic, and overall system functionality of HourWash.
                    </p>
                </div>

                
                <div class="bg-slate-50 dark:bg-[#18181B] border border-black/5 dark:border-white/5 rounded-lg p-6 flex flex-col items-center text-center space-y-4 shadow-sm hover:scale-[1.02] transition-transform duration-300">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-teal-500 to-emerald-400 text-white flex items-center justify-center font-bold text-xl shadow-md">
                        L
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-base font-bold  text-slate-900 dark:text-white">Lezil O. Orgasa</h3>
                        <span class="inline-block text-[10px] bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 font-bold px-2.5 py-0.5 rounded-full">Frontend Designer</span>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Specializes in modern user interfaces, designing responsive layouts, visual elements, and smooth interactions.
                    </p>
                </div>

                
                <div class="bg-slate-50 dark:bg-[#18181B] border border-black/5 dark:border-white/5 rounded-lg p-6 flex flex-col items-center text-center space-y-4 shadow-sm hover:scale-[1.02] transition-transform duration-300">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-purple-600 to-pink-500 text-white flex items-center justify-center font-bold text-xl shadow-md">
                        S
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-base font-bold  text-slate-900 dark:text-white">Shayne Marie R. Formento</h3>
                        <span class="inline-block text-[10px] bg-purple-500/15 text-purple-600 dark:text-purple-400 font-bold px-2.5 py-0.5 rounded-full">System Analyst</span>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Focuses on core system logic analysis, requirement definitions, quality assurance, and functional validation.
                    </p>
                </div>

                
                <div class="bg-slate-50 dark:bg-[#18181B] border border-black/5 dark:border-white/5 rounded-lg p-6 flex flex-col items-center text-center space-y-4 shadow-sm hover:scale-[1.02] transition-transform duration-300">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-amber-500 to-orange-400 text-white flex items-center justify-center font-bold text-xl shadow-md">
                        A
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-base font-bold  text-slate-900 dark:text-white">Alexa P. Cas</h3>
                        <span class="inline-block text-[10px] bg-amber-500/15 text-amber-600 dark:text-amber-400 font-bold px-2.5 py-0.5 rounded-full">Database & Docs</span>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Manages database optimization, data validation pipelines, system logs management, and technical documentation.
                    </p>
                </div>
            </div>

            <div class="border-t border-slate-200 dark:border-zinc-700 pt-6 text-center text-xs text-slate-500 dark:text-slate-400">
                HourWash Development Team
            </div>
        </div>

        <!-- Footer -->
        <footer class="pt-4 border-t border-slate-200 dark:border-zinc-800 text-center text-xs text-slate-500 dark:text-zinc-500 space-y-2">
            <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-1">
                <a href="{{ route('about') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">About Us</a>
                <span>•</span>
                <a href="{{ route('developers') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Developers</a>
                <span>•</span>
                <a href="{{ route('privacy') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Privacy Policy</a>
                <span>•</span>
                <a href="{{ route('terms') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Terms & Conditions</a>
            </div>
            <div>© {{ date('Y') }} Hour Wash Laundry Management System</div>
        </footer>
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

