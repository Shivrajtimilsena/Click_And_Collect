<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Manrope', Arial, sans-serif; background: #f6f6f6; margin: 0; padding: 0; }
        .container { max-width: 480px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; }
        .header { padding: 32px; background: #b12209; }
        .header h1 { color: #ffffff; font-size: 20px; margin: 0; font-weight: 800; }
        .body { padding: 32px; text-align: center; }
        .body p { color: #5c5b5b; font-size: 14px; line-height: 1.6; margin: 0 0 16px; }
        .code { font-size: 36px; font-weight: 900; letter-spacing: 8px; color: #b12209; margin: 24px 0; padding: 16px; background: #fef6f4; border-radius: 12px; display: inline-block; }
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
            <p>Hi there,</p>
            <p>Use the following code to reset your password:</p>
            <div class="code">{{ $code }}</div>
            <p>This code will expire in 10 minutes.</p>
            <p>If you didn't request this, you can safely ignore this email.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Click &amp; Collect. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
