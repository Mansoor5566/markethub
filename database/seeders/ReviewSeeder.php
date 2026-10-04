<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $completedOrders = Order::where('status', 'completed')
            ->with(['listing', 'buyer'])
            ->get();

        $comments = [
            'Excellent product, exactly as described!',
            'Very fast delivery, seller is trustworthy.',
            'Good quality, happy with the purchase.',
            'Amazing deal, highly recommended.',
            'Product was in perfect condition.',
            'Great seller, smooth transaction.',
            'Satisfied with the purchase overall.',
            'Will buy again from this seller.',
        ];

        $count = 0;
        foreach ($completedOrders as $order) {
            if ($count >= 45) break;

            // Skip if review already exists
            if (Review::where('order_id', $order->id)->exists()) continue;

            Review::create([
                'order_id'    => $order->id,
                'reviewer_id' => $order->buyer_id,
                'seller_id'   => $order->listing->user_id,
                'listing_id'  => $order->listing_id,
                'rating'      => rand(3, 5), // positive weighted
                'title'       => 'Great purchase!',
                'body'        => $comments[array_rand($comments)],
            ]);

            $count++;
        }
    }
}