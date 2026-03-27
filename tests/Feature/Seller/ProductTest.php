<?php

declare(strict_types=1);

use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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
    Storage::fake('r2');

    $product = Product::factory()->for($this->seller)->create([
        'status' => ProductStatus::Draft->value,
    ]);

    // Attach a fake product file so PublishProductAction passes the file check
    $product->addMedia(UploadedFile::fake()->create('product.pdf', 100, 'application/pdf'))
        ->toMediaCollection('product_file');

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
