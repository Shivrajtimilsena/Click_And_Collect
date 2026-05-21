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
        .tag { background: #f0f1f1; padding: 20px; margin: 20px 0; text-align: center; }
        .tag-label { color: #5c5b5b; font-size: 12px; font-weight: bold; letter-spacing: 1.6px; text-transform: uppercase; }
        .tag-value { color: #2d2f2f; font-family: Consolas, Monaco, monospace; font-size: 28px; font-weight: bold; margin-top: 8px; }
        .meta { background: #fafafa; border: 1px solid #eeeeee; padding: 12px 16px; margin: 16px 0; font-size: 13px; color: #2d2f2f; }
        .btn { display: inline-block; background: #b12209; color: #ffffff; padding: 12px 32px; text-decoration: none; font-weight: bold; }
        .footer { background: #f0f1f1; padding: 24px 32px; text-align: center; font-size: 12px; color: #5c5b5b; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Your Pickup Tag Is Ready</h1>
        </div>
        <div class="body">
            <p>Hi {{ $order->customer?->user?->full_name ?? 'Customer' }},</p>
            <p>Your order is ready for RFID collection. Use the pickup tag below at the collection counter.</p>

            <div class="tag">
                <div class="tag-label">RFID Pickup Tag</div>
                <div class="tag-value">{{ $order->rfid_uid }}</div>
            </div>

            <div class="meta">
                <strong>Order:</strong> #ORD-{{ $order->order_id }}<br>
                <strong>Shop:</strong> {{ $order->shop?->shop_name ?? 'Click&Collect shop' }}
                @if($order->collectionSlot)
                    <br>
                    <strong>Collection:</strong>
                    {{ $order->collectionSlot->slot_day }},
                    {{ substr($order->collectionSlot->start_time, 0, 5) }} -
                    {{ substr($order->collectionSlot->end_time, 0, 5) }}
                @endif
            </div>

            <p>When this RFID tag is scanned, the system will automatically mark your order as collected.</p>

            <p style="text-align: center; margin-top: 24px;">
                <a href="{{ route('profile.orders') }}" class="btn">View My Orders</a>
            </p>

            <p>Thanks,<br>The Click&Collect Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Click&Collect. All rights reserved.
        </div>
    </div>
</body>
</html>
