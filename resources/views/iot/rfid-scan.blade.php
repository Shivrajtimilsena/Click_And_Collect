<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RFID Collection Desk | Click&Collect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: Manrope, sans-serif; }
        h1, h2, h3 { font-family: "Plus Jakarta Sans", sans-serif; }
    </style>
</head>
<body class="bg-zinc-50 text-zinc-900">
    <main class="mx-auto max-w-6xl px-5 py-8 space-y-6">
        <header class="flex flex-col gap-3 border-b border-zinc-200 pb-6 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-red-700">Click&Collect IoT</p>
                <h1 class="mt-2 text-3xl font-extrabold">RFID Collection Desk</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-zinc-600">
                    Assign a card UID to an order, mark the order READY, then scan the card to complete collection.
                </p>
            </div>
            <a href="{{ route('home') }}" class="inline-flex h-10 items-center justify-center border border-zinc-300 bg-white px-4 text-sm font-bold hover:border-red-700 hover:text-red-700">
                Back to Store
            </a>
        </header>

        @if ($errors->any())
            <div class="border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('success'))
            <div class="border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <section class="grid gap-5 lg:grid-cols-2">
            <form method="POST" action="{{ route('iot.rfid.assign') }}" class="border border-zinc-200 bg-white p-5">
                @csrf
                <h2 class="text-lg font-extrabold">Assign RFID Tag</h2>
                <p class="mt-1 text-sm text-zinc-600">Use this when giving a customer/order a card or tag.</p>

                <label class="mt-5 block text-xs font-bold uppercase tracking-widest text-zinc-500" for="order_id">Order ID</label>
                <input id="order_id" name="order_id" required placeholder="Example: 12" class="mt-2 w-full border border-zinc-300 px-3 py-2 text-sm focus:border-red-700 focus:outline-none" value="{{ old('order_id') }}">

                <label class="mt-4 block text-xs font-bold uppercase tracking-widest text-zinc-500" for="assign_rfid_uid">RFID UID</label>
                <input id="assign_rfid_uid" name="rfid_uid" required placeholder="Example: A1B2C3D4" class="mt-2 w-full border border-zinc-300 px-3 py-2 text-sm uppercase focus:border-red-700 focus:outline-none" value="{{ old('rfid_uid') }}">

                <button class="mt-5 w-full bg-red-700 px-4 py-3 text-sm font-extrabold uppercase tracking-widest text-white hover:bg-red-800">
                    Assign Tag
                </button>
            </form>

            <form method="POST" action="{{ route('iot.rfid.scan') }}" class="border border-zinc-200 bg-white p-5">
                @csrf
                <h2 class="text-lg font-extrabold">Test RFID Scan</h2>
                <p class="mt-1 text-sm text-zinc-600">This simulates what the Arduino bridge sends after a real card scan.</p>

                <label class="mt-5 block text-xs font-bold uppercase tracking-widest text-zinc-500" for="scan_rfid_uid">RFID UID</label>
                <input id="scan_rfid_uid" name="rfid_uid" required placeholder="Example: A1B2C3D4" class="mt-2 w-full border border-zinc-300 px-3 py-2 text-sm uppercase focus:border-red-700 focus:outline-none">

                <button class="mt-5 w-full bg-zinc-900 px-4 py-3 text-sm font-extrabold uppercase tracking-widest text-white hover:bg-black">
                    Scan and Complete Order
                </button>

                <div class="mt-5 bg-zinc-50 p-4 text-xs leading-6 text-zinc-600">
                    <p class="font-bold text-zinc-900">Bridge API URL</p>
                    <code class="break-all">{{ $apiUrl }}</code>
                </div>
            </form>
        </section>

        <section class="border border-zinc-200 bg-white">
            <div class="border-b border-zinc-200 px-5 py-4">
                <h2 class="text-lg font-extrabold">Recent Active Orders</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-zinc-100 text-xs uppercase tracking-widest text-zinc-500">
                        <tr>
                            <th class="px-5 py-3">Order</th>
                            <th class="px-5 py-3">Customer</th>
                            <th class="px-5 py-3">Shop</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">RFID UID</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse($orders as $order)
                            <tr>
                                <td class="px-5 py-4 font-bold">#ORD-{{ $order->order_id }}</td>
                                <td class="px-5 py-4">{{ $order->customer?->user?->full_name ?? 'Unknown' }}</td>
                                <td class="px-5 py-4">{{ $order->collectionSlot?->shop?->shop_name ?? 'No shop' }}</td>
                                <td class="px-5 py-4">
                                    <span class="bg-zinc-100 px-3 py-1 text-xs font-bold">{{ str_replace('_', ' ', $order->order_status) }}</span>
                                </td>
                                <td class="px-5 py-4 font-mono text-xs">{{ $order->rfid_uid ?? 'Not assigned' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-zinc-500">No active orders yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
