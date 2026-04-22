<div class="bg-surface-container-lowest p-2 group hover:shadow-lg transition-all duration-300">
    <div class="aspect-square overflow-hidden bg-surface-container mb-2 relative">
        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
             src="{{ $product->image_url ?? 'https://via.placeholder.com/300' }}" 
             alt="{{ $product->product_name }}"/>
        @if ($product->discount && $product->discount->discount_percentage > 0)
            <span class="absolute top-2 left-2 bg-error text-white text-[10px] font-black px-2 py-1 rounded">
                -{{ $product->discount->discount_percentage }}%
            </span>
        @endif
        <form action="{{ route('cart.add') }}" method="POST" class="absolute bottom-2 right-2">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->product_id }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity translate-y-2 group-hover:translate-y-0 duration-300 hover:bg-primary-dim">
                <span class="material-symbols-outlined text-[18px]">add</span>
            </button>
        </form>
    </div>

    <p class="text-[10px] font-bold text-primary truncate">{{ $product->shop->shop_name ?? 'Shop' }}</p>
    <a href="{{ route('products.show', $product) }}" class="text-[12px] font-medium leading-tight h-8 line-clamp-2 hover:text-primary transition-colors">
        {{ $product->product_name }}
    </a>

    <div class="flex items-center gap-1 mt-1 text-[10px] text-orange-500">
        <span class="material-symbols-outlined text-[10px] fill-current" style="font-variation-settings: 'FILL' 1;">star</span>
        <span class="font-bold">{{ $product->reviews && $product->reviews->count() > 0 ? number_format($product->reviews->avg('rating'), 1) : '0.0' }}</span>
        <span class="text-on-surface-variant">({{ $product->reviews ? $product->reviews->count() : 0 }})</span>
    </div>

    <div class="flex items-center justify-between mt-2">
        <span class="text-sm font-black">${{ number_format($product->discounted_price, 2) }}</span>
        @if ($product->discount && $product->discount->discount_percentage > 0)
            <span class="text-[11px] text-on-surface-variant line-through">${{ number_format($product->price, 2) }}</span>
        @endif
    </div>
</div>
