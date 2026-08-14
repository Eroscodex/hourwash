<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\Order;
use App\Models\QrCode;
use App\Models\Service;
use App\Services\EmailNotificationService;
use App\Services\SmsNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LaundryController extends Controller
{
    public function create()
    {
        $services = Service::where('status', 'active')->get();

        return view('laundry.create', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'weight_kg' => 'required|numeric|min:0.5',
        ]);

        $service = Service::findOrFail($request->service_id);

        // Calculate subtotal: multiply by weight only if price_unit is 'kg'
        if ($service->price_unit === 'kg') {
            $subtotal = $service->price * $request->weight_kg;
        } else {
            $subtotal = $service->price;
        }

        // Prevent duplicate order submission within 60 seconds
        $existingDuplicate = Order::where('customer_id', auth()->id())
            ->where('service_id', $request->service_id)
            ->where('created_at', '>=', now()->subSeconds(60))
            ->first();

        if ($existingDuplicate) {
            return redirect()
                ->route('my.orders')
                ->with('success', 'Order already submitted! Duplicate order attempt prevented.');
        }

        $order = Order::create([
            'order_number' => 'HW-'.strtoupper(Str::random(8)),
            'customer_id' => auth()->id(),
            'service_id' => $request->service_id,
            'weight_kg' => $request->weight_kg,
            'subtotal' => $subtotal,
            'total_amount' => $subtotal,
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
            'estimated_completion' => now()->addMinutes($service->estimated_minutes),
            'notes' => $request->remarks,
        ]);

        QrCode::create([
            'order_id' => $order->id,
            'qr_token' => Str::uuid(),
            'status' => 'active',
            'expires_at' => now()->addDays(7),
        ]);

        // Eager load customer and service for emails
        $order->load(['customer', 'service']);

        // 1. Send email notification to Customer & Admin
        try {
            $customerEmail = $order->customer?->email ?? auth()->user()?->email;
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
            $order = Order::with(['service', 'customer', 'machine', 'qrCode'])->find($qrCode->order_id);
        } else {
            // 2. Check if code is Order Code or Order ID
            $order = Order::with(['service', 'customer', 'machine', 'qrCode'])
                ->where('order_number', $cleanQr)
                ->orWhere('id', $cleanQr)
                ->first();

            // 3. Check if code belongs to Machine Tag (e.g. WM-001)
            if (! $order) {
                $machine = Machine::where('machine_code', $cleanQr)->first();
                if ($machine && $machine->current_order_id) {
                    $order = Order::with(['service', 'customer', 'machine', 'qrCode'])->find($machine->current_order_id);
                }
            }
        }

        if (! $order) {
            return redirect()->route('welcome')->with('error', 'No active order tracking found for QR token / machine tag: '.$qr);
        }

        return view(
            'laundry.track',
            compact('order')
        );
    }
}
