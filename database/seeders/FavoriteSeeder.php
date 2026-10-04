<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Listing;
use App\Models\Favorite;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $buyers   = User::role('buyer')->get();
        $listings = Listing::where('status', 'active')->get();

        $count = 0;
        $tries = 0;

        while ($count < 50 && $tries < 200) {
            $tries++;
            $buyer   = $buyers->random();
            $listing = $listings->random();

            // Unique constraint enforced at DB level
            $exists = Favorite::where('user_id', $buyer->id)
                ->where('listing_id', $listing->id)
                ->exists();

            if (!$exists) {
                Favorite::create([
                    'user_id'    => $buyer->id,
                    'listing_id' => $listing->id,
                ]);
                $count++;
            }
        }
    }
}