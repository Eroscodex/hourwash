@props(['user'])

@php
    $stamps = min(12, max(0, $user->stamps_count ?? 0));
    $rewards = $user->discount_rewards_available ?? 0;
    $cardsCompleted = $user->completed_cards_count ?? 0;
@endphp

<div class="relative bg-pink-100 dark:bg-[#1f1216] border-2 border-pink-400/80 dark:border-pink-800/80 rounded-xl p-4 sm:p-5 shadow-sm text-pink-950 dark:text-pink-100 max-w-md w-full select-none overflow-hidden">
    <!-- Card Header -->
    <div class="flex items-center justify-between border-b-2 border-pink-300 dark:border-pink-900/60 pb-3 mb-3">
        <div class="flex items-center gap-2.5">
            <div class="w-10 h-10 rounded-full bg-white dark:bg-pink-900/50 p-1 border-2 border-pink-400 dark:border-pink-700 flex items-center justify-center shrink-0 shadow-sm">
                <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-7 h-7 object-contain">
            </div>
            <div>
                <h3 class="text-base font-extrabold tracking-tight text-pink-950 dark:text-pink-100 uppercase leading-none font-sans inline-flex items-center gap-0.5">
                    H<span class="inline-flex items-center justify-center text-pink-700 dark:text-pink-300 mx-[0.5px]"><svg class="w-[0.85em] h-[0.85em] inline-block -mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 3"/></svg></span>UR WASH
                </h3>
                <p class="text-[10px] font-black tracking-widest text-pink-800 dark:text-pink-300 uppercase mt-0.5">
                    FREQUENT USER CARD
                </p>
            </div>
        </div>
        <div class="text-right">
            <span class="block text-[9px] font-bold text-pink-700 dark:text-pink-400 uppercase tracking-wider">CLIENT NO.</span>
            <span class="font-mono font-extrabold text-xs text-pink-900 dark:text-pink-200">#{{ sprintf('%04d', $user->id) }}</span>
        </div>
    </div>

    <!-- Client Name Field -->
    <div class="flex items-center justify-between text-xs mb-3.5 bg-white/70 dark:bg-pink-950/50 px-3 py-1.5 rounded-md border border-pink-300/60 dark:border-pink-900/60">
        <span class="font-bold text-[11px] text-pink-800 dark:text-pink-300 uppercase shrink-0">CLIENT'S NAME:</span>
        <span class="font-black text-pink-950 dark:text-white uppercase truncate ml-2 font-mono text-xs">{{ $user->name }}</span>
    </div>

    <!-- 12-Stamp Grid (3 rows x 4 columns) -->
    <div class="grid grid-cols-4 gap-2 mb-3.5">
        @for ($i = 1; $i <= 12; $i++)
            @php $isStamped = $i <= $stamps; @endphp
            <div class="h-12 rounded-lg border-2 flex flex-col items-center justify-center relative transition-all {{ $isStamped ? 'bg-pink-500/20 border-pink-600 dark:bg-pink-600/30 dark:border-pink-500 shadow-sm' : 'bg-white/60 dark:bg-pink-950/20 border-pink-300/70 dark:border-pink-900/60' }}">
                @if ($isStamped)
                    <span class="text-pink-700 dark:text-pink-300 text-xs font-black rotate-[-12deg] tracking-wider uppercase font-mono">
                        ✓ STAMP
                    </span>
                    <span class="text-[8px] font-bold text-pink-800 dark:text-pink-300 opacity-80 leading-none">
                        #{{ $i }}
                    </span>
                @else
                    <span class="text-[11px] font-extrabold text-pink-300 dark:text-pink-800 font-mono">
                        {{ $i }}
                    </span>
                @endif
            </div>
        @endfor
    </div>

    <!-- Card Footer Status -->
    <div class="pt-2 border-t border-pink-300/60 dark:border-pink-900/60 flex items-center justify-between text-xs">
        <div class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-pink-600 animate-pulse"></span>
            <span class="font-bold text-[11px] text-pink-900 dark:text-pink-200">
                Progress: <span class="font-mono text-pink-700 dark:text-pink-300 font-black">{{ $stamps }} / 12</span> Stamps
            </span>
        </div>
        @if ($rewards > 0)
            <span class="px-2 py-0.5 rounded bg-emerald-600 text-white font-extrabold text-[10px] uppercase shadow-xs">
                {{ $rewards }} Reward Available (₱50 OFF)
            </span>
        @elseif ($cardsCompleted > 0)
            <span class="text-[10px] font-bold text-pink-800 dark:text-pink-300">
                {{ $cardsCompleted }} Card(s) Completed
            </span>
        @else
            <span class="text-[10px] text-pink-700 dark:text-pink-400 font-medium">
                12 Stamps = ₱50.00 OFF
            </span>
        @endif
    </div>
</div>
