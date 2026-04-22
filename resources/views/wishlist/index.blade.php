@extends('app')

@section('title', 'My Wishlist | Click&Collect')

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-extrabold mb-8">My Wishlist</h1>

    @if ($wishlist->products->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
            @foreach ($wishlist->products as $item)
                <div class="bg-surface-container-lowest rounded-lg p-2 group hover:shadow-lg transition-all duration-300 relative">
                    <div class="aspect-square rounded-md overflow-hidden bg-surface-container mb-2 relative">
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                             src="{{ $item->product->image ?? 'https://via.placeholder.com/300' }}" 
                             alt="{{ $item->product->name }}"/>
                        @if ($item->product->discount_percentage > 0)
                            <span class="absolute top-2 left-2 bg-error text-white text-[10px] font-black px-2 py-1 rounded">
                                -{{ $item->product->discount_percentage }}%
                            </span>
                        @endif
                        <form action="{{ route('wishlist.remove', $item) }}" method="POST" class="absolute top-2 right-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 rounded-full bg-error text-white flex items-center justify-center hover:bg-error/80 transition-colors">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                            </button>
                        </form>
                    </div>

                    <p class="text-[10px] font-bold text-primary truncate">{{ $item->product->shop->name }}</p>
                    <a href="{{ route('products.show', $item->product) }}" class="text-[12px] font-medium leading-tight h-8 line-clamp-2 hover:text-primary transition-colors">
                        {{ $item->product->name }}
                    </a>

                    <div class="flex items-center gap-1 mt-1 text-[10px] text-orange-500">
                        <span class="material-symbols-outlined text-[10px] fill-current" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="font-bold">{{ number_format($item->product->reviews->avg('rating') ?? 0, 1) }}</span>
                        <span class="text-on-surface-variant">({{ $item->product->reviews->count() }})</span>
                    </div>

                    <div class="flex items-center justify-between mt-2">
                        <span class="text-sm font-black">${{ number_format($item->product->discounted_price, 2) }}</span>
                    </div>

                    <form action="{{ route('cart.add') }}" method="POST" class="mt-2">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full bg-primary text-on-primary px-2 py-2 rounded text-xs font-bold hover:opacity-90 transition-opacity">
                            Add to Cart
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-surface-container-lowest rounded-lg p-12 text-center">
            <span class="material-symbols-outlined text-6xl text-on-surface-variant mb-4 block">favorite</span>
            <p class="text-on-surface-variant text-lg mb-6">Your wishlist is empty</p>
            <a href="{{ route('products.index') }}" class="bg-primary text-on-primary px-6 py-3 rounded-lg font-bold hover:opacity-90">
                Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection
