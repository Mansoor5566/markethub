<?php

namespace App\Http\Controllers;


use App\Models\Listing;
use App\Models\Category;
use App\Models\ListingImage;
use App\Http\Requests\StoreListingRequest;
use App\Http\Requests\UpdateListingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class ListingController extends Controller
{
    // Public listings browse (FR-BROWSE-01)
    public function index(Request $request)
    {
        $query = Listing::with(['seller', 'images', 'category'])
            ->active();

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by condition
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // Filter by price
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting (FR-BROWSE-03)
        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'price_low'  => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'popular'    => $query->orderByDesc('views_count'),
            default      => $query->latest(),
        };

        $listings   = $query->paginate(12)->withQueryString();
        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('listings.index', compact('listings', 'categories'));
    }

    // Listing detail (FR-DETAIL-01)
    public function show(string $slug)
    {
        $listing = Listing::with([
            'seller',
            'images',
            'category',
            'reviews.reviewer',
        ])->where('slug', $slug)->firstOrFail();

        // Track views (FR-DETAIL-05)
        $listing->incrementViews('viewed_listing_' . $listing->id);

        $canReview = false;
        if (auth()->check()) {
            $canReview = \App\Models\Order::where('buyer_id', auth()->id())
                ->where('listing_id', $listing->id)
                ->where('status', 'completed')
                ->whereDoesntHave('review')
                ->exists();
        }

        return view('listings.show', compact('listing', 'canReview'));
    }

    // Seller listings management (FR-SELLER-05)
   public function sellerIndex(Request $request)
{
    $query = Listing::with(['images', 'category'])
        ->where('user_id', auth()->id());

    // Only show trashed if status filter is 'deleted'
    if ($request->status === 'deleted') {
        $query->onlyTrashed();
    } else {
        // Default — no soft deleted listings
        $query->withoutTrashed();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    }

    $listings = $query->latest()->paginate(15)->withQueryString();

    return view('seller.listings.index', compact('listings'));
}
    // Create listing form (FR-SELLER-01)
    public function create()
    {
        $this->authorize('create', Listing::class);
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('seller.listings.create', compact('categories'));
    }

    // Store new listing (FR-SELLER-01)
    public function store(StoreListingRequest $request)
    {
        $this->authorize('create', Listing::class);

        $listing = Listing::create([
            'user_id'     => auth()->id(),
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'slug'        => Str::slug($request->title) . '-' . uniqid(),
            'description' => $request->description,
            'price'       => $request->price,
            'quantity'    => $request->quantity,
            'location'    => $request->location,
            'condition'   => $request->condition,
            'status'      => $request->status,
        ]);

        // Handle image uploads (FR-SELLER-01)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('listings', 'public');
                ListingImage::create([
                    'listing_id' => $listing->id,
                    'path'       => $path,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('seller.listings.index')
            ->with('success', 'Listing created successfully.');
    }

    // Edit listing form (FR-SELLER-02)
    public function edit(Listing $listing)
    {
        $this->authorize('update', $listing);
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('seller.listings.edit', compact('listing', 'categories'));
    }

    // Update listing (FR-SELLER-02)
    public function update(UpdateListingRequest $request, Listing $listing)
    {
        $this->authorize('update', $listing);

        $listing->update([
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'description' => $request->description,
            'price'       => $request->price,
            'quantity'    => $request->quantity,
            'location'    => $request->location,
            'condition'   => $request->condition,
            'status'      => $request->status,
        ]);

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('listings', 'public');
                ListingImage::create([
                    'listing_id' => $listing->id,
                    'path'       => $path,
                    'sort_order' => $listing->images()->count() + $index,
                ]);
            }
        }

        return redirect()->route('seller.listings.index')
            ->with('success', 'Listing updated successfully.');
    }

    // Soft delete listing (FR-SELLER-03)
    public function destroy(Listing $listing)
    {
        $this->authorize('delete', $listing);

        // Delete image files from disk
        foreach ($listing->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }

        $listing->delete(); // soft delete

        return redirect()->route('seller.listings.index')
            ->with('success', 'Listing deleted successfully.');
    }

    // Toggle active/paused (FR-SELLER-04)
    public function toggleStatus(Listing $listing)
    {
        $this->authorize('update', $listing);

        $listing->update([
            'status' => $listing->status === 'active' ? 'paused' : 'active',
        ]);

        return back()->with('success', 'Listing status updated.');
    }

    // Delete single image (FR-SELLER-02)
    public function destroyImage(Listing $listing, ListingImage $image)
    {
        $this->authorize('update', $listing);

        Storage::disk('public')->delete($image->path);
        $image->delete();

        return back()->with('success', 'Image removed.');
    }
}