<x-app-layout>
    <div class="space-y-3.5">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white">
                    Live SMS Customer Outbox
                </h1>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                    Automated SMS text messages generated & dispatched to customer phone numbers
                </p>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 shrink-0">
                <span class="px-3 py-1.5 rounded-md bg-blue-600/15 text-blue-600 dark:text-blue-400 text-xs font-extrabold tracking-wider uppercase border border-blue-600/30">
                    {{ $totalDispatched }} SMS Dispatched
                </span>

                <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'confirm-clear-sms')" class="px-3 py-1.5 rounded-md bg-rose-500/20 hover:bg-rose-500/30 text-rose-600 dark:text-rose-400 text-xs font-bold border border-rose-500/30 transition cursor-pointer">
                    Clear All SMS History
                </button>

                <x-modal name="confirm-clear-sms" maxWidth="sm">
                    <div class="p-5 bg-white dark:bg-[#141417] text-slate-900 dark:text-zinc-100 space-y-3.5 rounded-lg text-left">
                        <h2 class="text-sm font-bold text-rose-600 dark:text-rose-400">Clear All SMS History?</h2>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                            Are you sure you want to delete all SMS notification logs permanently?
                        </p>
                        <div class="pt-3 flex items-center justify-end gap-2.5 border-t border-slate-200 dark:border-zinc-800">
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
            <div class="p-3 rounded-md bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-3 rounded-md bg-rose-500/10 border border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs font-bold">
                {{ session('error') }}
            </div>
        @endif

        <!-- SMS Outbox Logs -->
        <div class="space-y-3">
            @forelse($smsLogs as $sms)
                <div class="app-card p-3 sm:p-3.5 space-y-2.5 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 dark:border-zinc-700 pb-2">
                        <div class="flex items-center gap-2 text-xs">
                            <span class="font-bold text-slate-700 dark:text-slate-300">Phone:</span>
                            <span class="font-mono font-bold text-blue-600 dark:text-blue-400">
                                {{ $sms->phone }}
                            </span>
                            <span class="ml-1 px-2 py-0.5 rounded text-[9.5px] font-extrabold uppercase tracking-wider @if(in_array($sms->status, ['sent', 'dispatched', 'delivered'])) bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 @elseif($sms->status === 'failed') bg-rose-500/15 text-rose-600 dark:text-rose-400 border border-rose-500/30 @else bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/30 @endif">
                                {{ strtoupper($sms->status) }}
                            </span>
                        </div>

                        <span class="text-[10.5px] font-medium text-slate-500 dark:text-slate-400 font-mono">
                            {{ $sms->created_at->format('M d, Y h:i A') }}
                        </span>
                    </div>

                    <div class="p-2.5 rounded-md bg-emerald-100/90 dark:bg-emerald-950/40 text-emerald-950 dark:text-emerald-100 font-mono text-[11px] leading-relaxed break-words border border-emerald-300 dark:border-emerald-700/50 shadow-sm">
                        {{ $sms->message }}
                    </div>
                </div>
            @empty
                <div class="app-card p-6 text-center space-y-2">
                    <h3 class="text-xs font-bold text-slate-900 dark:text-white">No SMS Outbox Records</h3>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">
                        SMS notifications sent to customers when order status changes will automatically appear here.
                    </p>
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>
