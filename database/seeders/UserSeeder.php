<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name'              => 'Admin User',
            'email'             => 'admin@markethub.test',
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active'         => true,
        ]);
        $admin->assignRole('admin');

        // Sellers (5)
        $sellers = [
            ['name' => 'Sara Khan',    'email' => 'seller@markethub.test'],
            ['name' => 'Ali Raza',     'email' => 'seller2@markethub.test'],
            ['name' => 'Usman Malik',  'email' => 'seller3@markethub.test'],
            ['name' => 'Fatima Noor',  'email' => 'seller4@markethub.test'],
            ['name' => 'Hamza Ahmed',  'email' => 'seller5@markethub.test'],
        ];

        foreach ($sellers as $data) {
            $user = User::create([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active'         => true,
                'bio'               => 'Trusted seller on MarketHub.',
            ]);
            $user->assignRole('seller');
        }

        // Buyers (20)
        for ($i = 1; $i <= 20; $i++) {
            $user = User::create([
                'name'              => 'Buyer User ' . $i,
                'email'             => 'buyer' . $i . '@markethub.test',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active'         => true,
            ]);
            $user->assignRole('buyer');
        }
    }
}