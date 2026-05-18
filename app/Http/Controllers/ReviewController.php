<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        if (auth()->user()->role === 'TRADER') {
            return back()->with('error', 'You cannot add a review as a trader.');
        }

        $request->validate([
            'comment' => 'nullable|string|max:500',
        ]);

        $customer = auth()->user()->customer ?? Customer::firstOrCreate(
            ['user_id' => auth()->id()],
            ['user_id' => auth()->id()]
        );

        $product->reviews()->create([
            'customer_id' => $customer->customer_id,
            'review' => $request->comment,
            'review_date' => now(),
        ]);

        return back()->with('success', 'Review added successfully!');
    }
}
