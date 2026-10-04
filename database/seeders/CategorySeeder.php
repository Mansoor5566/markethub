<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Electronics' => [
                'Mobile Phones', 'Laptops', 'Tablets', 'Accessories'
            ],
            'Vehicles' => [
                'Cars', 'Motorcycles', 'Spare Parts', 'Accessories'
            ],
            'Property' => [
                'Houses', 'Apartments', 'Plots', 'Commercial'
            ],
            'Fashion' => [
                'Men Clothing', 'Women Clothing', 'Shoes', 'Watches'
            ],
            'Home & Garden' => [
                'Furniture', 'Kitchen', 'Garden', 'Decor'
            ],
            'Sports' => [
                'Cricket', 'Football', 'Gym Equipment', 'Outdoor'
            ],
            'Books & Education' => [
                'Textbooks', 'Novels', 'Stationery', 'Courses'
            ],
            'Services' => [
                'Web Development', 'Graphic Design', 'Tutoring', 'Repair'
            ],
        ];

        foreach ($categories as $parentName => $children) {
            $parent = Category::create([
                'name'        => $parentName,
                'slug'        => Str::slug($parentName),
                'description' => 'Browse all ' . $parentName . ' listings.',
            ]);

            foreach ($children as $childName) {
                Category::create([
                    'name'        => $childName,
                    'slug'        => Str::slug($parentName . ' ' . $childName),
                    'description' => $childName . ' listings under ' . $parentName,
                    'parent_id'   => $parent->id,
                ]);
            }
        }
    }
}