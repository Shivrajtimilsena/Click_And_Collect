<div class="group cursor-pointer">
    <div class="relative aspect-square overflow-hidden bg-surface-container-low mb-3">
        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
             src="{{ $product->image_url ?? 'https://via.placeholder.com/300' }}" 
             alt="{{ $product->product_name }}"/>
        @if($product->discount)
        <span class="absolute top-2 left-2 bg-error text-white text-[10px] font-black px-2 py-1">
            -{{ $product->discount->discount_percentage }}%
        </span>
        @endif
    </div>
    <a href="{{ route('products.show', $product) }}" class="text-sm font-bold truncate hover:text-primary transition-colors">
        {{ $product->product_name }}
    </a>
    <div class="flex items-baseline gap-2 mt-1">
        <span class="text-primary font-extrabold">${{ number_format($product->discounted_price, 2) }}</span>
        <span class="text-[11px] text-on-surface-variant line-through">${{ number_format($product->price, 2) }}</span>
    </div>
    <div class="mt-2 w-full bg-surface-container h-1">
        @php
            $stockPercentage = min(100, ($product->stock / 50) * 100);
        @endphp
        <div class="bg-primary h-full" style="width: {{ $stockPercentage }}%"></div>
    </div>
    <p class="text-[10px] mt-1 text-on-surface-variant font-medium">{{ $product->stock }} items left</p>
</div>
