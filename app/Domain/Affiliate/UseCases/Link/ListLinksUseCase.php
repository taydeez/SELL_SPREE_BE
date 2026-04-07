<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\UseCases\Link;

use App\Domain\Affiliate\Actions\ListAffiliateLinksAction;
use App\Domain\Affiliate\Actions\ResolveCurrentAffiliateAction;
use App\Http\Resources\Affiliate\AffiliateLinkResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ListLinksUseCase
{
    public function __construct(
        private readonly ResolveCurrentAffiliateAction $resolveAffiliate,
        private readonly ListAffiliateLinksAction $listLinks,
    ) {}

    public function run(int|string $userId, array $filters): ResourceCollection
    {
        $affiliate = $this->resolveAffiliate->run($userId);

        $active = isset($filters['active'])
            ? filter_var($filters['active'], FILTER_VALIDATE_BOOLEAN)
            : null;

        return AffiliateLinkResource::collection($this->listLinks->run($affiliate, $active));
    }
}
