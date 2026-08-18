<!-- Camera QR Scanner Modal Component for Admin & Staff -->
<div id="admin-camera-scanner-modal" class="fixed inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm z-50 hidden flex-col items-center justify-center p-4 animate-fade-in">
    <div class="app-card max-w-sm w-full p-6 space-y-4 text-center shadow-xl border border-slate-200 dark:border-zinc-800">
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-zinc-800 pb-3">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <circle cx="12" cy="13" r="3"/>
                </svg>
                <span>Live Camera QR Code Scanner</span>
            </h3>
            <button onclick="closeAdminCameraScanner()" class="text-slate-500 hover:text-slate-900 dark:hover:text-white font-bold text-base">✕</button>
        </div>
        
        <div id="admin-qr-reader" class="w-full h-64 bg-black rounded-lg overflow-hidden relative flex items-center justify-center border border-slate-200 dark:border-zinc-800 shadow-inner"></div>

        <p class="text-[11px] text-slate-500 dark:text-slate-400">Point device camera at any HourWash customer order tag or receipt QR code to instantly track/process.</p>
        <button onclick="closeAdminCameraScanner()" class="btn-secondary text-xs w-full">Cancel</button>
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

    async function openAdminCameraScanner() {
        const modal = document.getElementById('admin-camera-scanner-modal');
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        if (!adminHtml5QrCodeScanner) {
            adminHtml5QrCodeScanner = new Html5Qrcode("admin-qr-reader");
        }

        const qrCodeSuccessCallback = (decodedText) => {
            closeAdminCameraScanner();
            if (decodedText) {
                let cleaned = decodedText.trim();
                if (cleaned.startsWith('http://') || cleaned.startsWith('https://')) {
                    window.location.href = cleaned;
                } else {
                    window.location.href = '/laundry/track/' + encodeURIComponent(cleaned);
                }
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
            console.warn("Direct camera selection failed, falling back to facingMode constraint:", err);
            try {
                await adminHtml5QrCodeScanner.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);
            } catch (fallbackErr) {
                console.error("Camera scanner fallback error:", fallbackErr);
                alert("Camera Access Required: Please allow camera permissions in your browser settings to scan QR tags.");
                closeAdminCameraScanner();
            }
        }
    }

    async function closeAdminCameraScanner() {
        const modal = document.getElementById('admin-camera-scanner-modal');
        if (!modal) return;
        if (adminHtml5QrCodeScanner && adminHtml5QrCodeScanner.isScanning) {
            try {
                await adminHtml5QrCodeScanner.stop();
            } catch (e) {}
        }
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
