<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailNotification;
use Illuminate\Support\Facades\Schema;

class EmailLogController extends Controller
{
    public function index()
    {
        $emailLogs = Schema::hasTable('email_notifications')
            ? EmailNotification::with(['order', 'user'])->latest()->get()
            : collect();

        $totalDispatched = $emailLogs->count();

        return view('admin.emails.index', compact('emailLogs', 'totalDispatched'));
    }
}
