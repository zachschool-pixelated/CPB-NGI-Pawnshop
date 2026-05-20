<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Safe;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Payment;
use App\Models\Approval;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class MockDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting Mock Data Seeder...');

        // 1. Ensure Users Exist
        $admin = User::firstOrCreate(
            ['email' => 'admin@pawnshop.com'],
            ['name' => 'Admin', 'role' => 'admin', 'password' => Hash::make('password')]
        );
        $teller = User::firstOrCreate(
            ['email' => 'teller@pawnshop.com'],
            ['name' => 'Teller', 'role' => 'teller', 'password' => Hash::make('password')]
        );

        // 2. Get/Create Categories
        $goldJewelry = Category::firstOrCreate(['name' => 'Gold Jewelry']);
        $silverJewelry = Category::firstOrCreate(['name' => 'Silver Jewelry']);
        $watches = Category::firstOrCreate(['name' => 'Watches']);
        $electronics = Category::firstOrCreate(['name' => 'Electronics']);

        // 3. Create Safes
        $safe1 = Safe::firstOrCreate(
            ['safe_code' => 'SAFE-001'],
            ['name' => 'Main Vault', 'capacity' => 1000000, 'items_capacity' => 50, 'location' => 'Main Branch']
        );
        $safe2 = Safe::firstOrCreate(
            ['safe_code' => 'SAFE-002'],
            ['name' => 'Display Case', 'capacity' => 500000, 'items_capacity' => 20, 'location' => 'Main Branch Front']
        );

        // Get or create dummy locations
        $region = \App\Models\Region::firstOrCreate(['code' => 'R1', 'name' => 'Region I']);
        $province = \App\Models\Province::firstOrCreate(['region_id' => $region->id, 'code' => 'P1', 'name' => 'Province 1']);
        $city = \App\Models\City::firstOrCreate(['province_id' => $province->id, 'code' => 'C1', 'name' => 'City 1']);
        $barangay = \App\Models\Barangay::firstOrCreate(['city_id' => $city->id, 'code' => 'B1', 'name' => 'Barangay 1']);

        // 4. Create Customers
        $customerNames = [
            ['first' => 'Maria', 'last' => 'Santos'],
            ['first' => 'Juan', 'last' => 'Dela Cruz'],
            ['first' => 'Ana', 'last' => 'Reyes'],
            ['first' => 'Pedro', 'last' => 'Penduko'],
            ['first' => 'Jose', 'last' => 'Garcia']
        ];
        
        $customers = collect();
        foreach ($customerNames as $index => $name) {
            $i = $index + 1;
            $customers->push(Customer::firstOrCreate(
                ['email' => "customer{$i}@example.com"],
                [
                    'first_name' => $name['first'],
                    'last_name' => $name['last'],
                    'phone_number' => "0912345678{$i}",
                    'address_line' => "{$i} Main St, Poblacion",
                    'id_type' => 'national_id',
                    'id_number' => "NID-000{$i}",
                    'region_id' => $region->id,
                    'province_id' => $province->id,
                    'city_id' => $city->id,
                    'barangay_id' => $barangay->id,
                ]
            ));
        }

        // Realistic Item Names Arrays
        $jewelryNames = ['18K Gold Necklace', '14K Diamond Ring', '24K Gold Bracelet', 'Pearl Earrings', 'Silver Pendant', 'Rolex Submariner Watch'];
        $electronicsNames = ['iPhone 13 Pro Max', 'Samsung Galaxy S22', 'Apple Watch Series 7', 'iPad Air 5th Gen', 'MacBook Pro M1', 'Sony PlayStation 5'];

        // 5. Create Dashboard Data (Chart Data - Last 14 Days)
        $this->command->info('Generating transactions and payments for the last 14 days...');
        
        for ($i = 13; $i >= 0; $i--) {
            // Randomly create 1-3 transactions per day
            $txnCount = rand(1, 3);
            for ($t = 0; $t < $txnCount; $t++) {
                $customer = $customers->random();
                $date = Carbon::now()->subDays($i)->subHours(rand(1, 8));
                
                $isJewelry = rand(1, 2) == 1;
                $itemName = $isJewelry ? $jewelryNames[array_rand($jewelryNames)] : $electronicsNames[array_rand($electronicsNames)];
                
                // Determine category
                if ($isJewelry) {
                    if (str_contains(strtolower($itemName), 'watch')) {
                        $catId = $watches->id;
                    } elseif (str_contains(strtolower($itemName), 'gold') || str_contains(strtolower($itemName), 'diamond')) {
                        $catId = $goldJewelry->id;
                    } else {
                        $catId = $silverJewelry->id;
                    }
                } else {
                    $catId = $electronics->id;
                }

                $item = Item::create([
                    'item_code' => uniqid('ITM-') . '-' . rand(1000, 9999),
                    'name' => $itemName,
                    'category_id' => $catId,
                    'safe_id' => rand(1, 2) == 1 ? $safe1->id : $safe2->id,
                    'appraised_value' => rand(5000, 25000),
                    'item_status' => 'stored',
                    'is_available' => false,
                ]);

                $loanAmount = $item->appraised_value * 0.8;

                $transaction = Transaction::create([
                    'customer_id' => $customer->id,
                    'user_id' => $teller->id,
                    'transaction_type' => 'pawn',
                    'loan_amount' => $loanAmount,
                    'interest_rate' => 3.00,
                    'term_days' => 30,
                    'transaction_date' => $date,
                    'maturity_date' => $date->copy()->addDays(30),
                    'status' => 'active',
                ]);

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'item_id' => $item->id,
                    'appraised_value' => $item->appraised_value,
                    'quantity' => 1,
                ]);

                // 30% chance they made a partial payment on the same day (unrealistic but good for chart data)
                if (rand(1, 100) <= 30) {
                    Payment::create([
                        'transaction_id' => $transaction->id,
                        'amount_paid' => rand(500, 2000),
                        'payment_type' => 'partial',
                        'payment_method' => 'cash',
                        'payment_date' => $date->copy()->addHours(2),
                        'interest_paid' => rand(100, 500),
                        'principal_paid' => rand(400, 1500),
                    ]);
                }
            }
        }

        // 6. Ensure some payments happen TODAY (for "Today's Collections")
        $this->command->info('Adding payments for today...');
        $activeTxn = Transaction::where('status', 'active')->inRandomOrder()->first();
        if ($activeTxn) {
            Payment::create([
                'transaction_id' => $activeTxn->id,
                'amount_paid' => 4500,
                'payment_type' => 'partial',
                'payment_method' => 'cash',
                'payment_date' => Carbon::now(),
                'interest_paid' => 500,
                'principal_paid' => 4000,
            ]);
        }

        // 7. Create Overdue / Maturing Transactions (For Action Needed Panel)
        $this->command->info('Creating upcoming and overdue maturities...');
        $maturityDates = [
            Carbon::now()->subDays(5), // Overdue
            Carbon::now()->addDays(2), // Maturing Soon
            Carbon::now()->addDays(6), // Maturing Soon
        ];

        foreach ($maturityDates as $matDate) {
            $customer = $customers->random();
            $txnDate = $matDate->copy()->subDays(30);

            $itemName = $jewelryNames[array_rand($jewelryNames)] . ' (Maturing)';
            
            if (str_contains(strtolower($itemName), 'watch')) {
                $catId = $watches->id;
            } elseif (str_contains(strtolower($itemName), 'gold') || str_contains(strtolower($itemName), 'diamond')) {
                $catId = $goldJewelry->id;
            } else {
                $catId = $silverJewelry->id;
            }

            $item = Item::create([
                'item_code' => 'ITM-MAT-' . rand(1000, 9999),
                'name' => $itemName,
                'category_id' => $catId,
                'safe_id' => $safe1->id,
                'appraised_value' => 15000,
                'item_status' => 'stored',
                'is_available' => false,
            ]);

            $transaction = Transaction::create([
                'customer_id' => $customer->id,
                'user_id' => $teller->id,
                'transaction_type' => 'pawn',
                'loan_amount' => 12000,
                'interest_rate' => 3.00,
                'term_days' => 30,
                'transaction_date' => $txnDate,
                'maturity_date' => $matDate,
                'status' => 'active',
            ]);

            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'item_id' => $item->id,
                'appraised_value' => $item->appraised_value,
                'quantity' => 1,
            ]);
        }

        // 8. Create a Pending Approval (For the Alert Banner)
        $this->command->info('Creating pending approval request...');
        $txForApproval = Transaction::where('status', 'active')->inRandomOrder()->first();
        if ($txForApproval) {
            Approval::firstOrCreate([
                'user_id' => $teller->id,
                'model_type' => Transaction::class,
                'model_id' => $txForApproval->id,
                'status' => 'pending',
            ], [
                'manager_id' => $admin->id,
                'action' => 'void_transaction',
                'payload' => ['reason' => 'Testing pending approvals.'],
            ]);
        }

        $this->command->info('✅ MockDataSeeder completed successfully!');
    }
}
