<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-2.5 bg-[#007AFF] hover:bg-[#0062CC] dark:bg-[#0A84FF] dark:hover:bg-[#0071E3] text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md hover:shadow-lg transition duration-150 active:scale-95']) }}>
    {{ $slot }}
</button>
