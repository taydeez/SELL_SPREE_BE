<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions;

use App\Domain\Affiliate\Actions\CreateAffiliateProfileAction;
use App\Domain\Seller\Actions\CreateSellerProfileAction;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Contracts\User as SocialUser;

class FindOrCreateSocialUserAction
{
    public function __construct(
        private readonly CreateSellerProfileAction $createSeller,
        private readonly CreateAffiliateProfileAction $createAffiliate,
    ) {}

    public function run(SocialUser $socialUser, UserRole $role): User
    {
        return DB::transaction(function () use ($socialUser, $role): User {
            $user = User::where('google_id', $socialUser->getId())->first()
                ?? User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                $user->update([
                    'google_id'     => $socialUser->getId(),
                    'social_avatar' => $socialUser->getAvatar(),
                ]);

                return $user;
            }

            $user = User::create([
                'name'          => $socialUser->getName(),
                'email'         => $socialUser->getEmail(),
                'google_id'     => $socialUser->getId(),
                'social_avatar' => $socialUser->getAvatar(),
                'email_verified_at' => now(),
                'active_role'   => $role->value,
                'roles'         => ['seller', 'affiliate', 'customer'],
            ]);

            $this->createSeller->run($user, []);
            $this->createAffiliate->run($user, []);

            return $user;
        });
    }
}
