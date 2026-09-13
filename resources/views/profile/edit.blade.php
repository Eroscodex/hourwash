<x-app-layout>

    <div class="max-w-5xl mx-auto space-y-3.5 sm:space-y-4">

        <!-- Page Header -->
        <div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white">
                Account & Profile Settings
            </h1>
            <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                Manage your account credentials, personal information, contact address, and security credentials.
            </p>
        </div>

        <!-- User Profile Summary Header Card (Compact Banner) -->
        <div class="bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-800 rounded-lg p-3 sm:px-4 shadow-sm flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-600/10 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400 flex items-center justify-center font-extrabold text-base border border-blue-600/20 shrink-0">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">{{ Auth::user()->name }}</h2>
                        <span class="px-2 py-0.5 rounded text-[9.5px] font-bold uppercase tracking-wider bg-blue-500/10 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400 border border-blue-500/20">
                            {{ ucfirst(Auth::user()->role ?? 'user') }}
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-zinc-400 font-medium">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <div class="text-[11px] text-slate-500 dark:text-zinc-400 font-mono text-right hidden sm:block">
                <span>Member Since: {{ Auth::user()->created_at ? Auth::user()->created_at->format('M Y') : '2026' }}</span>
            </div>
        </div>

        <!-- 2-COLUMN LAYOUT ON DESKTOP & LAPTOP (lg:grid lg:grid-cols-12) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 sm:gap-4 lg:gap-5 items-start">

            <!-- LEFT COLUMN (7 cols): Personal Information & Address -->
            <div class="lg:col-span-7 space-y-3.5">
                <div class="bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-800 rounded-lg shadow-sm overflow-hidden">
                    <div class="px-3.5 py-2.5 border-b border-slate-200 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/30 flex items-center justify-between">
                        <div>
                            <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Personal Information & Address</h3>
                            <p class="text-[10.5px] text-slate-500 dark:text-zinc-400 mt-0.5">Update display name, contact phone number, and address.</p>
                        </div>
                        <span class="text-blue-600 dark:text-blue-400 font-bold text-[10px] uppercase tracking-wider hidden sm:inline">Profile Data</span>
                    </div>

                    <div class="p-3.5 sm:p-4">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN (5 cols): Security & Danger Zone -->
            <div class="lg:col-span-5 space-y-3.5">

                <!-- Security & Password -->
                <div class="bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-800 rounded-lg shadow-sm overflow-hidden">
                    <div class="px-3.5 py-2.5 border-b border-slate-200 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/30 flex items-center justify-between">
                        <div>
                            <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Security & Password</h3>
                            <p class="text-[10.5px] text-slate-500 dark:text-zinc-400 mt-0.5">Ensure account protection with a random password.</p>
                        </div>
                        <span class="text-blue-600 dark:text-blue-400 font-bold text-[10px] uppercase tracking-wider hidden sm:inline">Security</span>
                    </div>

                    <div class="p-3.5 sm:p-4">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="bg-white dark:bg-[#141417] border border-rose-500/30 dark:border-rose-500/20 rounded-lg shadow-sm overflow-hidden">
                    <div class="px-3.5 py-2.5 border-b border-rose-500/20 bg-rose-500/5 dark:bg-rose-500/10 flex items-center justify-between">
                        <div>
                            <h3 class="text-xs font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wider">Danger Zone</h3>
                            <p class="text-[10.5px] text-rose-600/80 dark:text-rose-300/80 mt-0.5">Permanently remove your account and data.</p>
                        </div>
                        <span class="text-rose-600 dark:text-rose-400 font-bold text-[10px] uppercase tracking-wider hidden sm:inline">Action</span>
                    </div>

                    <div class="p-3">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>

            </div>

        </div>

    </div>

</x-app-layout>
