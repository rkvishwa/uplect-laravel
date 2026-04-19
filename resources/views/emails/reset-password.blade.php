<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset your password</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f5;font-family:Inter,system-ui,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f4f4f5;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width:480px;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(15,23,42,0.08);">
                    <tr>
                        <td style="padding:32px 32px 8px;text-align:center;">
                            <div style="display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#4338ca,#6366f1);color:#fff;font-weight:700;font-size:22px;font-family:Inter,system-ui,sans-serif;">U</div>
                            <h1 style="margin:20px 0 8px;font-size:22px;color:#18181b;">Reset your password</h1>
                            <p style="margin:0;font-size:15px;line-height:1.6;color:#52525b;">Hi {{ $user->name }}, we received a request to reset your Uplect password. Click the button below to choose a new one.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:8px 32px 32px;text-align:center;">
                            <a href="{{ $resetUrl }}" style="display:inline-block;padding:14px 28px;background:linear-gradient(135deg,#4338ca,#6366f1);color:#ffffff;text-decoration:none;font-weight:600;font-size:15px;border-radius:12px;">Reset password</a>
                            <p style="margin:20px 0 0;font-size:13px;line-height:1.5;color:#71717a;">This link expires in 60 minutes. If you didn’t request a reset, you can safely ignore this email.</p>
                        </td>
                    </tr>
                </table>
                <p style="margin:24px 0 0;font-size:12px;color:#a1a1aa;">Uplect Learning Management</p>
            </td>
        </tr>
    </table>
</body>
</html>
