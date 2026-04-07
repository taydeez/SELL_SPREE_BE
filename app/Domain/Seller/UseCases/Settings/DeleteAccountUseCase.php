<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases\Settings;

use App\Domain\Auth\Actions\RevokeTokenAction;
use App\Domain\Seller\Actions\DeleteSellerAccountAction;
use App\Domain\Seller\Actions\ResolveCurrentSellerAction;
use App\Models\User;

class DeleteAccountUseCase
{
    public function __construct(
        private readonly ResolveCurrentSellerAction $resolveSeller,
        private readonly RevokeTokenAction $revokeToken,
        private readonly DeleteSellerAccountAction $deleteAccount,
    ) {}

    public function run(User $user): void
    {
        $seller = $this->resolveSeller->run($user->id);

        $this->revokeToken->run('seller');
        $this->deleteAccount->run($user, $seller);
    }
}
