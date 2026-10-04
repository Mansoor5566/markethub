<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Listing;
use App\Models\Order;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $buyers   = User::role('buyer')->get();
        $listings = Listing::where('status', 'active')->get();
        $statuses = ['pending', 'paid', 'shipped', 'completed', 'cancelled'];

        for ($i = 0; $i < 60; $i++) {
            $buyer   = $buyers->random();
            $listing = $listings->random();
            $qty     = rand(1, 3);
            $total   = $listing->price * $qty;

            Order::create([
                'buyer_id'         => $buyer->id,
                'listing_id'       => $listing->id,
                'unit_price'       => $listing->price,
                'quantity'         => $qty,
                'total'            => $total,
                'status'           => $statuses[array_rand($statuses)],
                'stripe_session_id'=> 'cs_test_seed_' . uniqid(),
                'shipping_address' => $buyer->name . ', House 123, Street 4, ' . $listing->location,
            ]);
        }
    }
}