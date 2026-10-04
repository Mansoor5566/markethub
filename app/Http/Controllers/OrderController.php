<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Buyer order history
    public function index(Request $request)
    {
        $query = Order::with(['listing.images', 'listing.seller'])
            ->where('buyer_id', auth()->id())
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15)->withQueryString();
        return view('orders.index', compact('orders'));
    }

    // Buyer order detail
    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load(['listing.images', 'listing.seller', 'buyer', 'review']);
        return view('orders.show', compact('order'));
    }

    // Seller orders list
    public function sellerIndex(Request $request)
    {
        $query = Order::with(['listing', 'buyer'])
            ->whereHas('listing', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15)->withQueryString();
        return view('seller.orders.index', compact('orders'));
    }

    // Mark shipped
    public function markShipped(Order $order)
    {
        $this->authorize('updateStatus', $order);
        $order->update(['status' => 'shipped']);
        return back()->with('success', 'Order marked as shipped.');
    }

    // Mark completed
    public function markCompleted(Order $order)
    {
        $this->authorize('updateStatus', $order);
        $order->update(['status' => 'completed']);
        return back()->with('success', 'Order marked as completed.');
    }
}