<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Store Receipt - {{ $order->order_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        @media print {
            body { background: #fff !important; color: #000 !important; }
            .no-print { display: none !important; }
            .printable-card { border: none !important; shadow: none !important; padding: 0 !important; width: 100% !important; max-width: 100% !important; }
        }
        body { font-family: 'Space Mono', monospace; }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 min-h-screen flex flex-col items-center justify-center p-4">

    <!-- Action Bar (No Print) -->
    <div class="no-print mb-4 flex gap-3">
        <button onclick="window.print()" class="bg-[#007AFF] text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-md hover:bg-blue-600 transition flex items-center gap-2">
            🖨 Print Official Thermal Receipt
        </button>
        <button onclick="window.close()" class="bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-slate-300 transition">
            Close Window
        </button>
    </div>

    <!-- Official Thermal POS Receipt Card -->
    <div class="printable-card max-w-sm w-full bg-white p-6 rounded-2xl shadow-xl border border-slate-200 text-slate-900 space-y-4">
        
        <!-- Receipt Header -->
        <div class="text-center space-y-1 border-b border-dashed border-slate-300 pb-4">
            <h1 class="text-xl font-bold font-['Outfit'] tracking-tight">HOURWASH LAUNDRY</h1>
            <p class="text-[11px] font-sans text-slate-600">Self-Service & Drop-Off Laundry Systems</p>
            <p class="text-[10px] text-slate-500">Magallanes St., Orosite, Legazpi City, Albay</p>
            <p class="text-[10px] text-slate-500">Tel: (052) 801-4452 | Mobile: 09123456789</p>
        </div>

        <!-- Order Metadata -->
        <div class="text-[11px] space-y-1 border-b border-dashed border-slate-300 pb-3">
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
                <span>{{ $order->customer->phone ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between">
                <span>VIP STATUS:</span>
                <span class="font-bold text-[#007AFF]">
                    {{ ($order->customer->customerProfile->loyalty_points ?? 0) >= 200 ? '⭐ VIP MEMBER' : 'STANDARD MEMBER' }}
                </span>
            </div>
        </div>

        <!-- Purchased Items / Service Breakdown -->
        <div class="text-[11px] space-y-2 border-b border-dashed border-slate-300 pb-3">
            <div class="flex justify-between font-bold text-slate-700 border-b border-slate-200 pb-1">
                <span>DESCRIPTION</span>
                <span>QTY / AMT</span>
            </div>
            
            <div class="flex justify-between">
                <div>
                    <span class="font-bold block">{{ $order->service->name ?? 'Standard Laundry Service' }}</span>
                    <span class="text-[10px] text-slate-500">{{ $order->weight_kg }} kg @ ₱{{ number_format($order->service->price ?? 120, 2) }}/kg</span>
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

        <!-- Payment & Loyalty Summary -->
        <div class="text-[11px] space-y-1.5 border-b border-dashed border-slate-300 pb-3">
            <div class="flex justify-between text-sm font-bold pt-1">
                <span>TOTAL AMOUNT:</span>
                <span class="text-base text-slate-900">₱{{ number_format($order->total_amount, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span>PAYMENT STATUS:</span>
                <span class="font-bold uppercase {{ $order->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                    {{ strtoupper($order->payment_status) }} (CASHIER)
                </span>
            </div>
            <div class="flex justify-between">
                <span>POINTS EARNED THIS ORDER:</span>
                <span class="font-bold text-[#007AFF]">+{{ floor($order->total_amount / 10) }} PTS</span>
            </div>
            <div class="flex justify-between">
                <span>TOTAL LOYALTY POINTS:</span>
                <span class="font-bold text-[#007AFF]">{{ $order->customer->customerProfile->loyalty_points ?? 0 }} PTS</span>
            </div>
        </div>

        <!-- Scannable Order QR Code Tag -->
        <div class="text-center pt-2 space-y-2">
            <div class="w-32 h-32 mx-auto bg-white p-1.5 border border-slate-300 rounded-xl flex items-center justify-center">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $order->qrCode->qr_token ?? $order->order_number }}" 
                     alt="Order QR Tag {{ $order->order_number }}" 
                     class="w-full h-full">
            </div>
            <p class="text-[10px] text-slate-500">Scan QR Code tag to view live cleaning progress</p>
            <p class="text-[11px] font-bold text-slate-700 font-sans mt-2">Thank you for washing with HourWash!</p>
        </div>

    </div>

</body>
</html>
