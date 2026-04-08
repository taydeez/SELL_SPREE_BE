<?php

declare(strict_types=1);

namespace App\Domain\Auth\UseCases;

use App\Domain\Auth\Actions\AuthenticateUserAction;
use App\Domain\Auth\Actions\ResolveAffiliateProfileAction;
use App\Domain\Auth\Actions\ResolveSellerProfileAction;
use App\Exceptions\BusinessException;
use App\Http\Resources\Affiliate\AffiliateProfileResource;
use App\Http\Resources\Seller\SellerProfileResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UnifiedLoginUseCase
{
    public function __construct(
        private readonly AuthenticateUserAction $authenticate,
        private readonly ResolveSellerProfileAction $resolveSeller,
        private readonly ResolveAffiliateProfileAction $resolveAffiliate,
    ) {}

    public function run(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (! $user) {
            throw BusinessException::invalidCredentials();
        }

        if ($user->is_suspended) {
            throw BusinessException::suspended();
        }

        $portal = $user->active_role;

        if (! $portal || ! in_array($portal, ['seller', 'affiliate', 'customer'])) {
            throw BusinessException::invalidCredentials();
        }

        $token = $this->authenticate->run($email, $password, $portal);

        $profile = match ($portal) {
            'seller'    => new SellerProfileResource($this->resolveSeller->run($user->id)),
            'affiliate' => new AffiliateProfileResource($this->resolveAffiliate->run($user->id)),
            'customer'  => $user,
            default     => throw BusinessException::forbidden(),
        };

        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => Auth::guard($portal)->factory()->getTTL() * 60,
            'portal'       => $portal,
            'roles'        => $user->roles,
            'user'         => $profile,
        ];
    }
}
