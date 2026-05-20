<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Click&Collect | Local Shopping')</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
<body class="bg-surface text-on-background selection:bg-primary-container selection:text-on-primary-container" data-auth="{{ auth()->check() ? 'true' : 'false' }}" data-role="{{ auth()->check() ? auth()->user()->role : '' }}">
    @include('components.navbar')

    @hasSection('sidebar')
        <aside class="hidden lg:block fixed lg:top-24 left-0 w-64 bg-surface border-r border-surface-container-high p-4 z-40 overflow-y-auto" style="height: calc(100vh - 6rem);">
            @yield('sidebar')
        </aside>
    @endif
    <main class="pt-24 pb-12 px-4 md:px-12 mx-auto @hasSection('trader-page') max-w-3xl @endif @hasSection('sidebar') lg:ml-64 @endif">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-error/10 text-error rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (request()->routeIs('home'))
            <div id="successToast" class="fixed top-24 right-4 md:right-8 z-[60] w-[280px] md:w-[320px] bg-white border border-primary/20 shadow-2xl rounded-xl px-4 py-3 flex items-start gap-3 hidden">
                <div class="w-8 h-8 rounded-full bg-primary/15 text-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-lg">check</span>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-on-surface" id="successToastTitle">Product added to cart!</p>
                    <p class="text-xs text-secondary mt-1" id="successToastBody">Your item is ready in the cart.</p>
                </div>
                <button type="button" onclick="hideSuccessToast()" class="text-zinc-400 hover:text-zinc-600 font-bold text-lg leading-none">×</button>
            </div>
            <div id="errorToast" class="fixed top-24 right-4 md:right-8 z-[60] w-[280px] md:w-[320px] bg-white border border-error/30 shadow-2xl rounded-xl px-4 py-3 flex items-start gap-3 hidden">
                <div class="w-8 h-8 rounded-full bg-error/15 text-error flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-lg">error</span>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-on-surface" id="errorToastTitle">You cannot buy products.</p>
                    <p class="text-xs text-secondary mt-1" id="errorToastBody">Traders are not allowed to place orders.</p>
                </div>
                <button type="button" onclick="hideErrorToast()" class="text-zinc-400 hover:text-zinc-600 font-bold text-lg leading-none">×</button>
            </div>
            <div id="warningToast" class="fixed top-24 right-4 md:right-8 z-[60] w-[280px] md:w-[320px] bg-white border border-yellow-500/30 shadow-2xl rounded-xl px-4 py-3 flex items-start gap-3 hidden">
                <div class="w-8 h-8 rounded-full bg-yellow-500/15 text-yellow-700 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-lg">warning</span>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-on-surface" id="warningToastTitle">Warning</p>
                    <p class="text-xs text-secondary mt-1" id="warningToastBody">Please review the details.</p>
                </div>
                <button type="button" onclick="hideWarningToast()" class="text-zinc-400 hover:text-zinc-600 font-bold text-lg leading-none">×</button>
            </div>
            <div id="infoToast" class="fixed top-24 right-4 md:right-8 z-[60] w-[280px] md:w-[320px] bg-white border border-blue-500/30 shadow-2xl rounded-xl px-4 py-3 flex items-start gap-3 hidden">
                <div class="w-8 h-8 rounded-full bg-blue-500/15 text-blue-700 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-lg">info</span>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-on-surface" id="infoToastTitle">Info</p>
                    <p class="text-xs text-secondary mt-1" id="infoToastBody">Here is an update.</p>
                </div>
                <button type="button" onclick="hideInfoToast()" class="text-zinc-400 hover:text-zinc-600 font-bold text-lg leading-none">×</button>
            </div>
        @else
            @if (session('success'))
                <div id="successNotification" class="fixed top-24 right-4 md:right-8 z-[60] w-[280px] md:w-[320px] bg-white border border-primary/20 shadow-2xl rounded-xl px-4 py-3 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-primary/15 text-primary flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-lg">check</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-on-surface">{{ session('success') }}</p>
                        <p class="text-xs text-secondary mt-1">Action completed successfully.</p>
                    </div>
                    <button onclick="document.getElementById('successNotification').style.display='none'" class="text-zinc-400 hover:text-zinc-600 font-bold text-lg leading-none">×</button>
                </div>
                <script>
                    setTimeout(function () {
                        var toast = document.getElementById('successNotification');
                        if (toast) {
                            toast.style.display = 'none';
                        }
                    }, 2400);
                </script>
            @endif

            @if (session('error'))
                <div id="errorNotification" class="fixed top-24 right-4 md:right-8 z-[60] w-[280px] md:w-[320px] bg-white border border-error/30 shadow-2xl rounded-xl px-4 py-3 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-error/15 text-error flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-lg">error</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-on-surface">{{ session('error') }}</p>
                        <p class="text-xs text-secondary mt-1">Please try again.</p>
                    </div>
                    <button onclick="document.getElementById('errorNotification').style.display='none'" class="text-zinc-400 hover:text-zinc-600 font-bold text-lg leading-none">×</button>
                </div>
                <script>
                    setTimeout(function () {
                        var toast = document.getElementById('errorNotification');
                        if (toast) {
                            toast.style.display = 'none';
                        }
                    }, 2600);
                </script>
            @endif

            @if (session('warning'))
                <div id="warningNotification" class="fixed top-24 right-4 md:right-8 z-[60] w-[280px] md:w-[320px] bg-white border border-yellow-500/30 shadow-2xl rounded-xl px-4 py-3 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-yellow-500/15 text-yellow-700 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-lg">warning</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-on-surface">{{ session('warning') }}</p>
                        <p class="text-xs text-secondary mt-1">Please review the details.</p>
                    </div>
                    <button onclick="document.getElementById('warningNotification').style.display='none'" class="text-zinc-400 hover:text-zinc-600 font-bold text-lg leading-none">×</button>
                </div>
                <script>
                    setTimeout(function () {
                        var toast = document.getElementById('warningNotification');
                        if (toast) {
                            toast.style.display = 'none';
                        }
                    }, 2600);
                </script>
            @endif

            @if (session('info'))
                <div id="infoNotification" class="fixed top-24 right-4 md:right-8 z-[60] w-[280px] md:w-[320px] bg-white border border-blue-500/30 shadow-2xl rounded-xl px-4 py-3 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-500/15 text-blue-700 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-lg">info</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-on-surface">{{ session('info') }}</p>
                        <p class="text-xs text-secondary mt-1">Here is an update.</p>
                    </div>
                    <button onclick="document.getElementById('infoNotification').style.display='none'" class="text-zinc-400 hover:text-zinc-600 font-bold text-lg leading-none">×</button>
                </div>
                <script>
                    setTimeout(function () {
                        var toast = document.getElementById('infoNotification');
                        if (toast) {
                            toast.style.display = 'none';
                        }
                    }, 2600);
                </script>
            @endif
        @endif

        @yield('content')
    </main>

    @if (request()->routeIs('home'))
    <script>
        var toastTimer;
        var errorToastTimer;

        function showSuccessToast(title, body) {
            var toast = document.getElementById('successToast');
            if (!toast) return;
            var titleEl = document.getElementById('successToastTitle');
            var bodyEl = document.getElementById('successToastBody');
            if (titleEl) titleEl.textContent = title || 'Product added to cart!';
            if (bodyEl) bodyEl.textContent = body || 'Your item is ready in the cart.';
            toast.classList.remove('hidden');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(hideSuccessToast, 2200);
        }

        function hideSuccessToast() {
            var toast = document.getElementById('successToast');
            if (toast) {
                toast.classList.add('hidden');
            }
        }

        function showErrorToast(title, body) {
            var toast = document.getElementById('errorToast');
            if (!toast) return;
            var titleEl = document.getElementById('errorToastTitle');
            var bodyEl = document.getElementById('errorToastBody');
            if (titleEl) titleEl.textContent = title || 'You cannot buy products.';
            if (bodyEl) bodyEl.textContent = body || 'Traders are not allowed to place orders.';
            toast.classList.remove('hidden');
            clearTimeout(errorToastTimer);
            errorToastTimer = setTimeout(hideErrorToast, 2600);
        }

        function hideErrorToast() {
            var toast = document.getElementById('errorToast');
            if (toast) {
                toast.classList.add('hidden');
            }
        }

        function showWarningToast(title, body) {
            var toast = document.getElementById('warningToast');
            if (!toast) return;
            var titleEl = document.getElementById('warningToastTitle');
            var bodyEl = document.getElementById('warningToastBody');
            if (titleEl) titleEl.textContent = title || 'Warning';
            if (bodyEl) bodyEl.textContent = body || 'Please review the details.';
            toast.classList.remove('hidden');
            clearTimeout(errorToastTimer);
            errorToastTimer = setTimeout(hideWarningToast, 2600);
        }

        function hideWarningToast() {
            var toast = document.getElementById('warningToast');
            if (toast) {
                toast.classList.add('hidden');
            }
        }

        function showInfoToast(title, body) {
            var toast = document.getElementById('infoToast');
            if (!toast) return;
            var titleEl = document.getElementById('infoToastTitle');
            var bodyEl = document.getElementById('infoToastBody');
            if (titleEl) titleEl.textContent = title || 'Info';
            if (bodyEl) bodyEl.textContent = body || 'Here is an update.';
            toast.classList.remove('hidden');
            clearTimeout(errorToastTimer);
            errorToastTimer = setTimeout(hideInfoToast, 2600);
        }

        function hideInfoToast() {
            var toast = document.getElementById('infoToast');
            if (toast) {
                toast.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                showSuccessToast('{{ session('success') }}', 'Action completed successfully.');
            @endif
            @if (session('error'))
                showErrorToast('{{ session('error') }}', 'Please try again.');
            @endif
            @if (session('warning'))
                showWarningToast('{{ session('warning') }}', 'Please review the details.');
            @endif
            @if (session('info'))
                showInfoToast('{{ session('info') }}', 'Here is an update.');
            @endif

            var role = document.body.getAttribute('data-role');
            var isAuthed = document.body.getAttribute('data-auth') === 'true';
            document.querySelectorAll('form[action="{{ route('cart.add') }}"]').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    if (!isAuthed) {
                        showErrorToast('Please login first.', 'Sign in to add items to your cart.');
                        return;
                    }

                    if (role === 'TRADER') {
                        showErrorToast('You cannot buy products.', 'Traders are not allowed to place orders.');
                        return;
                    }

                    var formData = new FormData(form);
                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData,
                        credentials: 'same-origin',
                    }).then(function (response) {
                        if (response.ok) {
                            showSuccessToast('Product added to cart!', 'Your item is ready in the cart.');
                        } else {
                            showErrorToast('Could not add product', 'Please try again.');
                        }
                    }).catch(function () {
                        showErrorToast('Could not add product', 'Please try again.');
                    });
                });
            });
        });
    </script>
    @endif


    @include('modals.auth-modal')
    <div class="@hasSection('sidebar') lg:ml-64 @endif">
        @include('components.footer')
    </div>

    <script>
    function addToWishlist(productId, event) {
        const isAuth = document.body.dataset.auth === 'true';
        if (!isAuth) {
            if (typeof openAuthModal === 'function') {
                openAuthModal('signup');
            }
            return;
        }

        const btn = event.currentTarget;

        if (btn.dataset.wishlisted === 'true') {
            return;
        }

        const icon = btn.querySelector('.material-symbols-outlined');

        fetch('{{ route("wishlist.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ product_id: productId })
        }).then(response => {
            if (response.ok) {
                btn.dataset.wishlisted = 'true';
                icon.style.fontVariationSettings = "'FILL' 1, 'wght' 400";
                btn.classList.add('bg-error', 'text-white');
                btn.classList.remove('bg-white/80', 'text-error');
            }
        });
    }
    </script>
</body>
</html>
