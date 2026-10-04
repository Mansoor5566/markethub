<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q', '');

        $listings = Listing::with(['seller', 'images', 'category'])
            ->active()
            ->where(function ($query) use ($q) {
                $query->where('title', 'like', '%' . $q . '%')
                      ->orWhere('description', 'like', '%' . $q . '%');
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('search.index', compact('listings', 'q'));
    }
}