<?php

use App\Http\Controllers\Admin\EmailLogController;
use App\Http\Controllers\Admin\LaundryController as AdminLaundryController;
use App\Http\Controllers\Admin\MachineController;
use App\Http\Controllers\Admin\QrScanLogController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SmsLogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\LaundryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Rider\RiderDashboardController;
use App\Mail\OrderStatusUpdated;
use App\Models\CustomerFeedback;
use App\Models\EmailNotification;
use App\Models\Machine;
use App\Models\Order;
use App\Models\QrCode;
use App\Models\QrScanLog;
use App\Models\Service;
use App\Models\SmsNotification;
use App\Models\User;
use App\Services\SmsNotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Public Website / Landing Page
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    // Auto-fix any active machines that lack a current_order_id
    $unlinkedActiveMachines = Machine::whereIn('status', ['washing', 'rinsing', 'drying'])
        ->whereNull('current_order_id')
        ->get();

    if ($unlinkedActiveMachines->isNotEmpty()) {
        $defaultUser = User::where('role', 'owner')->first() ?? User::first();
        $defaultService = Service::where('status', 'active')->first();

        foreach ($unlinkedActiveMachines as $mach) {
            $numPart = preg_replace('/[^0-9]/', '', $mach->machine_code);
            $orderNum = 'HW'.str_pad($numPart, 6, '0', STR_PAD_RIGHT);

            $order = Order::firstOrCreate(
                ['order_number' => $orderNum],
                [
                    'customer_id' => $defaultUser?->id ?? 1,
                    'service_id' => $defaultService?->id ?? 1,
                    'machine_id' => $mach->id,
                    'weight_kg' => 5.0,
                    'subtotal' => 120.0,
                    'total_amount' => 120.0,
                    'payment_status' => 'paid',
                    'order_status' => $mach->status,
                    'estimated_completion' => now()->addMinutes($mach->remaining_minutes ?? 30),
                ]
            );

            $mach->update(['current_order_id' => $order->id]);

            QrCode::firstOrCreate(
                ['order_id' => $order->id],
                [
                    'qr_token' => $orderNum,
                    'status' => 'active',
                ]
            );
        }
    }

    $machines = Machine::with('currentOrder')->orderBy('id', 'asc')->get();
    $services = Service::where('status', 'active')->get();
    $feedbacks = CustomerFeedback::with('user:id,name')->where('status', 'published')->latest()->take(10)->get();
    $avgRating = round(CustomerFeedback::where('status', 'published')->avg('rating') ?? 5.0, 1);
    $totalReviews = CustomerFeedback::where('status', 'published')->count();
    $ratingCounts = [
        5 => CustomerFeedback::where('status', 'published')->where('rating', 5)->count(),
        4 => CustomerFeedback::where('status', 'published')->where('rating', 4)->count(),
        3 => CustomerFeedback::where('status', 'published')->where('rating', 3)->count(),
        2 => CustomerFeedback::where('status', 'published')->where('rating', 2)->count(),
        1 => CustomerFeedback::where('status', 'published')->where('rating', 1)->count(),
    ];

    $storeStatus = Cache::get('store_status', 'open');

    return view('welcome', compact('machines', 'services', 'feedbacks', 'avgRating', 'totalReviews', 'ratingCounts', 'storeStatus'));
})->name('welcome');

