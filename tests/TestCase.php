<?php

declare(strict_types=1);

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Authenticate as the given user against the given guard and return $this
     * for fluent chaining. Uses Laravel's actingAs() under the hood so no
     * real JWT token is generated — the guard is mocked for the request.
     */
    protected function loginAs(User $user, string $guard): static
    {
        return $this->actingAs($user, $guard);
    }
}
