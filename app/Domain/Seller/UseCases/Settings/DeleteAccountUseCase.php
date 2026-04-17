<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases\Settings;

use App\Domain\Auth\Actions\RevokeTokenAction;
use App\Domain\Seller\Actions\DeleteSellerAccountAction;
use App\Domain\Seller\Actions\ResolveCurrentSellerAction;
use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Models\User;

class DeleteAccountUseCase
{
    public function __construct(
        private readonly ResolveCurrentSellerAction $resolveSeller,
        private readonly RevokeTokenAction $revokeToken,
        private readonly DeleteSellerAccountAction $deleteAccount,
        private readonly CreateAppLogAction $log,
    ) {}

    public function run(User $user): void
    {
        $seller = $this->resolveSeller->run($user->id);

        $this->log->run('warning', 'SELLER_ACCOUNT_DELETED', 'Seller account deleted by user.', ['user_id' => $user->id, 'email' => $user->email, 'seller_id' => $seller->id]);

        $this->revokeToken->run('seller');
        $this->deleteAccount->run($user, $seller);
    }
}
