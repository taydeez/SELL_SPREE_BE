<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\UseCases\Settings;

use App\Domain\Affiliate\Actions\DeleteAffiliateAccountAction;
use App\Domain\Affiliate\Actions\ResolveCurrentAffiliateAction;
use App\Domain\Auth\Actions\RevokeTokenAction;
use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Models\User;

class DeleteAccountUseCase
{
    public function __construct(
        private readonly ResolveCurrentAffiliateAction $resolveAffiliate,
        private readonly RevokeTokenAction $revokeToken,
        private readonly DeleteAffiliateAccountAction $deleteAccount,
        private readonly CreateAppLogAction $log,
    ) {}

    public function run(User $user): void
    {
        $affiliate = $this->resolveAffiliate->run($user->id);

        $this->log->run('warning', 'AFFILIATE_ACCOUNT_DELETED', 'Affiliate account deleted by user.', ['user_id' => $user->id, 'email' => $user->email, 'affiliate_id' => $affiliate->id]);

        $this->revokeToken->run('affiliate');
        $this->deleteAccount->run($user, $affiliate);
    }
}
