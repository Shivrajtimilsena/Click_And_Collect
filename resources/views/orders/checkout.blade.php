@extends('app')

@section('title', 'Checkout | Click&Collect')

@section('content')
<div class="max-w-3xl mx-auto py-12">
    <h1 class="text-3xl font-headline font-bold text-on-surface mb-8">Checkout</h1>

    <form action="{{ route('orders.store') }}" method="POST" id="checkout-form" class="space-y-6">
        @csrf

        @if ($errors->any())
            <div class="bg-error/10 text-error p-4 border border-error/30">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-500/15 text-green-700 p-4 border border-green-500/30">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-surface-container-lowest border border-surface-container-high p-8">
            <div class="flex items-center gap-3 mb-6">
                <span class="material-symbols-outlined text-primary text-2xl">schedule</span>
                <div>
                    <h2 class="text-xl font-headline font-bold text-on-surface">Select Collection Slot</h2>
                    <p class="text-sm text-secondary">Collection available 24 hours after order placement</p>
                </div>
            </div>
            
            @if ($collectionSlots->isEmpty())
                <div class="bg-orange-50 border border-orange-200 p-4 text-center">
                    <p class="text-on-surface-variant">No collection slots available. Please check back later.</p>
                </div>
            @else
                <h3 class="text-lg font-bold text-on-surface mb-4">Select Day</h3>
                <div class="grid grid-cols-3 gap-4 mb-8">
                    <button type="button" class="day-selector px-6 py-4 border-2 border-surface-container-high font-bold text-center transition-all hover:border-primary" data-day="Wednesday">
                        Wednesday
                    </button>
                    <button type="button" class="day-selector px-6 py-4 border-2 border-surface-container-high font-bold text-center transition-all hover:border-primary" data-day="Thursday">
                        Thursday
                    </button>
                    <button type="button" class="day-selector px-6 py-4 border-2 border-surface-container-high font-bold text-center transition-all hover:border-primary" data-day="Friday">
                        Friday
                    </button>
                </div>

                <h3 class="text-lg font-bold text-on-surface mb-4">Select Time Slot</h3>
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <button type="button" class="time-selector px-6 py-4 border-2 border-surface-container-high font-bold text-center transition-all hover:border-primary" data-time="10:00">
                        10:00 - 13:00
                    </button>
                    <button type="button" class="time-selector px-6 py-4 border-2 border-surface-container-high font-bold text-center transition-all hover:border-primary" data-time="13:00">
                        13:00 - 16:00
                    </button>
                    <button type="button" class="time-selector px-6 py-4 border-2 border-surface-container-high font-bold text-center transition-all hover:border-primary" data-time="16:00">
                        16:00 - 19:00
                    </button>
                </div>

                <select name="collection_slot_id" id="collection_slot_id" required class="hidden">
                    <option value="">-- Select collection slot --</option>
                    @foreach ($collectionSlots as $shopId => $slots)
                        @foreach ($slots as $slot)
                            @php
                                $day = \Carbon\Carbon::parse($slot->slot_date)->format('l');
                                $time = $slot->start_time;
                                $available = $slot->capacity - $slot->total_order;
                            @endphp
                            <option value="{{ $slot->collection_slot_id }}" data-day="{{ $day }}" data-time="{{ $time }}" data-available="{{ $available }}">
                                {{ $day }}, {{ $slot->slot_date->format('M d') }} - {{ $slot->slot_label }} ({{ $available }} left)
                            </option>
                        @endforeach
                    @endforeach
                </select>

                <div id="selection-display" class="p-4 bg-primary/10 border-l-4 border-primary hidden">
                    <p class="text-sm">
                        <span class="font-bold text-primary">Selected:</span>
                        <span id="selected-text" class="text-on-surface">-</span>
                    </p>
                </div>
            @endif
        </div>

        <div class="bg-surface-container-lowest border border-surface-container-high p-8">
            <h3 class="text-lg font-bold text-on-surface mb-4">Promo Code (Optional)</h3>
            <div class="flex gap-4">
                <input type="text" name="coupon_code" placeholder="Enter coupon code" class="flex-1 px-4 py-3 bg-surface-container-high border border-surface-container-low focus:ring-2 focus:ring-primary/20"/>
                <button type="button" class="px-6 py-3 bg-surface-container-high text-secondary font-bold hover:bg-surface-container transition-all">Apply</button>
            </div>
        </div>

        <div class="space-y-3">
            <button type="submit" id="complete-order-btn" disabled class="w-full bg-surface-container-high text-on-surface px-6 py-4 font-bold text-lg cursor-not-allowed transition-all">
                Complete Order
            </button>
            <p id="slot-message" class="text-center text-sm text-secondary">Select a day and time slot to continue</p>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dayButtons = document.querySelectorAll('.day-selector');
    const timeButtons = document.querySelectorAll('.time-selector');
    const selectElement = document.getElementById('collection_slot_id');
    const displayDiv = document.getElementById('selection-display');
    const submitBtn = document.getElementById('complete-order-btn');
    const message = document.getElementById('slot-message');

    let selectedDay = null;
    let selectedTime = null;

    dayButtons.forEach(button => {
        button.addEventListener('click', function() {
            dayButtons.forEach(b => b.classList.remove('bg-primary/10', 'border-primary'));
            this.classList.add('bg-primary/10', 'border-primary');
            selectedDay = this.dataset.day;
            checkSelection();
        });
    });

    timeButtons.forEach(button => {
        button.addEventListener('click', function() {
            timeButtons.forEach(b => b.classList.remove('bg-primary/10', 'border-primary'));
            this.classList.add('bg-primary/10', 'border-primary');
            selectedTime = this.dataset.time;
            checkSelection();
        });
    });

    function checkSelection() {
        if (!selectedDay || !selectedTime) {
            submitBtn.disabled = true;
            submitBtn.classList.add('bg-surface-container-high', 'text-on-surface', 'cursor-not-allowed');
            submitBtn.classList.remove('bg-primary', 'text-on-primary');
            return;
        }

        const options = selectElement.querySelectorAll('option[value]');
        let found = false;
        
        for (let option of options) {
            if (option.dataset.day === selectedDay && option.dataset.time === selectedTime) {
                selectElement.value = option.value;
                document.getElementById('selected-text').textContent = option.textContent;
                displayDiv.classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('bg-surface-container-high', 'text-on-surface', 'cursor-not-allowed');
                submitBtn.classList.add('bg-primary', 'text-on-primary');
                message.classList.add('hidden');
                found = true;
                break;
            }
        }

        if (!found) {
            selectElement.value = '';
            submitBtn.disabled = true;
            submitBtn.classList.add('bg-surface-container-high', 'text-on-surface', 'cursor-not-allowed');
            submitBtn.classList.remove('bg-primary', 'text-on-primary');
            message.classList.remove('hidden');
            message.textContent = 'No slot available for this selection. Please try another combination.';
        }
    }

    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        if (!selectElement.value) {
            e.preventDefault();
            alert('Please select a collection slot.');
        }
    });
});
</script>
@endsection
