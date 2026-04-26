<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\UseCases\Link;

use App\Domain\Affiliate\Actions\GenerateAffiliateLinkAction;
use App\Domain\Affiliate\Actions\ResolveCurrentAffiliateAction;
use App\Http\Resources\Affiliate\AffiliateLinkResource;
use App\Models\Product;

class GenerateLinkUseCase
{
    public function __construct(
        private readonly ResolveCurrentAffiliateAction $resolveAffiliate,
        private readonly GenerateAffiliateLinkAction $generateLink,
    ) {}

    public function run(int|string $userId, string $productId): AffiliateLinkResource
    {
        $affiliate = $this->resolveAffiliate->run($userId);
        $product   = Product::with('seller')->findOrFail($productId);

        return new AffiliateLinkResource(
            $this->generateLink->run($affiliate, $product)->load('product')
        );
    }
}
