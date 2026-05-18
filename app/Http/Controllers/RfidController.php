<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RfidController extends Controller
{
    public function show(): View
    {
        $orders = Order::with(['customer.user', 'collectionSlot', 'shop'])
            ->whereIn('order_status', ['PENDING', 'IN_PROGRESS', 'READY'])
            ->latest()
            ->limit(12)
            ->get();

        return view('iot.rfid-scan', [
            'orders' => $orders,
            'apiUrl' => url('/api/iot/rfid-scan'),
        ]);
    }

    public function assign(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'order_id' => ['required', 'integer', 'exists:order,order_id'],
            'rfid_uid' => ['required', 'string', 'max:64'],
        ]);

        $uid = $this->normaliseUid($validated['rfid_uid']);

        if (strlen($uid) < 4) {
            return $this->failure($request, 'RFID UID is too short. Scan the card again.', 422);
        }

        $existingOrder = Order::where('rfid_uid', $uid)
            ->where('order_id', '!=', $validated['order_id'])
            ->first();

        if ($existingOrder) {
            return $this->failure($request, "This RFID tag is already assigned to order #ORD-{$existingOrder->order_id}.", 422);
        }

        $order = Order::findOrFail($validated['order_id']);
        $order->update([
            'rfid_uid' => $uid,
            'rfid_assigned_at' => now(),
        ]);

        return $this->success($request, [
            'message' => "RFID tag {$uid} assigned to order #ORD-{$order->order_id}.",
            'order_id' => $order->order_id,
            'rfid_uid' => $uid,
        ]);
    }

    public function scan(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'rfid_uid' => ['required', 'string', 'max:64'],
        ]);

        $uid = $this->normaliseUid($validated['rfid_uid']);

        if (strlen($uid) < 4) {
            return $this->failure($request, 'RFID UID is too short. Scan the card again.', 422);
        }

        $order = Order::with(['customer.user', 'collectionSlot', 'shop'])
            ->where('rfid_uid', $uid)
            ->first();

        if (! $order) {
            return $this->failure($request, "No order is assigned to RFID tag {$uid}.", 404);
        }

        if ($order->order_status !== 'READY') {
            return $this->failure(
                $request,
                "Order #ORD-{$order->order_id} is {$order->order_status}. Mark it READY before collection.",
                409,
                $order
            );
        }

        $order->update([
            'order_status' => 'COMPLETED',
            'collected_at' => now(),
        ]);

        return $this->success($request, [
            'message' => "Order #ORD-{$order->order_id} collected successfully.",
            'order' => $this->orderPayload($order->fresh(['customer.user', 'collectionSlot', 'shop'])),
        ]);
    }

    private function normaliseUid(string $uid): string
    {
        return strtoupper(preg_replace('/[^A-Fa-f0-9]/', '', $uid));
    }

    private function success(Request $request, array $payload): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['ok' => true] + $payload);
        }

        return back()->with('success', $payload['message']);
    }

    private function failure(Request $request, string $message, int $status, ?Order $order = null): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            $payload = ['ok' => false, 'message' => $message];

            if ($order) {
                $payload['order'] = $this->orderPayload($order);
            }

            return response()->json($payload, $status);
        }

        return back()->withErrors(['rfid_uid' => $message]);
    }

    private function orderPayload(Order $order): array
    {
        return [
            'order_id' => $order->order_id,
            'order_status' => $order->order_status,
            'rfid_uid' => $order->rfid_uid,
            'customer_name' => $order->customer?->user?->full_name,
            'shop_name' => $order->shop?->shop_name,
            'collected_at' => $order->collected_at?->toDateTimeString(),
        ];
    }
}
