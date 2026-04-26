<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases\Settings;

use App\Domain\Seller\Actions\ResolveCurrentSellerAction;
use App\Domain\Seller\Actions\SaveSellerBankAccountAction;
use App\Http\Resources\Seller\SellerProfileResource;
use App\Models\User;

class SaveBankAccountUseCase
{
    public function __construct(
        private readonly ResolveCurrentSellerAction $resolveSeller,
        private readonly SaveSellerBankAccountAction $saveAccount,
    ) {}

    public function run(User $user, array $data): SellerProfileResource
    {
        $seller = $this->resolveSeller->run($user->id);

        return new SellerProfileResource(
            $this->saveAccount->run($seller, $data)->load('user')
        );
    }
}
