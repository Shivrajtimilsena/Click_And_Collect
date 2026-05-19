<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderPlaced extends Notification
{
    use Queueable;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $customer = $this->order->customer?->user;

        return [
            'order_id' => $this->order->order_id,
            'group_id' => $this->order->group_id,
            'customer_name' => $customer?->full_name ?? 'Unknown',
            'customer_email' => $customer?->email ?? '',
            'total' => $this->order->total_amount,
            'shop_name' => $this->order->shop?->shop_name ?? 'Unknown Shop',
        ];
    }
}
