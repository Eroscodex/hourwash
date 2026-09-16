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
                font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif !important;
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
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body class="bg-slate-200 dark:bg-zinc-950 text-slate-900 min-h-screen flex flex-col items-center justify-center p-2 sm:p-4">

    <!-- Print & Download Action Bar -->
    <div class="no-print mb-3 flex flex-col items-center justify-center gap-2 text-center max-w-lg w-full">
        <div class="flex items-center justify-center gap-2 flex-wrap">
            <button id="bt-print-btn" onclick="printViaBluetooth()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3.5 py-2 rounded-lg text-xs font-bold shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071a10 10 0 0114.142 0M1.414 7.071a15 15 0 0121.212 0"/></svg>
                <span>Print via Bluetooth (Y58)</span>
            </button>
            <button id="download-img-btn" onclick="downloadReceiptImage()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3.5 py-2 rounded-lg text-xs font-bold shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Download Image (PNG)</span>
            </button>
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-xs font-bold shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Browser Print</span>
            </button>
            <button onclick="if(window.opener) { window.close(); } else if(window.history.length > 1) { window.history.back(); } else { window.close(); }" class="bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-200 px-3 py-2 rounded-lg text-xs font-bold border border-slate-300 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-700 transition cursor-pointer">
                ✕ Close
            </button>
        </div>

        <p id="bt-status-text" class="text-[10.5px] text-slate-600 dark:text-zinc-400 bg-white/90 dark:bg-zinc-900/90 px-3 py-1.5 rounded-md border border-slate-200 dark:border-zinc-800 shadow-sm leading-normal w-full">
            📡 <strong>Bluetooth Direct Print:</strong> Tap <strong>Print via Bluetooth (Y58)</strong> to pair & print directly from your browser without leaving the page!
        </p>
    </div>

    <script>
        // Web Bluetooth Direct ESC/POS Printer Engine
        let btDevice = null;
        let btCharacteristic = null;

        async function printViaBluetooth() {
            const statusEl = document.getElementById('bt-status-text');
            const btn = document.getElementById('bt-print-btn');
            const origHtml = btn.innerHTML;

            try {
                if (!navigator.bluetooth) {
                    alert('Web Bluetooth is not supported in this browser. Please open in Chrome or Edge on Android/PC (or WebBLE on iOS), or click "Download Image (PNG)".');
                    return;
                }

                btn.disabled = true;
                btn.innerHTML = '<svg class="animate-spin h-3.5 w-3.5 text-white inline mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Connecting...';
                if (statusEl) statusEl.innerText = '🔵 Searching for Bluetooth Thermal Printer...';

                if (!btDevice || !btDevice.gatt.connected || !btCharacteristic) {
                    btDevice = await navigator.bluetooth.requestDevice({
                        acceptAllDevices: true,
                        optionalServices: [
                            '000018f0-0000-1000-8000-00805f9b34fb',
                            '00001101-0000-1000-8000-00805f9b34fb',
                            '49535343-fe7d-4ae5-8fa9-9fafd205e455',
                            'e7810a71-73ae-499d-8c15-faa9aef0c3f2'
                        ]
                    });

                    if (statusEl) statusEl.innerText = `🟡 Connecting to ${btDevice.name || 'Bluetooth Printer'}...`;
                    const server = await btDevice.gatt.connect();
                    
                    const services = await server.getPrimaryServices();
                    for (const service of services) {
                        try {
                            const characteristics = await service.getCharacteristics();
                            for (const char of characteristics) {
                                if (char.properties.write || char.properties.writeWithoutResponse) {
                                    btCharacteristic = char;
                                    break;
                                }
                            }
                        } catch (e) {
                            console.log('Service scan skip:', e);
                        }
                        if (btCharacteristic) break;
                    }

                    if (!btCharacteristic) {
                        throw new Error('No writable print characteristic found on selected device.');
                    }
                }

                if (statusEl) statusEl.innerText = `🟢 Connected to ${btDevice.name || 'POS Printer'}! Sending receipt commands...`;

                const escBytes = buildEscPosCommands();
                const chunkSize = 512;
                for (let i = 0; i < escBytes.length; i += chunkSize) {
                    const chunk = escBytes.subarray(i, i + chunkSize);
                    if (btCharacteristic.properties.writeWithoutResponse) {
                        await btCharacteristic.writeValueWithoutResponse(chunk);
                    } else {
                        await btCharacteristic.writeValue(chunk);
                    }
                }

                if (statusEl) statusEl.innerText = `✅ Printed successfully to ${btDevice.name || 'Bluetooth Printer'}!`;
                btn.disabled = false;
                btn.innerHTML = origHtml;

            } catch (err) {
                console.error('Bluetooth Print Error:', err);
                if (statusEl) statusEl.innerText = `❌ Bluetooth Error: ${err.message || err}`;
                btn.disabled = false;
                btn.innerHTML = origHtml;
                if (err.name !== 'NotFoundError') {
                    alert('Bluetooth Printing Error: ' + (err.message || err));
                }
            }
        }

        function buildEscPosCommands() {
            const encoder = new TextEncoder();
            let buffer = [];

            const add = (arr) => buffer.push(...arr);
            const addText = (str) => {
                const bytes = encoder.encode(str);
                buffer.push(...bytes);
            };

            // ESC @ - Initialize
            add([0x1B, 0x40]);
            
            // ESC a 1 - Center Align
            add([0x1B, 0x61, 0x01]);

            // Double Size Bold Title
            add([0x1B, 0x21, 0x30]);
            addText("HOUR WASH LAUNDRY\n");
            
            // Normal size
            add([0x1B, 0x21, 0x00]);
            addText("Laundry Shop System\n");
            addText("Magallanes St., Orosite, Legazpi\n");
            addText("Mobile: 09123456789\n");
            addText("--------------------------------\n");

            // ESC a 0 - Left Align
            add([0x1B, 0x61, 0x00]);
            
            addText(`RECEIPT:  #{{ $order->order_number }}\n`);
            addText(`DATE:     {{ $order->created_at->format('M d, Y h:i A') }}\n`);
            addText(`CUSTOMER: {{ substr($order->customer->name ?? 'Walk-in', 0, 20) }}\n`);
            addText(`PHONE:    {{ substr($order->customer->phone ?? ($order->customer->customerProfile->phone ?? 'N/A'), 0, 20) }}\n`);
            addText(`STAFF:    {{ substr($processorName, 0, 20) }}\n`);
            addText(`MACHINE:  {{ $order->machine ? $order->machine->machine_code : 'AUTO-ASSIGN' }}\n`);
            addText("--------------------------------\n");
            addText("ITEM / SERVICE               AMT\n");
            addText("--------------------------------\n");
            
            const serviceName = "{{ substr($order->service->name ?? 'Standard Wash', 0, 20) }}";
            const amountStr = "P{{ number_format($order->subtotal, 2) }}";
            addText(`${serviceName.padEnd(20)} ${amountStr}\n`);
            addText(`  {{ $order->weight_kg }} kg @ P{{ number_format($order->service->price ?? 120, 2) }}/kg\n`);

            @if($order->delivery_fee > 0)
                addText(`  Delivery Fee:      P{{ number_format($order->delivery_fee, 2) }}\n`);
            @endif

            @if($order->discount > 0)
                addText(`  Discount:         -P{{ number_format($order->discount, 2) }}\n`);
            @endif

            addText("--------------------------------\n");
            
            // Bold Total
            add([0x1B, 0x45, 0x01]);
            addText(`TOTAL:                   P{{ number_format($order->total_amount, 2) }}\n`);
            add([0x1B, 0x45, 0x00]);
            
            addText(`PAYMENT:                  {{ strtoupper($order->payment_status) }}\n`);
            addText("--------------------------------\n");

            // ESC a 1 - Center Align Footer
            add([0x1B, 0x61, 0x01]);
            addText(`QR TOKEN: {{ $order->qrCode->qr_token ?? $order->order_number }}\n`);
            addText("Scan QR Code tag to track order\n");
            addText("Thank you for washing with HourWash!\n");
            addText("\n\n\n");

            // Cut paper / feed
            add([0x1D, 0x56, 0x41, 0x03]);

            return new Uint8Array(buffer);
        }

        function downloadReceiptImage() {
            const card = document.querySelector('.printable-card');
            if (!card) return;

            const btn = document.getElementById('download-img-btn');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin h-3.5 w-3.5 text-white inline mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';

            html2canvas(card, {
                scale: 3,
                backgroundColor: '#ffffff',
                useCORS: true,
                allowTaint: true,
                logging: false
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
    <div class="printable-card w-[58mm] max-w-[58mm] bg-white px-[4mm] py-2.5 rounded-lg shadow-xl border border-slate-300 text-slate-900 space-y-1 text-[7.5px] leading-tight">

        <!-- Receipt Header -->
        <div class="text-center space-y-0.5 border-b border-dashed border-slate-400 pb-1">
            <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-6 h-6 mx-auto mb-0.5 rounded-full object-cover shadow-sm p-0.5 border border-slate-300 bg-white">
            <h1 class="text-[9px] font-black tracking-wide uppercase text-slate-900 leading-tight">
                HOUR WASH LAUNDRY
            </h1>
            <p class="text-[7.5px] font-bold text-slate-700 leading-tight">Laundry Shop System</p>
            <p class="text-[7px] text-slate-600 leading-tight">Magallanes St., Orosite, Legazpi</p>
            <p class="text-[6.5px] text-slate-500">Mobile: 09123456789</p>
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
        <div class="space-y-0.5 border-b border-dashed border-slate-400 pb-1 text-[7.5px]">
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
                <span class="font-bold max-w-[26mm] truncate text-right">{{ $order->customer->name ?? 'Walk-in' }}</span>
            </div>
            <div class="flex justify-between">
                <span>PHONE:</span>
                <span class="font-bold">{{ $order->customer->phone ?? ($order->customer->customerProfile->phone ?? 'N/A') }}</span>
            </div>
            <div class="flex justify-between text-blue-700">
                <span>STAFF:</span>
                <span class="font-bold max-w-[26mm] truncate text-right">{{ $processorName }}</span>
            </div>
            <div class="flex justify-between">
                <span>MACHINE:</span>
                <span class="font-bold font-mono text-[7px]">
                    {{ $order->machine ? $order->machine->machine_name . ' (' . $order->machine->machine_code . ')' : 'AUTO-ASSIGN' }}
                </span>
            </div>
        </div>

        <!-- Receipt Line Items -->
        <div class="space-y-1 border-b border-dashed border-slate-400 pb-1 text-[7.5px]">
            <div class="flex justify-between font-bold text-slate-800 border-b border-slate-300 pb-0.5 text-[7.5px]">
                <span>ITEM / SERVICE</span>
                <span>AMT</span>
            </div>
            
            <div class="flex justify-between items-start">
                <div class="max-w-[34mm]">
                    <span class="font-bold block leading-tight text-[7.5px]">{{ $order->service->name ?? 'Standard Wash' }}</span>
                    <span class="text-[6.5px] text-slate-600 block">{{ $order->weight_kg }} kg @ ₱{{ number_format($order->service->price ?? 120, 2) }}/kg</span>
                </div>
                <span class="font-bold text-[7.5px]">₱{{ number_format($order->subtotal, 2) }}</span>
            </div>

            @if($order->delivery_fee > 0)
                <div class="flex justify-between text-[7px]">
                    <span>Delivery Fee</span>
                    <span>₱{{ number_format($order->delivery_fee, 2) }}</span>
                </div>
            @endif

            @if($order->discount > 0)
                <div class="flex justify-between text-emerald-700 text-[7px]">
                    <span>Discount</span>
                    <span>-₱{{ number_format($order->discount, 2) }}</span>
                </div>
            @endif
        </div>

        <!-- Total Amount & Payment Status -->
        <div class="space-y-0.5 border-b border-dashed border-slate-400 pb-1 text-[7.5px]">
            <div class="flex justify-between font-bold pt-0.5">
                <span>TOTAL:</span>
                <span class="text-[9.5px] font-black text-slate-900">₱{{ number_format($order->total_amount, 2) }}</span>
            </div>
            <div class="flex justify-between text-[7px]">
                <span>PAYMENT:</span>
                <span class="font-bold uppercase {{ $order->payment_status === 'paid' ? 'text-emerald-700' : 'text-rose-700' }}">
                    {{ strtoupper($order->payment_status) }}
                </span>
            </div>
        </div>

        <!-- Receipt Bottom QR & Footer Info -->
        <div class="text-center pt-1 space-y-0.5 border-t border-dashed border-slate-400 mt-1">
            <div class="w-12 h-12 mx-auto bg-white p-0.5 border border-slate-400 rounded flex items-center justify-center shadow-sm">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $order->qrCode->qr_token ?? $order->order_number }}" 
                     alt="Order QR Tag {{ $order->order_number }}" 
                     class="w-full h-full">
            </div>
            <p class="text-[6.5px] text-slate-600">Scan QR Code tag to track order</p>

            <div class="pt-0.5 flex flex-col items-center justify-center space-y-0.5">
                <p class="text-[7.5px] font-bold text-slate-800 leading-tight">Thank you for washing with HourWash!</p>
            </div>
        </div>

    </div>

</body>
</html>
