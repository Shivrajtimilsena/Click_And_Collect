<nav class="fixed top-0 w-full z-50 h-20 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-2xl shadow-[0_10px_30px_rgba(45,47,47,0.04)]">
    <div class="flex justify-between items-center px-12 w-full max-w-[1920px] mx-auto h-full">
        <a href="{{ route('home') }}" class="flex items-center ml-8">
            <img src="{{ asset('images/logo.png') }}" alt="Click&Collect" class="h-12 w-auto" />
        </a>

        <div class="hidden md:flex items-center gap-8">
            <a href="{{ route('home') }}" class="font-['Plus_Jakarta_Sans'] uppercase tracking-[0.05em] text-[12px] font-bold {{ request()->routeIs('home') ? 'text-orange-700 dark:text-orange-500 border-b-2 border-orange-700 pb-1' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-white transition-all' }}">
                Home
            </a>
            <a href="{{ route('products.index') }}" class="font-['Plus_Jakarta_Sans'] uppercase tracking-[0.05em] text-[12px] font-bold text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-white transition-all">
                New Arrivals
            </a>
            <a href="{{ route('shops.index') }}" class="font-['Plus_Jakarta_Sans'] uppercase tracking-[0.05em] text-[12px] font-bold text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-white transition-all">
                Shops
            </a>
            <a href="{{ route('aboutus') }}" class="font-['Plus_Jakarta_Sans'] uppercase tracking-[0.05em] text-[12px] font-bold {{ request()->routeIs('aboutus') ? 'text-orange-700 dark:text-orange-500 border-b-2 border-orange-700 pb-1' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-white transition-all' }}">
                About Us
            </a>
            <a href="{{ route('trader.apply') }}" class="font-['Plus_Jakarta_Sans'] uppercase tracking-[0.05em] text-[12px] font-bold text-primary hover:underline underline-offset-4">
                Become a Trader
            </a>
        </div>

        <div class="flex items-center gap-6">
            <div class="relative hidden lg:block">
                <form action="{{ route('products.index') }}" method="GET" class="flex">
                    <input type="text" name="search" placeholder="Search local curators..." value="{{ request('search') }}" class="bg-surface-container-high border-none rounded-full px-6 py-2 text-sm w-64 focus:ring-2 focus:ring-primary/20 transition-all"/>
                </form>
            </div>

            <div class="flex gap-4">
                @if (auth()->check())
                    @if(Auth::user()->role !== 'TRADER')
                        <a href="{{ route('wishlist.index') }}" class="hover:opacity-80 transition-opacity scale-95 active:scale-90 transition-transform">
                            <span class="material-symbols-outlined text-zinc-800">favorite</span>
                        </a>
                        <a href="{{ route('cart.index') }}" class="hover:opacity-80 transition-opacity scale-95 active:scale-90 transition-transform relative">
                            <span class="material-symbols-outlined text-zinc-800">shopping_bag</span>
                            @php
                                $cartCount = auth()->user()->customer?->getOrCreateCart()?->products()->count() ?? 0;
                            @endphp
                            @if ($cartCount > 0)
                                <span class="absolute -top-2 -right-2 bg-primary text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    @endif
                    <div class="relative group">
                        <button class="hover:opacity-80 transition-opacity scale-95 active:scale-90 transition-transform">
                            @if (auth()->user()->avatar_url)
                                <img 
                                    src="{{ auth()->user()->avatar_url }}" 
                                    alt="Profile"
                                    class="w-6 h-6 rounded-full object-cover"
                                />
                            @else
                                <span class="material-symbols-outlined text-zinc-800">person</span>
                            @endif
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white shadow-lg border border-surface-container-high opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                            @if(Auth::user()->role === 'TRADER')
                                <a href="{{ route('trader.settings') }}" class="block px-4 py-2 hover:bg-surface-container-high font-medium text-sm">Profile</a>
                                <a href="{{ route('trader.dashboard') }}" class="block px-4 py-2 hover:bg-surface-container-high font-medium text-sm">Trader Portal</a>
                            @else
                                <a href="{{ route('profile.settings') }}" class="block px-4 py-2 hover:bg-surface-container-high font-medium text-sm">Profile</a>
                                <a href="{{ route('profile.orders') }}" class="block px-4 py-2 hover:bg-surface-container-high font-medium text-sm">Orders</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-surface-container-high font-medium text-sm">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <button onclick="openAuthModal('login')" class="bg-primary text-on-primary px-6 py-2.5 text-sm font-bold hover:opacity-90 active:scale-95 transition-all">
                        Sign In
                    </button>
                @endif
            </div>
        </div>
    </div>
</nav>
