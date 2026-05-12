<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the profile dashboard
     */
    public function dashboard(): View
    {
        $user = Auth::user();

        // Ensure customer record exists
        $customer = $user->customer;
        if (! $customer) {
            $customer = Customer::firstOrCreate(
                ['user_id' => $user->user_id],
                ['user_id' => $user->user_id]
            );
        }

        // Get upcoming collections
        $upcomingCollections = $customer->orders()
            ->with('collectionSlot.shop', 'items.product')
            ->whereIn('order_status', ['READY', 'PENDING'])
            ->whereHas('collectionSlot', function ($query) {
                $query->where('slot_date', '>=', now()->toDateString());
            })
            ->orderBy('created_at', 'desc')
            ->limit(2)
            ->get();

        // Get recent order history
        $recentOrders = $customer->orders()
            ->with('collectionSlot.shop', 'items')
            ->orderBy('order_date', 'desc')
            ->paginate(10);

        return view('profile.dashboard', [
            'upcomingCollections' => $upcomingCollections,
            'recentOrders' => $recentOrders,
            'customer' => $customer,
        ]);
    }

    /**
     * Display all orders
     */
    public function orders(): View
    {
        $user = Auth::user();
        $customer = $user->customer;

        if (! $customer) {
            $customer = Customer::firstOrCreate(
                ['user_id' => $user->user_id],
                ['user_id' => $user->user_id]
            );
        }

        $orders = $customer->orders()
            ->with('items.product.shop', 'collectionSlot.shop')
            ->orderBy('order_date', 'desc')
            ->get();

        $orderGroups = $orders->groupBy(function ($order) {
            return $order->group_id ?? 'single_'.$order->order_id;
        });

        return view('profile.orders', ['orderGroups' => $orderGroups]);
    }

    /**
     * Display saved shops (wishlists)
     */
    public function shops(): View
    {
        $user = Auth::user();
        $customer = $user->customer;

        if (! $customer) {
            $customer = Customer::firstOrCreate(
                ['user_id' => $user->user_id],
                ['user_id' => $user->user_id]
            );
        }

        $wishlists = $customer->wishlists()
            ->with('products.product.discount')
            ->get();

        return view('profile.shops', ['wishlists' => $wishlists]);
    }

    /**
     * Display settings page
     */
    public function settings(): View
    {
        $user = Auth::user();
        $customer = $user->customer;

        if (! $customer) {
            $customer = Customer::firstOrCreate(
                ['user_id' => $user->user_id],
                ['user_id' => $user->user_id]
            );
        }

        // Get recent orders for display
        $orders = $customer->orders()
            ->with(['items.product.shop.trader.user', 'collectionSlot'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get upcoming collection slots
        $upcomingSlots = $customer->orders()
            ->with('collectionSlot')
            ->whereIn('order_status', ['READY', 'PENDING'])
            ->whereHas('collectionSlot', function ($query) {
                $query->where('slot_date', '>=', now()->toDateString());
            })
            ->get()
            ->map(fn($order) => $order->collectionSlot)
            ->filter();

        return view('profile.settings', [
            'customer' => $customer,
            'orders' => $orders,
            'upcomingSlots' => $upcomingSlots,
        ]);
    }

    /**
     * Display edit profile page (backward compatibility)
     */
    public function edit(): View
    {
        return $this->dashboard();
    }

    /**
     * Update the customer profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email,'.$user->user_id.',user_id',
            'phone_no' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = 'avatar_'.$user->user_id.'_'.time().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('avatars', $filename, 'public');
            $validated['avatar_url'] = '/storage/'.$path;
        }

        // Update user info (including address fields and avatar)
        $user->update($validated);

        return redirect()->route('profile.settings')->with('success', 'Profile updated successfully!');
    }
}
