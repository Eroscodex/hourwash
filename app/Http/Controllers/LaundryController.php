<?php

namespace App\Http\Controllers;

use App\Mail\OrderStatusUpdated;
use App\Models\Machine;
use App\Models\Order;
use App\Models\QrCode;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
        $subtotal = $service->price * $request->weight_kg;

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

        // 1. Send email notification to Admin
        try {
            $adminEmail = config('mail.from.address', 'karlnicko2019@gmail.com');
            Mail::to($adminEmail)->send(new OrderStatusUpdated($order, 'admin'));
        } catch (\Throwable $e) {
            Log::error('Admin email new order notification failed: '.$e->getMessage());
        }

        // 2. Send email notification to Customer
        try {
            $customerEmail = $order->customer?->email ?? auth()->user()?->email;
            if (! empty($customerEmail)) {
                Mail::to($customerEmail)->send(new OrderStatusUpdated($order, 'customer'));
            }
        } catch (\Throwable $e) {
            Log::error('Customer email new order notification failed: '.$e->getMessage());
        }

        // 3. Send SMS Phone Text Notification to Customer Phone Number
        try {
            \App\Services\SmsNotificationService::sendOrderStatusSms($order);
        } catch (\Throwable $e) {
            Log::error('Customer SMS new order notification failed: '.$e->getMessage());
        }

        return redirect()
            ->route('my.orders')
            ->with('success', 'Order submitted successfully. Confirmation email & SMS sent to your phone!');
    }

    public function myOrders()
    {
        $orders = Order::with(['service', 'qrCode'])
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
            return redirect()->route('welcome')->with('error', 'No active order telemetry found for QR token / machine tag: '.$qr);
        }

        return view(
            'laundry.track',
            compact('order')
        );
    }
}
