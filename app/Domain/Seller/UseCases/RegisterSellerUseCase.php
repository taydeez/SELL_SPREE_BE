<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases;

use App\Domain\Auth\Actions\CreateUserAction;
use App\Domain\Seller\Actions\CreateSellerProfileAction;
use App\Enums\UserRole;
use App\Events\Seller\SellerRegistered;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RegisterSellerUseCase
{
    public function __construct(
        private readonly CreateUserAction $createUser,
        private readonly CreateSellerProfileAction $createProfile,
    ) {}

    public function run(array $data): array
    {
        return DB::transaction(function () use ($data): array {
            /** @var User $user */
            $user = $this->createUser->run($data, UserRole::Seller);

            $user->sendEmailVerificationNotification();

            /** @var Seller $seller */
            $seller = $this->createProfile->run($user, $data);

            SellerRegistered::dispatch($user, $seller);

            return compact('user', 'seller');
        });
    }
}
