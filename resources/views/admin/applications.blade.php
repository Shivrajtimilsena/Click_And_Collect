@extends('app')

@section('title', 'Trader Applications | Click&Collect')

@section('content')
<div class="max-w-6xl mx-auto py-12">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-headline font-bold text-on-surface">Trader Applications</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-primary font-bold hover:underline">&larr; Back to Dashboard</a>
    </div>

    @if ($applications->isEmpty())
        <div class="bg-surface-container-lowest border border-surface-container-high p-12 text-center">
            <span class="material-symbols-outlined text-5xl text-secondary">inbox</span>
            <p class="text-secondary mt-4">No applications yet.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($applications as $application)
                <div class="bg-surface-container-lowest border border-surface-container-high p-6 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-on-surface text-lg">{{ $application->shop_name }}</h3>
                        <p class="text-sm text-secondary">{{ $application->email }} &middot; {{ $application->location }}</p>
                        <p class="text-xs text-secondary mt-1">Applied {{ $application->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        @if ($application->status === 'PENDING')
                            <span class="text-sm px-3 py-1 bg-amber-100 text-amber-800 font-bold">PENDING</span>
                            <a href="{{ route('admin.application.show', $application) }}" class="bg-primary text-on-primary px-4 py-2 text-sm font-bold hover:opacity-90 transition-all">Review</a>
                        @elseif ($application->status === 'APPROVED')
                            <span class="text-sm px-3 py-1 bg-green-100 text-green-800 font-bold">APPROVED</span>
                        @else
                            <span class="text-sm px-3 py-1 bg-red-100 text-red-800 font-bold">REJECTED</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
