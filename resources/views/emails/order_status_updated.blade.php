<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HourWash Order Notification</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F1F5F9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">

    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #F1F5F9; padding: 30px 15px;">
        <tr>
            <td align="center">
                
                <!-- Main Container Card -->
                <table width="100%" max-width="600" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; background-color: #FFFFFF; border-radius: 20px; border: 1px solid #E2E8F0; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.06);">
                    
                    <!-- Header Bar -->
                    <tr>
                        <td align="center" style="background-color: #0F172A; padding: 28px 20px;">
                            <h1 style="margin: 0; font-size: 22px; font-weight: 800; color: #38BDF8; letter-spacing: 1px; font-family: 'Outfit', sans-serif;">
                                🧺 HOUR WASH LAUNDRY
                            </h1>
                            <p style="margin: 4px 0 0 0; font-size: 11px; color: #94A3B8; letter-spacing: 0.5px;">
                                Self-Service & Drop-Off Laundry Systems • Legazpi City
                            </p>
                        </td>
                    </tr>

                    <!-- Status Notification Banner -->
                    <tr>
                        <td align="center" style="padding: 30px 24px 10px 24px;">
                            <h2 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 700; color: #0F172A;">
                                Order Status Update Notification
                            </h2>
                            <p style="margin: 0; font-size: 13px; color: #64748B; line-height: 1.5;">
                                This email notifies you that Order <strong style="color: #0284C7;">#{{ $order->order_number }}</strong> has moved to the next stage:
                            </p>
                            
                            <!-- Dynamic Status Pill Badge -->
                            <div style="margin-top: 16px;">
                                @php
                                    $st = strtolower($order->order_status);
                                    $bg = '#0284C7';
                                    if ($st === 'completed' || $st === 'delivered') $bg = '#16A34A';
                                    elseif ($st === 'ready') $bg = '#D97706';
                                    elseif ($st === 'cancelled') $bg = '#DC2626';
                                @endphp
                                <span style="display: inline-block; background-color: {{ $bg }}; color: #FFFFFF; font-weight: 800; font-size: 12px; padding: 8px 20px; border-radius: 9999px; text-transform: uppercase; letter-spacing: 1.5px;">
                                    {{ str_replace('_', ' ', $order->order_status) }}
                                </span>
                            </div>
                        </td>
                    </tr>

                    <!-- Order Details Table -->
                    <tr>
                        <td style="padding: 20px 24px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 14px; padding: 12px 16px;">
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #E2E8F0; font-size: 13px; color: #64748B; width: 45%;">
                                        Order Code:
                                    </td>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #E2E8F0; font-size: 13px; font-weight: 700; color: #0284C7; text-align: right;">
                                        #{{ $order->order_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #E2E8F0; font-size: 13px; color: #64748B;">
                                        Customer Name:
                                    </td>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #E2E8F0; font-size: 13px; font-weight: 700; color: #0F172A; text-align: right;">
                                        {{ $order->customer->name ?? 'Walk-in Customer' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #E2E8F0; font-size: 13px; color: #64748B;">
                                        Customer Email:
                                    </td>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #E2E8F0; font-size: 13px; font-weight: 600; color: #0284C7; text-align: right;">
                                        {{ $order->customer->email ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #E2E8F0; font-size: 13px; color: #64748B;">
                                        Service Package:
                                    </td>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #E2E8F0; font-size: 13px; font-weight: 700; color: #0F172A; text-align: right;">
                                        {{ $order->service->name ?? 'Standard Wash' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #E2E8F0; font-size: 13px; color: #64748B;">
                                        Laundry Weight:
                                    </td>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #E2E8F0; font-size: 13px; font-weight: 600; color: #0F172A; text-align: right;">
                                        {{ $order->weight_kg }} kg
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #E2E8F0; font-size: 13px; color: #64748B;">
                                        Total Amount:
                                    </td>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #E2E8F0; font-size: 14px; font-weight: 800; color: #16A34A; text-align: right;">
                                        ₱{{ number_format($order->total_amount, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #E2E8F0; font-size: 13px; color: #64748B;">
                                        Payment Status:
                                    </td>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #E2E8F0; font-size: 13px; font-weight: 700; color: {{ $order->payment_status === 'paid' ? '#16A34A' : '#D97706' }}; text-align: right; text-transform: uppercase;">
                                        {{ strtoupper($order->payment_status ?? 'unpaid') }}
                                    </td>
                                </tr>
                                @if($order->estimated_completion)
                                <tr>
                                    <td style="padding: 10px 0; font-size: 13px; color: #64748B;">
                                        Est. Completion:
                                    </td>
                                    <td style="padding: 10px 0; font-size: 13px; font-weight: 700; color: #0F172A; text-align: right;">
                                        {{ \Carbon\Carbon::parse($order->estimated_completion)->format('M d, Y h:i A') }}
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </td>
                    </tr>

                    <!-- Scannable QR Code Section -->
                    <tr>
                        <td align="center" style="padding: 10px 24px 24px 24px;">
                            <table border="0" cellspacing="0" cellpadding="0" style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 14px; padding: 16px; text-align: center; width: 100%;">
                                <tr>
                                    <td align="center">
                                        <div style="background-color: #FFFFFF; padding: 10px; border-radius: 12px; border: 1px solid #CBD5E1; display: inline-block;">
                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $order->qrCode->qr_token ?? $order->order_number }}" 
                                                 alt="Scannable QR Tag #{{ $order->order_number }}" 
                                                 width="130" height="130" style="display: block; border-radius: 8px;">
                                        </div>
                                        <p style="margin: 8px 0 0 0; font-size: 11px; font-weight: 700; color: #0284C7; font-family: monospace;">
                                            SCANNABLE QR TAG: #{{ $order->order_number }}
                                        </p>
                                        <p style="margin: 4px 0 12px 0; font-size: 11px; color: #64748B;">
                                            Scan this QR tag with any camera to inspect 5-stage cleaning progress
                                        </p>
                                        
                                        <!-- Direct Track Link Button -->
                                        <a href="{{ url('/laundry/track/' . ($order->qrCode->qr_token ?? $order->order_number)) }}" 
                                           style="display: inline-block; background-color: #0284C7; color: #FFFFFF; text-decoration: none; font-weight: 700; font-size: 12px; padding: 10px 24px; border-radius: 10px; shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                            📱 Click Here to Track Live Order Progress
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 20px; font-size: 11px; color: #64748B; line-height: 1.5;">
                            Store Location: <strong>Magallanes St., Orosite, Legazpi City, Albay</strong><br>
                            © {{ date('Y') }} Hour Wash Laundry Management System • CAT College Capstone Project
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
