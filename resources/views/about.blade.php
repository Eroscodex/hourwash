<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About Us | Hour Wash Laundry</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
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

        <!-- Header Nav -->
        <div class="flex items-center justify-between">
            <a href="{{ route('welcome') }}" class="btn-secondary text-xs">Back</a>
            <button id="theme-toggle" class="p-2 px-3 rounded-lg bg-white dark:dark:bg-zinc-800 text-slate-900 dark:text-zinc-100 border border-slate-200 dark:border-zinc-700 transition-all text-xs font-semibold flex items-center gap-1.5 shadow-sm cursor-pointer" title="Toggle Light/Dark Theme">
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

        <!-- Main Card -->
        <div class="bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-700/80 rounded-2xl p-6 sm:p-8 space-y-7 shadow-sm">
            <div class="text-center space-y-2 border-b border-slate-200 dark:border-zinc-700/80 pb-6">
                <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-16 h-16 rounded-full mx-auto bg-white p-1 border border-slate-200 dark:border-zinc-700 shadow-sm">
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">About HourWash</h1>
                <p class="text-xs font-medium text-slate-500 dark:text-zinc-400">Premium Laundry Services in Legazpi City</p>
            </div>

            <div class="space-y-6 text-sm leading-relaxed text-slate-700 dark:text-slate-300">
                <div class="space-y-2">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Our Story</h3>
                    <p class="text-slate-600 dark:text-zinc-300">
                        Located in the heart of Magallanes St., Orosite, Legazpi City, HourWash Laundry Shop was established with a singular mission: to redefine the laundry experience. We combine heavy-duty, high-efficiency equipment with a custom-built digital platform to deliver seamless drop-off, self-service, and delivery options for our community.
                    </p>
                </div>

                <div class="space-y-2">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Why Choose HourWash?</h3>
                    <ul class="list-disc pl-5 space-y-1.5 text-slate-600 dark:text-zinc-300">
                        <li><strong>Real-Time Digital Tracking:</strong> Know exactly when your clothes are washing, drying, or ready for pickup.</li>
                        <li><strong>Premium Equipment:</strong> Heavy-duty industrial washers and dryers that protect fabric fibers while delivering optimal cleanliness.</li>
                        <li><strong>Flexible Service Models:</strong> Choose between self-service washing or fully managed wash-and-fold services.</li>
                        <li><strong>Eco-Friendly Operations:</strong> We use energy-efficient appliances and high-quality eco-detergents to minimize environmental footprint.</li>
                    </ul>
                </div>

                <!-- Services Offered & Pricing -->
                <div class="space-y-3.5 border-t border-slate-200 dark:border-zinc-700/80 pt-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Services Offered &amp; Official Pricing</h3>
                        <span class="text-xs font-bold text-amber-600 dark:text-amber-400">*Detergent, Fabcon &amp; Bleach not included</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-700/80 shadow-xs hover:border-blue-500/40 transition">
                            <div class="flex items-center justify-between font-bold text-sm text-slate-900 dark:text-white mb-1.5">
                                <span>Wash Only</span>
                                <span class="text-blue-600 dark:text-blue-400 font-extrabold text-base">₱75.00</span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-slate-500 dark:text-zinc-400">
                                <span>Per load (max 7kg)</span>
                                <span class="font-semibold text-blue-600 dark:text-blue-400 font-mono">~35 mins</span>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-700/80 shadow-xs hover:border-blue-500/40 transition">
                            <div class="flex items-center justify-between font-bold text-sm text-slate-900 dark:text-white mb-1.5">
                                <span>Dry Only</span>
                                <span class="text-blue-600 dark:text-blue-400 font-extrabold text-base">₱75.00</span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-slate-500 dark:text-zinc-400">
                                <span>Per load (max 7kg)</span>
                                <span class="font-semibold text-blue-600 dark:text-blue-400 font-mono">~40mins</span>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-700/80 shadow-xs hover:border-blue-500/40 transition">
                            <div class="flex items-center justify-between font-bold text-sm text-slate-900 dark:text-white mb-1.5">
                                <span>Fold Only</span>
                                <span class="text-blue-600 dark:text-blue-400 font-extrabold text-base">₱50.00</span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-slate-500 dark:text-zinc-400">
                                <span>Per load (max 7kg)</span>
                                <span class="font-semibold text-blue-600 dark:text-blue-400 font-mono">~15mins</span>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-700/80 shadow-xs hover:border-blue-500/40 transition">
                            <div class="flex items-center justify-between font-bold text-sm text-slate-900 dark:text-white mb-1.5">
                                <span>Self-Service (Wash &amp; Dry)</span>
                                <span class="text-blue-600 dark:text-blue-400 font-extrabold text-base">₱150.00</span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-slate-500 dark:text-zinc-400">
                                <span>Per load (max 7kg)</span>
                                <span class="font-semibold text-blue-600 dark:text-blue-400 font-mono">~1hrs 15m</span>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-700/80 shadow-xs sm:col-span-2 hover:border-blue-500/40 transition">
                            <div class="flex items-center justify-between font-bold text-sm text-slate-900 dark:text-white mb-1.5">
                                <span>Full-Service (Wash, Dry &amp; Fold)</span>
                                <span class="text-blue-600 dark:text-blue-400 font-extrabold text-base">₱200.00</span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-slate-500 dark:text-zinc-400">
                                <span>Per load (max 7kg) — Complete drop-off care</span>
                                <span class="font-semibold text-blue-600 dark:text-blue-400 font-mono">~1hrs 30m</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact & Shop Details -->
                <div class="space-y-2.5 border-t border-slate-200 dark:border-zinc-700/80 pt-5">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Contact &amp; Shop Details</h3>
                    <p class="text-slate-600 dark:text-zinc-300 leading-relaxed text-xs sm:text-sm">
                        <strong>Address:</strong> Magallanes St., Orosite, Legazpi City, Albay, Philippines<br>
                        <strong>Store Hours:</strong> 7:30 AM – 6:00 PM (Monday – Sunday)<br>
                        <strong>Same-Day Order Cut-Off:</strong> 4:30 PM (Orders placed after 4:30 PM processed next morning)<br>
                        <strong>Support Helpline:</strong> Available via our customer dashboard and live AI assistant
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="pt-4 border-t border-slate-200 dark:border-zinc-800 text-center text-[11px] text-slate-500 dark:text-zinc-400 space-y-2.5">
            <div class="flex justify-center mb-1">
                <div class="w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center shadow-xs overflow-hidden p-0.5 border border-blue-500/30">
                    <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-full h-full object-cover rounded-full">
                </div>
            </div>
            <div class="flex items-center justify-center gap-x-1.5 sm:gap-x-3 text-[10px] sm:text-xs">
                <a href="{{ route('about') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">About Us</a>
                <span class="text-slate-300 dark:text-zinc-700">•</span>
                <a href="{{ route('developers') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Developers</a>
                <span class="text-slate-300 dark:text-zinc-700">•</span>
                <a href="{{ route('privacy') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Privacy Policy</a>
                <span class="text-slate-300 dark:text-zinc-700">•</span>
                <a href="{{ route('terms') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Terms &amp; Conditions</a>
            </div>
            <div>© {{ date('Y') }} A Web-Based Laundry Service Management System for Hour Wash Laundry Shop in Orosite, Legazpi City</div>
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
