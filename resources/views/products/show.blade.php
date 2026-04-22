@extends('app')

@section('title', $product->name . ' | Click&Collect')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-12">
    <!-- Product Image -->
    <div class="sticky top-32">
        <div class="aspect-square rounded-lg overflow-hidden bg-surface-container-low">
            <img src="{{ $product->image ?? 'https://via.placeholder.com/600' }}" 
                 alt="{{ $product->name }}" class="w-full h-full object-cover"/>
        </div>
    </div>

    <!-- Product Details -->
    <div class="space-y-6">
        <div>
            <p class="text-primary font-bold text-sm mb-2">{{ $product->shop->name }}</p>
            <h1 class="text-4xl font-extrabold mb-4">{{ $product->name }}</h1>
            
            <div class="flex items-center gap-4 mb-4">
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-orange-500 fill-current">star</span>
                    <span class="font-bold">{{ number_format($product->reviews->avg('rating') ?? 0, 1) }}</span>
                    <span class="text-on-surface-variant">({{ $product->reviews->count() }} reviews)</span>
                </div>
            </div>

            <div class="space-y-2">
                <div class="text-sm text-on-surface-variant">
                    <strong>Availability:</strong> 
                    @if ($product->quantity > 20)
                        <span class="text-green-600">In Stock ({{ $product->quantity }} available)</span>
                    @elseif ($product->quantity > 0)
                        <span class="text-orange-600">Limited Stock ({{ $product->quantity }} available)</span>
                    @else
                        <span class="text-error">Out of Stock</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Price Section -->
        <div class="space-y-2 py-6 border-y border-surface-container">
            <div class="flex items-baseline gap-4">
                <span class="text-5xl font-extrabold">${{ number_format($product->discounted_price, 2) }}</span>
                @if ($product->discount_percentage > 0)
                    <span class="text-2xl line-through text-on-surface-variant">${{ number_format($product->price, 2) }}</span>
                    <span class="bg-error text-white px-3 py-1 rounded text-sm font-bold">{{ $product->discount_percentage }}% OFF</span>
                @endif
            </div>
            @if ($product->allergy_info)
                <div class="text-sm text-on-surface-variant">
                    <strong>Allergy Info:</strong> {{ $product->allergy_info }}
                </div>
            @endif
        </div>

        <!-- Description -->
        @if ($product->description)
            <div class="space-y-2">
                <h3 class="font-bold text-lg">Description</h3>
                <p class="text-on-surface-variant">{{ $product->description }}</p>
            </div>
        @endif

        <!-- Add to Cart Form -->
        <form action="{{ route('cart.add') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            
            <div class="flex items-center gap-4">
                <input type="number" name="quantity" value="1" min="1" max="{{ $product->quantity }}" class="w-24 px-4 py-3 border border-surface-container rounded-lg">
                <button type="submit" {{ $product->quantity === 0 ? 'disabled' : '' }} class="flex-1 bg-primary text-on-primary px-8 py-3 rounded-lg font-bold hover:opacity-90 transition-opacity disabled:opacity-50">
                    <span class="material-symbols-outlined inline mr-2">shopping_cart</span>
                    Add to Cart
                </button>
            </div>

            @if (auth()->check())
                <button type="button" onclick="addToWishlist({{ $product->id }})" class="w-full border-2 border-primary text-primary px-8 py-3 rounded-lg font-bold hover:bg-primary/5 transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">favorite</span>
                    Add to Wishlist
                </button>
            @else
                <a href="{{ route('login') }}" class="w-full border-2 border-primary text-primary px-8 py-3 rounded-lg font-bold hover:bg-primary/5 transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">favorite</span>
                    Add to Wishlist
                </a>
            @endif
        </form>

        <!-- Shop Info -->
        <div class="bg-surface-container-lowest p-6 rounded-lg">
            <h3 class="font-bold text-lg mb-4">About the Shop</h3>
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[32px]">storefront</span>
                </div>
                <div>
                    <p class="font-bold">{{ $product->shop->name }}</p>
                    <p class="text-sm text-on-surface-variant">{{ $product->shop->description ?? 'Quality local products' }}</p>
                    <a href="{{ route('shops.show', $product->shop) }}" class="text-primary font-bold text-sm hover:underline">
                        Visit Shop →
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reviews Section -->
<section class="border-t border-surface-container pt-12">
    <h2 class="text-2xl font-extrabold mb-8">Customer Reviews</h2>

    @if (auth()->check())
        <div class="bg-surface-container-lowest rounded-lg p-6 mb-8">
            <h3 class="font-bold text-lg mb-4">Leave a Review</h3>
            <form action="{{ route('reviews.store', $product) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold mb-2">Rating</label>
                    <div class="flex gap-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="rating" value="{{ $i }}" class="sr-only" required>
                                <span class="material-symbols-outlined text-3xl fill-current hover:text-orange-500" style="color: var(--star-color);">star</span>
                            </label>
                        @endfor
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2">Comment</label>
                    <textarea name="comment" class="w-full px-4 py-3 border border-surface-container rounded-lg" rows="4" placeholder="Share your experience..."></textarea>
                </div>
                <button type="submit" class="bg-primary text-on-primary px-8 py-3 rounded-lg font-bold hover:opacity-90">
                    Post Review
                </button>
            </form>
        </div>
    @endif

    <div class="space-y-4">
        @forelse ($product->reviews()->latest()->paginate(5) as $review)
            <div class="bg-surface-container-lowest rounded-lg p-6">
                <div class="flex items-start justify-between mb-2">
                    <div>
                        <p class="font-bold">{{ $review->customer->user->name }}</p>
                        <div class="flex items-center gap-1">
                            @for ($i = 0; $i < $review->rating; $i++)
                                <span class="material-symbols-outlined text-orange-500 fill-current text-sm">star</span>
                            @endfor
                        </div>
                    </div>
                    <p class="text-sm text-on-surface-variant">{{ $review->created_at->diffForHumans() }}</p>
                </div>
                <p class="text-on-surface-variant">{{ $review->comment }}</p>
            </div>
        @empty
            <p class="text-center text-on-surface-variant py-8">No reviews yet</p>
        @endforelse
    </div>
</section>

<!-- Related Products -->
<section class="border-t border-surface-container pt-12 mt-12">
    <h2 class="text-2xl font-extrabold mb-6">Related Products</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
        @forelse ($relatedProducts as $relProduct)
            @include('components.product-card', ['product' => $relProduct])
        @empty
            <p class="col-span-full text-center text-on-surface-variant">No related products</p>
        @endforelse
    </div>
</section>

<script>
function addToWishlist(productId) {
    fetch('{{ route("wishlist.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ product_id: productId })
    }).then(response => {
        if (response.ok) {
            alert('Added to wishlist!');
        }
    });
}
</script>
@endsection
