<?php

declare(strict_types=1);

use App\Enums\ProductType;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Tag;
use App\Models\User;

beforeEach(function () {
    $this->sellerUser = User::factory()->seller()->create();
    $this->seller     = Seller::factory()->for($this->sellerUser, 'user')->approved()->create();
});

// ─── Tags on create ───────────────────────────────────────────────────────────

it('seller can create a product with tags', function () {
    $response = $this->actingAs($this->sellerUser, 'seller')
        ->postJson('/api/v1/seller/products', [
            'title' => 'Tagged Product',
            'type'  => ProductType::Ebook->value,
            'price' => 100000,
            'tags'  => ['Laravel', 'PHP'],
        ]);

    $response->assertStatus(201)
        ->assertJsonPath('success', true);

    $product = Product::where('title', 'Tagged Product')->first();
    expect($product->tags)->toHaveCount(2);
    expect($product->tags->pluck('slug')->all())->toContain('laravel', 'php');
});

it('tags are reused across products', function () {
    $this->actingAs($this->sellerUser, 'seller')
        ->postJson('/api/v1/seller/products', [
            'title' => 'Product A',
            'type'  => ProductType::Ebook->value,
            'price' => 100000,
            'tags'  => ['Laravel'],
        ]);

    $this->actingAs($this->sellerUser, 'seller')
        ->postJson('/api/v1/seller/products', [
            'title' => 'Product B',
            'type'  => ProductType::Code->value,
            'price' => 200000,
            'tags'  => ['Laravel'],
        ]);

    expect(Tag::where('slug', 'laravel')->count())->toBe(1);
});

it('tags are case-insensitive when matched by slug', function () {
    $this->actingAs($this->sellerUser, 'seller')
        ->postJson('/api/v1/seller/products', [
            'title' => 'Product A',
            'type'  => ProductType::Ebook->value,
            'price' => 100000,
            'tags'  => ['laravel'],
        ]);

    $this->actingAs($this->sellerUser, 'seller')
        ->postJson('/api/v1/seller/products', [
            'title' => 'Product B',
            'type'  => ProductType::Ebook->value,
            'price' => 100000,
            'tags'  => ['LARAVEL'],
        ]);

    expect(Tag::where('slug', 'laravel')->count())->toBe(1);
});

it('creating a product without tags works fine', function () {
    $response = $this->actingAs($this->sellerUser, 'seller')
        ->postJson('/api/v1/seller/products', [
            'title' => 'No Tags Product',
            'type'  => ProductType::Music->value,
            'price' => 50000,
        ]);

    $response->assertStatus(201);

    $product = Product::where('title', 'No Tags Product')->first();
    expect($product->tags)->toHaveCount(0);
});

it('create fails when more than 10 tags are submitted', function () {
    $response = $this->actingAs($this->sellerUser, 'seller')
        ->postJson('/api/v1/seller/products', [
            'title' => 'Over-tagged',
            'type'  => ProductType::Ebook->value,
            'price' => 100000,
            'tags'  => ['a1', 'b2', 'c3', 'd4', 'e5', 'f6', 'g7', 'h8', 'i9', 'j10', 'k11'],
        ]);

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

it('create fails when a tag is too short', function () {
    $response = $this->actingAs($this->sellerUser, 'seller')
        ->postJson('/api/v1/seller/products', [
            'title' => 'Short Tag Product',
            'type'  => ProductType::Ebook->value,
            'price' => 100000,
            'tags'  => ['x'],
        ]);

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

it('create fails when a tag exceeds 50 characters', function () {
    $response = $this->actingAs($this->sellerUser, 'seller')
        ->postJson('/api/v1/seller/products', [
            'title' => 'Long Tag Product',
            'type'  => ProductType::Ebook->value,
            'price' => 100000,
            'tags'  => [str_repeat('a', 51)],
        ]);

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

// ─── Tags on update ───────────────────────────────────────────────────────────

it('seller can update product tags', function () {
    $product = Product::factory()->for($this->seller)->create();
    $product->tags()->sync([
        Tag::firstOrCreate(['slug' => 'php'], ['name' => 'PHP'])->id,
    ]);

    $response = $this->actingAs($this->sellerUser, 'seller')
        ->patchJson("/api/v1/seller/products/{$product->id}", [
            'tags' => ['Laravel', 'Vue'],
        ]);

    $response->assertStatus(200)
        ->assertJsonPath('success', true);

    $product->refresh();
    expect($product->tags)->toHaveCount(2);
    expect($product->tags->pluck('slug')->all())->toContain('laravel', 'vue');
    expect($product->tags->pluck('slug')->all())->not->toContain('php');
});

it('passing empty tags array removes all tags', function () {
    $product = Product::factory()->for($this->seller)->create();
    $product->tags()->sync([
        Tag::firstOrCreate(['slug' => 'php'], ['name' => 'PHP'])->id,
    ]);

    $this->actingAs($this->sellerUser, 'seller')
        ->patchJson("/api/v1/seller/products/{$product->id}", [
            'tags' => [],
        ]);

    $product->refresh();
    expect($product->tags)->toHaveCount(0);
});

it('omitting tags key on update does not change existing tags', function () {
    $product = Product::factory()->for($this->seller)->create();
    $product->tags()->sync([
        Tag::firstOrCreate(['slug' => 'php'], ['name' => 'PHP'])->id,
    ]);

    $this->actingAs($this->sellerUser, 'seller')
        ->patchJson("/api/v1/seller/products/{$product->id}", [
            'title' => 'Updated Title',
        ]);

    $product->refresh();
    expect($product->tags)->toHaveCount(1);
});

// ─── Tags in responses ────────────────────────────────────────────────────────

it('tags are returned in the show response', function () {
    $product = Product::factory()->for($this->seller)->create();
    $product->tags()->sync([
        Tag::firstOrCreate(['slug' => 'laravel'], ['name' => 'Laravel'])->id,
    ]);

    $response = $this->actingAs($this->sellerUser, 'seller')
        ->getJson("/api/v1/seller/products/{$product->id}");

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data.tags')
        ->assertJsonPath('data.tags.0.slug', 'laravel');
});

it('tags are returned in the list response', function () {
    $product = Product::factory()->for($this->seller)->create();
    $product->tags()->sync([
        Tag::firstOrCreate(['slug' => 'nuxt'], ['name' => 'Nuxt'])->id,
    ]);

    $response = $this->actingAs($this->sellerUser, 'seller')
        ->getJson('/api/v1/seller/products');

    $response->assertStatus(200)
        ->assertJsonPath('data.0.tags.0.slug', 'nuxt');
});

// ─── Tag suggestions ──────────────────────────────────────────────────────────

it('suggest endpoint returns matching tags', function () {
    Tag::firstOrCreate(['slug' => 'laravel'], ['name' => 'Laravel']);
    Tag::firstOrCreate(['slug' => 'laravel-livewire'], ['name' => 'Laravel Livewire']);
    Tag::firstOrCreate(['slug' => 'vue'], ['name' => 'Vue']);

    $response = $this->actingAs($this->sellerUser, 'seller')
        ->getJson('/api/v1/seller/tags/suggest?q=Lar');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');
});

it('suggest endpoint returns empty array when no match', function () {
    $response = $this->actingAs($this->sellerUser, 'seller')
        ->getJson('/api/v1/seller/tags/suggest?q=xyz123');

    $response->assertStatus(200)
        ->assertJsonCount(0, 'data');
});

it('unauthenticated user cannot access tag suggestions', function () {
    $response = $this->getJson('/api/v1/seller/tags/suggest?q=php');

    $response->assertStatus(401);
});
