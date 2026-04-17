<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases;

use App\Domain\Affiliate\Actions\CreateAffiliateProfileAction;
use App\Domain\Auth\Actions\CreateUserAction;
use App\Domain\Seller\Actions\CreateSellerProfileAction;
use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Enums\UserRole;
use App\Events\Seller\SellerRegistered;
use App\Http\Resources\Seller\SellerProfileResource;
use Illuminate\Support\Facades\DB;

class RegisterSellerUseCase
{
    public function __construct(
        private readonly CreateUserAction $createUser,
        private readonly CreateSellerProfileAction $createProfile,
        private readonly CreateAffiliateProfileAction $createAffiliateProfile,
        private readonly CreateAppLogAction $log,
    ) {}

    public function run(array $data): SellerProfileResource
    {
        try {
            return DB::transaction(function () use ($data): SellerProfileResource {
                $user   = $this->createUser->run($data, UserRole::Seller);
                $seller = $this->createProfile->run($user, $data);

                // Ensure affiliate profile exists for role switching
                $this->createAffiliateProfile->run($user, []);

                $user->sendEmailVerificationNotification();
                SellerRegistered::dispatch($user, $seller);

                $this->log->run('info', 'SELLER_REGISTERED', "New seller registered: {$user->email}.", ['user_id' => $user->id, 'email' => $user->email]);

                return new SellerProfileResource($seller->load('user'));
            });
        } catch (\Throwable $e) {
            $this->log->run('error', 'SELLER_REGISTRATION_FAILED', 'Seller registration failed.', ['email' => $data['email'] ?? null, 'error' => $e->getMessage()]);
            throw $e;
        }
    }
}
