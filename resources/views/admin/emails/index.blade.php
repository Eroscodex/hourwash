<x-app-layout>
    <div class="space-y-3.5">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white">
                    Live Email Customer Outbox
                </h1>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                    Automated email notifications generated & dispatched to customer email addresses
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="px-3 py-1.5 rounded-md bg-blue-600/15 text-blue-600 dark:text-blue-400 text-xs font-extrabold tracking-wider uppercase border border-blue-600/30">
                    {{ $totalDispatched }} Emails Dispatched
                </span>

                <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'confirm-clear-emails')" class="px-3 py-1.5 rounded-md bg-red-600 hover:bg-red-700 text-white text-xs font-extrabold border border-red-700 shadow-sm transition cursor-pointer">
                    Clear All Email History
                </button>

                <x-modal name="confirm-clear-emails" maxWidth="sm">
                    <div class="p-5 bg-white dark:bg-[#141417] text-slate-900 dark:text-zinc-100 space-y-3.5 rounded-lg text-left">
                        <h2 class="text-sm font-bold text-rose-600 dark:text-rose-400">Clear All Email History?</h2>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                            Are you sure you want to delete all email notification logs permanently?
                        </p>
                        <div class="pt-3 flex items-center justify-end gap-2.5 border-t border-slate-200 dark:border-zinc-800">
                            <button type="button" x-on:click="$dispatch('close')" class="btn-secondary text-xs py-1.5 px-3">
                                Cancel
                            </button>
                            <form method="POST" action="{{ route('admin.emails.clearAll') }}">
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

        <div class="space-y-3">
            @forelse($emailLogs as $email)
                <div class="app-card p-3 sm:p-3.5 space-y-2.5 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 dark:border-zinc-700 pb-2">
                        <div class="flex items-center gap-2 text-xs">
                            <span class="font-bold text-slate-700 dark:text-slate-300">To:</span>
                            <span class="font-mono font-bold text-blue-600 dark:text-blue-400">
                                {{ $email->recipient }}
                            </span>
                            <span class="ml-1 px-2 py-0.5 rounded text-[9.5px] font-extrabold uppercase tracking-wider @if($email->status === 'sent') bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 @elseif($email->status === 'failed') bg-rose-500/15 text-rose-600 dark:text-rose-400 border border-rose-500/30 @else bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/30 @endif">
                                {{ strtoupper($email->status) }}
                            </span>
                        </div>

                        <span class="text-[10.5px] font-medium text-slate-500 dark:text-slate-400 font-mono">
                            {{ $email->created_at->format('M d, Y h:i A') }}
                        </span>
                    </div>

                    <div>
                        <span class="text-xs font-bold text-slate-900 dark:text-slate-100 block mb-1">
                            Subject: {{ $email->subject }}
                        </span>
                        <div class="p-2.5 rounded-md bg-emerald-100/90 dark:bg-emerald-950/40 text-emerald-950 dark:text-emerald-100 font-mono text-[11px] leading-relaxed break-words border border-emerald-300 dark:border-emerald-700/50 shadow-sm">
                            {{ $email->body }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="app-card p-6 text-center space-y-2">
                    <h3 class="text-xs font-bold text-slate-900 dark:text-white">No Email Outbox Records</h3>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">
                        Email notifications sent to customers will automatically appear here.
                    </p>
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>
