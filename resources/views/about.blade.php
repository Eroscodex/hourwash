<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About Us | Hour Wash Laundry</title>
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
            <a href="{{ route('welcome') }}" class="btn-ios-secondary text-xs">Back</a>
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
        <div class="bg-white dark:bg-[#1C1C1E] border border-black/10 dark:border-white/15 rounded-2xl p-6 sm:p-8 space-y-6 shadow-xl">
            <div class="text-center space-y-2 border-b border-black/10 dark:border-white/10 pb-5">
                <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-16 h-16 rounded-full mx-auto bg-white p-1 border border-black/10">
                <h1 class="text-2xl font-bold font-['Outfit'] text-slate-900 dark:text-white">About HourWash</h1>
                <p class="text-xs text-slate-500">Premium Laundry Services in Legazpi City</p>
            </div>

            <div class="space-y-6 text-sm leading-relaxed text-slate-700 dark:text-slate-300">
                <div class="space-y-2">
                    <h3 class="text-base font-bold font-['Outfit'] text-slate-900 dark:text-white">Our Story</h3>
                    <p>
                        Located in the heart of Magallanes St., Orosite, Legazpi City, HourWash Laundry Shop was established with a singular mission: to redefine the laundry experience. We combine heavy-duty, high-efficiency equipment with a custom-built digital platform to deliver seamless drop-off, self-service, and delivery options for our community.
                    </p>
                </div>

                <div class="space-y-2">
                    <h3 class="text-base font-bold font-['Outfit'] text-slate-900 dark:text-white">Why Choose HourWash?</h3>
                    <ul class="list-disc pl-5 space-y-1.5">
                        <li><strong>Real-Time Digital Tracking:</strong> Know exactly when your clothes are washing, drying, or ready for pickup.</li>
                        <li><strong>Premium Equipment:</strong> Heavy-duty industrial washers and dryers that protect fabric fibers while delivering optimal cleanliness.</li>
                        <li><strong>Flexible Service Models:</strong> Choose between self-service washing or fully managed wash-and-fold services.</li>
                        <li><strong>Eco-Friendly Operations:</strong> We use energy-efficient appliances and high-quality eco-detergents to minimize environmental footprint.</li>
                    </ul>
                </div>

                <div class="space-y-2 border-t border-black/10 dark:border-white/10 pt-4">
                    <h3 class="text-base font-bold font-['Outfit'] text-slate-900 dark:text-white">Contact & Shop Details</h3>
                    <p>
                        <strong>Address:</strong> Magallanes St., Orosite, Legazpi City, Albay, Philippines<br>
                        <strong>Store Hours:</strong> 7:00 AM – 6:00 PM Daily<br>
                        <strong>Support Helpline:</strong> Available via our customer dashboard and live AI assistant
                    </p>
                </div>
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
