<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Click&Collect | High-Density Local Shopping</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface": "#f6f6f6",
                        "on-error-container": "#510017",
                        "surface-variant": "#dbdddd",
                        "primary-dim": "#9f1600",
                        "tertiary": "#9b3f15",
                        "on-primary-fixed-variant": "#5d0900",
                        "on-primary": "#ffefec",
                        "inverse-surface": "#0c0f0f",
                        "primary-fixed-dim": "#ff5c3e",
                        "secondary": "#5c5b5b",
                        "surface-container-high": "#e1e3e3",
                        "primary-fixed": "#ff775d",
                        "inverse-primary": "#fd583a",
                        "on-tertiary-fixed-variant": "#692100",
                        "secondary-dim": "#504f4f",
                        "on-secondary-fixed": "#403f3f",
                        "error": "#b41340",
                        "tertiary-fixed": "#ff956b",
                        "on-background": "#2d2f2f",
                        "surface-container-low": "#f0f1f1",
                        "on-surface": "#2d2f2f",
                        "tertiary-container": "#ff956b",
                        "outline-variant": "#acadad",
                        "background": "#f6f6f6",
                        "secondary-container": "#e5e2e1",
                        "surface-container-lowest": "#ffffff",
                        "on-surface-variant": "#5a5c5c",
                        "outline": "#767777",
                        "on-error": "#ffefef",
                        "surface-bright": "#f6f6f6",
                        "on-secondary-container": "#525151",
                        "on-tertiary": "#ffefeb",
                        "on-primary-container": "#4c0600",
                        "inverse-on-surface": "#9c9d9d",
                        "on-secondary": "#f5f2f1",
                        "primary-container": "#ff775d",
                        "surface-tint": "#b12209",
                        "surface-container-highest": "#dbdddd",
                        "on-tertiary-container": "#5a1c00",
                        "error-dim": "#a70138",
                        "secondary-fixed-dim": "#d6d4d3",
                        "on-secondary-fixed-variant": "#5c5b5b",
                        "tertiary-dim": "#8b3309",
                        "surface-dim": "#d3d5d5",
                        "secondary-fixed": "#e5e2e1",
                        "on-primary-fixed": "#000000",
                        "primary": "#b12209",
                        "error-container": "#f74b6d",
                        "surface-container": "#e7e8e8",
                        "tertiary-fixed-dim": "#f68355",
                        "on-tertiary-fixed": "#310b00"
                    },
                    "borderRadius": {
                        "DEFAULT": "1rem",
                        "lg": "2rem",
                        "xl": "3rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "headline": ["Plus Jakarta Sans"],
                        "body": ["Manrope"],
                        "label": ["Manrope"]
                    }
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body { font-family: 'Manrope', sans-serif; }
        h1, h2, h3, h4 { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-surface text-on-background selection:bg-primary-container selection:text-on-primary-container">
    <!-- Top Navigation Bar -->
    <nav class="fixed top-0 w-full z-50 h-20 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-2xl shadow-[0_10px_30px_rgba(45,47,47,0.04)]">
        <div class="flex justify-between items-center px-12 w-full max-w-[1920px] mx-auto h-full">
            <div class="text-2xl font-black text-zinc-800 dark:text-zinc-100 tracking-tighter">Click&Collect</div>
            <div class="hidden md:flex items-center gap-8">
                <a class="font-['Plus_Jakarta_Sans'] uppercase tracking-[0.05em] text-[12px] font-bold text-orange-700 dark:text-orange-500 border-b-2 border-orange-700 pb-1" href="/">Home</a>
                <a class="font-['Plus_Jakarta_Sans'] uppercase tracking-[0.05em] text-[12px] font-bold text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-white transition-all" href="#">New Arrivals</a>
                <a class="font-['Plus_Jakarta_Sans'] uppercase tracking-[0.05em] text-[12px] font-bold text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-white transition-all" href="#">Shops</a>
                <a class="font-['Plus_Jakarta_Sans'] uppercase tracking-[0.05em] text-[12px] font-bold text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-white transition-all" href="/aboutus">About Us</a>
            </div>
            <div class="flex items-center gap-6">
                <div class="relative hidden lg:block">
                    <input class="bg-surface-container-high border-none rounded-full px-6 py-2 text-sm w-64 focus:ring-2 focus:ring-primary/20 transition-all" placeholder="Search local curators..." type="text"/>
                </div>
                <div class="flex gap-4">
                    <button class="hover:opacity-80 transition-opacity scale-95 active:scale-90 transition-transform"><span class="material-symbols-outlined text-zinc-800" data-icon="favorite">favorite</span></button>
                    <button class="hover:opacity-80 transition-opacity scale-95 active:scale-90 transition-transform"><span class="material-symbols-outlined text-zinc-800" data-icon="shopping_bag">shopping_bag</span></button>
                    <button class="hover:opacity-80 transition-opacity scale-95 active:scale-90 transition-transform"><span class="material-symbols-outlined text-zinc-800" data-icon="person">person</span></button>
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-24 pb-12 px-4 md:px-12 max-w-[1920px] mx-auto">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar Category Navigation -->
            <aside class="w-full lg:w-64 flex-shrink-0">
                <div class="bg-surface-container-lowest p-4 space-y-1">
                    <h3 class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant px-4 mb-4">Categories</h3>
                    <a class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container text-primary transition-colors" href="#">
                        <span class="material-symbols-outlined text-[20px]" data-icon="bakery_dining">bakery_dining</span> Artisan Bakery
                    </a>
                    <a class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container text-on-surface-variant transition-colors" href="#">
                        <span class="material-symbols-outlined text-[20px]" data-icon="restaurant">restaurant</span> Local Butcher
                    </a>
                    <a class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container text-on-surface-variant transition-colors" href="#">
                        <span class="material-symbols-outlined text-[20px]" data-icon="eco">eco</span> Daily Greens
                    </a>
                    <a class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container text-on-surface-variant transition-colors" href="#">
                        <span class="material-symbols-outlined text-[20px]" data-icon="egg">egg</span> Dairy & Eggs
                    </a>
                    <a class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container text-on-surface-variant transition-colors" href="#">
                        <span class="material-symbols-outlined text-[20px]" data-icon="set_meal">set_meal</span> Fresh Catch
                    </a>
                    <a class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container text-on-surface-variant transition-colors" href="#">
                        <span class="material-symbols-outlined text-[20px]" data-icon="local_cafe">local_cafe</span> Coffee & Tea
                    </a>
                    <a class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container text-on-surface-variant transition-colors" href="#">
                        <span class="material-symbols-outlined text-[20px]" data-icon="liquor">liquor</span> Wine & Spirits
                    </a>
                    <a class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container text-on-surface-variant transition-colors" href="#">
                        <span class="material-symbols-outlined text-[20px]" data-icon="shutter_speed">shutter_speed</span> Flash Deals
                    </a>
                </div>
                <div class="bg-surface-container-lowest p-4 mt-4 space-y-1">
                    <h3 class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant px-4 mb-4">Shop by Price</h3>
                    <a class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container text-on-surface-variant transition-colors" href="#">Under $5.00</a>
                    <a class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container text-on-surface-variant transition-colors" href="#">$5.00 - $10.00</a>
                    <a class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container text-on-surface-variant transition-colors" href="#">$10.00 - $20.00</a>
                </div>
                <div class="mt-4 bg-gradient-to-br from-orange-500 to-primary p-6 text-white shadow-lg overflow-hidden relative group cursor-pointer">
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-2 text-white/90">
                            <span class="material-symbols-outlined text-[18px] animate-pulse" data-icon="bolt">bolt</span>
                            <span class="text-[10px] font-black uppercase tracking-widest">Flash Deals</span>
                        </div>
                        <h4 class="text-lg font-bold leading-tight mb-3">Save up to 60% on Fresh Items!</h4>
                        <button class="w-full bg-white text-primary py-2 text-[11px] font-black uppercase tracking-wider hover:bg-orange-50 transition-colors">Shop Now</button>
                    </div>
                    <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                        <span class="material-symbols-outlined text-[120px]" data-icon="shopping_basket">shopping_basket</span>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-grow space-y-6 overflow-hidden">
                <!-- Hero Slider / Banner -->
                <section class="relative h-[400px] overflow-hidden group">
                    <img alt="Fresh Produce" class="absolute inset-0 w-full h-full object-cover" src="{{ asset('images/hero/landing_hero.png') }}"/>
                    <div class="absolute inset-0 bg-gradient-to-r from-on-background/80 to-transparent flex flex-col justify-center px-12 text-white">
                        <span class="bg-primary text-on-primary text-[10px] font-bold uppercase tracking-widest py-1 px-3 rounded-full w-fit mb-4">Click & Collect</span>
                        <h1 class="text-5xl font-extrabold tracking-tighter mb-4 max-w-lg">Freshness, Curated Locally.</h1>
                        <p class="text-lg opacity-90 max-w-md mb-8">Order from your favorite neighborhood artisans and collect at your convenience. No queues, just quality.</p>
                        <div class="flex gap-4">
                            <button class="bg-gradient-to-r from-primary to-primary-fixed text-on-primary px-8 py-3 rounded-full font-bold text-sm hover:opacity-90 transition-opacity">Shop Deals</button>
                            <button class="bg-white/20 backdrop-blur-md border border-white/30 text-white px-8 py-3 rounded-full font-bold text-sm hover:bg-white/30 transition-all">Trades</button>
                        </div>
                    </div>
                </section>

                <!-- Shop by Trader Section -->
                <section class="space-y-4">
                    <h2 class="text-xl font-extrabold tracking-tight">Our Local Traders</h2>
                    <div class="flex gap-4 overflow-x-auto no-scrollbar pb-4">
                        @php
                            $traders = [
                                ['name' => 'The Greengrocer', 'label' => 'Green Root'],
                                ['name' => 'The Butcher', 'label' => 'Old Town'],
                                ['name' => 'Fishmonger', 'label' => 'Ocean Fresh'],
                                ['name' => 'Bake House', 'label' => 'Flour & Salt'],
                                ['name' => 'Delicatessen', 'label' => 'Fine Treats']
                            ];
                        @endphp
                        @foreach($traders as $trader)
                        <div class="flex-shrink-0 flex flex-col items-center gap-2 group cursor-pointer">
                            <div class="w-20 h-20 rounded-full border-4 border-transparent group-hover:border-primary transition-all overflow-hidden bg-white shadow-sm p-1">
                                <div class="w-full h-full rounded-full bg-zinc-100 flex items-center justify-center font-bold text-xs text-center p-2">{{ $trader['label'] }}</div>
                            </div>
                            <span class="text-[11px] font-bold">{{ $trader['name'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </section>

                <!-- Flash Deals Section -->
                <section class="bg-surface-container-lowest p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <h2 class="text-xl font-extrabold tracking-tight">Flash Deals</h2>
                            <div class="flex items-center gap-2 text-sm font-bold text-primary">
                                <span class="bg-primary text-white px-2 py-1 rounded">04</span> :
                                <span class="bg-primary text-white px-2 py-1 rounded">22</span> :
                                <span class="bg-primary text-white px-2 py-1 rounded">58</span>
                            </div>
                        </div>
                        <a class="text-primary text-sm font-bold hover:underline" href="#">View All</a>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        @php
                            $flashDeals = [
                                ['name' => 'Artisan Sourdough Loaf', 'price' => '4.50', 'original' => '6.00', 'discount' => '-25%', 'items' => '8', 'stock' => '80'],
                                ['name' => 'Organic Bunch Carrots', 'price' => '1.80', 'original' => '3.00', 'discount' => '-40%', 'items' => '12', 'stock' => '45'],
                                ['name' => 'Aged Ribeye Steak', 'price' => '18.70', 'original' => '22.00', 'discount' => '-15%', 'items' => '4', 'stock' => '20'],
                                ['name' => 'Pasture-Raised Whole Milk', 'price' => '3.60', 'original' => '4.00', 'discount' => '-10%', 'items' => '21', 'stock' => '90'],
                                ['name' => 'Gala Apples (1kg)', 'price' => '2.80', 'original' => '4.00', 'discount' => '-30%', 'items' => '15', 'stock' => '60']
                            ];
                        @endphp
                        @foreach($flashDeals as $deal)
                        <div class="group cursor-pointer">
                            <div class="relative aspect-square overflow-hidden bg-surface-container-low mb-3">
                                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $deal['name'] }}" src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=300&h=300&fit=crop"/>
                                <span class="absolute top-2 left-2 bg-error text-white text-[10px] font-black px-2 py-1 rounded">{{ $deal['discount'] }}</span>
                            </div>
                            <h4 class="text-sm font-bold truncate">{{ $deal['name'] }}</h4>
                            <div class="flex items-baseline gap-2 mt-1">
                                <span class="text-primary font-extrabold">${{ $deal['price'] }}</span>
                                <span class="text-[11px] text-on-surface-variant line-through">${{ $deal['original'] }}</span>
                            </div>
                            <div class="mt-2 w-full bg-surface-container rounded-full h-1">
                                <div class="bg-primary h-full rounded-full" style="width: {{ $deal['stock'] }}%"></div>
                            </div>
                            <p class="text-[10px] mt-1 text-on-surface-variant font-medium">{{ $deal['items'] }} items left</p>
                        </div>
                        @endforeach
                    </div>
                </section>

                <!-- Product Grid: Just For You -->
                <section class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-extrabold tracking-tight">Just For You</h2>
                        <div class="flex gap-2">
                            <button class="px-4 py-1 bg-primary text-on-primary text-xs font-bold">Trending</button>
                            <button class="px-4 py-1 bg-surface-container-high text-on-surface-variant text-xs font-bold">Newest</button>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-3">
                        @php
                            $products = [
                                ['name' => 'Old Town Butcher', 'product' => 'Premium Grass-Fed Beef Mince (500g)', 'price' => '7.50', 'rating' => '4.8', 'reviews' => '122', 'image' => 'https://images.unsplash.com/photo-1432139555190-58524dae6a55?w=300&h=300&fit=crop'],
                                ['name' => 'The Pantry', 'product' => 'Medley Mixed Olives & Herbs', 'price' => '4.20', 'rating' => '4.9', 'reviews' => '45', 'image' => 'https://images.unsplash.com/photo-1599599810694-e9eb19a16409?w=300&h=300&fit=crop'],
                                ['name' => 'Flour & Salt', 'product' => 'Handmade Double Choc Cookies', 'price' => '3.00', 'rating' => '5.0', 'reviews' => '215', 'image' => 'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?w=300&h=300&fit=crop'],
                                ['name' => 'Ocean Fresh', 'product' => 'Fresh Scottish Salmon Fillet', 'price' => '12.50', 'rating' => '4.7', 'reviews' => '89', 'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300&h=300&fit=crop'],
                                ['name' => 'Pure Farm', 'product' => 'Aged Farmhouse Cheddar (250g)', 'price' => '5.80', 'rating' => '4.8', 'reviews' => '67', 'image' => 'https://images.unsplash.com/photo-1452195062665-d0d6e4d35e53?w=300&h=300&fit=crop'],
                                ['name' => 'Green Root', 'product' => 'Cold Pressed Apple Juice (1L)', 'price' => '4.50', 'rating' => '4.6', 'reviews' => '34', 'image' => 'https://images.unsplash.com/photo-1600271886742-f049cd1f0a7e?w=300&h=300&fit=crop'],
                                ['name' => 'Bean Craft', 'product' => 'Ethical Medium Roast Whole Bean', 'price' => '14.00', 'rating' => '4.9', 'reviews' => '156', 'image' => 'https://images.unsplash.com/photo-1599599810694-e9eb19a16409?w=300&h=300&fit=crop'],
                                ['name' => 'Green Root', 'product' => 'Vine Ripened Cherry Tomatoes', 'price' => '3.50', 'rating' => '4.8', 'reviews' => '42', 'image' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=300&h=300&fit=crop']
                            ];
                        @endphp
                        @foreach($products as $product)
                        <div class="bg-surface-container-lowest p-2 group hover:shadow-lg transition-all duration-300">
                            <div class="aspect-square overflow-hidden bg-surface-container mb-2 relative">
                                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $product['product'] }}" src="{{ $product['image'] }}"/>
                                <button class="absolute bottom-2 right-2 w-8 h-8 bg-primary text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity translate-y-2 group-hover:translate-y-0 duration-300">
                                    <span class="material-symbols-outlined text-[18px]" data-icon="add">add</span>
                                </button>
                            </div>
                            <p class="text-[10px] font-bold text-primary truncate">{{ $product['name'] }}</p>
                            <h4 class="text-[12px] font-medium leading-tight h-8 line-clamp-2">{{ $product['product'] }}</h4>
                            <div class="flex items-center gap-1 mt-1 text-[10px] text-orange-500">
                                <span class="material-symbols-outlined text-[10px] fill-current" data-icon="star" style='font-variation-settings: "FILL" 1;'>star</span>
                                <span class="font-bold">{{ $product['rating'] }}</span>
                                <span class="text-on-surface-variant">({{ $product['reviews'] }})</span>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-sm font-black">${{ $product['price'] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="flex justify-center pt-8">
                        <button class="bg-on-background text-surface px-12 py-3 rounded-full font-bold text-sm hover:opacity-90 transition-opacity">Load More Curated Items</button>
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>
