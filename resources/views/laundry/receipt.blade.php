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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        @media print {
            @page {
                size: 48mm auto; /* Continuous roll height */
                margin: 0;
            }
            *, *::before, *::after {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            html, body {
                width: 48mm !important;
                max-width: 48mm !important;
                margin: 0 auto !important;
                padding: 0 !important;
                background: #ffffff !important;
                color: #000000 !important;
                font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif !important;
            }
            .no-print {
                display: none !important;
            }
            .printable-card {
                width: 48mm !important;
                max-width: 48mm !important;
                margin: 0 auto !important;
                padding: 2mm !important;
                border: none !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                background: #ffffff !important;
                color: #000000 !important;
                box-sizing: border-box !important;
            }
        }
        html, body {
            overflow: hidden !important;
        }
        body {
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body class="bg-slate-200 dark:bg-zinc-950 text-slate-900 h-screen w-full flex flex-col items-center justify-center p-2 overflow-hidden select-none">

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

        $customerName = $order->customer?->name ?? 'Walk-in Customer';
        $customerPhone = $order->customer?->phone ?? ($order->customer?->customerProfile?->phone ?? 'N/A');
        $serviceName = $order->service?->name ?? 'Standard Wash';
        $servicePrice = $order->service?->price ?? 120;
        $machineLabel = $order->machine ? $order->machine->machine_name . ' (' . $order->machine->machine_code . ')' : 'AUTO-ASSIGN';
        $machineCode = $order->machine ? $order->machine->machine_code : 'AUTO-ASSIGN';
        $qrToken = $order->qrCode?->qr_token ?? $order->order_number;
    @endphp

    <!-- Action Bar (Download Image & Close only) -->
    <div class="no-print mb-3 flex flex-col items-center justify-center gap-2 text-center max-w-lg w-full">
        <div class="flex items-center justify-center gap-2 flex-wrap">
            <button id="download-img-btn" onclick="downloadReceiptImage()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm transition flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Download Image (PNG)</span>
            </button>
            <button onclick="if(window.opener) { window.close(); } else if(window.history.length > 1) { window.history.back(); } else { window.close(); }" class="bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-200 px-3.5 py-2 rounded-lg text-xs font-bold border border-slate-300 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-700 transition cursor-pointer">
                ✕ Close
            </button>
        </div>
    </div>

    <script>
        function downloadReceiptImage() {
            const card = document.querySelector('.printable-card');
            if (!card) return;

            const btn = document.getElementById('download-img-btn');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin h-3.5 w-3.5 text-white inline mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';

            html2canvas(card, {
                scale: 4, // 4x Ultra HD Resolution for crisp receipt printing
                backgroundColor: '#ffffff',
                useCORS: true,
                allowTaint: true,
                logging: false,
                letterRendering: true
            }).then(function(canvas) {
                const link = document.createElement('a');
                link.download = 'Receipt-{{ $order->order_number }}.png';
                link.href = canvas.toDataURL('image/png', 1.0);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                btn.disabled = false;
                btn.innerHTML = originalText;
            }).catch(function(err) {
                console.error('Download error:', err);
                alert('Could not download image automatically. You can screenshot the receipt card instead.');
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        }
    </script>

    <!-- 48.0mm Thermal Paper Roll Printable Card Container -->
    <div class="printable-card w-[48mm] max-w-[48mm] bg-white px-[2mm] py-2 text-black space-y-2 text-[8.5px] leading-tight border-0 shadow-sm rounded-none font-sans shrink-0">

        <!-- Receipt Header -->
        <div class="text-center space-y-0.5 border-b-2 border-black pb-2">
            <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-7 h-7 mx-auto mb-1 rounded-full object-cover p-0.5 border border-black bg-white">
            <h1 class="text-[10px] font-black tracking-wide uppercase text-black leading-tight">
                HOUR WASH LAUNDRY
            </h1>
            <p class="text-[8px] font-bold text-black leading-tight">Laundry Shop System</p>
            <p class="text-[7.5px] font-semibold text-black leading-tight">Magallanes St., Orosite, Legazpi</p>
            <p class="text-[7.5px] font-bold text-black">Mobile: 09123456789</p>
        </div>

        <!-- Receipt Order Meta -->
        <div class="space-y-1 border-b-2 border-black pb-2 text-[8.5px] text-black">
            <div class="flex justify-between items-start gap-1">
                <span class="font-bold text-black shrink-0">RECEIPT:</span>
                <span class="font-black text-right font-mono text-[9.5px] text-black">#{{ $order->order_number }}</span>
            </div>
            <div class="flex justify-between items-start gap-1">
                <span class="font-bold text-black shrink-0">DATE:</span>
                <span class="font-bold text-right text-black text-[8.5px]">{{ $order->created_at->format('M d, Y h:i A') }}</span>
            </div>
            <div class="flex justify-between items-start gap-1">
                <span class="font-bold text-black shrink-0">CUSTOMER:</span>
                <span class="font-black text-right text-black block break-words flex-1 pl-1 text-[9px] leading-tight uppercase">{{ $customerName }}</span>
            </div>
            <div class="flex justify-between items-start gap-1">
                <span class="font-bold text-black shrink-0">PHONE:</span>
                <span class="font-bold text-right text-black text-[8.5px]">{{ $customerPhone }}</span>
            </div>
            <div class="flex justify-between items-start gap-1">
                <span class="font-bold text-black shrink-0">STAFF:</span>
                <span class="font-black text-right text-black block break-words flex-1 pl-1 text-[9px] leading-tight uppercase">{{ $processorName }}</span>
            </div>
            <div class="flex justify-between items-start gap-1">
                <span class="font-bold text-black shrink-0">MACHINE:</span>
                <span class="font-extrabold font-mono text-[8px] text-right text-black">{{ $machineLabel }}</span>
            </div>
        </div>

        <!-- Receipt Line Items -->
        <div class="space-y-1.5 border-b-2 border-black pb-2 text-[8.5px] text-black">
            <div class="flex justify-between font-black border-b-2 border-black pb-1.5 mb-1.5 text-[8.5px]">
                <span class="leading-normal">ITEM / SERVICE</span>
                <span class="leading-normal">AMT</span>
            </div>
            
            <div class="flex justify-between items-start pt-1 pb-1 gap-1">
                <div class="flex-1 pr-1">
                    <span class="font-black block leading-snug text-[8.5px] text-black break-words">{{ $serviceName }}</span>
                    <span class="text-[7.5px] font-bold text-black block mt-1 leading-normal">{{ $order->weight_kg }} kg @ ₱{{ number_format($servicePrice, 2) }}/kg</span>
                </div>
                <span class="font-black text-[9.5px] text-black shrink-0 text-right leading-normal">₱{{ number_format($order->subtotal, 2) }}</span>
            </div>

            @if($order->delivery_fee > 0)
                <div class="flex justify-between text-[8px] font-bold text-black pt-1">
                    <span>Delivery Fee</span>
                    <span>₱{{ number_format($order->delivery_fee, 2) }}</span>
                </div>
            @endif

            @if($order->discount > 0)
                <div class="flex justify-between text-[8px] font-bold text-black pt-1">
                    <span>Discount</span>
                    <span>-₱{{ number_format($order->discount, 2) }}</span>
                </div>
            @endif
        </div>

        <!-- Total Amount & Payment Status -->
        <div class="space-y-1 border-b-2 border-black pb-2 text-[8.5px] text-black">
            <div class="flex justify-between font-black items-center pt-1">
                <span class="text-[9px]">TOTAL:</span>
                <span class="text-[11px] font-black text-black">₱{{ number_format($order->total_amount, 2) }}</span>
            </div>
            <div class="flex justify-between text-[8.5px] font-extrabold items-center pt-1 pb-0.5">
                <span>PAYMENT:</span>
                <span class="font-black uppercase text-black">
                    {{ strtoupper($order->payment_status) }}
                </span>
            </div>
        </div>

        <!-- Receipt Bottom QR & Footer Info -->
        <div class="text-center pt-2 space-y-1 text-black">
            <div class="w-16 h-16 mx-auto bg-white p-1 border-2 border-black rounded-none flex items-center justify-center shadow-none">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($qrToken) }}&margin=1" 
                     alt="Order QR Tag {{ $order->order_number }}" 
                     crossorigin="anonymous"
                     class="w-full h-full object-contain"
                     style="image-rendering: pixelated; image-rendering: -moz-crisp-edges; image-rendering: crisp-edges;">
            </div>
            <p class="text-[7.5px] font-bold text-black pt-1 leading-normal">Scan QR Code tag to track order</p>

            <div class="pt-0.5 flex flex-col items-center justify-center space-y-0.5 pb-1">
                <p class="text-[8px] font-extrabold text-black leading-tight">Thank you for washing with HourWash!</p>
            </div>
        </div>

    </div>

</body>
</html>
