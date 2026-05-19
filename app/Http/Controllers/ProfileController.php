<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the profile dashboard
     */
    public function dashboard(): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user->isTrader()) {
            return redirect()->route('trader.dashboard');
        }

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
            ->with('collectionSlot', 'shop', 'items.product')
            ->whereIn('order_status', ['READY', 'PENDING'])
            ->whereHas('collectionSlot', function ($query) {
                $query->where('slot_date', '>=', now()->toDateString());
            })
            ->orderBy('created_at', 'desc')
            ->limit(2)
            ->get();

        // Get recent order history
        $recentOrders = $customer->orders()
            ->with('collectionSlot', 'shop', 'items')
            ->orderBy('order_date', 'desc')
            ->paginate(10);

        // Stats for dashboard cards
        $activeOrdersCount = $customer->orders()
            ->whereIn('order_status', ['PENDING', 'READY', 'IN_PROGRESS'])
            ->count();

        $totalSpent = (float) $customer->orders()
            ->where('order_status', 'COMPLETED')
            ->sum('total_amount');

        $savedShopsCount = $customer->wishlists()
            ->withCount('products')
            ->get()
            ->sum('products_count');

        return view('profile.dashboard', [
            'upcomingCollections' => $upcomingCollections,
            'recentOrders' => $recentOrders,
            'customer' => $customer,
            'activeOrdersCount' => $activeOrdersCount,
            'totalSpent' => $totalSpent,
            'savedShopsCount' => $savedShopsCount,
        ]);
    }

    /**
     * Display all orders
     */
    public function orders(): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user->isTrader()) {
            return redirect()->route('trader.dashboard');
        }

        $customer = $user->customer;

        if (! $customer) {
            $customer = Customer::firstOrCreate(
                ['user_id' => $user->user_id],
                ['user_id' => $user->user_id]
            );
        }

        $orders = $customer->orders()
            ->with('items.product.shop', 'collectionSlot', 'shop')
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
    public function shops(): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user->isTrader()) {
            return redirect()->route('trader.dashboard');
        }

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
    public function settings(): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user->isTrader()) {
            return redirect()->route('trader.dashboard');
        }

        $customer = $user->customer;

        if (! $customer) {
            $customer = Customer::firstOrCreate(
                ['user_id' => $user->user_id],
                ['user_id' => $user->user_id]
            );
        }

        return view('profile.settings', [
            'customer' => $customer,
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
            'email' => 'required|email|unique:CC_USER,email,'.$user->user_id.',user_id',
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

    public function changePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if ($user->password !== $request->current_password) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => $request->new_password]);

        return redirect()->route('profile.settings')->with('success', 'Password changed successfully!');
    }

    public function clearOrderHistory(): RedirectResponse
    {
        $customer = Auth::user()->getCustomerRecord();

        $orderIds = $customer->orders()
            ->whereIn('order_status', ['COMPLETED', 'CANCELLED'])
            ->pluck('order_id');

        if ($orderIds->isEmpty()) {
            return redirect()->route('profile.orders')->with('info', 'No completed or cancelled orders to clear.');
        }

        DB::transaction(function () use ($orderIds) {
            OrderItem::whereIn('order_id', $orderIds)->delete();
            Payment::whereIn('order_id', $orderIds)->delete();
            Order::whereIn('order_id', $orderIds)->delete();
        });

        $count = $orderIds->count();
        return redirect()->route('profile.orders')->with('success', "{$count} order(s) cleared from history.");
    }
}
