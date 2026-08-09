@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-white dark:bg-[#2C2C2E] border border-black/15 dark:border-white/15 text-slate-900 dark:text-[#F5F5F7] placeholder-slate-400 focus:border-[#007AFF] dark:focus:border-[#0A84FF] focus:ring-1 focus:ring-[#007AFF] rounded-xl px-4 py-2.5 shadow-sm transition text-xs sm:text-sm']) }}>
