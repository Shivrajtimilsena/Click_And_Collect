<!-- Auth Modal -->
<div id="auth-modal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4" style="display: none;">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAuthModal()"></div>
    
    <!-- Modal Content -->
    <div class="relative bg-surface rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto z-10" onclick="event.stopPropagation()">
        <!-- Close Button -->
        <button onclick="closeAuthModal()" class="absolute top-4 right-4 p-2 hover:bg-surface-container rounded-full transition-colors z-10">
            <span class="material-symbols-outlined">close</span>
        </button>
            
            <div class="p-8 lg:p-10">
                <!-- Mobile Header -->
                <div class="mb-8">
                    <h1 class="font-headline text-2xl font-black text-on-background tracking-tighter">Click and Collect.</h1>
                </div>
                
                <!-- Tabs -->
                <div class="flex gap-4 mb-8 border-b border-surface-container-high">
                    <button onclick="switchTab('login')" id="login-tab" class="pb-4 font-bold text-primary border-b-2 border-primary transition-colors">
                        Sign In
                    </button>
                    <button onclick="switchTab('signup')" id="signup-tab" class="pb-4 font-bold text-on-surface-variant hover:text-on-surface transition-colors">
                        Sign Up
                    </button>
                </div>
                
                <!-- Login Form -->
                <div id="login-form" class="space-y-6">
                    <header class="mb-8">
                        <h2 class="font-headline text-3xl font-bold text-on-background">Sign In</h2>
                        <p class="text-on-surface-variant mt-2 text-sm">Enter your credentials to access your account.</p>
                    </header>
                    
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf
                        
                        <!-- Email Field -->
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-on-surface-variant ml-1" for="login-email">
                                Email Address
                            </label>
                            <div class="relative">
                                <input class="w-full px-6 py-4 bg-surface-container-high rounded-lg border-2 border-transparent focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest focus:border-primary transition-all duration-300 placeholder:text-outline/50 font-medium" 
                                       id="login-email" 
                                       name="email" 
                                       placeholder="name@example.com" 
                                       type="email"
                                       required/>
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline/40">mail</span>
                            </div>
                            @error('email')
                            <p class="text-error text-sm ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Password Field -->
                        <div class="space-y-2">
                            <div class="flex justify-between items-end ml-1">
                                <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-on-surface-variant" for="login-password">
                                    Password
                                </label>
                                <a class="text-[10px] font-bold uppercase tracking-widest text-primary hover:text-primary-dim transition-colors" href="#">
                                    Forgot Password?
                                </a>
                            </div>
                            <div class="relative">
                                <input class="w-full px-6 py-4 bg-surface-container-high rounded-lg border-2 border-transparent focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest focus:border-primary transition-all duration-300 placeholder:text-outline/50 font-medium" 
                                       id="login-password" 
                                       name="password" 
                                       placeholder="••••••••" 
                                       type="password"
                                       required/>
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline/40">lock</span>
                            </div>
                            @error('password')
                            <p class="text-error text-sm ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Remember Me -->
                        <div class="flex items-center space-x-3 py-2">
                            <input class="w-5 h-5 rounded border-2 border-surface-container-high bg-surface-container-high text-primary focus:ring-primary/20 cursor-pointer" 
                                   id="remember" 
                                   name="remember" 
                                   type="checkbox"/>
                            <label class="text-sm text-on-surface-variant font-medium select-none cursor-pointer" for="remember">
                                Remember me for 30 days
                            </label>
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" class="w-full py-5 rounded-full bg-gradient-to-r from-primary to-primary-fixed text-on-primary font-bold text-lg shadow-[0_10px_30px_rgba(177,34,9,0.15)] hover:shadow-[0_15px_35px_rgba(177,34,9,0.25)] active:scale-[0.98] transition-all duration-300 flex items-center justify-center group">
                            Sign In to Account
                            <span class="material-symbols-outlined ml-2 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>
                    </form>
                </div>
                
                <!-- Signup Form -->
                <div id="signup-form" class="space-y-6 hidden">
                    <header class="mb-8">
                        <h2 class="font-headline text-3xl font-bold text-on-background">Create Account</h2>
                        <p class="text-on-surface-variant mt-2 text-sm">Join our community of local shoppers.</p>
                    </header>
                    
                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf
                        
                        <!-- Full Name Field -->
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-on-surface-variant ml-1">
                                Full Name
                            </label>
                            <div class="relative">
                                <input class="w-full px-6 py-4 bg-surface-container-high rounded-lg border-2 border-transparent focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest focus:border-primary transition-all duration-300 placeholder:text-outline/50 font-medium" 
                                       name="name" 
                                       placeholder="John Doe" 
                                       type="text"
                                       required/>
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline/40">person</span>
                            </div>
                            @error('name')
                            <p class="text-error text-sm ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Email Field -->
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-on-surface-variant ml-1">
                                Email Address
                            </label>
                            <div class="relative">
                                <input class="w-full px-6 py-4 bg-surface-container-high rounded-lg border-2 border-transparent focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest focus:border-primary transition-all duration-300 placeholder:text-outline/50 font-medium" 
                                       name="email" 
                                       placeholder="name@example.com" 
                                       type="email"
                                       required/>
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline/40">mail</span>
                            </div>
                            @error('email')
                            <p class="text-error text-sm ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Password Field -->
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-on-surface-variant ml-1">
                                Password
                            </label>
                            <div class="relative">
                                <input class="w-full px-6 py-4 bg-surface-container-high rounded-lg border-2 border-transparent focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest focus:border-primary transition-all duration-300 placeholder:text-outline/50 font-medium" 
                                       name="password" 
                                       placeholder="••••••••" 
                                       type="password"
                                       required/>
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline/40">lock</span>
                            </div>
                            @error('password')
                            <p class="text-error text-sm ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Confirm Password Field -->
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-on-surface-variant ml-1">
                                Confirm Password
                            </label>
                            <div class="relative">
                                <input class="w-full px-6 py-4 bg-surface-container-high rounded-lg border-2 border-transparent focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest focus:border-primary transition-all duration-300 placeholder:text-outline/50 font-medium" 
                                       name="password_confirmation" 
                                       placeholder="••••••••" 
                                       type="password"
                                       required/>
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline/40">lock</span>
                            </div>
                        </div>
                        
                        <!-- Terms Checkbox -->
                        <div class="flex items-start space-x-3 py-2">
                            <input class="w-5 h-5 mt-1 rounded border-2 border-surface-container-high bg-surface-container-high text-primary focus:ring-primary/20 cursor-pointer" 
                                   id="terms" 
                                   type="checkbox"
                                   required/>
                            <label class="text-sm text-on-surface-variant font-medium select-none cursor-pointer" for="terms">
                                I agree to the <a href="#" class="text-primary hover:underline">Terms of Service</a> and <a href="#" class="text-primary hover:underline">Privacy Policy</a>
                            </label>
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" class="w-full py-5 rounded-full bg-gradient-to-r from-primary to-primary-fixed text-on-primary font-bold text-lg shadow-[0_10px_30px_rgba(177,34,9,0.15)] hover:shadow-[0_15px_35px_rgba(177,34,9,0.25)] active:scale-[0.98] transition-all duration-300 flex items-center justify-center group">
                            Create Account
                            <span class="material-symbols-outlined ml-2 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openAuthModal(tab = 'login') {
    document.getElementById('auth-modal').style.display = 'flex';
    switchTab(tab);
}

function closeAuthModal() {
    document.getElementById('auth-modal').style.display = 'none';
}

function switchTab(tab) {
    // Hide all forms
    document.getElementById('login-form').classList.add('hidden');
    document.getElementById('signup-form').classList.add('hidden');
    
    // Remove active state from tabs
    document.getElementById('login-tab').classList.remove('text-primary', 'border-primary');
    document.getElementById('signup-tab').classList.remove('text-primary', 'border-primary');
    
    // Show selected form
    if (tab === 'login') {
        document.getElementById('login-form').classList.remove('hidden');
        document.getElementById('login-tab').classList.add('text-primary', 'border-primary');
    } else {
        document.getElementById('signup-form').classList.remove('hidden');
        document.getElementById('signup-tab').classList.add('text-primary', 'border-primary');
    }
}

// Auto-open modal on page load if flash message exists
@if(session('openModal'))
document.addEventListener('DOMContentLoaded', function() {
    openAuthModal('{{ session('openModal') }}');
});
@endif

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeAuthModal();
    }
});
</script>
