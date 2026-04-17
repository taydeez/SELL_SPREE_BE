<?php

declare(strict_types=1);

namespace App\Domain\Admin\UseCases\Affiliate;

use App\Domain\Admin\Actions\Affiliate\ListAffiliateWithdrawalsAction;
use App\Http\Resources\Admin\AffiliateWithdrawalResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ListAffiliateWithdrawalsUseCase
{
    public function __construct(private readonly ListAffiliateWithdrawalsAction $action) {}

    public function run(?string $status): ResourceCollection
    {
        return AffiliateWithdrawalResource::collection($this->action->run($status));
    }
}
