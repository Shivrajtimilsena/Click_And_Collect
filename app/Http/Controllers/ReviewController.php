<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $customer = auth()->user()->customer;

        $product->reviews()->create([
            'customer_id' => $customer->customer_id,
            'review_rating' => $request->rating,
            'review' => $request->comment,
        ]);

        return back()->with('success', 'Review added successfully!');
    }
}
