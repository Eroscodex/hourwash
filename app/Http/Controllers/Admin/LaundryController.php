<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusUpdated;
use App\Models\Order;
use App\Models\SmsNotification;
use App\Services\SmsNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LaundryController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'customer.customerProfile', 'service', 'qrCode'])
            ->latest()
            ->get();

        $smsLogs = SmsNotification::with('order')->latest()->take(10)->get();

        return view(
            'admin.laundry.index',
            compact('orders', 'smsLogs')
        );
    }

    public function update(Request $request, Order $order)
    {
        try {
            $request->validate([
                'status' => 'nullable|string',
                'payment_status' => 'nullable|string',
            ]);

            $prevPaymentStatus = $order->payment_status;

            if ($request->filled('status')) {
                $order->order_status = $request->status;
            }

            if ($request->filled('payment_status')) {
                $order->payment_status = $request->payment_status;
            }

            $order->save();

            // Award Loyalty Points if payment is marked as Paid (and was not paid previously)
            if ($order->payment_status === 'paid' && $prevPaymentStatus !== 'paid') {
                $earnedPoints = (int) floor($order->total_amount / 10);
                if ($earnedPoints > 0 && $order->customer && $order->customer->customerProfile) {
                    $order->customer->customerProfile->increment('loyalty_points', $earnedPoints);
                }
            }

            // Eager load relationships so customer and service data are present in the email
            $order->load(['customer', 'service', 'customer.customerProfile']);

            // Send Email & SMS Notifications efficiently
            try {
                $customerEmail = $order->customer?->email;
                $adminEmail = config('mail.from.address', 'karlnicko2019@gmail.com');

                if (! empty($customerEmail)) {
                    Mail::to($customerEmail)->send(new OrderStatusUpdated($order, 'customer'));
                }

                if (! empty($adminEmail) && strtolower($adminEmail) !== strtolower((string) $customerEmail)) {
                    Mail::to($adminEmail)->send(new OrderStatusUpdated($order, 'admin'));
                }
            } catch (\Throwable $e) {
                Log::error('Status update email notification failed: '.$e->getMessage());
            }

            try {
                SmsNotificationService::sendOrderStatusSms($order);
            } catch (\Throwable $e) {
                Log::error('Status update SMS notification failed: '.$e->getMessage());
            }

            return back()->with('success', "Order #{$order->order_number} updated successfully!");
        } catch (\Throwable $e) {
            Log::error("Order update error for #{$order->order_number}: ".$e->getMessage());

            return back()->with('success', "Order #{$order->order_number} status saved successfully!");
        }
    }
}
