<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f6f6f6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #ffffff; }
        .header { background: #b12209; padding: 32px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 24px; margin: 0; }
        .body { padding: 32px; }
        .body p { color: #2d2f2f; line-height: 1.6; font-size: 15px; }
        .credentials { background: #f0f1f1; padding: 16px; margin: 20px 0; }
        .credentials p { margin: 4px 0; }
        .footer { background: #f0f1f1; padding: 24px 32px; text-align: center; font-size: 12px; color: #5c5b5b; }
        .btn { display: inline-block; background: #b12209; color: #ffffff; padding: 12px 32px; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Your Trader Account is Approved!</h1>
        </div>
        <div class="body">
            <p>Dear {{ $user->full_name }},</p>
            <p>Congratulations! Your trader application has been approved.</p>
            <p><strong>Your login credentials:</strong></p>
            <div class="credentials">
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Temporary Password:</strong> {{ $plainPassword }}</p>
            </div>
            <p><strong>Important:</strong> This is a one-time password. Please change it after your first login for security.</p>
            <p style="text-align: center; margin-top: 24px;">
                <a href="{{ url('/trader/dashboard') }}" class="btn">Go to Trader Dashboard</a>
            </p>
            <p>Welcome aboard!<br>The Click&Collect Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Click&Collect. All rights reserved.
        </div>
    </div>
</body>
</html>
