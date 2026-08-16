<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsNotification;
use App\Services\SmsNotificationService;
use Illuminate\Http\Request;
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

    public function sendTestSms(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        $res = SmsNotificationService::send($validated['phone'], $validated['message']);

        $isSuccess = ($res['success'] ?? false) === true
            || ! empty($res['smsBatchId'])
            || ($res['status'] ?? '') === 'success'
            || ($res['data']['success'] ?? false) === true
            || isset($res['recipientCount']);

        $smsStatus = $isSuccess ? 'sent' : 'failed';

        if (Schema::hasTable('sms_notifications')) {
            SmsNotification::create([
                'phone' => $validated['phone'],
                'message' => $validated['message'],
                'status' => $smsStatus,
                'user_id' => auth()->id(),
            ]);
        }

        if ($isSuccess) {
            return redirect()->route('admin.sms.index')->with('success', 'Test SMS successfully sent and queued via Textbee!');
        }

        return redirect()->route('admin.sms.index')->with('error', 'Test SMS dispatch failed: '.($res['message'] ?? 'Unknown error'));
    }
}
