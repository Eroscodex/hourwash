<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\Order;
use App\Models\QrCode;
use App\Models\QrScanLog;
use App\Models\Service;
use App\Models\User;
use App\Services\EmailNotificationService;
use App\Services\SmsNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class LaundryController extends Controller
{
    public function create()
    {
        $storeStatus = Cache::get('store_status', 'open');
        /** @var User|null $user */
        $user = Auth::user();
        $isStaffOrAdmin = $user && ($user->isAdmin() || $user->isOwner() || $user->isStaff());

        if ($storeStatus === 'closed' && ! $isStaffOrAdmin) {
            return redirect()->route('dashboard')->with('error', '⚠️ Store is currently CLOSED TODAY. New order bookings are disabled until the store re-opens.');
        }

        $services = Service::where('status', 'active')->get();
        $availableMachines = Machine::where('status', 'idle')
            ->whereNull('current_order_id')
            ->whereDoesntHave('activeOrder')
            ->orderBy('id', 'asc')
            ->get();
        $customers = User::where('role', 'customer')->orderBy('name', 'asc')->get();

        return view('laundry.create', compact('services', 'availableMachines', 'customers'));
    }

    public function store(Request $request)
    {
        try {
            $storeStatus = Cache::get('store_status', 'open');
            /** @var User|null $user */
            $user = Auth::user();
            $isStaffOrAdmin = $user && ($user->isAdmin() || $user->isOwner() || $user->isStaff());

            if ($storeStatus === 'closed' && ! $isStaffOrAdmin) {
                return back()->withInput()->with('error', '⚠️ Store is currently CLOSED TODAY. New order bookings are disabled until the store re-opens.');
            }

            $request->validate([
                'service_id' => 'required|exists:services,id',
                'weight_kg' => 'nullable|numeric|min:0|max:100',
                'machine_id' => 'nullable',
                'supplies_option' => 'nullable|string|in:store_provided,own_detergent,own_softener,own_both',
                'customer_id' => 'nullable|exists:users,id',
                'new_customer_name' => 'required_if:customer_mode,new|nullable|string|max:255',
                'new_customer_email' => 'nullable|email|max:255|unique:users,email',
                'new_customer_phone' => 'nullable|string|max:50|unique:users,phone',
                'new_customer_address' => 'nullable|string|max:255',
                'new_customer_barangay' => 'nullable|string|max:255',
                'new_customer_city' => 'nullable|string|max:255',
                'new_customer_province' => 'nullable|string|max:255',
            ], [
                'new_customer_phone.unique' => 'This phone number is already registered to an existing customer.',
                'new_customer_email.unique' => 'This email address is already registered to an existing customer.',
            ]);

            $customerId = Auth::id();

            if ($isStaffOrAdmin) {
                if ($request->input('customer_mode') === 'new' || ($request->filled('new_customer_name') && ! $request->filled('customer_id'))) {
                    $email = $request->input('new_customer_email');
                    if (empty($email)) {
                        $email = 'walkin_'.time().'_'.Str::random(4).'@hourwash.com';
                    }

                    $newCust = User::create([
                        'name' => $request->new_customer_name,
                        'email' => $email,
                        'phone' => $request->new_customer_phone,
                        'role' => 'customer',
                        'password' => Hash::make($request->new_customer_password ?: 'password'),
                    ]);

                    if (Schema::hasTable('customer_profiles')) {
                        $newCust->customerProfile()->create([
                            'address' => $request->new_customer_address ?: 'Magallanes St.',
                            'barangay' => $request->new_customer_barangay ?: 'Brgy. Orosite',
                            'city' => $request->new_customer_city ?: 'Legazpi City',
                            'province' => $request->new_customer_province ?: 'Albay',
                        ]);
                    }

                    $customerId = $newCust->id;
                } elseif ($request->filled('customer_id')) {
                    $customerId = (int) $request->customer_id;
                }
            }

            if (! $customerId) {
                return back()->withInput()->with('error', 'Unable to determine customer account for this order. Please log in or select a customer.');
            }

            $service = Service::findOrFail($request->service_id);

            $weight = max(1.0, (float) ($request->weight_kg ?: 7.0));
            $loadCount = max(1, (int) ceil($weight / 7.0));

            // Calculate subtotal based on load count (each load capacity is max 7kg)
            if ($service->price_unit === 'kg') {
                $subtotal = $service->price * $weight;
            } else {
                $subtotal = $service->price * $loadCount;
            }

            // Calculate supplies discount for self-provided detergent or softener
            $discount = 0.00;
            $suppliesLabel = '';

            switch ($request->supplies_option) {
                case 'own_detergent':
                    $discount = 15.00;
                    $suppliesLabel = '[Bring Own Detergent/Powder (-₱15.00)]';
                    break;
                case 'own_softener':
                    $discount = 10.00;
                    $suppliesLabel = '[Bring Own Fabric Softener (-₱10.00)]';
                    break;
                case 'own_both':
                    $discount = 25.00;
                    $suppliesLabel = '[Bring Own Powder & Softener (-₱25.00 Combo Discount)]';
                    break;
                default:
                    $discount = 0.00;
                    $suppliesLabel = '[Store Detergent & Softener]';
                    break;
            }

            // Apply Frequent User Card Loyalty Reward Discount if customer requested & eligible
            $targetCustomer = User::find($customerId);
            if ($request->boolean('apply_loyalty_discount') && $targetCustomer && $targetCustomer->hasDiscountReward()) {
                $discount += 50.00;
                $suppliesLabel .= ' [Frequent User Loyalty Discount (-₱50.00)]';
                $targetCustomer->useDiscountReward();
            }

            $totalAmount = max(0, $subtotal - $discount);
            $cutoffNote = now()->format('H:i') >= '16:30' ? ' [Placed past 4:30 PM cut-off time]' : '';
            $notes = trim($suppliesLabel.$cutoffNote.($request->remarks ? ' — '.$request->remarks : ''));

            // Safely parse machine_id: convert empty strings or non-numeric to null for MySQL foreign key safety
            $machineId = ($request->filled('machine_id') && is_numeric($request->machine_id)) ? (int) $request->machine_id : null;

            // Prevent duplicate order submission within 60 seconds
            $existingDuplicate = Order::where('customer_id', $customerId)
                ->where('service_id', $request->service_id)
                ->where('created_at', '>=', now()->subSeconds(60))
                ->first();

            if ($existingDuplicate) {
                $redirectRoute = $isStaffOrAdmin ? 'admin.laundry.index' : 'my.orders';

                return redirect()
                    ->route($redirectRoute)
                    ->with('success', 'Order already submitted! Duplicate order attempt prevented.');
            }

            $order = Order::create([
                'order_number' => 'HW-'.strtoupper(Str::random(8)),
                'customer_id' => $customerId,
                'service_id' => $service->id,
                'machine_id' => $machineId,
                'weight_kg' => $weight,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total_amount' => $totalAmount,
                'order_status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_method' => 'cash',
                'estimated_completion' => now()->addMinutes($service->estimated_minutes),
                'notes' => $notes,
            ]);

            QrCode::create([
                'order_id' => $order->id,
                'qr_token' => Str::uuid(),
                'status' => 'active',
                'expires_at' => now()->addDays(7),
            ]);

            // Eager load customer and service for emails
            $order->load(['customer', 'service']);

            // 1. Send email notification to Customer
            try {
                $customerEmail = $order->customer?->email;
                if (! empty($customerEmail)) {
                    EmailNotificationService::sendStatusEmail($order, $customerEmail);
                }
            } catch (\Throwable $e) {
                Log::error('New order email notification failed: '.$e->getMessage());
            }

            // 2. Send SMS Phone Text Notification to Customer Phone Number
            try {
                SmsNotificationService::sendOrderStatusSms($order);
            } catch (\Throwable $e) {
                Log::error('Customer SMS new order notification failed: '.$e->getMessage());
            }

            if ($isStaffOrAdmin) {
                return redirect()
                    ->route('admin.laundry.index')
                    ->with('success', 'Order #'.$order->order_number.' created successfully for '.($order->customer->name ?? 'Walk-in Customer').'!');
            }

            return redirect()
                ->route('my.orders')
                ->with('success', 'Order submitted successfully. Confirmation email & SMS sent to your phone!');
        } catch (\Throwable $e) {
            Log::error('Order booking failed: '.$e->getMessage());

            return back()->withInput()->with('error', 'Unable to submit order: '.$e->getMessage());
        }
    }

    public function myOrders()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $orders = Order::with(['service', 'qrCode', 'feedback', 'machine'])
            ->where('customer_id', Auth::id())
            ->latest()
            ->get();

        return view(
            'laundry.orders',
            compact('orders')
        );
    }

    public function track(string $qr)
    {
        $qrStr = trim($qr);

        // If input contains full URL or path (e.g. http://localhost:8000/laundry/track/HW884210), extract token
        if (filter_var($qrStr, FILTER_VALIDATE_URL) || Str::contains($qrStr, ['/laundry/track/', 'http'])) {
            $parsedPath = parse_url($qrStr, PHP_URL_PATH);
            if ($parsedPath) {
                $qrStr = basename($parsedPath);
            }
        }

        $cleanQr = ltrim($qrStr, '#');

        // 1. Check if QR belongs to Order QR Token
        $qrCode = QrCode::where('qr_token', $cleanQr)->first();

        if ($qrCode) {
            $order = Order::with(['service', 'customer', 'machine', 'qrCode', 'pickupDelivery', 'statusHistory'])->find($qrCode->order_id);
        } else {
            // 2. Check if code is Order Code or numeric Order ID
            $query = Order::with(['service', 'customer', 'machine', 'qrCode', 'pickupDelivery', 'statusHistory'])
                ->where('order_number', $cleanQr);

            if (is_numeric($cleanQr)) {
                $query->orWhere('id', (int) $cleanQr);
            }

            $order = $query->first();

            // 3. Check if code belongs to Machine Tag (e.g. WM-001)
            if (! $order) {
                $machine = Machine::where('machine_code', $cleanQr)->first();
                if ($machine && $machine->current_order_id) {
                    $order = Order::with(['service', 'customer', 'machine', 'qrCode', 'pickupDelivery', 'statusHistory'])->find($machine->current_order_id);
                }
            }
        }

        if (! $order) {
            return redirect()->route('welcome')->with('error', 'No active order tracking found for QR token / order code: '.$qr);
        }

        /** @var User|null $authUser */
        $authUser = Auth::user();
        $isStaffOrAdmin = $authUser && ($authUser->isStaff() || $authUser->isAdmin() || $authUser->isOwner());

        if ($order->qrCode) {
            try {
                QrScanLog::create([
                    'qr_code_id' => $order->qrCode->id,
                    'order_id' => $order->id,
                    'scanned_by' => $authUser?->id,
                    'scan_type' => $isStaffOrAdmin ? 'staff_scan' : 'customer_scan',
                    'device' => request()->header('User-Agent'),
                    'ip_address' => request()->ip(),
                ]);
            } catch (\Exception $e) {
                Log::warning('Failed to log QR scan: '.$e->getMessage());
            }
        }

        // Customers can only view their own orders; Admin & Staff can view any customer order
        if ($authUser && $authUser->isCustomer()) {
            if ($order->customer_id !== $authUser->id) {
                return redirect()->route('dashboard')->with('error', 'Unauthorized: You are only allowed to view your own order tracking details.');
            }
        }

        return view(
            'laundry.track',
            compact('order')
        );
    }
}
