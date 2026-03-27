<?php

declare(strict_types=1);

use App\Domain\Admin\Actions\SuspendUserAction;
use App\Domain\Admin\Actions\UnsuspendUserAction;
use App\Exceptions\BusinessException;
use App\Models\User;

// ─── SuspendUserAction ────────────────────────────────────────────────────

it('suspend action suspends an active user', function () {
    $user   = User::factory()->create(['is_suspended' => false]);
    $action = new SuspendUserAction();

    $action->run($user);

    expect($user->fresh()->is_suspended)->toBeTrue();
});

it('suspend action persists suspension to database', function () {
    $user   = User::factory()->create(['is_suspended' => false]);
    $action = new SuspendUserAction();

    $action->run($user);

    $this->assertDatabaseHas('users', [
        'id'           => $user->id,
        'is_suspended' => true,
    ]);
});

it('suspend action throws when user is already suspended', function () {
    $user   = User::factory()->suspended()->create();
    $action = new SuspendUserAction();

    $action->run($user);
})->throws(BusinessException::class, 'User is already suspended.');

it('suspend action throws with 422 status code for already suspended', function () {
    $user   = User::factory()->suspended()->create();
    $action = new SuspendUserAction();

    try {
        $action->run($user);
        $this->fail('Expected BusinessException was not thrown.');
    } catch (BusinessException $e) {
        expect($e->getStatusCode())->toBe(422);
    }
});

it('suspend action works for seller user', function () {
    $user   = User::factory()->seller()->create(['is_suspended' => false]);
    $action = new SuspendUserAction();

    $action->run($user);

    expect($user->fresh()->is_suspended)->toBeTrue();
});

it('suspend action works for affiliate user', function () {
    $user   = User::factory()->affiliate()->create(['is_suspended' => false]);
    $action = new SuspendUserAction();

    $action->run($user);

    expect($user->fresh()->is_suspended)->toBeTrue();
});

// ─── UnsuspendUserAction ─────────────────────────────────────────────────

it('unsuspend action unsuspends a suspended user', function () {
    $user   = User::factory()->suspended()->create();
    $action = new UnsuspendUserAction();

    $action->run($user);

    expect($user->fresh()->is_suspended)->toBeFalse();
});

it('unsuspend action persists change to database', function () {
    $user   = User::factory()->suspended()->create();
    $action = new UnsuspendUserAction();

    $action->run($user);

    $this->assertDatabaseHas('users', [
        'id'           => $user->id,
        'is_suspended' => false,
    ]);
});

it('unsuspend action throws when user is not suspended', function () {
    $user   = User::factory()->create(['is_suspended' => false]);
    $action = new UnsuspendUserAction();

    $action->run($user);
})->throws(BusinessException::class, 'User is not suspended.');

it('unsuspend action throws with 422 status code for non suspended', function () {
    $user   = User::factory()->create(['is_suspended' => false]);
    $action = new UnsuspendUserAction();

    try {
        $action->run($user);
        $this->fail('Expected BusinessException was not thrown.');
    } catch (BusinessException $e) {
        expect($e->getStatusCode())->toBe(422);
    }
});

it('suspend then unsuspend cycle works correctly', function () {
    $user      = User::factory()->create(['is_suspended' => false]);
    $suspend   = new SuspendUserAction();
    $unsuspend = new UnsuspendUserAction();

    $suspend->run($user);
    expect($user->fresh()->is_suspended)->toBeTrue();

    $unsuspend->run($user->fresh());
    expect($user->fresh()->is_suspended)->toBeFalse();
});
