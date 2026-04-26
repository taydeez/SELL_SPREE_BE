<?php

declare(strict_types=1);

namespace App\Domain\Auth\UseCases;

use App\Domain\Auth\Actions\FindOrCreateSocialUserAction;
use App\Domain\Auth\Actions\IssueJwtForUserAction;
use App\Domain\Auth\Actions\ResolveAffiliateProfileAction;
use App\Domain\Auth\Actions\ResolveSellerProfileAction;
use App\Enums\UserRole;
use App\Exceptions\BusinessException;
use App\Http\Resources\Affiliate\AffiliateProfileResource;
use App\Http\Resources\Seller\SellerProfileResource;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Contracts\User as SocialUser;

class SocialLoginUseCase
{
    public function __construct(
        private readonly FindOrCreateSocialUserAction $findOrCreate,
        private readonly IssueJwtForUserAction $issueJwt,
        private readonly ResolveSellerProfileAction $resolveSeller,
        private readonly ResolveAffiliateProfileAction $resolveAffiliate,
    ) {}

    public function run(SocialUser $socialUser, UserRole $role): array
    {
        $guard = $role->value;

        $user = $this->findOrCreate->run($socialUser, $role);

        if ($user->is_suspended) {
            throw BusinessException::suspended();
        }

        $jwt = $this->issueJwt->run($user, $guard);

        $profile = match ($role) {
            UserRole::Seller    => new SellerProfileResource($this->resolveSeller->run($user->id)),
            UserRole::Affiliate => new AffiliateProfileResource($this->resolveAffiliate->run($user->id)),
            UserRole::Customer  => $user,
        };

        return [
            ...$jwt,
            'portal' => $guard,
            'roles'  => $user->roles,
            'user'   => $profile,
        ];
    }
}
