<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\UseCases;

use App\Domain\Affiliate\Actions\CreateAffiliateProfileAction;
use App\Domain\Auth\Actions\CreateUserAction;
use App\Enums\UserRole;
use App\Events\Affiliate\AffiliateRegistered;
use App\Models\Affiliate;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RegisterAffiliateUseCase
{
    public function __construct(
        private readonly CreateUserAction $createUser,
        private readonly CreateAffiliateProfileAction $createProfile,
    ) {}

    public function run(array $data): array
    {
        return DB::transaction(function () use ($data): array {
            /** @var User $user */
            $user = $this->createUser->run($data, UserRole::Affiliate);

            $user->sendEmailVerificationNotification();

            /** @var Affiliate $affiliate */
            $affiliate = $this->createProfile->run($user, $data);

            AffiliateRegistered::dispatch($user, $affiliate);

            return compact('user', 'affiliate');
        });
    }
}
