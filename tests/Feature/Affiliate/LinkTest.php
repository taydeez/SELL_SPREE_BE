<?php

declare(strict_types=1);

use App\Models\Affiliate;
use App\Models\AffiliateLink;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;

beforeEach(function () {
    $this->affiliateUser = User::factory()->affiliate()->create();
    $this->affiliate     = Affiliate::factory()->for($this->affiliateUser, 'user')->create();
});

// ─── Generate link ────────────────────────────────────────────────────────

it('affiliate can generate a link for an active product', function () {
    $product = Product::factory()->active()->create();

    $response = $this->actingAs($this->affiliateUser, 'affiliate')
        ->postJson('/api/v1/affiliate/links', [
            'product_id' => $product->id,
        ]);

    $response->assertStatus(201)
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => ['id', 'slug', 'is_active', 'link_url', 'view_count', 'click_count'],
        ]);

    $this->assertDatabaseHas('affiliate_links', [
        'affiliate_id' => $this->affiliate->id,
        'product_id'   => $product->id,
    ]);
});

it('generating a link twice returns the existing link', function () {
    $product = Product::factory()->active()->create();

    // First call
    $first = $this->actingAs($this->affiliateUser, 'affiliate')
        ->postJson('/api/v1/affiliate/links', ['product_id' => $product->id]);

    $first->assertStatus(201);

    // Second call for the same product — controller always returns 201 with existing link
    $second = $this->actingAs($this->affiliateUser, 'affiliate')
        ->postJson('/api/v1/affiliate/links', ['product_id' => $product->id]);

    $second->assertStatus(201)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.id', $first->json('data.id'));

    // Only one link record should exist
    $this->assertDatabaseCount('affiliate_links', 1);
});

it('affiliate cannot generate a link for a non active product', function () {
    $product = Product::factory()->create(); // Draft by default

    $response = $this->actingAs($this->affiliateUser, 'affiliate')
        ->postJson('/api/v1/affiliate/links', [
            'product_id' => $product->id,
        ]);

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

it('affiliate cannot generate a link for a paused product', function () {
    $product = Product::factory()->paused()->create();

    $response = $this->actingAs($this->affiliateUser, 'affiliate')
        ->postJson('/api/v1/affiliate/links', [
            'product_id' => $product->id,
        ]);

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

it('generate link requires product id', function () {
    $response = $this->actingAs($this->affiliateUser, 'affiliate')
        ->postJson('/api/v1/affiliate/links', []);

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

it('unauthenticated user cannot generate link', function () {
    $product = Product::factory()->active()->create();

    $response = $this->postJson('/api/v1/affiliate/links', [
        'product_id' => $product->id,
    ]);

    $response->assertStatus(401);
});

// ─── List links ───────────────────────────────────────────────────────────

it('affiliate can list their own links', function () {
    AffiliateLink::factory()->count(3)->for($this->affiliate)->create();

    $response = $this->actingAs($this->affiliateUser, 'affiliate')
        ->getJson('/api/v1/affiliate/links');

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonCount(3, 'data');
});

it('affiliate does not see other affiliates links', function () {
    $otherAffiliate = Affiliate::factory()->create();
    AffiliateLink::factory()->count(4)->for($otherAffiliate)->create();
    AffiliateLink::factory()->count(2)->for($this->affiliate)->create();

    $response = $this->actingAs($this->affiliateUser, 'affiliate')
        ->getJson('/api/v1/affiliate/links');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');
});

it('unauthenticated user cannot list links', function () {
    $response = $this->getJson('/api/v1/affiliate/links');

    $response->assertStatus(401);
});

// ─── Disable link ─────────────────────────────────────────────────────────

it('affiliate can disable an active link', function () {
    $link = AffiliateLink::factory()->for($this->affiliate)->create(['is_active' => true]);

    $response = $this->actingAs($this->affiliateUser, 'affiliate')
        ->patchJson("/api/v1/affiliate/links/{$link->id}/disable");

    $response->assertStatus(200)
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('affiliate_links', [
        'id'        => $link->id,
        'is_active' => false,
    ]);
});

it('affiliate cannot disable another affiliates link', function () {
    $otherAffiliate = Affiliate::factory()->create();
    $link           = AffiliateLink::factory()->for($otherAffiliate)->create(['is_active' => true]);

    $response = $this->actingAs($this->affiliateUser, 'affiliate')
        ->patchJson("/api/v1/affiliate/links/{$link->id}/disable");

    $response->assertStatus(403);
});

it('unauthenticated user cannot disable link', function () {
    $link = AffiliateLink::factory()->create(['is_active' => true]);

    $response = $this->patchJson("/api/v1/affiliate/links/{$link->id}/disable");

    $response->assertStatus(401);
});

// ─── Enable link ──────────────────────────────────────────────────────────

it('affiliate can enable a disabled link', function () {
    $link = AffiliateLink::factory()->for($this->affiliate)->inactive()->create();

    $response = $this->actingAs($this->affiliateUser, 'affiliate')
        ->patchJson("/api/v1/affiliate/links/{$link->id}/enable");

    $response->assertStatus(200)
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('affiliate_links', [
        'id'        => $link->id,
        'is_active' => true,
    ]);
});

it('affiliate cannot enable another affiliates link', function () {
    $otherAffiliate = Affiliate::factory()->create();
    $link           = AffiliateLink::factory()->for($otherAffiliate)->inactive()->create();

    $response = $this->actingAs($this->affiliateUser, 'affiliate')
        ->patchJson("/api/v1/affiliate/links/{$link->id}/enable");

    $response->assertStatus(403);
});

it('unauthenticated user cannot enable link', function () {
    $link = AffiliateLink::factory()->inactive()->create();

    $response = $this->patchJson("/api/v1/affiliate/links/{$link->id}/enable");

    $response->assertStatus(401);
});
