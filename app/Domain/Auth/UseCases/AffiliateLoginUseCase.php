<?php

declare(strict_types=1);

namespace App\Domain\Auth\UseCases;

use App\Domain\Auth\Actions\ResolveAffiliateProfileAction;
use App\Enums\UserRole;
use App\Http\Resources\Affiliate\AffiliateProfileResource;

class AffiliateLoginUseCase
{
    public function __construct(
        private readonly LoginUseCase $loginUseCase,
        private readonly ResolveAffiliateProfileAction $resolveProfile,
    ) {}

    public function run(string $email, string $password): array
    {
        $result = $this->loginUseCase->run(
            email: $email,
            password: $password,
            role: UserRole::Affiliate,
            guard: 'affiliate',
        );

        $result['user'] = new AffiliateProfileResource($this->resolveProfile->run($result['user']->id));

        return $result;
    }
}
