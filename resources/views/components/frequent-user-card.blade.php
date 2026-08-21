@props(['user'])

@php
    $stamps = min(12, max(0, $user->stamps_count ?? 0));
    $rewards = $user->discount_rewards_available ?? 0;
    $cardsCompleted = $user->completed_cards_count ?? 0;
@endphp

<div class="relative bg-white dark:bg-[#18181B] border border-slate-200/80 dark:border-zinc-800 border-l-2 border-l-rose-500 rounded-lg p-4 sm:p-5 shadow-sm text-slate-900 dark:text-zinc-100 max-w-md w-full select-none overflow-hidden transition-all">
    <!-- Card Header -->
    <div class="flex items-center justify-between border-b border-slate-100 dark:border-zinc-800/80 pb-3 mb-3.5">
        <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-lg bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 flex items-center justify-center shrink-0">
                <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-5 h-5 object-contain">
            </div>
            <div>
                <h3 class="text-sm font-extrabold tracking-tight text-slate-900 dark:text-white uppercase leading-none font-sans flex items-center gap-1">
                    HOUR WASH
                </h3>
                <p class="text-[9.5px] font-bold tracking-widest text-slate-400 dark:text-zinc-500 uppercase mt-0.5">
                    FREQUENT USER CARD
                </p>
            </div>
        </div>
        <div class="text-right">
            <span class="block text-[8.5px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-widest">CLIENT NO.</span>
            <span class="font-mono font-extrabold text-xs text-slate-900 dark:text-zinc-200">#{{ sprintf('%04d', $user->id) }}</span>
        </div>
    </div>

    <!-- Client Name Field -->
    <div class="flex items-center justify-between text-xs mb-3.5 bg-slate-50 dark:bg-zinc-900/60 px-3 py-2 rounded-md border border-slate-200/60 dark:border-zinc-800">
        <span class="font-bold text-[10px] text-slate-400 dark:text-zinc-500 uppercase tracking-wider shrink-0">CLIENT'S NAME</span>
        <span class="font-bold text-slate-900 dark:text-white uppercase truncate ml-2 font-mono text-xs">{{ $user->name }}</span>
    </div>

    <!-- 12-Stamp Grid (3 rows x 4 columns) -->
    <div class="grid grid-cols-4 gap-2 mb-3.5">
        @for ($i = 1; $i <= 12; $i++)
            @php $isStamped = $i <= $stamps; @endphp
            <div class="h-11 rounded-md border flex flex-col items-center justify-center relative transition-all {{ $isStamped ? 'bg-rose-50 dark:bg-rose-950/40 border-rose-300 dark:border-rose-800 text-rose-600 dark:text-rose-400' : 'bg-slate-50/60 dark:bg-zinc-900/40 border-slate-200/80 dark:border-zinc-800 text-slate-300 dark:text-zinc-700' }}">
                @if ($isStamped)
                    <span class="text-[11px] font-extrabold tracking-wider uppercase font-mono flex items-center gap-0.5">
                        ✓ <span class="text-[8.5px]">#{{ $i }}</span>
                    </span>
                @else
                    <span class="text-[10px] font-bold font-mono">
                        {{ $i }}
                    </span>
                @endif
            </div>
        @endfor
    </div>

    <!-- Card Footer Status -->
    <div class="pt-2.5 border-t border-slate-100 dark:border-zinc-800/80 flex items-center justify-between text-xs">
        <div class="flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
            <span class="font-semibold text-[11px] text-slate-600 dark:text-slate-400">
                Progress: <span class="font-mono text-slate-900 dark:text-white font-extrabold">{{ $stamps }} / 12</span> Stamps
            </span>
        </div>
        @if ($rewards > 0)
            <span class="px-2 py-0.5 rounded bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 font-extrabold text-[10px] uppercase border border-emerald-200 dark:border-emerald-800/60">
                {{ $rewards }} Reward Ready (₱50 OFF)
            </span>
        @elseif ($cardsCompleted > 0)
            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400">
                {{ $cardsCompleted }} Card(s) Completed
            </span>
        @else
            <span class="text-[10px] text-slate-400 dark:text-zinc-500 font-medium">
                12 Stamps = ₱50.00 OFF
            </span>
        @endif
    </div>
</div>
