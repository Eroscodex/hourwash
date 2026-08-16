<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\SmsNotificationService;
use Illuminate\Http\Request;

class RiderDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Rider Analytics & Dispatch Metrics (EXCLUDING Walk-in / Drop-off orders)
        $riderPickupRequests = Order::whereIn('pickup_type', ['pickup_delivery', 'pickup'])
            ->whereIn('order_status', ['pending', 'out_for_pickup'])
            ->count();

        $riderReceivedCount = Order::whereIn('pickup_type', ['pickup_delivery', 'pickup'])
            ->where('order_status', 'received')
            ->count();

        $riderDeliveryCount = Order::whereIn('pickup_type', ['pickup_delivery', 'pickup'])
            ->where('order_status', 'out_for_delivery')
            ->count();

        $riderCompletedCount = Order::whereIn('pickup_type', ['pickup_delivery', 'pickup'])
            ->where('order_status', 'completed')
            ->count();

        $riderCancelledCount = Order::whereIn('pickup_type', ['pickup_delivery', 'pickup'])
            ->where('order_status', 'cancelled')
            ->count();

        $pickupOrders = Order::with(['customer.customerProfile', 'service'])
            ->whereIn('pickup_type', ['pickup_delivery', 'pickup'])
            ->whereIn('order_status', ['pending', 'out_for_pickup'])
            ->latest()
            ->get();

        $deliveryOrders = Order::with(['customer.customerProfile', 'service'])
            ->whereIn('pickup_type', ['pickup_delivery', 'pickup'])
            ->where('order_status', 'out_for_delivery')
            ->latest()
            ->get();

        $completedTodayCount = $riderCompletedCount;
        $totalActiveTasks = $pickupOrders->count() + $deliveryOrders->count();

        return view('rider.dashboard', compact(
            'user',
            'pickupOrders',
            'deliveryOrders',
            'completedTodayCount',
            'totalActiveTasks',
            'riderPickupRequests',
            'riderReceivedCount',
            'riderDeliveryCount',
            'riderCompletedCount',
            'riderCancelledCount'
        ));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:received,completed',
        ]);

        $newStatus = $validated['status'];
        $order->order_status = $newStatus;

        if ($newStatus === 'completed') {
            $order->completed_at = now();
        }

        $order->save();

        // Trigger automated SMS to customer
        try {
            SmsNotificationService::sendOrderStatusSms(
                $order,
                $newStatus === 'received' ? 'Laundry collected by rider.' : 'Laundry safely delivered.'
            );
        } catch (\Throwable $e) {
            // Logged inside SmsNotificationService
        }

        $message = $newStatus === 'received'
            ? "Order #{$order->order_number} marked as RECEIVED! Customer notified via SMS."
            : "Order #{$order->order_number} marked as DELIVERED & COMPLETED! Customer notified via SMS.";

        return redirect()->route('rider.dashboard')->with('success', $message);
    }
}
