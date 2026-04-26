<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\UseCases\Earnings;

use App\Domain\Affiliate\Actions\RequestWithdrawalAction;
use App\Domain\Affiliate\Actions\ResolveCurrentAffiliateAction;
use App\Models\AffiliateWithdrawal;
use App\Models\User;

class RequestWithdrawalUseCase
{
    public function __construct(
        private readonly ResolveCurrentAffiliateAction $resolveAffiliate,
        private readonly RequestWithdrawalAction $request,
    ) {}

    public function run(User $user): AffiliateWithdrawal
    {
        $affiliate = $this->resolveAffiliate->run($user->id);

        return $this->request->run($affiliate);
    }
}
