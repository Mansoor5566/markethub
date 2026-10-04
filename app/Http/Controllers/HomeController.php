<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Listing;

class HomeController extends Controller
{
    public function index()
    {
        $featuredListings = Listing::with(['seller', 'images', 'category'])
            ->active()
            ->orderByDesc('views_count')
            ->take(8)
            ->get();
        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->get()
            ->map(function ($category) {
                $category->listings_count = \App\Models\Listing::where('status', 'active')
                    ->whereIn('category_id', $category->children->pluck('id')->push($category->id))
                    ->count();
                return $category;
            });

        $recentListings = Listing::with(['seller', 'images', 'category'])
            ->active()
            ->latest()
            ->take(8)
            ->get();

        return view('home.index', compact(
            'featuredListings',
            'categories',
            'recentListings'
        ));
    }
}
