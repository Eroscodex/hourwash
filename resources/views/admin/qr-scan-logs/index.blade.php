<x-app-layout>

    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">QR Code Scan Logs</h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">Real-time audit log of customer, staff, and rider QR code scans across order tracking & verification</p>
            </div>

            @if(count($logs) > 0)
                <form method="POST" action="{{ route('admin.qr_scan_logs.clear') }}" onsubmit="return confirm('Are you sure you want to clear all QR scan logs?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3.5 py-2 rounded-lg bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 text-xs font-bold hover:bg-rose-500 hover:text-white transition shadow-sm">
                        🗑️ Clear All Scan Logs
                    </button>
                </form>
            @endif
        </div>

        @if(session('success'))
            <div class="p-4 rounded-lg bg-emerald-500/15 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <!-- Quick Summary Metrics -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="app-card p-4">
                <span class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold block">Total Scans Logged</span>
                <p class="text-xl font-extrabold text-blue-600 dark:text-blue-400 font-mono mt-1">{{ count($logs) }}</p>
            </div>
            <div class="app-card p-4">
                <span class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold block">Customer Scans</span>
                <p class="text-xl font-extrabold text-emerald-600 dark:text-emerald-400 font-mono mt-1">{{ $logs->where('scan_type', 'customer_scan')->count() }}</p>
            </div>
            <div class="app-card p-4">
                <span class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold block">Staff / Admin Scans</span>
                <p class="text-xl font-extrabold text-indigo-600 dark:text-indigo-400 font-mono mt-1">{{ $logs->where('scan_type', 'staff_scan')->count() }}</p>
            </div>
            <div class="app-card p-4">
                <span class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold block">Unique Scanned Orders</span>
                <p class="text-xl font-extrabold text-amber-600 dark:text-amber-400 font-mono mt-1">{{ $logs->pluck('order_id')->unique()->count() }}</p>
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
