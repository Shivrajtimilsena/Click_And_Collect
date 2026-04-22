@extends('app')

@section('title', 'My Orders | Click&Collect')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-extrabold mb-8">My Orders</h1>

    @if ($orders->count() > 0)
        <div class="space-y-4">
            @foreach ($orders as $order)
                <div class="bg-surface-container-lowest rounded-lg p-6 hover:shadow-lg transition-all">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-sm text-on-surface-variant">Order #{{ $order->id }}</p>
                            <h3 class="text-lg font-bold">{{ $order->items->count() }} Items</h3>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-extrabold">${{ number_format($order->total_price, 2) }}</p>
                            <p class="text-sm {{ $order->status === 'pending' ? 'text-orange-600' : ($order->status === 'completed' ? 'text-green-600' : 'text-on-surface-variant') }} font-bold mt-1">
                                {{ ucfirst($order->status) }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 py-4 border-y border-surface-container mb-4">
                        <div>
                            <p class="text-xs text-on-surface-variant font-bold">COLLECTION POINT</p>
                            <p class="font-bold">{{ $order->collectionSlot->shop->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-on-surface-variant font-bold">COLLECTION TIME</p>
                            <p class="font-bold">{{ $order->collectionSlot->start_time->format('M d, h:i A') }}</p>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('orders.show', $order) }}" class="flex-1 bg-primary text-on-primary px-4 py-3 rounded-lg font-bold text-center hover:opacity-90">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center py-8">
            {{ $orders->links() }}
        </div>
    @else
        <div class="bg-surface-container-lowest rounded-lg p-12 text-center">
            <span class="material-symbols-outlined text-6xl text-on-surface-variant mb-4 block">receipt_long</span>
            <p class="text-on-surface-variant text-lg mb-6">No orders yet</p>
            <a href="{{ route('products.index') }}" class="bg-primary text-on-primary px-6 py-3 rounded-lg font-bold hover:opacity-90">
                Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection
