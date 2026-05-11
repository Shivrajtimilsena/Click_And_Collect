@extends('app')

@section('title', 'Checkout | Click&Collect')

@section('content')
<div class="max-w-3xl mx-auto py-12">
    <h1 class="text-3xl font-headline font-bold text-on-surface mb-8">Checkout</h1>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-error/10 text-error border border-error/30">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 bg-error/10 text-error border border-error/30">
            {{ session('error') }}
        </div>
    @endif

    <!-- Collection Slot Section -->
    <div class="bg-surface-container-lowest border border-surface-container-high p-8 mb-6">
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

    <!-- Order Summary -->
    <div class="bg-surface-container-lowest border border-surface-container-high p-8 mb-6">
        <h2 class="text-xl font-headline font-bold text-on-surface mb-6">Order Summary</h2>

        @php
            $combinedTotal = 0;
            $shopGroups = $cart->products->groupBy(fn($item) => $item->product->shop->shop_name ?? 'Unknown');
        @endphp

        @foreach($shopGroups as $shopName => $items)
            @php $shopTotal = $items->sum(fn($item) => $item->product->discounted_price * $item->quantity); $combinedTotal += $shopTotal; @endphp
            <div class="mb-4 pb-4 border-b border-surface-container-high last:border-b-0 last:pb-0 last:mb-0">
                <div class="flex items-center gap-2 mb-3">
                    <span class="material-symbols-outlined text-secondary text-lg">store</span>
                    <span class="font-bold text-on-surface">{{ $shopName }}</span>
                </div>
                <div class="space-y-2 ml-7">
                    @foreach($items as $item)
                        <div class="flex justify-between text-sm">
                            <span class="text-secondary">{{ $item->quantity }}x {{ $item->product->product_name }}</span>
                            <span class="text-on-surface">&pound;{{ number_format($item->product->discounted_price * $item->quantity, 2) }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-between text-sm font-bold mt-2 ml-7">
                    <span class="text-on-surface">Shop Total</span>
                    <span class="text-on-surface">&pound;{{ number_format($shopTotal, 2) }}</span>
                </div>
            </div>
        @endforeach

        <div class="flex justify-between items-center pt-4 border-t border-surface-container-high">
            <span class="font-headline font-bold text-xl text-on-surface">Total to Pay</span>
            <span class="font-headline font-extrabold text-2xl text-primary" id="checkout-total">&pound;{{ number_format($combinedTotal, 2) }}</span>
        </div>
    </div>

    <!-- PayPal Button -->
    <div class="bg-surface-container-lowest border border-surface-container-high p-8 mb-6">
        <div id="paypal-button-container" class="min-h-[50px]"></div>
        <p id="paypal-message" class="text-center text-sm text-secondary mt-3">Select a day and time slot to pay</p>
        <div id="paypal-loading" class="hidden text-center py-4">
            <span class="text-primary font-bold">Processing payment...</span>
        </div>
    </div>
</div>

<script src="https://www.paypal.com/sdk/js?client_id={{ config('paypal.client_id') }}&currency={{ config('paypal.currency') }}"
    data-namespace="paypal_sdk">
</script>

<script>
(function() {
    const dayButtons = document.querySelectorAll('.day-selector');
    const timeButtons = document.querySelectorAll('.time-selector');
    const selectElement = document.getElementById('collection_slot_id');
    const displayDiv = document.getElementById('selection-display');
    const message = document.getElementById('paypal-message');
    const paypalContainer = document.getElementById('paypal-button-container');
    const loading = document.getElementById('paypal-loading');

    let selectedDay = null;
    let selectedTime = null;
    let slotSelected = false;

    dayButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            dayButtons.forEach(function(b) { b.classList.remove('bg-primary/10', 'border-primary'); });
            this.classList.add('bg-primary/10', 'border-primary');
            selectedDay = this.dataset.day;
            checkSelection();
        });
    });

    timeButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            timeButtons.forEach(function(b) { b.classList.remove('bg-primary/10', 'border-primary'); });
            this.classList.add('bg-primary/10', 'border-primary');
            selectedTime = this.dataset.time;
            checkSelection();
        });
    });

    function checkSelection() {
        if (!selectedDay || !selectedTime) {
            slotSelected = false;
            message.textContent = 'Select a day and time slot to pay';
            message.classList.remove('hidden');
            return;
        }

        var found = false;
        var options = selectElement.querySelectorAll('option[value]');
        for (var i = 0; i < options.length; i++) {
            var option = options[i];
            if (option.dataset.day === selectedDay && option.dataset.time === selectedTime) {
                selectElement.value = option.value;
                document.getElementById('selected-text').textContent = option.textContent;
                displayDiv.classList.remove('hidden');
                message.classList.add('hidden');
                slotSelected = true;
                found = true;
                break;
            }
        }

        if (!found) {
            selectElement.value = '';
            slotSelected = false;
            message.classList.remove('hidden');
            message.textContent = 'No slot available for this selection. Please try another combination.';
        }
    }

    function initPayPalButtons() {
        if (typeof paypal_sdk === 'undefined') {
            setTimeout(initPayPalButtons, 300);
            return;
        }

        paypal_sdk.Buttons({
            createOrder: function() {
                if (!slotSelected || !selectElement.value) {
                    alert('Please select a collection slot first.');
                    return Promise.reject('No slot selected');
                }

                return fetch('{{ route('paypal.create') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then(function(res) {
                    if (!res.ok) {
                        return res.json().then(function(data) {
                            throw new Error(data.error || 'Failed to create order');
                        });
                    }
                    return res.json();
                }).then(function(data) {
                    return data.paypal_order_id;
                });
            },
            onApprove: function(data) {
                loading.classList.remove('hidden');
                paypalContainer.classList.add('hidden');

                fetch('{{ route('paypal.capture') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        paypal_order_id: data.orderID,
                        collection_slot_id: selectElement.value,
                    }),
                }).then(function(res) {
                    return res.json();
                }).then(function(result) {
                    if (result.redirect_url) {
                        window.location.href = result.redirect_url;
                    } else {
                        throw new Error(result.error || 'Payment failed');
                    }
                }).catch(function(err) {
                    loading.classList.add('hidden');
                    paypalContainer.classList.remove('hidden');
                    alert('Payment failed: ' + err.message);
                });
            },
            onCancel: function() {
                alert('Payment cancelled. You can try again when ready.');
            },
            onError: function() {
                alert('An error occurred with PayPal. Please try again.');
            },
        }).render('#paypal-button-container');
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPayPalButtons);
    } else {
        initPayPalButtons();
    }
})();
</script>
@endsection
