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
            @page { margin: 0; size: auto; }
            body { background: #fff !important; color: #000 !important; padding: 0 !important; margin: 0 !important; }
            .no-print { display: none !important; }
            .printable-card { border: none !important; box-shadow: none !important; padding: 0 !important; margin: 0 !important; width: 100% !important; max-width: 100% !important; }
        }
        body { font-family: 'Space Mono', monospace; }
    </style>
</head>
<body class="bg-slate-200 dark:bg-zinc-950 text-slate-900 min-h-screen flex flex-col items-center justify-center p-2 sm:p-4">

    <!-- Print Action Bar -->
    <div class="no-print mb-3 flex items-center gap-2">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm transition flex items-center gap-1.5 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>Print Official Thermal Receipt</span>
        </button>
        <button onclick="if(window.opener) { window.close(); } else if(window.history.length > 1) { window.history.back(); } else { window.close(); }" class="bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-200 px-3.5 py-2 rounded-lg text-xs font-bold border border-slate-300 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-700 transition cursor-pointer">
            ✕ Close
        </button>
    </div>

    @if(request()->boolean('auto_print') || request()->boolean('print'))
        <script>
            window.addEventListener('load', function() {
                setTimeout(function() {
                    window.print();
                }, 300);
            });
        </script>
    @endif

    <!-- Authentic White Thermal Receipt Card -->
    <div class="printable-card max-w-sm w-full bg-white p-4 sm:p-5 rounded-lg shadow-xl border border-slate-300 text-slate-900 space-y-2 text-[10.5px]">

        <!-- Receipt Header -->
        <div class="text-center space-y-0.5 border-b border-dashed border-slate-300 pb-2">
            <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-10 h-10 mx-auto mb-1 rounded-full object-cover shadow-sm p-0.5 border border-slate-200 bg-white">
            <h1 class="text-base font-black tracking-wide uppercase text-slate-900 font-sans leading-none">
                HOUR WASH LAUNDRY
            </h1>
            <p class="text-[10px] font-bold font-sans text-slate-700 leading-tight">Laundry Shop Management System</p>
            <p class="text-[9.5px] text-slate-600 leading-tight">Magallanes St., Orosite, Legazpi City, Albay</p>
            <p class="text-[9px] text-slate-500 font-sans">Email: karlnicko2019@gmail.com | Mobile: 09123456789</p>
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
                $processorName = 'HourWash Counter Staff';
            }
        @endphp

        <!-- Receipt Order Meta -->
        <div class="space-y-0.5 border-b border-dashed border-slate-300 pb-2">
            <div class="flex justify-between">
                <span>RECEIPT NO:</span>
                <span class="font-bold">#{{ $order->order_number }}</span>
            </div>
            <div class="flex justify-between">
                <span>DATE / TIME:</span>
                <span>{{ $order->created_at->format('M d, Y h:i A') }}</span>
            </div>
            <div class="flex justify-between">
                <span>CUSTOMER:</span>
                <span class="font-bold">{{ $order->customer->name ?? 'Walk-in Customer' }}</span>
            </div>
            <div class="flex justify-between">
                <span>CUSTOMER PHONE:</span>
                <span class="font-bold">{{ $order->customer->phone ?? ($order->customer->customerProfile->phone ?? 'N/A') }}</span>
            </div>
            <div class="flex justify-between text-blue-600">
                <span>PROCESSED BY:</span>
                <span class="font-bold">{{ $processorName }}</span>
            </div>
            <div class="flex justify-between">
                <span>ASSIGNED MACHINE:</span>
                <span class="font-bold font-mono">
                    {{ $order->machine ? $order->machine->machine_name . ' (' . $order->machine->machine_code . ')' : 'UNASSIGNED (AUTO-ASSIGN)' }}
                </span>
            </div>
        </div>

        <!-- Receipt Line Items -->
        <div class="space-y-1 border-b border-dashed border-slate-300 pb-2">
            <div class="flex justify-between font-bold text-slate-700 border-b border-slate-200 pb-0.5">
                <span>DESCRIPTION</span>
                <span>QTY / AMT</span>
            </div>
            
            <div class="flex justify-between">
                <div>
                    <span class="font-bold block">{{ $order->service->name ?? 'Standard Laundry Service' }}</span>
                    <span class="text-[9.5px] text-slate-500">{{ $order->weight_kg }} kg @ ₱{{ number_format($order->service->price ?? 120, 2) }}/kg</span>
                </div>
                <span class="font-bold">₱{{ number_format($order->subtotal, 2) }}</span>
            </div>

            @if($order->delivery_fee > 0)
                <div class="flex justify-between">
                    <span>Delivery Fee</span>
                    <span>₱{{ number_format($order->delivery_fee, 2) }}</span>
                </div>
            @endif

            @if($order->discount > 0)
                <div class="flex justify-between text-emerald-600">
                    <span>Voucher Discount</span>
                    <span>-₱{{ number_format($order->discount, 2) }}</span>
                </div>
            @endif
        </div>

        <!-- Total Amount & Payment Status -->
        <div class="space-y-1 border-b border-dashed border-slate-300 pb-2">
            <div class="flex justify-between text-xs font-bold pt-0.5">
                <span>TOTAL AMOUNT:</span>
                <span class="text-sm font-extrabold text-slate-900">₱{{ number_format($order->total_amount, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span>PAYMENT STATUS:</span>
                <span class="font-bold uppercase {{ $order->payment_status === 'paid' ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ strtoupper($order->payment_status) }} (CASHIER)
                </span>
            </div>
        </div>

        <!-- Receipt Bottom QR & Footer Info -->
        <div class="text-center pt-2 space-y-1.5 border-t border-dashed border-slate-300 mt-1">
            <div class="w-20 h-20 mx-auto bg-white p-1 border border-slate-300 rounded-md flex items-center justify-center shadow-sm">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $order->qrCode->qr_token ?? $order->order_number }}" 
                     alt="Order QR Tag {{ $order->order_number }}" 
                     class="w-full h-full">
            </div>
            <p class="text-[9px] text-slate-500">Scan QR Code tag to view live cleaning progress</p>

            <div class="pt-1 flex flex-col items-center justify-center space-y-0.5">
                <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center shadow-sm overflow-hidden p-0.5 border border-blue-500/30">
                    <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-full h-full object-cover rounded-full">
                </div>
                <p class="text-[10px] font-bold text-slate-800 font-sans">Thank you for washing with HourWash!</p>
            </div>
        </div>

    </div>

</body>
</html>
