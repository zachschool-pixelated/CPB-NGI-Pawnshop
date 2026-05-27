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
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
 
class MockDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting Realistic Mock Data Seeder...');
 
        // 1. Ensure Users Exist (already created by DatabaseSeeder but we load them)
        $admin = User::where('role', 'admin')->first() ?? User::create([
            'name' => 'Admin User',
            'email' => 'admin@pawnshop.com',
            'role' => 'admin',
            'password' => Hash::make('password')
        ]);
        
        $teller = User::where('role', 'teller')->first() ?? User::create([
            'name' => 'Teller Staff',
            'email' => 'teller@pawnshop.com',
            'role' => 'teller',
            'password' => Hash::make('password')
        ]);
 
        $manager = User::where('role', 'manager')->first() ?? User::create([
            'name' => 'Manager Staff',
            'email' => 'manager@pawnshop.com',
            'role' => 'manager',
            'password' => Hash::make('password')
        ]);
 
        // 2. Ensure Categories Exist
        $goldJewelry = Category::firstOrCreate(['name' => 'Gold Jewelry'], ['description' => 'Gold rings, necklaces, bracelets, earrings']);
        $silverJewelry = Category::firstOrCreate(['name' => 'Silver Jewelry'], ['description' => 'Silver rings, necklaces, bracelets']);
        $watches = Category::firstOrCreate(['name' => 'Watches'], ['description' => 'Wristwatches, pocket watches']);
        $electronics = Category::firstOrCreate(['name' => 'Electronics'], ['description' => 'Phones, laptops, tablets, cameras']);
        $appliances = Category::firstOrCreate(['name' => 'Appliances'], ['description' => 'Small and large home appliances']);
        $instruments = Category::firstOrCreate(['name' => 'Musical Instruments'], ['description' => 'Guitars, keyboards, etc.']);
        $tools = Category::firstOrCreate(['name' => 'Tools & Equipment'], ['description' => 'Power tools, hand tools']);
        $others = Category::firstOrCreate(['name' => 'Others'], ['description' => 'Miscellaneous items']);
 
        // 3. Create Safes with Normal Names
        $safe1 = Safe::updateOrCreate(
            ['safe_code' => 'SAFE-001'],
            [
                'name' => 'Main Vault',
                'capacity' => 2000000,
                'items_capacity' => 150,
                'location' => 'Main Office (Back Room)'
            ]
        );
        $safe2 = Safe::updateOrCreate(
            ['safe_code' => 'SAFE-002'],
            [
                'name' => 'Front Display Case 1',
                'capacity' => 1000000,
                'items_capacity' => 50,
                'location' => 'Front Lobby Counter'
            ]
        );
 
        // Get or create location models
        $region = \App\Models\Region::firstOrCreate(['code' => 'R1', 'name' => 'Region I']);
        $province = \App\Models\Province::firstOrCreate(['region_id' => $region->id, 'code' => 'P1', 'name' => 'Province 1']);
        $city = \App\Models\City::firstOrCreate(['province_id' => $province->id, 'code' => 'C1', 'name' => 'City 1']);
        $barangay = \App\Models\Barangay::firstOrCreate(['city_id' => $city->id, 'code' => 'B1', 'name' => 'Barangay 1']);
 
        // 4. Create Normal Customers
        $customerNames = [
            ['first' => 'Maria', 'last' => 'Santos', 'email' => 'maria.santos@example.com', 'address' => '123 Main St, Poblacion'],
            ['first' => 'Juan', 'last' => 'Dela Cruz', 'email' => 'juan.delacruz@example.com', 'address' => '456 Oak Avenue, Barangay 2'],
            ['first' => 'Ana', 'last' => 'Reyes', 'email' => 'ana.reyes@example.com', 'address' => '789 Pine Road, City Center'],
            ['first' => 'Pedro', 'last' => 'Penduko', 'email' => 'pedro.penduko@example.com', 'address' => '12 Maple Lane, Subdivision A'],
            ['first' => 'Jose', 'last' => 'Garcia', 'email' => 'jose.garcia@example.com', 'address' => '34 Cedar Street, Barangay 3'],
            ['first' => 'Elena', 'last' => 'Soriano', 'email' => 'elena.soriano@example.com', 'address' => '56 Birch Boulevard, Heights'],
            ['first' => 'Manuel', 'last' => 'Roxas', 'email' => 'manuel.roxas@example.com', 'address' => '78 Redwood Drive, San Jose']
        ];
 
        $customers = collect();
        foreach ($customerNames as $index => $cData) {
            $customers->push(Customer::updateOrCreate(
                ['email' => $cData['email']],
                [
                    'first_name' => $cData['first'],
                    'last_name' => $cData['last'],
                    'phone_number' => '0912' . str_pad($index, 7, '3', STR_PAD_LEFT),
                    'address_line' => $cData['address'],
                    'id_type' => 'national_id',
                    'id_number' => 'NID-REG-' . (1000 + $index),
                    'region_id' => $region->id,
                    'province_id' => $province->id,
                    'city_id' => $city->id,
                    'barangay_id' => $barangay->id,
                ]
            ));
        }
 
        // 5. Normal Item Master Lists
        $normalItems = [
            'Gold Jewelry' => [
                '18K Gold Necklace (10g)',
                '24K Gold Bracelet',
                '14K Diamond Engagement Ring',
                'Gold Wedding Band (5g)',
                '18K Gold Pendant'
            ],
            'Silver Jewelry' => [
                'Sterling Silver Ring',
                'Silver Charm Bracelet',
                'Silver Pendant Necklace',
                'Silver Hoop Earrings',
                '925 Silver Bangle'
            ],
            'Watches' => [
                'Rolex Submariner Watch',
                'Seiko Chronograph Watch',
                'Apple Watch Series 7',
                'Casio G-Shock Watch',
                'Omega Speedmaster Watch'
            ],
            'Electronics' => [
                'iPhone 13 Pro Max (128GB)',
                'Samsung Galaxy S22 (256GB)',
                'iPad Pro 12.9" (256GB)',
                'MacBook Pro M1 (16GB RAM)',
                'Sony PlayStation 5 Console'
            ],
            'Appliances' => [
                'Samsung Inverter Refrigerator',
                'Panasonic Microwave Oven',
                'Dyson Vacuum Cleaner',
                'Sharp Air Purifier',
                'Philips Air Fryer'
            ],
            'Musical Instruments' => [
                'Fender Stratocaster Electric Guitar',
                'Yamaha Keyboard (61 Keys)',
                'Taylor Acoustic Guitar',
                'Pearl Drum Kit Cymbals',
                'Yamaha Flute'
            ],
            'Tools & Equipment' => [
                'Dewalt Power Drill Set',
                'Bosch Angle Grinder',
                'Stanley 150-Piece Socket Set',
                'Makita Circular Saw',
                'Black & Decker Sander'
            ],
            'Others' => [
                'Louis Vuitton Leather Wallet',
                'Gucci Sunglasses (Black)',
                'Ray-Ban Aviator Sunglasses',
                'Samsonite Travel Suitcase',
                'Montblanc Ballpoint Pen'
            ]
        ];
 
        // Helper function to pick a category and name
        $getItemDetails = function() use ($normalItems, $goldJewelry, $silverJewelry, $watches, $electronics, $appliances, $instruments, $tools, $others) {
            $categoriesList = array_keys($normalItems);
            $catName = $categoriesList[array_rand($categoriesList)];
            $itemName = $normalItems[$catName][array_rand($normalItems[$catName])];
            
            $catId = match($catName) {
                'Gold Jewelry' => $goldJewelry->id,
                'Silver Jewelry' => $silverJewelry->id,
                'Watches' => $watches->id,
                'Electronics' => $electronics->id,
                'Appliances' => $appliances->id,
                'Musical Instruments' => $instruments->id,
                'Tools & Equipment' => $tools->id,
                'Others' => $others->id,
            };
            return [$itemName, $catId];
        };
 
        // 6. Generate Active Pawn Transactions (10 rows)
        $this->command->info('Generating 10 active pawn transactions...');
        for ($i = 0; $i < 10; $i++) {
            $customer = $customers->random();
            list($name, $catId) = $getItemDetails();
            $val = rand(10000, 50000);
            
            $item = Item::create([
                'item_code' => 'ITM-ACT-' . rand(10000, 99999),
                'name' => $name,
                'category_id' => $catId,
                'safe_id' => rand(1, 2) === 1 ? $safe1->id : $safe2->id,
                'appraised_value' => $val,
                'condition' => 'excellent',
                'item_status' => 'stored',
                'is_available' => false,
            ]);
 
            $loan = $val * 0.8;
            $date = Carbon::now()->subDays(rand(5, 25));
 
            $txn = Transaction::create([
                'customer_id' => $customer->id,
                'user_id' => $teller->id,
                'transaction_type' => 'pawn',
                'loan_amount' => $loan,
                'interest_rate' => 3.00,
                'term_days' => 30,
                'transaction_date' => $date,
                'maturity_date' => $date->copy()->addDays(30),
                'status' => 'active',
                'notes' => 'Active pawn transaction'
            ]);
 
            TransactionItem::create([
                'transaction_id' => $txn->id,
                'item_id' => $item->id,
                'appraised_value' => $val,
                'quantity' => 1
            ]);
        }
 
        // 7. Generate Renewed Transactions (5 rows, with interest-only or partial payments)
        $this->command->info('Generating 5 renewed transactions...');
        for ($i = 0; $i < 5; $i++) {
            $customer = $customers->random();
            list($name, $catId) = $getItemDetails();
            $val = rand(12000, 45000);
            
            $item = Item::create([
                'item_code' => 'ITM-REN-' . rand(10000, 99999),
                'name' => $name,
                'category_id' => $catId,
                'safe_id' => $safe1->id,
                'appraised_value' => $val,
                'condition' => 'good',
                'item_status' => 'stored',
                'is_available' => false,
            ]);
 
            $loan = $val * 0.8;
            $date = Carbon::now()->subDays(rand(35, 50));
            $renewalDate = $date->copy()->addDays(30);
 
            $txn = Transaction::create([
                'customer_id' => $customer->id,
                'user_id' => $teller->id,
                'transaction_type' => 'pawn',
                'loan_amount' => $loan,
                'interest_rate' => 3.00,
                'term_days' => 30,
                'transaction_date' => $date,
                'maturity_date' => $renewalDate->copy()->addDays(30), // Extended
                'status' => 'renewed',
                'notes' => 'Pawn renewed'
            ]);
 
            TransactionItem::create([
                'transaction_id' => $txn->id,
                'item_id' => $item->id,
                'appraised_value' => $val,
                'quantity' => 1
            ]);
 
            // Create payment representing interest payment for renewal
            $interest = $txn->calculateInterest();
            Payment::create([
                'transaction_id' => $txn->id,
                'amount_paid' => $interest,
                'payment_type' => 'interest',
                'payment_method' => 'cash',
                'payment_date' => $renewalDate,
                'interest_paid' => $interest,
                'principal_paid' => 0.00,
                'notes' => 'Payment for renewal term extension',
                'receipt_number' => 'RCT-' . $renewalDate->format('Ymd') . '-R' . str_pad($i + 1, 4, '0', STR_PAD_LEFT)
            ]);
        }
 
        // 8. Generate Redeemed Transactions (5 rows, with full payments and redemption_date set)
        $this->command->info('Generating 5 redeemed transactions...');
        for ($i = 0; $i < 5; $i++) {
            $customer = $customers->random();
            list($name, $catId) = $getItemDetails();
            $val = rand(10000, 30000);
            
            $item = Item::create([
                'item_code' => 'ITM-RED-' . rand(10000, 99999),
                'name' => $name,
                'category_id' => $catId,
                'safe_id' => $safe2->id,
                'appraised_value' => $val,
                'condition' => 'excellent',
                'item_status' => 'released', // item status for redeemed is released
                'is_available' => false,
            ]);
 
            $loan = $val * 0.8;
            $date = Carbon::now()->subDays(rand(40, 60));
            $redeemedDate = $date->copy()->addDays(20);
 
            $txn = Transaction::create([
                'customer_id' => $customer->id,
                'user_id' => $teller->id,
                'transaction_type' => 'pawn',
                'loan_amount' => $loan,
                'interest_rate' => 3.00,
                'term_days' => 30,
                'transaction_date' => $date,
                'maturity_date' => $date->copy()->addDays(30),
                'redemption_date' => $redeemedDate,
                'status' => 'redeemed',
                'notes' => 'Redeemed early'
            ]);
 
            TransactionItem::create([
                'transaction_id' => $txn->id,
                'item_id' => $item->id,
                'appraised_value' => $val,
                'quantity' => 1
            ]);
 
            $interest = $txn->calculateInterest();
            $totalDue = $loan + $interest;
            Payment::create([
                'transaction_id' => $txn->id,
                'amount_paid' => $totalDue,
                'payment_type' => 'redemption',
                'payment_method' => 'cash',
                'payment_date' => $redeemedDate,
                'interest_paid' => $interest,
                'principal_paid' => $loan,
                'notes' => 'Full redemption payment',
                'receipt_number' => 'RCT-' . $redeemedDate->format('Ymd') . '-RD' . str_pad($i + 1, 4, '0', STR_PAD_LEFT)
            ]);
        }
 
        // 9. Generate Forfeited (Available for Sale) Transactions (5 rows, items marked for_sale)
        $this->command->info('Generating 5 forfeited transactions (items available for sale)...');
        for ($i = 0; $i < 5; $i++) {
            $customer = $customers->random();
            list($name, $catId) = $getItemDetails();
            $val = rand(15000, 35000);
            
            $item = Item::create([
                'item_code' => 'ITM-FOR-' . rand(10000, 99999),
                'name' => $name,
                'category_id' => $catId,
                'safe_id' => $safe2->id,
                'appraised_value' => $val,
                'condition' => 'good',
                'item_status' => 'for_sale', // marked for sale in display case
                'is_available' => true,
            ]);
 
            $loan = $val * 0.8;
            $date = Carbon::now()->subDays(rand(70, 90));
 
            $txn = Transaction::create([
                'customer_id' => $customer->id,
                'user_id' => $teller->id,
                'transaction_type' => 'pawn',
                'loan_amount' => $loan,
                'interest_rate' => 3.00,
                'term_days' => 30,
                'transaction_date' => $date,
                'maturity_date' => $date->copy()->addDays(30),
                'status' => 'forfeited',
                'notes' => 'Pawn forfeited due to maturity expiry'
            ]);
 
            TransactionItem::create([
                'transaction_id' => $txn->id,
                'item_id' => $item->id,
                'appraised_value' => $val,
                'quantity' => 1
            ]);
        }
 
        // 10. Generate Sold Transactions (5 rows, items sold via POS, Sale/SaleItem created)
        $this->command->info('Generating 5 sold transactions (POS Sales history)...');
        for ($i = 0; $i < 5; $i++) {
            $customer = $customers->random();
            list($name, $catId) = $getItemDetails();
            $val = rand(15000, 40000);
            
            $item = Item::create([
                'item_code' => 'ITM-SLD-' . rand(10000, 99999),
                'name' => $name,
                'category_id' => $catId,
                'safe_id' => $safe2->id,
                'appraised_value' => $val,
                'condition' => 'good',
                'item_status' => 'sold', // sold status
                'is_available' => false,
                'selling_price' => $val
            ]);
 
            $loan = $val * 0.8;
            $date = Carbon::now()->subDays(rand(60, 80));
            $soldDate = Carbon::now()->subDays(rand(1, 10));
 
            $txn = Transaction::create([
                'customer_id' => $customer->id,
                'user_id' => $teller->id,
                'transaction_type' => 'pawn',
                'loan_amount' => $loan,
                'interest_rate' => 3.00,
                'term_days' => 30,
                'transaction_date' => $date,
                'maturity_date' => $date->copy()->addDays(30),
                'status' => 'sold',
                'notes' => 'Pawn forfeited and sold via POS'
            ]);
 
            TransactionItem::create([
                'transaction_id' => $txn->id,
                'item_id' => $item->id,
                'appraised_value' => $val,
                'quantity' => 1
            ]);
 
            // Create POS Sale entry
            $sale = Sale::create([
                'user_id' => $admin->id,
                'total' => $val,
                'amount_tendered' => $val + rand(0, 1000),
                'change' => 0.00, // calculated from tendered - total
                'sold_at' => $soldDate,
                'receipt_number' => 'POS-' . $soldDate->format('Ymd') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT)
            ]);
            $sale->update(['change' => $sale->amount_tendered - $sale->total]);
 
            SaleItem::create([
                'sale_id' => $sale->id,
                'item_id' => $item->id,
                'price' => $val
            ]);
        }
 
        // 11. Generate Voided Transactions with Approved Void Request approvals (3 rows)
        $this->command->info('Generating 3 voided transactions...');
        for ($i = 0; $i < 3; $i++) {
            $customer = $customers->random();
            list($name, $catId) = $getItemDetails();
            $val = rand(10000, 25000);
            
            $item = Item::create([
                'item_code' => 'ITM-VOI-' . rand(10000, 99999),
                'name' => $name,
                'category_id' => $catId,
                'safe_id' => $safe1->id,
                'appraised_value' => $val,
                'condition' => 'fair',
                'item_status' => 'voided',
                'is_available' => false,
            ]);
 
            $loan = $val * 0.8;
            $date = Carbon::now()->subDays(rand(10, 20));
 
            $txn = Transaction::create([
                'customer_id' => $customer->id,
                'user_id' => $teller->id,
                'transaction_type' => 'pawn',
                'loan_amount' => $loan,
                'interest_rate' => 3.00,
                'term_days' => 30,
                'transaction_date' => $date,
                'maturity_date' => $date->copy()->addDays(30),
                'status' => 'voided',
                'notes' => 'Voided by manager request'
            ]);
 
            TransactionItem::create([
                'transaction_id' => $txn->id,
                'item_id' => $item->id,
                'appraised_value' => $val,
                'quantity' => 1
            ]);
 
            // Create APPROVED approval request
            Approval::create([
                'user_id' => $teller->id,
                'manager_id' => $manager->id,
                'model_type' => Transaction::class,
                'model_id' => $txn->id,
                'action' => 'void_transaction',
                'status' => 'approved',
                'payload' => ['reason' => 'Customer changed mind immediately after signing receipt.'],
                'notes' => 'Approved void. Item returned and transaction cleared.'
            ]);
        }
 
        // 12. Create Active Pawns with Pending and Rejected Void Requests
        $this->command->info('Generating pending and rejected void approval requests...');
        
        // 12a. Pending Approval Request (2 rows)
        for ($i = 0; $i < 2; $i++) {
            $customer = $customers->random();
            list($name, $catId) = $getItemDetails();
            $val = rand(15000, 30000);
            
            $item = Item::create([
                'item_code' => 'ITM-PND-' . rand(10000, 99999),
                'name' => $name,
                'category_id' => $catId,
                'safe_id' => $safe1->id,
                'appraised_value' => $val,
                'condition' => 'excellent',
                'item_status' => 'stored',
                'is_available' => false,
            ]);
 
            $loan = $val * 0.8;
            $date = Carbon::now()->subDays(2);
 
            $txn = Transaction::create([
                'customer_id' => $customer->id,
                'user_id' => $teller->id,
                'transaction_type' => 'pawn',
                'loan_amount' => $loan,
                'interest_rate' => 3.00,
                'term_days' => 30,
                'transaction_date' => $date,
                'maturity_date' => $date->copy()->addDays(30),
                'status' => 'active',
                'notes' => 'Pawn active - Void request pending'
            ]);
 
            TransactionItem::create([
                'transaction_id' => $txn->id,
                'item_id' => $item->id,
                'appraised_value' => $val,
                'quantity' => 1
            ]);
 
            Approval::create([
                'user_id' => $teller->id,
                'model_type' => Transaction::class,
                'model_id' => $txn->id,
                'action' => 'void_transaction',
                'status' => 'pending',
                'payload' => ['reason' => 'Incorrect appraised value keyed in by teller.'],
            ]);
        }
 
        // 12b. Rejected Approval Request (2 rows)
        for ($i = 0; $i < 2; $i++) {
            $customer = $customers->random();
            list($name, $catId) = $getItemDetails();
            $val = rand(10000, 20000);
            
            $item = Item::create([
                'item_code' => 'ITM-REJ-' . rand(10000, 99999),
                'name' => $name,
                'category_id' => $catId,
                'safe_id' => $safe1->id,
                'appraised_value' => $val,
                'condition' => 'good',
                'item_status' => 'stored',
                'is_available' => false,
            ]);
 
            $loan = $val * 0.8;
            $date = Carbon::now()->subDays(4);
 
            $txn = Transaction::create([
                'customer_id' => $customer->id,
                'user_id' => $teller->id,
                'transaction_type' => 'pawn',
                'loan_amount' => $loan,
                'interest_rate' => 3.00,
                'term_days' => 30,
                'transaction_date' => $date,
                'maturity_date' => $date->copy()->addDays(30),
                'status' => 'active',
                'notes' => 'Pawn active - Void request rejected'
            ]);
 
            TransactionItem::create([
                'transaction_id' => $txn->id,
                'item_id' => $item->id,
                'appraised_value' => $val,
                'quantity' => 1
            ]);
 
            Approval::create([
                'user_id' => $teller->id,
                'manager_id' => $manager->id,
                'model_type' => Transaction::class,
                'model_id' => $txn->id,
                'action' => 'void_transaction',
                'status' => 'rejected',
                'payload' => ['reason' => 'Customer requested cancellation 2 days after receipt.'],
                'notes' => 'Rejected. Voids are only permitted within 24 hours of transaction creation.'
            ]);
        }
 
        $this->command->info('✅ Realistic MockDataSeeder completed successfully!');
    }
}
