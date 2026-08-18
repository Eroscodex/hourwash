<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white font-semibold text-xs uppercase tracking-wider rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-rose-600 focus:ring-offset-1 dark:focus:ring-offset-zinc-900 disabled:opacity-50 transition duration-150']) }}>
    {{ $slot }}
</button>
