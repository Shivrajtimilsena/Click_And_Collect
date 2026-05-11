@extends('app')

@section('title', 'Order Confirmed | Click&Collect')

@section('content')
<div class="max-w-3xl mx-auto py-12 text-center">
    <div class="mb-8">
        <span class="material-symbols-outlined text-7xl text-green-500" style="font-size: 80px;">check_circle</span>
        <h1 class="text-4xl font-headline font-extrabold text-on-surface mt-4">Order Confirmed!</h1>
        <p class="text-secondary text-lg mt-2">Your payment was successful. Your orders are being prepared.</p>
    </div>

    @if($slot)
    <div class="bg-primary/5 border border-primary/20 p-6 mb-8 inline-block text-left">
        <div class="flex items-center gap-2 text-sm">
            <span class="material-symbols-outlined text-primary">schedule</span>
            <span class="font-bold">Collection:</span>
            <span>{{ $slot->slot_day }}, {{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }}</span>
        </div>
    </div>
    @endif

    @if($paypalTxnId)
    <p class="text-xs text-secondary mb-8">PayPal Transaction ID: {{ $paypalTxnId }}</p>
    @endif

    <div class="text-left space-y-4 mb-10">
        @foreach($orders as $order)
        <div class="bg-surface-container-lowest border border-surface-container-high p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center bg-surface-container-high">
                        <span class="material-symbols-outlined text-secondary">store</span>
                    </div>
                    <div>
                        <p class="font-bold text-on-surface">{{ $order->collectionSlot?->shop->shop_name ?? 'Shop' }}</p>
                        <p class="text-xs text-secondary">#ORD-{{ $order->order_id }}</p>
                    </div>
                </div>
                <span class="font-bold text-on-surface">&pound;{{ number_format($order->total_amount, 2) }}</span>
            </div>
            <div class="space-y-2">
                @foreach($order->items as $item)
                <div class="flex justify-between text-sm">
                    <span class="text-secondary">{{ $item->quantity }}x {{ $item->product->product_name }}</span>
                    <span class="text-on-surface">&pound;{{ number_format($item->line_total, 2) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <div class="bg-surface-container-lowest border border-surface-container-high p-6 flex justify-between items-center">
            <span class="font-bold text-lg text-on-surface">Total Paid</span>
            <span class="font-headline font-extrabold text-2xl text-primary">&pound;{{ number_format($combinedTotal, 2) }}</span>
        </div>
    </div>

    <a href="{{ route('home') }}" class="inline-block bg-primary text-on-primary px-10 py-4 font-bold text-lg hover:opacity-90 transition-all">
        Shop More
    </a>
</div>
@endsection
