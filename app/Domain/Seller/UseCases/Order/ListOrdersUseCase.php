<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases\Order;

use App\Domain\Seller\Actions\Order\ListSellerOrdersAction;
use App\Domain\Seller\Actions\ResolveCurrentSellerAction;
use App\Http\Resources\Seller\SellerOrderResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ListOrdersUseCase
{
    public function __construct(
        private readonly ResolveCurrentSellerAction $resolveSeller,
        private readonly ListSellerOrdersAction $listOrders,
    ) {}

    public function run(int|string $userId, array $filters): ResourceCollection
    {
        $seller = $this->resolveSeller->run($userId);

        return SellerOrderResource::collection(
            $this->listOrders->run($seller, $filters['status'] ?? null)
        );
    }
}
