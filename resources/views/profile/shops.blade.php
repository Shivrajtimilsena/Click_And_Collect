@extends('layouts.customer')

@section('title', 'Saved Shops | Click&Collect')

@section('header-title', 'Saved Shops')

@section('content')
<div>
    <h3 class="font-headline text-lg md:text-xl font-bold tracking-tight text-on-surface mb-6 md:mb-8">
        Saved Shops
    </h3>

    @if($savedShops->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        @foreach($savedShops as $shop)
        <div class="bg-surface-container-lowest overflow-hidden border border-surface-container-low rounded-xl">
            <a href="{{ route('shops.show', $shop) }}" class="block">
                <div class="h-48 md:h-64 bg-surface-container overflow-hidden">
                    @if($shop->shop_image)
                        <img 
                            alt="{{ $shop->shop_name }}" 
                            class="w-full h-full object-cover"
                            src="{{ $shop->shop_image }}"
                        />
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary to-primary-fixed text-white text-2xl font-bold">
                            {{ substr($shop->shop_name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="p-4 md:p-6">
                    <p class="text-xs font-bold uppercase tracking-widest text-secondary">Local Trader</p>
                    <h4 class="font-headline text-base md:text-lg font-bold text-on-surface mb-1.5 md:mb-2">
                        {{ $shop->shop_name }}
                    </h4>
                    <p class="text-secondary text-xs md:text-sm mb-3 md:mb-4">
                        {{ Str::limit($shop->description ?? 'Quality local products', 70) }}
                    </p>
                    <span class="text-xs text-on-surface-variant">{{ $shop->products()->count() }} Products</span>
                </div>
            </a>
            <div class="px-4 md:px-6 pb-4 md:pb-6">
                <form method="POST" action="{{ route('shops.save.remove', $shop) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-2 md:py-2.5 px-3 md:px-4 bg-surface-container text-on-surface font-bold text-xs md:text-sm hover:bg-surface-container-low transition-colors">
                        Remove
                    </button>
                </form>
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
