<?php

declare(strict_types=1);

use App\Models\Affiliate;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

// ─── Register ────────────────────────────────────────────────────────────

it('affiliate can register with valid data', function () {
    Notification::fake();

    $response = $this->postJson('/api/v1/affiliate/auth/register', [
        'name'                  => 'Alice Affiliate',
        'email'                 => 'alice@example.com',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'display_name',
                'user' => ['id', 'name', 'email'],
            ],
        ]);

    $this->assertDatabaseHas('users', ['email' => 'alice@example.com']);
    $this->assertDatabaseHas('affiliates', []);
});

it('affiliate register accepts optional display name', function () {
    Notification::fake();

    $response = $this->postJson('/api/v1/affiliate/auth/register', [
        'name'                  => 'Charlie Promoter',
        'email'                 => 'charlie@example.com',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
        'display_name'          => 'CharliePromo',
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('affiliates', ['display_name' => 'CharliePromo']);
});

it('affiliate register fails with duplicate email', function () {
    User::factory()->affiliate()->create(['email' => 'duplicate@example.com']);

    $response = $this->postJson('/api/v1/affiliate/auth/register', [
        'name'                  => 'Duplicate',
        'email'                 => 'duplicate@example.com',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(409)
        ->assertJsonPath('success', false)
        ->assertJsonStructure(['success', 'message', 'errors']);
});

it('affiliate register fails when required fields are missing', function () {
    $response = $this->postJson('/api/v1/affiliate/auth/register', []);

    $response->assertStatus(422)
        ->assertJsonPath('success', false)
        ->assertJsonStructure(['errors']);
});

it('affiliate register fails when passwords do not match', function () {
    $response = $this->postJson('/api/v1/affiliate/auth/register', [
        'name'                  => 'Mismatch',
        'email'                 => 'mismatch@example.com',
        'password'              => 'password123',
        'password_confirmation' => 'different456',
    ]);

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

// ─── Login ───────────────────────────────────────────────────────────────

it('affiliate can login with correct credentials', function () {
    $user = User::factory()->affiliate()->create(['email' => 'affiliate@example.com']);
    Affiliate::factory()->for($user, 'user')->create();

    $response = $this->postJson('/api/v1/affiliate/auth/login', [
        'email'    => 'affiliate@example.com',
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

it('affiliate login fails with wrong password', function () {
    User::factory()->affiliate()->create(['email' => 'affiliate@example.com']);

    $response = $this->postJson('/api/v1/affiliate/auth/login', [
        'email'    => 'affiliate@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401)
        ->assertJsonPath('success', false);
});

it('affiliate login fails with non existent email', function () {
    $response = $this->postJson('/api/v1/affiliate/auth/login', [
        'email'    => 'nobody@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(401)
        ->assertJsonPath('success', false);
});

it('seller cannot login via affiliate endpoint', function () {
    User::factory()->seller()->create(['email' => 'seller@example.com']);

    $response = $this->postJson('/api/v1/affiliate/auth/login', [
        'email'    => 'seller@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(403)
        ->assertJsonPath('success', false);
});

it('admin cannot login via affiliate endpoint', function () {
    User::factory()->admin()->create(['email' => 'admin@example.com']);

    $response = $this->postJson('/api/v1/affiliate/auth/login', [
        'email'    => 'admin@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(403)
        ->assertJsonPath('success', false);
});

// ─── Me ──────────────────────────────────────────────────────────────────

it('authenticated affiliate can fetch own profile', function () {
    $user = User::factory()->affiliate()->create();
    Affiliate::factory()->for($user, 'user')->create();

    $response = $this->actingAs($user, 'affiliate')
        ->getJson('/api/v1/affiliate/auth/me');

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.user.email', $user->email);
});

it('unauthenticated request to affiliate me returns 401', function () {
    $response = $this->getJson('/api/v1/affiliate/auth/me');

    $response->assertStatus(401);
});
