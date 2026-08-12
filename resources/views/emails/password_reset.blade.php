<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HourWash - Reset Your Password</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F1F5F9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #F1F5F9; padding: 30px 15px;">
    <tr>
        <td align="center">

            <!-- Main Container Card -->
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; background-color: #FFFFFF; border-radius: 20px; border: 1px solid #E2E8F0; overflow: hidden;">

                <!-- Header Bar -->
                <tr>
                    <td align="center" style="background-color: #0F172A; padding: 28px 20px;">
                        <img
                            src="{{ url('favicon.svg') }}"
                            alt="Hour Wash Logo"
                            width="56"
                            height="56"
                            style="display: block; margin: 0 auto 10px auto; border-radius: 50%; border: 2px solid #38BDF8;"
                        >

                        <h1 style="margin: 0; font-size: 22px; font-weight: 800; color: #38BDF8; letter-spacing: 1px;">
                            HOUR WASH LAUNDRY
                        </h1>

                        <p style="margin: 4px 0 0 0; font-size: 11px; color: #94A3B8; letter-spacing: 0.5px;">
                            Password Reset Request Notification
                        </p>
                    </td>
                </tr>

                <!-- Body Content -->
                <tr>
                    <td align="left" style="padding: 30px 24px;">

                        <h2 style="margin: 0 0 12px 0; font-size: 20px; font-weight: 700; color: #0F172A;">
                            Reset Your Account Password
                        </h2>

                        <p style="margin: 0 0 16px 0; font-size: 14px; color: #475569; line-height: 1.6;">
                            Hello,
                        </p>

                        <p style="margin: 0 0 20px 0; font-size: 14px; color: #475569; line-height: 1.6;">
                            You are receiving this email because we received a password reset request for your
                            <strong>HourWash</strong> account registered under
                            <strong>{{ $email }}</strong>.
                        </p>

                        <!-- CTA Button -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: 28px 0;">
                            <tr>
                                <td align="center">
                                    <a
                                        href="{{ $url }}"
                                        target="_blank"
                                        style="display: inline-block; background-color: #007AFF; color: #FFFFFF; font-size: 14px; font-weight: 700; text-decoration: none; padding: 14px 32px; border-radius: 12px;"
                                    >
                                        Reset Password Now →
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <p style="margin: 20px 0 0 0; font-size: 13px; color: #64748B; line-height: 1.5;">
                            This password reset link will expire in <strong>60 minutes</strong>.
                        </p>

                        <p style="margin: 8px 0 0 0; font-size: 13px; color: #64748B; line-height: 1.5;">
                            If you did not request a password reset, no further action is required and your account remains safe.
                        </p>

                        <!-- URL Direct Link Box -->
                        <div style="margin-top: 28px; padding: 16px; background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; font-size: 11px; color: #64748B; word-break: break-all;">
                            <p style="margin: 0 0 6px 0; font-weight: 600; color: #334155;">
                                If you are having trouble clicking the "Reset Password Now" button, copy and paste the link below into your web browser:
                            </p>

                            <a href="{{ $url }}" style="color: #007AFF; text-decoration: underline;">
                                {{ $url }}
                            </a>
                        </div>

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td align="center" style="background-color: #F8FAFC; padding: 20px; border-top: 1px solid #E2E8F0;">
                        <p style="margin: 0; font-size: 11px; color: #94A3B8;">
                            © {{ date('Y') }} Hour Wash Laundry Shop • Legazpi City, Albay. All rights reserved.
                        </p>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
