@extends('app')

@section('content')
<main class="pt-32 pb-24 px-6 md:px-12 max-w-screen-2xl mx-auto">
    <div class="flex flex-col lg:flex-row gap-12 lg:items-start">
        <!-- Left Sidebar: Personal Info & Navigation (Persistent) -->
        <aside class="w-full lg:w-80 flex flex-col space-y-8">
            <!-- Profile Card -->
            <div class="bg-surface-container-lowest p-8 rounded-lg shadow-[0_10px_30px_rgba(45,47,47,0.04)] relative overflow-hidden">
                <!-- Subtle Gradient Accent -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-linear-to-br from-primary/10 to-transparent"></div>
                <div class="relative z-10">
                    <!-- Profile Avatar -->
                    <div class="w-24 h-24 rounded-full overflow-hidden mb-6">
                        <img 
                            alt="User avatar" 
                            class="w-full h-full object-cover" 
                            src="{{ Auth::user()->avatar_url ?? 'https://via.placeholder.com/96' }}"
                        />
                    </div>

                    <!-- Profile Info -->
                    <h2 class="font-headline text-2xl font-bold text-on-surface mb-1">
                        {{ Auth::user()->full_name }}
                    </h2>
                    <p class="text-on-surface-variant text-sm mb-6">
                        {{ Auth::user()->role === 'CUSTOMER' ? 'CUSTOMER' : Auth::user()->role }} since {{ Auth::user()->created_at->format('Y') }}
                    </p>

                    <!-- Contact Info -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-primary text-xl">mail</span>
                            <span class="text-sm">{{ Auth::user()->email }}</span>
                        </div>
                        @if(Auth::user()->city)
                        <div class="flex items-center space-x-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-primary text-xl">location_on</span>
                            <span class="text-sm">{{ Auth::user()->city }}, {{ Auth::user()->address }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Edit Profile Button -->
                    <button
                        onclick="openEditProfileModal()"
                        class="mt-8 w-full py-3 px-6 bg-on-background text-surface rounded-full font-bold text-sm tracking-wide hover:opacity-90 transition-opacity active:scale-95 inline-block text-center cursor-pointer"
                    >
                        Edit Profile
                    </button>
                </div>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="flex flex-col space-y-2">
                <a 
                    href="{{ route('profile.dashboard') }}"
                    class="flex items-center space-x-4 p-4 @if(request()->routeIs('profile.dashboard')) bg-white shadow-sm text-primary font-bold @else text-on-surface-variant hover:bg-surface-container-low @endif transition-colors group rounded-lg"
                >
                    <span class="material-symbols-outlined @if(request()->routeIs('profile.dashboard')) text-primary @else group-hover:text-primary @endif transition-colors" style="font-variation-settings: 'FILL' 1;">grid_view</span>
                    <span class="text-sm uppercase tracking-widest">Dashboard</span>
                </a>
                <a 
                    href="{{ route('profile.orders') }}"
                    class="flex items-center space-x-4 p-4 @if(request()->routeIs('profile.orders')) bg-white shadow-sm text-primary font-bold @else text-on-surface-variant hover:bg-surface-container-low @endif transition-colors group rounded-lg"
                >
                    <span class="material-symbols-outlined @if(request()->routeIs('profile.orders')) text-primary @else group-hover:text-primary @endif transition-colors">shopping_bag</span>
                    <span class="text-sm uppercase tracking-widest">My Orders</span>
                </a>
                <a 
                    href="{{ route('profile.shops') }}"
                    class="flex items-center space-x-4 p-4 @if(request()->routeIs('profile.shops')) bg-white shadow-sm text-primary font-bold @else text-on-surface-variant hover:bg-surface-container-low @endif transition-colors group rounded-lg"
                >
                    <span class="material-symbols-outlined @if(request()->routeIs('profile.shops')) text-primary @else group-hover:text-primary @endif transition-colors">favorite</span>
                    <span class="text-sm uppercase tracking-widest">Saved Shops</span>
                </a>
                <a 
                    href="{{ route('profile.settings') }}"
                    class="flex items-center space-x-4 p-4 @if(request()->routeIs('profile.settings')) bg-white shadow-sm text-primary font-bold @else text-on-surface-variant hover:bg-surface-container-low @endif transition-colors group rounded-lg"
                >
                    <span class="material-symbols-outlined @if(request()->routeIs('profile.settings')) text-primary @else group-hover:text-primary @endif transition-colors">settings</span>
                    <span class="text-sm uppercase tracking-widest">Settings</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <section class="flex-1 space-y-12">
            @yield('profile-content')
        </section>
    </div>
</main>
@endsection

@include('modals.edit-profile-modal')
