<!-- Login Modal -->
<div id="loginModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-200 px-8 py-6 flex justify-between items-center">
            <h2 class="font-headline text-2xl font-bold text-on-background">Sign In</h2>
            <button id="closeLoginModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                <span class="material-symbols-outlined" data-icon="close">close</span>
            </button>
        </div>

        <!-- Modal Content -->
        <form id="loginForm" action="{{ route('login') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <!-- Display Validation Errors -->
            <div id="loginErrors" class="hidden p-4 rounded-lg bg-error-container/20 border border-error/30">
                <ul id="loginErrorsList" class="space-y-1"></ul>
            </div>

            <!-- Email Field -->
            <div class="space-y-2">
                <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-on-surface-variant ml-1" for="modalEmail">
                    Email Address
                </label>
                <div class="relative">
                    <input
                        class="w-full px-6 py-4 bg-surface-container-high rounded-md border-none focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all duration-300 placeholder:text-outline/50 font-medium"
                        id="modalEmail"
                        name="email"
                        type="email"
                        placeholder="name@example.com"
                        required
                        autofocus
                    />
                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline/40" data-icon="mail">mail</span>
                </div>
            </div>

            <!-- Password Field -->
            <div class="space-y-2">
                <div class="flex justify-between items-end ml-1">
                    <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-on-surface-variant" for="modalPassword">
                        Password
                    </label>
                    <a class="text-[10px] font-bold uppercase tracking-widest text-primary hover:text-primary-dim transition-colors" href="#">
                        Forgot Password?
                    </a>
                </div>
                <div class="relative">
                    <input
                        class="w-full px-6 py-4 bg-surface-container-high rounded-md border-none focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all duration-300 placeholder:text-outline/50 font-medium"
                        id="modalPassword"
                        name="password"
                        type="password"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                    />
                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline/40" data-icon="lock">lock</span>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center space-x-3 py-2">
                <input
                    class="w-5 h-5 rounded border-none bg-surface-container-high text-primary focus:ring-primary/20"
                    id="modalRemember"
                    name="remember"
                    type="checkbox"
                />
                <label class="text-sm text-on-surface-variant font-medium select-none" for="modalRemember">
                    Remember me for 30 days
                </label>
            </div>

            <!-- Primary Action -->
            <button
                class="w-full py-5 rounded-full bg-gradient-to-r from-primary to-primary-fixed text-on-primary font-bold text-lg shadow-[0_10px_30px_rgba(177,34,9,0.15)] hover:shadow-[0_15px_35px_rgba(177,34,9,0.25)] active:scale-[0.98] transition-all duration-300 flex items-center justify-center group disabled:opacity-50"
                type="submit"
            >
                Sign In to Account
                <span class="material-symbols-outlined ml-2 group-hover:translate-x-1 transition-transform" data-icon="arrow_forward">arrow_forward</span>
            </button>

            <!-- Divider -->
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full h-[1px] bg-surface-container-high"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="px-4 bg-white text-[10px] font-bold uppercase tracking-[0.2em] text-outline">or continue with</span>
                </div>
            </div>

            <!-- Secondary Actions (Social) -->
            <div class="grid grid-cols-2 gap-4">
                <button class="flex items-center justify-center px-4 py-4 rounded-full bg-surface-container hover:bg-surface-container-highest transition-colors duration-200" type="button">
                    <img alt="Google" class="w-5 h-5 mr-3" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC27Arm-U0gT-2VWOgrhGcU37dT2x46Hcl73iT8ICuEXw9zUBxwxBWXhvu_u0BBtS9kn1T2lKywG6Tttq_JJXvNFeL-bG2DoO_zswduGACJcCpkInyPZatBfQHpRJZMgWY0pldBSuo686nOGs5nHKdeJLT7goYxQ7CCxDzQJzn-TdSYZlbBFCz2x7dhCCOF2axpUqNAlmPMzbbYm4p9nKtIFttK61I-3KQev20dOMk3DdFCs_7B-62ZsIBpyzkIZdmoSok_Lrfb9A"/>
                    <span class="text-sm font-bold text-on-surface">Google</span>
                </button>
                <button class="flex items-center justify-center px-4 py-4 rounded-full bg-surface-container hover:bg-surface-container-highest transition-colors duration-200" type="button">
                    <span class="material-symbols-outlined mr-3 text-on-surface" data-icon="apple" style="font-variation-settings: 'FILL' 1;">ios</span>
                    <span class="text-sm font-bold text-on-surface">Apple</span>
                </button>
            </div>
        </form>

        <!-- Footer -->
        <div class="border-t border-gray-200 px-8 py-6 bg-surface text-center">
            <p class="text-on-surface-variant font-medium text-sm">
                Don't have an account?
                <button type="button" onclick="closeLoginModal(); openSignupModal();" class="text-primary font-bold hover:underline decoration-2 underline-offset-4">
                    Sign up for free
                </button>
            </p>
        </div>
    </div>
</div>

<script>
function openLoginModal() {
    document.getElementById('loginModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeLoginModal() {
    document.getElementById('loginModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.getElementById('closeLoginModal')?.addEventListener('click', closeLoginModal);
document.getElementById('loginModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeLoginModal();
});

// Handle form submission
document.getElementById('loginForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    // Clear previous errors
    document.getElementById('loginErrors').classList.add('hidden');
    
    const formData = new FormData(this);
    fetch('{{ route("login") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => {
        if (response.redirected) {
            window.location.href = response.url;
        } else if (!response.ok) {
            return response.text().then(text => {
                throw new Error(text);
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const errorDiv = document.getElementById('loginErrors');
        const errorsList = document.getElementById('loginErrorsList');
        errorsList.innerHTML = '<li class="text-sm text-error font-medium">Invalid email or password</li>';
        errorDiv.classList.remove('hidden');
    });
});
</script>
