<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\UseCases\Earnings;

use App\Domain\Affiliate\Actions\GetAffiliateEarningsAction;
use App\Domain\Affiliate\Actions\ResolveCurrentAffiliateAction;
use App\Http\Resources\Affiliate\EarningsResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GetEarningsUseCase
{
    public function __construct(
        private readonly ResolveCurrentAffiliateAction $resolveAffiliate,
        private readonly GetAffiliateEarningsAction $getEarnings,
    ) {}

    public function run(int|string $userId, array $filters): ResourceCollection
    {
        $affiliate = $this->resolveAffiliate->run($userId);

        $paid = isset($filters['paid'])
            ? filter_var($filters['paid'], FILTER_VALIDATE_BOOLEAN)
            : null;

        return EarningsResource::collection($this->getEarnings->run($affiliate, $paid));
    }
}
