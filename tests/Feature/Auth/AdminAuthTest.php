<?php

declare(strict_types=1);

use App\Models\User;

// ─── Login ───────────────────────────────────────────────────────────────

it('admin can login with correct credentials', function () {
    User::factory()->admin()->create(['email' => 'admin@example.com']);

    $response = $this->postJson('/api/v1/admin/auth/login', [
        'email'    => 'admin@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => ['access_token', 'token_type', 'user'],
        ]);
});

it('admin login fails with wrong password', function () {
    User::factory()->admin()->create(['email' => 'admin@example.com']);

    $response = $this->postJson('/api/v1/admin/auth/login', [
        'email'    => 'admin@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401)
        ->assertJsonPath('success', false);
});

it('admin login fails with non existent email', function () {
    $response = $this->postJson('/api/v1/admin/auth/login', [
        'email'    => 'nobody@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(401)
        ->assertJsonPath('success', false);
});

it('seller cannot login via admin endpoint', function () {
    User::factory()->seller()->create(['email' => 'seller@example.com']);

    $response = $this->postJson('/api/v1/admin/auth/login', [
        'email'    => 'seller@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(403)
        ->assertJsonPath('success', false);
});

it('affiliate cannot login via admin endpoint', function () {
    User::factory()->affiliate()->create(['email' => 'affiliate@example.com']);

    $response = $this->postJson('/api/v1/admin/auth/login', [
        'email'    => 'affiliate@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(403)
        ->assertJsonPath('success', false);
});

it('admin login requires email and password', function () {
    $response = $this->postJson('/api/v1/admin/auth/login', []);

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

// ─── Me ──────────────────────────────────────────────────────────────────

it('authenticated admin can fetch own profile', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin, 'admin')
        ->getJson('/api/v1/admin/auth/me');

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.email', $admin->email);
});

it('unauthenticated request to admin me returns 401', function () {
    $response = $this->getJson('/api/v1/admin/auth/me');

    $response->assertStatus(401);
});
