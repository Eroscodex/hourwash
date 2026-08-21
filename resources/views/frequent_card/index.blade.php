<x-app-layout>
    <div class="space-y-6 sm:space-y-8">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white">
                    Frequent User Card & Loyalty Rewards
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Collect 12 stamps on your digital loyalty card to unlock an instant ₱50.00 OFF discount on your next laundry booking.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('laundry.create') }}" class="btn-primary py-2 px-4 text-xs font-bold flex items-center gap-1.5 shadow-sm">
                    + Book New Order
                </a>
            </div>
        </div>

        <!-- Main Frequent User Card & Stats Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            <!-- Pink Frequent User Card Component -->
            <div class="lg:col-span-1 flex justify-center lg:justify-start">
                <x-frequent-user-card :user="auth()->user()" />
            </div>

            <!-- Loyalty Status & How It Works -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Loyalty Rewards Summary Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="p-4 rounded-lg bg-white dark:bg-[#18181B] border border-slate-200/80 dark:border-zinc-800 border-l-2 border-l-pink-500 flex flex-col justify-between shadow-sm">
                        <span class="text-[10px] font-extrabold text-pink-600 dark:text-pink-400 uppercase tracking-wider block">
                            ACTIVE STAMPS
                        </span>
                        <span class="text-2xl font-extrabold text-slate-900 dark:text-white font-mono mt-1">
                            {{ auth()->user()->stamps_count ?? 0 }} <span class="text-xs text-slate-400 dark:text-zinc-500 font-sans font-normal">/ 12</span>
                        </span>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                            {{ 12 - (auth()->user()->stamps_count ?? 0) }} more stamp(s) for next reward
                        </p>
                    </div>

                    <div class="p-4 rounded-lg bg-white dark:bg-[#18181B] border border-slate-200/80 dark:border-zinc-800 border-l-2 border-l-emerald-500 flex flex-col justify-between shadow-sm">
                        <span class="text-[10px] font-extrabold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider block">
                            DISCOUNTS AVAILABLE
                        </span>
                        <span class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 font-mono mt-1">
                            {{ auth()->user()->discount_rewards_available ?? 0 }}
                        </span>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                            Ready to use at booking checkout
                        </p>
                    </div>

                    <div class="p-4 rounded-lg bg-white dark:bg-[#18181B] border border-slate-200/80 dark:border-zinc-800 border-l-2 border-l-blue-500 flex flex-col justify-between shadow-sm">
                        <span class="text-[10px] font-extrabold text-blue-600 dark:text-blue-400 uppercase tracking-wider block">
                            CARDS COMPLETED
                        </span>
                        <span class="text-2xl font-extrabold text-slate-900 dark:text-white font-mono mt-1">
                            {{ auth()->user()->completed_cards_count ?? 0 }}
                        </span>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                            Lifetime completed 12-stamp cards
                        </p>
                    </div>
                </div>

                <!-- Step-by-Step Explanation Guide -->
                <div class="app-card p-5 space-y-4">
                    <h3 class="text-sm font-extrabold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2 border-b border-slate-200 dark:border-zinc-800 pb-3">
                        <svg class="w-4 h-4 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        How Does The Frequent User Stamp Card Work?
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                        <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-800 space-y-1.5">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-pink-600 text-white font-extrabold flex items-center justify-center text-xs shrink-0">1</span>
                                <span class="font-bold text-slate-900 dark:text-white">Book & Complete Laundry</span>
                            </div>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed text-[11px] pl-8">
                                Every time you place an order and your laundry status is marked <strong>Completed</strong> by store staff, you automatically earn <strong>1 Stamp</strong> on your card.
                            </p>
                        </div>

                        <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-800 space-y-1.5">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-pink-600 text-white font-extrabold flex items-center justify-center text-xs shrink-0">2</span>
                                <span class="font-bold text-slate-900 dark:text-white">Collect 12 Stamps</span>
                            </div>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed text-[11px] pl-8">
                                Watch your card fill up with 12 stamps! Track live progress right here on your personal Frequent User Card.
                            </p>
                        </div>

                        <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-800 space-y-1.5">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-emerald-600 text-white font-extrabold flex items-center justify-center text-xs shrink-0">3</span>
                                <span class="font-bold text-slate-900 dark:text-white">Unlock ₱50.00 OFF Reward</span>
                            </div>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed text-[11px] pl-8">
                                Completing 12 stamps instantly unlocks a <strong>₱50.00 OFF Loyalty Reward Discount</strong> for your next booking.
                            </p>
                        </div>

                        <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-800 space-y-1.5">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-blue-600 text-white font-extrabold flex items-center justify-center text-xs shrink-0">4</span>
                                <span class="font-bold text-slate-900 dark:text-white">Redeem & Start Fresh</span>
                            </div>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed text-[11px] pl-8">
                                Check <code>Apply -₱50.00 OFF</code> at checkout to redeem. A new 12-stamp card will automatically open!
                            </p>
                        </div>
                    </div>

                    @if((auth()->user()->discount_rewards_available ?? 0) > 0)
                        <div class="mt-4 p-4 rounded-lg bg-emerald-500/15 border border-emerald-500/30 flex flex-col sm:flex-row items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">🎉</span>
                                <div>
                                    <span class="text-xs font-extrabold text-emerald-700 dark:text-emerald-300 block">
                                        Congratulations! You have {{ auth()->user()->discount_rewards_available }} Discount Reward(s) Unlocked!
                                    </span>
                                    <span class="text-[11px] text-emerald-800 dark:text-emerald-400">
                                        Book a new laundry order now and apply your ₱50.00 OFF discount at checkout.
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('laundry.create') }}" class="btn-primary bg-emerald-600 hover:bg-emerald-700 text-xs py-2 px-4 whitespace-nowrap shrink-0">
                                Redeem ₱50.00 OFF Now →
                            </a>
                        </div>
                    @endif
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
