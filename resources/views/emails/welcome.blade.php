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
        .footer { background: #f0f1f1; padding: 24px 32px; text-align: center; font-size: 12px; color: #5c5b5b; }
        .btn { display: inline-block; background: #b12209; color: #ffffff; padding: 12px 32px; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Click&Collect!</h1>
        </div>
        <div class="body">
            <p>Hi {{ $user->full_name }},</p>
            <p>Thank you for registering with Click&Collect. Your account has been created successfully.</p>
            <p>You can now browse products from local shops, add items to your cart, and place orders for convenient collection.</p>
            <p style="text-align: center; margin-top: 24px;">
                <a href="{{ url('/') }}" class="btn">Start Shopping</a>
            </p>
            <p>If you have any questions, feel free to contact us.</p>
            <p>Happy shopping!<br>The Click&Collect Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Click&Collect. All rights reserved.
        </div>
    </div>
</body>
</html>
