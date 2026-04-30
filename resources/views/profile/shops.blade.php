@extends('layouts.profile')

@section('profile-content')
<div>
    <h3 class="font-headline text-3xl font-extrabold tracking-tight text-on-surface mb-8">
        Saved Shops
    </h3>

    @if($wishlists->count() > 0 && $wishlists->first()->products->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($wishlists->first()->products as $wishlistProduct)
        <div class="bg-surface-container-lowest overflow-hidden rounded-lg shadow-[0_10px_30px_rgba(45,47,47,0.04)]">
            <!-- Product Image -->
            <div class="h-64 bg-surface-container overflow-hidden rounded-lg">
                <img 
                    alt="{{ $wishlistProduct->product->product_name ?? 'Product' }}" 
                    class="w-full h-full object-cover"
                    src="{{ $wishlistProduct->product->image_url ?? 'https://via.placeholder.com/256' }}"
                />
            </div>

            <!-- Product Details -->
            <div class="p-6">
                <h4 class="font-headline text-lg font-bold text-on-surface mb-2">
                    {{ $wishlistProduct->product->product_name ?? 'Product' }}
                </h4>
                <p class="text-on-surface-variant text-sm mb-4">
                    {{ Str::limit($wishlistProduct->product->description ?? '', 60) }}
                </p>

                <!-- Price -->
                <div class="flex justify-between items-center mb-6">
                    <div class="space-y-1">
                        @if($wishlistProduct->product->discount)
                        <p class="text-on-surface-variant text-sm line-through">
                            ${{ number_format($wishlistProduct->product->price, 2) }}
                        </p>
                        <p class="font-bold text-primary text-lg">
                            ${{ number_format($wishlistProduct->product->price * (1 - $wishlistProduct->product->discount->discount_percentage / 100), 2) }}
                        </p>
                        @else
                        <p class="font-bold text-on-surface text-lg">
                            ${{ number_format($wishlistProduct->product->price, 2) }}
                        </p>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3">
                    <button class="flex-1 py-2 px-4 bg-primary text-on-primary font-bold text-sm hover:opacity-90 transition-opacity">
                        Add to Cart
                    </button>
                    <form method="POST" action="{{ route('wishlist.remove', $wishlistProduct->product_id) }}" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2 px-4 bg-surface-container text-on-surface font-bold text-sm hover:bg-surface-container-low transition-colors">
                            Remove
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-surface-container p-12 text-center">
        <span class="material-symbols-outlined text-4xl text-on-surface-variant mb-4 block">favorite</span>
        <p class="text-on-surface-variant">No saved shops yet.</p>
        <a href="{{ route('home') }}" class="mt-4 inline-block px-6 py-2 bg-primary text-on-primary font-bold text-sm hover:opacity-90 transition-opacity">
            Browse Shops
        </a>
    </div>
    @endif
</div>
@endsection
