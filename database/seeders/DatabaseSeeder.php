<?php

namespace Database\Seeders;

use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin account
        User::create([
            'name' => 'Admin',
            'email' => 'admin@getclose.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create demo vendor account
        User::create([
            'name' => 'Demo Vendor',
            'email' => 'vendor@getclose.com',
            'password' => Hash::make('vendor123'),
            'role' => 'vendor',
            'email_verified_at' => now(),
        ]);

        // Create demo client account
        User::create([
            'name' => 'Demo Client',
            'email' => 'client@getclose.com',
            'password' => Hash::make('client123'),
            'role' => 'client',
            'email_verified_at' => now(),
        ]);

        // Create default shipping methods
        ShippingMethod::create([
            'name' => 'Standard Shipping',
            'description' => 'Delivery in 5-7 business days',
            'price' => 5.99,
            'estimated_days' => 7,
            'is_active' => true,
        ]);

        ShippingMethod::create([
            'name' => 'Express Shipping',
            'description' => 'Delivery in 2-3 business days',
            'price' => 12.99,
            'estimated_days' => 3,
            'is_active' => true,
        ]);

        ShippingMethod::create([
            'name' => 'Next Day Delivery',
            'description' => 'Next business day delivery',
            'price' => 24.99,
            'estimated_days' => 1,
            'is_active' => true,
        ]);
    }
}
