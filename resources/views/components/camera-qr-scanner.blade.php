<!-- Camera & Hardware QR Code Scanner Component with Audio Sound & Multi-Mode Switcher -->
<div id="admin-camera-scanner-modal" class="fixed inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm z-50 hidden flex-col items-center justify-center p-4 animate-fade-in">
    <div class="app-card max-w-md w-full p-5 sm:p-6 space-y-4 text-center shadow-xl border border-slate-200 dark:border-zinc-800">
        
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-zinc-800 pb-3">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <circle cx="12" cy="13" r="3"/>
                </svg>
                <span>QR Code Scanner Terminal</span>
            </h3>
            <button onclick="closeAdminCameraScanner()" class="text-slate-400 hover:text-slate-900 dark:hover:text-white font-bold text-base transition">✕</button>
        </div>

        <!-- Connection Mode Switcher Tabs -->
        <div class="space-y-1.5 text-left">
            <label class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">
                Select Scanner Hardware Mode:
            </label>
            <div class="grid grid-cols-2 gap-1.5 p-1 bg-slate-100 dark:bg-zinc-800/80 rounded-lg text-[11px] font-bold">
                <button type="button" id="scanner-mode-camera" onclick="switchQrScannerMode('camera')" class="qr-mode-btn px-2.5 py-1.5 rounded-md transition flex items-center justify-center gap-1.5">
                    <span>📷</span> <span>Camera</span>
                </button>
                <button type="button" id="scanner-mode-wired_usb" onclick="switchQrScannerMode('wired_usb')" class="qr-mode-btn px-2.5 py-1.5 rounded-md transition flex items-center justify-center gap-1.5">
                    <span>🔌</span> <span>Wired USB</span>
                </button>
                <button type="button" id="scanner-mode-wireless_2g4" onclick="switchQrScannerMode('wireless_2g4')" class="qr-mode-btn px-2.5 py-1.5 rounded-md transition flex items-center justify-center gap-1.5">
                    <span>⚡</span> <span>2.4GHz Wireless</span>
                </button>
                <button type="button" id="scanner-mode-bluetooth" onclick="switchQrScannerMode('bluetooth')" class="qr-mode-btn px-2.5 py-1.5 rounded-md transition flex items-center justify-center gap-1.5">
                    <span>📶</span> <span>Bluetooth</span>
                </button>
            </div>
        </div>

        <!-- Live Scanner Viewport & Status -->
        <div id="camera-viewport-container" class="relative">
            <div id="admin-qr-reader" class="w-full h-56 bg-black rounded-lg overflow-hidden relative flex items-center justify-center border border-slate-200 dark:border-zinc-800 shadow-inner"></div>
            
            <div id="hardware-scanner-active-banner" class="hidden absolute inset-0 bg-slate-900/90 dark:bg-zinc-900/95 backdrop-blur-xs rounded-lg flex flex-col items-center justify-center p-4 space-y-2 border border-blue-500/30 text-white">
                <div class="w-10 h-10 rounded-full bg-blue-600/20 border border-blue-500/50 flex items-center justify-center text-blue-400 text-lg font-bold animate-pulse">
                    <span id="hardware-scanner-icon">🔌</span>
                </div>
                <span id="hardware-scanner-title" class="font-extrabold text-xs text-blue-400 uppercase tracking-wider">Wired USB Scanner Active</span>
                <p class="text-[10px] text-slate-300 text-center max-w-[240px]">Ready for hardware scan! Aim your handheld scanner at any QR tag or receipt barcode.</p>
                <span class="text-[9px] px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-mono font-bold">Auto-Beep & Auto-Paid Enabled ✓</span>
            </div>
        </div>

        <!-- Sound & Auto-Paid Indicator -->
        <div class="flex items-center justify-between text-[10px] text-slate-500 dark:text-slate-400 px-1 pt-1 border-t border-slate-100 dark:border-zinc-800">
            <span class="flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Audio Beep On Scan</span>
            </span>
            <span class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-bold">
                <span>✓ Auto-Mark PAID</span>
            </span>
        </div>

        <button onclick="closeAdminCameraScanner()" class="btn-secondary text-xs w-full py-2">Cancel</button>
    </div>
</div>

<style>
    #admin-qr-reader video {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        border-radius: 8px;
    }
    #admin-qr-reader__scan_region {
        background: transparent !important;
    }
