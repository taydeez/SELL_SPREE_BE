<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases\Settings;

use App\Domain\Seller\Actions\ResolveCurrentSellerAction;
use App\Domain\Seller\Actions\UpdateSellerProfileAction;
use App\Http\Resources\Seller\SellerProfileResource;
use App\Models\User;
use Illuminate\Http\UploadedFile;

class UpdateProfileUseCase
{
    public function __construct(
        private readonly ResolveCurrentSellerAction $resolveSeller,
        private readonly UpdateSellerProfileAction $updateProfile,
    ) {}

    public function run(User $user, array $data, ?UploadedFile $avatar): SellerProfileResource
    {
        $seller = $this->resolveSeller->run($user->id);

        return new SellerProfileResource(
            $this->updateProfile->run($user, $seller, $data, $avatar)->load('user')
        );
    }
}
