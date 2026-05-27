<?php

use App\Models\User;
use App\Models\Category;
use App\Models\Safe;
use App\Models\Customer;
use App\Models\Region;
use App\Models\Province;
use App\Models\City;
use App\Models\Barangay;
use App\Models\Transaction;

test('teller can view pawn wizard form', function () {
    $teller = User::factory()->create(['role' => 'teller']);

    $response = $this
        ->actingAs($teller)
        ->get('/pawn-wizard');

    $response->assertStatus(200);
});

test('pawn wizard store action succeeds with existing customer', function () {
    $teller = User::factory()->create(['role' => 'teller']);

    $region = Region::create(['code' => 'R1', 'name' => 'Region I']);
    $province = Province::create(['region_id' => $region->id, 'code' => 'P1', 'name' => 'Province 1']);
    $city = City::create(['province_id' => $province->id, 'code' => 'C1', 'name' => 'City 1']);
    $barangay = Barangay::create(['city_id' => $city->id, 'code' => 'B1', 'name' => 'Barangay 1']);

    $customer = Customer::create([
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'phone_number' => '09123456789',
        'region_id' => $region->id,
        'province_id' => $province->id,
        'city_id' => $city->id,
        'barangay_id' => $barangay->id,
        'id_type' => 'national_id',
        'id_number' => 'NID-5555',
    ]);

    $category = Category::create(['name' => 'Electronics']);

    // Create an active safe
    $safe = Safe::create([
        'safe_code' => 'SAFE-WIZ-01',
        'name' => 'Safe 1',
        'location' => 'Store Front',
        'capacity' => 1000000,
        'items_capacity' => 1000,
        'status' => 'active',
    ]);

    $response = $this
        ->actingAs($teller)
        ->post('/pawn-wizard', [
            'customer_type' => 'existing',
            'customer_id' => $customer->id,
            'item_name' => 'iPhone 15',
            'item_description' => 'Brand new in box',
            'category_id' => $category->id,
            'assessed_value' => 50000,
            'condition' => 'excellent',
            'loan_percentage' => 60,
            'loan_amount' => 30000,
            'interest_rate' => 5.0,
            'term_days' => 30,
        ]);

    $response->assertSessionHasNoErrors();

    $transaction = Transaction::latest()->first();
    expect($transaction)->not->toBeNull();
    expect((float)$transaction->loan_amount)->toEqual(30000);
    expect($transaction->customer_id)->toEqual($customer->id);

    $response->assertRedirect();
});

test('pawn wizard store action succeeds with new customer', function () {
    $teller = User::factory()->create(['role' => 'teller']);

    $region = Region::create(['code' => 'R1', 'name' => 'Region I']);
    $province = Province::create(['region_id' => $region->id, 'code' => 'P1', 'name' => 'Province 1']);
    $city = City::create(['province_id' => $province->id, 'code' => 'C1', 'name' => 'City 1']);
    $barangay = Barangay::create(['city_id' => $city->id, 'code' => 'B1', 'name' => 'Barangay 1']);

    $category = Category::create(['name' => 'Electronics']);

    // Create an active safe
    $safe = Safe::create([
        'safe_code' => 'SAFE-WIZ-02',
        'name' => 'Safe 2',
        'location' => 'Store Front',
        'capacity' => 1000000,
        'items_capacity' => 1000,
        'status' => 'active',
    ]);

    $response = $this
        ->actingAs($teller)
        ->post('/pawn-wizard', [
            'customer_type' => 'new',
            'first_name' => 'Bobby',
            'last_name' => 'Brown',
            'phone_number' => '09887766554',
            'email' => 'bobby@example.com',
            'region_id' => $region->id,
            'province_id' => $province->id,
            'city_id' => $city->id,
            'barangay_id' => $barangay->id,
            'id_type' => 'passport',
            'id_number' => 'PASS-9876',
            'item_name' => 'MacBook Air',
            'category_id' => $category->id,
            'assessed_value' => 60000,
            'condition' => 'good',
            'loan_percentage' => 50,
            'loan_amount' => 30000,
            'interest_rate' => 4.5,
            'term_days' => 60,
        ]);

    $response->assertSessionHasNoErrors();

    $customer = Customer::where('email', 'bobby@example.com')->first();
    expect($customer)->not->toBeNull();
    expect($customer->first_name)->toEqual('Bobby');

    $transaction = Transaction::latest()->first();
    expect($transaction)->not->toBeNull();
    expect($transaction->customer_id)->toEqual($customer->id);

    $response->assertRedirect();
});

test('pawn wizard store fails validation when required fields are missing', function () {
    $teller = User::factory()->create(['role' => 'teller']);

    $response = $this
        ->actingAs($teller)
        ->post('/pawn-wizard', [
            'customer_type' => 'new',
            // Missing first_name, last_name, etc.
        ]);

    $response->assertSessionHasErrors(['first_name', 'last_name', 'phone_number', 'item_name', 'category_id', 'assessed_value', 'condition', 'loan_percentage', 'loan_amount', 'interest_rate', 'term_days']);
});

test('pawn wizard AJAX location endpoints work properly', function () {
    $teller = User::factory()->create(['role' => 'teller']);

    $region = Region::create(['code' => 'R1', 'name' => 'Region I']);
    $province = Province::create(['region_id' => $region->id, 'code' => 'P1', 'name' => 'Province 1']);
    $city = City::create(['province_id' => $province->id, 'code' => 'C1', 'name' => 'City 1']);
    $barangay = Barangay::create(['city_id' => $city->id, 'code' => 'B1', 'name' => 'Barangay 1']);

    // Provinces endpoint
    $response = $this->actingAs($teller)->get("/api/provinces/{$region->id}");
    $response->assertStatus(200);
    $response->assertJsonFragment(['name' => 'Province 1']);

    // Cities endpoint
    $response = $this->actingAs($teller)->get("/api/cities/{$province->id}");
    $response->assertStatus(200);
    $response->assertJsonFragment(['name' => 'City 1']);

    // Barangays endpoint
    $response = $this->actingAs($teller)->get("/api/barangays/{$city->id}");
    $response->assertStatus(200);
    $response->assertJsonFragment(['name' => 'Barangay 1']);
});

test('pawn wizard AJAX customer search endpoint works', function () {
    $teller = User::factory()->create(['role' => 'teller']);

    $region = Region::create(['code' => 'R1', 'name' => 'Region I']);
    $province = Province::create(['region_id' => $region->id, 'code' => 'P1', 'name' => 'Province 1']);
    $city = City::create(['province_id' => $province->id, 'code' => 'C1', 'name' => 'City 1']);
    $barangay = Barangay::create(['city_id' => $city->id, 'code' => 'B1', 'name' => 'Barangay 1']);

    $customer = Customer::create([
        'first_name' => 'Samantha',
        'last_name' => 'Smith',
        'phone_number' => '09223334444',
        'region_id' => $region->id,
        'province_id' => $province->id,
        'city_id' => $city->id,
        'barangay_id' => $barangay->id,
        'id_type' => 'passport',
        'id_number' => 'PASS-SEARCH-1',
    ]);

    $response = $this->actingAs($teller)->get('/api/customers/search?q=Samantha');
    $response->assertStatus(200);
    $response->assertJsonFragment(['name' => 'Samantha Smith']);
});
