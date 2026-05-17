<div class="group cursor-pointer">
    <div class="relative aspect-square overflow-hidden bg-surface-container-low mb-1.5">
        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
             src="{{ $product->image_url ?? 'https://via.placeholder.com/300' }}" 
             alt="{{ $product->product_name }}"/>
        @if($product->discount)
        <span class="absolute top-0.5 left-0.5 bg-error text-white text-[8px] font-black px-1 py-0.5">
            -{{ $product->discount->discount_percentage }}%
        </span>
        @endif
    </div>
    <a href="{{ route('products.show', $product) }}" class="text-[11px] font-bold truncate hover:text-primary transition-colors block leading-tight">
        {{ $product->product_name }}
    </a>
    <div class="flex items-baseline gap-1 mt-0.5">
        <span class="text-primary font-extrabold text-xs">&pound;{{ number_format($product->discounted_price, 2) }}</span>
        <span class="text-[9px] text-on-surface-variant line-through">&pound;{{ number_format($product->price, 2) }}</span>
    </div>
    <div class="mt-1 w-full bg-surface-container h-0.5">
        @php
            $stockPercentage = min(100, ($product->stock / 50) * 100);
        @endphp
        <div class="bg-primary h-full" style="width: {{ $stockPercentage }}%"></div>
    </div>
    <p class="text-[8px] mt-0.5 text-on-surface-variant font-medium">{{ $product->stock }} items left</p>
</div>
