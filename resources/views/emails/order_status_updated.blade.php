<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HourWash Order Notification</title>
    <style>
        @media only screen and (max-width: 600px) {
            .email-outer-table { padding: 20px 10px !important; }
            .email-inner-table { width: 100% !important; max-width: 100% !important; }
            .hero-card-td { padding: 16px !important; }
            .claim-box-td { padding: 12px 14px !important; font-size: 12px !important; }
            .footer-copyright { font-size: 10.5px !important; line-height: 1.4 !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #FFFFFF; font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'SF Pro Text', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1D1D1F; -webkit-font-smoothing: antialiased;">

    @php
        $logoSrc = 'https://raw.githubusercontent.com/Eroscodex/hourwash/main/public/favicon.svg';
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

    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="email-outer-table" style="background-color: #FFFFFF; padding: 40px 20px;">
        <tr>
            <td align="center">

                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="email-inner-table" align="center" style="max-width: 580px; margin: 0 auto; text-align: left;">

                    <!-- Header Top Bar (Favicon Logo + Title) -->
                    <tr>
                        <td style="padding-bottom: 24px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="left" valign="middle" style="width: 44px;">
                                        <div style="width: 36px; height: 36px; background-color: #FFFFFF; border-radius: 9px; text-align: center; line-height: 36px; overflow: hidden; border: 1px solid #E2E8F0; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                                            <img src="{{ $logoSrc }}" alt="Hour Wash Logo" width="36" height="36" style="display: block; border-radius: 9px; border: 0; outline: none; width: 36px; height: 36px; object-fit: contain; background-color: #FFFFFF;">
                                        </div>
                                    </td>
                                    <td align="right" valign="middle" style="font-size: 20px; sm:font-size: 22px; font-weight: 400; color: #86868B; letter-spacing: -0.5px;">
                                        Track Order
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Light Grey Hero Banner Card -->
                    <tr>
                        <td style="padding-bottom: 28px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #F5F5F7; border-radius: 16px;">
                                <tr>
                                    <td class="hero-card-td" style="padding: 24px;">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td valign="top" style="width: 72px; padding-right: 16px;">
                                                    <div style="width: 64px; height: 64px; background-color: #FFFFFF; border-radius: 14px; text-align: center; overflow: hidden; border: 1px solid #E2E8F0; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                                                        <img src="{{ $logoSrc }}" alt="Hour Wash Logo" width="64" height="64" style="display: block; border-radius: 14px; border: 0; outline: none; width: 64px; height: 64px; object-fit: contain; background-color: #FFFFFF;">
                                                    </div>
                                                </td>
                                                <td valign="middle">
                                                    <h2 style="margin: 0; font-size: 17px; font-weight: 700; color: #1D1D1F; letter-spacing: -0.3px;">
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
                                @php
                                    $serviceName = strtolower($order->service?->name ?? '');
                                    $serviceType = strtolower($order->service?->service_type ?? '');

                                    $isWashOnly = str_contains($serviceName, 'wash only') || ($serviceType === 'wash') || (str_contains($serviceName, 'wash') && !str_contains($serviceName, 'dry') && !str_contains($serviceName, 'fold') && !str_contains($serviceName, 'full'));
                                    $isDryOnly = str_contains($serviceName, 'dry only') || ($serviceType === 'dry') || (str_contains($serviceName, 'dry') && !str_contains($serviceName, 'wash') && !str_contains($serviceName, 'full'));
                                    $isFoldOnly = str_contains($serviceName, 'fold only') || ($serviceType === 'fold') || (str_contains($serviceName, 'fold') && !str_contains($serviceName, 'wash') && !str_contains($serviceName, 'dry'));

                                    if ($isWashOnly) {
                                        $claimHeading = 'YOUR LAUNDRY WASHING IS COMPLETED!';
                                        $claimDesc = 'You can now claim your laundry order at our store counter (or await delivery if dispatch was requested).';
                                    } elseif ($isDryOnly) {
                                        $claimHeading = 'YOUR LAUNDRY DRYING IS COMPLETED!';
                                        $claimDesc = 'You can now claim your laundry order at our store counter (or await delivery if dispatch was requested).';
                                    } elseif ($isFoldOnly) {
                                        $claimHeading = 'YOUR LAUNDRY FOLDING IS COMPLETED!';
                                        $claimDesc = 'You can now claim your laundry order at our store counter (or await delivery if dispatch was requested).';
                                    } else {
                                        $claimHeading = 'YOUR LAUNDRY IS FINISHED & FOLDED!';
                                        $claimDesc = 'PLEASE CLAIM YOUR LAUNDRY ORDER AT OUR STORE COUNTER (or await delivery if dispatch was requested).';
                                    }
                                @endphp
                                <!-- Mobile-Responsive Claim Banner Table -->
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 20px;">
                                    <tr>
                                        <td class="claim-box-td" style="background-color: #FEF3C7; border-left: 4px solid #F59E0B; border-radius: 8px; padding: 14px 16px; color: #78350F; font-size: 13px; line-height: 1.5; font-weight: 600;">
                                            <strong style="display: block; font-size: 14px; margin-bottom: 4px; color: #92400E; font-weight: 800;">{{ $claimHeading }}</strong>
                                            {{ $claimDesc }}
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            <p style="margin: 0 0 24px 0; color: #333336;">
                                To inspect live cleaning progress, view receipt, or scan your QR code tag, <a href="{{ url('/laundry/track/' . ($order->qrCode->qr_token ?? $order->order_number)) }}" style="color: #0066CC; text-decoration: underline; font-weight: 500;">track your order live</a>.
                            </p>

                            <p style="margin: 0; color: #1D1D1F;">
                                Sincerely,<br>
                                <strong>Hour Wash</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Minimalist Mobile-Responsive Footer -->
                    <tr>
                        <td align="center" style="border-top: 1px solid #E5E5EA; padding-top: 28px; padding-bottom: 20px;">
                            <div style="width: 28px; height: 28px; background-color: #2563EB; border-radius: 7px; text-align: center; line-height: 28px; color: #FFFFFF; font-weight: 900; font-size: 10px; margin: 0 auto 14px auto; overflow: hidden; box-shadow: 0 2px 4px rgba(37,99,235,0.25);">
                                <img src="{{ $logoSrc }}" alt="HW" width="28" height="28" style="display: block; border-radius: 7px; border: 0; outline: none; width: 28px; height: 28px; object-fit: cover;">
                            </div>

                            <p style="margin: 0 0 12px 0; font-size: 12px; color: #0066CC;">
                                <a href="{{ url('/laundry/track/' . ($order->qrCode->qr_token ?? $order->order_number)) }}" style="color: #0066CC; text-decoration: none; font-weight: 500;">Track Order</a>
                                <span style="color: #86868B; margin: 0 6px;">•</span>
                                <a href="{{ route('privacy') }}" style="color: #0066CC; text-decoration: none; font-weight: 500;">Privacy Policy</a>
                            </p>

                            <p class="footer-copyright" style="margin: 0; font-size: 11px; color: #86868B; line-height: 1.5; text-align: center; word-break: break-word; max-width: 100%;">
                                Copyright © {{ date('Y') }} A Web-Based Laundry Service Management System for Hour Wash Laundry Shop in Orosite, Legazpi City.<br style="display: block;">All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
