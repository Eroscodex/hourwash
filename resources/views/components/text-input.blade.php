@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-white dark:bg-[#18181B] border border-slate-300 dark:border-zinc-700 text-slate-900 dark:text-zinc-100 placeholder-slate-400 dark:placeholder-zinc-500 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 rounded-md px-3.5 py-2 shadow-sm transition text-xs sm:text-sm']) }}>
