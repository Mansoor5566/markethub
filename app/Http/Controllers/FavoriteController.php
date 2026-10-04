<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = auth()->user()
            ->favorites()
            ->with(['listing.images', 'listing.seller'])
            ->latest()
            ->get();

        return view('favorites.index', compact('favorites'));
    }

    // Toggle favorite via vanilla JS fetch (FR-FAV-01)
    public function toggle(Listing $listing)
    {
        $user = auth()->user();

        $existing = Favorite::where('user_id', $user->id)
            ->where('listing_id', $listing->id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['favorited' => false]);
        }

        Favorite::create([
            'user_id'    => $user->id,
            'listing_id' => $listing->id,
        ]);

        return response()->json(['favorited' => true]);
    }
}