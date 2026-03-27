<?php

declare(strict_types=1);

use App\Models\Seller;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
});

// ─── List sellers ─────────────────────────────────────────────────────────

it('admin can list sellers', function () {
    Seller::factory()->count(3)->create();

    $response = $this->actingAs($this->admin, 'admin')
        ->getJson('/api/v1/admin/sellers');

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => ['id', 'store_name'],
            ],
            'meta' => ['current_page', 'per_page', 'total'],
        ]);
});

it('list sellers returns paginated data', function () {
    Seller::factory()->count(5)->create();

    $response = $this->actingAs($this->admin, 'admin')
        ->getJson('/api/v1/admin/sellers');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data',
            'meta' => ['current_page', 'per_page', 'total'],
        ]);
});

it('unauthenticated user cannot list sellers', function () {
    $response = $this->getJson('/api/v1/admin/sellers');

    $response->assertStatus(401);
});

// ─── Approve seller ──────────────────────────────────────────────────────

it('admin can approve a pending seller', function () {
    $seller = Seller::factory()->create(['is_approved' => false]);

    $response = $this->actingAs($this->admin, 'admin')
        ->patchJson("/api/v1/admin/sellers/{$seller->id}/approve");

    $response->assertStatus(200)
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('sellers', [
        'id'          => $seller->id,
        'is_approved' => true,
    ]);
});

it('admin cannot approve an already approved seller', function () {
    $seller = Seller::factory()->approved()->create();

    $response = $this->actingAs($this->admin, 'admin')
        ->patchJson("/api/v1/admin/sellers/{$seller->id}/approve");

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

it('unauthenticated user cannot approve seller', function () {
    $seller = Seller::factory()->create();

    $response = $this->patchJson("/api/v1/admin/sellers/{$seller->id}/approve");

    $response->assertStatus(401);
});

// ─── Suspend seller ──────────────────────────────────────────────────────

it('admin can suspend an active seller', function () {
    $user   = User::factory()->seller()->create(['is_suspended' => false]);
    $seller = Seller::factory()->for($user, 'user')->approved()->create();

    $response = $this->actingAs($this->admin, 'admin')
        ->patchJson("/api/v1/admin/sellers/{$seller->id}/suspend");

    $response->assertStatus(200)
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('users', [
        'id'           => $user->id,
        'is_suspended' => true,
    ]);
});

it('admin cannot suspend an already suspended seller', function () {
    $user   = User::factory()->seller()->suspended()->create();
    $seller = Seller::factory()->for($user, 'user')->approved()->create();

    $response = $this->actingAs($this->admin, 'admin')
        ->patchJson("/api/v1/admin/sellers/{$seller->id}/suspend");

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

it('unauthenticated user cannot suspend seller', function () {
    $seller = Seller::factory()->create();

    $response = $this->patchJson("/api/v1/admin/sellers/{$seller->id}/suspend");

    $response->assertStatus(401);
});

// ─── Unsuspend seller ────────────────────────────────────────────────────

it('admin can unsuspend a suspended seller', function () {
    $user   = User::factory()->seller()->suspended()->create();
    $seller = Seller::factory()->for($user, 'user')->approved()->create();

    $response = $this->actingAs($this->admin, 'admin')
        ->patchJson("/api/v1/admin/sellers/{$seller->id}/unsuspend");

    $response->assertStatus(200)
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('users', [
        'id'           => $user->id,
        'is_suspended' => false,
    ]);
});

it('admin cannot unsuspend a non suspended seller', function () {
    $user   = User::factory()->seller()->create(['is_suspended' => false]);
    $seller = Seller::factory()->for($user, 'user')->approved()->create();

    $response = $this->actingAs($this->admin, 'admin')
        ->patchJson("/api/v1/admin/sellers/{$seller->id}/unsuspend");

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

it('unauthenticated user cannot unsuspend seller', function () {
    $seller = Seller::factory()->create();

    $response = $this->patchJson("/api/v1/admin/sellers/{$seller->id}/unsuspend");

    $response->assertStatus(401);
});

// ─── Delete seller ───────────────────────────────────────────────────────

it('admin can delete a seller', function () {
    $user   = User::factory()->seller()->create();
    $seller = Seller::factory()->for($user, 'user')->create();

    $response = $this->actingAs($this->admin, 'admin')
        ->deleteJson("/api/v1/admin/sellers/{$seller->id}");

    $response->assertStatus(204);

    $this->assertSoftDeleted('sellers', ['id' => $seller->id]);
});

it('unauthenticated user cannot delete seller', function () {
    $seller = Seller::factory()->create();

    $response = $this->deleteJson("/api/v1/admin/sellers/{$seller->id}");

    $response->assertStatus(401);
});

it('approve returns 404 for nonexistent seller', function () {
    $response = $this->actingAs($this->admin, 'admin')
        ->patchJson('/api/v1/admin/sellers/nonexistent-id/approve');

    $response->assertStatus(404);
});
