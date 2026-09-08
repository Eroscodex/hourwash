<x-app-layout>

    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">QR Code Scan Logs</h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">Real-time audit log of customer, staff, and rider QR code scans across order tracking & verification</p>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" onclick="openAdminCameraScanner()" class="px-3.5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-sm flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <circle cx="12" cy="13" r="3"/>
                    </svg>
                    <span>Scan Order QR</span>
                </button>

                @if(count($logs) > 0)
                    <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'confirm-clear-qr-logs')" class="px-3.5 py-2 rounded-lg bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 text-xs font-bold hover:bg-rose-500 hover:text-white transition shadow-sm cursor-pointer">
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
        </div>

        @if(session('success'))
            <div class="p-4 rounded-lg bg-emerald-500/15 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 text-xs font-bold flex items-center gap-2 shadow-sm animate-fade-in">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Live Hardware Scanner Mode Switcher Controls -->
        <div class="app-card p-4 flex flex-col md:flex-row md:items-center justify-between gap-3 shadow-sm border-l-4 border-l-blue-600">
            <div class="space-y-0.5">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-900 dark:text-white flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Hardware Scanner Connection Mode</span>
                    </span>
                </div>
                <p class="text-[11px] text-slate-500 dark:text-slate-400">
                    Switch active connection mode for wired, 2.4GHz wireless USB dongle, Bluetooth handheld scanners, or built-in camera.
                </p>
            </div>

            <div class="flex items-center gap-1.5 bg-slate-100 dark:bg-zinc-800 p-1 rounded-lg text-xs font-bold overflow-x-auto">
                <button type="button" onclick="switchQrScannerMode('camera')" class="qr-mode-btn px-3 py-1.5 rounded-md transition whitespace-nowrap">
                    📷 Camera
                </button>
                <button type="button" onclick="switchQrScannerMode('wired_usb')" class="qr-mode-btn px-3 py-1.5 rounded-md transition whitespace-nowrap">
                    🔌 Wired USB
                </button>
                <button type="button" onclick="switchQrScannerMode('wireless_2g4')" class="qr-mode-btn px-3 py-1.5 rounded-md transition whitespace-nowrap">
                    ⚡ 2.4GHz Wireless
                </button>
                <button type="button" onclick="switchQrScannerMode('bluetooth')" class="qr-mode-btn px-3 py-1.5 rounded-md transition whitespace-nowrap">
                    📶 Bluetooth
                </button>
            </div>
        </div>

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
                    <span class="text-[10px] uppercase tracking-wider text-emerald-600 dark:text-emerald-400 font-bold block">Customer / Public Scans</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Public scans</span>
                </div>
                <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400 font-mono">{{ $logs->whereIn('scan_type', ['customer_scan', 'public_scan'])->count() }}</span>
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
                <table class="w-full text-left text-xs whitespace-nowrap min-w-[750px]">
                    <thead class="bg-slate-100 dark:bg-[#18181B] text-slate-700 dark:text-slate-300 uppercase text-[10px] tracking-wider border-b border-slate-200 dark:border-zinc-700">
                        <tr>
                            <th class="px-4 py-3">Scan Time</th>
                            <th class="px-4 py-3">Order #</th>
                            <th class="px-4 py-3">Scanned By</th>
                            <th class="px-4 py-3">Payment Status</th>
                            <th class="px-4 py-3">Scan Type</th>
                            <th class="px-4 py-3">Scanner Mode & Device</th>
                            <th class="px-4 py-3">IP Address</th>
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
                                    @if(($log->order->payment_status ?? '') === 'paid')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">
                                            PAID ✓
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30">
                                            UNPAID
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($log->scan_type === 'staff_scan')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-indigo-500/15 text-indigo-700 dark:text-indigo-300 border border-indigo-500/30">
                                            STAFF SCAN
                                        </span>
                                    @elseif($log->scan_type === 'customer_scan')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">
                                            CUSTOMER SCAN
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-500/30">
                                            PUBLIC SCAN
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-300 font-medium truncate max-w-[220px]" title="{{ $log->device }}">
                                    {{ Str::limit($log->device ?? 'Browser', 45) }}
                                </td>
                                <td class="px-4 py-3 font-mono text-slate-500 dark:text-slate-400">
                                    {{ $log->ip_address ?? '127.0.0.1' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400 text-xs">
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
