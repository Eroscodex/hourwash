<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailNotification;

class EmailLogController extends Controller
{
    public function index()
    {
        $emailLogs = EmailNotification::with(['order', 'user'])
            ->latest()
            ->get();

        $totalDispatched = $emailLogs->count();

        return view('admin.emails.index', compact('emailLogs', 'totalDispatched'));
    }
}
