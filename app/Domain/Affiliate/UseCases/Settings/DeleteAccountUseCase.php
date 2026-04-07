<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\UseCases\Settings;

use App\Domain\Affiliate\Actions\DeleteAffiliateAccountAction;
use App\Domain\Affiliate\Actions\ResolveCurrentAffiliateAction;
use App\Domain\Auth\Actions\RevokeTokenAction;
use App\Models\User;

class DeleteAccountUseCase
{
    public function __construct(
        private readonly ResolveCurrentAffiliateAction $resolveAffiliate,
        private readonly RevokeTokenAction $revokeToken,
        private readonly DeleteAffiliateAccountAction $deleteAccount,
    ) {}

    public function run(User $user): void
    {
        $affiliate = $this->resolveAffiliate->run($user->id);

        $this->revokeToken->run('affiliate');
        $this->deleteAccount->run($user, $affiliate);
    }
}
