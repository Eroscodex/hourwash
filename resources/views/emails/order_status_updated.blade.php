<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HourWash Order Notification</title>
</head>
<body style="margin: 0; padding: 0; background-color: #FFFFFF; font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'SF Pro Text', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1D1D1F; -webkit-font-smoothing: antialiased;">

    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #FFFFFF; padding: 40px 20px;">
        <tr>
            <td align="center">
                
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 580px; text-align: left;">
                    
                    <!-- Header Top Bar (Favicon Picture Logo + Title) -->
                    <tr>
                        <td style="padding-bottom: 24px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="left" valign="middle" style="width: 44px;">
                                        <img src="{{ url('favicon.png') }}" alt="Hour Wash Logo" width="36" height="36" style="display: block; border-radius: 8px;">
                                    </td>
                                    <td align="right" valign="middle" style="font-size: 22px; font-weight: 400; color: #86868B; letter-spacing: -0.5px;">
                                        Order Status Update
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    @php
                        $st = strtolower($order->order_status);
                        $statusLabel = match($st) {
                            'pending' => 'Order Placed (Pending)',
                            'out_for_pickup' => 'Out for Pickup',
                            'received' => 'Store Received',
                            'washing' => 'Washing Cycle Active',
                            'rinsing' => 'Rinsing Cycle Active',
                            'drying' => 'Drying Cycle Active',
                            'finish', 'folding' => 'FOLDING & READY FOR PICKUP',
                            'out_for_delivery' => 'Out for Delivery',
                            'completed' => 'Order Completed',
                            'cancelled' => 'Order Cancelled',
                            default => strtoupper(str_replace('_', ' ', $order->order_status))
                        };
                        $custFirstName = explode(' ', trim($order->customer->name ?? 'Customer'))[0];
                    @endphp

                    <!-- Apple-style Light Grey Hero Banner Card -->
                    <tr>
                        <td style="padding-bottom: 32px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #F5F5F7; border-radius: 16px; padding: 24px;">
                                <tr>
                                    <td valign="top" style="width: 80px; padding-right: 20px;">
                                        <div style="width: 72px; height: 72px; background-color: #FFFFFF; border-radius: 18px; border: 1px solid rgba(0,0,0,0.06); text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.04); overflow: hidden;">
                                            <img src="{{ url('favicon.png') }}" alt="Hour Wash Laundry" width="48" height="48" style="display: block; margin: 12px auto; border-radius: 10px;">
                                        </div>
                                    </td>
                                    <td valign="middle">
                                        <h2 style="margin: 0; font-size: 18px; font-weight: 700; color: #1D1D1F; letter-spacing: -0.3px;">
                                            {{ $order->service->name ?? 'Hour Wash Laundry' }}
                                        </h2>
                                        <p style="margin: 4px 0 6px 0; font-size: 13px; color: #86868B; font-weight: 500;">
                                            Order #{{ $order->order_number }} • {{ $order->weight_kg }} kg
                                        </p>
                                        <p style="margin: 0; font-size: 13px; color: #1D1D1F; font-weight: 600;">
                                            <span style="color: #2563EB;">{{ $statusLabel }}</span> — ₱{{ number_format($order->total_amount, 2) }}
                                        </p>
                                        @if($order->estimated_completion)
                                            <p style="margin: 4px 0 0 0; font-size: 12px; color: #86868B;">
                                                Est: {{ \Carbon\Carbon::parse($order->estimated_completion)->format('d M Y • h:i A') }}
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Email Body Paragraphs -->
                    <tr>
                        <td style="font-size: 14px; line-height: 1.6; color: #1D1D1F; padding-bottom: 24px;">
                            <p style="margin: 0 0 16px 0;">
                                Dear {{ $custFirstName }},
                            </p>

                            <p style="margin: 0 0 16px 0; color: #333336;">
                                Thank you for choosing Hour Wash Laundry Shop. We wanted to let you know that your laundry order <strong>#{{ $order->order_number }}</strong> has been updated to <strong>{{ $statusLabel }}</strong>.
                            </p>

                            @if(in_array($st, ['finish', 'folding', 'ready', 'ready_for_pickup', 'shelved_and_tagged']))
                                <p style="margin: 0 0 16px 0; background-color: #FEF3C7; border-left: 4px solid #F59E0B; padding: 12px 16px; border-radius: 8px; color: #78350F; font-weight: 600;">
                                    <strong>YOUR LAUNDRY IS FINISHED & FOLDED!</strong><br>
                                    PLEASE CLAIM YOUR LAUNDRY ORDER AT OUR STORE COUNTER (Magallanes St., Orosite, Legazpi City).
                                </p>
                            @endif

                            <p style="margin: 0 0 24px 0; color: #333336;">
                                To inspect live cleaning progress, view receipt, or scan your QR code tag, <a href="{{ url('/laundry/track/' . ($order->qrCode->qr_token ?? $order->order_number)) }}" style="color: #0066CC; text-decoration: underline; font-weight: 500;">track your order live</a>.
                            </p>

                            <p style="margin: 0; color: #1D1D1F;">
                                Sincerely,<br>
                                <strong>Hour Wash Team</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Minimalist Footer Divider & Links -->
                    <tr>
                        <td align="center" style="border-top: 1px solid #E5E5EA; padding-top: 32px; padding-bottom: 20px;">
                            <img src="{{ url('favicon.png') }}" alt="Hour Wash Logo" width="24" height="24" style="display: block; margin: 0 auto 16px auto; border-radius: 6px;">

                            <p style="margin: 0 0 12px 0; font-size: 12px; color: #0066CC;">
                                <a href="{{ url('/laundry/track/' . ($order->qrCode->qr_token ?? $order->order_number)) }}" style="color: #0066CC; text-decoration: none; font-weight: 500;">Track Order</a>
                                <span style="color: #86868B; margin: 0 6px;">•</span>
                                <a href="https://maps.google.com/?q=Magallanes+St+Orosite+Legazpi+City" style="color: #0066CC; text-decoration: none; font-weight: 500;">Store Location</a>
                                <span style="color: #86868B; margin: 0 6px;">•</span>
                                <a href="{{ url('/privacy') }}" style="color: #0066CC; text-decoration: none; font-weight: 500;">Privacy Policy</a>
                            </p>

                            <p style="margin: 0; font-size: 11px; color: #86868B; line-height: 1.4;">
                                Magallanes St., Orosite, Legazpi City, Albay • (052) 800-WASH<br>
                                Copyright © {{ date('Y') }} Hour Wash Laundry Management System. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
