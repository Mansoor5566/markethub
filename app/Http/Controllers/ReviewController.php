<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use App\Http\Requests\StoreReviewRequest;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request, Order $order)
    {
        // Make sure buyer owns this order
        if ($order->buyer_id !== auth()->id()) {
            abort(403);
        }

        // Make sure order is completed
        if ($order->status !== 'completed') {
            abort(403, 'You can only review completed orders.');
        }

        // Make sure no review exists yet
        if ($order->review()->exists()) {
            return back()->with('error', 'You have already reviewed this order.');
        }

        Review::create([
            'order_id'    => $order->id,
            'reviewer_id' => auth()->id(),
            'seller_id'   => $order->listing->user_id,
            'listing_id'  => $order->listing_id,
            'rating'      => $request->rating,
            'title'       => $request->title,
            'body'        => $request->body,
        ]);

        return back()->with('success', 'Review submitted successfully!');
    }
}