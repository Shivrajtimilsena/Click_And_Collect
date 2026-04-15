<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Sign Up | Click and Collect</title>
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <!-- Fonts: Plus Jakarta Sans & Manrope -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-primary-fixed-variant": "#5d0900",
                        "inverse-primary": "#fd583a",
                        "secondary-container": "#e5e2e1",
                        "tertiary": "#9b3f15",
                        "on-tertiary-fixed": "#310b00",
                        "surface": "#f6f6f6",
                        "primary-fixed-dim": "#ff5c3e",
                        "on-tertiary-fixed-variant": "#692100",
                        "on-secondary": "#f5f2f1",
                        "surface-dim": "#d3d5d5",
                        "surface-container-highest": "#dbdddd",
                        "surface-container-low": "#f0f1f1",
                        "tertiary-fixed-dim": "#f68355",
                        "inverse-surface": "#0c0f0f",
                        "on-surface-variant": "#5a5c5c",
                        "tertiary-dim": "#8b3309",
                        "primary": "#b12209",
                        "inverse-on-surface": "#9c9d9d",
                        "error": "#b41340",
                        "surface-container": "#e7e8e8",
                        "secondary": "#5c5b5b",
                        "on-error": "#ffefef",
                        "on-primary": "#ffefec",
                        "on-secondary-container": "#525151",
                        "on-error-container": "#510017",
                        "on-primary-container": "#4c0600",
                        "surface-variant": "#dbdddd",
                        "on-tertiary-container": "#5a1c00",
                        "primary-fixed": "#ff775d",
                        "tertiary-container": "#ff956b",
                        "error-container": "#f74b6d",
                        "surface-container-lowest": "#ffffff",
                        "outline": "#767777",
                        "error-dim": "#a70138",
                        "surface-container-high": "#e1e3e3",
                        "on-secondary-fixed": "#403f3f",
                        "on-surface": "#2d2f2f",
                        "on-background": "#2d2f2f",
                        "outline-variant": "#acadad",
                        "on-tertiary": "#ffefeb",
                        "background": "#f6f6f6",
                        "surface-tint": "#b12209",
                        "surface-bright": "#f6f6f6",
                        "secondary-fixed-dim": "#d6d4d3",
                        "secondary-fixed": "#e5e2e1",
                        "primary-dim": "#9f1600",
                        "on-secondary-fixed-variant": "#5c5b5b",
                        "tertiary-fixed": "#ff956b",
                        "on-primary-fixed": "#000000",
                        "primary-container": "#ff775d",
                        "secondary-dim": "#504f4f"
                    },
                    fontFamily: {
                        "headline": ["Plus Jakarta Sans"],
                        "body": ["Manrope"],
                        "label": ["Manrope"]
                    },
                    borderRadius: {"DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px"},
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(24px);
        }
    </style>
