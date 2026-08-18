@if(session('success'))
    <div id="pop-success-modal" class="fixed inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-fade-in">
        <div class="bg-white dark:bg-[#141417] max-w-sm w-full p-6 rounded-lg text-center space-y-4 shadow-xl border border-slate-200 dark:border-zinc-800">
            
            <div class="w-16 h-16 mx-auto rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-500/20">
                <svg class="w-8 h-8 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Success</h3>
                <p class="text-xs text-slate-600 dark:text-zinc-400 mt-1.5 leading-relaxed font-sans">
                    {{ session('success') }}
                </p>
            </div>

            <button onclick="closePopSuccessModal()" class="btn-primary w-full py-2.5">
                Continue
            </button>
        </div>
    </div>
@endif

@if(session('error') || $errors->any())
    <div id="pop-error-modal" class="fixed inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-fade-in">
        <div class="bg-white dark:bg-[#141417] max-w-sm w-full p-6 rounded-lg text-center space-y-4 shadow-xl border border-slate-200 dark:border-zinc-800">
            
            <div class="w-16 h-16 mx-auto rounded-full bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center border border-rose-500/20">
                <svg class="w-8 h-8 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>

            <div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Error</h3>
                <p class="text-xs text-slate-600 dark:text-zinc-400 mt-1.5 leading-relaxed font-sans">
                    {{ session('error') ?? $errors->first() }}
                </p>
            </div>

            <button onclick="closePopErrorModal()" class="btn-danger w-full py-2.5">
                Try again
            </button>
        </div>
    </div>
@endif

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
        <div id="pop-success-modal" class="fixed inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-fade-in">
            <div class="bg-white dark:bg-[#141417] max-w-sm w-full p-6 rounded-lg text-center space-y-4 shadow-xl border border-slate-200 dark:border-zinc-800">
                <div class="w-16 h-16 mx-auto rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-500/20">
                    <svg class="w-8 h-8 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Success</h3>
                    <p class="text-xs text-slate-600 dark:text-zinc-400 mt-1.5 leading-relaxed font-sans">${message}</p>
                </div>
                <button onclick="closePopSuccessModal()" class="btn-primary w-full py-2.5">
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
        <div id="pop-error-modal" class="fixed inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-fade-in">
            <div class="bg-white dark:bg-[#141417] max-w-sm w-full p-6 rounded-lg text-center space-y-4 shadow-xl border border-slate-200 dark:border-zinc-800">
                <div class="w-16 h-16 mx-auto rounded-full bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center border border-rose-500/20">
                    <svg class="w-8 h-8 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Error</h3>
                    <p class="text-xs text-slate-600 dark:text-zinc-400 mt-1.5 leading-relaxed font-sans">${message}</p>
                </div>
                <button onclick="closePopErrorModal()" class="btn-danger w-full py-2.5">
                    Try again
                </button>
            </div>
        </div>
    `;
};
</script>
