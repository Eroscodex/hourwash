<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Privacy Policy | Hour Wash Laundry</title>
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
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <span>Dark</span>
                </span>
            </button>
        </div>

        
        <div class="bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-600 rounded-lg p-6 sm:p-8 space-y-6 shadow-sm">
            <div class="text-center space-y-2 border-b border-slate-200 dark:border-zinc-700 pb-5">
                <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-16 h-16 rounded-full mx-auto bg-white p-1 border border-slate-200">
                <h1 class="text-2xl font-bold  text-slate-900 dark:text-white">Privacy Policy</h1>
                <p class="text-xs text-slate-500">Last updated: {{ date('F d, Y') }}</p>
            </div>

            <div class="space-y-4 text-sm leading-relaxed text-slate-700 dark:text-slate-300">
                <p>
                    At <strong>Hour Wash Laundry</strong>, we value your privacy and are committed to protecting your personal data. This Privacy Policy explains how we collect, use, and protect your information when you use our laundry services and web application.
                </p>

                <h3 class="text-base font-bold  text-slate-900 dark:text-white pt-2">1. Information We Collect</h3>
                <p>
                    We collect basic information required to process your laundry orders, manage your account, and contact you regarding order status updates. This includes your name, email address, phone number, and laundry transaction details.
                </p>

                <h3 class="text-base font-bold  text-slate-900 dark:text-white pt-2">2. How We Use Your Information</h3>
                <ul class="list-disc pl-5 space-y-1">
                    <li>To process, track, and complete your laundry orders.</li>
                    <li>To send automated SMS and email notifications regarding order status updates (e.g. Received, Washing, Ready for pickup).</li>
                </ul>

                <h3 class="text-base font-bold  text-slate-900 dark:text-white pt-2">3. Data Security</h3>
                <p>
                    We implement industry-standard security measures to protect your account credentials, transactional logs, and contact details from unauthorized access, alteration, or disclosure.
                </p>

                <h3 class="text-base font-bold  text-slate-900 dark:text-white pt-2">4. Third-Party Services</h3>
                <p>
                    We use trusted third-party services (such as Brevo for sending emails and SMS) solely to dispatch transactional notifications. We do not sell or rent your personal information to third parties.
                </p>
            </div>
        </div>

        <footer class="pt-4 border-t border-slate-200 dark:border-zinc-800 text-center text-[11px] text-slate-500 dark:text-zinc-400 space-y-1.5">
            <div class="flex items-center justify-center gap-x-1.5 sm:gap-x-3 text-[10px] sm:text-xs">
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

