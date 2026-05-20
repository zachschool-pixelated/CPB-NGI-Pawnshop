<?php

use App\Models\User;
use App\Models\Safe;
use App\Models\Item;
use App\Models\Category;

test('manager can view safes list', function () {
    $user = User::factory()->create(['role' => 'manager']);

    $response = $this
        ->actingAs($user)
        ->get('/safes');

    $response->assertStatus(200);
});

test('manager can edit and update safe with no items', function () {
    $user = User::factory()->create(['role' => 'manager']);
    
    $safe = Safe::create([
        'safe_code' => 'SAFE-TEST-001',
        'name' => 'Empty Safe',
        'location' => 'Vault Room A',
        'items_capacity' => 10,
        'capacity' => 50000.00,
    ]);

    // Check we can access edit page
    $response = $this
        ->actingAs($user)
        ->get("/safes/{$safe->id}/edit");
    $response->assertStatus(200);

    // Check we can update
    $response = $this
        ->actingAs($user)
        ->put("/safes/{$safe->id}", [
            'name' => 'Updated Safe Name',
            'location' => 'Vault Room B',
            'items_capacity' => 12,
            'capacity' => 60000.00,
        ]);
    
    $response->assertRedirect("/safes/{$safe->id}");
    $safe->refresh();
    expect($safe->location)->toBe('Vault Room B');
    expect($safe->items_capacity)->toBe(12);
});

test('manager cannot edit or update safe with items', function () {
    $user = User::factory()->create(['role' => 'manager']);
    
    $safe = Safe::create([
        'safe_code' => 'SAFE-TEST-002',
        'name' => 'Filled Safe',
        'location' => 'Vault Room A',
        'items_capacity' => 10,
        'capacity' => 50000.00,
    ]);

    $category = Category::create([
        'name' => 'Test Category',
    ]);

    // Create an item inside this safe
    Item::create([
        'item_code' => 'ITEM-TEST-001',
        'name' => 'Gold Ring',
        'category_id' => $category->id,
        'safe_id' => $safe->id,
        'appraised_value' => 1000.00,
    ]);

    // Try to access edit page
    $response = $this
        ->actingAs($user)
        ->get("/safes/{$safe->id}/edit");
    
    $response->assertRedirect('/safes');
    $response->assertSessionHas('error', 'Cannot edit safe with items. Please move items first.');

    // Try to update
    $response = $this
        ->actingAs($user)
        ->put("/safes/{$safe->id}", [
            'name' => 'Should Not Update',
            'location' => 'Vault Room B',
            'items_capacity' => 15,
            'capacity' => 70000.00,
        ]);

    $response->assertRedirect('/safes');
    $response->assertSessionHas('error', 'Cannot edit safe with items. Please move items first.');
    
    $safe->refresh();
    expect($safe->items_capacity)->toBe(10);
});

test('manager can delete safe with no items', function () {
    $user = User::factory()->create(['role' => 'manager']);
    
    $safe = Safe::create([
        'safe_code' => 'SAFE-TEST-003',
        'name' => 'Delete Me',
        'location' => 'Vault Room A',
        'items_capacity' => 10,
        'capacity' => 50000.00,
    ]);

    $response = $this
        ->actingAs($user)
        ->delete("/safes/{$safe->id}");

    $response->assertRedirect('/safes');
    $response->assertSessionHas('success', 'Safe deleted successfully!');
    expect(Safe::find($safe->id))->toBeNull();
});

test('manager cannot delete safe with items', function () {
    $user = User::factory()->create(['role' => 'manager']);
    
    $safe = Safe::create([
        'safe_code' => 'SAFE-TEST-004',
        'name' => 'Cannot Delete Me',
        'location' => 'Vault Room A',
        'items_capacity' => 10,
        'capacity' => 50000.00,
    ]);

    $category = Category::create([
        'name' => 'Test Category 2',
    ]);

    // Create an item inside this safe
    Item::create([
        'item_code' => 'ITEM-TEST-002',
        'name' => 'Diamond Ring',
        'category_id' => $category->id,
        'safe_id' => $safe->id,
        'appraised_value' => 2000.00,
    ]);

    $response = $this
        ->actingAs($user)
        ->delete("/safes/{$safe->id}");

    $response->assertRedirect('/safes');
    $response->assertSessionHas('error', 'Cannot delete safe with items. Please move items first.');
    expect(Safe::find($safe->id))->not->toBeNull();
});
