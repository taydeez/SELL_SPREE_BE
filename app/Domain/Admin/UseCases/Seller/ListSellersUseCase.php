<?php

declare(strict_types=1);

namespace App\Domain\Admin\UseCases\Seller;

use App\Domain\Admin\Actions\Seller\ListSellersAction;
use App\Http\Resources\Admin\SellerListResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ListSellersUseCase
{
    public function __construct(private readonly ListSellersAction $action) {}

    public function run(array $filters): ResourceCollection
    {
        return SellerListResource::collection($this->action->run(
            $filters['search'] ?? null,
            $filters['status'] ?? null,
        ));
    }
}
