<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Trader Portal | Click&Collect')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
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
                    fontFamily: {
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
        .chart-gradient {
            background: linear-gradient(180deg, rgba(177, 34, 9, 0.1) 0%, rgba(177, 34, 9, 0) 100%);
        }
    </style>
</head>
<body class="text-on-surface antialiased">
    @auth
        @if(Auth::user()->role === 'TRADER')
            <aside class="h-screen w-64 fixed left-0 top-0 z-40 bg-surface-container-lowest flex flex-col py-8 px-4 gap-2 border-r border-surface-container-high">
                <a href="{{ route('home') }}" class="px-4 mb-10 hover:opacity-80 transition-opacity cursor-pointer">
                    <h1 class="text-lg font-bold text-zinc-900 font-headline">Click&Collect</h1>
                    <p class="text-xs font-medium text-primary uppercase tracking-widest">Trader Portal</p>
                </a>
                <nav class="flex-1 space-y-1">
                    <a class="flex items-center gap-3 {{ request()->routeIs('trader.dashboard') ? 'bg-primary text-on-primary' : 'text-zinc-600 hover:bg-surface-container-high' }} px-4 py-3 font-medium text-sm transition-all" href="{{ route('trader.dashboard') }}">
                        <span class="material-symbols-outlined">dashboard</span>
                        <span>Dashboard</span>
                    </a>
                    <a class="flex items-center gap-3 {{ request()->routeIs('trader.orders.*') ? 'bg-primary text-on-primary' : 'text-zinc-600 hover:bg-surface-container-high' }} px-4 py-3 font-medium text-sm transition-all" href="{{ route('trader.orders.index') }}">
                        <span class="material-symbols-outlined">shopping_bag</span>
                        <span>Orders</span>
                    </a>
                    <a class="flex items-center gap-3 {{ request()->routeIs('trader.inventory.*') ? 'bg-primary text-on-primary' : 'text-zinc-600 hover:bg-surface-container-high' }} px-4 py-3 font-medium text-sm transition-all" href="{{ route('trader.inventory.index') }}">
                        <span class="material-symbols-outlined">inventory_2</span>
                        <span>Inventory</span>
                    </a>
                    <a class="flex items-center gap-3 {{ request()->routeIs('trader.settings') ? 'bg-primary text-on-primary' : 'text-zinc-600 hover:bg-surface-container-high' }} px-4 py-3 font-medium text-sm transition-all" href="{{ route('trader.settings') }}">
                        <span class="material-symbols-outlined">settings</span>
                        <span>Settings</span>
                    </a>
                </nav>
                <div class="mt-auto px-2">
                    <button class="w-full bg-primary text-on-primary font-bold py-4 px-6 flex items-center justify-center gap-2 shadow-lg shadow-primary/20 active:scale-95 transition-all">
                        <span class="material-symbols-outlined text-sm">add</span>
                        <span class="text-sm uppercase tracking-wider">Add Product</span>
                    </button>
                </div>
            </aside>

            <main class="ml-64 min-h-screen flex flex-col">
                <header class="fixed top-0 right-0 left-64 z-50 bg-white/80 backdrop-blur-xl flex justify-between items-center px-8 h-20 shadow-sm">
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-medium uppercase tracking-wider text-zinc-500">@yield('header-title', 'Overview / Dashboard')</span>
                    </div>
                    <div class="flex items-center gap-6">
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-3 flex items-center text-zinc-400">
                                <span class="material-symbols-outlined text-xl">search</span>
                            </span>
                            <input class="bg-surface-container-high border-none py-2 pl-10 pr-4 text-sm w-64 focus:ring-2 focus:ring-primary/20 transition-all" placeholder="Search orders..." type="text"/>
                        </div>
                        <div class="flex items-center gap-4 text-zinc-500">
                            <button class="hover:text-primary transition-colors active:scale-95 duration-200 relative">
                                <span class="material-symbols-outlined">notifications</span>
                                <span class="absolute top-0 right-0 w-2 h-2 bg-primary rounded-full"></span>
                            </button>
                            <div class="relative group">
                                <button class="hover:text-primary transition-colors active:scale-95 duration-200">
                                    @if(Auth::user()->avatar_url)
                                        <img src="{{ Auth::user()->avatar_url }}" alt="Profile" class="w-8 h-8 rounded-none object-cover"/>
                                    @else
                                        <span class="material-symbols-outlined">account_circle</span>
                                    @endif
                                </button>
                                <div class="absolute right-0 mt-2 w-48 bg-white shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all border border-surface-container-high">
                                    <a href="{{ route('profile.settings') }}" class="block px-4 py-2 hover:bg-surface-container-high">My Profile</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-surface-container-high">Logout</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <div class="pt-28 px-12 pb-12 space-y-8">
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-error/10 text-error border border-error/30">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-500/15 text-green-700 border border-green-500/30 flex justify-between items-center">
                            <span>{{ session('success') }}</span>
                            <button onclick="this.parentElement.style.display='none'" class="font-bold text-xl leading-none">&times;</button>
                        </div>
                    @endif

                    @yield('content')
                </div>

                <footer class="w-full py-6 mt-auto bg-surface-container-lowest flex justify-between items-center px-12 border-t border-surface-container-high">
                    <div class="text-zinc-500 text-xs uppercase tracking-widest">
                        &copy; {{ date('Y') }} Click&Collect Merchant Services
                    </div>
                    <div class="flex gap-8">
                        <a class="text-zinc-500 text-xs uppercase tracking-widest hover:text-primary transition-colors" href="#">Privacy Policy</a>
                        <a class="text-zinc-500 text-xs uppercase tracking-widest hover:text-primary transition-colors" href="#">Support</a>
                        <a class="text-zinc-500 text-xs uppercase tracking-widest hover:text-primary transition-colors" href="#">Terms of Service</a>
                    </div>
                </footer>
            </main>
        @else
            <div class="flex items-center justify-center min-h-screen">
                <div class="text-center">
                    <h1 class="text-2xl font-bold text-error">Access Denied</h1>
                    <p class="mt-2 text-zinc-500">You must be a trader to access this portal.</p>
                    <a href="{{ route('home') }}" class="mt-4 inline-block bg-primary text-on-primary px-6 py-2">Go Home</a>
                </div>
            </div>
        @endif
    @else
        <div class="flex items-center justify-center min-h-screen">
            <div class="text-center">
                <h1 class="text-2xl font-bold">Please Login</h1>
                <p class="mt-2 text-zinc-500">You need to login to access the trader portal.</p>
                <a href="{{ route('signin') }}" class="mt-4 inline-block bg-primary text-on-primary px-6 py-2">Sign In</a>
            </div>
        </div>
    @endauth
</body>
</html>
