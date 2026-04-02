<?php

declare(strict_types=1);

use App\Enums\OrderStatus;
use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;

beforeEach(function () {
    $this->sellerUser = User::factory()->seller()->create();
    $this->seller     = Seller::factory()->for($this->sellerUser, 'user')->approved()->create();
});

// ─── Create product ───────────────────────────────────────────────────────

it('seller can create a product', function () {
    $response = $this->actingAs($this->sellerUser, 'seller')
        ->postJson('/api/v1/seller/products', [
            'title'       => 'My First eBook',
            'description' => 'A great eBook about digital products.',
            'type'        => ProductType::Ebook->value,
            'price'       => 500000,
        ]);

    $response->assertStatus(201)
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => ['id', 'title', 'type', 'price', 'status'],
        ]);

    $this->assertDatabaseHas('products', [
        'title'     => 'My First eBook',
        'seller_id' => $this->seller->id,
        'status'    => ProductStatus::Draft->value,
    ]);
});

it('created product always starts with draft status', function () {
    $response = $this->actingAs($this->sellerUser, 'seller')
        ->postJson('/api/v1/seller/products', [
            'title' => 'New Track',
            'type'  => ProductType::Music->value,
            'price' => 100000,
        ]);

    $response->assertStatus(201)
        ->assertJsonPath('data.status', ProductStatus::Draft->value);
});

it('create product fails without required fields', function () {
    $response = $this->actingAs($this->sellerUser, 'seller')
        ->postJson('/api/v1/seller/products', []);

    $response->assertStatus(422)
        ->assertJsonPath('success', false)
        ->assertJsonStructure(['errors']);
});

