<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\UseCases;

use App\Domain\Affiliate\Actions\CreateAffiliateProfileAction;
use App\Domain\Auth\Actions\CreateUserAction;
use App\Domain\Seller\Actions\CreateSellerProfileAction;
use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Enums\UserRole;
use App\Events\Affiliate\AffiliateRegistered;
use App\Http\Resources\Affiliate\AffiliateProfileResource;
use Illuminate\Support\Facades\DB;

class RegisterAffiliateUseCase
{
    public function __construct(
        private readonly CreateUserAction $createUser,
        private readonly CreateAffiliateProfileAction $createProfile,
        private readonly CreateSellerProfileAction $createSellerProfile,
        private readonly CreateAppLogAction $log,
    ) {}

    public function run(array $data): AffiliateProfileResource
    {
        try {
            return DB::transaction(function () use ($data): AffiliateProfileResource {
                $user      = $this->createUser->run($data, UserRole::Affiliate);
                $affiliate = $this->createProfile->run($user, $data);

                // Ensure seller profile exists for role switching
                $this->createSellerProfile->run($user, []);

                $user->sendEmailVerificationNotification();
                AffiliateRegistered::dispatch($user, $affiliate);

                $this->log->run('info', 'AFFILIATE_REGISTERED', "New affiliate registered: {$user->email}.", ['user_id' => $user->id, 'email' => $user->email]);

                return new AffiliateProfileResource($affiliate->load('user'));
            });
        } catch (\Throwable $e) {
            $this->log->run('error', 'AFFILIATE_REGISTRATION_FAILED', 'Affiliate registration failed.', ['email' => $data['email'] ?? null, 'error' => $e->getMessage()]);
            throw $e;
        }
    }
}
