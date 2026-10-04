<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Listing;

class SellerController extends Controller
{
    // Public seller profile (FR-PROFILE-02)
    public function show(User $user)
    {
        $listings = Listing::with(['images'])
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->latest()
            ->paginate(12);

        $reviews = $user->receivedReviews()
            ->with('reviewer')
            ->latest()
            ->take(5)
            ->get();

        return view('sellers.show', compact('user', 'listings', 'reviews'));
    }

    // Seller dashboard (FR-SELLER-06)
    public function dashboard()
    {
        $user = auth()->user();

        $totalRevenue = Order::whereHas('listing', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('status', 'completed')
            ->sum('total');

        $totalOrders = Order::whereHas('listing', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count();

        $activeListings = Listing::where('user_id', $user->id)
            ->where('status', 'active')
            ->count();

        $pendingOrders = Order::whereHas('listing', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('status', 'paid')
            ->count();

        $recentOrders = Order::whereHas('listing', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with(['listing', 'buyer'])
            ->latest()
            ->take(10)
            ->get();

        // Monthly revenue for last 6 months (FR-SELLER-06)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenue = Order::whereHas('listing', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->where('status', 'completed')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total');

            $monthlyRevenue[] = [
                'month'   => $month->format('M'),
                'revenue' => $revenue,
            ];
        }

        return view('seller.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'activeListings',
            'pendingOrders',
            'recentOrders',
            'monthlyRevenue'
        ));
    }
}