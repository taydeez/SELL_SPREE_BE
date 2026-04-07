<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\UseCases;

use App\Domain\Affiliate\Actions\CreateAffiliateProfileAction;
use App\Domain\Auth\Actions\CreateUserAction;
use App\Enums\UserRole;
use App\Events\Affiliate\AffiliateRegistered;
use App\Http\Resources\Affiliate\AffiliateProfileResource;
use Illuminate\Support\Facades\DB;

class RegisterAffiliateUseCase
{
    public function __construct(
        private readonly CreateUserAction $createUser,
        private readonly CreateAffiliateProfileAction $createProfile,
    ) {}

    public function run(array $data): AffiliateProfileResource
    {
        return DB::transaction(function () use ($data): AffiliateProfileResource {
            $user      = $this->createUser->run($data, UserRole::Affiliate);
            $affiliate = $this->createProfile->run($user, $data);

            $user->sendEmailVerificationNotification();
            AffiliateRegistered::dispatch($user, $affiliate);

            return new AffiliateProfileResource($affiliate->load('user'));
        });
    }
}
