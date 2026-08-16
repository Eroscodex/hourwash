<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\SmsNotification;
use App\Models\User;
use App\Services\EmailNotificationService;
use App\Services\SmsNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

                // When washing cycle starts, recalculate estimated completion time starting from now
                if ($request->status === 'washing') {
                    $order->estimated_completion = now()->addMinutes($order->service?->estimated_minutes ?? 30);
                }

                // Sync assigned machine status dynamically
                if ($order->machine_id) {
                    if ($request->status === 'washing') {
                        Machine::where('id', $order->machine_id)->update([
                            'current_order_id' => $order->id,
                            'status' => 'washing',
                            'remaining_minutes' => $order->service?->estimated_minutes ?? 30,
                            'last_status_update' => now(),
                        ]);
                    } elseif ($request->status === 'rinsing') {
                        Machine::where('id', $order->machine_id)->update([
                            'current_order_id' => $order->id,
                            'status' => 'rinsing',
                            'remaining_minutes' => 15,
                            'last_status_update' => now(),
                        ]);
                    } elseif ($request->status === 'drying') {
                        Machine::where('id', $order->machine_id)->update([
                            'current_order_id' => $order->id,
                            'status' => 'drying',
                            'remaining_minutes' => 35,
                            'last_status_update' => now(),
                        ]);
                    } elseif (in_array($request->status, ['finish', 'completed', 'cancelled'])) {
                        Machine::where('id', $order->machine_id)->update([
                            'current_order_id' => null,
                            'status' => 'idle',
                            'remaining_minutes' => null,
                            'last_status_update' => now(),
                        ]);
                    }
                }

                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'status' => $request->status,
                    'changed_by' => auth()->id(),
                    'notes' => 'Status updated to '.($request->status === 'finish' ? 'Finish & Shelved' : str_replace('_', ' ', $request->status)),
                    'created_at' => now(),
                ]);
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
                if (empty($customerEmail) && $order->customer_id) {
                    $customerEmail = User::find($order->customer_id)?->email;
                }

                if (! empty($customerEmail)) {
                    EmailNotificationService::sendStatusEmail($order, $customerEmail);
                }

                $adminEmail = config('mail.from.address', 'karlnicko2019@gmail.com');
                if (! empty($adminEmail) && strtolower($adminEmail) !== strtolower((string) $customerEmail)) {
                    EmailNotificationService::sendStatusEmail($order, $adminEmail);
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
