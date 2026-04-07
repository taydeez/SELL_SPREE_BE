<?php

declare(strict_types=1);

namespace App\Domain\Auth\UseCases;

use App\Domain\Auth\Actions\ResolveSellerProfileAction;
use App\Enums\UserRole;
use App\Http\Resources\Seller\SellerProfileResource;

class SellerLoginUseCase
{
    public function __construct(
        private readonly LoginUseCase $loginUseCase,
        private readonly ResolveSellerProfileAction $resolveProfile,
    ) {}

    public function run(string $email, string $password): array
    {
        $result = $this->loginUseCase->run(
            email: $email,
            password: $password,
            role: UserRole::Seller,
            guard: 'seller',
        );

        $result['user'] = new SellerProfileResource($this->resolveProfile->run($result['user']->id));

        return $result;
    }
}
