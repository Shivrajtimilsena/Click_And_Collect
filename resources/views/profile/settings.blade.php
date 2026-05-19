@extends('layouts.customer')

@section('title', 'Settings | Click&Collect')

@section('header-title', 'Settings')

@section('content')
<div class="max-w-2xl space-y-6 md:space-y-8">
    <div class="bg-surface-container-lowest p-5 md:p-8 border border-surface-container-high">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 md:gap-6 text-center sm:text-left">
            <div class="w-20 h-20 rounded-full overflow-hidden bg-surface-container-high shrink-0">
                @if(Auth::user()->avatar_url)
                    <img class="w-full h-full object-cover" src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->full_name }}"/>
                @else
                    <div class="w-full h-full bg-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-on-primary text-3xl">account_circle</span>
                    </div>
                @endif
            </div>
            <div>
                <h2 class="text-xl font-headline font-extrabold text-on-surface">{{ Auth::user()->full_name ?? 'Customer' }}</h2>
                <p class="text-sm text-secondary mt-0.5">Member since {{ Auth::user()->created_at?->format('M Y') ?? 'Recently' }}</p>
                <button onclick="openEditProfileModal()" class="mt-3 inline-block bg-on-background text-surface px-5 py-2 text-xs font-bold uppercase tracking-wider hover:opacity-90 transition-opacity cursor-pointer">
                    Edit Profile
                </button>
            </div>
        </div>
    </div>

    <div class="bg-surface-container-lowest border border-surface-container-high p-5 md:p-8">
        <div class="flex items-center justify-between mb-5 md:mb-6">
            <h3 class="text-base md:text-lg font-bold font-headline">Change Password</h3>
            <span class="material-symbols-outlined text-secondary">lock</span>
        </div>

        <form method="POST" action="{{ route('profile.change-password') }}" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <label class="block text-[10px] font-bold uppercase tracking-widest text-secondary">Current Password</label>
                <input type="password" name="current_password" placeholder="Enter current password"
                       class="w-full px-4 py-3 bg-surface-container-low border border-surface-container-low text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-outline/50" required/>
                @error('current_password')
                    <p class="text-error text-xs">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-[10px] font-bold uppercase tracking-widest text-secondary">New Password</label>
                <input type="password" name="new_password" placeholder="Enter new password"
                       class="w-full px-4 py-3 bg-surface-container-low border border-surface-container-low text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-outline/50" required/>
                @error('new_password')
                    <p class="text-error text-xs">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-[10px] font-bold uppercase tracking-widest text-secondary">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" placeholder="Confirm new password"
                       class="w-full px-4 py-3 bg-surface-container-low border border-surface-container-low text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-outline/50" required/>
            </div>

            <button type="submit" class="w-full py-3 bg-primary text-on-primary text-xs font-bold uppercase tracking-wider hover:opacity-90 transition-opacity">
                Update Password
            </button>
        </form>
    </div>
</div>
@stop

@include('modals.edit-profile-modal')
