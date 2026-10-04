<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Listing;
use App\Models\Category;
use Illuminate\Support\Str;

class ListingSeeder extends Seeder
{
    public function run(): void
    {
        $sellers    = User::role('seller')->get();
        $categories = Category::whereNotNull('parent_id')->get();

        $conditions = ['new', 'like_new', 'good', 'fair'];
        $statuses   = ['active', 'active', 'active', 'draft', 'paused'];
        $locations  = [
            'Karachi', 'Lahore', 'Islamabad', 'Rawalpindi',
            'Peshawar', 'Quetta', 'Multan', 'Faisalabad'
        ];

        $titles = [
            'iPhone 15 Pro Max 256GB',
            'Samsung Galaxy S24 Ultra',
            'Dell Laptop Core i7 16GB',
            'Honda CD 70 2023',
            'Toyota Corolla 2020',
            'Sofa Set 5 Seater',
            'Cricket Kit Complete',
            'Web Development Service',
            'MacBook Pro M3',
            'Nike Running Shoes',
            'Wooden Dining Table',
            'Canon DSLR Camera',
            'PlayStation 5',
            'Air Conditioner 1.5 Ton',
            'Electric Guitar',
            'Mountain Bike',
            'Washing Machine',
            'Study Table with Chair',
            'Graphics Design Service',
            'Python Programming Course',
            'Samsung Smart TV 55 inch',
            'Refrigerator Double Door',
            'Antique Watch Collection',
            'Handmade Carpet',
        ];

        foreach ($sellers as $seller) {
            for ($i = 0; $i < 24; $i++) {
                $title = $titles[$i % count($titles)] . ' #' . ($i + 1);
                $slug  = Str::slug($title) . '-' . $seller->id . '-' . $i;

                Listing::create([
                    'user_id'     => $seller->id,
                    'category_id' => $categories->random()->id,
                    'title'       => $title,
                    'slug'        => $slug,
                    'description' => 'This is a great ' . $title . '. In excellent condition and ready for sale. Contact seller for more details. Price is negotiable for serious buyers.',
                    'price'       => rand(500, 150000),
                    'quantity'    => rand(1, 10),
                    'location'    => $locations[array_rand($locations)],
                    'condition'   => $conditions[array_rand($conditions)],
                    'status'      => $statuses[array_rand($statuses)],
                    'views_count' => rand(0, 500),
                ]);
            }
        }
    }
}