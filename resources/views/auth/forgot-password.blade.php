@extends('layouts.auth')

@section('title', 'Reset Password | Click&Collect')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-surface p-6">
    <div class="bg-surface-container-lowest rounded-2xl shadow-2xl w-full max-w-md p-10">
        <div class="mb-8">
            <a href="{{ route('home') }}" class="font-headline text-2xl font-black text-on-background tracking-tighter">Click and Collect.</a>
        </div>

        <header class="mb-8">
            <h2 class="font-headline text-3xl font-bold text-on-background">Reset Password</h2>
            <p class="text-on-surface-variant mt-2 text-sm">
                @if(session('code_sent'))
                    Enter the verification code sent to your email, then set a new password.
                @else
                    Enter your email address to receive a verification code.
                @endif
            </p>
        </header>

        @if(session('code_sent'))
            {{-- Step 2: Code + New Password --}}
            <form method="POST" action="{{ route('password.verify-reset') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="email" value="{{ session('email') }}">

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-on-surface-variant ml-1">Verification Code</label>
                    <div class="relative">
                        <input class="w-full px-6 py-4 bg-surface-container-high rounded-lg border-2 border-transparent focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest focus:border-primary transition-all duration-300 placeholder:text-outline/50 font-medium text-center text-2xl tracking-[0.3em]"
                               name="verification_code"
                               type="text"
                               maxlength="6"
                               placeholder="000000"
                               value="{{ old('verification_code') }}"
                               required/>
                    </div>
                    @error('verification_code')
                        <p class="text-error text-sm ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-on-surface-variant ml-1">New Password</label>
                    <input class="w-full px-6 py-4 bg-surface-container-high rounded-lg border-2 border-transparent focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest focus:border-primary transition-all duration-300 placeholder:text-outline/50 font-medium"
                           name="password"
                           type="password"
                           placeholder="••••••••"
                           required/>
                    @error('password')
                        <p class="text-error text-sm ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-on-surface-variant ml-1">Confirm New Password</label>
                    <input class="w-full px-6 py-4 bg-surface-container-high rounded-lg border-2 border-transparent focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest focus:border-primary transition-all duration-300 placeholder:text-outline/50 font-medium"
                           name="password_confirmation"
                           type="password"
                           placeholder="••••••••"
                           required/>
                </div>

                @error('email')
                    <p class="text-error text-sm">{{ $message }}</p>
                @enderror

                <button type="submit" class="w-full py-5 rounded-full bg-gradient-to-r from-primary to-primary-fixed text-on-primary font-bold text-lg shadow-[0_10px_30px_rgba(177,34,9,0.15)] hover:shadow-[0_15px_35px_rgba(177,34,9,0.25)] active:scale-[0.98] transition-all duration-300 flex items-center justify-center group">
                    Reset Password
                    <span class="material-symbols-outlined ml-2 group-hover:translate-x-1 transition-transform">lock_reset</span>
                </button>
            </form>
        @else
            {{-- Step 1: Email Only --}}
            <form method="POST" action="{{ route('password.send-code') }}" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-on-surface-variant ml-1">Email Address</label>
                    <div class="relative">
                        <input class="w-full px-6 py-4 bg-surface-container-high rounded-lg border-2 border-transparent focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest focus:border-primary transition-all duration-300 placeholder:text-outline/50 font-medium"
                               name="email"
                               type="email"
                               placeholder="name@example.com"
                               value="{{ old('email') }}"
                               required/>
                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline/40">mail</span>
                    </div>
                    @error('email')
                        <p class="text-error text-sm ml-1">{{ $message }}</p>
                    @enderror
                </div>

                @if (session('status'))
                    <div class="p-4 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg">
                        {{ session('status') }}
                    </div>
                @endif

                <button type="submit" class="w-full py-5 rounded-full bg-gradient-to-r from-primary to-primary-fixed text-on-primary font-bold text-lg shadow-[0_10px_30px_rgba(177,34,9,0.15)] hover:shadow-[0_15px_35px_rgba(177,34,9,0.25)] active:scale-[0.98] transition-all duration-300 flex items-center justify-center group">
                    Send Code
                    <span class="material-symbols-outlined ml-2 group-hover:translate-x-1 transition-transform">send</span>
                </button>
            </form>
        @endif

        <div class="mt-6 text-center">
            <p class="text-sm text-on-surface-variant">
                Remember your password?
                <a href="{{ route('signin') }}" class="text-primary font-bold hover:underline">Sign In</a>
            </p>
        </div>
    </div>
</div>
@endsection
