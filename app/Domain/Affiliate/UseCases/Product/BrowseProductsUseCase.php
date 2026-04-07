<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\UseCases\Product;

use App\Domain\Affiliate\Actions\BrowseProductsAction;
use App\Domain\Affiliate\Actions\ResolveCurrentAffiliateAction;
use App\Http\Resources\Affiliate\BrowsableProductResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BrowseProductsUseCase
{
    public function __construct(
        private readonly ResolveCurrentAffiliateAction $resolveAffiliate,
        private readonly BrowseProductsAction $browseProducts,
    ) {}

    public function run(int|string $userId, array $filters): ResourceCollection
    {
        $affiliate = $this->resolveAffiliate->run($userId);

        return BrowsableProductResource::collection(
            $this->browseProducts->run($affiliate, $filters['type'] ?? null, $filters['search'] ?? null)
        );
    }
}
