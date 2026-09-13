<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\SmsNotification;
use App\Models\User;
use App\Services\EmailNotificationService;
use App\Services\SmsNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LaundryController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'customer.customerProfile', 'service', 'qrCode', 'machine'])
            ->latest()
            ->get();

        $machines = Machine::orderBy('id', 'asc')->get();
        $smsLogs = SmsNotification::with('order')->latest()->take(10)->get();

        return view(
            'admin.laundry.index',
            compact('orders', 'smsLogs', 'machines')
        );
    }

    public function update(Request $request, Order $order)
    {
        try {
            $request->validate([
                'status' => 'nullable|string',
                'order_status' => 'nullable|string',
                'payment_status' => 'nullable|string',
                'machine_id' => 'nullable',
            ]);

            $previousStatus = $order->order_status;
            $prevPaymentStatus = $order->payment_status;
            $statusInput = $request->input('status') ?? $request->input('order_status');

            $hasMachineInput = $request->has('machine_id');
            $manualMachineId = ($hasMachineInput && $request->filled('machine_id')) ? (int) $request->machine_id : null;

            if (! empty($statusInput)) {
                $order->order_status = $statusInput;

                try {
                    OrderStatusHistory::create([
                        'order_id' => $order->id,
                        'status' => $statusInput,
                        'changed_by' => Auth::id(),
                        'notes' => 'Order status updated to '.strtoupper(str_replace('_', ' ', $statusInput)),
                        'created_at' => now(),
                    ]);
                } catch (\Throwable $e) {
                    Log::warning('Failed to log order status history: '.$e->getMessage());
                }

                // Start the service timer when the active processing stage begins.
                if ($statusInput === 'washing') {
                    $order->estimated_completion = now()->addMinutes($order->service?->estimatedDurationMinutes() ?? 35);
                } elseif ($statusInput === 'finish' && $order->service?->service_type === 'fold') {
                    $order->estimated_completion = now()->addMinutes($order->service?->estimatedDurationMinutes() ?? 15);
                }

                if ($statusInput === 'completed' && $previousStatus !== 'completed') {
                    $order->completed_at = now();
                }
            }

            // Sync assigned machine dynamically (auto-assign washer for wash, dryer for dry, release for fold/finish/done)
            $order->syncMachineAssignment($statusInput ?: $order->order_status, $manualMachineId, $hasMachineInput);

            if ($request->filled('payment_status')) {
                $order->payment_status = $request->payment_status;
            }

            $order->save();

            // Award 1 Frequent User stamp if order completed
            if (! empty($statusInput) && $statusInput === 'completed' && $previousStatus !== 'completed') {
                $customer = User::find($order->customer_id);
                if ($customer) {
                    $customer->addStamp(1);
                }
            }

            // Eager load relationships so customer and service data are present in notifications
            $order->load(['customer', 'service', 'customer.customerProfile', 'machine']);

            // Send Email & SMS Notifications efficiently
            try {
                $customerEmail = $order->customer?->email;
                if (empty($customerEmail) && $order->customer_id) {
                    $customerEmail = User::find($order->customer_id)?->email;
                }

                if (! empty($customerEmail)) {
                    EmailNotificationService::sendStatusEmail($order, $customerEmail);
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

            return back()->with('error', "Error updating Order #{$order->order_number}: ".$e->getMessage());
        }
    }
}
