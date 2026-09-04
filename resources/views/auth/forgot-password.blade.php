<x-guest-layout>
    <div class="app-card p-6 sm:p-7 space-y-6 shadow-xl border-t-4 border-t-blue-600 bg-white dark:bg-[#18181B] rounded-2xl relative overflow-hidden">
        
        <!-- Header & Badge -->
        <div class="space-y-2 border-b border-slate-200 dark:border-zinc-800 pb-4">
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-md bg-blue-600/15 text-blue-600 dark:text-blue-400 text-[10px] font-extrabold uppercase tracking-wider border border-blue-600/30">
                    PASSWORD RECOVERY
                </span>
                <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">Account Assistance</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                Forgot Your Password?
            </h2>
            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                No problem. Enter your registered email address below and we will send you an official password reset link.
            </p>
        </div>

        <!-- Session Status Alert -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Error Banner -->
        @if ($errors->any())
            <div class="p-4 rounded-xl bg-rose-500/15 border border-rose-500/30 text-rose-700 dark:text-rose-400 text-xs font-medium space-y-1.5 shadow-sm">
                <div class="flex items-center gap-2 font-bold text-rose-800 dark:text-rose-300">
                    <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Please fix the following validation errors:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-[11.5px] pl-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">
                    Email Address:
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-zinc-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="e.g. name@example.com"
                        class="w-full !pl-10 pr-3.5 py-2.5 bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-zinc-100 placeholder-slate-400 dark:placeholder-zinc-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition shadow-sm"
                        style="padding-left: 2.5rem !important;">
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider transition-all shadow-md hover:shadow-blue-600/25 flex items-center justify-center gap-2 cursor-pointer group mt-2">
                <svg class="w-4 h-4 text-white/90 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>Email Password Reset Link</span>
            </button>
        </form>

        <!-- Footer Link -->
        <div class="border-t border-slate-200 dark:border-zinc-800 pt-4 text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Return to Account Login</span>
            </a>
        </div>

    </div>
</x-guest-layout>
