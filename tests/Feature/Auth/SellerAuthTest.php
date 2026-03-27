<?php

declare(strict_types=1);

use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

// ─── Register ────────────────────────────────────────────────────────────

it('seller can register with valid data', function () {
    Notification::fake();

    $response = $this->postJson('/api/v1/seller/auth/register', [
        'name'                  => 'Jane Seller',
        'email'                 => 'jane@example.com',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
        'store_name'            => 'Jane\'s Digital Store',
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'store_name',
                'user' => ['id', 'name', 'email'],
            ],
        ]);

    $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
    $this->assertDatabaseHas('sellers', ['store_name' => 'Jane\'s Digital Store']);
});

it('seller register accepts optional bio and payout email', function () {
    Notification::fake();

    $response = $this->postJson('/api/v1/seller/auth/register', [
        'name'                  => 'Bob Creator',
        'email'                 => 'bob@example.com',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
        'store_name'            => 'Bob\'s Shop',
        'bio'                   => 'I sell great digital products.',
        'payout_email'          => 'bob-payout@example.com',
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('sellers', [
        'bio'          => 'I sell great digital products.',
        'payout_email' => 'bob-payout@example.com',
    ]);
});

it('seller register fails with duplicate email', function () {
    User::factory()->seller()->create(['email' => 'existing@example.com']);

    $response = $this->postJson('/api/v1/seller/auth/register', [
        'name'                  => 'Duplicate',
        'email'                 => 'existing@example.com',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
        'store_name'            => 'Some Store',
    ]);

    $response->assertStatus(409)
        ->assertJsonPath('success', false)
        ->assertJsonStructure(['success', 'message', 'errors']);
});

it('seller register fails when required fields are missing', function () {
    $response = $this->postJson('/api/v1/seller/auth/register', []);

    $response->assertStatus(422)
        ->assertJsonPath('success', false)
        ->assertJsonStructure(['errors']);
});

it('seller register fails when passwords do not match', function () {
    $response = $this->postJson('/api/v1/seller/auth/register', [
        'name'                  => 'Mismatch User',
        'email'                 => 'mismatch@example.com',
        'password'              => 'password123',
        'password_confirmation' => 'different456',
        'store_name'            => 'Some Store',
    ]);

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

it('seller register fails when store name is missing', function () {
    $response = $this->postJson('/api/v1/seller/auth/register', [
        'name'                  => 'No Store',
        'email'                 => 'nostore@example.com',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

// ─── Login ───────────────────────────────────────────────────────────────

it('seller can login with correct credentials', function () {
    $user   = User::factory()->seller()->create(['email' => 'seller@example.com']);
    Seller::factory()->for($user, 'user')->approved()->create();

    $response = $this->postJson('/api/v1/seller/auth/login', [
        'email'    => 'seller@example.com',
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

it('seller login fails with wrong password', function () {
    User::factory()->seller()->create(['email' => 'seller@example.com']);

    $response = $this->postJson('/api/v1/seller/auth/login', [
        'email'    => 'seller@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401)
        ->assertJsonPath('success', false);
});

it('seller login fails with non existent email', function () {
    $response = $this->postJson('/api/v1/seller/auth/login', [
        'email'    => 'nobody@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(401)
        ->assertJsonPath('success', false);
});

it('affiliate cannot login via seller endpoint', function () {
    User::factory()->affiliate()->create(['email' => 'affiliate@example.com']);

    $response = $this->postJson('/api/v1/seller/auth/login', [
        'email'    => 'affiliate@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(403)
        ->assertJsonPath('success', false);
});

it('admin cannot login via seller endpoint', function () {
    User::factory()->admin()->create(['email' => 'admin@example.com']);

    $response = $this->postJson('/api/v1/seller/auth/login', [
        'email'    => 'admin@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(403)
        ->assertJsonPath('success', false);
});

// ─── Me ──────────────────────────────────────────────────────────────────

it('authenticated seller can fetch own profile', function () {
    $user = User::factory()->seller()->create();
    Seller::factory()->for($user, 'user')->approved()->create();

    $response = $this->actingAs($user, 'seller')
        ->getJson('/api/v1/seller/auth/me');

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.user.email', $user->email);
});

it('unauthenticated request to me returns 401', function () {
    $response = $this->getJson('/api/v1/seller/auth/me');

    $response->assertStatus(401);
});
