<?php

use App\Models\User;
use App\Models\Category;
use App\Models\Safe;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Payment;
use App\Models\Sale;
use Carbon\Carbon;

test('unauthorized roles are redirected with error', function () {
    $teller = User::factory()->create(['role' => 'teller']);
    $cashier = User::factory()->create(['role' => 'cashier']);

    foreach ([$teller, $cashier] as $user) {
        $this->actingAs($user)->get('/reports')->assertRedirect('/dashboard')->assertSessionHas('error');
        $this->actingAs($user)->get('/reports/summary')->assertRedirect('/dashboard')->assertSessionHas('error');
        $this->actingAs($user)->get('/reports/transactions')->assertRedirect('/dashboard')->assertSessionHas('error');
        $this->actingAs($user)->get('/reports/payments')->assertRedirect('/dashboard')->assertSessionHas('error');
        $this->actingAs($user)->get('/reports/sales')->assertRedirect('/dashboard')->assertSessionHas('error');
        $this->actingAs($user)->get('/reports/inventory')->assertRedirect('/dashboard')->assertSessionHas('error');
        $this->actingAs($user)->get('/reports/export-pdf/summary')->assertRedirect('/dashboard')->assertSessionHas('error');
    }
});

test('admin and manager can access reports dashboard and standard reports', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $manager = User::factory()->create(['role' => 'manager']);

    foreach ([$admin, $manager] as $user) {
        $this->actingAs($user)->get('/reports')->assertStatus(200);
        $this->actingAs($user)->get('/reports/summary')->assertStatus(200);
        $this->actingAs($user)->get('/reports/transactions')->assertStatus(200);
        $this->actingAs($user)->get('/reports/payments')->assertStatus(200);
        $this->actingAs($user)->get('/reports/sales')->assertStatus(200);
        $this->actingAs($user)->get('/reports/inventory')->assertStatus(200);
    }
});

