@extends('app')

@section('title', 'Review Application | Click&Collect')

@section('content')
<div class="max-w-3xl mx-auto py-12">
    <div class="mb-6">
        <a href="{{ route('admin.applications') }}" class="text-sm text-primary font-bold hover:underline">&larr; Back to Applications</a>
    </div>

    <div class="bg-surface-container-lowest border border-surface-container-high p-8 mb-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-headline font-bold text-on-surface">{{ $application->shop_name }}</h1>
            <span class="text-sm px-3 py-1 bg-amber-100 text-amber-800 font-bold">{{ $application->status }}</span>
        </div>

        <div class="space-y-4">
            <div>
                <p class="text-xs uppercase tracking-wider text-secondary font-bold">Email</p>
                <p class="text-on-surface">{{ $application->email }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wider text-secondary font-bold">Location</p>
                <p class="text-on-surface">{{ $application->location }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wider text-secondary font-bold">Speciality</p>
                <p class="text-on-surface">{{ $application->speciality }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wider text-secondary font-bold">Description</p>
                <p class="text-on-surface">{{ $application->description }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wider text-secondary font-bold">Applied</p>
                <p class="text-on-surface">{{ $application->created_at?->format('M d, Y g:i A') ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    @if ($application->status === 'PENDING')
        <div class="grid grid-cols-2 gap-4">
            <form action="{{ route('admin.application.approve', $application) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-green-600 text-white px-6 py-4 font-bold text-lg hover:bg-green-700 transition-all">
                    Approve Application
                </button>
            </form>

            <form action="{{ route('admin.application.reject', $application) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <textarea name="admin_notes" placeholder="Reason for rejection..." class="w-full bg-surface-container-high border-0 p-3 text-sm" rows="2" required></textarea>
                </div>
                <button type="submit" class="w-full bg-error text-on-error px-6 py-4 font-bold text-lg hover:opacity-90 transition-all">
                    Reject Application
                </button>
            </form>
        </div>
    @else
        <div class="bg-surface-container-high p-6">
            <p class="text-sm text-secondary">
                This application has been <strong>{{ $application->status }}</strong>
                @if ($application->reviewed_at)
                    on {{ $application->reviewed_at->format('M d, Y g:i A') }}.
                @endif
            </p>
            @if ($application->admin_notes)
                <p class="text-sm text-on-surface mt-2"><strong>Admin notes:</strong> {{ $application->admin_notes }}</p>
            @endif
        </div>
    @endif
</div>
@endsection
