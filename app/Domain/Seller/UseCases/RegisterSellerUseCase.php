<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases;

use App\Domain\Auth\Actions\CreateUserAction;
use App\Domain\Seller\Actions\CreateSellerProfileAction;
use App\Enums\UserRole;
use App\Events\Seller\SellerRegistered;
use App\Http\Resources\Seller\SellerProfileResource;
use Illuminate\Support\Facades\DB;

class RegisterSellerUseCase
{
    public function __construct(
        private readonly CreateUserAction $createUser,
        private readonly CreateSellerProfileAction $createProfile,
    ) {}

    public function run(array $data): SellerProfileResource
    {
        return DB::transaction(function () use ($data): SellerProfileResource {
            $user   = $this->createUser->run($data, UserRole::Seller);
            $seller = $this->createProfile->run($user, $data);

            $user->sendEmailVerificationNotification();
            SellerRegistered::dispatch($user, $seller);

            return new SellerProfileResource($seller->load('user'));
        });
    }
}
