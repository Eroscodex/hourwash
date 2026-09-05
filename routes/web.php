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
use Illuminate\Support\Facades\Auth;
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
    /** @var User $user */
    $user = Auth::user();

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

    $idleWashers = Machine::where('status', 'idle')
        ->where(function ($q) {
            $q->where('machine_type', 'washer')
                ->orWhere('machine_name', 'like', '%Washer%')
                ->orWhere('machine_code', 'like', '%WM%')
                ->orWhere('machine_code', 'like', '%W%');
        })->count();

    $idleDryers = Machine::where('status', 'idle')
        ->where(function ($q) {
            $q->where('machine_type', 'dryer')
                ->orWhere('machine_name', 'like', '%Dryer%')
                ->orWhere('machine_code', 'like', '%DR%')
                ->orWhere('machine_code', 'like', '%DM%')
                ->orWhere('machine_code', 'like', '%D%');
        })->count();

    if ($idleWashers > 0 && $idleDryers === 0) {
        $totalIdle = Machine::where('status', 'idle')->count();
        $idleWashers = (int) ceil($totalIdle / 2);
        $idleDryers = (int) floor($totalIdle / 2);
    }
    $availableMachinesCount = Machine::where('status', 'idle')->count();
    $storeStatus = Cache::get('store_status', 'open');

    return view('dashboard', compact('user', 'activeOrder', 'recentOrders', 'notifications', 'machines', 'idleWashers', 'idleDryers', 'availableMachinesCount', 'storeStatus'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Public QR Order Tracking (Rate limited: max 15 requests/min per IP to prevent enumeration)
Route::get('/laundry/track/{qr}', [LaundryController::class, 'track'])->middleware('throttle:15,1')->name('laundry.track');

// Printable Store Receipt Route
Route::get('/laundry/receipt/{order}', function (Order $order) {
    $order->load(['customer', 'customer.customerProfile', 'service', 'machine', 'qrCode', 'statusHistory.changedBy']);

    return view('laundry.receipt', compact('order'));
})->name('laundry.receipt');

// Customer & Admin Order Cancellation Route
Route::post('/laundry/{order}/cancel', function (Order $order) {
    /** @var User|null $authUser */
    $authUser = Auth::user();
    if (Auth::id() !== $order->customer_id && (! $authUser || (! $authUser->isStaff() && ! $authUser->isOwner()))) {
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

    return back()->with('success', "Power Outage / Brownout extension applied! Order #{$order->order_number} estimated completion extended by +{$minutes} minutes. Customer notified via Email & SMS ({$order->customer->phone}).");
})->middleware('auth')->name('admin.laundry.extend');

Route::delete('/laundry/{order}', [LaundryController::class, 'destroy'])->middleware('auth')->name('laundry.destroy');
Route::post('/laundry/{order}/auto-assign-rider', [LaundryController::class, 'autoAssignRider'])->middleware('auth')->name('laundry.auto-assign-rider');

// Global Navbar Search Route with Strict Role Scoping
Route::get('/search', function (Request $request) {
    $q = trim($request->get('q', ''));

    if (empty($q)) {
        return back();
    }

    $cleanQ = ltrim($q, '#');
    /** @var User|null $authUser */
    $authUser = Auth::user();

    if (! $authUser) {
        return redirect()->route('login');
    }

    // 1. CUSTOMER ROLE SEARCH SCOPING (Strictly isolated to their own orders)
    if ($authUser->isCustomer()) {
        $order = Order::where('customer_id', $authUser->id)
            ->where(function ($query) use ($cleanQ, $q) {
                $query->where('order_number', $cleanQ)
                    ->orWhere('id', is_numeric($cleanQ) ? (int) $cleanQ : 0)
                    ->orWhereHas('service', function ($s) use ($q) {
                        $s->where('name', 'like', "%{$q}%");
                    });
            })
            ->first();

        if ($order) {
            return redirect()->route('laundry.track', $order->order_number);
        }

        return redirect()->route('my.orders')->with('error', "No matching orders found in your order history for '{$q}'.");
    }

    // 2. RIDER ROLE SEARCH SCOPING (Dispatches & assigned customer orders)
    if ($authUser->isRider()) {
        $order = Order::where(function ($query) use ($cleanQ, $q) {
            $query->where('order_number', $cleanQ)
                ->orWhere('id', is_numeric($cleanQ) ? (int) $cleanQ : 0)
                ->orWhereHas('customer', function ($u) use ($q) {
                    $u->where('name', 'like', "%{$q}%")->orWhere('phone', 'like', "%{$q}%");
                });
        })->first();

        if ($order) {
            return redirect()->route('rider.dashboard')->with('success', "Found dispatch for Order #{$order->order_number}");
        }

        return redirect()->route('rider.dashboard')->with('error', "No matching dispatches found for '{$q}'.");
    }

    // 3. STAFF ROLE SEARCH SCOPING (Laundry Orders & Machines)
    if ($authUser->isStaff()) {
        $order = Order::where(function ($query) use ($cleanQ, $q) {
            $query->where('order_number', $cleanQ)
                ->orWhere('id', is_numeric($cleanQ) ? (int) $cleanQ : 0)
                ->orWhereHas('customer', function ($u) use ($q) {
                    $u->where('name', 'like', "%{$q}%");
                });
        })->first();

        if ($order) {
            return redirect()->route('laundry.track', $order->order_number);
        }

        $machine = Machine::where('machine_code', $cleanQ)->orWhere('machine_name', 'like', "%{$q}%")->first();
        if ($machine) {
            return redirect()->route('staff.machines.index')->with('info', "Machine {$machine->machine_name} Status: ".ucfirst($machine->status));
        }

        return redirect()->route('staff.laundry.index')->with('error', "No matching orders or machines found for '{$q}'.");
    }

    // 4. ADMIN / OWNER ROLE SEARCH SCOPING (Full system search: Orders, Machines, Users)
    $order = Order::where(function ($query) use ($cleanQ, $q) {
        $query->where('order_number', $cleanQ)
            ->orWhere('id', is_numeric($cleanQ) ? (int) $cleanQ : 0)
            ->orWhereHas('customer', function ($u) use ($q) {
                $u->where('name', 'like', "%{$q}%");
            });
    })->first();

    if ($order) {
        return redirect()->route('laundry.track', $order->order_number);
    }

    $machine = Machine::where('machine_code', $cleanQ)->orWhere('machine_name', 'like', "%{$q}%")->first();
    if ($machine) {
        return redirect()->route('admin.machines.index');
    }

    $foundUser = User::where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%")->first();
    if ($foundUser) {
        return redirect()->route('admin.users.index');
    }

    return redirect()->route('admin.dashboard')->with('error', "No matching results found for '{$q}'.");
})->middleware('auth')->name('global.search');

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/laundry/create', [LaundryController::class, 'create'])->name('laundry.create');
    Route::post('/laundry', [LaundryController::class, 'store'])->name('laundry.store');
    Route::get('/my-orders', [LaundryController::class, 'myOrders'])->name('my.orders');
    Route::get('/frequent-user-card', function () {
        return view('frequent_card.index');
    })->name('frequent_card.index');

    // Profile Router (Auto-redirects to role-specific URL: /admin/profile, /staff/profile, or /customer/profile)
    Route::get('/profile', function () {
        /** @var User $user */
        $user = Auth::user();
        if ($user && ($user->isAdmin() || $user->isOwner())) {
            return redirect()->route('admin.profile.edit');
        }
        if ($user && $user->isStaff()) {
            return redirect()->route('staff.profile.edit');
        }

        return redirect()->route('customer.profile.edit');
    })->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customer Dedicated Profile Route
    Route::get('/customer/profile', [ProfileController::class, 'edit'])->name('customer.profile.edit');

    // Customer Feedback Submission Route
    Route::post('/feedback', function (Request $request) {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $order = Order::findOrFail($request->order_id);

        if ($order->customer_id !== Auth::id()) {
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
            'user_id' => Auth::id(),
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'published',
        ]);

        return back()->with('success', 'Thank you! Your feedback & star rating have been published.');
    })->name('feedback.store');

    Route::delete('/feedback/{feedback}', function (CustomerFeedback $feedback) {
        /** @var User $user */
        $user = Auth::user();
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
Route::middleware(['auth', 'staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', function () {
        /** @var User $user */
        $user = Auth::user();

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
    })->name('dashboard');

    Route::get('/laundry', [AdminLaundryController::class, 'index'])->name('laundry.index');
    Route::get('/machines', [MachineController::class, 'index'])->name('machines.index');
    Route::get('/machines/create', [MachineController::class, 'create'])->name('machines.create');
    Route::get('/qr-scan-logs', [QrScanLogController::class, 'index'])->name('qr_scan_logs.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});

/*
|--------------------------------------------------------------------------
| Rider Logistics Panel
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'rider'])->group(function () {
    Route::get('/rider/dashboard', [RiderDashboardController::class, 'index'])->name('rider.dashboard');
    Route::match(['post', 'patch'], '/rider/order/{order}/status', [RiderDashboardController::class, 'updateStatus'])->name('rider.updateStatus');
    Route::match(['post', 'patch'], '/rider/order/{order}/payment', [RiderDashboardController::class, 'updatePaymentStatus'])->name('rider.updatePaymentStatus');
});

/*
|--------------------------------------------------------------------------
| Admin / Owner Panel
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        /** @var User $user */
        $user = Auth::user();

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

    Route::delete('/reviews/clear-all', function () {
        /** @var User $user */
        $user = Auth::user();
        if (! $user->isAdmin() && ! $user->isOwner()) {
            abort(403);
        }

        CustomerFeedback::query()->truncate();

        return back()->with('success', 'All customer reviews have been cleared successfully.');
    })->name('reviews.clearAll');

    Route::get('/qr-scan-logs', [QrScanLogController::class, 'index'])->name('qr_scan_logs.index');
    Route::delete('/qr-scan-logs/clear', [QrScanLogController::class, 'clear'])->name('qr_scan_logs.clear');

    Route::post('/store-status/toggle', function () {
        /** @var User $user */
        $user = Auth::user();
        if (! $user->isAdmin() && ! $user->isOwner() && ! $user->isStaff()) {
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
    Route::patch('/users/{user}/stamps', [UserController::class, 'updateStamps'])->name('users.stamps.update');
    Route::get('/laundry', [AdminLaundryController::class, 'index'])->name('laundry.index');
    Route::match(['post', 'patch'], '/laundry/{order}', [AdminLaundryController::class, 'update'])->name('laundry.update');
    Route::delete('/laundry/{order}', [LaundryController::class, 'destroy'])->name('laundry.destroy');
    Route::post('/laundry/{order}/auto-assign-rider', [LaundryController::class, 'autoAssignRider'])->name('laundry.auto-assign-rider');
    Route::get('/analytics', function () {
        return redirect()->route('admin.dashboard');
    })->name('analytics');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/sms', [SmsLogController::class, 'index'])->name('sms.index');
    Route::delete('/sms/clear-all', [SmsLogController::class, 'destroyAll'])->name('sms.clearAll');
    Route::get('/emails', [EmailLogController::class, 'index'])->name('emails.index');
    Route::delete('/emails/clear-all', [EmailLogController::class, 'destroyAll'])->name('emails.clearAll');

    Route::post('/orders/reset-all', function () {
        /** @var User $user */
        $user = Auth::user();
        if (! $user->isOwner() && ! $user->isStaff()) {
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
Route::post('/chatbot', [ChatbotController::class, 'chat'])->middleware('throttle:10,1');

Route::view('/privacy-policy', 'privacy')->name('privacy');
Route::view('/terms-and-conditions', 'terms')->name('terms');
Route::view('/about-us', 'about')->name('about');
Route::view('/developers', 'developers')->name('developers');

/*
|--------------------------------------------------------------------------
| Security: Block Direct Exposure of Sensitive Environment & Internal Files
|--------------------------------------------------------------------------
*/
Route::get('/.env{any?}', fn () => abort(404));
Route::get('/.git{any?}', fn () => abort(404));
Route::get('/storage/logs{any?}', fn () => abort(404));
Route::get('/composer.json', fn () => abort(404));
Route::get('/package.json', fn () => abort(404));

require __DIR__.'/auth.php';
