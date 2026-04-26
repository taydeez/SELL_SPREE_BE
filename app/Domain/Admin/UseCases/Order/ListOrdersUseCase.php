<?php

declare(strict_types=1);

namespace App\Domain\Admin\UseCases\Order;

use App\Domain\Admin\Actions\Order\ListOrdersAction;
use App\Http\Resources\Admin\OrderResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ListOrdersUseCase
{
    public function __construct(private readonly ListOrdersAction $action) {}

    public function run(array $filters): ResourceCollection
    {
        return OrderResource::collection($this->action->run(
            $filters['status'] ?? null,
            $filters['provider'] ?? null,
            $filters['search'] ?? null,
        ));
    }
}
