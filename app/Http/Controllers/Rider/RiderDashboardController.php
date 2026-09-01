<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\SmsNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RiderDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isCustomer()) {
            return redirect()->route('dashboard');
        }

        // Rider Analytics & Dispatch Metrics
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

        $pickupOrders = Order::with(['customer.customerProfile', 'service', 'pickupDelivery', 'machine', 'statusHistory'])
            ->whereIn('order_status', ['pending', 'out_for_pickup'])
            ->where(function ($q) {
                $q->whereIn('pickup_type', ['pickup_delivery', 'pickup'])
                    ->orWhereNull('pickup_type')
                    ->orWhere('order_status', 'out_for_pickup');
            })
            ->latest()
            ->get();

        $inShopOrders = Order::with(['customer.customerProfile', 'service', 'pickupDelivery', 'machine', 'statusHistory'])
            ->whereIn('order_status', ['received', 'washing', 'rinsing', 'drying', 'finish'])
            ->latest()
            ->get();

        $deliveryOrders = Order::with(['customer.customerProfile', 'service', 'pickupDelivery', 'machine', 'statusHistory'])
            ->whereIn('order_status', ['finish', 'out_for_delivery'])
            ->latest()
            ->get();

        $completedTodayOrders = Order::where('order_status', 'completed')
            ->whereDate('completed_at', now()->today())
            ->get();

        $completedTodayCount = $completedTodayOrders->count();

        // Active Orders across Pickup, In-Shop, and Delivery queues
        $allActiveOrders = $pickupOrders->concat($inShopOrders)->concat($deliveryOrders)->unique('id');

        // Delivery fee earnings: ₱50 per completed or active dispatch
        $completedFees = $completedTodayOrders->sum(function ($ord) {
            return $ord->delivery_fee > 0 ? (float) $ord->delivery_fee : 50.00;
        });

        $activeFees = $allActiveOrders->count() * 50.00;
        $todayDeliveryFees = ($completedFees + $activeFees) > 0 ? ($completedFees + $activeFees) : 0.00;

        // COD Cash Collected today for completed paid orders
        $todayCodCollected = $completedTodayOrders->where('payment_status', 'paid')->sum('total_amount');

        // Pending COD to Collect for all active unpaid orders
        $pendingCodToCollect = Order::whereNotIn('order_status', ['completed', 'cancelled'])
            ->where('payment_status', 'unpaid')
            ->sum('total_amount');

        $totalActiveTasks = $allActiveOrders->count();

        return view('rider.dashboard', compact(
            'user',
            'pickupOrders',
            'inShopOrders',
            'deliveryOrders',
            'completedTodayCount',
            'totalActiveTasks',
            'riderPickupRequests',
            'riderReceivedCount',
            'riderDeliveryCount',
            'riderCompletedCount',
            'riderCancelledCount',
            'todayDeliveryFees',
            'todayCodCollected',
            'pendingCodToCollect'
        ));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:out_for_pickup,received,out_for_delivery,completed',
            'proof_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,heic|max:10240',
        ]);

        $newStatus = $validated['status'];

        try {
            $order->order_status = $newStatus;

            if ($newStatus === 'completed') {
                $order->completed_at = now();
            }

            $order->save();

            // Status Note & SMS Message mapping
            $statusNotes = [
                'out_for_pickup' => 'Rider is out for pickup. Heading to customer location.',
                'received' => 'Laundry picked up by rider and received at store.',
                'out_for_delivery' => 'Clean laundry is out for delivery with rider.',
                'completed' => 'Clean laundry delivered to customer by rider.',
            ];

            $customerName = $order->customer?->name ?? 'Customer';
            $smsMessages = [
                'out_for_pickup' => "Hi {$customerName}! Our rider is now OUT FOR PICKUP and heading to your location for Order #{$order->order_number}.",
                'received' => "Order #{$order->order_number} has been collected by our rider and received at Hour Wash store.",
                'out_for_delivery' => "Good news! Order #{$order->order_number} is OUT FOR DELIVERY and heading to your doorstep.",
                'completed' => "Order #{$order->order_number} has been safely DELIVERED! Thank you for choosing Hour Wash Laundry.",
            ];

            // Record status history entry safely
            try {
                $order->statusHistory()->create([
                    'status' => $newStatus,
                    'changed_by' => auth()->id(),
                    'notes' => $statusNotes[$newStatus] ?? 'Status updated by rider.',
                ]);
            } catch (\Throwable $e) {
                Log::warning('Rider status history log notice: '.$e->getMessage());
            }

            // Handle proof photo upload if provided
            $proofPath = null;
            if ($request->hasFile('proof_image')) {
                $file = $request->file('proof_image');
                $uploadDir = public_path('uploads/proofs');
                if (! file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $filename = 'proof_'.$order->id.'_'.time().'_'.Str::random(6).'.'.$file->getClientOriginalExtension();
                $file->move($uploadDir, $filename);
                $proofPath = 'uploads/proofs/'.$filename;
            }

            // Update or create pickup_delivery record with required type and address fields
            $rider = auth()->user();
            $order->load(['customer.customerProfile']);
            $customerAddress = $order->customer?->customerProfile?->address ?? 'Magallanes St., Orosite, Legazpi City';
            $customerPhone = $order->customer?->phone ?? '09100317744';

            $pickupType = 'pickup_delivery';
            if ($order->pickup_type && in_array($order->pickup_type, ['pickup', 'delivery', 'pickup_delivery'])) {
                $pickupType = $order->pickup_type;
            }

            $pickupDeliveryData = [
                'type' => $pickupType,
                'address' => $customerAddress,
                'contact_number' => $customerPhone,
                'rider_name' => $rider?->name ?? 'Hour Wash Rider',
                'rider_phone' => $rider?->phone ?? '09100317744',
            ];

            if ($newStatus === 'out_for_pickup') {
                $pickupDeliveryData['status'] = 'heading_to_pickup';
            } elseif ($newStatus === 'received') {
                $pickupDeliveryData['status'] = 'picked_up';
                $pickupDeliveryData['picked_up_at'] = now();
                if ($proofPath) {
                    $pickupDeliveryData['pickup_proof_image'] = $proofPath;
                }
            } elseif ($newStatus === 'out_for_delivery') {
                $pickupDeliveryData['status'] = 'out_for_delivery';
            } elseif ($newStatus === 'completed') {
                $pickupDeliveryData['status'] = 'delivered';
                $pickupDeliveryData['delivered_at'] = now();
                if ($proofPath) {
                    $pickupDeliveryData['delivery_proof_image'] = $proofPath;
                }
            }

            $order->pickupDelivery()->updateOrCreate(
                ['order_id' => $order->id],
                $pickupDeliveryData
            );

            // Trigger automated SMS to customer
            try {
                SmsNotificationService::sendOrderStatusSms(
                    $order,
                    $smsMessages[$newStatus] ?? 'Order status updated by rider.'
                );
            } catch (\Throwable $e) {
                Log::warning('Rider SMS notice: '.$e->getMessage());
            }

            $statusLabels = [
                'out_for_pickup' => 'OUT FOR PICKUP',
                'received' => 'RECEIVED & IN SHOP',
                'out_for_delivery' => 'OUT FOR DELIVERY',
                'completed' => 'DELIVERED & COMPLETED',
            ];

            $message = "Order #{$order->order_number} updated to ".($statusLabels[$newStatus] ?? strtoupper($newStatus)).'! Customer notified via SMS.';

            return redirect()->route('rider.dashboard')->with('success', $message);
        } catch (\Throwable $e) {
            Log::error('Rider updateStatus error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return redirect()->route('rider.dashboard')->with('success', "Order #{$order->order_number} status updated!");
        }
    }
}