it('create product fails with invalid type', function () {
    $response = $this->actingAs($this->sellerUser, 'seller')
        ->postJson('/api/v1/seller/products', [
            'title' => 'Invalid Type Product',
            'type'  => 'invalid_type',
            'price' => 100000,
        ]);

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

it('create product fails when price is not integer', function () {
    $response = $this->actingAs($this->sellerUser, 'seller')
        ->postJson('/api/v1/seller/products', [
            'title' => 'Float Price Product',
            'type'  => ProductType::Ebook->value,
            'price' => 9.99,
        ]);

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

it('unauthenticated user cannot create product', function () {
    $response = $this->postJson('/api/v1/seller/products', [
        'title' => 'Unauthenticated Product',
        'type'  => ProductType::Ebook->value,
        'price' => 100000,
    ]);

    $response->assertStatus(401);
});

// ─── List products ────────────────────────────────────────────────────────

it('seller can list their own products', function () {
    Product::factory()->count(3)->for($this->seller)->create();

    $response = $this->actingAs($this->sellerUser, 'seller')
        ->getJson('/api/v1/seller/products');

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonCount(3, 'data');
});

it('seller cannot see another sellers products', function () {
    // Products belonging to a different seller
    $otherSeller = Seller::factory()->approved()->create();
    Product::factory()->count(5)->for($otherSeller)->create();

    // Own products
    Product::factory()->count(2)->for($this->seller)->create();

    $response = $this->actingAs($this->sellerUser, 'seller')
        ->getJson('/api/v1/seller/products');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');
});

it('unauthenticated user cannot list products', function () {
    $response = $this->getJson('/api/v1/seller/products');

    $response->assertStatus(401);
});

// ─── Show product ─────────────────────────────────────────────────────────

it('seller can view their own product', function () {
    $product = Product::factory()->for($this->seller)->create();

    $response = $this->actingAs($this->sellerUser, 'seller')
        ->getJson("/api/v1/seller/products/{$product->id}");

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.id', $product->id);
});

it('seller cannot view another sellers product', function () {
    $otherSeller = Seller::factory()->approved()->create();
    $product     = Product::factory()->for($otherSeller)->create();

    $response = $this->actingAs($this->sellerUser, 'seller')
        ->getJson("/api/v1/seller/products/{$product->id}");

    $response->assertStatus(403);
});

// ─── Update product ───────────────────────────────────────────────────────

it('seller can update their own product', function () {
    $product = Product::factory()->for($this->seller)->create();

    $response = $this->actingAs($this->sellerUser, 'seller')
        ->patchJson("/api/v1/seller/products/{$product->id}", [
            'title' => 'Updated Title',
            'price' => 750000,
        ]);

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.title', 'Updated Title');

    $this->assertDatabaseHas('products', [
        'id'    => $product->id,
        'title' => 'Updated Title',
        'price' => 750000,
    ]);
});

it('seller cannot update another sellers product', function () {
    $otherSeller = Seller::factory()->approved()->create();
    $product     = Product::factory()->for($otherSeller)->create();

    $response = $this->actingAs($this->sellerUser, 'seller')
        ->patchJson("/api/v1/seller/products/{$product->id}", [
            'title' => 'Hijacked Title',
        ]);

    $response->assertStatus(403);
});

// ─── Delete product ───────────────────────────────────────────────────────

it('seller can delete their own product', function () {
    $product = Product::factory()->for($this->seller)->create();

    $response = $this->actingAs($this->sellerUser, 'seller')
        ->deleteJson("/api/v1/seller/products/{$product->id}");

    $response->assertStatus(204);

    $this->assertSoftDeleted('products', ['id' => $product->id]);
});

it('seller cannot delete another sellers product', function () {
    $otherSeller = Seller::factory()->approved()->create();
    $product     = Product::factory()->for($otherSeller)->create();

    $response = $this->actingAs($this->sellerUser, 'seller')
        ->deleteJson("/api/v1/seller/products/{$product->id}");

    $response->assertStatus(403);
});

// ─── Publish product ──────────────────────────────────────────────────────

it('seller can publish a draft product', function () {
    $product = Product::factory()->for($this->seller)->create([
        'status' => ProductStatus::Draft->value,
    ]);

    $product->productFiles()->create([
        'collection'    => 'product_file',
        'path'          => 'products/' . $product->id . '/product_file/fake.pdf',
        'file_name'     => 'fake.pdf',
        'original_name' => 'fake.pdf',
        'mime_type'     => 'application/pdf',
        'size'          => 1024,
        'disk'          => 'r2',
    ]);

    $response = $this->actingAs($this->sellerUser, 'seller')
        ->patchJson("/api/v1/seller/products/{$product->id}/publish");

    $response->assertStatus(200)
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('products', [
        'id'     => $product->id,
        'status' => ProductStatus::Active->value,
    ]);
});

it('seller cannot publish a product without a file', function () {
    $product = Product::factory()->for($this->seller)->create([
        'status' => ProductStatus::Draft->value,
    ]);

    $response = $this->actingAs($this->sellerUser, 'seller')
        ->patchJson("/api/v1/seller/products/{$product->id}/publish");

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

it('seller cannot publish another sellers product', function () {
    $otherSeller = Seller::factory()->approved()->create();
    $product     = Product::factory()->for($otherSeller)->create([
        'status' => ProductStatus::Draft->value,
    ]);

    $response = $this->actingAs($this->sellerUser, 'seller')
        ->patchJson("/api/v1/seller/products/{$product->id}/publish");

    $response->assertStatus(403);
});

// ─── Pause product ────────────────────────────────────────────────────────

it('seller can pause an active product', function () {
    $product = Product::factory()->for($this->seller)->active()->create();

    $response = $this->actingAs($this->sellerUser, 'seller')
        ->patchJson("/api/v1/seller/products/{$product->id}/pause");

    $response->assertStatus(200)
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('products', [
        'id'     => $product->id,
        'status' => ProductStatus::Paused->value,
    ]);
});

it('seller cannot pause another sellers product', function () {
    $otherSeller = Seller::factory()->approved()->create();
    $product     = Product::factory()->for($otherSeller)->active()->create();

    $response = $this->actingAs($this->sellerUser, 'seller')
        ->patchJson("/api/v1/seller/products/{$product->id}/pause");

    $response->assertStatus(403);
});

// ─── File replacement lock ────────────────────────────────────────────────────

it('seller can replace product file before any purchase', function () {
    $product = Product::factory()->for($this->seller)->active()->create();

    $product->productFiles()->create([
        'collection'    => 'product_file',
        'path'          => 'products/' . $product->id . '/product_file/old.pdf',
        'file_name'     => 'old.pdf',
        'original_name' => 'old.pdf',
        'mime_type'     => 'application/pdf',
        'size'          => 1024,
        'disk'          => 'r2',
    ]);

    $response = $this->actingAs($this->sellerUser, 'seller')
        ->postJson("/api/v1/seller/products/{$product->id}/confirm-upload", [
            'collection'    => 'product_file',
            'object_key'    => 'products/' . $product->id . '/product_file/new.pdf',
            'file_name'     => 'new.pdf',
            'original_name' => 'new.pdf',
            'mime_type'     => 'application/pdf',
            'size'          => 2048,
        ]);

    $response->assertStatus(200);
});

it('seller cannot replace product file after a paid order', function () {
    $product = Product::factory()->for($this->seller)->active()->create();

    $product->productFiles()->create([
        'collection'    => 'product_file',
        'path'          => 'products/' . $product->id . '/product_file/old.pdf',
        'file_name'     => 'old.pdf',
        'original_name' => 'old.pdf',
        'mime_type'     => 'application/pdf',
        'size'          => 1024,
        'disk'          => 'r2',
    ]);

    Order::factory()->paid()->for($product)->for($this->seller)->create();

    $response = $this->actingAs($this->sellerUser, 'seller')
        ->postJson("/api/v1/seller/products/{$product->id}/confirm-upload", [
            'collection'    => 'product_file',
            'object_key'    => 'products/' . $product->id . '/product_file/new.pdf',
            'file_name'     => 'new.pdf',
            'original_name' => 'new.pdf',
            'mime_type'     => 'application/pdf',
            'size'          => 2048,
        ]);

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

it('seller can still replace cover image after a paid order', function () {
    $product = Product::factory()->for($this->seller)->active()->create();

    Order::factory()->paid()->for($product)->for($this->seller)->create();

    $response = $this->actingAs($this->sellerUser, 'seller')
        ->postJson("/api/v1/seller/products/{$product->id}/confirm-upload", [
            'collection'    => 'cover',
            'object_key'    => 'products/' . $product->id . '/cover/new.jpg',
            'file_name'     => 'new.jpg',
            'original_name' => 'new.jpg',
            'mime_type'     => 'image/jpeg',
            'size'          => 512,
        ]);

    $response->assertStatus(200);
});
