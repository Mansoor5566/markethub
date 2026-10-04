<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class, // must run first
            UserSeeder::class,
            CategorySeeder::class,
            ListingSeeder::class,
            OrderSeeder::class,
            ReviewSeeder::class,
            MessageSeeder::class,
            FavoriteSeeder::class,
        ]);
    }
}