/*
|--------------------------------------------------------------------------
| Dashboard Router (Role Based)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->isOwner() || $user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->isStaff()) {
        return redirect()->route('staff.dashboard');
    }

    if ($user->isRider()) {
        return redirect()->route('rider.dashboard');
    }

    // Customer Dashboard Data
    $activeOrder = Order::with(['service', 'machine', 'qrCode'])
        ->where('customer_id', $user->id)
        ->whereNotIn('order_status', ['completed', 'cancelled'])
        ->latest()
        ->first();

    $recentOrders = Order::with('service')
        ->where('customer_id', $user->id)
        ->latest()
        ->take(10)
        ->get();

    $notifications = SmsNotification::where('user_id', $user->id)
        ->latest()
        ->take(4)
        ->get();

    $machines = Machine::orderBy('id', 'asc')->get();
    $idleWashers = Machine::whereIn('machine_type', ['washer', 'washer_dryer'])->where('status', 'idle')->count();
    $idleDryers = Machine::whereIn('machine_type', ['dryer', 'washer_dryer'])->where('status', 'idle')->count();
    $storeStatus = Cache::get('store_status', 'open');

    return view('dashboard', compact('user', 'activeOrder', 'recentOrders', 'notifications', 'machines', 'idleWashers', 'idleDryers', 'storeStatus'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Public QR Order Tracking (Accessible by anyone without login)
Route::get('/laundry/track/{qr}', [LaundryController::class, 'track'])->name('laundry.track');

// Printable Store Receipt Route
Route::get('/laundry/receipt/{order}', function (Order $order) {
    $order->load(['customer', 'customer.customerProfile', 'service', 'qrCode']);

    return view('laundry.receipt', compact('order'));
})->name('laundry.receipt');

// Customer & Admin Order Cancellation Route
Route::post('/laundry/{order}/cancel', function (Order $order) {
    if (auth()->id() !== $order->customer_id && ! auth()->user()->isStaff() && ! auth()->user()->isOwner()) {
        abort(403);
    }

    if ($order->order_status !== 'pending' && $order->order_status !== 'received') {
        return back()->with('error', 'Only pending or newly received orders can be cancelled.');
    }

    $order->order_status = 'cancelled';
    $order->save();

    // Release assigned machine if any
    if ($order->machine_id) {
        $machine = Machine::find($order->machine_id);
        if ($machine) {
            $machine->update(['status' => 'idle', 'remaining_minutes' => 0]);
        }
    }

    return back()->with('success', "Order #{$order->order_number} has been cancelled successfully.");
})->middleware('auth')->name('laundry.cancel');

// Brownout / Power Outage Time Extension Route
Route::post('/laundry/{order}/extend-brownout', function (Request $request, Order $order) {
    $minutes = (int) $request->get('delay_minutes', 60);

    // Extend order completion time
    if ($order->estimated_completion) {
        $order->estimated_completion = Carbon::parse($order->estimated_completion)->addMinutes($minutes);
    } else {
        $order->estimated_completion = now()->addMinutes($minutes);
    }

    $order->save();

    // Extend machine remaining minutes if assigned
    if ($order->machine_id) {
        $machine = Machine::find($order->machine_id);
        if ($machine) {
            $machine->increment('remaining_minutes', $minutes);
        }
    }

    // Eager load customer and service for notification
    $order->load(['customer', 'service']);

    // Send email notification to customer explaining power interruption
    try {
        if ($order->customer && $order->customer->email) {
            Mail::to($order->customer->email)->send(new OrderStatusUpdated($order, 'customer'));
        }
    } catch (Throwable $e) {
        Log::error('Brownout email notification error: '.$e->getMessage());
    }

    // Send SMS Notification explaining power outage delay
    try {
        SmsNotificationService::sendOrderStatusSms($order, "POWER OUTAGE ALERT: Completion time extended by +{$minutes} mins due to store brownout.");
    } catch (Throwable $e) {
        Log::error('Brownout SMS notification error: '.$e->getMessage());
    }

    return back()->with('success', "⚡ Power Outage / Brownout extension applied! Order #{$order->order_number} estimated completion extended by +{$minutes} minutes. Customer notified via Email & SMS ({$order->customer->phone}).");
})->middleware('auth')->name('admin.laundry.extend');

// Global Navbar Search Route
Route::get('/search', function (Request $request) {
    $q = trim($request->get('q', ''));

    if (empty($q)) {
        return back();
    }

    $cleanQ = ltrim($q, '#');

    // 1. Match Order Code / QR Token / Order ID
    $qr = QrCode::where('qr_token', $cleanQ)->first();
    $order = $qr ? Order::find($qr->order_id) : Order::where('order_number', $cleanQ)->orWhere('id', $cleanQ)->first();

    if ($order) {
        return redirect()->route('laundry.track', $order->order_number);
    }

    // 2. Match Machine Code
    $machine = Machine::where('machine_code', $cleanQ)->orWhere('machine_name', 'like', "%{$q}%")->first();
    if ($machine) {
        if (auth()->check() && (auth()->user()->isOwner() || auth()->user()->isStaff())) {
            return redirect()->route('admin.machines.index');
        }

        return redirect()->route('welcome')->with('info', "Machine {$machine->machine_name} Status: ".ucfirst($machine->status));
    }

    // 3. Match User Name / Email (Owner & Staff only)
    if (auth()->check() && (auth()->user()->isOwner() || auth()->user()->isStaff())) {
        $foundUser = User::where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%")->first();
        if ($foundUser) {
            return redirect()->route('admin.users.index');
        }
    }

    return redirect()->route('welcome')->with('error', "No results found for '{$q}'. Try searching by Order Code e.g. HW884210");
})->name('global.search');

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/laundry/create', [LaundryController::class, 'create'])->name('laundry.create');
    Route::post('/laundry', [LaundryController::class, 'store'])->name('laundry.store');
    Route::get('/my-orders', [LaundryController::class, 'myOrders'])->name('my.orders');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customer Feedback Submission Route
    Route::post('/feedback', function (Request $request) {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $order = Order::findOrFail($request->order_id);

        if ($order->customer_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized order feedback attempt.');
        }

        if ($order->order_status !== 'completed') {
            return back()->with('error', 'Feedback can only be submitted for completed orders.');
        }

        $existing = CustomerFeedback::where('order_id', $order->id)->first();
        if ($existing) {
            return back()->with('error', 'You have already submitted feedback for this order.');
        }

        CustomerFeedback::create([
            'user_id' => auth()->id(),
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'published',
        ]);

        return back()->with('success', 'Thank you! Your feedback & star rating have been published. ⭐');
    })->name('feedback.store');

    Route::delete('/feedback/{feedback}', function (CustomerFeedback $feedback) {
        $user = auth()->user();
        if ($user->isAdmin() || $user->isOwner() || $feedback->user_id === $user->id) {
            $feedback->delete();

            return back()->with('success', 'Feedback successfully removed.');
        }
        abort(403);
    })->name('feedback.destroy');
});

/*
|--------------------------------------------------------------------------
| Staff Panel
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->get('/staff/dashboard', function () {
    $user = auth()->user();

    if ($user->isRider()) {
        return redirect()->route('rider.dashboard');
    }

    if (! $user->isStaff() && ! $user->isAdmin() && ! $user->isOwner()) {
        return redirect()->route('dashboard');
    }
    $machines = Machine::with(['currentOrder', 'currentOrder.customer', 'activeOrder', 'activeOrder.customer'])->orderBy('id', 'asc')->get();
    $orders = Order::with(['customer', 'service', 'qrCode'])->latest()->get();
    $recentOrders = $orders->take(6);

    $totalOrders = Order::count();
    $inProgress = Order::whereIn('order_status', ['received', 'washing', 'rinsing', 'drying'])->count();
    $readyPickup = Order::where('order_status', 'ready')->count();
    $completedToday = Order::whereDate('updated_at', now()->today())->where('order_status', 'completed')->count();

    $notifications = SmsNotification::latest()->take(5)->get();
    $storeStatus = Cache::get('store_status', 'open');

    return view('staff.dashboard', compact(
        'user', 'machines', 'orders', 'recentOrders', 'totalOrders', 'inProgress', 'readyPickup', 'completedToday', 'notifications', 'storeStatus'
    ));
})->name('staff.dashboard');

/*
|--------------------------------------------------------------------------
| Rider Logistics Panel
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/rider/dashboard', [RiderDashboardController::class, 'index'])->name('rider.dashboard');
    Route::match(['post', 'patch'], '/rider/order/{order}/status', [RiderDashboardController::class, 'updateStatus'])->name('rider.updateStatus');
});

/*
|--------------------------------------------------------------------------
| Admin / Owner Panel
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if (! $user->isAdmin() && ! $user->isOwner()) {
            if ($user->isStaff()) {
                return redirect()->route('staff.dashboard');
            }

            if ($user->isRider()) {
                return redirect()->route('rider.dashboard');
            }

            return redirect()->route('dashboard');
        }
        $machines = Machine::with(['currentOrder', 'currentOrder.customer', 'activeOrder', 'activeOrder.customer'])->orderBy('id', 'asc')->get();
        $recentOrders = Order::with(['customer', 'service', 'qrCode'])->latest()->take(6)->get();

        $totalToday = Order::whereDate('created_at', now()->today())->count();
        $inProgress = Order::whereIn('order_status', ['received', 'washing', 'rinsing', 'drying'])->count();
        $readyPickup = Order::where('order_status', 'ready')->count();
        $completedToday = Order::whereDate('updated_at', now()->today())->where('order_status', 'completed')->count();

        $staffCount = User::where('role', 'staff')->count();
        $riderCount = User::where('role', 'rider')->count();
        $customerCount = User::where('role', 'customer')->orWhere('role', 'user')->count();
        $profitTotal = Order::where('payment_status', 'paid')->sum('total_amount');
        $feedbacks = CustomerFeedback::with('user:id,name')->latest()->take(6)->get();
        $notifications = SmsNotification::latest()->take(5)->get();

        // Overall System Reports & Analytics metrics
        $totalUsers = User::count();
        $totalMachines = Machine::count();
        $availableMachines = Machine::where('status', 'idle')->count();
        $totalLaundry = Order::count();
        $laundryStatus = Order::select('order_status as status', DB::raw('count(*) as total'))
            ->groupBy('order_status')
            ->get();
        $smsCount = Schema::hasTable('sms_notifications') ? SmsNotification::count() : 0;
        $emailCount = Schema::hasTable('email_notifications') ? EmailNotification::count() : 0;
        $qrScanCount = Schema::hasTable('qr_scan_logs') ? QrScanLog::count() : 0;
        $reviewCount = Schema::hasTable('customer_feedbacks') ? CustomerFeedback::count() : 0;

        // Rider Analytics & Dispatch Metrics for Admin & Staff
        $riderPickupRequests = Order::whereIn('order_status', ['pending', 'out_for_pickup'])
            ->where(function ($q) {
                $q->whereIn('pickup_type', ['pickup_delivery', 'pickup'])
                    ->orWhereNull('pickup_type')
                    ->orWhere('order_status', 'out_for_pickup');
            })
            ->count();

        $riderReceivedCount = Order::where('order_status', 'received')->count();
        $riderDeliveryCount = Order::where('order_status', 'out_for_delivery')->count();
        $riderCompletedCount = Order::where('order_status', 'completed')->count();
        $riderCancelledCount = Order::where('order_status', 'cancelled')->count();

        $riderOrders = Order::with(['customer.customerProfile', 'service'])
            ->whereIn('order_status', ['pending', 'out_for_pickup', 'out_for_delivery'])
            ->latest()
            ->get();

        $outForPickup = $riderPickupRequests;
        $outForDelivery = $riderDeliveryCount;

        $storeStatus = Cache::get('store_status', 'open');

        return view('admin.dashboard', compact(
            'user', 'machines', 'recentOrders', 'totalToday', 'inProgress', 'readyPickup',
            'completedToday', 'notifications', 'feedbacks', 'staffCount', 'riderCount', 'customerCount',
            'profitTotal', 'totalUsers', 'totalMachines', 'availableMachines', 'totalLaundry',
            'laundryStatus', 'smsCount', 'emailCount', 'qrScanCount', 'reviewCount', 'outForPickup', 'outForDelivery', 'riderOrders',
            'riderPickupRequests', 'riderReceivedCount', 'riderDeliveryCount', 'riderCompletedCount', 'riderCancelledCount',
            'storeStatus'
        ));
    })->name('dashboard');

    Route::get('/reviews', function () {
        $feedbacks = CustomerFeedback::with(['user', 'order'])->latest()->paginate(15);
        $totalReviews = CustomerFeedback::count();
        $avgRating = number_format(CustomerFeedback::avg('rating') ?? 5.0, 1);

        return view('admin.reviews.index', compact('feedbacks', 'totalReviews', 'avgRating'));
    })->name('reviews.index');

    Route::get('/qr-scan-logs', [QrScanLogController::class, 'index'])->name('qr_scan_logs.index');
    Route::delete('/qr-scan-logs/clear', [QrScanLogController::class, 'clear'])->name('qr_scan_logs.clear');

    Route::post('/store-status/toggle', function () {
        if (! auth()->user()->isAdmin() && ! auth()->user()->isOwner() && ! auth()->user()->isStaff()) {
            abort(403);
        }

        $current = Cache::get('store_status', 'open');
        $newStatus = $current === 'open' ? 'closed' : 'open';
        Cache::forever('store_status', $newStatus);

        $label = $newStatus === 'open' ? 'STORE OPEN TODAY' : 'STORE CLOSED TODAY';

        return back()->with('success', "Store status updated: {$label}.");
    })->name('store-status.toggle');

    Route::resource('machines', MachineController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('users', UserController::class);
    Route::get('/laundry', [AdminLaundryController::class, 'index'])->name('laundry.index');
    Route::match(['post', 'patch'], '/laundry/{order}', [AdminLaundryController::class, 'update'])->name('laundry.update');
    Route::get('/analytics', function () {
        return redirect()->route('admin.dashboard');
    })->name('analytics');
    Route::get('/sms', [SmsLogController::class, 'index'])->name('sms.index');
    Route::delete('/sms/clear-all', [SmsLogController::class, 'destroyAll'])->name('sms.clearAll');
    Route::get('/emails', [EmailLogController::class, 'index'])->name('emails.index');
    Route::delete('/emails/clear-all', [EmailLogController::class, 'destroyAll'])->name('emails.clearAll');

    Route::post('/orders/reset-all', function () {
        if (! auth()->user()->isOwner() && ! auth()->user()->isStaff()) {
            abort(403);
        }

        DB::transaction(function () {
            Order::query()->delete();
            Machine::query()->update([
                'current_order_id' => null,
                'status' => 'idle',
                'remaining_minutes' => null,
            ]);
        });

        return back()->with('success', 'All laundry orders have been reset successfully! All store machines reset to idle.');
    })->name('orders.reset');
});

/*
|--------------------------------------------------------------------------
| Chatbot
|--------------------------------------------------------------------------
*/
Route::get('/chatbot', function () {
    return view('chatbot');
});
Route::post('/chatbot', [ChatbotController::class, 'chat']);

Route::view('/privacy-policy', 'privacy')->name('privacy');
Route::view('/terms-and-conditions', 'terms')->name('terms');
Route::view('/about-us', 'about')->name('about');
Route::view('/developers', 'developers')->name('developers');

require __DIR__.'/auth.php';
