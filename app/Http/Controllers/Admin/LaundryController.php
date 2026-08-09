<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusUpdated;
use App\Models\Order;
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

        $smsLogs = \App\Models\SmsNotification::with('order')->latest()->take(10)->get();

        return view(
            'admin.laundry.index',
            compact('orders', 'smsLogs')
        );
    }

    public function update(Request $request, Order $order)
    {
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

        // 1. Send Email Notification to Admin
        try {
            $adminEmail = config('mail.from.address', 'karlnicko2019@gmail.com');
            Mail::to($adminEmail)->send(new OrderStatusUpdated($order, 'admin'));
        } catch (\Throwable $e) {
            Log::error('Admin email status notification failed: '.$e->getMessage());
        }

        // 2. Send Email Notification to Customer
        try {
            $customerEmail = $order->customer?->email;
            if (! empty($customerEmail)) {
                Mail::to($customerEmail)->send(new OrderStatusUpdated($order, 'customer'));
            }
        } catch (\Throwable $e) {
            Log::error('Customer email status notification failed: '.$e->getMessage());
        }

        // 3. Send SMS Phone Text Notification to Customer Phone Number
        try {
            \App\Services\SmsNotificationService::sendOrderStatusSms($order);
        } catch (\Throwable $e) {
            Log::error('Customer SMS status notification failed: '.$e->getMessage());
        }

        return back()->with('success', 'Order updated! Loyalty points awarded, Email & SMS sent to customer!');
    }
}
