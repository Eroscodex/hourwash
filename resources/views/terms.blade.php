<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terms & Conditions | Hour Wash Laundry</title>
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

        
        <div class="bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-600 rounded-lg p-6 sm:p-8 space-y-6 shadow-sm">
            <div class="text-center space-y-2 border-b border-slate-200 dark:border-zinc-700 pb-5">
                <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-16 h-16 rounded-full mx-auto bg-white p-1 border border-slate-200">
                <h1 class="text-2xl font-bold  text-slate-900 dark:text-white">Terms & Conditions</h1>
                <p class="text-xs text-slate-500">Last updated: {{ date('F d, Y') }}</p>
            </div>

            <div class="space-y-4 text-sm leading-relaxed text-slate-700 dark:text-slate-300">
                <p>
                    Welcome to <strong>Hour Wash Laundry</strong>. By using our web application, ordering laundry services, or using our self-service/drop-off facilities, you agree to comply with and be bound by the following terms and conditions.
                </p>

                <h3 class="text-base font-bold  text-slate-900 dark:text-white pt-2">1. Laundry Services</h3>
                <p>
                    We provide self-service washing and drying machines, as well as drop-off/full-service laundry handling. It is the customer's responsibility to check pockets and verify garment care labels before loading machines.
                </p>

                <h3 class="text-base font-bold  text-slate-900 dark:text-white pt-2">2. Liability</h3>
                <ul class="list-disc pl-5 space-y-1">
                    <li>We are not responsible for damage caused by bleeding colors, shrinkage, or weakening of fabrics during standard cycles.</li>
                    <li>We are not liable for any items (coins, jewelry, electronics, etc.) left inside garments or laundry bags.</li>
                </ul>

                <h3 class="text-base font-bold  text-slate-900 dark:text-white pt-2">3. Unclaimed Clothes</h3>
                <p>
                    Drop-off laundry orders that remain unclaimed for more than <strong>30 days</strong> after the ready-for-pickup notification is sent will be subject to storage fees or disposal/donation.
                </p>

                <h3 class="text-base font-bold  text-slate-900 dark:text-white pt-2">4. User Accounts</h3>
                <p>
                    You are responsible for maintaining the confidentiality of your login credentials. You agree to notify us immediately of any unauthorized use of your account.
                </p>
            </div>
        </div>

        <footer class="pt-4 border-t border-slate-200 dark:border-zinc-800 text-center text-[11px] text-slate-500 dark:text-zinc-400 space-y-1.5">
            <div class="flex flex-wrap items-center justify-center gap-x-2.5 gap-y-1">
                <a href="{{ route('about') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">About Us</a>
                <span class="text-slate-300 dark:text-zinc-700">•</span>
                <a href="{{ route('developers') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Developers</a>
                <span class="text-slate-300 dark:text-zinc-700">•</span>
                <a href="{{ route('privacy') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Privacy Policy</a>
                <span class="text-slate-300 dark:text-zinc-700">•</span>
                <a href="{{ route('terms') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Terms & Conditions</a>
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

