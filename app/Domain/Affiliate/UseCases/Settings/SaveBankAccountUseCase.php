<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\UseCases\Settings;

use App\Domain\Affiliate\Actions\ResolveCurrentAffiliateAction;
use App\Domain\Affiliate\Actions\SaveAffiliateBankAccountAction;
use App\Models\Affiliate;
use App\Models\User;

class SaveBankAccountUseCase
{
    public function __construct(
        private readonly ResolveCurrentAffiliateAction $resolveAffiliate,
        private readonly SaveAffiliateBankAccountAction $saveBank,
    ) {}

    public function run(User $user, array $data): Affiliate
    {
        $affiliate = $this->resolveAffiliate->run($user->id);

        return $this->saveBank->run($affiliate, $data);
    }
}
