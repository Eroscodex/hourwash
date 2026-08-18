<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs uppercase tracking-wider rounded-md shadow-sm transition duration-150 active:scale-[0.98]']) }}>
    {{ $slot }}
</button>
