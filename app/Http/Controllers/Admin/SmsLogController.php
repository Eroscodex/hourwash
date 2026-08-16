<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsNotification;

class SmsLogController extends Controller
{
    public function index()
    {
        $smsLogs = SmsNotification::with(['order', 'user'])
            ->latest()
            ->get();

        $totalDispatched = $smsLogs->count();

        return view('admin.sms.index', compact('smsLogs', 'totalDispatched'));
    }
}
