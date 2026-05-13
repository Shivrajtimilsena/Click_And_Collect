<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f6f6f6; margin: 0; padding: 0; }
        .container { max-width: 680px; margin: 32px auto; background: #ffffff; border: 1px solid #e1e3e3; }
        .header { background: #b12209; padding: 28px 32px; }
        .header h1 { color: #ffffff; font-size: 22px; margin: 0; }
        .body { padding: 32px; }
        .body p { color: #2d2f2f; line-height: 1.6; font-size: 15px; margin: 0 0 14px; }
        .label { display: block; font-size: 12px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #5a5c5c; margin-bottom: 6px; }
        .panel { background: #f0f1f1; border: 1px solid #e1e3e3; padding: 16px; margin-bottom: 16px; }
        .message { white-space: pre-wrap; }
        .footer { background: #f0f1f1; padding: 18px 32px; font-size: 12px; color: #5c5b5b; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Contact Enquiry</h1>
        </div>

        <div class="body">
            <div class="panel">
                <span class="label">From</span>
                <p>{{ $enquiry['name'] }} ({{ $enquiry['email'] }})</p>

                <span class="label">Customer Type</span>
                <p>{{ ucfirst($enquiry['customer_type']) }}</p>

                <span class="label">Preferred Contact</span>
                <p>{{ ucfirst($enquiry['preferred_contact']) }}</p>

                <span class="label">Phone</span>
                <p>{{ $enquiry['phone'] ?: 'Not provided' }}</p>

                <span class="label">Order Reference</span>
                <p>{{ $enquiry['order_reference'] ?: 'Not provided' }}</p>
            </div>

            <span class="label">Subject</span>
            <p>{{ $enquiry['subject'] }}</p>

            <span class="label">Message</span>
            <p class="message">{{ $enquiry['message'] }}</p>
        </div>

        <div class="footer">
            Submitted from the Click&Collect contact page.
        </div>
    </div>
</body>
</html>
