<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases\Dashboard;

use App\Domain\Seller\Actions\GetSellerDashboardStatsAction;
use App\Domain\Seller\Actions\ResolveCurrentSellerAction;

class GetStatsUseCase
{
    public function __construct(
        private readonly ResolveCurrentSellerAction $resolveSeller,
        private readonly GetSellerDashboardStatsAction $getStats,
    ) {}

    public function run(int|string $userId): array
    {
        $seller = $this->resolveSeller->run($userId);

        return $this->getStats->run($seller);
    }
}
