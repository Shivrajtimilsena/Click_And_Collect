@extends('layouts.trader')

@section('title', 'Withdrawal History | Click&Collect')

@section('header-title', 'Finance / Withdrawal History')

@section('content')
<div class="max-w-4xl">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-surface-container-lowest border border-surface-container-high p-6">
            <p class="text-xs font-bold text-secondary uppercase tracking-widest">Total Revenue</p>
            <p class="text-2xl font-headline font-extrabold text-on-surface mt-1">&pound;{{ number_format($totalRevenue, 2) }}</p>
        </div>
        <div class="bg-surface-container-lowest border border-surface-container-high p-6">
            <p class="text-xs font-bold text-secondary uppercase tracking-widest">Withdrawn</p>
            <p class="text-2xl font-headline font-extrabold text-on-surface mt-1">&pound;{{ number_format($totalWithdrawn, 2) }}</p>
        </div>
        <div class="bg-surface-container-lowest border border-surface-container-high p-6">
            <p class="text-xs font-bold text-secondary uppercase tracking-widest">Available</p>
            <p class="text-2xl font-headline font-extrabold text-primary mt-1">&pound;{{ number_format($availableBalance, 2) }}</p>
        </div>
    </div>

    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold font-headline">Withdrawal Requests</h3>
        <a href="{{ route('trader.withdraw.form') }}" class="bg-primary text-on-primary px-6 py-2.5 font-bold text-sm hover:opacity-90 transition-all">
            New Withdrawal
        </a>
    </div>

    <div class="bg-surface-container-lowest border border-surface-container-high overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low">
                    <tr>
                        <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Date</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Amount</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">PayPal Email</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Status</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container-low">
                    @forelse($withdrawals as $w)
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="px-8 py-4 text-sm text-secondary">{{ $w->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-8 py-4 text-sm font-bold">&pound;{{ number_format($w->amount, 2) }}</td>
                        <td class="px-8 py-4 text-sm text-secondary">{{ $w->paypal_email }}</td>
                        <td class="px-8 py-4">
                            @php
                                $colors = ['PENDING' => 'bg-zinc-100 text-zinc-500', 'APPROVED' => 'bg-blue-100 text-blue-700', 'COMPLETED' => 'bg-green-100 text-green-700', 'REJECTED' => 'bg-red-100 text-red-700', 'FAILED' => 'bg-red-100 text-red-700'];
                                $color = $colors[$w->status] ?? 'bg-zinc-100 text-zinc-500';
                            @endphp
                            <span class="px-3 py-1 text-[10px] font-bold uppercase {{ $color }}">{{ $w->status }}</span>
                        </td>
                        <td class="px-8 py-4 text-sm text-secondary">{{ $w->admin_notes ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center text-secondary">No withdrawal requests yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $withdrawals->links() }}
    </div>
</div>
@endsection