</style>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    let adminHtml5QrCodeScanner = null;
    let currentQrScannerMode = localStorage.getItem('hourwash_qr_scanner_mode') || 'camera';
    let hardwareScanBuffer = '';
    let lastKeyTime = 0;

    // Web Audio API Synth Sound Beeper
    function playQrScanBeep() {
        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            const audioCtx = new AudioCtx();
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }

            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();

            osc.type = 'sine';
            osc.frequency.setValueAtTime(1400, audioCtx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(1800, audioCtx.currentTime + 0.12);

            gain.gain.setValueAtTime(0.3, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.12);

            osc.connect(gain);
            gain.connect(audioCtx.destination);

            osc.start();
            osc.stop(audioCtx.currentTime + 0.12);
        } catch (e) {
            console.log("Audio beep playback notice:", e);
        }
    }

    function updateScannerModeUI() {
        document.querySelectorAll('.qr-mode-btn').forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white', 'shadow-sm', 'font-extrabold');
            btn.classList.add('text-slate-700', 'dark:text-slate-300', 'hover:bg-slate-200', 'dark:hover:bg-zinc-700');
        });

        const activeBtn = document.getElementById('scanner-mode-' + currentQrScannerMode);
        if (activeBtn) {
            activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-sm', 'font-extrabold');
            activeBtn.classList.remove('text-slate-700', 'dark:text-slate-300', 'hover:bg-slate-200', 'dark:hover:bg-zinc-700');
        }

        const banner = document.getElementById('hardware-scanner-active-banner');
        const iconEl = document.getElementById('hardware-scanner-icon');
        const titleEl = document.getElementById('hardware-scanner-title');

        if (currentQrScannerMode === 'camera') {
            if (banner) banner.classList.add('hidden');
        } else {
            if (banner) banner.classList.remove('hidden');
            if (currentQrScannerMode === 'wired_usb') {
                if (iconEl) iconEl.textContent = '🔌';
                if (titleEl) titleEl.textContent = 'Wired USB Scanner Active';
            } else if (currentQrScannerMode === 'wireless_2g4') {
                if (iconEl) iconEl.textContent = '⚡';
                if (titleEl) titleEl.textContent = '2.4GHz Wireless Scanner Active';
            } else if (currentQrScannerMode === 'bluetooth') {
                if (iconEl) iconEl.textContent = '📶';
                if (titleEl) titleEl.textContent = 'Wireless Bluetooth Scanner Active';
            }
        }
    }

    async function switchQrScannerMode(mode) {
        currentQrScannerMode = mode;
        localStorage.setItem('hourwash_qr_scanner_mode', mode);
        updateScannerModeUI();

        if (mode === 'camera') {
            await startCameraStream();
        } else {
            await stopCameraStream();
        }
    }

    async function startCameraStream() {
        const modal = document.getElementById('admin-camera-scanner-modal');
        if (!modal || modal.classList.contains('hidden')) return;

        if (!adminHtml5QrCodeScanner) {
            adminHtml5QrCodeScanner = new Html5Qrcode("admin-qr-reader");
        }

        if (adminHtml5QrCodeScanner.isScanning) {
            return;
        }

        const qrCodeSuccessCallback = (decodedText) => {
            playQrScanBeep();
            closeAdminCameraScanner();
            if (decodedText) {
                processQrScanResult(decodedText);
            }
        };

        const config = { fps: 15, qrbox: { width: 220, height: 220 } };

        try {
            const devices = await Html5Qrcode.getCameras();
            if (devices && devices.length > 0) {
                const backCam = devices.find(d => 
                    d.label.toLowerCase().includes('back') || 
                    d.label.toLowerCase().includes('rear') || 
                    d.label.toLowerCase().includes('environment')
                ) || devices[devices.length - 1];

                await adminHtml5QrCodeScanner.start(backCam.id, config, qrCodeSuccessCallback);
            } else {
                await adminHtml5QrCodeScanner.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);
            }
        } catch (err) {
            console.warn("Camera start warning:", err);
            try {
                await adminHtml5QrCodeScanner.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);
            } catch (fallbackErr) {
                console.error("Camera scanner error:", fallbackErr);
            }
        }
    }

    async function stopCameraStream() {
        if (adminHtml5QrCodeScanner && adminHtml5QrCodeScanner.isScanning) {
            try {
                await adminHtml5QrCodeScanner.stop();
            } catch (e) {}
        }
    }

    async function openAdminCameraScanner() {
        const modal = document.getElementById('admin-camera-scanner-modal');
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        updateScannerModeUI();

        if (currentQrScannerMode === 'camera') {
            await startCameraStream();
        }
    }

    async function closeAdminCameraScanner() {
        const modal = document.getElementById('admin-camera-scanner-modal');
        if (!modal) return;
        await stopCameraStream();
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function processQrScanResult(scannedText) {
        let cleaned = scannedText.trim();
        if (cleaned.startsWith('http://') || cleaned.startsWith('https://')) {
            const urlObj = new URL(cleaned);
            urlObj.searchParams.set('scanner_mode', currentQrScannerMode);
            window.location.href = urlObj.toString();
        } else {
            window.location.href = '/laundry/track/' + encodeURIComponent(cleaned) + '?scanner_mode=' + encodeURIComponent(currentQrScannerMode);
        }
    }

    // Global Hardware Barcode & QR Code Listener (Wired USB / 2.4GHz / Bluetooth HID)
    document.addEventListener('keydown', function(e) {
        // Do not intercept standard typing in inputs/textareas
        const activeTag = document.activeElement ? document.activeElement.tagName.toLowerCase() : '';
        const isEditable = document.activeElement && (document.activeElement.isContentEditable || activeTag === 'input' || activeTag === 'textarea' || activeTag === 'select');
        
        if (isEditable) return;

        const currentTime = new Date().getTime();

        if (e.key === 'Enter') {
            if (hardwareScanBuffer.length >= 3) {
                e.preventDefault();
                const codeScanned = hardwareScanBuffer;
                hardwareScanBuffer = '';
                playQrScanBeep();
                processQrScanResult(codeScanned);
            }
            hardwareScanBuffer = '';
            return;
        }

        // Hardware barcode scanners type characters in rapid bursts (< 60ms between keys)
        if (currentTime - lastKeyTime > 60) {
            hardwareScanBuffer = '';
        }

        if (e.key && e.key.length === 1) {
            hardwareScanBuffer += e.key;
        }

        lastKeyTime = currentTime;
    });

    document.addEventListener('DOMContentLoaded', function() {
        updateScannerModeUI();
    });
</script>
