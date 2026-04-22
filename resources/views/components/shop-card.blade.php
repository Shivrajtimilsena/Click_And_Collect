<div class="flex-shrink-0 flex flex-col items-center gap-2 group cursor-pointer">
    <a href="{{ route('shops.show', $shop) }}" class="w-20 h-20 rounded-full border-4 border-transparent group-hover:border-primary transition-all overflow-hidden bg-white shadow-sm p-1">
        <div class="w-full h-full rounded-full bg-gradient-to-br from-primary to-primary-fixed flex items-center justify-center font-bold text-xs text-center p-2 text-white" style="">
            {{ $shop->shop_name }}
        </div>
    </a>
    <span class="text-[11px] font-bold text-center">{{ $shop->trader->shop_type ?? 'Shop' }}</span>
</div>
