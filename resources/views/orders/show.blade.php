@extends('app')

@section('title', 'Order Details | Click&Collect')

@section('content')
<div class="max-w-4xl mx-auto py-12">
    @if($groupOrders->count() > 1)
        <div class="flex items-center gap-2 text-sm text-secondary mb-6">
            <a href="{{ route('orders.index') }}" class="hover:underline">&larr; Back to Orders</a>
            <span>&middot; Part of multi-shop order ({{ $groupOrders->count() }} shops)</span>
        </div>
    @else
        <a href="{{ route('orders.index') }}" class="text-sm text-secondary hover:underline mb-6 inline-block">&larr; Back to Orders</a>
    @endif

    @foreach($groupOrders as $groupOrder)
        @php $isCurrent = $groupOrder->order_id === $order->order_id; @endphp
        <div class="@if(!$isCurrent) opacity-70 @endif bg-surface-container-lowest border border-surface-container-high p-8 mb-6 @if($isCurrent) ring-2 ring-primary @endif">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 flex items-center justify-center bg-surface-container-high">
                        <span class="material-symbols-outlined text-secondary text-2xl">store</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-headline font-bold text-on-surface">
                            {{ $groupOrder->shop->shop_name ?? 'Shop' }}
                        </h2>
                        <p class="text-sm text-secondary">Order #{{ $groupOrder->order_id }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @php
                        $statusStyles = [
                            'PENDING' => 'bg-surface-container-high text-secondary',
                            'IN_PROGRESS' => 'bg-blue-100 text-blue-700',
                            'READY' => 'bg-orange-100 text-orange-700',
                            'COMPLETED' => 'bg-green-100 text-green-700',
                            'CANCELLED' => 'bg-red-100 text-red-700',
                        ];
                        $statusStyle = $statusStyles[$groupOrder->order_status] ?? 'bg-surface-container-high text-secondary';
                    @endphp
                    <span class="px-4 py-2 text-xs font-bold uppercase tracking-widest {{ $statusStyle }}">
                        {{ str_replace('_', ' ', $groupOrder->order_status) }}
                    </span>
                    <p class="text-2xl font-headline font-extrabold text-on-surface">&pound;{{ number_format($groupOrder->total_amount, 2) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="font-headline font-bold mb-3">Order Information</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-secondary">Order Date</span>
                            <span class="font-bold">{{ $groupOrder->created_at?->format('M d, Y h:i A') ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-secondary">Subtotal</span>
                            <span class="font-bold">&pound;{{ number_format($groupOrder->order_amount, 2) }}</span>
                        </div>
                        @if($groupOrder->discount_amount > 0)
                        <div class="flex justify-between">
                            <span class="text-secondary">Discount</span>
                            <span class="font-bold text-green-600">-&pound;{{ number_format($groupOrder->discount_amount, 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between border-t border-surface-container-high pt-2 font-bold text-lg">
                            <span>Total</span>
                            <span class="text-primary">&pound;{{ number_format($groupOrder->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="font-headline font-bold mb-3">Collection Details</h3>
                    @if($groupOrder->collectionSlot)
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-secondary">Shop</span>
                            <span class="font-bold">{{ $groupOrder->shop->shop_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-secondary">Day</span>
                            <span class="font-bold">{{ $groupOrder->collectionSlot->slot_day }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-secondary">Time</span>
                            <span class="font-bold">{{ substr($groupOrder->collectionSlot->start_time, 0, 5) }} - {{ substr($groupOrder->collectionSlot->end_time, 0, 5) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-secondary">Date</span>
                            <span class="font-bold">{{ $groupOrder->collectionSlot->slot_date?->format('M d, Y') }}</span>
                        </div>
                    </div>
                    @else
                    <p class="text-secondary text-sm">No collection slot assigned</p>
                    @endif
                </div>
            </div>

            <div class="border-t border-surface-container-high pt-6">
                <h3 class="font-headline font-bold text-lg mb-4">Items</h3>
                <div class="space-y-4">
                    @foreach($groupOrder->items as $item)
                        <div class="flex items-center justify-between pb-4 border-b border-surface-container-high last:border-b-0">
                            <div class="flex items-center gap-4">
                                @if($item->product->image_url)
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->product_name }}" class="w-16 h-16 object-cover border border-surface-container-high"/>
                                @else
                                    <div class="w-16 h-16 flex items-center justify-center bg-surface-container-high">
                                        <span class="material-symbols-outlined text-secondary">image</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold text-on-surface">{{ $item->product->product_name }}</p>
                                    <p class="text-sm text-secondary">&pound;{{ number_format($item->unit_price, 2) }} each</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-secondary">Qty: {{ $item->quantity }}</p>
                                <p class="font-bold text-on-surface">&pound;{{ number_format($item->line_total, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

    @if($groupOrders->count() > 1)
        <div class="bg-surface-container-lowest border border-surface-container-high p-6 mb-6">
            <h3 class="font-headline font-bold text-lg mb-2">Order Summary</h3>
            <p class="text-sm text-secondary mb-4">
                This order contains items from {{ $groupOrders->count() }} shops.
                Each shop prepares their items independently. You can collect all items at the selected time slot.
            </p>
            <div class="space-y-2">
                @foreach($groupOrders as $groupOrder)
                    <div class="flex justify-between text-sm">
                        <span>{{ $groupOrder->shop->shop_name ?? 'Shop #'.$groupOrder->shop_id }}</span>
                        <span class="font-bold">&pound;{{ number_format($groupOrder->total_amount, 2) }}</span>
                    </div>
                @endforeach
                <div class="flex justify-between font-bold text-lg border-t border-surface-container-high pt-2">
                    <span>Combined Total</span>
                    <span class="text-primary">&pound;{{ number_format($groupOrders->sum('total_amount'), 2) }}</span>
                </div>
            </div>
        </div>
    @endif

    <div class="flex gap-4">
        <a href="{{ route('orders.index') }}" class="flex-1 border-2 border-primary text-primary px-6 py-3 font-bold text-center hover:bg-primary/5 transition-all">
            Back to Orders
        </a>
        <a href="{{ route('products.index') }}" class="flex-1 bg-primary text-on-primary px-6 py-3 font-bold text-center hover:opacity-90 transition-all">
            Continue Shopping
        </a>
    </div>
</div>
@endsection