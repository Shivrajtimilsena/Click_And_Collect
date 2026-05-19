@extends('layouts.customer')

@section('title', 'Saved Shops | Click&Collect')

@section('header-title', 'Saved Shops')

@section('content')
<div>
    <h3 class="font-headline text-lg md:text-xl font-bold tracking-tight text-on-surface mb-6 md:mb-8">
        Saved Shops
    </h3>

    @if($wishlists->count() > 0 && $wishlists->first()->products->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        @foreach($wishlists->first()->products as $wishlistProduct)
        <div class="bg-surface-container-lowest overflow-hidden border border-surface-container-low rounded-xl">
            <!-- Product Image -->
            <div class="h-48 md:h-64 bg-surface-container overflow-hidden">
                <img 
                    alt="{{ $wishlistProduct->product->product_name ?? 'Product' }}" 
                    class="w-full h-full object-cover"
                    src="{{ $wishlistProduct->product->image_url ?? 'https://via.placeholder.com/256' }}"
                />
            </div>

            <!-- Product Details -->
            <div class="p-4 md:p-6">
                <h4 class="font-headline text-base md:text-lg font-bold text-on-surface mb-1.5 md:mb-2">
                    {{ $wishlistProduct->product->product_name ?? 'Product' }}
                </h4>
                <p class="text-secondary text-xs md:text-sm mb-3 md:mb-4">
                    {{ Str::limit($wishlistProduct->product->description ?? '', 60) }}
                </p>

                <!-- Price -->
                <div class="mb-4 md:mb-6">
                    @if($wishlistProduct->product->discount)
                    <p class="text-secondary text-xs md:text-sm line-through">
                        ${{ number_format($wishlistProduct->product->price, 2) }}
                    </p>
                    <p class="font-bold text-primary text-base md:text-lg">
                        ${{ number_format($wishlistProduct->product->price * (1 - $wishlistProduct->product->discount->discount_percentage / 100), 2) }}
                    </p>
                    @else
                    <p class="font-bold text-on-surface text-base md:text-lg">
                        ${{ number_format($wishlistProduct->product->price, 2) }}
                    </p>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-2">
                    <button class="w-full sm:flex-1 py-2 md:py-2.5 px-3 md:px-4 bg-primary text-on-primary font-bold text-xs md:text-sm hover:opacity-90 transition-opacity">
                        Add to Cart
                    </button>
                    <form method="POST" action="{{ route('wishlist.remove', $wishlistProduct->product_id) }}" class="w-full sm:flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2 md:py-2.5 px-3 md:px-4 bg-surface-container text-on-surface font-bold text-xs md:text-sm hover:bg-surface-container-low transition-colors">
                            Remove
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-surface-container-lowest border border-surface-container-low p-8 md:p-12 text-center">
        <span class="material-symbols-outlined text-3xl md:text-4xl text-secondary mb-3 md:mb-4 block">favorite</span>
        <p class="text-sm md:text-base text-secondary">No saved shops yet.</p>
        <a href="{{ route('home') }}" class="mt-3 md:mt-4 inline-block px-5 md:px-6 py-2 md:py-2.5 bg-primary text-on-primary font-bold text-xs md:text-sm hover:opacity-90 transition-opacity">
            Browse Shops
        </a>
    </div>
    @endif
</div>
@stop
