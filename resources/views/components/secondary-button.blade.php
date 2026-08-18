<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 rounded-md font-semibold text-xs text-slate-700 dark:text-zinc-200 uppercase tracking-wider shadow-sm hover:bg-slate-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-1 focus:ring-blue-600 focus:ring-offset-1 dark:focus:ring-offset-zinc-900 disabled:opacity-50 transition duration-150']) }}>
    {{ $slot }}
</button>
