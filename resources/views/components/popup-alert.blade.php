<!-- Reusable iOS Style Success & Error Popup Modal -->
@if(session('success'))
    <div id="pop-success-modal" class="fixed inset-0 bg-black/60 backdrop-blur-md z-50 flex items-center justify-center p-4 animate-fade-in">
        <div class="bg-white dark:bg-[#1C1C1E] max-w-sm w-full p-8 rounded-3xl text-center space-y-5 shadow-2xl border border-black/10 dark:border-white/10 transform transition-all scale-100">
            <!-- Blue Circle Icon with White Checkmark -->
            <div class="w-20 h-20 mx-auto rounded-full bg-[#007AFF] text-white flex items-center justify-center shadow-lg shadow-[#007AFF]/30">
                <svg class="w-10 h-10 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <div>
                <h3 class="text-2xl font-bold font-['Outfit'] text-[#007AFF] dark:text-[#0A84FF]">Success</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed font-sans">
                    {{ session('success') }}
                </p>
            </div>

            <button onclick="closePopSuccessModal()" class="w-full bg-[#007AFF] hover:bg-[#0062CC] text-white font-bold py-3.5 px-6 rounded-2xl text-xs shadow-md shadow-[#007AFF]/25 transition active:scale-95">
                Continue
            </button>
        </div>
    </div>
@endif

@if(session('error') || $errors->any())
    <div id="pop-error-modal" class="fixed inset-0 bg-black/60 backdrop-blur-md z-50 flex items-center justify-center p-4 animate-fade-in">
        <div class="bg-white dark:bg-[#1C1C1E] max-w-sm w-full p-8 rounded-3xl text-center space-y-5 shadow-2xl border border-black/10 dark:border-white/10 transform transition-all scale-100">
            <!-- Red Circle Icon with White Cross -->
            <div class="w-20 h-20 mx-auto rounded-full bg-[#FF3B30] text-white flex items-center justify-center shadow-lg shadow-[#FF3B30]/30">
                <svg class="w-10 h-10 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>

            <div>
                <h3 class="text-2xl font-bold font-['Outfit'] text-[#FF3B30]">Error</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed font-sans">
                    {{ session('error') ?? $errors->first() }}
                </p>
            </div>

            <button onclick="closePopErrorModal()" class="w-full bg-[#FF3B30] hover:bg-[#D70015] text-white font-bold py-3.5 px-6 rounded-2xl text-xs shadow-md shadow-[#FF3B30]/25 transition active:scale-95">
                Try again
            </button>
        </div>
    </div>
@endif

<!-- Dynamic JS Popup Container -->
<div id="dynamic-pop-modal-container"></div>

<script>
function closePopSuccessModal() {
    const el = document.getElementById('pop-success-modal');
    if (el) el.remove();
}

function closePopErrorModal() {
    const el = document.getElementById('pop-error-modal');
    if (el) el.remove();
}

window.showPopSuccess = function(message) {
    const container = document.getElementById('dynamic-pop-modal-container');
    if (!container) return;
    container.innerHTML = `
        <div id="pop-success-modal" class="fixed inset-0 bg-black/60 backdrop-blur-md z-50 flex items-center justify-center p-4 animate-fade-in">
            <div class="bg-white dark:bg-[#1C1C1E] max-w-sm w-full p-8 rounded-3xl text-center space-y-5 shadow-2xl border border-black/10 dark:border-white/10 transform transition-all scale-100">
                <div class="w-20 h-20 mx-auto rounded-full bg-[#007AFF] text-white flex items-center justify-center shadow-lg shadow-[#007AFF]/30">
                    <svg class="w-10 h-10 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold font-['Outfit'] text-[#007AFF] dark:text-[#0A84FF]">Success</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed font-sans">${message}</p>
                </div>
                <button onclick="closePopSuccessModal()" class="w-full bg-[#007AFF] hover:bg-[#0062CC] text-white font-bold py-3.5 px-6 rounded-2xl text-xs shadow-md shadow-[#007AFF]/25 transition active:scale-95">
                    Continue
                </button>
            </div>
        </div>
    `;
};

window.showPopError = function(message) {
    const container = document.getElementById('dynamic-pop-modal-container');
    if (!container) return;
    container.innerHTML = `
        <div id="pop-error-modal" class="fixed inset-0 bg-black/60 backdrop-blur-md z-50 flex items-center justify-center p-4 animate-fade-in">
            <div class="bg-white dark:bg-[#1C1C1E] max-w-sm w-full p-8 rounded-3xl text-center space-y-5 shadow-2xl border border-black/10 dark:border-white/10 transform transition-all scale-100">
                <div class="w-20 h-20 mx-auto rounded-full bg-[#FF3B30] text-white flex items-center justify-center shadow-lg shadow-[#FF3B30]/30">
                    <svg class="w-10 h-10 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold font-['Outfit'] text-[#FF3B30]">Error</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed font-sans">${message}</p>
                </div>
                <button onclick="closePopErrorModal()" class="w-full bg-[#FF3B30] hover:bg-[#D70015] text-white font-bold py-3.5 px-6 rounded-2xl text-xs shadow-md shadow-[#FF3B30]/25 transition active:scale-95">
                    Try again
                </button>
            </div>
        </div>
    `;
};
</script>
