<?php

declare(strict_types=1);

namespace App\Domain\Admin\UseCases\Affiliate;

use App\Domain\Admin\Actions\Affiliate\ListAffiliatesAction;
use App\Http\Resources\Admin\AffiliateListResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ListAffiliatesUseCase
{
    public function __construct(private readonly ListAffiliatesAction $action) {}

    public function run(array $filters): ResourceCollection
    {
        return AffiliateListResource::collection(
            $this->action->run($filters['search'] ?? null)
        );
    }
}
