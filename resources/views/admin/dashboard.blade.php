@extends('app')

@section('title', 'Admin Dashboard | Click&Collect')

@section('content')
<div class="max-w-6xl mx-auto py-12">
    <h1 class="text-3xl font-headline font-bold text-on-surface mb-8">Admin Dashboard</h1>

    <div class="grid grid-cols-4 gap-6 mb-10">
        <div class="bg-surface-container-lowest border border-surface-container-high p-6">
            <p class="text-sm text-secondary uppercase tracking-wider">Pending</p>
            <p class="text-4xl font-headline font-extrabold text-primary mt-2">{{ $pendingCount }}</p>
        </div>
        <div class="bg-surface-container-lowest border border-surface-container-high p-6">
            <p class="text-sm text-secondary uppercase tracking-wider">Approved</p>
            <p class="text-4xl font-headline font-extrabold text-green-600 mt-2">{{ $approvedCount }}</p>
        </div>
        <div class="bg-surface-container-lowest border border-surface-container-high p-6">
            <p class="text-sm text-secondary uppercase tracking-wider">Rejected</p>
            <p class="text-4xl font-headline font-extrabold text-error mt-2">{{ $rejectedCount }}</p>
        </div>
        <div class="bg-surface-container-lowest border border-surface-container-high p-6 {{ $pendingWithdrawals > 0 ? 'ring-2 ring-primary' : '' }}">
            <p class="text-sm text-secondary uppercase tracking-wider">Pending Withdrawals</p>
            <p class="text-4xl font-headline font-extrabold {{ $pendingWithdrawals > 0 ? 'text-primary' : 'text-on-surface' }} mt-2">{{ $pendingWithdrawals }}</p>
        </div>
    </div>

    <div class="flex gap-4">
        <a href="{{ route('admin.applications') }}" class="inline-block bg-primary text-on-primary px-8 py-3 font-bold hover:opacity-90 transition-all">
            Manage Applications
        </a>
        <a href="{{ route('admin.withdrawals.index') }}" class="inline-block border border-primary text-primary px-8 py-3 font-bold hover:bg-primary hover:text-on-primary transition-all">
            Manage Withdrawals
        </a>
    </div>
</div>
@endsection
