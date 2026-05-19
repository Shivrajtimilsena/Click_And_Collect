@extends('app')

@section('title', 'Click&Collect | Home')

@section('sidebar')
    @include('components.category-sidebar', ['categories' => $categories])
@endsection

@section('content')
<div class="space-y-6">
        <!-- Hero Banner -->
        <section class="relative min-h-[280px] sm:h-[300px] md:h-[400px] overflow-hidden group">
            <picture>
                <source srcset="{{ asset('images/landing_hero.webp') }}" type="image/webp">
                <img alt="Fresh Produce" class="absolute inset-0 w-full h-full object-cover"
                     src="{{ asset('images/landing_hero.png') }}" loading="lazy"/>
            </picture>
            <div class="absolute inset-0 bg-gradient-to-r from-on-background/80 to-transparent flex flex-col justify-center px-6 md:px-12 text-white">
                <span class="bg-primary text-on-primary text-[10px] font-bold uppercase tracking-widest py-1 px-3 rounded-full w-fit mb-4">Click &amp; Collect</span>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tighter mb-4 max-w-lg">Freshness, Curated Locally.</h1>
                <p class="text-sm sm:text-base md:text-lg opacity-90 max-w-xs sm:max-w-sm md:max-w-md mb-4 md:mb-8">Order from your favorite neighborhood artisans and collect at your convenience. No queues, just quality.</p>
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-4">
                    <a href="{{ route('products.index') }}" class="bg-gradient-to-r from-primary to-primary-fixed text-on-primary px-6 md:px-8 py-2.5 md:py-3 rounded-full font-bold text-sm hover:opacity-90 transition-opacity inline-block text-center">
                        Shop Deals
                    </a>
                    <a href="{{ route('shops.index') }}" class="bg-white/20 backdrop-blur-md border border-white/30 text-white px-6 md:px-8 py-2.5 md:py-3 rounded-full font-bold text-sm hover:bg-white/30 transition-all inline-block text-center">
                        Explore Shops
                    </a>
                </div>
            </div>
        </section>

        <!-- Categories Strip (mobile) -->
        <section class="lg:hidden overflow-x-auto no-scrollbar -mx-4 px-4">
            <div class="flex gap-2 pb-2">
                <a href="{{ route('products.index') }}" class="flex-shrink-0 px-4 py-2 rounded-full text-xs font-bold {{ !request('category') ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface-variant' }} transition-colors">
                    All
                </a>
                @foreach ($categories as $category)
                    @php $slug = \Illuminate\Support\Str::slug($category->category_name); @endphp
                    <a href="{{ route('products.index', ['category' => $slug]) }}" class="flex-shrink-0 px-4 py-2 rounded-full text-xs font-bold {{ request('category') === $slug ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface-variant' }} transition-colors">
                        {{ $category->category_name }}
                    </a>
                @endforeach
            </div>
        </section>

        <!-- Flash Deals Section -->
        <section class="bg-surface-container-lowest p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-extrabold tracking-tight">Flash Deals</h2>
                <a href="{{ route('products.index', ['sort' => 'flash']) }}" class="text-primary text-sm font-bold hover:underline">View All</a>
            </div>
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 xl:grid-cols-7 gap-2">
                @forelse ($flashDeals as $product)
                    @include('components.flash-deal-card', ['product' => $product])
                @empty
                    <p class="col-span-full text-center text-on-surface-variant">No flash deals available</p>
                @endforelse
            </div>
        </section>

        <!-- Local Shops Section -->
        <section class="space-y-4">
            <h2 class="text-xl font-extrabold tracking-tight">Our Local Traders</h2>
            <div class="flex gap-4 overflow-x-auto no-scrollbar pb-4">
                @forelse ($shops as $shop)
                    @include('components.shop-card', ['shop' => $shop])
                @empty
                    <p class="text-on-surface-variant">No shops available</p>
                @endforelse
            </div>
        </section>

        <!-- Featured Products Section -->
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-extrabold tracking-tight">Just For You</h2>
                <div class="flex gap-2">
                    <a href="{{ route('products.index', ['sort' => 'trending']) }}" class="px-4 py-1 rounded-full {{ request('sort') === 'trending' || !request('sort') ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface-variant' }} text-xs font-bold">
                        Trending
                    </a>
                    <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="px-4 py-1 rounded-full {{ request('sort') === 'newest' ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface-variant' }} text-xs font-bold">
                        Newest
                    </a>
                </div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-3">
                @forelse ($products as $product)
                    @include('components.product-card', ['product' => $product])
                @empty
                    <p class="col-span-full text-center text-on-surface-variant">No products available</p>
                @endforelse
            </div>
            <div class="flex justify-center pt-8">
                <a href="{{ route('products.index') }}" class="bg-on-background text-surface px-6 md:px-12 py-3 rounded-full font-bold text-sm hover:opacity-90 transition-opacity">
                    Load More Curated Items
                </a>
            </div>
        </section>
    </div>
@endsection
