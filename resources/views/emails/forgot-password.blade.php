<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Manrope', Arial, sans-serif; background: #f6f6f6; margin: 0; padding: 0; }
        .container { max-width: 480px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; }
        .header { padding: 32px; background: #b12209; }
        .header h1 { color: #ffffff; font-size: 20px; margin: 0; font-weight: 800; }
        .body { padding: 32px; }
        .body p { color: #5c5b5b; font-size: 14px; line-height: 1.6; margin: 0 0 16px; }
        .btn { display: inline-block; padding: 14px 32px; background: #b12209; color: #ffffff; text-decoration: none; border-radius: 50px; font-weight: 700; font-size: 14px; margin: 16px 0; }
        .footer { padding: 24px 32px; border-top: 1px solid #e1e3e3; text-align: center; }
        .footer p { color: #767777; font-size: 12px; margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Click &amp; Collect</h1>
        </div>
        <div class="body">
            <p>Hi {{ $user->full_name }},</p>
            <p>We received a request to reset the password for your Click &amp; Collect account. Click the button below to set a new password:</p>
            <p style="text-align: center;">
                <a href="{{ $resetUrl }}" class="btn">Reset Password</a>
            </p>
            <p>Or copy this link into your browser:</p>
            <p style="font-size: 12px; word-break: break-all; color: #b12209;">{{ $resetUrl }}</p>
            <p>If you didn't request this, you can safely ignore this email.</p>
            <p>This link will expire in 60 minutes.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Click &amp; Collect. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
