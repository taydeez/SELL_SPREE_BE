<?php

declare(strict_types=1);

namespace App\Domain\Admin\UseCases\Affiliate;

use App\Domain\Admin\Actions\Affiliate\VerifyAffiliatePayoutAction;
use App\Http\Resources\Admin\AffiliateWithdrawalResource;
use App\Models\AffiliateWithdrawal;

class VerifyAffiliatePayoutUseCase
{
    public function __construct(private readonly VerifyAffiliatePayoutAction $action) {}

    public function run(AffiliateWithdrawal $withdrawal): AffiliateWithdrawalResource
    {
        return new AffiliateWithdrawalResource($this->action->run($withdrawal));
    }
}
