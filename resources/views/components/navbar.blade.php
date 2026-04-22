<nav class="fixed top-0 w-full z-50 h-20 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-2xl shadow-[0_10px_30px_rgba(45,47,47,0.04)]">
    <div class="flex justify-between items-center px-12 w-full max-w-[1920px] mx-auto h-full">
        <a href="{{ route('home') }}" class="text-2xl font-black text-zinc-800 dark:text-zinc-100 tracking-tighter">
            Click&Collect
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
        </div>

        <div class="flex items-center gap-6">
            <div class="relative hidden lg:block">
                <form action="{{ route('products.index') }}" method="GET" class="flex">
                    <input type="text" name="search" placeholder="Search local curators..." class="bg-surface-container-high border-none rounded-full px-6 py-2 text-sm w-64 focus:ring-2 focus:ring-primary/20 transition-all"/>
                </form>
            </div>

            <div class="flex gap-4">
                @if (auth()->check())
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
                    <div class="relative group">
                        <button class="hover:opacity-80 transition-opacity scale-95 active:scale-90 transition-transform">
                            <span class="material-symbols-outlined text-zinc-800">person</span>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-surface-container">Profile</a>
                            <a href="{{ route('orders.index') }}" class="block px-4 py-2 hover:bg-surface-container">Orders</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-surface-container">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <button onclick="openAuthModal('login')" class="bg-primary text-on-primary px-6 py-2.5 rounded-full text-sm font-bold hover:opacity-90 active:scale-95 transition-all shadow-[0_4px_12px_rgba(177,34,9,0.2)]">
                        Sign In
                    </button>
                @endif
            </div>
        </div>
    </div>
</nav>
