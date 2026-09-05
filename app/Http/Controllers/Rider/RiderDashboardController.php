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

        // Scoping closure: Only include orders requiring Rider Pickup & Delivery
        $riderOrderScope = function ($q) {
            $q->whereIn('pickup_type', ['pickup_delivery', 'pickup', 'delivery'])
                ->orWhereHas('service', function ($sq) {
                    $sq->where('name', 'like', '%Pickup%')
                        ->orWhere('name', 'like', '%Delivery%');
                });
        };

        // Rider Analytics & Dispatch Metrics
        $riderPickupRequests = Order::whereIn('order_status', ['pending', 'out_for_pickup', 'picked_up'])
            ->where($riderOrderScope)
            ->count();

        $riderReceivedCount = Order::where('order_status', 'received')
            ->where($riderOrderScope)
            ->count();

        $riderDeliveryCount = Order::where('order_status', 'out_for_delivery')
            ->where($riderOrderScope)
            ->count();

        $riderCompletedCount = Order::where('order_status', 'completed')
            ->where($riderOrderScope)
            ->count();

        $riderCancelledCount = Order::where('order_status', 'cancelled')
            ->where($riderOrderScope)
            ->count();

        $pickupOrders = Order::with(['customer.customerProfile', 'service', 'pickupDelivery', 'machine', 'statusHistory', 'qrCode'])
            ->whereIn('order_status', ['pending', 'out_for_pickup', 'picked_up'])
            ->where($riderOrderScope)
            ->latest()
            ->get();

        $inShopOrders = Order::with(['customer.customerProfile', 'service', 'pickupDelivery', 'machine', 'statusHistory', 'qrCode'])
            ->whereIn('order_status', ['received', 'washing', 'rinsing', 'drying', 'finish'])
            ->where($riderOrderScope)
            ->latest()
            ->get();

        $deliveryOrders = Order::with(['customer.customerProfile', 'service', 'pickupDelivery', 'machine', 'statusHistory', 'qrCode'])
            ->whereIn('order_status', ['finish', 'out_for_delivery', 'delivered'])
            ->where($riderOrderScope)
            ->latest()
            ->get();

        $completedTodayOrders = Order::where('order_status', 'completed')
            ->whereDate('completed_at', now()->today())
            ->where($riderOrderScope)
            ->get();

        $completedTodayCount = $completedTodayOrders->count();

        // Active Orders across Pickup, In-Shop, and Delivery queues for Rider
        $allActiveOrders = $pickupOrders->concat($inShopOrders)->concat($deliveryOrders)->unique('id');

        // Delivery fee earnings: ₱50 per completed or active dispatch
        $completedFees = $completedTodayOrders->sum(function ($ord) {
            return $ord->delivery_fee > 0 ? (float) $ord->delivery_fee : 50.00;
        });

        $activeFees = $allActiveOrders->count() * 50.00;
        $todayDeliveryFees = ($completedFees + $activeFees) > 0 ? ($completedFees + $activeFees) : 0.00;

        // COD Cash Collected for paid rider orders (today or total paid dispatches)
        $todayCodCollected = Order::where($riderOrderScope)
            ->where('payment_status', 'paid')
            ->where(function ($q) {
                $q->whereDate('paid_at', now()->today())
                    ->orWhereDate('completed_at', now()->today())
                    ->orWhereDate('updated_at', now()->today());
            })
            ->sum('total_amount');

        // Fallback to all-time paid rider orders if today query is zero but paid rider orders exist
        if ($todayCodCollected <= 0) {
            $todayCodCollected = Order::where($riderOrderScope)
                ->where('payment_status', 'paid')
                ->sum('total_amount');
        }

        // Pending COD to Collect ONLY for active unpaid Pickup & Delivery rider orders
        $pendingCodToCollect = Order::where($riderOrderScope)
            ->whereNotIn('order_status', ['completed', 'cancelled'])
            ->where('payment_status', '!=', 'paid')
            ->sum('total_amount');

        $totalActiveTasks = $allActiveOrders->count();

        $completedHistoryOrders = Order::with(['customer.customerProfile', 'service', 'pickupDelivery', 'machine', 'statusHistory', 'qrCode'])
            ->where('order_status', 'completed')
            ->where($riderOrderScope)
            ->latest()
            ->get();

        $cancelledHistoryOrders = Order::with(['customer.customerProfile', 'service', 'pickupDelivery', 'machine', 'statusHistory', 'qrCode'])
            ->where('order_status', 'cancelled')
            ->where($riderOrderScope)
            ->latest()
            ->get();

        return view('rider.dashboard', compact(
            'user',
            'pickupOrders',
            'inShopOrders',
            'deliveryOrders',
            'completedHistoryOrders',
            'cancelledHistoryOrders',
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
            'status' => 'required|string|in:out_for_pickup,picked_up,received,out_for_delivery,delivered,completed',
            'proof_image' => 'nullable|file|max:25600',
        ]);

        $newStatus = $validated['status'];

        try {
            $order->order_status = $newStatus;

            if ($request->filled('payment_status') && in_array($request->payment_status, ['paid', 'unpaid'])) {
                $order->payment_status = $request->payment_status;
                if ($request->payment_status === 'paid' && ! $order->paid_at) {
                    $order->paid_at = now();
                }
            }

            if ($newStatus === 'completed') {
                $order->completed_at = now();
            }

            $order->save();

            // Status Note & SMS Message mapping
            $statusNotes = [
                'out_for_pickup' => 'Rider is out for pickup. Heading to customer location.',
                'picked_up' => 'Laundry picked up from customer by rider.',
                'received' => 'Laundry received at store.',
                'out_for_delivery' => 'Clean laundry is out for delivery with rider.',
                'delivered' => 'Clean laundry handed over to customer successfully.',
                'completed' => 'Order completed and archived to history.',
            ];

            $customerName = $order->customer?->name ?? 'Customer';
            $smsMessages = [
                'out_for_pickup' => "Hi {$customerName}! Our rider is now OUT FOR PICKUP and heading to your location for Order #{$order->order_number}.",
                'picked_up' => "Order #{$order->order_number} has been picked up by our rider! Transporting to Hour Wash shop.",
                'received' => "Order #{$order->order_number} has been received at Hour Wash store and is ready for washing.",
                'out_for_delivery' => "Good news! Order #{$order->order_number} is OUT FOR DELIVERY and heading to your doorstep.",
                'delivered' => "Order #{$order->order_number} has been DELIVERED to your doorstep! Thank you for choosing Hour Wash Laundry.",
                'completed' => "Order #{$order->order_number} has been marked COMPLETED. We appreciate your business!",
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
                try {
                    $file = $request->file('proof_image');
                    if ($file && $file->isValid()) {
                        $uploadDir = public_path('uploads/proofs');
                        if (! file_exists($uploadDir)) {
                            @mkdir($uploadDir, 0777, true);
                        }
                        $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
                        if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'heic', 'heif', 'jfif'])) {
                            $ext = 'jpg';
                        }
                        $filename = 'proof_'.$order->id.'_'.time().'_'.Str::random(6).'.'.$ext;
                        $file->move($uploadDir, $filename);
                        $proofPath = 'uploads/proofs/'.$filename;
                    }
                } catch (\Throwable $e) {
                    Log::error('Rider proof photo upload exception: '.$e->getMessage());
                }
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
            } elseif ($newStatus === 'picked_up') {
                $pickupDeliveryData['status'] = 'picked_up';
                $pickupDeliveryData['picked_up_at'] = now();
                if ($proofPath) {
                    $pickupDeliveryData['pickup_proof_image'] = $proofPath;
                }
            } elseif ($newStatus === 'received') {
                $pickupDeliveryData['status'] = 'in_shop';
                if ($proofPath) {
                    $pickupDeliveryData['pickup_proof_image'] = $proofPath;
                }
            } elseif ($newStatus === 'out_for_delivery') {
                $pickupDeliveryData['status'] = 'out_for_delivery';
            } elseif ($newStatus === 'delivered') {
                $pickupDeliveryData['status'] = 'delivered';
                $pickupDeliveryData['delivered_at'] = now();
                if ($proofPath) {
                    $pickupDeliveryData['delivery_proof_image'] = $proofPath;
                }
            } elseif ($newStatus === 'completed') {
                $pickupDeliveryData['status'] = 'delivered';
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
                    $newStatus,
                    $smsMessages[$newStatus] ?? "Order #{$order->order_number} status updated to: {$newStatus}."
                );
            } catch (\Throwable $e) {
                Log::warning('Rider updateStatus SMS dispatch notice: '.$e->getMessage());
            }

            $statusLabels = [
                'pending' => 'PENDING',
                'out_for_pickup' => 'OUT FOR PICKUP',
                'picked_up' => 'PICKUP SUCCESSFUL',
                'received' => 'IN SHOP',
                'washing' => 'WASHING',
                'rinsing' => 'RINSING',
                'drying' => 'DRYING',
                'finish' => 'DONE',
                'out_for_delivery' => 'OUT FOR DELIVERY',
                'delivered' => 'DELIVERY SUCCESSFUL',
                'completed' => 'COMPLETED',
                'cancelled' => 'CANCELLED',
            ];

            $message = "Order #{$order->order_number} updated to ".($statusLabels[$newStatus] ?? strtoupper($newStatus)).'! Customer notified via SMS.';

            return redirect()->route('rider.dashboard')->with('success', $message);
        } catch (\Throwable $e) {
            Log::error('Rider updateStatus error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return redirect()->route('rider.dashboard')->with('error', "Order #{$order->order_number} update error: ".$e->getMessage());
        }
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:paid,unpaid',
        ]);

        $order->payment_status = $validated['payment_status'];
        $order->paid_at = $validated['payment_status'] === 'paid' ? now() : null;
        $order->save();

        $msg = $validated['payment_status'] === 'paid'
            ? "Order #{$order->order_number} marked as PAID! COD cash collection recorded."
            : "Order #{$order->order_number} payment status updated to UNPAID.";

        return redirect()->route('rider.dashboard')->with('success', $msg);
    }
}
