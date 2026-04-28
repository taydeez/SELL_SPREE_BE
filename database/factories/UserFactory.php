<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name'              => $this->faker->name(),
            'email'             => $this->faker->unique()->safeEmail(),
            'password'          => static::$password ??= Hash::make('password'),
            'active_role'       => 'seller',
            'roles'             => ['seller', 'affiliate', 'customer'],
            'email_verified_at' => now(),
            'is_suspended'      => false,
            'remember_token'    => Str::random(10),
        ];
    }

    public function seller(): static
    {
        return $this->state([
            'active_role' => 'seller',
            'roles'       => ['seller', 'affiliate', 'customer'],
        ]);
    }

    public function affiliate(): static
    {
        return $this->state([
            'active_role' => 'affiliate',
            'roles'       => ['seller', 'affiliate', 'customer'],
        ]);
    }

    public function customer(): static
    {
        return $this->state([
            'active_role' => 'customer',
            'roles'       => ['seller', 'affiliate', 'customer'],
        ]);
    }

    public function suspended(): static
    {
        return $this->state(['is_suspended' => true]);
    }

    public function unverified(): static
    {
        return $this->state(['email_verified_at' => null]);
    }
}
