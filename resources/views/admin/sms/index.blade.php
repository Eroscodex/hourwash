<x-app-layout>
    <div class="space-y-6 sm:space-y-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">
                    Live SMS Customer Outbox
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Automated SMS text messages generated & dispatched to customer phone numbers
                </p>
            </div>

            <div class="flex items-center gap-3">
                <span class="px-3.5 py-1.5 rounded-full bg-[#007AFF]/15 text-[#007AFF] dark:text-[#0A84FF] text-xs font-extrabold tracking-wider uppercase border border-[#007AFF]/30">
                    {{ $totalDispatched }} SMS Dispatched
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs font-bold">
                {{ session('error') }}
            </div>
        @endif

        <!-- Quick Test SMS Card -->
        <div class="app-card p-5 bg-[#1C1C1E] border border-white/10 rounded-2xl space-y-4">
            <h2 class="text-sm font-bold text-white flex items-center gap-2">
                <span>💬</span> Direct Test SMS Dispatcher
            </h2>
            <form action="{{ route('admin.sms.send') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                @csrf
                <div class="sm:col-span-4">
                    <label class="block text-[11px] font-semibold text-slate-400 mb-1">Phone Number</label>
                    <input type="text" name="phone" value="09100317744" required placeholder="09100317744"
                           class="w-full px-3 py-2 text-xs rounded-xl bg-black/50 border border-white/10 text-white focus:border-[#007AFF] focus:outline-none">
                </div>
                <div class="sm:col-span-6">
                    <label class="block text-[11px] font-semibold text-slate-400 mb-1">Message Body</label>
                    <input type="text" name="message" value="HourWash Test Alert: SMS live gateway test is working!" required
                           class="w-full px-3 py-2 text-xs rounded-xl bg-black/50 border border-white/10 text-white focus:border-[#007AFF] focus:outline-none">
                </div>
                <div class="sm:col-span-2">
                    <button type="submit" class="w-full py-2 px-4 rounded-xl bg-[#007AFF] hover:bg-[#0056b3] text-white font-bold text-xs transition shadow-sm">
                        Send Test SMS
                    </button>
                </div>
            </form>
        </div>

        <!-- SMS Outbox Logs -->
        <div class="space-y-4">
            @forelse($smsLogs as $sms)
                <div class="app-card p-4 sm:p-5 space-y-3 bg-[#1C1C1E] border border-black/10 dark:border-white/10 rounded-2xl shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-black/5 dark:border-white/10 pb-2.5">
                        <div class="flex items-center gap-2 text-xs">
                            <span class="font-bold text-slate-400">Phone:</span>
                            <span class="font-mono font-bold text-[#007AFF] dark:text-[#0A84FF]">
                                {{ $sms->phone }}
                            </span>
                            <span class="ml-2 px-2.5 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider
                                @if(in_array($sms->status, ['sent', 'dispatched', 'delivered'])) bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30
                                @elseif($sms->status === 'failed') bg-rose-500/15 text-rose-600 dark:text-rose-400 border border-rose-500/30
                                @else bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/30 @endif">
                                {{ strtoupper($sms->status) }}
                            </span>
                        </div>

                        <span class="text-[11px] font-medium text-slate-400 dark:text-slate-400 font-mono">
                            {{ $sms->created_at->format('M d, Y h:i A') }}
                        </span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-black/40 text-slate-200 font-mono text-xs leading-relaxed break-words border border-white/5">
                        {{ $sms->message }}
                    </div>
                </div>
            @empty
                <div class="app-card p-8 text-center space-y-3">
                    <div class="w-12 h-12 rounded-full bg-[#007AFF]/10 text-[#007AFF] mx-auto flex items-center justify-center text-xl">
                        📱
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">No SMS Outbox Records</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        SMS notifications sent to customers when order status changes will automatically appear here.
                    </p>
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>
