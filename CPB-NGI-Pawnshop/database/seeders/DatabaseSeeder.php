<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin user
        User::firstOrCreate(
            ['email' => 'admin@pawnshop.com'],
            [
                'name'     => 'Admin',
                'role'     => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        // Create default teller
        User::firstOrCreate(
            ['email' => 'teller@pawnshop.com'],
            [
                'name'     => 'Teller',
                'role'     => 'teller',
                'password' => Hash::make('password'),
            ]
        );

        // Create default manager
        User::firstOrCreate(
            ['email' => 'manager@pawnshop.com'],
            [
                'name'     => 'Manager',
                'role'     => 'manager',
                'password' => Hash::make('password'),
            ]
        );

        // Create default cashier
        User::firstOrCreate(
            ['email' => 'cashier@pawnshop.com'],
            [
                'name'     => 'Cashier',
                'role'     => 'cashier',
                'password' => Hash::make('password'),
            ]
        );

        // Create default categories
        $categories = [
            ['name' => 'Gold Jewelry', 'description' => 'Gold rings, necklaces, bracelets, earrings'],
            ['name' => 'Silver Jewelry', 'description' => 'Silver rings, necklaces, bracelets'],
            ['name' => 'Watches', 'description' => 'Wristwatches, pocket watches'],
            ['name' => 'Electronics', 'description' => 'Phones, laptops, tablets, cameras'],
            ['name' => 'Appliances', 'description' => 'Small and large home appliances'],
            ['name' => 'Musical Instruments', 'description' => 'Guitars, keyboards, etc.'],
            ['name' => 'Tools & Equipment', 'description' => 'Power tools, hand tools'],
            ['name' => 'Others', 'description' => 'Miscellaneous items'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], $cat);
        }

        // Seed Philippine locations from PSGC API
        $this->call(LocationSeeder::class);

        // Seed mock data for the dashboard and system testing
        $this->call(MockDataSeeder::class);
    }
}