test('calculations are precise and filter-bound', function () {
    $user = User::factory()->create(['role' => 'manager']);
    
    // Seed locations
    $region = \App\Models\Region::firstOrCreate(['code' => 'R1', 'name' => 'Region I']);
    $province = \App\Models\Province::firstOrCreate(['region_id' => $region->id, 'code' => 'P1', 'name' => 'Province 1']);
    $city = \App\Models\City::firstOrCreate(['province_id' => $province->id, 'code' => 'C1', 'name' => 'City 1']);
    $barangay = \App\Models\Barangay::firstOrCreate(['city_id' => $city->id, 'code' => 'B1', 'name' => 'Barangay 1']);

    $customer = Customer::create([
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'testuser@example.com',
        'phone_number' => '09123456789',
        'region_id' => $region->id,
        'province_id' => $province->id,
        'city_id' => $city->id,
        'barangay_id' => $barangay->id,
        'id_type' => 'national_id',
        'id_number' => 'NID-99999',
    ]);

    $category = Category::create(['name' => 'Gold Jewelry']);
    $safe = Safe::create([
        'safe_code' => 'SAFE-TEST-99',
        'name' => 'Test Safe',
        'location' => 'Main Vault',
        'items_capacity' => 10,
        'capacity' => 50000.00,
    ]);

    // Seed Transactions
    // 1. Transaction inside filter range (May 2026)
    $txn1 = Transaction::create([
        'customer_id' => $customer->id,
        'user_id' => $user->id,
        'transaction_type' => 'pawn',
        'loan_amount' => 1000.00,
        'interest_rate' => 3.00,
        'term_days' => 30,
        'transaction_date' => Carbon::create(2026, 5, 10, 12, 0, 0),
        'status' => 'active',
    ]);
    // 2. Transaction outside filter range (April 2026)
    $txn2 = Transaction::create([
        'customer_id' => $customer->id,
        'user_id' => $user->id,
        'transaction_type' => 'pawn',
        'loan_amount' => 5000.00,
        'interest_rate' => 3.00,
        'term_days' => 30,
        'transaction_date' => Carbon::create(2026, 4, 15, 12, 0, 0),
        'status' => 'active',
    ]);

    // Seed Payments
    // 1. Payment inside range (May 2026)
    $payment1 = Payment::create([
        'transaction_id' => $txn1->id,
        'amount_paid' => 500.00,
        'payment_type' => 'partial',
        'payment_method' => 'cash',
        'payment_date' => Carbon::create(2026, 5, 12, 10, 0, 0),
        'principal_paid' => 400.00,
        'interest_paid' => 100.00,
        'penalty_paid' => 0.00,
        'service_charge' => 10.00,
    ]);
    // 2. Payment outside range (April 2026)
    $payment2 = Payment::create([
        'transaction_id' => $txn2->id,
        'amount_paid' => 1000.00,
        'payment_type' => 'partial',
        'payment_method' => 'cash',
        'payment_date' => Carbon::create(2026, 4, 20, 10, 0, 0),
        'principal_paid' => 800.00,
        'interest_paid' => 200.00,
        'penalty_paid' => 0.00,
        'service_charge' => 20.00,
    ]);

    // Seed Sales
    // 1. Sale inside range (May 2026)
    $sale1 = Sale::create([
        'user_id' => $user->id,
        'total' => 1500.00,
        'amount_tendered' => 1500.00,
        'change' => 0.00,
        'sold_at' => Carbon::create(2026, 5, 14, 14, 0, 0),
        'receipt_number' => 'POS-20260514-0001',
    ]);
    // 2. Sale outside range (April 2026)
    $sale2 = Sale::create([
        'user_id' => $user->id,
        'total' => 3000.00,
        'amount_tendered' => 3000.00,
        'change' => 0.00,
        'sold_at' => Carbon::create(2026, 4, 25, 14, 0, 0),
        'receipt_number' => 'POS-20260425-0001',
    ]);

    // Seed Items for Inventory Movements
    // Item 1: Pawned in April (beg balance in May)
    $item1 = new Item([
        'item_code' => 'ITM-0001',
        'name' => 'Item 1',
        'category_id' => $category->id,
        'safe_id' => $safe->id,
        'appraised_value' => 2000.00,
        'item_status' => 'stored',
    ]);
    $item1->timestamps = false;
    $item1->created_at = Carbon::create(2026, 4, 1, 10, 0, 0);
    $item1->updated_at = Carbon::create(2026, 4, 1, 10, 0, 0);
    $item1->save();

    // Item 2: Pawned in May (added in May)
    $item2 = new Item([
        'item_code' => 'ITM-0002',
        'name' => 'Item 2',
        'category_id' => $category->id,
        'safe_id' => $safe->id,
        'appraised_value' => 3000.00,
        'item_status' => 'stored',
    ]);
    $item2->timestamps = false;
    $item2->created_at = Carbon::create(2026, 5, 5, 10, 0, 0);
    $item2->updated_at = Carbon::create(2026, 5, 5, 10, 0, 0);
    $item2->save();

    // Item 3: Pawned in April, Redeemed in May (beg balance in May, removed/redeemed in May)
    $item3 = new Item([
        'item_code' => 'ITM-0003',
        'name' => 'Item 3',
        'category_id' => $category->id,
        'safe_id' => $safe->id,
        'appraised_value' => 4000.00,
        'item_status' => 'redeemed',
    ]);
    $item3->timestamps = false;
    $item3->created_at = Carbon::create(2026, 4, 1, 10, 0, 0);
    $item3->updated_at = Carbon::create(2026, 5, 15, 10, 0, 0);
    $item3->save();

    // Check transactions report with filter
    $response = $this->actingAs($user)
        ->get('/reports/transactions?start_date=2026-05-01&end_date=2026-05-31');
    $response->assertStatus(200);
    $response->assertViewHas('totalLoanReleased', 1000.00);
    expect($response->viewData('transactions')->count())->toBe(1);

    // Check payments report with filter
    $response = $this->actingAs($user)
        ->get('/reports/payments?start_date=2026-05-01&end_date=2026-05-31');
    $response->assertStatus(200);
    $response->assertViewHas('totalCollected', 500.00);
    expect($response->viewData('payments')->count())->toBe(1);

    // Check sales report with filter
    $response = $this->actingAs($user)
        ->get('/reports/sales?start_date=2026-05-01&end_date=2026-05-31');
    $response->assertStatus(200);
    $response->assertViewHas('totalSales', 1500.00);
    expect($response->viewData('sales')->count())->toBe(1);

    // Check summary report with filter
    $response = $this->actingAs($user)
        ->get('/reports/summary?start_date=2026-05-01&end_date=2026-05-31');
    $response->assertStatus(200);
    $summaryData = $response->viewData('summaryData');
    expect((float)$summaryData['total_principal'])->toEqual(400.00);
    expect((float)$summaryData['total_interest'])->toEqual(100.00);
    expect((float)$summaryData['total_service_charge'])->toEqual(10.00);
    expect((float)$summaryData['net_collection'])->toEqual(500.00);

    // Check inventory report with filter
    $response = $this->actingAs($user)
        ->get('/reports/inventory?start_date=2026-05-01&end_date=2026-05-31');
    $response->assertStatus(200);
    $inventory = $response->viewData('inventory');
    // Category: Gold Jewelry
    expect($inventory['Gold Jewelry']['beg'])->toBe(2); // item1 and item3 were stored at the beginning of May
    expect($inventory['Gold Jewelry']['add'])->toBe(1); // item2 was added in May
    expect($inventory['Gold Jewelry']['minus'])->toBe(1); // item3 was redeemed/removed in May
    expect($inventory['Gold Jewelry']['end'])->toBe(2); // 2 + 1 - 1 = 2
});

test('pdf exports output successful pdf stream', function () {
    $user = User::factory()->create(['role' => 'manager']);

    $pdfTypes = ['transactions', 'payments', 'sales', 'inventory', 'summary'];

    foreach ($pdfTypes as $type) {
        $response = $this->actingAs($user)
            ->get("/reports/export-pdf/{$type}?start_date=2026-05-01&end_date=2026-05-31");
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
});
