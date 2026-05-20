<?php

use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\Safe;

test('manager can view categories list', function () {
    $user = User::factory()->create(['role' => 'manager']);

    $response = $this
        ->actingAs($user)
        ->get('/categories');

    $response->assertStatus(200);
});

test('manager can edit and update category with no items', function () {
    $user = User::factory()->create(['role' => 'manager']);
    
    $category = Category::create([
        'name' => 'Empty Category',
        'description' => 'A category with no items inside.',
    ]);

    // Check we can access edit page
    $response = $this
        ->actingAs($user)
        ->get("/categories/{$category->id}/edit");
    $response->assertStatus(200);

    // Check we can update
    $response = $this
        ->actingAs($user)
        ->put("/categories/{$category->id}", [
            'name' => 'Updated Category Name',
            'description' => 'New description.',
        ]);
    
    $response->assertRedirect("/categories/{$category->id}");
    $category->refresh();
    expect($category->name)->toBe('Updated Category Name');
    expect($category->description)->toBe('New description.');
});

test('manager cannot edit or update category with items', function () {
    $user = User::factory()->create(['role' => 'manager']);
    
    $category = Category::create([
        'name' => 'Filled Category',
        'description' => 'A category with items inside.',
    ]);

    $safe = Safe::create([
        'safe_code' => 'SAFE-CAT-TEST-01',
        'name' => 'Test Safe',
        'location' => 'Main Vault',
        'items_capacity' => 10,
        'capacity' => 50000.00,
    ]);

    // Create an item inside this category
    Item::create([
        'item_code' => 'ITEM-CAT-TEST-001',
        'name' => 'Rolex',
        'category_id' => $category->id,
        'safe_id' => $safe->id,
        'appraised_value' => 5000.00,
    ]);

    // Try to access edit page
    $response = $this
        ->actingAs($user)
        ->get("/categories/{$category->id}/edit");
    
    $response->assertRedirect('/categories');
    $response->assertSessionHas('error', 'Cannot edit category containing items. Please move items first.');

    // Try to update
    $response = $this
        ->actingAs($user)
        ->put("/categories/{$category->id}", [
            'name' => 'Should Not Update',
            'description' => 'Should not change.',
        ]);

    $response->assertRedirect('/categories');
    $response->assertSessionHas('error', 'Cannot update category containing items.');
    
    $category->refresh();
    expect($category->name)->toBe('Filled Category');
});

test('manager can delete category with no items', function () {
    $user = User::factory()->create(['role' => 'manager']);
    
    $category = Category::create([
        'name' => 'Delete Me Category',
        'description' => 'Delete me.',
    ]);

    $response = $this
        ->actingAs($user)
        ->delete("/categories/{$category->id}");

    $response->assertRedirect('/categories');
    $response->assertSessionHas('success', 'Category deleted successfully!');
    expect(Category::find($category->id))->toBeNull();
});

test('manager cannot delete category with items', function () {
    $user = User::factory()->create(['role' => 'manager']);
    
    $category = Category::create([
        'name' => 'Cannot Delete Category',
        'description' => 'Cannot delete.',
    ]);

    $safe = Safe::create([
        'safe_code' => 'SAFE-CAT-TEST-02',
        'name' => 'Test Safe 2',
        'location' => 'Main Vault',
        'items_capacity' => 10,
        'capacity' => 50000.00,
    ]);

    // Create an item inside this category
    Item::create([
        'item_code' => 'ITEM-CAT-TEST-002',
        'name' => 'Cartier',
        'category_id' => $category->id,
        'safe_id' => $safe->id,
        'appraised_value' => 6000.00,
    ]);

    $response = $this
        ->actingAs($user)
        ->delete("/categories/{$category->id}");

    $response->assertRedirect('/categories');
    $response->assertSessionHas('error', 'Cannot delete category containing items. Please move or reclassify items first.');
    expect(Category::find($category->id))->not->toBeNull();
});
