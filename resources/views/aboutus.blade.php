<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Click & Collect</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body { font-family: 'Manrope', sans-serif; }
        h1, h2, h3, h4 { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-white">
    <!-- Top Navigation Bar -->
    <nav class="fixed top-0 w-full z-50 h-20 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-2xl shadow-[0_10px_30px_rgba(45,47,47,0.04)]">
        <div class="flex justify-between items-center px-12 w-full max-w-[1920px] mx-auto h-full">
            <a href="/" class="text-2xl font-black text-zinc-800 dark:text-zinc-100 tracking-tighter hover:opacity-80 transition-opacity">Click&Collect</a>
            <div class="hidden md:flex items-center gap-8">
                <a class="font-['Plus_Jakarta_Sans'] uppercase tracking-[0.05em] text-[12px] font-bold text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-white transition-all" href="/">Home</a>
                <a class="font-['Plus_Jakarta_Sans'] uppercase tracking-[0.05em] text-[12px] font-bold text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-white transition-all" href="#">New Arrivals</a>
                <a class="font-['Plus_Jakarta_Sans'] uppercase tracking-[0.05em] text-[12px] font-bold text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-white transition-all" href="#">Shops</a>
                <a class="font-['Plus_Jakarta_Sans'] uppercase tracking-[0.05em] text-[12px] font-bold text-orange-700 dark:text-orange-500 border-b-2 border-orange-700 pb-1" href="/aboutus">About Us</a>
            </div>
            <div class="flex items-center gap-6">
                <div class="relative hidden lg:block">
                    <input class="bg-gray-100 border-none rounded-full px-6 py-2 text-sm w-64 focus:ring-2 focus:ring-red-600/20 transition-all" placeholder="Search local curators..." type="text"/>
                </div>
                <div class="flex gap-4">
                    @auth
                        <button class="hover:opacity-80 transition-opacity scale-95 active:scale-90 transition-transform"><span class="material-symbols-outlined text-zinc-800" data-icon="favorite">favorite</span></button>
                        <button class="hover:opacity-80 transition-opacity scale-95 active:scale-90 transition-transform"><span class="material-symbols-outlined text-zinc-800" data-icon="shopping_bag">shopping_bag</span></button>
                        <div class="relative group">
                            <button class="hover:opacity-80 transition-opacity scale-95 active:scale-90 transition-transform"><span class="material-symbols-outlined text-zinc-800" data-icon="person">person</span></button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 py-2">
                                <p class="px-4 py-2 text-sm font-medium text-zinc-800">{{ auth()->user()->name }}</p>
                                <a href="#" class="block px-4 py-2 text-sm text-zinc-700 hover:bg-gray-100 transition-colors">My Profile</a>
                                <a href="#" class="block px-4 py-2 text-sm text-zinc-700 hover:bg-gray-100 transition-colors">My Orders</a>
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-zinc-700 hover:bg-gray-100 transition-colors">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="font-['Plus_Jakarta_Sans'] uppercase tracking-[0.05em] text-[12px] font-bold text-zinc-600 hover:text-zinc-800 transition-all">Sign In</a>
                        <a href="{{ route('register') }}" class="font-['Plus_Jakarta_Sans'] uppercase tracking-[0.05em] text-[12px] font-bold text-white bg-red-600 px-6 py-2 rounded-full hover:bg-red-700 transition-all">Sign Up</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gray-50 py-20 pt-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-12 items-center">
                <div>
                    <span class="inline-block text-red-600 font-semibold mb-2">OUR STORY</span>
                    <h1 class="text-5xl font-bold text-gray-900 mb-6 leading-tight">
                        Connecting You to<br/>
                        the <span class="italic">Heart</span> of the<br/>
                        Village.
                    </h1>
                    <p class="text-lg text-gray-700 mb-4">We are a digital bridge between marketplace artisans in your neighborhood and your kitchen table. High-end curators and small-batch specialists thrive when bypassing the middle shop and selling directly.</p>
                    <button class="bg-red-600 text-white px-8 py-3 rounded-full font-semibold hover:bg-red-700">EXPLORE THE MARKET</button>
                </div>
                <div class="bg-gray-400 rounded-2xl overflow-hidden">
                    <div class="bg-gray-500 rounded-2xl p-8 text-white">
                        <div class="text-center">
                            <p class="text-lg font-semibold mb-2">LOUA & MARKET</p>
                            <p class="text-sm mb-4">SAFE RE WORK</p>
                            <img src="https://via.placeholder.com/300x250?text=Market+Store" alt="Loua Market" class="rounded-lg">
                            <p class="text-sm mt-4">"The finest vegetables in the valley, delivered without the hype."</p>
                            <p class="text-xs mt-2">- LOUA KUUSAR</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- The Local Curator Philosophy -->
    <div class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-8">The Local Curator Philosophy.</h2>
                    <p class="text-lg text-gray-700 mb-6">We believe that local commerce is the soul of our community. At its best, a fast-paced world needs the butcher, the baker, and the greensgrocer are often missed for convenience.</p>
                    <p class="text-lg text-gray-700 mb-6">By unifying the fragmented local shopping experiences, we empower artisans to support their favorite small businesses through a single, seamless digital storefront. We don't just aggregate; we curate. Every artisan on our platform has been hand-selected and vetted for their commitment to quality.</p>
                    <p class="text-lg text-gray-700">By unifying the fragmented local shopping experiences, we empower retailers to support their favorite small businesses through a single, seamless digital storefront. We don't just aggregate; we curate. Every artisan on our platform has been hand-selected for their commitment to quality.</p>
                </div>
                <div class="bg-gray-100 rounded-2xl overflow-hidden">
                    <img src="https://via.placeholder.com/400x500?text=Local+Artisans" alt="Local artisans at work" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </div>

    <!-- Pure Simplicity Section -->
    <div class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-gray-900 mb-16">The Process<br/>Pure Simplicity.</h2>
            <div class="grid grid-cols-3 gap-8">
                <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                    <div class="text-red-600 text-3xl">🛒</div>
                </div>
                <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                    <div class="text-red-600 text-3xl">✓</div>
                </div>
                <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                    <div class="text-red-600 text-3xl">❤️</div>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Curate Your Basket</h3>
                    <p class="text-gray-700">Discover artisan products curated locally from a desire to preserve the heritage of local shopping here uniting ease of modern discovery.</p>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Unified Checkout</h3>
                    <p class="text-gray-700">Checkout once for everything in your basket. Manage our secure, hygiene and safety standards for your entire shopping cart.</p>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Collective Collection</h3>
                    <p class="text-gray-700">Curated checkout from all sellers on Wednesday during this venue, drop-in pick up at your local</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Meet Our Artisans -->
    <div class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900">Meet Our Artisans</h2>
                <a href="#" class="text-red-600 font-semibold hover:text-red-700">VIEW ALL 47 VENDORS</a>
            </div>
            <div class="grid grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-32 h-32 bg-orange-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <div class="text-4xl">🥖</div>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Heritage Bakery</h3>
                    <p class="text-gray-600 text-sm">Artisan Bakery</p>
                    <p class="text-gray-700 text-sm mt-2">Baked fresh daily since 1952</p>
                </div>
                <div class="text-center">
                    <div class="w-32 h-32 bg-red-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <div class="text-4xl">🍖</div>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">The Village Butcher</h3>
                    <p class="text-gray-600 text-sm">Ethical Butchery</p>
                    <p class="text-gray-700 text-sm mt-2">Ethically sourced quality meat</p>
                </div>
                <div class="text-center">
                    <div class="w-32 h-32 bg-green-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <div class="text-4xl">🌱</div>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Green Valley</h3>
                    <p class="text-gray-600 text-sm">Seasonal Grocer</p>
                    <p class="text-gray-700 text-sm mt-2">Seasonal organic farm fresh</p>
                </div>
                <div class="text-center">
                    <div class="w-32 h-32 bg-yellow-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <div class="text-4xl">☕</div>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Charm & Card</h3>
                    <p class="text-gray-600 text-sm">Craft Specialists</p>
                    <p class="text-gray-700 text-sm mt-2">Single origin specialty coffee</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sustainability Section -->
    <div class="bg-gray-900 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="text-red-600 text-4xl mb-4">⚡</div>
                    <h3 class="text-xl font-bold mb-2">Sustainability</h3>
                    <p class="text-gray-300">We believe in supporting organic artisans and pledging vendors are collectively managing carbon footprint with high-end packaging initiatives.</p>
                </div>
                <div class="text-center">
                    <div class="text-red-600 text-4xl mb-4">👥</div>
                    <h3 class="text-xl font-bold mb-2">Community First</h3>
                    <p class="text-gray-300">Trade margins stay high. Everything focuses on bringing local businesses together with a digital collective ecosystem. We are a public benefit organization.</p>
                </div>
                <div class="text-center">
                    <div class="text-red-600 text-4xl mb-4">♻️</div>
                    <h3 class="text-xl font-bold mb-2">Zero Waste</h3>
                    <p class="text-gray-300">Our ethical values mean we must minimize waste created by today's shopping bag-centric model from a working local artisan trade.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-red-600 text-white py-20">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-6">Ready to join the collective?</h2>
            <p class="text-xl mb-8">Support your neighborhood artisans and enjoy the difference of local craftsmanship.</p>
            <div class="flex justify-center space-x-4">
                <button class="bg-gray-900 text-white px-8 py-3 rounded-full font-semibold hover:bg-gray-800">SHOP THE MARKET</button>
                <button class="border-2 border-white text-white px-8 py-3 rounded-full font-semibold hover:bg-white hover:text-red-600">VENDOR PORTAL</button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-4 gap-8 mb-8">
                <div>
                    <h4 class="text-white font-semibold mb-4">COMPANY</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white">The Story</a></li>
                        <li><a href="#" class="hover:text-white">Sustainability</a></li>
                        <li><a href="#" class="hover:text-white">Affiliates</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">SUPPORT</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white">Shipping & Returns</a></li>
                        <li><a href="#" class="hover:text-white">Contact</a></li>
                        <li><a href="#" class="hover:text-white">Privacy Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">INFO</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white">FAQ</a></li>
                        <li><a href="#" class="hover:text-white">Terms</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">NEWSLETTER</h4>
                    <p class="text-sm mb-3">Curated weekly highlights from your local market.</p>
                    <div class="flex">
                        <input type="email" placeholder="Email address" class="px-3 py-2 bg-gray-800 text-white rounded-l text-sm flex-1">
                        <button class="bg-red-600 px-3 py-2 rounded-r hover:bg-red-700">→</button>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8">
                <div class="flex justify-between items-center">
                    <p class="text-sm">&copy; 2025 Click&Collect. Freshness Curated Locally.</p>
                    <div class="flex space-x-4 text-sm">
                        <a href="#" class="hover:text-white">Legal</a>
                        <a href="#" class="hover:text-white">Terms & Conditions</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
