<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Listing::with(['seller', 'category', 'images'])
            ->withTrashed();

        if ($request->filled('status')) {
            if ($request->status === 'deleted') {
                $query->onlyTrashed();
            } else {
                $query->withoutTrashed()->where('status', $request->status);
            }
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $listings = $query->latest()->paginate(20)->withQueryString();

        return view('admin.listings.index', compact('listings'));
    }

    public function approve(Listing $listing)
    {
        $listing->update(['status' => 'active']);
        return back()->with('success', 'Listing approved and set to active.');
    }

    public function forceDelete(Listing $listing)
    {
        // Delete all images from disk
        foreach ($listing->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }

        $listing->forceDelete();
        return back()->with('success', 'Listing permanently deleted.');
    }
}