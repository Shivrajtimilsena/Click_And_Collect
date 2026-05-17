<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f6f6f6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; overflow: hidden; }
        .header { background: #b12209; padding: 32px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 24px; margin: 0; }
        .body { padding: 32px; }
        .body p { color: #2d2f2f; line-height: 1.6; font-size: 15px; }
        .reason-box { background: #f8f4f4; border-left: 4px solid #b12209; padding: 16px; margin: 20px 0; }
        .reason-box p { margin: 0; font-size: 14px; }
        .footer { background: #f0f1f1; padding: 24px 32px; text-align: center; font-size: 12px; color: #5c5b5b; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Application Status</h1>
        </div>
        <div class="body">
            <p>Dear {{ $application->shop_name }},</p>

            <p>Thank you for taking the time to apply to become a trader on Click&Collect. We truly appreciate your interest in joining our community.</p>

            <p>After careful consideration, we regret to inform you that we are unable to approve your application at this time.</p>

            @if ($application->admin_notes)
                <div class="reason-box">
                    <p><strong>Reason for this decision:</strong></p>
                    <p>{{ $application->admin_notes }}</p>
                </div>
            @endif

            <p>This decision does not prevent you from reapplying in the future. If your business circumstances change or you believe there has been a misunderstanding, we encourage you to submit a new application.</p>

            <p>If you have any questions or need further clarification, please feel free to reach out to us at <a href="mailto:support@cleckhuddersfax-local.co.uk" style="color: #b12209;">support@cleckhuddersfax-local.co.uk</a>.</p>

            <p>We wish you all the best in your endeavours.</p>

            <p>Warm regards,<br>The Click&Collect Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Click&Collect. All rights reserved.
        </div>
    </div>
</body>
</html>
