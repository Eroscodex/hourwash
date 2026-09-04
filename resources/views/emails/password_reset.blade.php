<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HourWash - Reset Your Password</title>
    <style>
        @media only screen and (max-width: 600px) {
            .email-outer-table { padding: 20px 10px !important; }
            .email-inner-table { width: 100% !important; max-width: 100% !important; }
            .hero-card-td { padding: 16px !important; }
            .footer-copyright { font-size: 10.5px !important; line-height: 1.4 !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #FFFFFF; font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'SF Pro Text', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1D1D1F; -webkit-font-smoothing: antialiased;">

    @php
        $logoSrc = 'https://raw.githubusercontent.com/Eroscodex/hourwash/main/public/favicon.svg';
    @endphp

    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="email-outer-table" style="background-color: #FFFFFF; padding: 40px 20px;">
        <tr>
            <td align="center">

                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="email-inner-table" align="center" style="max-width: 580px; margin: 0 auto; text-align: left;">

                    <!-- Header Top Bar (Favicon Logo + Right Title) -->
                    <tr>
                        <td style="padding-bottom: 24px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="left" valign="middle" style="width: 44px;">
                                        <div style="width: 36px; height: 36px; background-color: #FFFFFF; border-radius: 9px; text-align: center; line-height: 36px; overflow: hidden; border: 1px solid #E2E8F0; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                                            <img src="{{ $logoSrc }}" alt="Hour Wash Logo" width="36" height="36" style="display: block; border-radius: 9px; border: 0; outline: none; width: 36px; height: 36px; object-fit: contain; background-color: #FFFFFF;">
                                        </div>
                                    </td>
                                    <td align="right" valign="middle" style="font-size: 20px; font-weight: 400; color: #86868B; letter-spacing: -0.5px;">
                                        Password Reset Request
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
                                                        Reset Your Account Password
                                                    </h2>
                                                    <p style="margin: 4px 0 6px 0; font-size: 13px; color: #86868B; font-weight: 500;">
                                                        Security Notification • HourWash Account
                                                    </p>
                                                    <p style="margin: 0; font-size: 13px; color: #1D1D1F; font-weight: 600;">
                                                        Account: <span style="color: #2563EB;">{{ $email }}</span>
                                                    </p>
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
                                Hello,
                            </p>

                            <p style="margin: 0 0 16px 0; color: #333336;">
                                You are receiving this email because we received a password reset request for your <strong>HourWash</strong> account registered under <strong>{{ $email }}</strong>.
                            </p>

                            <!-- Primary Action Button -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: 24px 0;">
                                <tr>
                                    <td align="left">
                                        <a href="{{ $url }}" target="_blank" style="display: inline-block; background-color: #2563EB; color: #FFFFFF; font-size: 14px; font-weight: 700; text-decoration: none; padding: 12px 28px; border-radius: 10px; box-shadow: 0 2px 6px rgba(37,99,235,0.25);">
                                            Reset Password Now →
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0 0 12px 0; color: #333336;">
                                This password reset link will expire in <strong>60 minutes</strong>.
                            </p>

                            <p style="margin: 0 0 24px 0; color: #333336;">
                                If you did not request a password reset, no further action is required and your account remains safe.
                            </p>

                            <!-- Direct Link Copy-Paste Fallback Box -->
                            <div style="margin-bottom: 24px; padding: 14px 16px; background-color: #F5F5F7; border: 1px solid #E5E5EA; border-radius: 10px; font-size: 12px; color: #86868B; word-break: break-all;">
                                <p style="margin: 0 0 4px 0; font-weight: 600; color: #1D1D1F;">
                                    If you are having trouble clicking the "Reset Password Now" button, copy and paste the link below into your web browser:
                                </p>
                                <a href="{{ $url }}" style="color: #0066CC; text-decoration: underline; font-weight: 500;">
                                    {{ $url }}
                                </a>
                            </div>

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
                                <a href="{{ route('welcome') }}" style="color: #0066CC; text-decoration: none; font-weight: 500;">Track Order</a>
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
