<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Listing;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(string $slug, Request $request)
    {
        $category = Category::where('slug', $slug)
            ->with('children')
            ->firstOrFail();

        $query = Listing::with(['seller', 'images'])
            ->active();

        // If parent category, include all children
        if ($category->parent_id === null) {
            $childIds = $category->children->pluck('id');
            $query->where(function ($q) use ($category, $childIds) {
                $q->where('category_id', $category->id)
                  ->orWhereIn('category_id', $childIds);
            });
        } else {
            $query->where('category_id', $category->id);
        }

        // Subcategory filter pill
        if ($request->filled('sub')) {
            $query->where('category_id', $request->sub);
        }

        $listings = $query->latest()->paginate(12)->withQueryString();

        return view('categories.show', compact('category', 'listings'));
    }
}