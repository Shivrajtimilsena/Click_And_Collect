@extends('app')

@section('title', 'Manage Withdrawals | Admin')

@section('content')
<div class="max-w-6xl mx-auto py-12">
    <h1 class="text-3xl font-headline font-bold text-on-surface mb-8">Manage Withdrawals</h1>

    <div class="bg-surface-container-lowest border border-surface-container-high overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">ID</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Trader</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Amount</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">PayPal</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Date</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container-low">
                    @forelse($withdrawals as $w)
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="px-6 py-4 text-sm font-mono">#{{ $w->withdrawal_id }}</td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold">{{ $w->trader?->user?->full_name ?? 'Unknown' }}</p>
                            <p class="text-xs text-secondary">{{ $w->trader?->shops()->first()?->shop_name ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm font-bold">&pound;{{ number_format($w->amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-secondary max-w-[200px] truncate">{{ $w->paypal_email }}</td>
                        <td class="px-6 py-4 text-sm text-secondary">{{ $w->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            @php
                                $colors = ['PENDING' => 'bg-zinc-100 text-zinc-500', 'APPROVED' => 'bg-blue-100 text-blue-700', 'COMPLETED' => 'bg-green-100 text-green-700', 'REJECTED' => 'bg-red-100 text-red-700', 'FAILED' => 'bg-red-100 text-red-700'];
                                $color = $colors[$w->status] ?? 'bg-zinc-100 text-zinc-500';
                            @endphp
                            <span class="px-3 py-1 text-[10px] font-bold uppercase {{ $color }}">{{ $w->status }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($w->status === 'PENDING')
                            <div class="flex gap-2">
                                <button onclick="openApproveModal({{ $w->withdrawal_id }}, '{{ $w->trader?->user?->full_name ?? 'Trader' }}', {{ $w->amount }})"
                                    class="bg-green-600 text-white px-3 py-1.5 text-xs font-bold hover:opacity-90 transition-all">
                                    Approve
                                </button>
                                <button onclick="openRejectModal({{ $w->withdrawal_id }})"
                                    class="bg-error text-on-error px-3 py-1.5 text-xs font-bold hover:opacity-90 transition-all">
                                    Reject
                                </button>
                            </div>
                            @else
                            <span class="text-xs text-secondary">{{ $w->processed_at?->format('d M Y') ?? '-' }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-secondary">No withdrawal requests.</td>
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

<!-- Approve Confirmation Modal -->
<div id="approve-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white max-w-md w-full mx-4 p-8">
        <h3 class="text-xl font-headline font-bold mb-2">Approve Withdrawal</h3>
        <p class="text-sm text-secondary mb-6">This will send a PayPal payout to the trader. Continue?</p>
        <div class="bg-surface-container-low p-4 mb-6 text-sm space-y-2">
            <div class="flex justify-between"><span class="text-secondary">Trader:</span><span class="font-bold" id="approve-trader-name"></span></div>
            <div class="flex justify-between"><span class="text-secondary">Amount:</span><span class="font-bold" id="approve-amount"></span></div>
        </div>
        <form id="approve-form" method="POST" class="flex gap-3 justify-end">
            @csrf
            <button type="button" onclick="closeModal('approve-modal')" class="border border-surface-container-high px-6 py-2.5 text-sm font-bold text-secondary hover:bg-surface-container-low">Cancel</button>
            <button type="submit" class="bg-green-600 text-white px-6 py-2.5 text-sm font-bold hover:opacity-90">Confirm & Send Payout</button>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="reject-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white max-w-md w-full mx-4 p-8">
        <h3 class="text-xl font-headline font-bold mb-2">Reject Withdrawal</h3>
        <p class="text-sm text-secondary mb-6">Provide a reason for rejecting this withdrawal request.</p>
        <form id="reject-form" method="POST" class="space-y-4">
            @csrf
            <textarea name="admin_notes" rows="3" class="w-full border border-surface-container-high bg-surface px-4 py-3 text-sm" placeholder="Reason for rejection..." required></textarea>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeModal('reject-modal')" class="border border-surface-container-high px-6 py-2.5 text-sm font-bold text-secondary hover:bg-surface-container-low">Cancel</button>
                <button type="submit" class="bg-error text-on-error px-6 py-2.5 text-sm font-bold hover:opacity-90">Confirm Rejection</button>
            </div>
        </form>
    </div>
</div>

<script>
function openApproveModal(id, name, amount) {
    document.getElementById('approve-trader-name').textContent = name;
    document.getElementById('approve-amount').textContent = '£' + amount.toFixed(2);
    document.getElementById('approve-form').action = '{{ route("admin.withdrawals.approve", "") }}/' + id;
    document.getElementById('approve-modal').classList.remove('hidden');
    document.getElementById('approve-modal').classList.add('flex');
}

function openRejectModal(id) {
    document.getElementById('reject-form').action = '{{ route("admin.withdrawals.reject", "") }}/' + id;
    document.getElementById('reject-modal').classList.remove('hidden');
    document.getElementById('reject-modal').classList.add('flex');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.getElementById(id).classList.remove('flex');
}

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('bg-black/50')) {
        e.target.classList.add('hidden');
        e.target.classList.remove('flex');
    }
});
</script>
@endsection
