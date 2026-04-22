@extends('app')

@section('title', 'Order #' . $order->id . ' | Click&Collect')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-extrabold">Order #{{ $order->id }}</h1>
        <span class="px-4 py-2 rounded-full text-sm font-bold {{ $order->status === 'pending' ? 'bg-orange-100 text-orange-700' : ($order->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700') }}">
            {{ ucfirst($order->status) }}
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Order Info -->
        <div class="bg-surface-container-lowest rounded-lg p-6">
            <h3 class="font-bold text-lg mb-4">Order Information</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-on-surface-variant font-bold">Order Date</p>
                    <p class="font-bold">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <p class="text-xs text-on-surface-variant font-bold">Total Amount</p>
                    <p class="text-2xl font-extrabold">${{ number_format($order->total_price, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Collection Details -->
        <div class="bg-surface-container-lowest rounded-lg p-6">
            <h3 class="font-bold text-lg mb-4">Collection Details</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-on-surface-variant font-bold">Shop</p>
                    <p class="font-bold">{{ $order->collectionSlot->shop->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-on-surface-variant font-bold">Collection Time</p>
                    <p class="font-bold">{{ $order->collectionSlot->start_time->format('M d, h:i A') }}</p>
                </div>
                <div>
                    <p class="text-xs text-on-surface-variant font-bold">to {{ $order->collectionSlot->end_time->format('h:i A') }}</p>
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        @if ($order->payment)
            <div class="bg-surface-container-lowest rounded-lg p-6">
                <h3 class="font-bold text-lg mb-4">Payment Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-on-surface-variant font-bold">Payment Method</p>
                        <p class="font-bold">{{ ucfirst($order->payment->payment_method) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-on-surface-variant font-bold">Status</p>
                        <p class="font-bold text-green-600">{{ ucfirst($order->payment->status) }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Order Items -->
    <div class="bg-surface-container-lowest rounded-lg p-6 mb-8">
        <h2 class="text-xl font-extrabold mb-6">Order Items</h2>
        <div class="space-y-4">
            @foreach ($order->items as $item)
                <div class="flex items-center justify-between pb-4 border-b border-surface-container last:border-b-0">
                    <div class="flex items-center gap-4">
                        <img src="{{ $item->product->image ?? 'https://via.placeholder.com/80' }}" 
                             alt="{{ $item->product->name }}" class="w-20 h-20 rounded-lg object-cover"/>
                        <div>
                            <p class="font-bold">{{ $item->product->name }}</p>
                            <p class="text-sm text-on-surface-variant">{{ $item->product->shop->name }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-on-surface-variant">Qty: {{ $item->quantity }}</p>
                        <p class="font-bold">${{ number_format($item->price, 2) }}</p>
                        <p class="text-primary font-extrabold">${{ number_format($item->price * $item->quantity, 2) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Order Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Customer Info -->
        <div class="bg-surface-container-lowest rounded-lg p-6">
            <h3 class="font-bold text-lg mb-4">Customer Information</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-on-surface-variant font-bold">Name</p>
                    <p class="font-bold">{{ $order->customer->user->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-on-surface-variant font-bold">Email</p>
                    <p class="font-bold">{{ $order->customer->user->email }}</p>
                </div>
                <div>
                    <p class="text-xs text-on-surface-variant font-bold">Phone</p>
                    <p class="font-bold">{{ $order->customer->phone ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Price Summary -->
        <div class="bg-surface-container-lowest rounded-lg p-6">
            <h3 class="font-bold text-lg mb-4">Price Summary</h3>
            @php
                $subtotal = $order->items->sum(fn($item) => $item->price * $item->quantity);
                $tax = $subtotal * 0.1;
            @endphp
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span>${{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Tax (10%)</span>
                    <span>${{ number_format($tax, 2) }}</span>
                </div>
                <div class="flex justify-between font-extrabold text-lg border-t border-surface-container pt-3">
                    <span>Total</span>
                    <span>${{ number_format($order->total_price, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 flex gap-4">
        <a href="{{ route('orders.index') }}" class="flex-1 border-2 border-primary text-primary px-6 py-3 rounded-lg font-bold text-center hover:bg-primary/5">
            Back to Orders
        </a>
        <a href="{{ route('products.index') }}" class="flex-1 bg-primary text-on-primary px-6 py-3 rounded-lg font-bold text-center hover:opacity-90">
            Continue Shopping
        </a>
    </div>
</div>
@endsection
