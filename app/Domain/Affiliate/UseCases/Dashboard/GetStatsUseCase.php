<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\UseCases\Dashboard;

use App\Domain\Affiliate\Actions\GetAffiliateDashboardStatsAction;
use App\Domain\Affiliate\Actions\ResolveCurrentAffiliateAction;

class GetStatsUseCase
{
    public function __construct(
        private readonly ResolveCurrentAffiliateAction $resolveAffiliate,
        private readonly GetAffiliateDashboardStatsAction $getStats,
    ) {}

    public function run(int|string $userId): array
    {
        $affiliate = $this->resolveAffiliate->run($userId);

        return $this->getStats->run($affiliate);
    }
}
