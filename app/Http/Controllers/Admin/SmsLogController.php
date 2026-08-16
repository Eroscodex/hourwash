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

    /**
     * Clear all SMS outbox log history
     */
    public function destroyAll()
    {
        if (Schema::hasTable('sms_notifications')) {
            SmsNotification::query()->delete();
        }

        return redirect()
            ->route('admin.sms.index')
            ->with('success', 'All SMS notification history has been deleted successfully.');
    }
}
