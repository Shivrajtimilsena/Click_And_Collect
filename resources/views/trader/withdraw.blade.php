@extends('layouts.trader')

@section('title', 'Withdraw Funds | Click&Collect')

@section('header-title', 'Finance / Withdraw')

@section('content')
<div class="max-w-2xl">
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

    <div class="bg-surface-container-lowest border border-surface-container-high p-8">
        <h3 class="text-lg font-bold font-headline mb-2">Request Withdrawal</h3>
        <p class="text-sm text-secondary mb-6">Funds will be sent to your PayPal account after admin approval.</p>

        <form method="POST" action="{{ route('trader.withdraw.submit') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-bold text-on-surface mb-2">Amount (&pound;)</label>
                <input type="number" name="amount" id="amount" step="0.01" min="1" max="{{ $availableBalance }}"
                    class="w-full border border-surface-container-high bg-surface px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20"
                    placeholder="0.00" value="{{ old('amount') }}" required>
                <p class="text-xs text-secondary mt-1">Min: &pound;1.00 &middot; Max: &pound;{{ number_format($availableBalance, 2) }}</p>
                @error('amount')
                    <p class="text-xs text-error mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-on-surface mb-2">PayPal Email</label>
                <input type="email" name="paypal_email" id="paypal_email"
                    class="w-full border border-surface-container-high bg-surface px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20"
                    placeholder="your-paypal@example.com" value="{{ old('paypal_email') }}" required>
                @error('paypal_email')
                    <p class="text-xs text-error mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-primary text-on-primary px-8 py-3 font-bold text-sm hover:opacity-90 transition-all">
                    Submit Withdrawal Request
                </button>
                <a href="{{ route('trader.withdrawals.index') }}" class="border border-surface-container-high px-8 py-3 font-bold text-sm text-secondary hover:bg-surface-container-low transition-all">
                    View History
                </a>
            </div>
        </form>
    </div>

    @if($recentWithdrawals->isNotEmpty())
    <div class="bg-surface-container-lowest border border-surface-container-high mt-6">
        <div class="px-8 py-6 border-b border-surface-container-high">
            <h3 class="text-lg font-bold font-headline">Recent Withdrawal Requests</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low">
                    <tr>
                        <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Date</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Amount</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">PayPal</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container-low">
                    @foreach($recentWithdrawals as $w)
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="px-8 py-4 text-sm text-secondary">{{ $w->created_at->format('d M Y') }}</td>
                        <td class="px-8 py-4 text-sm font-bold">&pound;{{ number_format($w->amount, 2) }}</td>
                        <td class="px-8 py-4 text-sm text-secondary">{{ $w->paypal_email }}</td>
                        <td class="px-8 py-4">
                            @php
                                $colors = ['PENDING' => 'bg-zinc-100 text-zinc-500', 'APPROVED' => 'bg-blue-100 text-blue-700', 'COMPLETED' => 'bg-green-100 text-green-700', 'REJECTED' => 'bg-red-100 text-red-700', 'FAILED' => 'bg-red-100 text-red-700'];
                                $color = $colors[$w->status] ?? 'bg-zinc-100 text-zinc-500';
                            @endphp
                            <span class="px-3 py-1 text-[10px] font-bold uppercase {{ $color }}">{{ $w->status }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
