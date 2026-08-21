<x-app-layout>

    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">QR Code Scan Logs</h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">Real-time audit log of customer, staff, and rider QR code scans across order tracking & verification</p>
            </div>

            @if(count($logs) > 0)
                <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'confirm-clear-qr-logs')" class="px-3.5 py-2 rounded-lg bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 text-xs font-bold hover:bg-rose-500 hover:text-white transition shadow-sm">
                    Clear All Scan Logs
                </button>

                <x-modal name="confirm-clear-qr-logs" maxWidth="sm">
                    <div class="p-6 bg-white dark:bg-[#141417] text-slate-900 dark:text-zinc-100 space-y-4 rounded-lg text-left">
                        <h2 class="text-base font-bold text-rose-600 dark:text-rose-400">Clear All QR Scan Logs?</h2>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                            Are you sure you want to clear all QR code audit scan logs permanently?
                        </p>
                        <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200 dark:border-zinc-800">
                            <button type="button" x-on:click="$dispatch('close')" class="btn-secondary text-xs py-1.5 px-3">
                                Cancel
                            </button>
                            <form method="POST" action="{{ route('admin.qr_scan_logs.clear') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger text-xs py-1.5 px-3">
                                    Clear Scan Logs
                                </button>
                            </form>
                        </div>
                    </div>
                </x-modal>
            @endif
        </div>

        @if(session('success'))
            <div class="p-4 rounded-lg bg-emerald-500/15 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <!-- Quick Summary Metrics -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="card-accent-blue p-4 flex items-center justify-between shadow-sm">
                <div>
                    <span class="text-[10px] uppercase tracking-wider text-blue-600 dark:text-blue-400 font-bold block">Total Scans Logged</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Scan count</span>
                </div>
                <span class="text-2xl font-black text-blue-600 dark:text-blue-400 font-mono">{{ count($logs) }}</span>
            </div>
            <div class="card-accent-emerald p-4 flex items-center justify-between shadow-sm">
                <div>
                    <span class="text-[10px] uppercase tracking-wider text-emerald-600 dark:text-emerald-400 font-bold block">Customer Scans</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Public scans</span>
                </div>
                <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400 font-mono">{{ $logs->where('scan_type', 'customer_scan')->count() }}</span>
            </div>
            <div class="card-accent-purple p-4 flex items-center justify-between shadow-sm">
                <div>
                    <span class="text-[10px] uppercase tracking-wider text-purple-600 dark:text-purple-400 font-bold block">Staff / Admin Scans</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Internal scans</span>
                </div>
                <span class="text-2xl font-black text-purple-600 dark:text-purple-400 font-mono">{{ $logs->where('scan_type', 'staff_scan')->count() }}</span>
            </div>
            <div class="card-accent-amber p-4 flex items-center justify-between shadow-sm">
                <div>
                    <span class="text-[10px] uppercase tracking-wider text-amber-600 dark:text-amber-400 font-bold block">Unique Orders</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Tracked orders</span>
                </div>
                <span class="text-2xl font-black text-amber-600 dark:text-amber-400 font-mono">{{ $logs->pluck('order_id')->unique()->count() }}</span>
            </div>
        </div>

        <div class="app-card p-4 sm:p-6 space-y-4 overflow-hidden shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-zinc-700 pb-3">
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">QR Code Scan Activity History</h2>
                <span class="text-xs text-slate-500 dark:text-slate-400">Live feed</span>
            </div>

            <div class="overflow-x-auto max-w-full">
                <table class="w-full text-left text-xs whitespace-nowrap min-w-[700px]">
                    <thead class="bg-slate-100 dark:bg-[#18181B] text-slate-700 dark:text-slate-300 uppercase text-[10px] tracking-wider border-b border-slate-200 dark:border-zinc-700">
                        <tr>
                            <th class="px-4 py-3">Scan Time</th>
                            <th class="px-4 py-3">Order #</th>
                            <th class="px-4 py-3">Scanned By</th>
                            <th class="px-4 py-3">Scan Type</th>
                            <th class="px-4 py-3">IP Address</th>
                            <th class="px-4 py-3">Device / User Agent</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-zinc-800">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition">
                                <td class="px-4 py-3 font-mono text-slate-500 dark:text-slate-400">
                                    {{ $log->created_at ? $log->created_at->format('M d, Y • h:i A') : 'N/A' }}
                                </td>
                                <td class="px-4 py-3 font-mono font-bold text-blue-600 dark:text-blue-400">
                                    @if($log->order)
                                        <a href="{{ route('laundry.track', $log->qrCode->qr_token ?? $log->order->order_number) }}" class="hover:underline">
                                            #{{ $log->order->order_number }}
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-slate-900 dark:text-white font-semibold">
                                    {{ $log->scannedBy->name ?? 'Guest / Public User' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $log->scan_type === 'staff_scan' ? 'bg-indigo-500/15 text-indigo-600 dark:text-indigo-400 border border-indigo-500/30' : 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30' }}">
                                        {{ str_replace('_', ' ', $log->scan_type) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-mono text-slate-600 dark:text-slate-400">
                                    {{ $log->ip_address ?? '127.0.0.1' }}
                                </td>
                                <td class="px-4 py-3 text-slate-500 dark:text-slate-400 truncate max-w-[200px]" title="{{ $log->device }}">
                                    {{ Str::limit($log->device ?? 'Browser', 40) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400 text-xs">
                                    No QR scan logs recorded yet. Scans made from the public landing or tracking page will automatically show here!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</x-app-layout>
