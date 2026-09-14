<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Store Receipt - {{ $order->order_number }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        @media print {
            @page {
                size: 58mm auto; /* Physical paper roll width 58mm with automatic continuous roll height */
                margin: 0;
            }
            *, *::before, *::after {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            html, body {
                width: 58mm !important;
                max-width: 58mm !important;
                margin: 0 auto !important;
                padding: 0 !important;
                background: #ffffff !important;
                color: #000000 !important;
                font-family: 'Space Mono', 'Courier New', monospace !important;
            }
            .no-print {
                display: none !important;
            }
            .printable-card {
                width: 48mm !important; /* Active printhead printable width (48mm ±1mm) */
                max-width: 48mm !important;
                margin: 0 auto !important;
                padding: 0 !important;
                border: none !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                background: #ffffff !important;
                color: #000000 !important;
            }
        }
        body {
            font-family: 'Space Mono', 'Courier New', monospace;
        }
    </style>
</head>
<body class="bg-slate-200 dark:bg-zinc-950 text-slate-900 min-h-screen flex flex-col items-center justify-center p-2 sm:p-4">

    <!-- Print Action Bar -->
    <div class="no-print mb-3 flex flex-col items-center justify-center gap-2 text-center">
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print 58mm Receipt</span>
            </button>
            <button onclick="if(window.opener) { window.close(); } else if(window.history.length > 1) { window.history.back(); } else { window.close(); }" class="bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-200 px-3.5 py-2 rounded-lg text-xs font-bold border border-slate-300 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-700 transition cursor-pointer">
                ✕ Close
            </button>
        </div>
        <p class="text-[10.5px] text-slate-600 dark:text-zinc-400 bg-white/80 dark:bg-zinc-900/80 px-3 py-1 rounded-md border border-slate-200 dark:border-zinc-800 shadow-sm">
            💡 <strong>Thermal Printer Tip:</strong> Select your <strong>POS-58 Thermal Printer</strong> under <em>Destination</em> in the print dialog.
        </p>
    </div>

    @if(request()->boolean('auto_print') || request()->boolean('print'))
        <script>
            (function() {
                function triggerPrint() {
                    setTimeout(function() {
                        try {
                            window.print();
                        } catch(e) {
                            console.log('Print error:', e);
                        }
                    }, 350);
                }
                if (document.readyState === 'complete' || document.readyState === 'interactive') {
                    triggerPrint();
                } else {
                    window.addEventListener('load', triggerPrint);
                }
            })();
        </script>
    @endif

    <!-- 58mm Thermal Paper Roll (48mm Printable Width Container) -->
    <div class="printable-card w-[58mm] max-w-[58mm] bg-white px-[5mm] py-3 rounded-lg shadow-xl border border-slate-300 text-slate-900 space-y-1.5 text-[8.5px] leading-tight">

        <!-- Receipt Header -->
        <div class="text-center space-y-0.5 border-b border-dashed border-slate-400 pb-1.5">
            <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-7 h-7 mx-auto mb-1 rounded-full object-cover shadow-sm p-0.5 border border-slate-300 bg-white">
            <h1 class="text-[10px] font-black tracking-wide uppercase text-slate-900 font-sans leading-tight">
                HOUR WASH LAUNDRY
            </h1>
            <p class="text-[8px] font-bold font-sans text-slate-700 leading-tight">Laundry Shop System</p>
            <p class="text-[7.5px] text-slate-600 leading-tight">Magallanes St., Orosite, Legazpi</p>
            <p class="text-[7px] text-slate-500 font-sans">Mobile: 09123456789</p>
        </div>

        @php
            $historyUser = $order->statusHistory?->whereNotNull('changed_by')->last()?->changedBy;
            if ($historyUser && in_array($historyUser->role, ['admin', 'owner', 'staff'])) {
                $roleLabel = match($historyUser->role) {
                    'admin', 'owner' => 'Admin',
                    'staff' => 'Staff',
                    default => ucfirst($historyUser->role)
                };
                $processorName = $historyUser->name . ' (' . $roleLabel . ')';
            } elseif (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOwner() || auth()->user()->isStaff())) {
                $roleLabel = match(auth()->user()->role) {
                    'admin', 'owner' => 'Admin',
                    'staff' => 'Staff',
                    default => ucfirst(auth()->user()->role)
                };
                $processorName = auth()->user()->name . ' (' . $roleLabel . ')';
            } else {
                $processorName = 'Counter Staff';
            }
        @endphp

        <!-- Receipt Order Meta -->
        <div class="space-y-0.5 border-b border-dashed border-slate-400 pb-1.5">
            <div class="flex justify-between">
                <span>RECEIPT:</span>
                <span class="font-bold">#{{ $order->order_number }}</span>
            </div>
            <div class="flex justify-between">
                <span>DATE:</span>
                <span>{{ $order->created_at->format('M d, Y h:i A') }}</span>
            </div>
            <div class="flex justify-between">
                <span>CUSTOMER:</span>
                <span class="font-bold max-w-[24mm] truncate text-right">{{ $order->customer->name ?? 'Walk-in' }}</span>
            </div>
            <div class="flex justify-between">
                <span>PHONE:</span>
                <span class="font-bold">{{ $order->customer->phone ?? ($order->customer->customerProfile->phone ?? 'N/A') }}</span>
            </div>
            <div class="flex justify-between text-blue-700">
                <span>STAFF:</span>
                <span class="font-bold max-w-[24mm] truncate text-right">{{ $processorName }}</span>
            </div>
            <div class="flex justify-between">
                <span>MACHINE:</span>
                <span class="font-bold font-mono text-[8px]">
                    {{ $order->machine ? $order->machine->machine_name . ' (' . $order->machine->machine_code . ')' : 'AUTO-ASSIGN' }}
                </span>
            </div>
        </div>

        <!-- Receipt Line Items -->
        <div class="space-y-1 border-b border-dashed border-slate-400 pb-1.5">
            <div class="flex justify-between font-bold text-slate-800 border-b border-slate-300 pb-0.5 text-[8px]">
                <span>ITEM / SERVICE</span>
                <span>AMT</span>
            </div>
            
            <div class="flex justify-between items-start">
                <div class="max-w-[30mm]">
                    <span class="font-bold block leading-tight text-[8px]">{{ $order->service->name ?? 'Standard Wash' }}</span>
                    <span class="text-[7.5px] text-slate-600 block">{{ $order->weight_kg }} kg @ ₱{{ number_format($order->service->price ?? 120, 2) }}/kg</span>
                </div>
                <span class="font-bold text-[8.5px]">₱{{ number_format($order->subtotal, 2) }}</span>
            </div>

            @if($order->delivery_fee > 0)
                <div class="flex justify-between text-[8px]">
                    <span>Delivery Fee</span>
                    <span>₱{{ number_format($order->delivery_fee, 2) }}</span>
                </div>
            @endif

            @if($order->discount > 0)
                <div class="flex justify-between text-emerald-700 text-[8px]">
                    <span>Discount</span>
                    <span>-₱{{ number_format($order->discount, 2) }}</span>
                </div>
            @endif
        </div>

        <!-- Total Amount & Payment Status -->
        <div class="space-y-1 border-b border-dashed border-slate-400 pb-1.5">
            <div class="flex justify-between text-[9px] font-bold pt-0.5">
                <span>TOTAL:</span>
                <span class="text-[11px] font-black text-slate-900">₱{{ number_format($order->total_amount, 2) }}</span>
            </div>
            <div class="flex justify-between text-[8px]">
                <span>PAYMENT:</span>
                <span class="font-bold uppercase {{ $order->payment_status === 'paid' ? 'text-emerald-700' : 'text-rose-700' }}">
                    {{ strtoupper($order->payment_status) }}
                </span>
            </div>
        </div>

        <!-- Receipt Bottom QR & Footer Info -->
        <div class="text-center pt-1 space-y-1 border-t border-dashed border-slate-400 mt-1">
            <div class="w-14 h-14 mx-auto bg-white p-0.5 border border-slate-400 rounded flex items-center justify-center shadow-sm">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=110x110&data={{ $order->qrCode->qr_token ?? $order->order_number }}" 
                     alt="Order QR Tag {{ $order->order_number }}" 
                     class="w-full h-full">
            </div>
            <p class="text-[7px] text-slate-600">Scan QR Code tag to track order</p>

            <div class="pt-0.5 flex flex-col items-center justify-center space-y-0.5">
                <p class="text-[8.5px] font-bold text-slate-800 font-sans leading-tight">Thank you for washing with HourWash!</p>
            </div>
        </div>

    </div>

</body>
</html>
