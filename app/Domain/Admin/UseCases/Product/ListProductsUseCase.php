<?php

declare(strict_types=1);

namespace App\Domain\Admin\UseCases\Product;

use App\Domain\Admin\Actions\Product\ListProductsAction;
use App\Http\Resources\Admin\ProductListResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ListProductsUseCase
{
    public function __construct(private readonly ListProductsAction $action) {}

    public function run(array $filters): ResourceCollection
    {
        return ProductListResource::collection($this->action->run(
            $filters['search'] ?? null,
            $filters['status'] ?? null,
            $filters['type'] ?? null,
        ));
    }
}
