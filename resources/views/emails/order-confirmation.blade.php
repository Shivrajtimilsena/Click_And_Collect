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
        .meta { background: #f0f1f1; padding: 12px 16px; margin: 16px 0; font-size: 13px; color: #2d2f2f; }
        .order { border: 1px solid #e3e3e3; margin: 18px 0; }
        .order-header { background: #fafafa; padding: 12px 16px; font-weight: bold; }
        .items { width: 100%; border-collapse: collapse; }
        .items th, .items td { text-align: left; padding: 10px 16px; border-top: 1px solid #eeeeee; font-size: 14px; }
        .items th { background: #fafafa; font-size: 13px; text-transform: uppercase; letter-spacing: 0.4px; }
        .amount { text-align: right; white-space: nowrap; }
        .order-total { padding: 12px 16px; text-align: right; font-weight: bold; background: #fafafa; }
        .grand-total { background: #f0f1f1; padding: 16px; margin-top: 20px; text-align: right; font-weight: bold; }
        .footer { background: #f0f1f1; padding: 24px 32px; text-align: center; font-size: 12px; color: #5c5b5b; }
        .btn { display: inline-block; background: #b12209; color: #ffffff; padding: 12px 32px; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Confirmed</h1>
        </div>
        <div class="body">
            <p>Hi {{ $user->full_name }},</p>
            <p>Thank you for your order. Your payment has been received and your items are being prepared.</p>

            @if($slot)
            <div class="meta">
                <strong>Collection:</strong>
                {{ $slot->slot_day }}, {{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }}
            </div>
            @endif

            @if($paypalTxnId)
            <div class="meta">
                <strong>PayPal Transaction ID:</strong> {{ $paypalTxnId }}
            </div>
            @endif

            @foreach($orders as $order)
            <div class="order">
                <div class="order-header">
                    {{ $order->collectionSlot?->shop->shop_name ?? 'Shop' }}
                    <span style="float: right;">#ORD-{{ $order->order_id }}</span>
                </div>
                <table class="items">
                    <thead>
                        <tr>
                            <th style="width: 55%;">Item</th>
                            <th style="width: 15%;">Qty</th>
                            <th style="width: 30%;" class="amount">Line Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="amount">{{ $currencySymbol }}{{ number_format($item->line_total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="order-total">
                    Shop Total: {{ $currencySymbol }}{{ number_format($order->total_amount, 2) }}
                </div>
            </div>
            @endforeach

            <div class="grand-total">
                Total Paid: {{ $currencySymbol }}{{ number_format($combinedTotal, 2) }}
            </div>

            <p style="text-align: center; margin-top: 24px;">
                <a href="{{ url('/') }}" class="btn">Shop More</a>
            </p>

            <p>If you have any questions, feel free to contact us.</p>
            <p>Thanks again!<br>The Click&Collect Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Click&Collect. All rights reserved.
        </div>
    </div>
</body>
</html>
