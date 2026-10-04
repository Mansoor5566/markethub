<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Listing;
use App\Models\Order;
use App\Models\Review;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Stat cards
        $totalUsers    = User::count();
        $totalListings = Listing::count();
        $totalOrders   = Order::count();
        $totalRevenue  = Order::where('status', 'completed')->sum('total');

        // Users by role
        $totalBuyers  = User::role('buyer')->count();
        $totalSellers = User::role('seller')->count();
        $totalAdmins  = User::role('admin')->count();

        // Listings by status
        $activeListings = Listing::where('status', 'active')->count();
        $draftListings  = Listing::where('status', 'draft')->count();

        // Orders by status
        $pendingOrders   = Order::where('status', 'pending')->count();
        $paidOrders      = Order::where('status', 'paid')->count();
        $completedOrders = Order::where('status', 'completed')->count();

        // Recent orders
        $recentOrders = Order::with(['listing', 'buyer'])
            ->latest()
            ->take(10)
            ->get();

        // Monthly revenue last 6 months
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenue = Order::where('status', 'completed')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total');

            $monthlyRevenue[] = [
                'month'   => $month->format('M'),
                'revenue' => $revenue,
            ];
        }

        return view('admin.dashboard', compact(
            'totalUsers', 'totalListings', 'totalOrders', 'totalRevenue',
            'totalBuyers', 'totalSellers', 'totalAdmins',
            'activeListings', 'draftListings',
            'pendingOrders', 'paidOrders', 'completedOrders',
            'recentOrders', 'monthlyRevenue'
        ));
    }
}