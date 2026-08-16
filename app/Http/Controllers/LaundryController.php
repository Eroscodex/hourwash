<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\QrCode;
use App\Models\Service;
use App\Models\User;
use App\Services\EmailNotificationService;
use App\Services\SmsNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class LaundryController extends Controller
{
    public function create()
    {
        $services = Service::where('status', 'active')->get();
        $availableMachines = Machine::where('status', 'idle')->orderBy('id', 'asc')->get();
        $customers = User::where('role', 'customer')->orderBy('name', 'asc')->get();

        return view('laundry.create', compact('services', 'availableMachines', 'customers'));
    }

    public function store(Request $request)
    {
        $isStaffOrAdmin = auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOwner() || auth()->user()->isStaff());

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'weight_kg' => 'required|numeric|min:0.5|max:24.0',
            'machine_id' => 'nullable|exists:machines,id',
            'supplies_option' => 'nullable|string|in:store_provided,own_detergent,own_softener,own_both',
            'customer_id' => 'nullable|exists:users,id',
            'new_customer_name' => 'required_if:customer_mode,new|nullable|string|max:255',
            'new_customer_email' => 'nullable|email|max:255|unique:users,email',
            'new_customer_phone' => 'nullable|string|max:50',
            'new_customer_address' => 'nullable|string|max:255',
        ]);

        $customerId = auth()->id();

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
                        'address' => $request->new_customer_address ?: 'Magallanes St., Orosite, Legazpi City',
                    ]);
                }

                $customerId = $newCust->id;
            } elseif ($request->filled('customer_id')) {
                $customerId = $request->customer_id;
            }
        }

        $service = Service::findOrFail($request->service_id);

        // Calculate subtotal: multiply by weight only if price_unit is 'kg'
        if ($service->price_unit === 'kg') {
            $subtotal = $service->price * $request->weight_kg;
        } else {
            $subtotal = $service->price;
        }

        // Calculate supplies discount (Tipid option: Customer brings own detergent/softener)
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
                $suppliesLabel = '[Bring Own Powder & Softener (-₱25.00 Tipid Combo)]';
                break;
            default:
                $discount = 0.00;
                $suppliesLabel = '[Store Detergent & Softener]';
                break;
        }

        $totalAmount = max(0, $subtotal - $discount);
        $notes = trim($suppliesLabel.($request->remarks ? ' — '.$request->remarks : ''));

        // Auto-assign available machine if not selected
        $machineId = $request->machine_id;
        if (! $machineId) {
            $firstAvailable = Machine::where('status', 'idle')->first();
            $machineId = $firstAvailable?->id;
        }

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
            'service_id' => $request->service_id,
            'machine_id' => $machineId,
            'weight_kg' => $request->weight_kg,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total_amount' => $totalAmount,
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
            'estimated_completion' => now()->addMinutes($service->estimated_minutes),
            'notes' => $notes,
        ]);

        if ($machineId) {
            Machine::where('id', $machineId)->update([
                'current_order_id' => $order->id,
                'status' => 'idle',
                'remaining_minutes' => null,
            ]);
        }

        QrCode::create([
            'order_id' => $order->id,
            'qr_token' => Str::uuid(),
            'status' => 'active',
            'expires_at' => now()->addDays(7),
        ]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'pending',
            'changed_by' => auth()->id(),
            'notes' => 'Order created and submitted.',
            'created_at' => now(),
        ]);

        // Eager load customer and service for emails
        $order->load(['customer', 'service']);

        // 1. Send email notification to Customer & Admin
        try {
            $customerEmail = $order->customer?->email;
            if (! empty($customerEmail)) {
                EmailNotificationService::sendStatusEmail($order, $customerEmail);
            }

            $adminEmail = config('mail.from.address', 'karlnicko2019@gmail.com');
            if (! empty($adminEmail) && strtolower($adminEmail) !== strtolower((string) $customerEmail)) {
                EmailNotificationService::sendStatusEmail($order, $adminEmail);
            }
        } catch (\Throwable $e) {
            Log::error('New order email notification failed: '.$e->getMessage());
        }

        // 3. Send SMS Phone Text Notification to Customer Phone Number
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
    }

    public function myOrders()
    {
        $orders = Order::with(['service', 'qrCode', 'feedback'])
            ->where('customer_id', auth()->id())
            ->latest()
            ->get();

        return view(
            'laundry.orders',
            compact('orders')
        );
    }

    public function track($qr)
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
            $order = Order::with(['service', 'customer', 'machine', 'qrCode', 'statusHistory'])->find($qrCode->order_id);
        } else {
            // 2. Check if code is Order Code or Order ID
            $order = Order::with(['service', 'customer', 'machine', 'qrCode', 'statusHistory'])
                ->where('order_number', $cleanQr)
                ->orWhere('id', $cleanQr)
                ->first();

            // 3. Check if code belongs to Machine Tag (e.g. WM-001)
            if (! $order) {
                $machine = Machine::where('machine_code', $cleanQr)->first();
                if ($machine && $machine->current_order_id) {
                    $order = Order::with(['service', 'customer', 'machine', 'qrCode', 'statusHistory'])->find($machine->current_order_id);
                }
            }
        }

        if (! $order) {
            return redirect()->route('welcome')->with('error', 'No active order tracking found for QR token / machine tag: '.$qr);
        }

        // Customers can only view their own orders; Admin & Staff can view any customer order
        if (auth()->check() && auth()->user()->isCustomer()) {
            if ($order->customer_id !== auth()->id()) {
                return redirect()->route('dashboard')->with('error', 'Unauthorized: You are only allowed to view your own order tracking details.');
            }
        }

        return view(
            'laundry.track',
            compact('order')
        );
    }
}
