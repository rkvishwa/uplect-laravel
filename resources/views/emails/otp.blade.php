<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify your email</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f5;font-family:Inter,system-ui,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f4f4f5;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width:480px;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(15,23,42,0.08);">
                    <tr>
                        <td style="padding:32px 32px 8px;text-align:center;">
                            <div style="display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#4338ca,#6366f1);color:#fff;font-weight:700;font-size:22px;font-family:Inter,system-ui,sans-serif;">U</div>
                            <h1 style="margin:20px 0 8px;font-size:22px;color:#18181b;">Verify your email</h1>
                            <p style="margin:0;font-size:15px;line-height:1.6;color:#52525b;">Hi {{ $recipientName }}, use this code to finish setting up your Uplect account. It expires in 10 minutes.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:8px 32px 32px;text-align:center;">
                            <div style="letter-spacing:0.35em;font-size:28px;font-weight:700;color:#312e81;font-family:ui-monospace,Menlo,monospace;padding:16px 24px;background:#eef2ff;border-radius:12px;display:inline-block;">{{ $otpCode }}</div>
                            <p style="margin:24px 0 0;font-size:13px;color:#71717a;">If you didn’t create an account, you can ignore this message.</p>
                        </td>
                    </tr>
                </table>
                <p style="margin:24px 0 0;font-size:12px;color:#a1a1aa;">Uplect Learning Management</p>
            </td>
        </tr>
    </table>
</body>
</html>
