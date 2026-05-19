<nav class="fixed top-0 w-full z-50 h-20 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-2xl shadow-[0_10px_30px_rgba(45,47,47,0.04)]">
    <div class="flex justify-between items-center px-4 lg:px-12 w-full max-w-[1920px] mx-auto h-full">
        <div class="flex items-center gap-3">
            <button id="hamburger-btn" class="lg:hidden flex items-center justify-center w-10 h-10 rounded-full hover:bg-surface-container-high transition-colors" aria-label="Menu">
                <span class="material-symbols-outlined text-zinc-800 text-2xl">menu</span>
            </button>
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Click&Collect" class="h-12 md:h-12 w-auto" />
            </a>
        </div>

        <div class="hidden lg:flex items-center gap-8">
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

        <div class="flex items-center gap-2 md:gap-6">
            <div class="relative flex-1 max-w-[160px] md:max-w-[200px] lg:max-w-none">
                <form action="{{ route('products.index') }}" method="GET">
                    <input type="text" name="search" placeholder="{{ request()->is('/') ? 'Search...' : 'Search local curators...' }}" value="{{ request('search') }}" class="w-full bg-surface-container-high border-none rounded-full px-3 md:px-6 py-1.5 md:py-2 text-xs md:text-sm focus:ring-2 focus:ring-primary/20 transition-all"/>
                </form>
            </div>

            <div class="flex gap-2 md:gap-4">
                @if (auth()->check())
                    @if(Auth::user()->role !== 'TRADER')
                        <a href="{{ route('wishlist.index') }}" class="hover:opacity-80 transition-opacity scale-95 active:scale-90 transition-transform">
                            <span class="material-symbols-outlined text-zinc-800 text-xl md:text-2xl">favorite</span>
                        </a>
                        <a href="{{ route('cart.index') }}" class="hover:opacity-80 transition-opacity scale-95 active:scale-90 transition-transform relative">
                            <span class="material-symbols-outlined text-zinc-800 text-xl md:text-2xl">shopping_bag</span>
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
                        <button class="hover:opacity-80 transition-opacity scale-95 active:scale-90 transition-transform flex items-center justify-center w-8 h-8">
                            @if (auth()->user()->avatar_url)
                                <img 
                                    src="{{ auth()->user()->avatar_url }}" 
                                    alt="Profile"
                                    class="w-7 h-7 md:w-8 md:h-8 rounded-full object-cover"
                                />
                            @else
                                <span class="material-symbols-outlined text-zinc-800 text-xl md:text-2xl">person</span>
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
                    <button onclick="openAuthModal('login')" class="bg-primary text-on-primary px-4 md:px-6 py-2 md:py-2.5 text-xs md:text-sm font-bold hover:opacity-90 active:scale-95 transition-all">
                        Sign In
                    </button>
                @endif
            </div>
        </div>
    </div>
</nav>

<div id="mobile-menu" class="fixed inset-0 z-[100] hidden lg:hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeMobileMenu()"></div>
    <div class="fixed top-0 left-0 h-full w-72 max-w-[85vw] bg-white shadow-2xl flex flex-col">
        <div class="flex items-center justify-between px-4 h-20 border-b border-surface-container-high">
            <span class="font-headline font-bold text-lg">Menu</span>
            <button onclick="closeMobileMenu()" class="w-10 h-10 rounded-full hover:bg-surface-container-high flex items-center justify-center transition-colors" aria-label="Close menu">
                <span class="material-symbols-outlined text-zinc-800 text-2xl">close</span>
            </button>
        </div>
            <div class="flex-1 overflow-y-auto p-4 space-y-1">
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm {{ request()->routeIs('home') ? 'bg-primary/10 text-primary' : 'text-zinc-700 hover:bg-surface-container-high transition-colors' }}">
                <span class="material-symbols-outlined text-lg">home</span>
                Home
            </a>
            <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm text-zinc-700 hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-lg">local_mall</span>
                New Arrivals
            </a>
            <a href="{{ route('shops.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm text-zinc-700 hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-lg">store</span>
                Shops
            </a>
            <a href="{{ route('aboutus') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm text-zinc-700 hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-lg">info</span>
                About Us
            </a>
            <a href="{{ route('trader.apply') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm text-primary hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-lg">badge</span>
                Become a Trader
            </a>
        </div>
        @if (!auth()->check())
            <div class="p-4 border-t border-surface-container-high">
                <button onclick="closeMobileMenu(); openAuthModal('login');" class="w-full bg-primary text-on-primary px-6 py-3 rounded-full font-bold text-sm hover:opacity-90 transition-opacity">
                    Sign In
                </button>
            </div>
        @endif
    </div>
</div>

<script>
function openMobileMenu() {
    var menu = document.getElementById('mobile-menu');
    if (menu) {
        menu.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeMobileMenu() {
    var menu = document.getElementById('mobile-menu');
    if (menu) {
        menu.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('hamburger-btn');
    if (btn) {
        btn.addEventListener('click', openMobileMenu);
    }
});
</script>