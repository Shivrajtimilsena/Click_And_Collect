@extends('app')

@section('title', 'Become a Trader | Click&Collect')

@section('content')
<main class="pt-32 pb-24 px-6 max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
    <!-- Hero & Editorial Column -->
    <div class="lg:col-span-5 space-y-12">
        <div class="relative">
            <div class="absolute -top-12 -left-12 w-48 h-48 bg-primary-container/20 rounded-full blur-3xl"></div>
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tighter leading-[0.9] text-on-background relative z-10">
                Empower Your <span class="text-primary">Craft.</span>
            </h1>
            <p class="mt-8 text-lg text-on-surface-variant max-w-md leading-relaxed">
                Join an exclusive collective of local artisans. Bridge the gap between your workshop and your neighbors through our premium 'Click and Collect' platform.
            </p>
        </div>
        <!-- Visual Bento Elements -->
        <div class="grid grid-cols-2 gap-8 items-center">
            <!-- Left - Bakeries Card -->
            <div class="bg-surface-container rounded-3xl overflow-hidden relative group">
                <img class="w-full h-full object-cover transition-all duration-700" alt="Bakeries" src="/images/traderregistrationImage.png">
                <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-on-background">Bakeries</span>
                </div>
            </div>
            
            <!-- Right - Single Large Butchers Card -->
            <div class="bg-surface-container rounded-3xl overflow-hidden relative group h-96">
                <img class="w-full h-full object-cover transition-all duration-700" alt="Butchers" src="/images/traderregistrationImage.png">
                <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-on-background">Butchers</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Form Column -->
    <div class="lg:col-span-7 bg-surface-container-lowest rounded-lg p-8 md:p-12 shadow-[0_10px_30px_rgba(45,47,47,0.04)] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -mr-16 -mt-16"></div>
        <div class="mb-10 relative z-10">
            <h2 class="text-2xl font-bold text-on-background uppercase tracking-tight">Trader Registration</h2>
            <div class="h-1 w-12 bg-primary mt-2 rounded-full"></div>
        </div>
        <form action="{{ route('trader.apply.submit') }}" method="POST" class="space-y-8 relative z-10">
            @csrf
            
            <!-- Shop Details Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="shop_name" class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant ml-4">Shop Name</label>
                    <input 
                        type="text" 
                        name="shop_name" 
                        id="shop_name"
                        value="{{ old('shop_name') }}"
                        class="w-full bg-surface-container-high border-0 rounded-md px-6 py-4 focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all placeholder:text-outline-variant @error('shop_name') ring-2 ring-error @enderror" 
                        placeholder="e.g. The Golden Crust"
                        required
                    />
                    @error('shop_name')
                        <p class="text-xs text-error ml-4">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Address Section -->
            <div class="space-y-2">
                <label for="location" class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant ml-4">Business Address</label>
                <input 
                    type="text" 
                    name="location" 
                    id="location"
                    value="{{ old('location') }}"
                    class="w-full bg-surface-container-high border-0 rounded-md px-6 py-4 focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all placeholder:text-outline-variant @error('location') ring-2 ring-error @enderror" 
                    placeholder="Street Address, City, Postcode"
                    required
                />
                @error('location')
                    <p class="text-xs text-error ml-4">{{ $message }}</p>
                @enderror
            </div>

            <!-- Contact Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="email" class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant ml-4">Contact Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        value="{{ old('email') }}"
                        class="w-full bg-surface-container-high border-0 rounded-md px-6 py-4 focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all placeholder:text-outline-variant @error('email') ring-2 ring-error @enderror" 
                        placeholder="artisan@localcurator.com"
                        required
                    />
                    @error('email')
                        <p class="text-xs text-error ml-4">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="space-y-2">
                <label for="speciality" class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant ml-4">What Makes Your Shop Special</label>
                <textarea 
                    name="speciality" 
                    id="speciality"
                    class="w-full bg-surface-container-high border-0 rounded-md px-6 py-4 focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all placeholder:text-outline-variant resize-none @error('speciality') ring-2 ring-error @enderror" 
                    placeholder="Tell us about the craftsmanship behind your products..." 
                    rows="3"
                    maxlength="100"
                    required
                >{{ old('speciality') }}</textarea>
                @error('speciality')
                    <p class="text-xs text-error ml-4">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="description" class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant ml-4">About Your Goods</label>
                <textarea 
                    name="description" 
                    id="description"
                    class="w-full bg-surface-container-high border-0 rounded-md px-6 py-4 focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all placeholder:text-outline-variant resize-none @error('description') ring-2 ring-error @enderror" 
                    placeholder="Tell customers about your shop, your story, and what customers can expect..." 
                    rows="4"
                    maxlength="100"
                    required
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-xs text-error ml-4">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="password" class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant ml-4">Password</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            class="w-full bg-surface-container-high border-0 rounded-md px-6 py-4 pr-14 focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all placeholder:text-outline-variant @error('password') ring-2 ring-error @enderror" 
                            placeholder="Minimum 8 characters"
                            required
                        />
                        <button type="button" onclick="togglePassword('password', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface transition-colors cursor-pointer">
                            <span class="material-symbols-outlined text-xl">visibility</span>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-error ml-4">{{ $message }}</p>
                    @enderror
                </div>
                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant ml-4">Confirm Password</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation"
                            class="w-full bg-surface-container-high border-0 rounded-md px-6 py-4 pr-14 focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all placeholder:text-outline-variant" 
                            placeholder="Repeat password"
                            required
                        />
                        <button type="button" onclick="togglePassword('password_confirmation', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface transition-colors cursor-pointer">
                            <span class="material-symbols-outlined text-xl">visibility</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Verification Checkbox -->
            <div class="flex items-start space-x-4 p-4 rounded-xl bg-surface">
                <input type="checkbox" required class="mt-1 rounded border-outline text-primary focus:ring-primary" />
                <p class="text-xs text-on-surface-variant leading-relaxed">
                    I confirm that my business complies with local health and safety regulations and I agree to the Trader Terms of Service.
                </p>
            </div>

            <!-- Primary CTA -->
            <button 
                type="submit" 
                class="w-full py-5 rounded-full bg-gradient-to-r from-primary to-primary-container text-on-primary font-bold uppercase tracking-widest shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all duration-300 flex items-center justify-center space-x-3 group"
            >
                <span>Register as Trader</span>
                <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </button>
        </form>
    </div>
</main>

<!-- Trust Section -->
<section class="max-w-7xl mx-auto px-6 pb-32">
    <div class="bg-surface-container-low rounded-xl p-12 flex flex-col md:flex-row items-center justify-between gap-12">
        <div class="text-center md:text-left space-y-4">
            <h3 class="text-3xl font-bold text-on-background">Why Join Us?</h3>
            <p class="text-on-surface-variant max-w-sm">Built by locals for locals. We handle the tech, you handle the craft.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
            <div class="flex flex-col items-center text-center space-y-3">
                <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-primary shadow-sm">
                    <span class="material-symbols-outlined">payments</span>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest">Low Fees</span>
            </div>
            <div class="flex flex-col items-center text-center space-y-3">
                <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-primary shadow-sm">
                    <span class="material-symbols-outlined">trending_up</span>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest">Growth Tools</span>
            </div>
            <div class="flex flex-col items-center text-center space-y-3">
                <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-primary shadow-sm">
                    <span class="material-symbols-outlined">groups</span>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest">Community</span>
            </div>
        </div>
    </div>
</section>
@endsection

<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('.material-symbols-outlined');
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        input.type = 'password';
        icon.textContent = 'visibility';
    }
}
</script>
