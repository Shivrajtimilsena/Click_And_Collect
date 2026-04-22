@extends('app')

@section('title', 'Checkout | Click&Collect')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <h1 class="text-3xl font-extrabold mb-8">Checkout</h1>

        <!-- Collection Slot Selection -->
        <form action="{{ route('orders.store') }}" method="POST" id="checkout-form" class="space-y-6">
            @csrf

            <div class="bg-surface-container-lowest rounded-lg p-6">
                <h2 class="text-xl font-extrabold mb-4">Select Collection Slot</h2>
                
                <div class="space-y-4">
                    @forelse ($collectionSlots as $shopId => $slots)
                        @php
                            $shop = $slots[0]->shop;
                        @endphp
                        <div class="border-2 border-surface-container rounded-lg p-4">
                            <h3 class="font-bold mb-4">{{ $shop->name }}</h3>
                            <div class="space-y-2">
                                @foreach ($slots as $slot)
                                    <label class="flex items-center gap-3 p-3 hover:bg-surface-container rounded cursor-pointer">
                                        <input type="radio" name="collection_slot_id" value="{{ $slot->id }}" required class="w-4 h-4">
                                        <div class="flex-grow">
                                            <p class="font-bold">
                                                {{ $slot->start_time->format('h:i A') }} - {{ $slot->end_time->format('h:i A') }}
                                            </p>
                                            <p class="text-sm text-on-surface-variant">
                                                {{ $slot->max_orders - $slot->current_orders }} slots available
                                            </p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-on-surface-variant">No collection slots available</p>
                    @endforelse
                </div>
            </div>

            <!-- Coupon Code -->
            <div class="bg-surface-container-lowest rounded-lg p-6">
                <h2 class="text-xl font-extrabold mb-4">Promo Code</h2>
                <input type="text" name="coupon_code" placeholder="Enter coupon code" class="w-full px-4 py-3 border border-surface-container rounded-lg"/>
                <p class="text-xs text-on-surface-variant mt-2">Optional: Enter a valid coupon code to apply discount</p>
            </div>

            <!-- Customer Info -->
            <div class="bg-surface-container-lowest rounded-lg p-6">
                <h2 class="text-xl font-extrabold mb-4">Delivery Information</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold mb-2">Full Name</label>
                        <input type="text" value="{{ $customer->user->name }}" readonly class="w-full px-4 py-3 border border-surface-container rounded-lg bg-surface"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2">Email</label>
                        <input type="email" value="{{ $customer->user->email }}" readonly class="w-full px-4 py-3 border border-surface-container rounded-lg bg-surface"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2">Phone</label>
                        <input type="text" value="{{ $customer->phone ?? '' }}" readonly class="w-full px-4 py-3 border border-surface-container rounded-lg bg-surface"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2">Address</label>
                        <textarea readonly class="w-full px-4 py-3 border border-surface-container rounded-lg bg-surface">{{ $customer->address ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-primary text-on-primary px-6 py-4 rounded-lg font-bold text-lg hover:opacity-90">
                Complete Order
            </button>
        </form>
    </div>

    <!-- Order Summary -->
    <div class="lg:col-span-1">
        <div class="bg-surface-container-lowest rounded-lg p-6 sticky top-32 space-y-4">
            <h2 class="text-xl font-extrabold">Order Summary</h2>

            <div class="space-y-3 max-h-96 overflow-y-auto">
                @foreach ($cart->products as $item)
                    <div class="flex justify-between text-sm">
                        <span>{{ $item->product->name }} x {{ $item->quantity }}</span>
                        <span>${{ number_format($item->subtotal, 2) }}</span>
                    </div>
                @endforeach
            </div>

            @php
                $subtotal = $cart->products->sum(fn($item) => $item->subtotal);
                $tax = $subtotal * 0.1;
                $total = $subtotal + $tax;
            @endphp

            <div class="border-t border-surface-container pt-4 space-y-2">
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span>${{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Tax (10%)</span>
                    <span>${{ number_format($tax, 2) }}</span>
                </div>
                <div class="flex justify-between font-extrabold text-lg">
                    <span>Total</span>
                    <span>${{ number_format($total, 2) }}</span>
                </div>
            </div>

            <div class="bg-primary/10 border-l-4 border-primary p-3 rounded text-sm">
                <p class="font-bold text-primary mb-1">Collection Info</p>
                <p class="text-xs">Select a collection slot above to choose when and where to pick up your order.</p>
            </div>
        </div>
    </div>
</div>
@endsection
