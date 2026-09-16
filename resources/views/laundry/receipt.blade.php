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
                size: 48mm 72mm; /* Physical paper width 48.0mm, height 72.0mm */
                margin: 0;
            }
            *, *::before, *::after {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            html, body {
                width: 48mm !important;
                max-width: 48mm !important;
                height: 72mm !important;
                max-height: 72mm !important;
                margin: 0 auto !important;
                padding: 0 !important;
                background: #ffffff !important;
                color: #000000 !important;
                font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif !important;
                overflow: hidden !important;
            }
            .no-print {
                display: none !important;
            }
            .printable-card {
                width: 48mm !important;
                max-width: 48mm !important;
                height: 72mm !important;
                max-height: 72mm !important;
                margin: 0 auto !important;
                padding: 2mm !important;
                border: none !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                background: #ffffff !important;
                color: #000000 !important;
                box-sizing: border-box !important;
                overflow: hidden !important;
            }
        }
        body {
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body class="bg-slate-200 dark:bg-zinc-950 text-slate-900 min-h-screen flex flex-col items-center justify-center p-2 sm:p-4">

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

        $customerName = $order->customer?->name ?? 'Walk-in';
        $customerPhone = $order->customer?->phone ?? ($order->customer?->customerProfile?->phone ?? 'N/A');
        $serviceName = $order->service?->name ?? 'Standard Wash';
        $servicePrice = $order->service?->price ?? 120;
        $machineLabel = $order->machine ? $order->machine->machine_name . ' (' . $order->machine->machine_code . ')' : 'AUTO-ASSIGN';
        $machineCode = $order->machine ? $order->machine->machine_code : 'AUTO-ASSIGN';
        $qrToken = $order->qrCode?->qr_token ?? $order->order_number;
    @endphp

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
            addText(`CUSTOMER: {{ substr($customerName, 0, 20) }}\n`);
            addText(`PHONE:    {{ substr($customerPhone, 0, 20) }}\n`);
            addText(`STAFF:    {{ substr($processorName, 0, 20) }}\n`);
            addText(`MACHINE:  {{ substr($machineCode, 0, 20) }}\n`);
            addText("--------------------------------\n");
            addText("ITEM / SERVICE               AMT\n");
            addText("--------------------------------\n");
            
            const serviceNameStr = "{{ substr($serviceName, 0, 20) }}";
            const amountStr = "P{{ number_format($order->subtotal, 2) }}";
            addText(`${serviceNameStr.padEnd(20)} ${amountStr}\n`);
            addText(`  {{ $order->weight_kg }} kg @ P{{ number_format($servicePrice, 2) }}/kg\n`);

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
            addText(`QR TOKEN: {{ $qrToken }}\n`);
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
                scale: 4, // 4x Ultra HD Resolution for pitch-black thermal printing
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
            addText(`CUSTOMER: {{ substr($customerName, 0, 20) }}\n`);
            addText(`PHONE:    {{ substr($customerPhone, 0, 20) }}\n`);
            addText(`STAFF:    {{ substr($processorName, 0, 20) }}\n`);
            addText(`MACHINE:  {{ substr($machineCode, 0, 20) }}\n`);
            addText("--------------------------------\n");
            addText("ITEM / SERVICE               AMT\n");
            addText("--------------------------------\n");
            
            const serviceNameStr = "{{ substr($serviceName, 0, 20) }}";
            const amountStr = "P{{ number_format($order->subtotal, 2) }}";
            addText(`${serviceNameStr.padEnd(20)} ${amountStr}\n`);
            addText(`  {{ $order->weight_kg }} kg @ P{{ number_format($servicePrice, 2) }}/kg\n`);

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
            addText(`QR TOKEN: {{ $qrToken }}\n`);
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
                scale: 4, // 4x Ultra HD Resolution for pitch-black thermal printing
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

    <!-- 48mm x 72mm Thermal Paper Roll Printable Card Container -->
    <div class="printable-card w-[48mm] max-w-[48mm] min-h-[72mm] bg-white px-[2.5mm] py-2 text-black space-y-2 text-[9px] leading-normal border-0 shadow-sm rounded-none font-sans">

        <!-- Receipt Header -->
        <div class="text-center space-y-0.5 border-b-2 border-black pb-2">
            <img src="{{ asset('favicon.svg') }}" alt="Hour Wash Logo" class="w-8 h-8 mx-auto mb-1 rounded-full object-cover p-0.5 border border-black bg-white">
            <h1 class="text-[11px] font-black tracking-wide uppercase text-black leading-tight">
                HOUR WASH LAUNDRY
            </h1>
            <p class="text-[8.5px] font-bold text-black leading-tight">Laundry Shop System</p>
            <p class="text-[8px] font-semibold text-black leading-tight">Magallanes St., Orosite, Legazpi</p>
            <p class="text-[8px] font-bold text-black">Mobile: 09123456789</p>
        </div>

        <!-- Receipt Order Meta -->
        <div class="space-y-1.5 border-b-2 border-black pb-2.5 text-[9.5px] text-black">
            <div class="flex justify-between items-start gap-1">
                <span class="font-bold text-black shrink-0">RECEIPT:</span>
                <span class="font-black text-right font-mono text-[10px] text-black">#{{ $order->order_number }}</span>
            </div>
            <div class="flex justify-between items-start gap-1">
                <span class="font-bold text-black shrink-0">DATE:</span>
                <span class="font-bold text-right text-black text-[9px]">{{ $order->created_at->format('M d, Y h:i A') }}</span>
            </div>
            <div class="flex justify-between items-start gap-1">
                <span class="font-bold text-black shrink-0">CUSTOMER:</span>
                <span class="font-black text-right text-black block break-words flex-1 pl-2 text-[9.5px] leading-tight uppercase">{{ $customerName }}</span>
            </div>
            <div class="flex justify-between items-start gap-1">
                <span class="font-bold text-black shrink-0">PHONE:</span>
                <span class="font-bold text-right text-black text-[9px]">{{ $customerPhone }}</span>
            </div>
            <div class="flex justify-between items-start gap-1">
                <span class="font-bold text-black shrink-0">STAFF:</span>
                <span class="font-black text-right text-black block break-words flex-1 pl-2 text-[9.5px] leading-tight uppercase">{{ $processorName }}</span>
            </div>
            <div class="flex justify-between items-start gap-1">
                <span class="font-bold text-black shrink-0">MACHINE:</span>
                <span class="font-extrabold font-mono text-[8.5px] text-right text-black">{{ $machineLabel }}</span>
            </div>
        </div>

        <!-- Receipt Line Items -->
        <div class="space-y-1.5 border-b-2 border-black pb-2.5 text-[9.5px] text-black">
            <div class="flex justify-between font-black border-b border-black pb-1 text-[9px]">
                <span>ITEM / SERVICE</span>
                <span>AMT</span>
            </div>
            
            <div class="flex justify-between items-start pt-0.5 gap-1">
                <div class="flex-1 pr-1">
                    <span class="font-black block leading-snug text-[9px] text-black break-words">{{ $serviceName }}</span>
                    <span class="text-[8px] font-bold text-black block mt-0.5 leading-tight">{{ $order->weight_kg }} kg @ ₱{{ number_format($servicePrice, 2) }}/kg</span>
                </div>
                <span class="font-black text-[10px] text-black shrink-0 text-right">₱{{ number_format($order->subtotal, 2) }}</span>
            </div>

            @if($order->delivery_fee > 0)
                <div class="flex justify-between text-[8.5px] font-bold text-black pt-1">
                    <span>Delivery Fee</span>
                    <span>₱{{ number_format($order->delivery_fee, 2) }}</span>
                </div>
            @endif

            @if($order->discount > 0)
                <div class="flex justify-between text-[8.5px] font-bold text-black pt-1">
                    <span>Discount</span>
                    <span>-₱{{ number_format($order->discount, 2) }}</span>
                </div>
            @endif
        </div>

        <!-- Total Amount & Payment Status -->
        <div class="space-y-1 border-b-2 border-black pb-2 text-[9.5px] text-black">
            <div class="flex justify-between font-black items-center pt-1">
                <span class="text-[10px]">TOTAL:</span>
                <span class="text-[12px] font-black text-black">₱{{ number_format($order->total_amount, 2) }}</span>
            </div>
            <div class="flex justify-between text-[9px] font-extrabold items-center pt-0.5">
                <span>PAYMENT:</span>
                <span class="font-black uppercase text-black">
                    {{ strtoupper($order->payment_status) }}
                </span>
            </div>
        </div>

        <!-- Receipt Bottom QR & Footer Info -->
        <div class="text-center pt-2 space-y-1 text-black">
            <div class="w-16 h-16 mx-auto bg-white p-0.5 border border-black rounded flex items-center justify-center shadow-none">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ $qrToken }}" 
                     alt="Order QR Tag {{ $order->order_number }}" 
                     class="w-full h-full">
            </div>
            <p class="text-[8px] font-bold text-black pt-0.5">Scan QR Code tag to track order</p>

            <div class="pt-1 flex flex-col items-center justify-center space-y-0.5">
                <p class="text-[8.5px] font-extrabold text-black leading-tight">Thank you for washing with HourWash!</p>
            </div>
        </div>

    </div>

</body>
</html>
