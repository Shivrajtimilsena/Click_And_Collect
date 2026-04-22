@extends('app')

@section('title', 'Shopping Cart | Click&Collect')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <h1 class="text-3xl font-extrabold mb-6">Shopping Cart</h1>

        @if ($cart->products->count() > 0)
            <div class="space-y-4">
                @foreach ($cart->products as $item)
                    <div class="bg-surface-container-lowest rounded-lg p-6 flex gap-4">
                        <img src="{{ $item->product->image ?? 'https://via.placeholder.com/100' }}" 
                             alt="{{ $item->product->name }}" class="w-24 h-24 rounded-lg object-cover"/>
                        
                        <div class="flex-grow">
                            <a href="{{ route('products.show', $item->product) }}" class="font-bold text-lg hover:text-primary">
                                {{ $item->product->name }}
                            </a>
                            <p class="text-sm text-on-surface-variant">{{ $item->product->shop->name }}</p>
                            <p class="text-primary font-bold mt-2">${{ number_format($item->product->discounted_price, 2) }}</p>
                        </div>

                        <div class="flex flex-col items-end justify-between">
                            <form action="{{ route('cart.remove', $item) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-error hover:underline text-sm font-bold">Remove</button>
                            </form>

                            <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-20 px-2 py-1 border border-surface-container rounded"/>
                                <button type="submit" class="bg-primary text-on-primary px-3 py-1 rounded text-sm font-bold">Update</button>
                            </form>

                            <p class="font-extrabold text-lg">${{ number_format($item->subtotal, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-between mt-8">
                <a href="{{ route('products.index') }}" class="text-primary font-bold hover:underline">← Continue Shopping</a>
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-error font-bold hover:underline">Clear Cart</button>
                </form>
            </div>
        @else
            <div class="bg-surface-container-lowest rounded-lg p-12 text-center">
                <span class="material-symbols-outlined text-6xl text-on-surface-variant mb-4 block">shopping_bag</span>
                <p class="text-on-surface-variant text-lg mb-6">Your cart is empty</p>
                <a href="{{ route('products.index') }}" class="bg-primary text-on-primary px-6 py-3 rounded-lg font-bold hover:opacity-90">
                    Start Shopping
                </a>
            </div>
        @endif
    </div>

    <!-- Cart Summary -->
    @if ($cart->products->count() > 0)
        <div class="lg:col-span-1">
            <div class="bg-surface-container-lowest rounded-lg p-6 sticky top-32 space-y-4">
                <h2 class="text-xl font-extrabold">Order Summary</h2>

                @php
                    $subtotal = $cart->products->sum(fn($item) => $item->subtotal);
                    $tax = $subtotal * 0.1; // 10% tax
                    $total = $subtotal + $tax;
                @endphp

                <div class="space-y-2 border-b border-surface-container pb-4">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tax (10%)</span>
                        <span>${{ number_format($tax, 2) }}</span>
                    </div>
                </div>

                <div class="flex justify-between font-extrabold text-lg">
                    <span>Total</span>
                    <span>${{ number_format($total, 2) }}</span>
                </div>

                <a href="{{ route('orders.checkout') }}" class="w-full bg-primary text-on-primary px-6 py-4 rounded-lg font-bold text-center hover:opacity-90 block">
                    Proceed to Checkout
                </a>

                <p class="text-xs text-on-surface-variant text-center">Safe & Secure Checkout</p>
            </div>
        </div>
    @endif
</div>
@endsection
