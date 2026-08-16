<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsNotification;
use Illuminate\Support\Facades\Schema;

class SmsLogController extends Controller
{
    public function index()
    {
        $smsLogs = Schema::hasTable('sms_notifications')
            ? SmsNotification::with(['order', 'user'])->latest()->get()
            : collect();

        $totalDispatched = $smsLogs->count();

        return view('admin.sms.index', compact('smsLogs', 'totalDispatched'));
    }
}
