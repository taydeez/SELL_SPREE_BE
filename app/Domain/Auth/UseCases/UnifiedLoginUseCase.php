<?php

declare(strict_types=1);

namespace App\Domain\Auth\UseCases;

use App\Domain\Auth\Actions\AuthenticateUserAction;
use App\Domain\Auth\Actions\ResolveAffiliateProfileAction;
use App\Domain\Auth\Actions\ResolveSellerProfileAction;
use App\Domain\Shared\Actions\CreateAppLogAction;
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
        private readonly CreateAppLogAction $log,
    ) {}

    public function run(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->log->run('warning', 'LOGIN_FAILED', 'Login attempted with unknown email.', ['email' => $email]);
            throw BusinessException::invalidCredentials();
        }

        if ($user->is_suspended) {
            $this->log->run('warning', 'LOGIN_FAILED', 'Suspended user attempted login.', ['email' => $email, 'user_id' => $user->id]);
            throw BusinessException::suspended();
        }

        $portal = $user->active_role;

        if (! $portal || ! in_array($portal, ['seller', 'affiliate', 'customer'])) {
            $this->log->run('warning', 'LOGIN_FAILED', 'Login attempted with invalid portal role.', ['email' => $email, 'user_id' => $user->id, 'role' => $portal]);
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
