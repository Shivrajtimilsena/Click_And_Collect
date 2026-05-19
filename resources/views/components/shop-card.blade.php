<div class="flex-shrink-0 flex flex-col items-center gap-2 group cursor-pointer">
    @php
        $traderName = $shop->trader?->user?->full_name ?? $shop->shop_name;
    @endphp
    <a href="{{ route('shops.show', $shop) }}" class="w-20 h-20 rounded-full border-4 border-transparent group-hover:border-primary transition-all overflow-hidden bg-white shadow-sm p-1">
        @if ($shop->shop_image)
            <img 
                src="{{ $shop->shop_image }}" 
                alt="{{ $traderName }}"
                class="w-full h-full rounded-full object-cover"
            />
        @else
            <div class="w-full h-full rounded-full bg-gradient-to-br from-primary to-primary-fixed flex items-center justify-center font-bold text-xs text-center p-2 text-white">
                {{ substr($traderName, 0, 1) }}
            </div>
        @endif
    </a>
    <span class="text-[11px] font-bold text-center">{{ $traderName }}</span>
</div>
