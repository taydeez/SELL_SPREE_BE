<?php

declare(strict_types=1);

use App\Models\Affiliate;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
});

// ─── List affiliates ──────────────────────────────────────────────────────

it('admin can list affiliates', function () {
    Affiliate::factory()->count(3)->create();

    $response = $this->actingAs($this->admin, 'admin')
        ->getJson('/api/v1/admin/affiliates');

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => ['id', 'display_name'],
            ],
            'meta' => ['current_page', 'per_page', 'total'],
        ]);
});

it('list affiliates returns paginated data', function () {
    Affiliate::factory()->count(5)->create();

    $response = $this->actingAs($this->admin, 'admin')
        ->getJson('/api/v1/admin/affiliates');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data',
            'meta' => ['current_page', 'per_page', 'total'],
        ]);
});

it('unauthenticated user cannot list affiliates', function () {
    $response = $this->getJson('/api/v1/admin/affiliates');

    $response->assertStatus(401);
});

// ─── Suspend affiliate ────────────────────────────────────────────────────

it('admin can suspend an active affiliate', function () {
    $user      = User::factory()->affiliate()->create(['is_suspended' => false]);
    $affiliate = Affiliate::factory()->for($user, 'user')->create();

    $response = $this->actingAs($this->admin, 'admin')
        ->patchJson("/api/v1/admin/affiliates/{$affiliate->id}/suspend");

    $response->assertStatus(200)
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('users', [
        'id'           => $user->id,
        'is_suspended' => true,
    ]);
});

it('admin cannot suspend an already suspended affiliate', function () {
    $user      = User::factory()->affiliate()->suspended()->create();
    $affiliate = Affiliate::factory()->for($user, 'user')->create();

    $response = $this->actingAs($this->admin, 'admin')
        ->patchJson("/api/v1/admin/affiliates/{$affiliate->id}/suspend");

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

it('unauthenticated user cannot suspend affiliate', function () {
    $affiliate = Affiliate::factory()->create();

    $response = $this->patchJson("/api/v1/admin/affiliates/{$affiliate->id}/suspend");

    $response->assertStatus(401);
});

// ─── Unsuspend affiliate ──────────────────────────────────────────────────

it('admin can unsuspend a suspended affiliate', function () {
    $user      = User::factory()->affiliate()->suspended()->create();
    $affiliate = Affiliate::factory()->for($user, 'user')->create();

    $response = $this->actingAs($this->admin, 'admin')
        ->patchJson("/api/v1/admin/affiliates/{$affiliate->id}/unsuspend");

    $response->assertStatus(200)
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('users', [
        'id'           => $user->id,
        'is_suspended' => false,
    ]);
});

it('admin cannot unsuspend a non suspended affiliate', function () {
    $user      = User::factory()->affiliate()->create(['is_suspended' => false]);
    $affiliate = Affiliate::factory()->for($user, 'user')->create();

    $response = $this->actingAs($this->admin, 'admin')
        ->patchJson("/api/v1/admin/affiliates/{$affiliate->id}/unsuspend");

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

it('unauthenticated user cannot unsuspend affiliate', function () {
    $affiliate = Affiliate::factory()->create();

    $response = $this->patchJson("/api/v1/admin/affiliates/{$affiliate->id}/unsuspend");

    $response->assertStatus(401);
});

// ─── Update commission ────────────────────────────────────────────────────

it('admin can update affiliate commission rate', function () {
    $affiliate = Affiliate::factory()->create(['commission_rate' => 10]);

    $response = $this->actingAs($this->admin, 'admin')
        ->patchJson("/api/v1/admin/affiliates/{$affiliate->id}/commission", [
            'commission_rate' => 25,
        ]);

    $response->assertStatus(200)
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('affiliates', [
        'id'              => $affiliate->id,
        'commission_rate' => 25,
    ]);
});

it('admin update commission requires commission rate field', function () {
    $affiliate = Affiliate::factory()->create();

    $response = $this->actingAs($this->admin, 'admin')
        ->patchJson("/api/v1/admin/affiliates/{$affiliate->id}/commission", []);

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

it('admin update commission requires integer value', function () {
    $affiliate = Affiliate::factory()->create();

    $response = $this->actingAs($this->admin, 'admin')
        ->patchJson("/api/v1/admin/affiliates/{$affiliate->id}/commission", [
            'commission_rate' => 'not-a-number',
        ]);

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

it('unauthenticated user cannot update affiliate commission', function () {
    $affiliate = Affiliate::factory()->create();

    $response = $this->patchJson("/api/v1/admin/affiliates/{$affiliate->id}/commission", [
        'commission_rate' => 20,
    ]);

    $response->assertStatus(401);
});

// ─── Delete affiliate ─────────────────────────────────────────────────────

it('admin can delete an affiliate', function () {
    $user      = User::factory()->affiliate()->create();
    $affiliate = Affiliate::factory()->for($user, 'user')->create();

    $response = $this->actingAs($this->admin, 'admin')
        ->deleteJson("/api/v1/admin/affiliates/{$affiliate->id}");

    $response->assertStatus(204);

    $this->assertSoftDeleted('affiliates', ['id' => $affiliate->id]);
});

it('unauthenticated user cannot delete affiliate', function () {
    $affiliate = Affiliate::factory()->create();

    $response = $this->deleteJson("/api/v1/admin/affiliates/{$affiliate->id}");

    $response->assertStatus(401);
});
