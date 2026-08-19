<x-app-layout>
    <div class="space-y-6 sm:space-y-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white">
                    Live SMS Customer Outbox
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Automated SMS text messages generated & dispatched to customer phone numbers
                </p>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 shrink-0">
                <span class="px-3.5 py-1.5 rounded-md bg-blue-600/15 text-blue-600 dark:text-blue-400 text-xs font-extrabold tracking-wider uppercase border border-blue-600/30">
                    {{ $totalDispatched }} SMS Dispatched
                </span>

                    <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'confirm-clear-sms')" class="px-3.5 py-1.5 rounded-lg bg-rose-500/20 hover:bg-rose-500/30 text-rose-600 dark:text-rose-400 text-xs font-bold border border-rose-500/30 transition">
                        Clear All SMS History
                    </button>

                    <x-modal name="confirm-clear-sms" maxWidth="sm">
                        <div class="p-6 bg-white dark:bg-[#141417] text-slate-900 dark:text-zinc-100 space-y-4 rounded-lg text-left">
                            <h2 class="text-base font-bold text-rose-600 dark:text-rose-400">Clear All SMS History?</h2>
                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                                Are you sure you want to delete all SMS notification logs permanently?
                            </p>
                            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200 dark:border-zinc-800">
                                <button type="button" x-on:click="$dispatch('close')" class="btn-secondary text-xs py-1.5 px-3">
                                    Cancel
                                </button>
                                <form method="POST" action="{{ route('admin.sms.clearAll') }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger text-xs py-1.5 px-3">
                                        Clear History
                                    </button>
                                </form>
                            </div>
                        </div>
                    </x-modal>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-lg bg-rose-500/10 border border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs font-bold">
                {{ session('error') }}
            </div>
        @endif



        <!-- SMS Outbox Logs -->
        <div class="space-y-4">
            @forelse($smsLogs as $sms)
                <div class="app-card p-4 sm:p-5 space-y-3 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 dark:border-zinc-700 pb-2.5">
                        <div class="flex items-center gap-2 text-xs">
                            <span class="font-bold text-slate-700 dark:text-slate-300">Phone:</span>
                            <span class="font-mono font-bold text-blue-600 dark:text-blue-400">
                                {{ $sms->phone }}
                            </span>
                            <span class="ml-2 px-2.5 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider @if(in_array($sms->status, ['sent', 'dispatched', 'delivered'])) bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 @elseif($sms->status === 'failed') bg-rose-500/15 text-rose-600 dark:text-rose-400 border border-rose-500/30 @else bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/30 @endif">
                                {{ strtoupper($sms->status) }}
                            </span>
                        </div>

                        <span class="text-[11px] font-medium text-slate-500 dark:text-slate-400 font-mono">
                            {{ $sms->created_at->format('M d, Y h:i A') }}
                        </span>
                    </div>

                    <div class="p-3.5 rounded-lg bg-emerald-100/90 dark:bg-emerald-950/40 text-emerald-950 dark:text-emerald-100 font-mono text-xs leading-relaxed break-words border border-emerald-300 dark:border-emerald-700/50 shadow-sm">
                        {{ $sms->message }}
                    </div>
                </div>
            @empty
                <div class="app-card p-8 text-center space-y-3">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">No SMS Outbox Records</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        SMS notifications sent to customers when order status changes will automatically appear here.
                    </p>
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>