</head>
<body class="bg-surface font-body text-on-surface antialiased overflow-x-hidden">
<div class="min-h-screen flex flex-col md:flex-row">
    <!-- Brand Visual Side (Left) -->
    <div class="hidden md:flex md:w-1/2 lg:w-3/5 bg-on-background relative overflow-hidden items-center justify-center p-12">
        <!-- Decorative Elements -->
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] rounded-full bg-gradient-to-br from-primary to-primary-fixed opacity-20 blur-[100px]"></div>
        <div class="absolute bottom-[-5%] left-[-5%] w-[300px] h-[300px] rounded-full bg-tertiary opacity-10 blur-[80px]"></div>
        <div class="relative z-10 max-w-xl">
            <span class="inline-block px-4 py-2 rounded-full bg-primary-container/10 border border-primary/20 text-primary-fixed font-bold text-xs uppercase tracking-widest mb-8">
                Join Our Community
            </span>
            <h1 class="font-headline text-6xl lg:text-8xl font-black text-surface tracking-tighter leading-[0.9] mb-8">
                Click and Collect.
            </h1>
            <p class="text-surface-variant text-xl leading-relaxed max-w-md font-light">
                Create your account to discover the finest local artisans and enjoy curated shopping at your convenience.
            </p>
            <div class="mt-16 grid grid-cols-2 gap-8">
                <div class="bg-surface-container-highest/5 backdrop-blur-sm p-8 rounded-lg">
                    <span class="material-symbols-outlined text-primary-fixed mb-4" data-icon="verified_user">verified_user</span>
                    <h3 class="text-surface font-headline font-bold text-lg">Safe & Secure</h3>
                    <p class="text-surface-variant text-sm mt-2">Your data is protected with industry-standard encryption.</p>
                </div>
                <div class="bg-surface-container-highest/5 backdrop-blur-sm p-8 rounded-lg">
                    <span class="material-symbols-outlined text-primary-fixed mb-4" data-icon="card_giftcard">card_giftcard</span>
                    <h3 class="text-surface font-headline font-bold text-lg">Exclusive Perks</h3>
                    <p class="text-surface-variant text-sm mt-2">Members get early access to new shops and special offers.</p>
                </div>
            </div>
        </div>
        <!-- Floating Image Detail -->
        <div class="absolute bottom-12 right-12 w-64 h-80 rounded-xl overflow-hidden shadow-2xl rotate-3 hover:rotate-0 transition-transform duration-500 hidden lg:block">
            <img alt="Luxury Local Boutique" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAk1_jqouTfL4bEq2lWVGAi69yZB216H_CdFsjfC2TGTUle8n3ZnEkmPrHscOfodpZOZFTpCzuGGYdznTzv4p9H2wA_2nCy4oJQyiHJgGcIwFbaUS0Znv_fXnA3JvsHt5pVYle3V0izQTPv-86mSJsdoqtybs_Fml8_sqTZSi1_dsAXPn3X8dDFo-njgLTmWUlopQ5fBP_bVEmCNcQaAodqpr71JT074Qqd_yo86HSuq3gYRD7s-y_NdLlDzzpHygWxHR3cGfgeBg"/>
            <div class="absolute inset-0 bg-gradient-to-t from-on-background/80 to-transparent flex flex-col justify-end p-6">
                <p class="text-white text-xs uppercase tracking-widest font-bold">Member Benefit</p>
                <p class="text-surface-variant text-sm">Early Access to New Shops</p>
            </div>
        </div>
    </div>

    <!-- Sign Up Form Side (Right) -->
    <main class="w-full md:w-1/2 lg:w-2/5 flex flex-col justify-center px-6 py-12 lg:px-24 bg-surface">
        <!-- Mobile Header Only -->
        <div class="md:hidden mb-12">
            <h1 class="font-headline text-3xl font-black text-on-background tracking-tighter">
                Click and Collect.
            </h1>
        </div>

        <div class="w-full max-w-md mx-auto">
            <header class="mb-10">
                <h2 class="font-headline text-4xl font-bold text-on-background tracking-tight">Create Account</h2>
                <p class="text-on-surface-variant mt-2">Join us and start exploring local treasures.</p>
            </header>

            <!-- Display Validation Errors -->
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-error-container/20 border border-error/30">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm text-error font-medium">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Full Name Field -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-on-surface-variant ml-1" for="name">
                        Full Name
                    </label>
                    <div class="relative">
                        <input
                            class="w-full px-6 py-4 bg-surface-container-high rounded-md border-none focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all duration-300 placeholder:text-outline/50 font-medium @error('name') ring-2 ring-error @enderror"
                            id="name"
                            name="name"
                            type="text"
                            placeholder="John Doe"
                            value="{{ old('name') }}"
                            required
                            autofocus
                        />
                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline/40" data-icon="person">person</span>
                    </div>
                    @error('name')
                        <p class="text-xs text-error font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-on-surface-variant ml-1" for="email">
                        Email Address
                    </label>
                    <div class="relative">
                        <input
                            class="w-full px-6 py-4 bg-surface-container-high rounded-md border-none focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all duration-300 placeholder:text-outline/50 font-medium @error('email') ring-2 ring-error @enderror"
                            id="email"
                            name="email"
                            type="email"
                            placeholder="name@example.com"
                            value="{{ old('email') }}"
                            required
                        />
                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline/40" data-icon="mail">mail</span>
                    </div>
                    @error('email')
                        <p class="text-xs text-error font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-on-surface-variant ml-1" for="password">
                        Password
                    </label>
                    <div class="relative">
                        <input
                            class="w-full px-6 py-4 bg-surface-container-high rounded-md border-none focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all duration-300 placeholder:text-outline/50 font-medium @error('password') ring-2 ring-error @enderror"
                            id="password"
                            name="password"
                            type="password"
                            placeholder="••••••••"
                            required
                            autocomplete="new-password"
                        />
                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline/40" data-icon="lock">lock</span>
                    </div>
                    @error('password')
                        <p class="text-xs text-error font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-on-surface-variant ml-1" for="password_confirmation">
                        Confirm Password
                    </label>
                    <div class="relative">
                        <input
                            class="w-full px-6 py-4 bg-surface-container-high rounded-md border-none focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all duration-300 placeholder:text-outline/50 font-medium @error('password_confirmation') ring-2 ring-error @enderror"
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            placeholder="••••••••"
                            required
                            autocomplete="new-password"
                        />
                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline/40" data-icon="lock_check">lock_check</span>
                    </div>
                    @error('password_confirmation')
                        <p class="text-xs text-error font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Terms Agreement -->
                <div class="flex items-start space-x-3 py-2">
                    <input
                        class="w-5 h-5 mt-1 rounded border-none bg-surface-container-high text-primary focus:ring-primary/20"
                        id="agree_terms"
                        name="agree_terms"
                        type="checkbox"
                        required
                    />
                    <label class="text-sm text-on-surface-variant font-medium select-none" for="agree_terms">
                        I agree to the
                        <a class="text-primary font-bold hover:underline" href="#">Terms of Service</a>
                        and
                        <a class="text-primary font-bold hover:underline" href="#">Privacy Policy</a>
                    </label>
                </div>

                <!-- Primary Action -->
                <button
                    class="w-full py-5 rounded-full bg-gradient-to-r from-primary to-primary-fixed text-on-primary font-bold text-lg shadow-[0_10px_30px_rgba(177,34,9,0.15)] hover:shadow-[0_15px_35px_rgba(177,34,9,0.25)] active:scale-[0.98] transition-all duration-300 flex items-center justify-center group disabled:opacity-50"
                    type="submit"
                >
                    Create My Account
                    <span class="material-symbols-outlined ml-2 group-hover:translate-x-1 transition-transform" data-icon="arrow_forward">arrow_forward</span>
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-12">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full h-[1px] bg-surface-container-high"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="px-4 bg-surface text-[10px] font-bold uppercase tracking-[0.2em] text-outline">or sign up with</span>
                </div>
            </div>

            <!-- Secondary Actions (Social) -->
            <div class="grid grid-cols-2 gap-4">
                <button
                    class="flex items-center justify-center px-4 py-4 rounded-full bg-surface-container hover:bg-surface-container-highest transition-colors duration-200"
                    type="button"
                >
                    <img alt="Google" class="w-5 h-5 mr-3" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC27Arm-U0gT-2VWOgrhGcU37dT2x46Hcl73iT8ICuEXw9zUBxwxBWXhvu_u0BBtS9kn1T2lKywG6Tttq_JJXvNFeL-bG2DoO_zswduGACJcCpkInyPZatBfQHpRJZMgWY0pldBSuo686nOGs5nHKdeJLT7goYxQ7CCxDzQJzn-TdSYZlbBFCz2x7dhCCOF2axpUqNAlmPMzbbYm4p9nKtIFttK61I-3KQev20dOMk3DdFCs_7B-62ZsIBpyzkIZdmoSok_Lrfb9A"/>
                    <span class="text-sm font-bold text-on-surface">Google</span>
                </button>
                <button
                    class="flex items-center justify-center px-4 py-4 rounded-full bg-surface-container hover:bg-surface-container-highest transition-colors duration-200"
                    type="button"
                >
                    <span class="material-symbols-outlined mr-3 text-on-surface" data-icon="apple" style="font-variation-settings: 'FILL' 1;">ios</span>
                    <span class="text-sm font-bold text-on-surface">Apple</span>
                </button>
            </div>

            <!-- Footer Sign In -->
            <footer class="mt-12 text-center">
                <p class="text-on-surface-variant font-medium">
                    Already have an account?
                    <a class="text-primary font-bold hover:underline decoration-2 underline-offset-4 ml-1" href="{{ route('login') }}">Sign in here</a>
                </p>
            </footer>
        </div>

        <!-- Legal Footer -->
        <div class="mt-auto pt-12 flex justify-center space-x-6 text-[10px] font-bold uppercase tracking-widest text-outline">
            <a class="hover:text-primary transition-colors" href="#">Privacy Policy</a>
            <a class="hover:text-primary transition-colors" href="#">Terms of Service</a>
            <a class="hover:text-primary transition-colors" href="#">Help Center</a>
        </div>
    </main>
</div>

<!-- Background Decoration for Mobile -->
<div class="fixed top-0 left-0 w-full h-full -z-10 pointer-events-none md:hidden">
    <div class="absolute top-[-10%] left-[-10%] w-64 h-64 bg-primary-container/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-[-5%] right-[-5%] w-80 h-80 bg-tertiary-container/10 rounded-full blur-3xl"></div>
</div>
</body>
</html>
