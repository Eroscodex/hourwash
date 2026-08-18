<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QrScanLog;
use Illuminate\Http\Request;

class QrScanLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = QrScanLog::with(['order', 'qrCode', 'scannedBy'])
            ->latest()
            ->get();

        return view('admin.qr-scan-logs.index', compact('logs'));
    }

    public function clear()
    {
        QrScanLog::query()->delete();

        return redirect()->back()->with('success', 'All QR scan history logs cleared successfully.');
    }
}
