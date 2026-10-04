<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Listing;
use App\Models\Message;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $buyers  = User::role('buyer')->get();
        $listings = Listing::where('status', 'active')->with('seller')->get();

        $messageTexts = [
            'Hi, is this still available?',
            'Can you do a lower price?',
            'Yes it is available, price is fixed.',
            'Can I see it in person?',
            'Sure, you can visit anytime.',
            'Is there any warranty?',
            'Yes 6 months warranty included.',
            'I am interested, how to proceed?',
        ];

        for ($t = 0; $t < 20; $t++) {
            $buyer   = $buyers->random();
            $listing = $listings->random();
            $seller  = $listing->seller;

            if ($buyer->id === $seller->id) continue;

            // 4 messages per thread alternating sender
            for ($m = 0; $m < 4; $m++) {
                $isBuyerTurn = $m % 2 === 0;

                Message::create([
                    'sender_id'   => $isBuyerTurn ? $buyer->id   : $seller->id,
                    'receiver_id' => $isBuyerTurn ? $seller->id  : $buyer->id,
                    'listing_id'  => $listing->id,
                    'body'        => $messageTexts[$m],
                    'is_read'     => $m < 2,
                ]);
            }
        }
    }
}