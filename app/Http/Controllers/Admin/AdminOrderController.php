<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['listing', 'buyer'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $totalRevenue = (clone $query)->where('status', 'completed')->sum('total');
        $orders = $query->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders', 'totalRevenue'));
    }
}